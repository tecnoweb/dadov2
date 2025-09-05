# Grid View Persistence & Theme Mode System

## 🎯 Obiettivo
Implementare un sistema robusto per la persistenza della modalità di visualizzazione (grid/list) che sia integrato nel core di xCrudRevolution piuttosto che essere un hack del tema.

## 💡 Idea Proposta: `theme_mode()`

### Sintassi Base
```php
// Imposta la modalità di visualizzazione predefinita
$xcrud->theme_mode('grid');  // o 'list', 'cards', 'masonry', etc.

// Con campo titolo per grid view
$xcrud->theme_mode('grid', 'firstName');

// Con opzioni avanzate
$xcrud->theme_mode('grid', [
    'title_field' => 'firstName',
    'subtitle_field' => 'jobTitle', 
    'image_field' => 'photo',
    'max_fields' => 5,
    'card_style' => 'compact'  // o 'full', 'minimal'
]);
```

## 🏗️ Architettura Proposta

### 1. Core Integration
```php
class Xcrud {
    protected $view_mode = 'list';  // Default
    protected $view_mode_config = [];
    protected $allowed_view_modes = ['list', 'grid', 'cards', 'masonry', 'kanban'];
    
    /**
     * Set the theme view mode and configuration
     * 
     * @param string $mode View mode (list, grid, cards, etc.)
     * @param mixed $config String field name or array of options
     * @return self
     */
    public function theme_mode(string $mode, $config = null): self {
        if (!in_array($mode, $this->allowed_view_modes)) {
            throw new Exception("Invalid view mode: {$mode}");
        }
        
        $this->view_mode = $mode;
        
        if (is_string($config)) {
            $this->view_mode_config = ['title_field' => $config];
        } elseif (is_array($config)) {
            $this->view_mode_config = $config;
        }
        
        // Trigger hook for custom handling
        HookManager::trigger('xcrud.theme_mode.change', $mode, $config, $this);
        
        return $this;
    }
    
    /**
     * Get current view mode
     */
    public function get_view_mode(): string {
        // Check session override
        if (isset($_SESSION['xcrud_view_mode'][$this->instance_name])) {
            return $_SESSION['xcrud_view_mode'][$this->instance_name];
        }
        return $this->view_mode;
    }
}
```

### 2. Session Persistence Layer
```php
class ViewModeManager {
    /**
     * Store user's view mode preference
     */
    public static function saveUserPreference($instance, $mode) {
        $_SESSION['xcrud_view_mode'][$instance] = $mode;
        
        // Opzionale: salvare anche nel database per persistenza cross-session
        if (Xcrud_config::$persist_view_preferences && $user_id = self::getUserId()) {
            Xcrud_db::get_instance()->query(
                "INSERT INTO xcrud_user_preferences (user_id, instance, view_mode) 
                 VALUES (?, ?, ?) 
                 ON DUPLICATE KEY UPDATE view_mode = ?",
                [$user_id, $instance, $mode, $mode]
            );
        }
    }
    
    /**
     * Get user's saved preference
     */
    public static function getUserPreference($instance) {
        // Prima controlla la sessione
        if (isset($_SESSION['xcrud_view_mode'][$instance])) {
            return $_SESSION['xcrud_view_mode'][$instance];
        }
        
        // Poi controlla il database
        if (Xcrud_config::$persist_view_preferences && $user_id = self::getUserId()) {
            $result = Xcrud_db::get_instance()->query(
                "SELECT view_mode FROM xcrud_user_preferences 
                 WHERE user_id = ? AND instance = ?",
                [$user_id, $instance]
            );
            if ($row = $result->row()) {
                return $row['view_mode'];
            }
        }
        
        return null;
    }
}
```

