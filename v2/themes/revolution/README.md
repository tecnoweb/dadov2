# 🎨 Revolution Theme - Documentazione Completa

Il tema **Revolution** è un tema professionale e moderno per xCrudRevolution che combina eleganza, funzionalità e performance. Basato sui pattern di Bootstrap ma con classi personalizzate `revo-*`.

## 📋 Caratteristiche Principali

### ✨ Design Moderno
- **Gradiente personalizzato** con colori primari e secondari
- **Shadow e blur** per effetti di profondità
- **Animazioni fluide** con transizioni CSS
- **Responsive design** ottimizzato per tutti i dispositivi
- **Tipografia professionale** con font system moderni

### 🎯 Componenti Avanzati
- **FAB (Floating Action Button)** dinamico che rileva automaticamente i pulsanti disponibili
- **Sistema di paginazione** moderno con hover effects
- **Form styling** professionale con focus states
- **Tabelle responsive** con hover animations
- **Sistema di messaggi** con slide animations

### 🔧 Personalizzazione CSS
- **CSS Custom Properties** per personalizzazione facile
- **Classi BEM-like** con prefisso `revo-`
- **Sistema di colori** coerente e accessibile
- **Utilities classes** per spacing, alignment, etc.

---

## 🏗️ Struttura del Tema

```
themes/revolution/
├── xcrud.ini           # Configurazione classi CSS
├── xcrud.css          # Framework CSS completo (1000+ righe)
├── xcrud_container.php # Template wrapper con FAB
├── xcrud_list_view.php # Template griglia
├── xcrud_detail_view.php # Template form
└── README.md          # Questa documentazione
```

---

## 🎨 Sistema di Colori

### Palette Principale
```css
--revo-primary: #667eea    /* Blu principale */
--revo-secondary: #764ba2  /* Viola secondario */
--revo-success: #10b981    /* Verde successo */
--revo-danger: #ef4444     /* Rosso errore */
--revo-warning: #f59e0b    /* Giallo warning */
--revo-info: #3b82f6       /* Blu info */
--revo-light: #f8fafc      /* Grigio chiaro */
--revo-dark: #1e293b       /* Grigio scuro */
--revo-border: #d1d5db     /* Bordi */
```

### Gradient Principale
```css
--revo-gradient: linear-gradient(135deg, var(--revo-primary), var(--revo-secondary));
```

---

## 🧩 Classi CSS Principali

### 🔲 Bottoni (revo-btn)

#### Classi Base
```css
.revo-btn              /* Bottone base */
.revo-btn-sm           /* Bottone piccolo */
.revo-btn-lg           /* Bottone grande */
```

#### Varianti di Colore
```css
.revo-btn-primary      /* Bottone principale (gradient) */
.revo-btn-secondary    /* Bottone secondario */
.revo-btn-success      /* Bottone verde */
.revo-btn-danger       /* Bottone rosso */
.revo-btn-warning      /* Bottone giallo */
.revo-btn-info         /* Bottone blu */
.revo-btn-default      /* Bottone bianco */
```

#### Gruppi di Bottoni
```css
.revo-btn-group        /* Gruppo di bottoni collegati */
```

### 📋 Form Controls

#### Input Fields
```css
.revo-input           /* Input text generico */
.revo-number          /* Input numerico */
.revo-decimal         /* Input decimale */
.revo-text            /* Input text */
.revo-password        /* Input password */
.revo-date            /* Input data */
.revo-datetime        /* Input data/ora */
.revo-time            /* Input ora */
.revo-timestamp       /* Input timestamp */
.revo-price           /* Input prezzo */
.revo-coord           /* Input coordinate */
.revo-address         /* Input indirizzo */
.revo-relation        /* Select relazione */
.revo-remote-img      /* Input immagine remota */
.revo-point           /* Input punto mappa */
```

