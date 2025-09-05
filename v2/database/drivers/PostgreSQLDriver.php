<?php
/**
 * xCrudRevolution - PostgreSQL Driver
 * Driver per database PostgreSQL
 * 
 * @package xCrudRevolution
 * @version 2.0
 */

namespace XcrudRevolution\Database\Drivers;

use XcrudRevolution\Database\DatabaseInterface;

class PostgreSQLDriver implements DatabaseInterface 
{
    private $connection = null;
    private $result = null;
    private ?string $lastError = null;
    
    /**
     * Connette al database PostgreSQL
     */
    public function connect(array $config): void 
    {
        $host = $config['host'] ?? 'localhost';
        $user = $config['user'] ?? 'postgres';
        $pass = $config['pass'] ?? '';
        $dbname = $config['dbname'] ?? 'postgres';
        $port = $config['port'] ?? 5432;
        $charset = $config['charset'] ?? 'UTF8';
        
        // Costruisce stringa di connessione PostgreSQL
        $dsn = "host=$host port=$port dbname=$dbname user=$user";
        if ($pass) {
            $dsn .= " password=$pass";
        }
        
        // Options extra
        if (isset($config['options'])) {
            $dsn .= " options='" . $config['options'] . "'";
        }
        
        $this->connection = @pg_connect($dsn);
        
        if (!$this->connection) {
            $this->lastError = "PostgreSQL connection failed";
            throw new \Exception("PostgreSQL Connection Error");
        }
        
        // Set charset
        if (!pg_set_client_encoding($this->connection, $charset)) {
            $this->lastError = pg_last_error($this->connection);
            throw new \Exception("PostgreSQL Charset Error: " . $this->lastError);
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
            throw new \Exception("PostgreSQL: Not connected to database");
        }
        
        $this->result = @pg_query($this->connection, $query);
        
        if (!$this->result) {
            $this->lastError = pg_last_error($this->connection);
            throw new \Exception("PostgreSQL Query Error: " . $this->lastError . "\nQuery: " . $query);
        }
        
        return pg_affected_rows($this->result);
    }
    
    /**
     * Ottiene ultimo ID inserito
     * PostgreSQL richiede RETURNING id nella query INSERT
     */
    public function getLastInsertId() 
    {
        if ($this->result) {
            $row = pg_fetch_row($this->result);
            if ($row && isset($row[0])) {
                return $row[0];
            }
        }
        
        // Fallback: prova con lastval()
        $result = pg_query($this->connection, "SELECT lastval()");
        if ($result) {
            $row = pg_fetch_row($result);
            return $row ? $row[0] : 0;
        }
        
        return 0;
    }
    
    /**
     * Ottiene tutti i risultati
     */
    public function fetchAll(): array 
    {
        $out = [];
        if ($this->result) {
            while ($row = pg_fetch_assoc($this->result)) {
                $out[] = $row;
            }
            pg_free_result($this->result);
            $this->result = null;
        }
        return $out;
    }
    
    /**
     * Ottiene una singola riga
     */
    public function fetchRow(): ?array 
    {
        if ($this->result) {
            $row = pg_fetch_assoc($this->result);
            pg_free_result($this->result);
            $this->result = null;
            return $row;
        }
        return null;
    }
    
    /**
     * Escape valore per PostgreSQL
     */
    public function escape($value, bool $not_quoted = false, $type = false, bool $null = false, bool $bit = false): string 
    {
        if (!$this->connection) {
            throw new \Exception("PostgreSQL: Not connected to database");
        }
        
        if ($type) {
            switch ($type) {
                case 'bool':
                    if ($bit) {
                        return (int)$value ? "B'1'" : "B'0'";
                    } else {
                        return $value ? 'true' : 'false';
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
                    
                case 'point':
                    if ($value === '' || $value === null) {
                        return $null ? 'NULL' : "'(0,0)'";
                    }
                    // PostgreSQL point format: (x,y)
                    $value = preg_replace('[^0-9\.\,\-]', '', $value);
                    return "'(" . $value . ")'";
                    
                default:
                    if ($value === '' || $value === null) {
                        if ($null) {
                            return 'NULL';
                        } else {
                            return "''";
                        }
                    }
                    break;
            }
        }
        
        if ($not_quoted) {
            return pg_escape_string($this->connection, (string)$value);
        }
        
        return "'" . pg_escape_string($this->connection, (string)$value) . "'";
    }
    
    /**
     * Escape per LIKE
     */
    public function escapeLike($value, array $pattern = ['%', '%']): string 
    {
        if (!$this->connection) {
            throw new \Exception("PostgreSQL: Not connected to database");
        }
        
        if (is_int($value)) {
            return "'" . $pattern[0] . (int)$value . $pattern[1] . "'";
        }
        
        if ($value == '') {
            return "''";
        }
        
        // PostgreSQL needs special escape for LIKE
        $value = pg_escape_string($this->connection, (string)$value);
        $value = str_replace(['%', '_'], ['\%', '\_'], $value);
        
        return "'" . $pattern[0] . $value . $pattern[1] . "'";
    }
    
    /**
     * Ottiene info tabella
     */
    public function getTableInfo(string $table): array 
    {
        $query = "
            SELECT 
                column_name AS Field,
                data_type AS Type,
                is_nullable AS Null,
                column_default AS Default,
                character_maximum_length AS Length
            FROM information_schema.columns 
            WHERE table_name = '$table'
            ORDER BY ordinal_position
        ";
        
        $this->query($query);
        return $this->fetchAll();
    }
    
    /**
     * Ottiene lista tabelle
     */
    public function getTables(): array 
    {
        $query = "
            SELECT tablename 
            FROM pg_tables 
            WHERE schemaname = 'public'
            ORDER BY tablename
        ";
        
        $this->query($query);
        $tables = [];
        $result = $this->fetchAll();
        foreach ($result as $row) {
            $tables[] = $row['tablename'];
        }
        return $tables;
    }
    
    /**
     * Transazioni
     */
    public function beginTransaction(): bool 
    {
        return $this->connection ? pg_query($this->connection, "BEGIN") !== false : false;
    }
    
    public function commit(): bool 
    {
        return $this->connection ? pg_query($this->connection, "COMMIT") !== false : false;
    }
    
    public function rollback(): bool 
    {
        return $this->connection ? pg_query($this->connection, "ROLLBACK") !== false : false;
    }
    
    /**
     * Stato connessione
     */
    public function isConnected(): bool 
    {
        return $this->connection && pg_connection_status($this->connection) === PGSQL_CONNECTION_OK;
    }
    
    /**
     * Chiude connessione
     */
    public function close(): void 
    {
        if ($this->connection) {
            pg_close($this->connection);
            $this->connection = null;
        }
    }
    
    /**
     * Ultimo errore
     */
    public function getLastError(): ?string 
    {
        if ($this->connection) {
            return pg_last_error($this->connection);
        }
        return $this->lastError;
    }
    
    /**
     * Tipo driver
     */
    public function getDriverType(): string 
    {
        return 'postgresql';
    }
    
    /**
     * Versione database
     */
    public function getVersion(): string 
    {
        if ($this->connection) {
            $result = pg_query($this->connection, "SELECT version()");
            if ($result) {
                $row = pg_fetch_row($result);
                return $row ? $row[0] : 'Unknown';
            }
        }
        return 'Unknown';
    }
    
    /**
     * Imposta timezone
     */
    public function setTimezone(string $timezone): bool 
    {
        if ($this->connection) {
            $result = pg_query($this->connection, "SET TIME ZONE '$timezone'");
            return $result !== false;
        }
        return false;
    }
}