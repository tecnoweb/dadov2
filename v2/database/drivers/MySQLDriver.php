<?php
/**
 * xCrudRevolution - MySQL Driver
 * Driver per database MySQL/MariaDB
 * 
 * @package xCrudRevolution
 * @version 2.0
 */

namespace XcrudRevolution\Database\Drivers;

use XcrudRevolution\Database\DatabaseInterface;

class MySQLDriver implements DatabaseInterface 
{
    private ?\mysqli $connection = null;
    private $result = null;
    private ?string $lastError = null;
    
    /**
     * Connette al database MySQL
     */
    public function connect(array $config): void 
    {
        $host = $config['host'] ?? 'localhost';
        $user = $config['user'] ?? 'root';
        $pass = $config['pass'] ?? '';
        $dbname = $config['dbname'] ?? '';
        $charset = $config['charset'] ?? 'utf8mb4';
        $port = $config['port'] ?? 3306;
        $socket = $config['socket'] ?? null;
        
        // Gestione host:port legacy
        if (strpos($host, ':') !== false) {
            list($host, $port) = explode(':', $host, 2);
            preg_match('/^([0-9]*)([^0-9]*.*)$/', $port, $socks);
            if (!empty($socks[1])) $port = (int)$socks[1];
            if (!empty($socks[2])) $socket = $socks[2];
        }
        
        $this->connection = @mysqli_connect($host, $user, $pass, $dbname, $port, $socket);
        
        if (!$this->connection) {
            $this->lastError = mysqli_connect_error();
            throw new \Exception("MySQL Connection Error: " . $this->lastError);
        }
        
        if (!$this->connection->set_charset($charset)) {
            $this->lastError = $this->connection->error;
            throw new \Exception("MySQL Charset Error: " . $this->lastError);
        }
        
        // Set timezone if specified
        if (isset($config['timezone']) && $config['timezone']) {
            $this->setTimezone($config['timezone']);
        }
    }
    
    /**
     * Esegue query SQL
     */
    public function query(string $query): int 
    {
        if (!$this->connection) {
            throw new \Exception("MySQL: Not connected to database");
        }
        
        $this->result = $this->connection->query($query, MYSQLI_USE_RESULT);
        
        if ($this->connection->error) {
            $this->lastError = $this->connection->error;
            throw new \Exception("MySQL Query Error: " . $this->lastError . "\nQuery: " . $query);
        }
        
        return $this->connection->affected_rows;
    }
    
    /**
     * Ottiene ultimo ID inserito
     */
    public function getLastInsertId() 
    {
        return $this->connection ? $this->connection->insert_id : 0;
    }
    
    /**
     * Ottiene tutti i risultati
     */
    public function fetchAll(): array 
    {
        $out = [];
        if ($this->result && $this->result instanceof \mysqli_result) {
            while ($row = $this->result->fetch_assoc()) {
                $out[] = $row;
            }
            $this->result->free();
            $this->result = null;
        }
        return $out;
    }
    
    /**
     * Ottiene una singola riga
     */
    public function fetchRow(): ?array 
    {
        if ($this->result && $this->result instanceof \mysqli_result) {
            $row = $this->result->fetch_assoc();
            $this->result->free();
            $this->result = null;
            return $row;
        }
        return null;
    }
    
    /**
     * Escape valore - IDENTICO all'originale per retrocompatibilità
     */
    public function escape($value, bool $not_quoted = false, $type = false, bool $null = false, bool $bit = false): string 
    {
        if (!$this->connection) {
            throw new \Exception("MySQL: Not connected to database");
        }
        
        if ($type) {
            switch ($type) {
                case 'bool':
                    if ($bit) {
                        return (int)$value ? "b'1'" : "b'0'";
                    } else {
                        return (int)$value ? '1' : '0';
                    }
                    
                case 'int':
                    if ($value === '' || $value === null) {
                        return $null ? 'NULL' : '0';
                    }
                    return (string)(int)$value;
                    
                case 'float':
                    if ($value === '' || $value === null) {
                        return $null ? 'NULL' : '0';
                    }
                    return (string)(float)$value;
                    
                default:
                    if ($value === '' || $value === null) {
                        if ($null) {
                            return 'NULL';
                        } else {
                            return "''";
                        }
                    } else {
                        if ($type == 'point') {
                            $value = preg_replace('[^0-9\.\,\-]', '', $value);
                        }
                    }
                    break;
            }
        }
        
        if ($not_quoted) {
            return $this->connection->real_escape_string((string)$value);
        }
        
        return "'" . $this->connection->real_escape_string((string)$value) . "'";
    }
    
    /**
     * Escape per LIKE
     */
    public function escapeLike($value, array $pattern = ['%', '%']): string 
    {
        if (!$this->connection) {
            throw new \Exception("MySQL: Not connected to database");
        }
        
        if (is_int($value)) {
            return "'" . $pattern[0] . (int)$value . $pattern[1] . "'";
        }
        
        if ($value == '') {
            return "''";
        }
        
        return "'" . $pattern[0] . $this->connection->real_escape_string((string)$value) . $pattern[1] . "'";
    }
    
    /**
     * Ottiene info tabella
     */
    public function getTableInfo(string $table): array 
    {
        $this->query("SHOW FULL COLUMNS FROM `$table`");
        return $this->fetchAll();
    }
    
    /**
     * Ottiene lista tabelle
     */
    public function getTables(): array 
    {
        $this->query("SHOW TABLES");
        $tables = [];
        $result = $this->fetchAll();
        foreach ($result as $row) {
            $tables[] = array_values($row)[0];
        }
        return $tables;
    }
    
    /**
     * Transazioni
     */
    public function beginTransaction(): bool 
    {
        return $this->connection ? $this->connection->begin_transaction() : false;
    }
    
    public function commit(): bool 
    {
        return $this->connection ? $this->connection->commit() : false;
    }
    
    public function rollback(): bool 
    {
        return $this->connection ? $this->connection->rollback() : false;
    }
    
    /**
     * Stato connessione
     */
    public function isConnected(): bool 
    {
        return $this->connection && $this->connection->ping();
    }
    
    /**
     * Chiude connessione
     */
    public function close(): void 
    {
        if ($this->connection) {
            $this->connection->close();
            $this->connection = null;
        }
    }
    
    /**
     * Ultimo errore
     */
    public function getLastError(): ?string 
    {
        if ($this->connection && $this->connection->error) {
            return $this->connection->error;
        }
        return $this->lastError;
    }
    
    /**
     * Tipo driver
     */
    public function getDriverType(): string 
    {
        return 'mysql';
    }
    
    /**
     * Versione database
     */
    public function getVersion(): string 
    {
        if ($this->connection) {
            return $this->connection->server_info;
        }
        return 'Unknown';
    }
    
    /**
     * Imposta timezone
     */
    public function setTimezone(string $timezone): bool 
    {
        if ($this->connection) {
            $this->connection->query("SET time_zone = '$timezone'");
            return !$this->connection->error;
        }
        return false;
    }
}