#### Other Controls
```css
.revo-textarea        /* Area di testo */
.revo-select          /* Select dropdown */
.revo-multiselect     /* Select multipla */
.revo-checkbox        /* Checkbox */
.revo-radio           /* Radio button */
.revo-input-sm        /* Input piccolo */
.revo-inline          /* Input inline */
```

#### Gruppi e Container
```css
.revo-radio-group     /* Gruppo radio buttons */
.revo-checkbox-group  /* Gruppo checkboxes */
```

### 📊 Tabelle

#### Struttura Base
```css
.xcrud-list           /* Tabella principale */
.xcrud-list th        /* Header tabella */
.xcrud-list td        /* Celle tabella */
.xcrud-list tbody tr:hover /* Hover righe */
```

#### Classi Utilità Colonne
```css
.align-left           /* Allineamento sinistro */
.align-right          /* Allineamento destro */
.align-center         /* Allineamento centro */
.font-bold            /* Grassetto */
.font-italic          /* Corsivo */
.text-underline       /* Sottolineato */
```

#### Azioni e Controlli
```css
.xcrud-actions        /* Colonna azioni */
.xcrud-num            /* Numerazione righe */
.xcrud-sum            /* Righe somma */
```

### 📄 Form Layout

#### Struttura Form
```css
.revo-form           /* Container form */
.revo-form-row       /* Riga form */
.revo-label          /* Label campo (25% width) */
.revo-field          /* Container campo (75% width) */
```

### 🔍 Sistema di Ricerca

#### Container e Controlli
```css
.revo-search         /* Container ricerca */
.revo-search-input   /* Input ricerca principale */
.revo-search-from    /* Input da */
.revo-search-to      /* Input a */
.revo-search-range   /* Input range */
.revo-search-fields  /* Select campi */
.revo-search-dropdown /* Dropdown ricerca */
.revo-search-go      /* Bottone cerca */
.revo-search-reset   /* Bottone reset */
.revo-search-open    /* Bottone apri ricerca */
```

### 📑 Paginazione

#### Struttura Paginazione
```css
.revo-pagination     /* Container paginazione */
.revo-page-item      /* Item singolo pagina */
.revo-active         /* Pagina attiva */
.revo-dots           /* Puntini separatori */
```

### 🗂️ Sistema Tabs

#### Struttura Tabs
```css
.revo-tabs           /* Container tabs */
.revo-tabs-nav       /* Navigazione tabs */
.revo-tab-item       /* Item singolo tab */
.revo-tab-link       /* Link tab */
.revo-tab-content    /* Contenuto tabs */
.revo-tab-pane       /* Pannello singolo tab */
```

### 📁 Upload e Files

#### File Management
```css
.revo-upload         /* Bottone upload */
.revo-remove         /* Bottone rimuovi */
.revo-image          /* Immagine preview */
.revo-file-link      /* Link file */
.revo-no-file        /* Messaggio nessun file */
```

#### Container Upload
```css
.xcrud-file-container /* Container file */
.xcrud-add-file      /* Bottone aggiungi file */
.xcrud-upload        /* Input file nascosto */
```

### 💬 Sistema Messaggi

#### Tipi di Messaggio
```css
.xcrud-message       /* Messaggio base */
.xcrud-message.success /* Messaggio successo */
.xcrud-message.error  /* Messaggio errore */
.xcrud-message.info   /* Messaggio info */
.xcrud-message.note   /* Messaggio nota */
```

### 🎯 FAB System (Floating Action Button)

#### Struttura FAB
```css
.revo-fab            /* Container FAB */
.revo-fab-trigger    /* Bottone principale FAB */
.revo-fab-menu       /* Menu FAB */
.revo-fab-item       /* Item menu FAB */
.revo-fab.active     /* FAB attivo */
```

#### Varianti Colore FAB
```css
.revo-fab-add        /* FAB verde (add) */
.revo-fab-export     /* FAB blu (export) */
.revo-fab-print      /* FAB viola (print) */
```

