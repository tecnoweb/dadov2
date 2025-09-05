<?php
/**
 * xCrudRevolution - Database Interface
 * Interfaccia per supporto multi-database
 * 
 * @package xCrudRevolution
 * @version 2.0
 * @author xCrudRevolution Team
 */

namespace XcrudRevolution\Database;

interface DatabaseInterface 
{
    /**
     * Connette al database
     * @param array $config Configurazione: [host, user, pass, dbname, charset, port, socket]
     * @return void
     * @throws \Exception
     */
    public function connect(array $config): void;
    
    /**
     * Esegue una query SQL
     * @param string $query Query SQL da eseguire
     * @return int Numero di righe affette
     */
    public function query(string $query): int;
    
    /**
     * Ottiene l'ultimo ID inserito
     * @return int|string
     */
    public function getLastInsertId();
    
    /**
     * Ottiene tutti i risultati come array
     * @return array
     */
    public function fetchAll(): array;
    
    /**
     * Ottiene una singola riga
     * @return array|null
     */
    public function fetchRow(): ?array;
    
    /**
     * Escape di un valore per prevenire SQL injection
     * @param mixed $value Valore da escapare
     * @param bool $not_quoted Se true, non aggiunge quotes
     * @param string|false $type Tipo di dato (int, float, bool, point, etc)
     * @param bool $null Se il campo può essere NULL
     * @param bool $bit Se è un campo BIT
     * @return string Valore escapato
     */
    public function escape($value, bool $not_quoted = false, $type = false, bool $null = false, bool $bit = false): string;
    
    /**
     * Escape per LIKE queries
     * @param mixed $value Valore da escapare
     * @param array $pattern Pattern per LIKE ['%', '%']
     * @return string
     */
    public function escapeLike($value, array $pattern = ['%', '%']): string;
    
    /**
     * Ottiene informazioni su una tabella
     * @param string $table Nome tabella
     * @return array
     */
    public function getTableInfo(string $table): array;
    
    /**
     * Ottiene la lista delle tabelle
     * @return array
     */
    public function getTables(): array;
    
    /**
     * Inizia una transazione
     * @return bool
     */
    public function beginTransaction(): bool;
    
    /**
     * Commit della transazione
     * @return bool
     */
    public function commit(): bool;
    
    /**
     * Rollback della transazione
     * @return bool
     */
    public function rollback(): bool;
    
    /**
     * Verifica se connesso
     * @return bool
     */
    public function isConnected(): bool;
    
    /**
     * Chiude la connessione
     * @return void
     */
    public function close(): void;
    
    /**
     * Ottiene l'ultimo errore
     * @return string|null
     */
    public function getLastError(): ?string;
    
    /**
     * Ottiene il tipo di database (mysql, pgsql, sqlite, etc)
     * @return string
     */
    public function getDriverType(): string;
    
    /**
     * Ottiene la versione del database
     * @return string
     */
    public function getVersion(): string;
    
    /**
     * Imposta il timezone del database
     * @param string $timezone
     * @return bool
     */
    public function setTimezone(string $timezone): bool;
}