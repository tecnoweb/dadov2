//---------------------------------- Inglese

This is short description for RocketxCRUD archive.

1. HOW TO INSTALL DEMO
 - Extract this archive on your server.
 - Create new database and IMPORT demo tables (demo_database folder):
	- database_demo.sql- basic database.
	- million.sql.zip - table for 'One million rows' demo.
 - Configure database connection for xCRUD ( /xcrud/xcrud_config.php ).
 - Try it:
	- http://your_server_url/demos - main demos
	- http://your_server_url/example.php - the simpliest example

2. WHAT FOLDERS IS IN ARCHIVE
 - demo_database - sql files for demo database
 - demos - all examples is in this folder
 - documentation - latest docs
 - editors - free editors, used for demos
 - xcrud - MAIN APPLICATION

Config DB: 
/xcrud/xcrud_config.php 
User DB:
/xcrud/application/configurations.php
    private $host = "localhost";
    private $db_name = "database_demo";
    private $username = "root";
    private $password = "";

Enable Registration:
'allow_signup' => true,
Default User:
user: demo
password: password

//------------------------------ ITALIANO
Questa è una breve descrizione dell'archivio RocketxCRUD.


1. COME INSTALLARE DEMO
- Estrai questo archivio sul tuo server.
- Crea nuovo database e importa le tabelle demo (cartella demo_database):
- database_demo.sql - database di base.
- million.sql.zip - tabella per la demo "Un milione di righe".
  - Configura la connessione al database per xCRUD (/xcrud/xcrud_config.php).
  - Provalo:
- http: // your_server_url / demos - demo principali
- http: //your_server_url/example.php - l'esempio più semplice

Config DB: 
/xcrud/xcrud_config.php 

User DB:
/xcrud/application/configurations.php
    private $host = "localhost";
    private $db_name = "database_demo";
    private $username = "root";
    private $password = "";

Enable Registration:
'allow_signup' => true,
Default User:
user: demo
password: password