---

## ⚙️ Configurazione xcrud.ini

Il file `xcrud.ini` contiene tutte le mappature tra gli elementi xCrud e le classi CSS Revolution:

### 📤 Upload Elements
```ini
upload_button = "revo-btn revo-btn-success revo-upload"
upload_button_icon = "fas fa-upload"
remove_button = "revo-btn revo-btn-danger revo-remove"
remove_button_icon = "fas fa-trash"
image = "revo-image"
file_name = "revo-file-link"
no_file = "revo-no-file"
```

### 📋 Form Structure
```ini
details_container = "revo-form"
details_row = "revo-form-row"
details_label_cell = "revo-label"
details_field_cell = "revo-field"
```

### 📝 Form Fields
```ini
text_field = "revo-input revo-text"
select_field = "revo-select"
textarea_field = "revo-textarea"
bool_field = "revo-checkbox"
date_field = "revo-input revo-date"
# ... tutti gli altri tipi di campo
```

### 🔍 Search Elements
```ini
search_container = "revo-search"
search_go = "revo-btn revo-btn-primary revo-search-go"
search_reset = "revo-btn revo-btn-secondary revo-search-reset"
search_phrase = "revo-input revo-input-sm revo-search-input"
```

### 🎛️ Grid Buttons
```ini
grid_edit = "revo-btn revo-btn-warning revo-edit"
grid_edit_icon = "fas fa-edit"
grid_remove = "revo-btn revo-btn-danger revo-delete"
grid_remove_icon = "fas fa-trash"
grid_view = "revo-btn revo-btn-info revo-view"
grid_view_icon = "fas fa-search"
```

### 📑 Pagination
```ini
pagination_container = "revo-pagination"
pagination_item = "revo-page-item"
pagination_active = "revo-active"
pagination_dots = "revo-dots"
```

---

## 🚀 Sistema FAB Avanzato

### Funzionalità Automatiche

Il FAB (Floating Action Button) rileva automaticamente i bottoni disponibili nella pagina e li aggiunge al menu fluttuante:

#### 🔍 Rilevamento Automatico
- **Add/Aggiungi**: Bottoni con classe `revo-btn-success` o testo contenente "add"/"aggiungi"
- **Export/CSV**: Bottoni con testo contenente "csv"/"export"/"esport"
- **Print/Stampa**: Bottoni con testo contenente "print"/"stampa"

#### 🎯 Posizionamento
- **Desktop**: Bottom right (24px from edges)
- **Mobile**: Bottom right (16px from edges)
- **RTL Support**: Automaticamente a sinistra per lingue RTL

#### ⚡ Animazioni
- **Hover effect**: Scala e rotazione del trigger
- **Menu slide**: Fade in/out con translateY
- **Click outside**: Chiude automaticamente il menu

### JavaScript Integration

```javascript
// Il FAB si popola automaticamente al caricamento della pagina
// e si aggiorna dopo ogni chiamata AJAX grazie al MutationObserver

function populateFAB() {
    // Rileva bottoni disponibili
    const actionButtons = xcrudContainer.querySelectorAll(
        '.revo-btn-success, .revo-btn[class*="add"], ' +
        '.revo-btn[class*="csv"], .revo-btn[class*="export"], ' +
        '.revo-btn[class*="print"], .xcrud-top-actions .revo-btn'
    );
    
    // Crea items FAB dinamicamente
    fabItems.forEach(item => {
        const fabItem = document.createElement('button');
        fabItem.className = 'revo-fab-item ' + item.color;
        fabItem.innerHTML = '<i class="' + item.icon + '"></i>';
        fabItem.addEventListener('click', item.action);
        fabMenu.appendChild(fabItem);
    });
}
```

---

## 📱 Design Responsive

### Breakpoints

