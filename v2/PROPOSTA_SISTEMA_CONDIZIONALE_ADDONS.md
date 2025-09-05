# Sistema Condizionale e Addons per xCrudRevolution

## CONSIGLIO FINALE: Sistema Ibrido Ottimale

### 1. PROBLEMA ATTUALE
```php
// LIMITAZIONE ATTUALE - Solo UNA condizione
$xcrud->unset_edit(true, 'status', '=', 'completed');
// Non puoi fare: status = 'completed' AND user_role = 'viewer'
```

### 2. SOLUZIONE PROPOSTA: Multi-Conditional System via Addons

## A. SISTEMA CONDIZIONALE BASE (Core)
Aggiungi questi metodi minimi al core di xCrud per mantenere la struttura monolitica:

```php
// In xcrud.php - Aggiunte MINIME al core
class Xcrud {
    // Proprietà per condizioni
    protected $conditions_stack = [];
    protected $active_condition = null;
    
    /**
     * Sistema condizionale semplice che salva in sessione
     */
    public function when($condition, $callback = null) {
        $result = $this->evaluate_condition($condition);
        
        if ($result && $callback) {
            if (is_callable($callback)) {
                $callback($this);
            } else {
                // Salva per metodi successivi
                $this->active_condition = true;
            }
        } else {
            $this->active_condition = false;
        }
        
        return $this;
    }
    
    public function otherwise($callback = null) {
        if ($this->active_condition === false) {
            if (is_callable($callback)) {
                $callback($this);
            }
        }
        return $this;
    }
    
    public function endif() {
        $this->active_condition = null;
        return $this;
    }
    
    /**
     * Override dei metodi unset_* per supportare multi-condizioni
     */
    public function unset_edit($bool = true, $conditions = null) {
        if (is_array($conditions)) {
            // Multi-condizioni tramite addon
            return $this->apply_conditional_unset('edit', $bool, $conditions);
        }
        
        // Comportamento legacy (backward compatible)
        if (func_num_args() === 4) {
            $args = func_get_args();
            $this->is_edit = !(bool)$bool;
            if ($args[1] && $args[2] && $args[3] !== false) {
                $this->unset_edit_condition = [
                    'field' => $args[1],
                    'operand' => $args[2],
                    'value' => $args[3]
                ];
            }
        } else {
            $this->is_edit = !(bool)$bool;
        }
        
        return $this;
    }
    
    /**
     * Hook per estensioni via addon
     */
    protected function apply_conditional_unset($action, $bool, $conditions) {
        // Trigger hook per addon
        $this->trigger_hook('before_conditional_unset', [
            'action' => $action,
            'bool' => $bool,
            'conditions' => $conditions
        ]);
        
        // Se c'è un addon che gestisce multi-condizioni
        if ($this->has_addon('multi_conditions')) {
            return $this->addons['multi_conditions']->handle($action, $bool, $conditions);
        }
        
        // Fallback al comportamento standard
        $property = 'is_' . $action;
        $this->$property = !(bool)$bool;
        
        return $this;
    }
}
```

## B. SISTEMA ADDONS RACCOMANDATO

### Struttura Directory
```
xcrud/
├── addons/
│   ├── multi_conditions/
│   │   ├── addon.json
│   │   ├── multi_conditions.php
│   │   └── assets/
│   ├── conditional_fields/
│   │   ├── addon.json
│   │   ├── conditional_fields.php
│   │   └── conditional_fields.js
│   └── smart_permissions/
│       ├── addon.json
│       └── smart_permissions.php
```

### Addon Multi-Condizioni
```php
// addons/multi_conditions/multi_conditions.php
class XcrudAddon_MultiConditions {
    
    protected $xcrud;
    
    public function __construct($xcrud_instance) {
        $this->xcrud = $xcrud_instance;
    }
    
    /**
     * Gestisce multi-condizioni per unset_*
     */
    public function handle($action, $bool, $conditions) {
        // Supporta diversi formati
        if ($this->evaluate_all_conditions($conditions)) {
            $property = 'is_' . $action;
            $this->xcrud->$property = !(bool)$bool;
        }
        
        return $this->xcrud;
    }
    
    protected function evaluate_all_conditions($conditions) {
        // Formato 1: Array di condizioni AND
        // [
        //     ['field' => 'status', 'op' => '=', 'value' => 'completed'],
        //     ['field' => 'user_role', 'op' => '!=', 'value' => 'admin']
        // ]
        
        // Formato 2: Condizioni OR/AND complesse
        // [
        //     'AND' => [
        //         ['status', '=', 'completed'],
        //         'OR' => [
        //             ['role', '=', 'viewer'],
        //             ['role', '=', 'guest']
        //         ]
        //     ]
        // ]
        
        // Formato 3: String expression
        // "status = 'completed' AND (role = 'viewer' OR role = 'guest')"
        
        foreach ($conditions as $key => $condition) {
            if (!$this->evaluate_single_condition($condition)) {
                return false;
            }
        }
        return true;
    }
}
```