### 3. Hook System Integration
```php
// Registrare hooks per gestire cambio modalità
HookManager::register('xcrud.request.pre', function($data, $xcrud) {
    // Intercetta richieste di cambio modalità
    if (isset($data['set_view_mode'])) {
        ViewModeManager::saveUserPreference(
            $xcrud->instance_name, 
            $data['set_view_mode']
        );
    }
});

// Hook per modificare il rendering basato sulla modalità
HookManager::register('xcrud.render.list', function($output, $xcrud) {
    $mode = $xcrud->get_view_mode();
    
    if ($mode === 'grid') {
        // Modifica l'output per grid view
        return GridViewRenderer::render($xcrud);
    }
    
    return $output;
});
```

### 4. Theme Templates
```php
// In xcrud_list_view.php
<?php 
$view_mode = $this->get_view_mode();
$view_config = $this->get_view_mode_config();
?>

<div class="xcrud-view-switcher">
    <button data-mode="list" class="<?= $view_mode == 'list' ? 'active' : '' ?>">
        <i class="icon-list"></i> List
    </button>
    <button data-mode="grid" class="<?= $view_mode == 'grid' ? 'active' : '' ?>">
        <i class="icon-grid"></i> Grid
    </button>
    <?php if ($this->allow_cards_view): ?>
    <button data-mode="cards" class="<?= $view_mode == 'cards' ? 'active' : '' ?>">
        <i class="icon-cards"></i> Cards
    </button>
    <?php endif; ?>
</div>

<?php 
// Render basato sulla modalità
switch($view_mode) {
    case 'grid':
        include 'partials/grid_view.php';
        break;
    case 'cards':
        include 'partials/cards_view.php';
        break;
    default:
        include 'partials/list_view.php';
}
?>
```

### 5. JavaScript Handler
```javascript
// Nuovo modulo ES6 per gestione view mode
export class ViewModeManager {
    constructor(xcrud) {
        this.xcrud = xcrud;
        this.currentMode = this.loadMode();
        this.init();
    }
    
    init() {
        // Gestisce click sui pulsanti di switch
        document.querySelectorAll('.xcrud-view-switcher button').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const mode = e.currentTarget.dataset.mode;
                this.setMode(mode);
            });
        });
        
        // Intercetta tutte le richieste AJAX per includere la modalità
        this.xcrud.on('beforeRequest', (data) => {
            data.view_mode = this.currentMode;
            return data;
        });
    }
    
    setMode(mode) {
        this.currentMode = mode;
        
        // Salva in localStorage per persistenza client-side immediata
        localStorage.setItem(`xcrud_view_${this.xcrud.instance}`, mode);
        
        // Notifica il server
        this.xcrud.request({
            task: 'set_view_mode',
            mode: mode
        });
        
        // Ricarica la vista
        this.xcrud.reload();
    }
    
    loadMode() {
        // Priorità: URL param > localStorage > server default
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('view')) {
            return urlParams.get('view');
        }
        
        const saved = localStorage.getItem(`xcrud_view_${this.xcrud.instance}`);
        if (saved) {
            return saved;
        }
        
        return this.xcrud.defaultViewMode || 'list';
    }
}
```

## 🎨 Creazione Nuovi Temi

### Struttura Tema Avanzata
```
themes/[theme_name]/
├── theme.json          # Configurazione tema (sostituisce INI)
├── assets/
│   ├── css/
│   │   ├── main.css
│   │   ├── grid.css    # Stili specifici per grid view
│   │   ├── cards.css   # Stili per cards view
│   │   └── dark.css    # Dark mode
│   └── js/
│       └── theme.js    # JS specifico del tema
├── templates/
│   ├── container.php
│   ├── list_view.php
│   ├── detail_view.php
│   └── partials/       # Template parziali
│       ├── grid_view.php
│       ├── cards_view.php
│       ├── kanban_view.php
│       └── masonry_view.php
└── components/         # Componenti riutilizzabili
    ├── card.php
    ├── toolbar.php
    └── filters.php
```

