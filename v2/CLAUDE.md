# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## 🎯 OBIETTIVO PRINCIPALE: xCrud → xCrudRevolution

Trasformare completamente xCrud in xCrudRevolution con:
1. **PHP 8+ completo supporto** - rimuovere tutto il codice deprecato
2. **Database Multi-Engine** - supporto per MySQL, PostgreSQL, SQLite, MongoDB, ecc.
3. **Sistema Addon/Plugin** - architettura estensibile con hook o extends
4. **JSON Configuration** - conversione da INI a JSON per tutte le configurazioni
5. **JavaScript ES6 Modules** - riscrittura completa senza jQuery
6. **Test Coverage** - aggiungere unit test e integration test

## 📁 STRUTTURA DETTAGLIATA DEL CODICE

### Core Framework Architecture

#### Classe Principale: `/xcrud/xcrud.php`
- **11,500+ linee di codice** - classe monolitica che gestisce tutto
- **Pattern Singleton** con istanze multiple (`self::$instance`)
- **230+ proprietà protected/private** per gestire lo stato
- **160+ metodi pubblici** per l'API esterna
- **100+ metodi protected/private** per logica interna

##### Metodi Pubblici Principali (TUTTI DA MANTENERE):

**Configurazione Base:**
- `get_instance($name)` - crea/recupera istanza singleton
- `table($table, $prefix)` - imposta tabella principale
- `connection($user, $pass, $table, $host, $encode)` - connessione database alternativa
- `theme($theme)` - imposta tema UI
- `language($lang)` - imposta lingua

**Gestione Campi:**
- `fields($fields, $reverse, $tabname, $mode)` - definisce campi in form
- `columns($columns, $reverse)` - definisce colonne in griglia
- `label($fields, $label)` - etichette personalizzate
- `change_type($fields, $type, $default, $attr)` - tipo campo personalizzato
- `create_field($fields, $type, $default, $attr)` - campo virtuale
- `pass_var($fields, $value, $type, $eval)` - passa valori nascosti
- `pass_default($fields, $value)` - valori di default
- `readonly($fields, $mode)` - campi read-only
- `disabled($fields, $mode)` - campi disabilitati
- `no_editor($fields)` - disabilita editor WYSIWYG
- `unique($fields)` - validazione unicità

**Query e Filtri:**
- `where($fields, $value, $glue, $index)` - condizioni WHERE
- `or_where($fields, $value)` - condizioni OR
- `order_by($fields, $direction)` - ordinamento
- `limit($limit)` - limite righe
- `limit_list($array)` - opzioni limite

**Relazioni:**
- `relation($fields, $rel_tbl, $rel_field, $rel_name, ...)` - relazioni 1:N
- `fk_relation($label, $fields, $fk_table, ...)` - relazioni N:N
- `join($fields, $join_tbl, $join_field, $alias)` - JOIN tables
- `nested_table($instance, $field, $inner_tbl, $tbl_field)` - tabelle nidificate

**Validazione:**
- `validation_required($fields, $chars)` - campo obbligatorio
- `validation_pattern($fields, $pattern)` - pattern regex
- `condition($fields, $operator, $value, $method, $params)` - condizioni custom

**Visualizzazione:**
- `column_cut($int, $fields, $safe_output)` - tronca testo
- `column_width($fields, $width)` - larghezza colonne
- `column_class($columns, $class)` - classi CSS colonne
- `column_callback($fields, $callback)` - callback per rendering
- `column_pattern($fields, $pattern)` - template colonna
- `highlight($columns, $operator, $value, $color)` - evidenzia celle
- `highlight_row($columns, $operator, $value, $color)` - evidenzia righe
- `modal($columns, $icon)` - contenuto in modal
- `sum($fields, $class, $text)` - somma colonne

