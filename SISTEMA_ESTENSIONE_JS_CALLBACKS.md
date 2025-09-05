# Sistema di Estensione JavaScript e Callbacks per xCrudRevolution

## 1. PROBLEMA ATTUALE CON XCRUD.JS

### Limitazioni:
- xcrud.js è monolitico (1344 righe)
- Callbacks solo in file separati in `/xcrud/functions.php`
- Sviluppatori non possono estendere facilmente JavaScript
- Dipendenza stretta tra xcrud.php → xcrud_ajax.php → xcrud.js

## 2. SOLUZIONE: SISTEMA ESTENSIBILE COMPLETO

### A. ESTENSIONE JAVASCRIPT - Plugin System

```javascript
// Nuovo xcrud.js modulare
var Xcrud = (function() {
    'use strict';
    
    // Core privato
    var _core = {
        version: '2.0',
        plugins: {},
        hooks: {},
        overrides: {}
    };
    
    // API Pubblica
    var api = {
        // Registra un plugin
        plugin: function(name, plugin) {
            if (typeof plugin === 'function') {
                _core.plugins[name] = new plugin(this);
            } else {
                _core.plugins[name] = plugin;
            }
            
            // Auto-init se ha metodo init
            if (_core.plugins[name].init) {
                _core.plugins[name].init();
            }
            
            return this;
        },
        
        // Override di metodi esistenti
        override: function(method, newFunction) {
            _core.overrides[method] = _core.overrides[method] || [];
            _core.overrides[method].push(newFunction);
            return this;
        },
        
        // Sistema di hook JavaScript
        hook: function(name, callback, priority) {
            priority = priority || 10;
            _core.hooks[name] = _core.hooks[name] || [];
            _core.hooks[name].push({
                callback: callback,
                priority: priority
            });
            
            // Ordina per priorità
            _core.hooks[name].sort(function(a, b) {
                return a.priority - b.priority;
            });
            
            return this;
        },
        
        // Trigger hook
        trigger: function(name, args) {
            if (_core.hooks[name]) {
                var result = args;
                _core.hooks[name].forEach(function(hook) {
                    result = hook.callback.apply(null, [result]);
                });
                return result;
            }
            return args;
        },
        
        // Metodo request originale estendibile
        request: function(container, data, callback) {
            // Trigger before hook
            data = this.trigger('before_request', data);
            
            // Check overrides
            if (_core.overrides.request) {
                return _core.overrides.request[0].call(this, container, data, callback);
            }
            
            // Request originale
            jQuery.ajax({
                type: 'post',
                url: this.config('url'),
                data: {'xcrud': data},
                success: function(response) {
                    // Trigger after hook
                    response = api.trigger('after_request', response);
                    jQuery(container).html(response);
                    if (callback) callback(response);
                }
            });
        }
    };
    
    return api;
})();

// Esempio di plugin custom
Xcrud.plugin('customActions', {
    init: function() {
        // Aggiungi azioni custom
        Xcrud.hook('before_request', function(data) {
            console.log('Request intercepted:', data);
            // Modifica data se necessario
            return data;
        });
        
        // Override del metodo di validazione
        Xcrud.override('validate', function(container) {
            console.log('Custom validation');
            // Validazione custom
            return true;
        });
    },
    
    // Metodi pubblici del plugin
    customMethod: function() {
        console.log('Custom method called');
    }
});
```

### B. CALLBACKS INLINE (NEL FILE PHP)

Modifica a xcrud.php per supportare callbacks inline:

```php
// In xcrud.php - aggiungi questi metodi
class Xcrud {
    
    protected $inline_callbacks = [];
    protected $js_extensions = [];
    
    /**
     * Registra callback inline (non più in functions.php)
     */
    public function callback($type, $callable, $inline = true) {
        if ($inline && is_callable($callable)) {
            // Genera ID univoco per il callback
            $callback_id = 'inline_' . md5(serialize($callable) . microtime());
            
            // Salva in memoria
            $this->inline_callbacks[$callback_id] = $callable;
            
            // Registra come callback normale
            $this->{$type . '_callback'} = $callback_id;
            
        } else {
            // Comportamento legacy (function name in functions.php)
            $this->{$type . '_callback'} = $callable;
        }
        
        return $this;
    }
    
    /**
     * Callback inline diretto con closure
     */
    public function before_insert($callback) {
        if ($callback instanceof Closure) {
            $this->callback('before_insert', $callback, true);
        } else {
            $this->before_insert_callback = $callback;
        }
        return $this;
    }
    
    public function after_update($callback) {
        if ($callback instanceof Closure) {
            $this->callback('after_update', $callback, true);
        } else {
            $this->after_update_callback = $callback;
        }
        return $this;
    }
    
    /**
     * Esegui callback (modificato per supportare inline)
     */
    protected function _call_callback($name, $value) {
        $callback = $this->{$name . '_callback'};
        
        // Check se è inline callback
        if (isset($this->inline_callbacks[$callback])) {
            return call_user_func_array($this->inline_callbacks[$callback], [$value, $this]);
        }
        
        // Legacy: cerca in functions.php
        if (is_callable($callback)) {
            return call_user_func_array($callback, [$value, $this]);
        }
        
        // Legacy: file functions.php
        $path = $this->load_view($this->functions_file_path, true);
        if ($path) {
            include_once($path);
            if (is_callable($callback)) {
                return call_user_func_array($callback, [$value, $this]);
            }
        }
        
        return $value;
    }
    
    /**
     * Aggiungi JavaScript custom
     */
    public function extend_js($code) {
        $this->js_extensions[] = $code;
        return $this;
    }
    
    /**
     * Registra plugin JavaScript
     */
    public function js_plugin($name, $code) {
        $plugin = "
        Xcrud.plugin('$name', {
            $code
        });
        ";
        $this->js_extensions[] = $plugin;
        return $this;
    }
    
    /**
     * Hook JavaScript inline
     */
    public function js_hook($event, $callback, $priority = 10) {
        $js = "
        Xcrud.hook('$event', function(data) {
            $callback
        }, $priority);
        ";
        $this->js_extensions[] = $js;
        return $this;
    }
}
```

### C. ESEMPI DI UTILIZZO

#### 1. Callbacks Inline (senza functions.php)
```php
// VECCHIO MODO (functions.php richiesto)
$xcrud->before_insert('my_callback_function');

// NUOVO MODO 1: Closure inline
$xcrud->before_insert(function($postdata, $xcrud) {
    // Logica direttamente qui
    $postdata['created_by'] = $_SESSION['user_id'];
    $postdata['created_at'] = date('Y-m-d H:i:s');
    return $postdata;
});

// NUOVO MODO 2: Callback multipli
$xcrud->callback('before_update', function($postdata, $xcrud) {
    $postdata['updated_at'] = time();
    
    // Puoi accedere a tutti i metodi di xcrud
    if ($xcrud->get_primary() == 1) {
        $postdata['is_admin'] = true;
    }
    
    return $postdata;
});

// NUOVO MODO 3: Chain callbacks
$xcrud->after_insert(function($postdata, $primary, $xcrud) {
    // Log the insert
    error_log("New record inserted: " . $primary);
    
    // Send email
    mail('admin@example.com', 'New Record', 'ID: ' . $primary);
    
    return true;
})->after_update(function($postdata, $primary, $xcrud) {
    // Clear cache
    Cache::forget('records_' . $primary);
    
    return true;
});
```

#### 2. Estensione JavaScript Inline
```php
// Aggiungi validazione custom JavaScript
$xcrud->extend_js("
    // Validazione custom per email
    Xcrud.hook('before_validation', function(data) {
        var email = $('[name=\"email\"]').val();
        if (!email.includes('@company.com')) {
            alert('Solo email aziendali sono permesse');
            return false;
        }
        return data;
    });
");

// Registra un plugin completo
$xcrud->js_plugin('autoSave', "
    init: function() {
        // Auto-save ogni 30 secondi
        setInterval(function() {
            if ($('.xcrud-form').length) {
                var data = Xcrud.list_data('.xcrud-form');
                localStorage.setItem('xcrud_draft', JSON.stringify(data));
            }
        }, 30000);
    },
    
    restore: function() {
        var draft = localStorage.getItem('xcrud_draft');
        if (draft) {
            // Ripristina i dati
            var data = JSON.parse(draft);
            // ... logica di restore
        }
    }
");

// Hook per eventi specifici
$xcrud->js_hook('after_request', "
    // Google Analytics tracking
    if (typeof ga !== 'undefined') {
        ga('send', 'event', 'xcrud', 'request', data.task);
    }
    
    // Custom notification
    if (data.task === 'save') {
        toastr.success('Record salvato con successo!');
    }
    
    return data;
");
```