#### Desktop (>768px)
- **Form Layout**: Labels a sinistra (25%) + Fields a destra (75%)
- **FAB Size**: 56px con trigger da 24px
- **Pagination**: Items da 36px
- **Nav**: Layout orizzontale

#### Mobile (≤768px)
- **Form Layout**: Layout verticale, labels sopra i fields
- **FAB Size**: 48px con trigger da 20px
- **Pagination**: Items da 32px
- **Nav**: Layout verticale
- **Search**: Form verticale con input full-width

### Media Queries

```css
@media (max-width: 768px) {
    .revo-form-row {
        flex-direction: column;
        gap: 8px;
    }
    
    .revo-label {
        flex: none;
        max-width: none;
        padding-right: 0;
    }
    
    .revo-fab-trigger {
        width: 48px;
        height: 48px;
    }
}
```

---

## 🌐 Supporto RTL

### Funzionalità RTL
- **Direzione testo**: `direction: rtl` automatico
- **FAB Position**: Spostamento automatico da destra a sinistra
- **Actions Fixed**: Posizionamento corretto per hover tables
- **Toggle Show**: Float corretto per pulsanti

### Classi RTL
```css
.xcrud.xcrud_rtl {
    direction: rtl;
}

.xcrud.xcrud_rtl .revo-fab {
    right: auto;
    left: 24px;
}
```

---

## 🎨 Personalizzazione Avanzata

### Modificare i Colori

Per personalizzare i colori del tema, modifica le CSS Custom Properties:

```css
:root {
    --revo-primary: #your-color;     /* Colore primario */
    --revo-secondary: #your-color;   /* Colore secondario */
    --revo-success: #your-color;     /* Verde successo */
    /* ... altri colori */
}
```

### Modificare le Dimensioni

```css
:root {
    --revo-radius: 8px;              /* Raggio bordi */
    --revo-shadow: 0 2px 4px rgba(0,0,0,0.1); /* Ombre */
    --revo-transition: all 0.3s ease; /* Transizioni */
}
```

### Aggiungere Nuove Varianti Button

```css
.revo-btn-custom {
    background: linear-gradient(135deg, #ff6b6b, #ee5a6f);
    color: white;
    border-color: #ff6b6b;
}
```

---

## 🔧 Integrazione con xCrud

### Uso nel Codice PHP

```php
<?php
// Imposta il tema Revolution
$xcrud = Xcrud::get_instance();
$xcrud->theme('revolution');

// Le classi vengono applicate automaticamente
// secondo la configurazione in xcrud.ini
$xcrud->table('users');
$xcrud->columns('name,email,created_at');
echo $xcrud->render();
?>
```

### Personalizzazione Classi

Puoi modificare le classi in `xcrud.ini`:

```ini
; Cambia il colore del bottone edit da warning a info
grid_edit = "revo-btn revo-btn-info revo-edit"

; Aggiungi classi custom ai tuoi form
text_field = "revo-input revo-text my-custom-class"
```

---

## 🛠️ Troubleshooting

### Problemi Comuni

#### FAB non appare
1. Verifica che ci siano bottoni rilevabili nella pagina
2. Controlla la console per errori JavaScript
3. Assicurati che FontAwesome sia caricato

#### Stili non applicati
1. Verifica che `xcrud.css` sia caricato correttamente
2. Controlla che il tema sia impostato su 'revolution'
3. Svuota la cache del browser

#### Form non responsive
1. Assicurati che il viewport meta tag sia presente
2. Verifica le media queries CSS
3. Controlla conflitti con altri CSS

### Debug CSS

Usa gli strumenti developer per ispezionare le classi applicate:

```javascript
// Mostra tutte le classi revo-* nella pagina
document.querySelectorAll('[class*="revo-"]').forEach(el => {
    console.log(el.className);
});
```

---

## 🚀 Performance

### Ottimizzazioni Incluse

