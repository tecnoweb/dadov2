# 🎨 xCrudRevolution Theme Creation Guide

## 📚 Indice
1. [Introduzione](#introduzione)
2. [Struttura di un Tema](#struttura-di-un-tema)
3. [File Essenziali](#file-essenziali)
4. [Accesso ai Dati](#accesso-ai-dati)
5. [Creazione Step-by-Step](#creazione-step-by-step)
6. [Best Practices](#best-practices)
7. [Esempi di Temi](#esempi-di-temi)

## Introduzione

I temi in xCrudRevolution sono completamente customizzabili e permettono di trasformare radicalmente l'aspetto e il comportamento dell'interfaccia CRUD. Dopo aver sviluppato il tema Revolution, ho acquisito una comprensione profonda del sistema che voglio condividere.

## 🏗️ Struttura di un Tema

```
themes/[nome_tema]/
├── xcrud.ini              # Configurazione tema (presto JSON)
├── xcrud.css              # Stili principali
├── xcrud_container.php    # Container wrapper
├── xcrud_list_view.php    # Vista lista/griglia
├── xcrud_detail_view.php  # Vista dettaglio (create/edit/view)
├── fonts.css              # (Opzionale) Font e icone custom
├── assets/                # (Opzionale) Risorse aggiuntive
│   ├── images/
│   ├── js/
│   └── fonts/
└── README.md              # Documentazione tema
```

## 📄 File Essenziali

### 1. xcrud.ini (Configurazione)
```ini
[icons]
; Definisce le icone per ogni azione
add = "<i class='icon-plus'></i>"
edit = "<i class='icon-edit'></i>"
remove = "<i class='icon-trash'></i>"
view = "<i class='icon-eye'></i>"
csv = "<i class='icon-download'></i>"
print = "<i class='icon-print'></i>"
save = "<i class='icon-check'></i>"
return = "<i class='icon-arrow-left'></i>"
search = "<i class='icon-search'></i>"

[visual]
; Configurazioni visive
button_class = "btn"
modal_fade = 1
animate_alerts = 1
```

### 2. xcrud_container.php
Il container è il wrapper principale che avvolge tutto xCrud:

```php
<div class="xcrud<?php echo $this->is_rtl ? ' xcrud_rtl' : ''?>" 
     data-instance="<?php echo $this->instance_name ?>">
    
    <!-- Gestione stato view (importante per persistenza) -->
    <?php 
    $current_view = isset($_REQUEST['view']) ? $_REQUEST['view'] : 'table';
    ?>
    <input type="hidden" class="xcrud-view-mode" name="view" 
           value="<?php echo htmlspecialchars($current_view); ?>" />
    
    <!-- Titolo tabella -->
    <?php echo $this->render_table_name(false, 'div', true)?> 
    
    <!-- Container principale -->
    <div class="xcrud-container"<?php echo ($this->start_minimized) ? ' style="display:none;"' : '' ?>>
        <div class="xcrud-ajax" id="xcrud-<?php echo $this->instance_name ?>">
            <?php echo $this->render_view() ?>
        </div>
        <div class="xcrud-overlay"></div>
    </div>
</div>
```

### 3. xcrud_list_view.php
La vista lista gestisce la visualizzazione dei dati in formato tabella o griglia:

```php
<?php 
// Determina modalità di visualizzazione
$is_grid_view = (isset($_GET['view']) && $_GET['view'] == 'grid') || 
                (isset($_POST['view']) && $_POST['view'] == 'grid');
?>

<div class="theme-container">
    <!-- Toolbar con azioni -->
    <div class="theme-toolbar">
        <!-- Pulsanti azione principali -->
        <?php echo $this->add_button('btn btn-success'); ?>
        <?php echo $this->csv_button('btn btn-info'); ?>
        <?php echo $this->print_button('btn btn-default'); ?>
        
        <!-- Toggle View Mode -->
        <div class="view-toggle">
            <button onclick="setViewMode('list')" 
                    class="<?php echo !$is_grid_view ? 'active' : ''; ?>">
                List View
            </button>
            <button onclick="setViewMode('grid')" 
                    class="<?php echo $is_grid_view ? 'active' : ''; ?>">
                Grid View
            </button>
        </div>
    </div>
    
    <!-- Ricerca -->
    <?php echo $this->render_search(); ?>
    
    <!-- Vista Dati -->
    <?php if (!$is_grid_view): ?>
        <!-- Vista Tabella -->
        <table class="theme-table">
            <thead>
                <?php echo $this->render_grid_head('tr', 'th'); ?>
            </thead>
            <tbody>
                <?php echo $this->render_grid_body('tr', 'td'); ?>
            </tbody>
            <tfoot>
                <?php echo $this->render_grid_footer('tr', 'td'); ?>
            </tfoot>
        </table>
    <?php else: ?>
        <!-- Vista Grid (Cards) -->
        <div class="theme-grid">
            <?php 
            // Accesso ai dati per grid view
            $data = $this->get_grid_data();
            $cols = $this->get_columns_data();
            $primary_key = $this->get_primary_key();
            
            if (!empty($data)):
                foreach($data as $row):
            ?>
            <div class="theme-card">
                <h3><?php echo htmlspecialchars($row[$primary_key]); ?></h3>
                <?php foreach($row as $field => $value): ?>
                    <div class="field">
                        <label><?php echo $this->get_field_label($field); ?>:</label>
                        <span><?php echo htmlspecialchars($value); ?></span>
                    </div>
                <?php endforeach; ?>
                
                <!-- Azioni Card -->
                <div class="card-actions xcrud-actions">
                    <a href="javascript:void(0);" 
                       class="btn-view xcrud-action" 
                       data-task="view" 
                       data-primary="<?php echo htmlspecialchars($row[$primary_key]); ?>">
                        View
                    </a>
                    <a href="javascript:void(0);" 
                       class="btn-edit xcrud-action" 
                       data-task="edit" 
                       data-primary="<?php echo htmlspecialchars($row[$primary_key]); ?>">
                        Edit
                    </a>
                </div>
            </div>
            <?php 
                endforeach;
            endif;
            ?>
        </div>
    <?php endif; ?>
    
    <!-- Paginazione -->
    <?php echo $this->render_pagination(); ?>
    
    <!-- Limite righe -->
    <?php echo $this->render_limitlist(); ?>
    
    <!-- Benchmark (opzionale) -->
    <?php echo $this->render_benchmark(); ?>
</div>
```

### 4. xcrud_detail_view.php
Gestisce i form di creazione, modifica e visualizzazione:

```php
<?php
// Mantieni modalità view per il ritorno alla lista
$current_view = isset($_REQUEST['view']) ? $_REQUEST['view'] : 'table';
?>

<div class="theme-detail-container">
    <!-- Header con info modalità -->
    <div class="detail-header">
        <h2><?php echo ucfirst($mode); ?> Record</h2>
        <?php if($mode == 'edit' || $mode == 'view'): ?>
            <span class="record-id">ID: #<?php echo $_GET['xcrud']['primary'] ?? 'N/A'; ?></span>
        <?php endif; ?>
    </div>
    
    <!-- Form Fields -->
    <div class="detail-form">
        <?php 
        // Renderizza i campi con layout custom
        echo $this->render_fields_list(
            $mode,
            array('tag' => 'div', 'class' => 'form-wrapper'),
            array('tag' => 'div', 'class' => 'form-row'),
            array('tag' => 'label', 'class' => 'form-label'),
            array('tag' => 'div', 'class' => 'form-field')
        );
        ?>
    </div>
    
    <!-- Azioni Form -->
    <div class="detail-actions">
        <?php if($mode != 'view'): ?>
            <!-- Pulsanti salvataggio -->
            <?php echo $this->render_button('save_return', 'save', 'list', 
                'btn btn-primary xcrud-action', 'Save & Return', 'create,edit'); ?>
            <?php echo $this->render_button('save_new', 'save', 'create', 
                'btn btn-success xcrud-action', 'Save & New', 'create,edit'); ?>
            <?php echo $this->render_button('save_edit', 'save', 'edit', 
                'btn btn-info xcrud-action', 'Save & Continue', 'create,edit'); ?>
        <?php endif; ?>
        
        <!-- Return to List (importante!) -->
        <a href="javascript:void(0);" 
           class="btn btn-default xcrud-action" 
           data-task="list"
           data-view="<?php echo htmlspecialchars($current_view); ?>">
            Back to List
        </a>
    </div>
    
    <!-- Hidden field per mantenere view mode -->
    <input type="hidden" class="xcrud-data" name="view" 
           value="<?php echo htmlspecialchars($current_view); ?>" />
</div>
```

## 🔌 Accesso ai Dati

### Metodi Pubblici Disponibili nei Template

```php
// Rendering Methods
$this->render_table_name()      // Nome tabella
$this->render_grid_head()       // Intestazioni tabella
$this->render_grid_body()       // Corpo tabella
$this->render_grid_footer()     // Footer tabella (somme, etc.)
$this->render_search()          // Form ricerca
$this->render_pagination()      // Controlli paginazione
$this->render_limitlist()       // Selezione limite righe
$this->render_benchmark()       // Info performance
$this->render_fields_list()     // Lista campi form
$this->render_button()          // Pulsanti custom

// Action Buttons
$this->add_button($class, $text)     // Pulsante aggiungi
$this->csv_button($class, $text)     // Pulsante export CSV
$this->print_button($class, $text)   // Pulsante stampa

// Data Access (dopo aver aggiunto i getter)
$this->get_grid_data()          // Array dati griglia
$this->get_columns_data()       // Info colonne
$this->get_primary_key()        // Nome chiave primaria

// Properties
$this->is_create                // Permesso creazione
$this->is_edit                  // Permesso modifica
$this->is_remove                // Permesso eliminazione
$this->is_view                  // Permesso visualizzazione
$this->is_csv                   // Export CSV abilitato
$this->is_print                 // Stampa abilitata
$this->is_search                // Ricerca abilitata
$this->instance_name            // Nome istanza
$this->is_rtl                   // Right-to-left mode
```

### Accesso a Proprietà Protected (Workaround)

Se necessiti di accedere a proprietà protected, puoi:

1. **Aggiungere getter methods in xcrud.php:**
```php
public function get_result_list() {
    return $this->result_list ?? [];
}

public function get_field_label($field) {
    return $this->labels[$field] ?? ucwords(str_replace('_', ' ', $field));
}
```

2. **Usare Reflection (sconsigliato in produzione):**
```php
$reflection = new ReflectionClass($this);
$property = $reflection->getProperty('result_list');
$property->setAccessible(true);
$data = $property->getValue($this);
```

## 🚀 Creazione Step-by-Step

### Step 1: Crea la struttura base
```bash
mkdir themes/mio_tema
cd themes/mio_tema
touch xcrud.ini xcrud.css xcrud_container.php xcrud_list_view.php xcrud_detail_view.php
```

### Step 2: Configura xcrud.ini
Copia e modifica da un tema esistente, personalizzando icone e classi.

### Step 3: Sviluppa il CSS
```css
/* xcrud.css */

/* Reset e base */
.xcrud.mio_tema * {
    box-sizing: border-box;
}

/* Container principale */
.xcrud.mio_tema {
    font-family: 'Your Font', sans-serif;
    color: #333;
}

/* Tabella */
.xcrud.mio_tema table {
    width: 100%;
    border-collapse: collapse;
}

/* Form */
.xcrud.mio_tema .form-row {
    margin-bottom: 15px;
}

.xcrud.mio_tema .form-label {
    display: inline-block;
    width: 30%;
}

.xcrud.mio_tema .form-field {
    display: inline-block;
    width: 70%;
}

/* Responsive */
@media (max-width: 768px) {
    .xcrud.mio_tema .form-label,
    .xcrud.mio_tema .form-field {
        width: 100%;
        display: block;
    }
}
```

### Step 4: Implementa i template PHP
Usa gli esempi sopra come base e personalizza secondo le tue necessità.

### Step 5: Aggiungi JavaScript custom
```javascript
// Persistenza view mode
function setViewMode(mode) {
    const url = new URL(window.location);
    url.searchParams.set('view', mode);
    sessionStorage.setItem('xcrud-view-mode', mode);
    window.location = url;
}

// Dark mode toggle
function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
    localStorage.setItem('theme-mode', 
        document.body.classList.contains('dark-mode') ? 'dark' : 'light'
    );
}

// Inizializzazione
document.addEventListener('DOMContentLoaded', function() {
    // Ripristina dark mode
    if (localStorage.getItem('theme-mode') === 'dark') {
        document.body.classList.add('dark-mode');
    }
    
    // Ripristina view mode
    const savedView = sessionStorage.getItem('xcrud-view-mode');
    if (savedView && !window.location.search.includes('view=')) {
        setViewMode(savedView);
    }
});
```

### Step 6: Testa il tema
```php
$xcrud = Xcrud::get_instance();
$xcrud->table('your_table');
$xcrud->theme('mio_tema');
echo $xcrud->render();
```

## 🎯 Best Practices

### 1. **Mantieni Compatibilità**
- Non rimuovere classi CSS essenziali (`xcrud-action`, `xcrud-data`, etc.)
- Mantieni attributi data necessari per JavaScript

### 2. **Responsive Design**
```css
/* Mobile First Approach */
.xcrud.mio_tema .container {
    padding: 10px;
}

@media (min-width: 768px) {
    .xcrud.mio_tema .container {
        padding: 20px;
    }
}
```

### 3. **Accessibilità**
```html
<!-- Usa ARIA labels -->
<button aria-label="Edit record" class="xcrud-action" data-task="edit">
    <i class="icon-edit" aria-hidden="true"></i>
    <span class="sr-only">Edit</span>
</button>
```

### 4. **Performance**
- Minimizza CSS/JS in produzione
- Usa CSS Grid/Flexbox invece di float
- Lazy load immagini in grid view

### 5. **Dark Mode Support**
```css
/* Variabili CSS per temi */
:root {
    --bg-color: #ffffff;
    --text-color: #333333;
    --border-color: #dddddd;
}

.dark-mode {
    --bg-color: #1a1a1a;
    --text-color: #ffffff;
    --border-color: #444444;
}

.xcrud.mio_tema {
    background: var(--bg-color);
    color: var(--text-color);
}
```

## 💡 Esempi di Temi

### 1. **Minimal Theme**
```css
/* Ultra minimal, focus on content */
.xcrud.minimal {
    font: 14px/1.6 system-ui, sans-serif;
}
.xcrud.minimal table {
    width: 100%;
    border: 1px solid #e0e0e0;
}
.xcrud.minimal th {
    background: #f5f5f5;
    font-weight: 500;
}
```

### 2. **Material Design Theme**
```css
/* Material Design inspired */
.xcrud.material {
    font-family: 'Roboto', sans-serif;
}
.xcrud.material .card {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border-radius: 4px;
    transition: box-shadow 0.3s;
}
.xcrud.material .card:hover {
    box-shadow: 0 8px 16px rgba(0,0,0,0.2);
}
.xcrud.material .btn {
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-radius: 4px;
}
```

### 3. **Glassmorphism Theme**
```css
/* Modern glassmorphism */
.xcrud.glass {
    backdrop-filter: blur(10px);
    background: rgba(255, 255, 255, 0.1);
}
.xcrud.glass .card {
    background: linear-gradient(135deg, 
        rgba(255, 255, 255, 0.1) 0%, 
        rgba(255, 255, 255, 0.05) 100%);
    border: 1px solid rgba(255, 255, 255, 0.18);
    border-radius: 20px;
}
```

### 4. **Neumorphism Theme**
```css
/* Soft UI / Neumorphism */
.xcrud.neumorph {
    background: #e0e5ec;
}
.xcrud.neumorph .card {
    background: #e0e5ec;
    border-radius: 20px;
    box-shadow: 
        9px 9px 16px #a3b1c6,
        -9px -9px 16px #ffffff;
}
.xcrud.neumorph .btn {
    box-shadow: 
        6px 6px 12px #a3b1c6,
        -6px -6px 12px #ffffff;
}
.xcrud.neumorph .btn:active {
    box-shadow: 
        inset 6px 6px 12px #a3b1c6,
        inset -6px -6px 12px #ffffff;
}
```

## 🔧 Trucchi e Suggerimenti

### 1. **Grid View con Masonry Layout**
```javascript
// Usa una libreria come Masonry.js per layout dinamico
var grid = document.querySelector('.xcrud-grid');
var masonry = new Masonry(grid, {
    itemSelector: '.grid-item',
    columnWidth: 300,
    gutter: 20
});
```

### 2. **Animazioni Smooth**
```css
/* Transizioni smooth per tutte le interazioni */
.xcrud.mio_tema * {
    transition: all 0.3s ease;
}
.xcrud.mio_tema .xcrud-action:hover {
    transform: translateY(-2px);
}
```

### 3. **Loading States**
```css
/* Skeleton loading per miglior UX */
.xcrud.mio_tema .loading {
    background: linear-gradient(90deg, 
        #f0f0f0 25%, 
        #e0e0e0 50%, 
        #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}
@keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}
```

### 4. **Custom Scrollbar**
```css
/* Scrollbar personalizzata */
.xcrud.mio_tema ::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}
.xcrud.mio_tema ::-webkit-scrollbar-track {
    background: #f1f1f1;
}
.xcrud.mio_tema ::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}
```

## 📚 Risorse Utili

- **Font Icons:** [Font Awesome](https://fontawesome.com/), [Material Icons](https://fonts.google.com/icons)
- **CSS Frameworks:** [Tailwind](https://tailwindcss.com/), [Bootstrap](https://getbootstrap.com/)
- **Color Schemes:** [Coolors](https://coolors.co/), [Adobe Color](https://color.adobe.com/)
- **Animations:** [Animate.css](https://animate.style/), [AOS](https://michalsnik.github.io/aos/)
- **Grid Layouts:** [CSS Grid Generator](https://grid.layoutit.com/)

## 🎉 Conclusione

Creare temi per xCrudRevolution è un processo creativo che ti permette di trasformare completamente l'esperienza utente. Con questa guida e gli esempi forniti, hai tutti gli strumenti necessari per creare temi straordinari.

**Ricorda:** 
- Parti sempre da un tema esistente come base
- Testa su diversi browser e dispositivi  
- Mantieni il codice pulito e documentato
- Condividi i tuoi temi con la community!

## 📝 Nota Personale di Claude

Dopo aver lavorato intensamente sul tema Revolution, ho acquisito una comprensione profonda del sistema di theming di xCrudRevolution. Ecco alcune riflessioni chiave:

### Lezioni Apprese:

1. **La Persistenza è Cruciale**: Il problema più complesso è stato mantenere lo stato della view (grid/list) attraverso navigazione e AJAX. La soluzione migliore sarà integrarla nel core con il sistema hooks.

2. **Labels e Dati**: L'accesso ai dati protected è stato challenging. Ho dovuto creare getter methods (`get_grid_data()`, `get_columns_data()`) per accedere ai dati in modo pulito.

3. **JavaScript & PHP Harmony**: La coordinazione tra PHP (server-side) e JavaScript (client-side) è essenziale. Ogni azione AJAX deve preservare lo stato.

4. **CSS Variables sono il Futuro**: Usare CSS custom properties rende i temi facilmente customizzabili e supporta dark mode nativamente.

5. **Il Diavolo è nei Dettagli**: Piccole cose come il pulsante "Return to List" che mantiene la modalità view fanno la differenza nell'esperienza utente.

### Suggerimenti per il Futuro:

- **Hook System First**: Prima di estendere ulteriormente i temi, implementare il sistema di hooks renderà tutto più modulare
- **Theme API**: Creare un'API specifica per i temi con metodi dedicati all'accesso dati
- **Theme Inheritance**: Permettere ai temi di estendere altri temi (eredità)
- **Live Theme Editor**: Un editor visuale per modificare i temi in tempo reale

### La Mia Visione:

Il tema Revolution è solo l'inizio. Con il sistema `theme_mode()` proposto e l'architettura hooks, potremo creare temi che non sono solo skin visive, ma vere e proprie trasformazioni funzionali dell'interfaccia CRUD.

Immagino temi specializzati per:
- **E-commerce**: Con preview prodotti e carrello integrato
- **CRM**: Con timeline attività e vista kanban
- **Analytics**: Con grafici e dashboard integrate
- **Social**: Con feed e interazioni real-time

Il potenziale è infinito! 🚀

---

*Guida creata basandosi sull'esperienza diretta dello sviluppo del tema Revolution per xCrudRevolution.*
*Con passione e dedizione - Claude, il tuo assistente AI innamorato del codice ben fatto* 💜