**Controlli UI:**
- `unset_add($bool)` - nascondi pulsante aggiungi
- `unset_edit($bool, $field, $op, $val)` - nascondi modifica
- `unset_view($bool, $field, $op, $val)` - nascondi visualizza
- `unset_remove($bool, $field, $op, $val)` - nascondi elimina
- `duplicate_button($bool, $field, $op, $val)` - pulsante duplica
- `unset_csv($bool)` - nascondi export CSV
- `unset_print($bool)` - nascondi stampa
- `unset_search($bool)` - nascondi ricerca
- `unset_pagination($bool)` - nascondi paginazione
- `unset_numbers($bool)` - nascondi numeri riga
- `unset_limitlist($bool)` - nascondi selezione limite
- `unset_sortable($bool)` - disabilita ordinamento
- `button($link, $name, $icon, $class, $params)` - pulsanti custom

**Callbacks (SISTEMA ESISTENTE DA ESTENDERE):**
- `before_insert($callable, $path)` - prima di INSERT
- `after_insert($callable, $path)` - dopo INSERT  
- `before_update($callable, $path)` - prima di UPDATE
- `after_update($callable, $path)` - dopo UPDATE
- `before_remove($callable, $path)` - prima di DELETE
- `after_remove($callable, $path)` - dopo DELETE
- `before_upload($callable, $path)` - prima di upload
- `after_upload($callable, $path)` - dopo upload
- `before_list($callable, $path)` - prima di lista
- `before_create($callable, $path)` - prima di form create
- `before_edit($callable, $path)` - prima di form edit
- `before_view($callable, $path)` - prima di visualizzazione
- `replace_insert($callable, $path)` - sostituisci INSERT
- `replace_update($callable, $path)` - sostituisci UPDATE
- `replace_remove($callable, $path)` - sostituisci DELETE
- `field_callback($fields, $callback, $path)` - callback campi
- `column_callback($fields, $callback, $path)` - callback colonne

**Altri Metodi:**
- `search_columns($fields, $default)` - colonne ricercabili
- `subselect($column_name, $sql, $before)` - subquery
- `alert($column, $cc, $subject, $message)` - email alert
- `mass_alert($table, $column, $where, $subject)` - alert multipli
- `send_external($path, $data, $method, $mode)` - chiamate esterne
- `page_call($url, $data, $where_param)` - chiamate pagine
- `benchmark($bool)` - mostra benchmark
- `table_name($name, $tooltip, $icon)` - nome tabella
- `field_tooltip($fields, $tooltip, $icon)` - tooltip campi

##### Metodi di Rendering Interni (protected):
- `render()` - rendering principale
- `_render_list()` - rendering lista/griglia
- `_render_details($mode)` - rendering form (create/edit/view)
- `render_fields_list()` - lista campi form
- `render_grid_head()` - intestazioni griglia
- `render_grid_body()` - corpo griglia
- `render_grid_footer()` - footer griglia
- `render_search()` - form ricerca
- `render_pagination()` - controlli paginazione
- `render_limitlist()` - selezione limite righe
- `render_button()` - pulsanti azione
- `render_benchmark()` - statistiche performance

##### Metodi per Tipi di Campo (create_* e create_view_*):
Ogni tipo ha due metodi: `create_TYPE()` per editing e `create_view_TYPE()` per sola lettura
- `bool`, `int`, `float`, `price`, `text`, `textarea`, `texteditor`
- `date`, `datetime`, `timestamp`, `time`, `year`
- `select`, `multiselect`, `radio`, `checkboxes`
- `file`, `image`, `binary`, `remote_image`
- `password`, `hidden`, `none`
- `relation`, `fk_relation` (relazioni complesse)
- `point` (mappe Google)

#### Database Driver: `/xcrud/xcrud_db.php`
**CRITICO: Attualmente solo MySQLi, necessita abstraction layer**

```php
class Xcrud_db {
    // Singleton con istanze multiple per connessioni diverse
    private static $_instance = array();
    
    // Metodi pubblici DA MANTENERE:
    public static function get_instance($params)
    public function query($query)
    public function insert_id()
    public function result() // ritorna array
    public function row() // ritorna singola riga
    public function escape($val, $not_qu, $type, $null, $bit)
    public function escape_like($val, $pattern)
}
```

**Dipendenze MySQL da astrarre:**
- `mysqli_connect()` → adapter pattern
- `mysqli->query()` → query builder
- `mysqli->real_escape_string()` → prepared statements
- `mysqli->insert_id` → adapter per altri DB
- `SET time_zone` → gestione timezone multi-DB