#### CSS
- **CSS Custom Properties** per rendering veloce
- **Transizioni ottimizzate** con `transform` invece di proprietà layout
- **Selettori specifici** per evitare conflitti
- **Media queries ottimizzate** per mobile-first

#### JavaScript FAB
- **Event delegation** per performance migliori
- **MutationObserver** ottimizzato per AJAX
- **Debouncing** per resize events
- **Lazy loading** delle animazioni

#### Responsive Images
```css
.revo-image {
    max-width: 200px;
    height: auto; /* Mantiene aspect ratio */
}
```

### Tips per Performance

1. **Minimizza CSS** in produzione
2. **Usa CDN** per FontAwesome
3. **Abilita compressione** server-side
4. **Optimizza immagini** caricate

---

## 📄 Print Styles

Il tema include stili ottimizzati per la stampa:

```css
@media print {
    .revo-fab,           /* Nasconde FAB */
    .xcrud-top-actions,  /* Nasconde azioni */
    .xcrud-nav,         /* Nasconde navigazione */
    .revo-btn,          /* Nasconde bottoni */
    .xcrud-actions {    /* Nasconde colonna azioni */
        display: none !important;
    }
    
    .xcrud-list th,
    .xcrud-list td {
        border: 1px solid #ddd !important; /* Bordi per stampa */
    }
}
```

---

## 🎯 Best Practices

### CSS
1. **Usa sempre** le CSS Custom Properties per i colori
2. **Preferisci** `transform` per le animazioni
3. **Evita** `!important` quando possibile
4. **Usa** classi semantiche (`revo-btn-success` invece di `revo-btn-green`)

### HTML
1. **Mantieni** la struttura definita in `xcrud.ini`
2. **Non modificare** le classi core di xCrud
3. **Aggiungi** classi custom con prefisso diverso da `revo-`

### JavaScript
1. **Usa** event delegation per gli eventi dinamici
2. **Rispetta** il pattern FAB per nuovi pulsanti
3. **Non interferire** con il MutationObserver esistente

---

## 🔮 Roadmap Future

### Pianificato per le prossime versioni:

#### v2.1
- [ ] **Dark mode** support con CSS Custom Properties
- [ ] **Animazioni** more advanced con CSS animations
- [ ] **Accessibility** improvements (ARIA, focus management)
- [ ] **Custom FAB items** via configuration

#### v2.2
- [ ] **Theme variants** (Compact, Spacious, Minimal)
- [ ] **CSS Grid** layouts per form complessi
- [ ] **Advanced tooltips** con Popper.js integration
- [ ] **Micro-interactions** per better UX

#### v2.3
- [ ] **Component library** standalone
- [ ] **SASS/LESS** source files
- [ ] **Build system** per customization
- [ ] **Theme designer** UI tool

---

## 📞 Support & Contributi

### Come Contribuire
1. Forka il repository
2. Crea un branch per la tua feature
3. Fai commit delle modifiche
4. Apri una Pull Request
5. Descrivi chiaramente le modifiche

### Segnalare Bug
- Usa la sezione Issues di GitHub
- Includi browser/versione
- Fornisci codice riproducibile
- Allega screenshot se necessario

### Community
- 💬 **Discord**: [Link al server Discord]
- 📧 **Email**: support@xcrudrevolution.com
- 🐦 **Twitter**: [@xcrudrevolution]
- 📚 **Docs**: [https://docs.xcrudrevolution.com]

---

## 📜 Licenza

Revolution Theme è rilasciato sotto licenza MIT. Vedi il file `LICENSE` per i dettagli completi.

---

## 🙏 Ringraziamenti

- **Bootstrap Team** per l'ispirazione del design system
- **FontAwesome** per le icone
- **Tailwind CSS** per le utility classes inspiration
- **Community xCrud** per feedback e testing

---

**Revolution Theme v1.0** - *Developed with ❤️ for xCrudRevolution*

*Ultimo aggiornamento: Settembre 2025*