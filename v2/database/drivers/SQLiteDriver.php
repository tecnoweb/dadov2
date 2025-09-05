<?php
/**
 * xCrudRevolution - SQLite Driver
 * Driver for SQLite database
 * 
 * @package xCrudRevolution
 * @version 2.0
 * @author xCrudRevolution Team
 */

namespace XcrudRevolution\Database\Drivers;

use XcrudRevolution\Database\DatabaseInterface;

class SQLiteDriver implements DatabaseInterface 
{
    private ?\SQLite3 $connection = null;
    private ?\SQLite3Result $result = null;
    private ?string $lastError = null;
    private int $affectedRows = 0;
    
    /**
     * Connect to SQLite database
     * 
     * @param array $config Configuration array with 'file' key
     * @throws \Exception
     */
    public function connect(array $config): void 
    {
        $file = $config['file'] ?? $config['dbname'] ?? ':memory:';
        $flags = $config['flags'] ?? SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE;
        $encryption = $config['encryption'] ?? '';
        
        try {
            $this->connection = new \SQLite3($file, $flags, $encryption);
            
            // Enable foreign keys
            $this->connection->exec('PRAGMA foreign_keys = ON');
            
            // Set busy timeout to 5 seconds
            $this->connection->busyTimeout(5000);
            
        } catch (\Exception $e) {
            $this->lastError = $e->getMessage();
            throw new \Exception("SQLite Connection Error: " . $this->lastError);
        }
    }
    
    /**
     * Execute SQL query
     * 
     * @param string $query SQL query to execute
     * @return int Number of affected rows
     * @throws \Exception
     */
    public function query(string $query): int 
    {
        if (!$this->connection) {
            throw new \Exception("SQLite: Not connected to database");
        }
        
        // Reset affected rows
        $this->affectedRows = 0;
        
        // Determine query type
        $queryType = strtoupper(substr(ltrim($query), 0, 6));
        
        if (in_array($queryType, ['SELECT', 'PRAGMA'])) {
            $this->result = @$this->connection->query($query);
        } else {
            $result = @$this->connection->exec($query);
            if ($result === false) {
                $this->result = null;  // Changed from false
            } else {
                $this->affectedRows = $this->connection->changes();
                $this->result = null;  // Changed from true
            }
        }
        
        if ($this->result === false) {
            $this->lastError = $this->connection->lastErrorMsg();
            throw new \Exception("SQLite Query Error: " . $this->lastError . "\nQuery: " . $query);
        }
        
        return $this->affectedRows;
    }
    
    /**
     * Get last insert ID
     * 
     * @return int|string
     */
    public function getLastInsertId() 
    {
        return $this->connection ? $this->connection->lastInsertRowID() : 0;
    }
    
    /**
     * Fetch all results as array
     * 
     * @return array
     */
    public function fetchAll(): array 
    {
        $out = [];
        if ($this->result instanceof \SQLite3Result) {
            while ($row = $this->result->fetchArray(SQLITE3_ASSOC)) {
                $out[] = $row;
            }
            $this->result->finalize();
            $this->result = null;
        }
        return $out;
    }
    
    /**
     * Fetch single row
     * 
     * @return array|null
     */
    public function fetchRow(): ?array 
    {
        if ($this->result instanceof \SQLite3Result) {
            $row = $this->result->fetchArray(SQLITE3_ASSOC);
            $this->result->finalize();
            $this->result = null;
            return $row ?: null;
        }
        return null;
    }
    