#### 3. Override di Metodi JavaScript
```php
// Override del metodo request per aggiungere loading spinner
$xcrud->extend_js("
    Xcrud.override('request', function(container, data, callback) {
        // Mostra spinner
        $(container).addClass('loading');
        
        // Chiama il metodo originale
        var original = this.constructor.prototype.request;
        original.call(this, container, data, function(response) {
            // Rimuovi spinner
            $(container).removeClass('loading');
            
            // Callback originale
            if (callback) callback(response);
        });
    });
");
```

### D. SISTEMA DI RENDERING (Rimane uguale ma estendibile)

Il sistema con `xcrud_ajax.php` e campi hidden rimane identico per compatibilità, ma ora è estendibile:

```php
// Puoi intercettare il render
$xcrud->before_render(function($html, $xcrud) {
    // Modifica HTML prima del render
    $html = str_replace('{{user}}', $_SESSION['username'], $html);
    return $html;
});

// Puoi aggiungere campi hidden custom
$xcrud->add_hidden_field('user_token', $_SESSION['token']);
$xcrud->add_hidden_field('environment', 'production');

// Puoi modificare l'URL AJAX
$xcrud->set_ajax_url('/custom/ajax/endpoint.php');
```

### E. ADDONS CHE ESTENDONO JAVASCRIPT

```php
// Struttura addon con JavaScript
// addons/rich_editor/
//   ├── addon.json
//   ├── rich_editor.php
//   └── assets/
//       ├── rich_editor.js
//       └── rich_editor.css

// In rich_editor.php
class XcrudAddon_RichEditor {
    
    public function init($xcrud) {
        // Aggiungi JavaScript dell'addon
        $xcrud->load_js('addons/rich_editor/assets/rich_editor.js');
        
        // Registra il plugin JavaScript
        $xcrud->js_plugin('richEditor', file_get_contents(__DIR__ . '/assets/plugin.js'));
        
        // Hook PHP e JavaScript insieme
        $xcrud->change_type('description', 'richtext');
        $xcrud->js_hook('after_render', "
            $('.xcrud-richtext').richEditor({
                toolbar: ['bold', 'italic', 'underline'],
                height: 300
            });
        ");
    }
}
```

## 3. VANTAGGI DEL NUOVO SISTEMA

1. **JavaScript Modulare**: Plugin system per estendere xcrud.js
2. **Callbacks Inline**: Non più dipendenti da functions.php
3. **Hooks Ovunque**: PHP e JavaScript hooks coordinati
4. **Override Facile**: Puoi sovrascrivere qualsiasi metodo
5. **Backward Compatible**: Vecchio codice continua a funzionare
6. **Addons Potenti**: Possono estendere sia PHP che JavaScript
7. **Developer Friendly**: Tutto nel file dove stai lavorando

## 4. MIGRAZIONE GRADUALE

```php
// FASE 1: Supporta entrambi i modi
$xcrud->before_insert('old_callback'); // Vecchio modo (functions.php)
$xcrud->before_insert(function($data) { /* nuovo */ }); // Nuovo modo

// FASE 2: Depreca vecchio sistema
if (is_string($callback)) {
    trigger_error('String callbacks deprecated, use closures', E_USER_DEPRECATED);
}

// FASE 3: Solo nuovo sistema
// Rimuovi supporto per functions.php
```

Questo sistema mantiene la struttura esistente (xcrud_ajax.php, hidden fields, sessioni) ma la rende completamente estendibile! 🚀