### Theme Configuration (theme.json)
```json
{
    "name": "Revolution",
    "version": "2.0.0",
    "author": "xCrudRevolution",
    "description": "Modern theme with multiple view modes",
    "supports": {
        "view_modes": ["list", "grid", "cards", "masonry"],
        "dark_mode": true,
        "responsive": true,
        "rtl": true
    },
    "default_view_mode": "list",
    "assets": {
        "css": [
            "assets/css/main.css",
            {
                "file": "assets/css/grid.css",
                "condition": "view_mode:grid"
            },
            {
                "file": "assets/css/dark.css",
                "condition": "dark_mode:true"
            }
        ],
        "js": ["assets/js/theme.js"]
    },
    "config": {
        "grid": {
            "columns": 3,
            "gap": "20px",
            "card_height": "auto"
        },
        "masonry": {
            "column_width": 300,
            "gutter": 20
        }
    }
}
```

## 🚀 Vantaggi del Sistema

1. **Persistenza Multi-Livello**
   - Session storage per persistenza durante la sessione
   - LocalStorage per persistenza client-side
   - Database per preferenze utente permanenti
   
2. **Estensibilità**
   - Facile aggiunta di nuove modalità di visualizzazione
   - Hooks per personalizzazione completa
   - Temi possono definire proprie modalità custom

3. **Performance**
   - Lazy loading dei CSS specifici per modalità
   - Caching delle preferenze
   - Rendering ottimizzato per ogni modalità

4. **Developer Experience**
   - API semplice e intuitiva
   - Documentazione inline
   - Esempi pronti all'uso

## 📋 Implementazione Step-by-Step

### Fase 1: Core (dopo implementazione hooks)
1. Aggiungere proprietà `view_mode` a Xcrud
2. Implementare metodo `theme_mode()`
3. Creare ViewModeManager

### Fase 2: Storage
1. Implementare persistenza in sessione
2. Aggiungere tabella preferenze utente (opzionale)
3. Implementare localStorage handler

### Fase 3: Rendering
1. Modificare render_list() per supportare modalità
2. Creare renderer specifici per ogni modalità
3. Aggiornare template themes

### Fase 4: JavaScript
1. Creare modulo ViewModeManager
2. Integrare con Xcrud.js
3. Implementare switch UI

### Fase 5: Temi
1. Aggiornare Revolution theme
2. Creare template per ogni modalità
3. Documentare per altri sviluppatori

## 🎯 Esempi di Utilizzo

```php
// Setup base
$xcrud = Xcrud::get_instance();
$xcrud->table('employees');
$xcrud->theme('revolution');
$xcrud->theme_mode('grid', 'firstName');

// Con configurazione avanzata
$xcrud->theme_mode('cards', [
    'title_field' => 'fullName',
    'subtitle_field' => 'position',
    'image_field' => 'avatar',
    'fields' => ['email', 'phone', 'department'],
    'actions' => ['view', 'edit'],
    'card_width' => '300px'
]);

// Kanban view per project management
$xcrud->table('tasks');
$xcrud->theme_mode('kanban', [
    'group_by' => 'status',
    'title_field' => 'task_name',
    'order' => ['todo', 'in_progress', 'review', 'done'],
    'draggable' => true,
    'on_drop' => 'updateTaskStatus'
]);

// Masonry per galleria
$xcrud->table('gallery');
$xcrud->theme_mode('masonry', [
    'image_field' => 'image_url',
    'title_field' => 'caption',
    'columns' => 4,
    'lightbox' => true
]);
```

## 🔮 Future Enhancements

1. **AI-Powered View Selection**
   - Auto-detect best view mode based on data type
   - Learn user preferences over time

2. **Custom View Builder**
   - Visual editor per creare view personalizzate
   - Drag & drop field positioning

3. **Export View Templates**
   - Esporta configurazioni view come template
   - Marketplace per condividere view custom

4. **Responsive Auto-Switch**
   - Cambia automaticamente view basato su screen size
   - Configurazione per breakpoints

---

**Nota:** Questa implementazione sarà realizzata DOPO il completamento del sistema di hooks, che fornirà l'infrastruttura necessaria per un'integrazione pulita e modulare.