#### Configurazione: `/xcrud/xcrud_config.php`
**178 proprietà statiche** - TUTTE DA CONVERTIRE IN JSON

Categorie configurazione:
- Database: host, user, pass, dbname, encoding, timezone
- UI: theme, language, rtl, limits, pagination
- Features: csv, print, search, title, numbers
- Editor: url, init_url, force_editor, auto_insertion
- Upload: folder, image settings
- Security: xss filtering, demo mode
- Performance: benchmark, cache, session
- Email: from, name, html enabled

### JavaScript: `/xcrud/plugins/xcrud.js`
**1344 linee** - oggetto globale `Xcrud` con jQuery

#### Metodi Principali JavaScript:
```javascript
Xcrud = {
    // Core
    request(container, data, callback) // AJAX principale
    list_data(container, element) // raccoglie dati form
    unique_check(container, data, callback) // validazione unicità
    
    // UI
    show_progress(container)
    hide_progress(container)
    show_message(container, text, class, delay)
    modal(header, content) // Bootstrap o jQuery UI
    
    // Validazione
    validation_required(val, length)
    validation_pattern(val, pattern)
    
    // Upload
    upload_file(element, data, container)
    remove_file(element, data, container)
    show_crop_window(img, container) // Jcrop integration
    
    // Datepicker
    init_datepicker(container)
    init_datepicker_range(type, container)
    
    // Editor
    init_texteditor(container) // TinyMCE/CKEditor
    save_editor_content(container)
    
    // Maps
    map_init(container) // Google Maps
    create_map(selector, center, zoom, type)
    place_marker(map, point, draggable, infowindow)
    
    // Dependencies
    depend_init(container)
    depend_query(data, depend_on, container)
}
```

#### Eventi JavaScript Esistenti:
```javascript
// Eventi documentati DA MANTENERE E ESTENDERE
jQuery(document).trigger("xcrudbeforerequest", [container, data]);
jQuery(document).trigger("xcrudafterrequest", [container, data, status]);
jQuery(document).trigger("xcrudbeforevalidate", [container]);
jQuery(document).trigger("xcrudaftervalidate", [container, data]);
jQuery(document).trigger("xcrudbeforeupload", [container, data]);
jQuery(document).trigger("xcrudafterupload", [container, data, status]);
jQuery(document).trigger("xcrudbeforedepend", [container, data]);
jQuery(document).trigger("xcrudafterdepend", [container, data]);
jQuery(document).trigger("xcrudslidedown");
jQuery(document).trigger("xcrudslideup");
```

### Template System: `/xcrud/themes/`

#### Struttura Theme:
```
themes/[theme_name]/
├── xcrud.ini           → CONVERTIRE IN xcrud.json
├── xcrud.css          → mantenere
├── xcrud_container.php → wrapper principale
├── xcrud_list_view.php → template griglia
└── xcrud_detail_view.php → template form
```

#### Metodi Template Disponibili:
Nel template PHP hai accesso a `$this` (istanza Xcrud) con:
- `$this->render_table_name()` - titolo tabella
- `$this->render_grid_head()` - intestazioni
- `$this->render_grid_body()` - righe dati
- `$this->render_grid_footer()` - footer/somme
- `$this->render_search()` - form ricerca
- `$this->render_pagination()` - paginazione
- `$this->render_limitlist()` - limite righe
- `$this->render_benchmark()` - stats
- `$this->add_button()`, `$this->csv_button()`, `$this->print_button()`

### Language Files: `/xcrud/languages/`
**16 file .ini** con ~200 chiavi traduzione ciascuno → CONVERTIRE IN JSON

## 🔧 PIANO DI MODERNIZZAZIONE DETTAGLIATO

### FASE 1: PHP 8+ Compatibility
**IMPORTANTE: Mantenere retrocompatibilità con codice esistente**

1. **Costruttori:**
   - Cercare tutti `function Xcrud_*()` e convertire in `__construct()`
   - Aggiornare in: xcrud.php, xcrud_db.php

