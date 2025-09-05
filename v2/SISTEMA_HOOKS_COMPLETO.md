# 🪝 SISTEMA HOOKS COMPLETO PER XCRUD

## IMPLEMENTAZIONE HOOKS OVUNQUE

### 1. HOOKS DENTRO XCRUD.PHP (Non file separato!)

```php
class Xcrud {
    // Properties per hooks
    protected $hooks = [];
    
    /**
     * Registra un hook
     */
    public function add_hook($name, $callback, $priority = 10) {
        if (!isset($this->hooks[$name])) {
            $this->hooks[$name] = [];
        }
        
        $this->hooks[$name][] = [
            'callback' => $callback,
            'priority' => $priority
        ];
        
        // Ordina per priorità
        usort($this->hooks[$name], function($a, $b) {
            return $a['priority'] - $b['priority'];
        });
        
        return $this;
    }
    
    /**
     * Esegue hooks
     */
    protected function do_hook($name, $value = null, $params = []) {
        if (!isset($this->hooks[$name])) {
            return $value;
        }
        
        foreach ($this->hooks[$name] as $hook) {
            $value = call_user_func_array($hook['callback'], [$value, $this, $params]);
        }
        
        return $value;
    }
    
    /**
     * Verifica se esistono hooks
     */
    protected function has_hook($name) {
        return isset($this->hooks[$name]) && count($this->hooks[$name]) > 0;
    }
}
```

### 2. DOVE METTERE GLI HOOKS - LISTA COMPLETA

#### A. RENDERING HOOKS
```php
protected function render($mode = '', $return = false, $force_append = false) {
    // HOOK: Prima del render
    $this->do_hook('before_render', null, ['mode' => $mode]);
    
    // ... codice esistente ...
    
    // HOOK: Modifica HTML prima dell'output
    $output = $this->do_hook('filter_render_output', $output, ['mode' => $mode]);
    
    // HOOK: Dopo il render
    $this->do_hook('after_render', $output, ['mode' => $mode]);
    
    return $output;
}

protected function _list() {
    // HOOK: Prima di generare lista
    $this->do_hook('before_list');
    
    // ... genera lista ...
    
    // HOOK: Modifica dati lista
    $this->result_list = $this->do_hook('filter_list_data', $this->result_list);
    
    // HOOK: Dopo lista
    $this->do_hook('after_list', $this->result_list);
}
```

#### B. CRUD OPERATION HOOKS
```php
protected function _create() {
    // HOOK: Prima del form create
    $this->do_hook('before_create_form');
    
    // ... genera form ...
    
    // HOOK: Modifica campi form
    $this->fields_create = $this->do_hook('filter_create_fields', $this->fields_create);
    
    // HOOK: Dopo create form
    $this->do_hook('after_create_form');
}

protected function _save() {
    // HOOK: Prima del salvataggio
    $postdata = $this->do_hook('before_save', $postdata);
    
    // HOOK: Validazione custom
    if ($this->has_hook('custom_validation')) {
        $validation = $this->do_hook('custom_validation', true, ['data' => $postdata]);
        if (!$validation) {
            return false;
        }
    }
    
    if ($this->task == self::TASK_SAVE) {
        // HOOK: Prima INSERT
        $postdata = $this->do_hook('before_insert', $postdata);
        
        // ... insert nel database ...
        
        // HOOK: Dopo INSERT
        $this->do_hook('after_insert', $primary_value, ['data' => $postdata]);
        
    } else {
        // HOOK: Prima UPDATE
        $postdata = $this->do_hook('before_update', $postdata, ['primary' => $this->primary_val]);
        
        // ... update nel database ...
        
        // HOOK: Dopo UPDATE
        $this->do_hook('after_update', $this->primary_val, ['data' => $postdata]);
    }
    
    // HOOK: Dopo salvataggio (sia insert che update)
    $this->do_hook('after_save', $this->primary_val, ['task' => $this->task]);
}

protected function _remove() {
    // HOOK: Prima della cancellazione
    $can_delete = $this->do_hook('before_delete', true, ['primary' => $this->primary_val]);
    
    if (!$can_delete) {
        return false;
    }
    
    // ... delete dal database ...
    
    // HOOK: Dopo cancellazione
    $this->do_hook('after_delete', $this->primary_val);
}
```

