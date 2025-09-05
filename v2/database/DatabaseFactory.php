<?php
/**
 * xCrudRevolution - Database Factory
 * Factory pattern for creating database driver instances
 * 
 * @package xCrudRevolution
 * @version 2.0
 * @author xCrudRevolution Team
 */

namespace XcrudRevolution\Database;

use XcrudRevolution\Database\Drivers\MySQLDriver;
use XcrudRevolution\Database\Drivers\PostgreSQLDriver;
use XcrudRevolution\Database\Drivers\SQLiteDriver;

class DatabaseFactory 
{
    /**
     * Supported database drivers
     */
    const SUPPORTED_DRIVERS = [
        'mysql',
        'mysqli', 
        'mariadb',
        'pgsql',
        'postgresql',
        'postgres',
        'sqlite',
        'sqlite3'
    ];
    
    /**
     * Driver mapping for aliases
     */
    const DRIVER_MAP = [
        'mysql' => 'mysql',
        'mysqli' => 'mysql',
        'mariadb' => 'mysql',
        'pgsql' => 'postgresql',
        'postgresql' => 'postgresql',
        'postgres' => 'postgresql',
        'sqlite' => 'sqlite',
        'sqlite3' => 'sqlite'
    ];
    
    /**
     * Create database driver instance
     * 
     * @param string $type Database type (mysql, postgresql, sqlite, etc)
     * @param array $config Database configuration
     * @return DatabaseInterface
     * @throws \Exception If driver not supported
     */
    public static function create(string $type, array $config = []): DatabaseInterface 
    {
        $type = strtolower($type);
        
        if (!in_array($type, self::SUPPORTED_DRIVERS)) {
            throw new \Exception("Database driver '$type' is not supported. Supported drivers: " . 
                implode(', ', self::SUPPORTED_DRIVERS));
        }
        
        // Map to actual driver type
        $driverType = self::DRIVER_MAP[$type] ?? $type;
        
        // Create driver instance
        switch ($driverType) {
            case 'mysql':
                $driver = new MySQLDriver();
                break;
                
            case 'postgresql':
                // Check if PostgreSQL extension is loaded
                if (!extension_loaded('pgsql')) {
                    throw new \Exception("PostgreSQL extension (pgsql) is not installed");
                }
                $driver = new PostgreSQLDriver();
                break;
                
            case 'sqlite':
                // Check if SQLite3 extension is loaded
                if (!class_exists('SQLite3')) {
                    throw new \Exception("SQLite3 extension is not installed");
                }
                $driver = new SQLiteDriver();
                break;
                
            default:
                throw new \Exception("Driver implementation for '$driverType' not found");
        }
        
        // Connect to database if config provided
        if (!empty($config)) {
            $driver->connect($config);
        }
        
        return $driver;
    }
    
    /**
     * Create driver from connection string
     * 
     * @param string $dsn Data Source Name (e.g., mysql://user:pass@localhost/dbname)
     * @return DatabaseInterface
     * @throws \Exception
     */
    public static function createFromDSN(string $dsn): DatabaseInterface 
    {
        $parts = parse_url($dsn);
        
        if (!isset($parts['scheme'])) {
            throw new \Exception("Invalid DSN format. Expected: driver://user:pass@host/database");
        }
        
        $config = [
            'host' => $parts['host'] ?? 'localhost',
            'user' => $parts['user'] ?? '',
            'pass' => $parts['pass'] ?? '',
            'dbname' => isset($parts['path']) ? ltrim($parts['path'], '/') : '',
            'port' => $parts['port'] ?? null
        ];
        
        // Parse query string for additional options
        if (isset($parts['query'])) {
            parse_str($parts['query'], $options);
            $config = array_merge($config, $options);
        }
        
        return self::create($parts['scheme'], $config);
    }
    
    /**
     * Create driver from Xcrud config (backward compatibility)
     * 
     * @param array|null $params Custom connection parameters or null for default
     * @return DatabaseInterface
     * @throws \Exception
     */
    public static function createFromXcrudConfig($params = null): DatabaseInterface 
    {
        if (is_array($params)) {
            // Custom connection parameters
            list($user, $pass, $dbname, $host, $encoding) = $params;
            $config = [
                'host' => $host,
                'user' => $user,
                'pass' => $pass,
                'dbname' => $dbname,
                'charset' => $encoding
            ];
        } else {
            // Use default Xcrud config
            $config = [
                'host' => \Xcrud_config::$dbhost ?? 'localhost',
                'user' => \Xcrud_config::$dbuser ?? 'root',
                'pass' => \Xcrud_config::$dbpass ?? '',
                'dbname' => \Xcrud_config::$dbname ?? '',
                'charset' => \Xcrud_config::$dbencoding ?? 'utf8'
            ];
            
            if (\Xcrud_config::$db_time_zone ?? false) {
                $config['timezone'] = \Xcrud_config::$db_time_zone;
            }
        }
        
        // Detect driver type from config or default to MySQL
        $driver = $config['driver'] ?? 'mysql';
        
        return self::create($driver, $config);
    }
    
    /**
     * Check if driver is supported
     * 
     * @param string $type Driver type
     * @return bool
     */
    public static function isSupported(string $type): bool 
    {
        return in_array(strtolower($type), self::SUPPORTED_DRIVERS);
    }
    
    /**
     * Get list of available drivers (checks installed extensions)
     * 
     * @return array List of available driver types
     */
    public static function getAvailableDrivers(): array 
    {
        $available = [];
        
        // Check MySQL/MariaDB
        if (extension_loaded('mysqli')) {
            $available[] = 'mysql';
            $available[] = 'mariadb';
        }
        
        // Check PostgreSQL
        if (extension_loaded('pgsql')) {
            $available[] = 'postgresql';
        }
        
        // Check SQLite
        if (class_exists('SQLite3')) {
            $available[] = 'sqlite';
        }
        
        return $available;
    }
    
    /**
     * Get driver requirements
     * 
     * @param string $type Driver type
     * @return array Requirements info
     */
    public static function getRequirements(string $type): array 
    {
        $type = strtolower($type);
        $driverType = self::DRIVER_MAP[$type] ?? $type;
        
        switch ($driverType) {
            case 'mysql':
                return [
                    'extension' => 'mysqli',
                    'class' => 'mysqli',
                    'functions' => ['mysqli_connect', 'mysqli_query']
                ];
                
            case 'postgresql':
                return [
                    'extension' => 'pgsql',
                    'functions' => ['pg_connect', 'pg_query']
                ];
                
            case 'sqlite':
                return [
                    'extension' => 'sqlite3',
                    'class' => 'SQLite3'
                ];
                
            default:
                return [];
        }
    }
}