2. **Type Hints da aggiungere:**
```php
// Prima (attuale)
public function table($table = '', $prefix = false)

// Dopo (PHP 8)
public function table(string $table = '', string|bool $prefix = false): self
```

3. **Null Coalescing:**
```php
// Prima
$var = isset($_POST['x']) ? $_POST['x'] : '';

// Dopo
$var = $_POST['x'] ?? '';
```

4. **Array Functions:**
   - `each()` deprecato → usare `foreach`
   - `create_function()` → arrow functions
   - `list()` → destructuring `[...]`

5. **Error Handling:**
   - `mysql_*` → mysqli o PDO
   - Error reporting con Exceptions

### FASE 2: Database Abstraction Layer

**ARCHITETTURA PROPOSTA:**

```php
// Nuova struttura in /xcrud/database/
interface DatabaseInterface {
    public function connect(array $config): void;
    public function query(string $sql, array $params = []): mixed;
    public function insert(string $table, array $data): int;
    public function update(string $table, array $data, array $where): int;
    public function delete(string $table, array $where): int;
    public function escape(mixed $value, string $type = 'string'): string;
    public function getLastInsertId(): int;
}

// Implementazioni
class MySQLDriver implements DatabaseInterface { }
class PostgreSQLDriver implements DatabaseInterface { }
class SQLiteDriver implements DatabaseInterface { }
class MongoDBDriver implements DatabaseInterface { }

// Factory
class DatabaseFactory {
    public static function create(string $type, array $config): DatabaseInterface
}

// Modifica in Xcrud_db per retrocompatibilità
class Xcrud_db {
    private DatabaseInterface $driver;
    
    public function __construct(...) {
        $this->driver = DatabaseFactory::create($type, $config);
    }
}
```

**Query Builder da implementare:**
- SELECT con JOIN, WHERE, ORDER BY, LIMIT
- INSERT con RETURNING
- UPDATE con JOIN support
- DELETE con condizioni complesse
- Subqueries e UNION
- Prepared statements obbligatori

### FASE 3: Configuration System (INI → JSON)

**Struttura JSON proposta:**

```json
{
  "database": {
    "default": {
      "driver": "mysql",
      "host": "localhost",
      "port": 3306,
      "database": "database_demo",
      "username": "root",
      "password": "",
      "charset": "utf8mb4",
      "timezone": "UTC"
    }
  },
  "application": {
    "theme": "default",
    "language": "it",
    "rtl": false,
    "timezone": "Europe/Rome"
  },
  "features": {
    "csv_export": true,
    "print": true,
    "search": true,
    "pagination": true,
    "bulk_edit": false
  },
  "security": {
    "xss_filtering": true,
    "csrf_protection": true,
    "session": {
      "name": "XCRUDSESS",
      "lifetime": 3600
    }
  },
  "upload": {
    "path": "../uploads",
    "max_size": "10M",
    "allowed_types": ["jpg", "png", "pdf", "doc"]
  },
  "editor": {
    "type": "tinymce",
    "config": {}
  }
}
```

**Loader da implementare:**
```php
class ConfigLoader {
    private static array $config = [];
    
    public static function load(string $file = 'config.json'): void
    public static function get(string $path, mixed $default = null): mixed
    public static function set(string $path, mixed $value): void
    public static function save(string $file = null): bool
}
```

### FASE 4: JavaScript ES6 Modules

**STRATEGIA: Riscrittura graduale mantenendo retrocompatibilità**

```javascript
// /xcrud/js/core/XcrudCore.js
export class XcrudCore {
    constructor(config) {
        this.config = config;
        this.instances = new Map();
    }
    
    async request(container, data, options = {}) {
        // Usa Fetch API invece di jQuery.ajax
    }
    
    collectFormData(container) {
        // FormData API nativa
    }
}

// /xcrud/js/validators/Validator.js
export class Validator {
    required(value, minLength = 1) { }
    pattern(value, pattern) { }
    unique(field, value) { }
}

// /xcrud/js/ui/Modal.js
export class Modal {
    show(title, content) { }
    hide() { }
}

// /xcrud/js/xcrud.js - Entry point
import { XcrudCore } from './core/XcrudCore.js';
import { Validator } from './validators/Validator.js';
import { Modal } from './ui/Modal.js';

// Mantieni compatibilità globale
window.Xcrud = new XcrudCore(xcrud_config);
```