#### C. QUERY HOOKS
```php
protected function _run_query() {
    // HOOK: Modifica query prima dell'esecuzione
    $query = $this->do_hook('filter_query', $query);
    
    // HOOK: Prima di eseguire query
    $this->do_hook('before_query', $query);
    
    // ... esegui query ...
    
    // HOOK: Modifica risultati
    $result = $this->do_hook('filter_query_result', $result);
    
    // HOOK: Dopo query
    $this->do_hook('after_query', $result);
}
```

#### D. FIELD RENDERING HOOKS
```php
protected function _render_field($field, $value, $primary) {
    // HOOK: Prima di renderizzare campo
    $value = $this->do_hook('before_field_render', $value, [
        'field' => $field,
        'primary' => $primary
    ]);
    
    // HOOK: Per tipo di campo specifico
    $value = $this->do_hook("filter_field_{$field['type']}", $value, [
        'field' => $field
    ]);
    
    // ... rendering del campo ...
    
    // HOOK: Dopo rendering campo
    $html = $this->do_hook('after_field_render', $html, [
        'field' => $field,
        'value' => $value
    ]);
    
    return $html;
}
```

#### E. VALIDATION HOOKS
```php
protected function _validation($postdata) {
    // HOOK: Prima della validazione
    $postdata = $this->do_hook('before_validation', $postdata);
    
    foreach ($this->validation_rules as $field => $rules) {
        // HOOK: Validazione per campo specifico
        $is_valid = $this->do_hook("validate_field_{$field}", true, [
            'value' => $postdata[$field],
            'rules' => $rules
        ]);
        
        if (!$is_valid) {
            $this->validation_errors[$field] = "Validation failed";
        }
    }
    
    // HOOK: Dopo validazione
    $this->validation_errors = $this->do_hook('after_validation', $this->validation_errors);
}
```

#### F. UPLOAD HOOKS
```php
protected function _upload($field) {
    // HOOK: Prima dell'upload
    $file_info = $this->do_hook('before_upload', $_FILES[$field], [
        'field' => $field
    ]);
    
    // ... processo upload ...
    
    // HOOK: Dopo upload
    $this->do_hook('after_upload', $uploaded_file, [
        'field' => $field,
        'path' => $upload_path
    ]);
}
```

#### G. RELATION HOOKS
```php
protected function _render_relation($field, $value) {
    // HOOK: Prima di caricare relazione
    $this->do_hook('before_relation_load', null, [
        'field' => $field,
        'value' => $value
    ]);
    
    // ... carica dati relazione ...
    
    // HOOK: Filtra opzioni relazione
    $options = $this->do_hook('filter_relation_options', $options, [
        'field' => $field
    ]);
    
    // HOOK: Dopo relazione
    $this->do_hook('after_relation_load', $options);
}
```

#### H. BUTTON/ACTION HOOKS
```php
public function button($url, $name, $icon = '', $class = '') {
    // HOOK: Modifica button
    $button_data = $this->do_hook('filter_button', [
        'url' => $url,
        'name' => $name,
        'icon' => $icon,
        'class' => $class
    ]);
    
    // ... crea button ...
    
    // HOOK: Dopo button creato
    $this->do_hook('after_button_create', $button_data);
}
```

#### I. PAGINATION HOOKS
```php
protected function _pagination() {
    // HOOK: Prima paginazione
    $this->do_hook('before_pagination');
    
    // HOOK: Modifica limiti
    $this->limit = $this->do_hook('filter_pagination_limit', $this->limit);
    
    // ... genera paginazione ...
    
    // HOOK: Dopo paginazione
    $this->do_hook('after_pagination', [
        'total' => $this->total_count,
        'limit' => $this->limit,
        'start' => $this->start
    ]);
}
```