    /**
     * Escape value for SQLite
     * 
     * @param mixed $value Value to escape
     * @param bool $not_quoted Don't add quotes
     * @param string|false $type Data type
     * @param bool $null Allow NULL
     * @param bool $bit Is BIT field
     * @return string Escaped value
     */
    public function escape($value, bool $not_quoted = false, $type = false, bool $null = false, bool $bit = false): string 
    {
        if (!$this->connection) {
            throw new \Exception("SQLite: Not connected to database");
        }
        
        if ($type) {
            switch ($type) {
                case 'bool':
                    // SQLite stores boolean as 0/1
                    return $value ? '1' : '0';
                    
                case 'int':
                    if ($value === '' || $value === null) {
                        return $null ? 'NULL' : '0';
                    }
                    return (string)(int)$value;
                    
                case 'float':
                    if ($value === '' || $value === null) {
                        return $null ? 'NULL' : '0.0';
                    }
                    return (string)(float)$value;
                    
                case 'point':
                    // SQLite doesn't have native point type, store as text
                    if ($value === '' || $value === null) {
                        return $null ? 'NULL' : "'0,0'";
                    }
                    $value = preg_replace('[^0-9\.\,\-]', '', $value);
                    return "'" . \SQLite3::escapeString($value) . "'";
                    
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
            return \SQLite3::escapeString((string)$value);
        }
        
        return "'" . \SQLite3::escapeString((string)$value) . "'";
    }
    
    /**
     * Escape value for LIKE query
     * 
     * @param mixed $value Value to escape
     * @param array $pattern LIKE pattern ['%', '%']
     * @return string
     */
    public function escapeLike($value, array $pattern = ['%', '%']): string 
    {
        if (is_int($value)) {
            return "'" . $pattern[0] . (int)$value . $pattern[1] . "'";
        }
        
        if ($value == '') {
            return "''";
        }
        
        // Escape special characters for LIKE
        $value = \SQLite3::escapeString((string)$value);
        $value = str_replace(['%', '_', '['], ['\%', '\_', '\['], $value);
        
        return "'" . $pattern[0] . $value . $pattern[1] . "' ESCAPE '\'";
    }
    
    /**
     * Get table information
     * 
     * @param string $table Table name
     * @return array
     */
    public function getTableInfo(string $table): array 
    {
        $this->query("PRAGMA table_info('$table')");
        $columns = $this->fetchAll();
        
        // Convert SQLite format to MySQL-like format
        $result = [];
        foreach ($columns as $col) {
            $result[] = [
                'Field' => $col['name'],
                'Type' => $col['type'],
                'Null' => $col['notnull'] ? 'NO' : 'YES',
                'Key' => $col['pk'] ? 'PRI' : '',
                'Default' => $col['dflt_value'],
                'Extra' => ''
            ];
        }
        
        return $result;
    }
    
    /**
     * Get list of tables
     * 
     * @return array
     */
    public function getTables(): array 
    {
        $query = "SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%' ORDER BY name";
        $this->query($query);
        
        $tables = [];
        $result = $this->fetchAll();
        foreach ($result as $row) {
            $tables[] = $row['name'];
        }
        return $tables;
    }
    
    /**
     * Begin transaction
     * 
     * @return bool
     */
    public function beginTransaction(): bool 
    {
        return $this->connection ? $this->connection->exec('BEGIN TRANSACTION') : false;
    }
    
    /**
     * Commit transaction
     * 
     * @return bool
     */
    public function commit(): bool 
    {
        return $this->connection ? $this->connection->exec('COMMIT') : false;
    }
    
    /**
     * Rollback transaction
     * 
     * @return bool
     */
    public function rollback(): bool 
    {
        return $this->connection ? $this->connection->exec('ROLLBACK') : false;
    }
    
    /**
     * Check if connected
     * 
     * @return bool
     */
    public function isConnected(): bool 
    {
        return $this->connection !== null;
    }
    
    /**
     * Close connection
     */
    public function close(): void 
    {
        if ($this->connection) {
            $this->connection->close();
            $this->connection = null;
        }
    }
    
    /**
     * Get last error message
     * 
     * @return string|null
     */
    public function getLastError(): ?string 
    {
        if ($this->connection) {
            return $this->connection->lastErrorMsg();
        }
        return $this->lastError;
    }
    
    /**
     * Get driver type
     * 
     * @return string
     */
    public function getDriverType(): string 
    {
        return 'sqlite';
    }
    
    /**
     * Get database version
     * 
     * @return string
     */
    public function getVersion(): string 
    {
        if ($this->connection) {
            $version = \SQLite3::version();
            return $version['versionString'] ?? 'Unknown';
        }
        return 'Unknown';
    }
    
    /**
     * Set timezone (not supported in SQLite)
     * 
     * @param string $timezone
     * @return bool Always returns true for compatibility
     */
    public function setTimezone(string $timezone): bool 
    {
        // SQLite doesn't support timezones
        // This method exists for interface compatibility
        return true;
    }
}