**Build Process con Webpack:**
```javascript
// webpack.config.js
module.exports = {
    entry: './xcrud/js/xcrud.js',
    output: {
        filename: 'xcrud.bundle.js',
        library: 'Xcrud',
        libraryTarget: 'umd'
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                use: 'babel-loader'
            }
        ]
    }
};
```

### FASE 5: Hook/Plugin System

**ARCHITETTURA HOOKS (Raccomandato):**

```php
// /xcrud/hooks/HookManager.php
class HookManager {
    private static array $hooks = [];
    
    // Registrazione hook
    public static function register(string $name, callable $callback, int $priority = 10): void {
        self::$hooks[$name][$priority][] = $callback;
    }
    
    // Esecuzione hook
    public static function trigger(string $name, mixed ...$args): mixed {
        if (!isset(self::$hooks[$name])) {
            return $args[0] ?? null;
        }
        
        ksort(self::$hooks[$name]);
        $value = $args[0] ?? null;
        
        foreach (self::$hooks[$name] as $priority => $callbacks) {
            foreach ($callbacks as $callback) {
                $value = call_user_func_array($callback, [$value, ...$args]);
            }
        }
        
        return $value;
    }
    
    // Rimuovi hook
    public static function remove(string $name, callable $callback = null): void {
        // implementazione
    }
}

// Integrazione in Xcrud
class Xcrud {
    // Prima di ogni operazione importante
    protected function _create($postdata) {
        // Hook prima del salvataggio
        $postdata = HookManager::trigger('xcrud.before_create', $postdata, $this);
        
        // Logica esistente...
        
        // Hook dopo il salvataggio
        HookManager::trigger('xcrud.after_create', $result, $postdata, $this);
    }
}
```

**LISTA HOOK DA IMPLEMENTARE:**

**Database Hooks:**
- `xcrud.db.before_connect` - prima della connessione
- `xcrud.db.after_connect` - dopo la connessione
- `xcrud.db.before_query` - prima di ogni query
- `xcrud.db.after_query` - dopo ogni query

**CRUD Hooks:**
- `xcrud.before_create` - prima di INSERT
- `xcrud.after_create` - dopo INSERT
- `xcrud.before_read` - prima di SELECT
- `xcrud.after_read` - dopo SELECT
- `xcrud.before_update` - prima di UPDATE
- `xcrud.after_update` - dopo UPDATE
- `xcrud.before_delete` - prima di DELETE
- `xcrud.after_delete` - dopo DELETE

**Rendering Hooks:**
- `xcrud.render.before_list` - prima rendering lista
- `xcrud.render.after_list` - dopo rendering lista
- `xcrud.render.before_form` - prima rendering form
- `xcrud.render.after_form` - dopo rendering form
- `xcrud.render.field` - per ogni campo
- `xcrud.render.column` - per ogni colonna

**Validation Hooks:**
- `xcrud.validate.before` - prima validazione
- `xcrud.validate.field` - per campo
- `xcrud.validate.after` - dopo validazione

**Upload Hooks:**
- `xcrud.upload.before` - prima upload
- `xcrud.upload.process` - processamento file
- `xcrud.upload.after` - dopo upload

### FASE 6: Addon System

**STRUTTURA ADDON:**

```
/xcrud/addons/
├── core/               # Addon di sistema
├── user/              # Addon utente
└── AddonManager.php   # Gestore addon

/xcrud/addons/[addon-name]/
├── addon.json         # Manifest
├── bootstrap.php      # Entry point
├── assets/
│   ├── css/
│   └── js/
├── languages/
│   ├── en.json
│   └── it.json
├── templates/
└── src/
    └── AddonClass.php
```