### Addon Conditional Fields
```php
// addons/conditional_fields/conditional_fields.php
class XcrudAddon_ConditionalFields {
    
    /**
     * Campi che appaiono/scompaiono in base a condizioni
     */
    public function show_when($field, $depends_on, $condition) {
        // Aggiungi JavaScript per nascondere/mostrare campo
        $js = "
        $(document).ready(function() {
            function check_$field() {
                var val = $('[name=\"$depends_on\"]').val();
                if ($condition) {
                    $('.xcrud-field-$field').show();
                } else {
                    $('.xcrud-field-$field').hide();
                }
            }
            
            $('[name=\"$depends_on\"]').on('change', check_$field);
            check_$field();
        });
        ";
        
        $this->xcrud->set_js($js);
        return $this->xcrud;
    }
    
    /**
     * Validazione condizionale
     */
    public function required_when($field, $depends_on, $value) {
        $this->xcrud->validation_pattern($field, 'conditional_required', [
            'depends_on' => $depends_on,
            'value' => $value
        ]);
        
        return $this->xcrud;
    }
}
```

## C. ESEMPI DI UTILIZZO

### 1. Multi-Condizioni con Addon
```php
// Con addon multi_conditions installato
$xcrud->load_addon('multi_conditions');

// Ora puoi usare multi-condizioni
$xcrud->unset_edit(true, [
    ['field' => 'status', 'op' => '=', 'value' => 'completed'],
    ['field' => 'locked', 'op' => '=', 'value' => 1],
    ['field' => 'user_id', 'op' => '!=', 'value' => $_SESSION['user_id']]
]);

// Oppure con sintassi SQL-like
$xcrud->unset_remove(true, "status = 'deleted' OR archived = 1");

// Condizioni complesse
$xcrud->unset_view(true, [
    'OR' => [
        ['privacy', '=', 'private'],
        'AND' => [
            ['owner', '!=', $_SESSION['user_id']],
            ['shared_with', 'NOT LIKE', "%{$_SESSION['user_id']}%"]
        ]
    ]
]);
```

### 2. Campi Condizionali
```php
$xcrud->load_addon('conditional_fields');

// Campo che appare solo se tipo = 'custom'
$xcrud->show_when('custom_value', 'type', "val == 'custom'");

// Campo required solo se status = 'published'
$xcrud->required_when('publish_date', 'status', 'published');

// Cascade di campi
$xcrud->show_when('state', 'country', "val == 'USA'")
      ->show_when('city', 'state', "val != ''");
```

### 3. Sistema When/Otherwise Fluente
```php
// Con sessioni
$xcrud->when($_SESSION['user_role'] === 'admin')
        ->columns('id,name,email,role,status,actions')
        ->unset_remove(false)
      ->otherwise()
        ->columns('name,email,status')
        ->unset_remove(true)
        ->unset_edit(true, ['field' => 'role', 'op' => '!=', 'value' => 'user'])
      ->endif();

// Con feature flags
$xcrud->when($xcrud->feature('bulk_operations'))
        ->button('#bulk-delete', 'Delete Selected', 'glyphicon glyphicon-trash')
        ->button('#bulk-export', 'Export Selected', 'glyphicon glyphicon-download')
      ->endif();

// Con query database
$xcrud->when_query("SELECT premium FROM users WHERE id = ?", [$_SESSION['user_id']])
        ->limit_list('10,25,50,100,500')
      ->otherwise()
        ->limit_list('10,25,50')
      ->endif();
```

## D. INTEGRAZIONE CON VISUAL XCRUD BUILDER

Il Visual Builder genererebbe:
```php
// Generato automaticamente dal Builder
$xcrud = Xcrud::get_instance();
$xcrud->table('products');

// [IF premium_user]
$xcrud->when($user->isPremium())
        ->columns('id,name,sku,price,cost,profit,stock')
        ->change_type('profit', 'price')
// [ELSE]
      ->otherwise()
        ->columns('name,sku,price,stock')
        ->hide('cost,profit')
// [ENDIF]
      ->endif();

// [PERMISSIONS]
$xcrud->when_permissions(['edit_products'])
        ->unset_edit(false)
      ->otherwise()
        ->unset_edit(true)
      ->endif();
```

## E. VANTAGGI DI QUESTO APPROCCIO

1. **Mantieni Struttura Monolitica**: Solo piccole aggiunte al core
2. **Backward Compatible**: Codice esistente continua a funzionare
3. **Estendibile via Addons**: Nuove funzionalità senza toccare il core
4. **Multi-Condizioni**: Finalmente supportate tramite addon
5. **Visual Builder Compatible**: Facile generare codice condizionale
6. **Performance**: Condizioni cachate in sessione
7. **Flessibile**: Supporta diversi stili di sintassi

## F. IMPLEMENTAZIONE GRADUALE

### Fase 1 - Core Minimo (1 giorno)
- Aggiungi metodi `when()`, `otherwise()`, `endif()`
- Modifica `params2save()` per salvare stato condizionale
- Test con Visual Builder

### Fase 2 - Addon Multi-Condizioni (2 giorni)
- Crea sistema di caricamento addons
- Implementa addon multi_conditions
- Estendi tutti i metodi unset_*

### Fase 3 - Addons Aggiuntivi (1 settimana)
- conditional_fields
- smart_permissions
- workflow_states
- feature_flags

## CONCLUSIONE

Questo sistema ti permette di:
1. Mantenere xCrud monolitico come desideri
2. Estendere le funzionalità tramite addons
3. Supportare multi-condizioni ovunque
4. Rimanere compatibile con Visual xCrud Builder
5. Salvare tutto in sessione come già fa xCrud

Il tutto con modifiche MINIME al core! 🚀