#### J. SEARCH HOOKS
```php
protected function _search() {
    // HOOK: Prima della ricerca
    $search_data = $this->do_hook('before_search', $this->search);
    
    // HOOK: Modifica query di ricerca
    $search_query = $this->do_hook('filter_search_query', $search_query);
    
    // ... esegui ricerca ...
    
    // HOOK: Dopo ricerca
    $this->do_hook('after_search', $results);
}
```

#### K. EXPORT HOOKS
```php
public function export_csv() {
    // HOOK: Prima export
    $data = $this->do_hook('before_export', $this->result_list);
    
    // HOOK: Modifica dati export
    $data = $this->do_hook('filter_export_data', $data);
    
    // ... genera CSV ...
    
    // HOOK: Dopo export
    $this->do_hook('after_export', ['format' => 'csv']);
}
```

#### L. THEME/TEMPLATE HOOKS
```php
protected function load_view($file) {
    // HOOK: Prima di caricare view
    $file = $this->do_hook('filter_view_file', $file);
    
    // HOOK: Variabili per la view
    $vars = $this->do_hook('filter_view_vars', get_defined_vars());
    
    // ... carica view ...
    
    // HOOK: Dopo view caricata
    $this->do_hook('after_view_load', $file);
}
```

### 3. UTILIZZO PRATICO DEGLI HOOKS

```php
// Esempio 1: Modifica dati prima del salvataggio
$xcrud->add_hook('before_save', function($data, $xcrud) {
    $data['modified_by'] = $_SESSION['user_id'];
    $data['modified_at'] = date('Y-m-d H:i:s');
    return $data;
});

// Esempio 2: Validazione custom
$xcrud->add_hook('custom_validation', function($valid, $xcrud, $params) {
    $data = $params['data'];
    if ($data['price'] < $data['cost']) {
        $xcrud->set_exception('price', 'Price must be higher than cost');
        return false;
    }
    return true;
});

// Esempio 3: Dopo insert, invia email
$xcrud->add_hook('after_insert', function($primary, $xcrud, $params) {
    $data = $params['data'];
    mail('admin@site.com', 'New Record', 'ID: ' . $primary);
    return $primary;
});

// Esempio 4: Modifica HTML output
$xcrud->add_hook('filter_render_output', function($html, $xcrud) {
    // Aggiungi wrapper custom
    return '<div class="my-wrapper">' . $html . '</div>';
});

// Esempio 5: Hook per campi specifici
$xcrud->add_hook('filter_field_image', function($value, $xcrud, $params) {
    // Thumbnail automatico per immagini
    if ($value) {
        return '<img src="/thumb.php?img=' . $value . '" />';
    }
    return $value;
});
```

### 4. HOOKS PER ADDONS

```php
// Gli addon possono registrare hooks
class XcrudAddon_MyAddon {
    public function init($xcrud) {
        // Addon registra suoi hooks
        $xcrud->add_hook('before_render', [$this, 'modify_render']);
        $xcrud->add_hook('after_save', [$this, 'clear_cache']);
    }
    
    public function modify_render($value, $xcrud) {
        // Logica addon
        return $value;
    }
}
```

### 5. PRIORITÀ E ORDINE ESECUZIONE

```php
// Priorità bassa = eseguito prima
$xcrud->add_hook('before_save', 'validate_required', 5);    // Priorità 5
$xcrud->add_hook('before_save', 'sanitize_data', 10);       // Priorità 10 (default)
$xcrud->add_hook('before_save', 'add_timestamps', 15);      // Priorità 15

// Ordine esecuzione:
// 1. validate_required (5)
// 2. sanitize_data (10)
// 3. add_timestamps (15)
```

## RIASSUNTO: HOOKS EVERYWHERE! 🪝

**50+ PUNTI DI HOOK** distribuiti in tutto il codice:
- ✅ Ogni operazione CRUD
- ✅ Ogni rendering
- ✅ Ogni query
- ✅ Ogni validazione
- ✅ Ogni campo
- ✅ Ogni export
- ✅ Ogni upload
- ✅ Ogni relazione

**TUTTO DENTRO XCRUD.PHP** - Niente file separati!

Questo sistema permette estensibilità TOTALE senza modificare il core! 🚀