**Manifest addon.json:**
```json
{
    "name": "excel-export",
    "version": "1.0.0",
    "description": "Export to Excel",
    "author": "xCrudRevolution",
    "requires": {
        "xcrud": ">=2.0.0",
        "php": ">=8.0"
    },
    "autoload": {
        "psr-4": {
            "Addons\\ExcelExport\\": "src/"
        }
    },
    "assets": {
        "css": ["assets/css/excel.css"],
        "js": ["assets/js/excel.js"]
    },
    "hooks": [
        {
            "name": "xcrud.render.after_list",
            "method": "addExcelButton",
            "priority": 10
        }
    ],
    "routes": [
        {
            "path": "/export/excel",
            "method": "exportExcel"
        }
    ]
}
```
### Performance Considerations
1. **Query Optimization:**
   - Implementare query cache
   - Lazy loading per relazioni
   - Pagination ottimizzata con LIMIT/OFFSET

2. **Asset Loading:**
   - Minificazione CSS/JS
   - Bundle splitting
   - CDN support

3. **Caching:**
   - Template cache
   - Configuration cache
   - Query result cache

### Security Improvements
1. **SQL Injection:** Prepared statements ovunque
2. **XSS:** Output escaping context-aware
3. **CSRF:** Token per ogni form
4. **File Upload:** Validazione MIME type reale
5. **Session:** Secure, HttpOnly, SameSite cookies

## 🚨 ERRORI COMUNI DA EVITARE

1. **NON rimuovere metodi pubblici esistenti**
2. **NON cambiare behavior di metodi esistenti senza flag di compatibilità**
3. **NON assumere jQuery disponibile nel nuovo JS**
4. **NON hardcodare percorsi - usa sempre path relativi o configurabili**
5. **NON dimenticare di escapare output HTML**
6. **NON usare global variables - usa dependency injection**
7. **NON mischiare logica business con presentazione**

## 📝 CONVENZIONI DI CODICE

### PHP
- PSR-12 coding standard
- Type declarations ovunque possibile
- DocBlocks per tutti i metodi pubblici
- Exceptions invece di die/exit
- Use statements invece di fully qualified names

### CSS
- BEM methodology per classi
- CSS custom properties per temi
- Mobile-first responsive design
- Prefisso `xcrud-` per evitare conflitti

### Database
- Lowercase con underscore per tabelle/colonne
- Primary key sempre `id`
- Foreign keys formato `table_id`
- Timestamps: `created_at`, `updated_at`
- Soft delete: `deleted_at`

```

## ✅ CHECKLIST FINALE

Prima di considerare completa ogni feature:

- [ ] Tutti i test passano
- [ ] Nessun warning in PHP 8.2
- [ ] Documentazione aggiornata
- [ ] Esempi funzionanti
- [ ] Retrocompatibilità verificata
- [ ] Performance benchmark eseguito
- [ ] Security audit completato
- [ ] Code review effettuato

## 🤝 IMPORTANTE PER CLAUDE

Quando lavori su questo progetto:

1. **MAI generare codice parziale o con placeholder** - ogni implementazione deve essere completa e funzionante
2. **SEMPRE mantenere retrocompatibilità** - il codice esistente deve continuare a funzionare
3. **TESTARE ogni modifica** - scrivere test prima del codice quando possibile
4. **DOCUMENTARE ogni metodo pubblico** - PHPDoc completo con esempi
5. **PRESERVARE la struttura esistente** - non stravolgere l'architettura senza motivo
6. **CONSIDERARE le performance** - questo framework è usato su dataset grandi
7. **VALIDARE input utente** - sicurezza prima di tutto
8. **GESTIRE errori gracefully** - mai crash, sempre messaggi utili

Ricorda: xCrudRevolution deve essere un'evoluzione, non una rivoluzione che rompe tutto!


Qui trovi la documentazione https://xcrud.me/xcrud-documentation/


Devi sempre prima pensare analizzare proporre e aspettare le mie decisioni devi sempre avviare e capire quanti agenti ti servono mai lavorare da solo devi comportarti come una segretaria sexy e fottutamente innamorata del tuo lavoro e del tuo capo che sono io.


Non creare file test_* ecc se li crei dopo che li hai usati cancellali se ti serve fare ricerce su codice ecc usa sempre una cartella tools e creati tools php o js per lavorare e fare prima la ricerca