# 🔐 CREAZIONE REPOSITORY GITHUB PRIVATA

## PASSAGGI PER CREARE REPOSITORY "dadov2" PRIVATA

### 1. CREA REPOSITORY SU GITHUB

1. Vai su https://github.com/new
2. **Repository name**: `dadov2`
3. **Description**: "xCrudRevolution v2 - Private Development"
4. **Visibility**: 🔒 **Private** (IMPORTANTE!)
5. **NON** inizializzare con README
6. **NON** aggiungere .gitignore
7. **NON** aggiungere license
8. Click **Create repository**

### 2. COLLEGA REPOSITORY LOCALE

Esegui questi comandi nella directory v2:

```bash
# Configura git (se non l'hai già fatto)
git config --global user.name "Tuo Nome"
git config --global user.email "tua@email.com"

# Aggiungi remote GitHub (sostituisci USERNAME con il tuo)
git remote add origin https://github.com/USERNAME/dadov2.git

# Oppure con SSH (se hai configurato SSH keys)
git remote add origin git@github.com:USERNAME/dadov2.git

# Verifica remote
git remote -v

# Push del primo commit
git push -u origin main

# Se il branch si chiama master invece di main
git branch -M main
git push -u origin main
```

### 3. SETUP PER COLLABORAZIONE (Opzionale)

Se vuoi aggiungere collaboratori:
1. Vai su Settings → Manage access
2. Click "Add people"
3. Invita collaboratori via username/email

### 4. GITHUB ACTIONS (Opzionale)

Crea `.github/workflows/php.yml` per CI/CD:

```yaml
name: PHP 8 Tests

on:
  push:
    branches: [ main ]
  pull_request:
    branches: [ main ]

jobs:
  test:
    runs-on: ubuntu-latest
    
    strategy:
      matrix:
        php-versions: ['8.0', '8.1', '8.2', '8.3']
    
    steps:
    - uses: actions/checkout@v3
    
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: ${{ matrix.php-versions }}
        extensions: mbstring, mysqli, pdo, pdo_mysql
    
    - name: Run tests
      run: |
        php -v
        # Add your test commands here
```

### 5. WORKFLOW CONSIGLIATO

```bash
# Per ogni nuova feature
git checkout -b feature/nome-feature
git add .
git commit -m "Add: descrizione feature"
git push origin feature/nome-feature
# Poi crea Pull Request su GitHub

# Per bug fixes
git checkout -b fix/nome-fix
git add .
git commit -m "Fix: descrizione fix"
git push origin fix/nome-fix

# Per aggiornamenti da main
git checkout main
git pull origin main
```

### 6. .GITIGNORE AGGIUNTIVO (se necessario)

Aggiungi al `.gitignore` se mancano:

```
# Credentials locali
config.local.php
.env.local

# Database locali
*.sqlite
local.db

# IDE
.idea/
.vscode/

# OS
.DS_Store
Thumbs.db
```

### 7. BRANCH PROTECTION (Consigliato)

1. Vai su Settings → Branches
2. Add rule per `main`
3. Abilita:
   - Require pull request reviews
   - Dismiss stale pull request approvals
   - Require status checks to pass

### 8. SECRETS PER DEPLOY (Opzionale)

Settings → Secrets → Actions:
- `DB_HOST`
- `DB_USER`
- `DB_PASS`
- `FTP_SERVER`
- `FTP_USERNAME`
- `FTP_PASSWORD`

### 9. BACKUP AUTOMATICO

```bash
# Script per backup automatico
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
git add .
git commit -m "Backup: $DATE"
git push origin main
```

### 10. COMANDI UTILI

```bash
# Stato repository
git status

# Log commits
git log --oneline --graph

# Vedere modifiche
git diff

# Annullare modifiche locali
git checkout -- file.php

# Creare tag per release
git tag -a v2.0.0 -m "Release version 2.0.0"
git push origin v2.0.0

# Clonare repository su altro computer
git clone https://github.com/USERNAME/dadov2.git
```

## ⚠️ IMPORTANTE

1. **MAI** committare credenziali o password
2. **SEMPRE** usare branch per nuove features
3. **TEST** prima di merge su main
4. **BACKUP** regolari con push
5. La repository è **PRIVATA** - non condividere link pubblicamente

## 🚀 PRONTO!

Ora hai:
- ✅ Repository locale git
- ✅ Tutti i file committati
- ✅ Pronto per push su GitHub
- ✅ Sistema di backup e versioning

Esegui i comandi sopra per collegare a GitHub!