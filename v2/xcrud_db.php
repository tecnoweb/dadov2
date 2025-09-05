<?php
/**
 * xCrudRevolution Database Driver
 * Enhanced with multi-database support and modern error handling
 * Maintains 100% backward compatibility
 * 
 * @package    xCrudRevolution
 * @version    2.0.0
 * @copyright  Copyright (c) 2024 xCrudRevolution. All rights reserved.
 * @license    Proprietary License - Unauthorized copying or distribution is prohibited.
 * @website    https://www.xcrudrevolution.com
 */

// Include database abstraction layer
require_once __DIR__ . '/database/DatabaseInterface.php';
require_once __DIR__ . '/database/DatabaseFactory.php';
require_once __DIR__ . '/database/QueryBuilder.php';
require_once __DIR__ . '/database/drivers/MySQLDriver.php';
require_once __DIR__ . '/database/drivers/PostgreSQLDriver.php';
require_once __DIR__ . '/database/drivers/SQLiteDriver.php';

use XcrudRevolution\Database\DatabaseInterface;
use XcrudRevolution\Database\DatabaseFactory;
use XcrudRevolution\Database\QueryBuilder;

class Xcrud_db
{
    /**
     * Singleton instances array
     * @var array
     */
    private static $_instance = array();
    
    /**
     * Database driver instance (new multi-DB support)
     * @var DatabaseInterface|null
     */
    private $driver = null;
    
    /**
     * Legacy MySQL connection (for backward compatibility)
     * @var mysqli|null
     */
    private $connect = null;
    
    /**
     * Legacy result property (for backward compatibility)
     * @var mixed
     */
    public $result;
    
    /**
     * Connection parameters
     */
    private $dbhost;
    private $dbuser;
    private $dbpass;
    private $dbname;
    private $dbencoding;
    private $dbtype = 'mysql'; // New: database type support

    /**
     * Get singleton instance - Enhanced with multi-database support
     * Maintains 100% backward compatibility with original parameters
     * 
     * @param array|false $params Connection parameters or false for default
     * @return Xcrud_db
     */
    public static function get_instance($params = false)
    {
        if (is_array($params)) {
            // Check if new format with driver type
            if (isset($params['driver'])) {
                // New format: associative array with driver
                $instance_name = sha1(serialize($params));
            } else {
                // Legacy format: indexed array [user, pass, dbname, host, encoding]
                list($dbuser, $dbpass, $dbname, $dbhost, $dbencoding) = $params;
                $instance_name = sha1($dbuser . $dbpass . $dbname . $dbhost . $dbencoding);
            }
        } else {
            $instance_name = 'db_instance_default';
        }
        
        if (!isset(self::$_instance[$instance_name]) || null === self::$_instance[$instance_name]) {
            if (!is_array($params)) {
                // Use default config
                $dbuser = Xcrud_config::$dbuser;
                $dbpass = Xcrud_config::$dbpass;
                $dbname = Xcrud_config::$dbname;
                $dbhost = Xcrud_config::$dbhost;
                $dbencoding = Xcrud_config::$dbencoding;
                $dbtype = Xcrud_config::$dbtype ?? 'mysql'; // New config option
            } elseif (isset($params['driver'])) {
                // New format with driver
                $dbtype = $params['driver'];
                $dbuser = $params['user'] ?? 'root';
                $dbpass = $params['pass'] ?? '';
                $dbname = $params['dbname'] ?? '';
                $dbhost = $params['host'] ?? 'localhost';
                $dbencoding = $params['charset'] ?? 'utf8';
            } else {
                // Legacy format - default to MySQL for compatibility
                $dbtype = 'mysql';
            }
            
            self::$_instance[$instance_name] = new self($dbuser, $dbpass, $dbname, $dbhost, $dbencoding, $dbtype);
        }
        
        return self::$_instance[$instance_name];
    }
    
    /**
     * Enhanced constructor with multi-database support
     */
    private function __construct($dbuser, $dbpass, $dbname, $dbhost, $dbencoding, $dbtype = 'mysql')
    {
        $this->dbuser = $dbuser;
        $this->dbpass = $dbpass;
        $this->dbname = $dbname;
        $this->dbhost = $dbhost;
        $this->dbencoding = $dbencoding;
        $this->dbtype = $dbtype;
        
        // Initialize Logger if available
        if (class_exists('\XcrudRevolution\Logger')) {
            \XcrudRevolution\Logger::debug('Database connection attempt', \XcrudRevolution\Logger::CATEGORY_DATABASE, [
                'type' => $dbtype,
                'host' => $dbhost,
                'database' => $dbname,
                'user' => $dbuser
            ]);
        }
        
        try {
            if ($dbtype === 'mysql' || $dbtype === 'mysqli') {
                // Legacy MySQL connection for backward compatibility
                $this->initLegacyMySQL($dbuser, $dbpass, $dbname, $dbhost, $dbencoding);
            } else {
                // New multi-database support
                $this->initMultiDatabase($dbuser, $dbpass, $dbname, $dbhost, $dbencoding, $dbtype);
            }
            
            // Log successful connection
            if (class_exists('\XcrudRevolution\Logger')) {
                \XcrudRevolution\Logger::info('Database connected successfully', \XcrudRevolution\Logger::CATEGORY_DATABASE, [
                    'type' => $dbtype,
                    'version' => $this->get_database_version()
                ]);
            }
            
        } catch (Exception $e) {
            $this->error('Connection error: ' . $e->getMessage(), 'error', [
                'Database Type' => $dbtype,
                'Host' => $dbhost,
                'Database' => $dbname,
                'User' => $dbuser,
                'Error Details' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Initialize legacy MySQL connection
     */
    private function initLegacyMySQL($dbuser, $dbpass, $dbname, $dbhost, $dbencoding)
    {
        if (strpos($dbhost, ':') !== false) {
            list($host, $port) = explode(':', $dbhost, 2);
            preg_match('/^([0-9]*)([^0-9]*.*)$/', $port, $socks);
            $this->connect = mysqli_connect($host, $dbuser, $dbpass, $dbname, $socks[1] ? $socks[1] : null, $socks[2] ? $socks[2] : null);
        } else {
            $this->connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
        }
        
        if (!$this->connect) {
            throw new Exception('MySQL Connection failed: ' . mysqli_connect_error());
        }
        
        $this->connect->set_charset($dbencoding);
        
        if ($this->connect->error) {
            throw new Exception('MySQL Charset Error: ' . $this->connect->error);
        }
        
        if (Xcrud_config::$db_time_zone) {
            $this->connect->query('SET time_zone = \'' . Xcrud_config::$db_time_zone . '\'');
        }
    }
    
    /**
     * Initialize multi-database connection
     */
    private function initMultiDatabase($dbuser, $dbpass, $dbname, $dbhost, $dbencoding, $dbtype)
    {
        $config = [
            'host' => $dbhost,
            'user' => $dbuser,
            'pass' => $dbpass,
            'dbname' => $dbname,
            'charset' => $dbencoding
        ];
        
        // Add timezone if configured
        if (Xcrud_config::$db_time_zone ?? false) {
            $config['timezone'] = Xcrud_config::$db_time_zone;
        }
        
        // Handle SQLite special case
        if ($dbtype === 'sqlite' || $dbtype === 'sqlite3') {
            $config['file'] = $dbname; // SQLite uses file path as dbname
        }
        
        $this->driver = DatabaseFactory::create($dbtype, $config);
        
        // Set dummy connect for legacy compatibility
        $this->connect = new stdClass();
    }
    
    /**
     * Execute query - Enhanced with logging and multi-DB support
     * 
     * @param string $query SQL query
     * @return int Affected rows
     */
    public function query($query = '')
    {
        $startTime = microtime(true);
        
        try {
            if ($this->driver) {
                // Multi-database mode
                $affected = $this->driver->query($query);
                $this->result = true; // For backward compatibility
            } else {
                // Legacy MySQL mode
                $this->result = $this->connect->query($query, MYSQLI_USE_RESULT);
                if ($this->connect->error) {
                    throw new Exception($this->connect->error);
                }
                $affected = $this->connect->affected_rows;
            }
            
            $executionTime = microtime(true) - $startTime;
            
            // Log query if Logger is available
            if (class_exists('\XcrudRevolution\Logger')) {
                \XcrudRevolution\Logger::query($query, $executionTime, $affected);
            }
            
            return $affected;
            
        } catch (Exception $e) {
            $executionTime = microtime(true) - $startTime;
            
            // Log error
            if (class_exists('\XcrudRevolution\Logger')) {
                \XcrudRevolution\Logger::error('Database Query Failed', \XcrudRevolution\Logger::CATEGORY_DATABASE, [
                    'query' => $query,
                    'error' => $e->getMessage(),
                    'execution_time' => $executionTime
                ]);
            }
            
            // Use modern ErrorHandler instead of legacy error
            require_once __DIR__ . '/includes/ErrorHandler.php';
            \XcrudRevolution\ErrorHandler::display($e->getMessage() . '<pre>' . $query . '</pre>', 'error', [
                'Query' => $query,
                'Error Code' => $e->getCode(),
                'File' => $e->getFile(),
                'Line' => $e->getLine()
            ]);
            return 0;
        }
    }
    
    /**
     * Get last insert ID
     * 
     * @return int|string
     */
    public function insert_id()
    {
        if ($this->driver) {
            return $this->driver->getLastInsertId();
        } else {
            return $this->connect->insert_id;
        }
    }
    
    /**
     * Get all results as array
     * 
     * @return array
     */
    public function result()
    {
        if ($this->driver) {
            return $this->driver->fetchAll();
        } else {
            $out = array();
            if ($this->result) {
                while ($obj = $this->result->fetch_assoc()) {
                    $out[] = $obj;
                }
                $this->result->free();
            }
            return $out;
        }
    }
    
    /**
     * Get single row
     * 
     * @return array|null
     */
    public function row()
    {
        if ($this->driver) {
            return $this->driver->fetchRow();
        } else {
            if ($this->result === null) {
                return array();
            }
            $obj = $this->result->fetch_assoc();
            $this->result->free();
            return $obj;
        }
    }
    
    /**
     * Escape value - Enhanced for multi-database
     * 
     * @param mixed $val Value to escape
     * @param bool $not_qu Don't add quotes
     * @param string|false $type Data type
     * @param bool $null Allow NULL
     * @param bool $bit Is BIT field
     * @return string
     */
    public function escape($val, $not_qu = false, $type = false, $null = false, $bit = false)
    {
        if ($this->driver) {
            return $this->driver->escape($val, $not_qu, $type, $null, $bit);
        } else {
            // Legacy MySQL escape logic
            if ($type) {
                switch ($type) {
                    case 'bool':
                        if ($bit) {
                            return (int)$val ? 'b\'1\'' : 'b\'0\'';
                        } else {
                            return (int)$val ? '1' : '0';
                        }
                    case 'int':
                        if ($val === '' || $val === null) {
                            return $null ? 'NULL' : '0';
                        }
                        return (string)(int)$val;
                    case 'float':
                        if ($val === '' || $val === null) {
                            return $null ? 'NULL' : '0';
                        }
                        return (string)(float)$val;
                    default:
                        if ($val === '' || $val === null) {
                            if ($null) {
                                return 'NULL';
                            } else {
                                return '\'\'';
                            }
                        } else {
                            if ($type == 'point') {
                                $val = preg_replace('[^0-9\.\,\-]', '', $val);
                            }
                        }
                        break;
                }
            }
            
            if ($not_qu) {
                return $this->connect->real_escape_string((string)$val);
            }
            
            return '\'' . $this->connect->real_escape_string((string)$val) . '\'';
        }
    }
    
    /**
     * Escape for LIKE query
     * 
     * @param mixed $val Value to escape
     * @param array $pattern LIKE pattern
     * @return string
     */
    public function escape_like($val, $pattern = array('%', '%'))
    {
        if ($this->driver) {
            return $this->driver->escapeLike($val, $pattern);
        } else {
            if (is_int($val)) {
                return '\'' . $pattern[0] . (int)$val . $pattern[1] . '\'';
            }
            if ($val == '') {
                return '\'\'';
            } else {
                return '\'' . $pattern[0] . $this->connect->real_escape_string((string)$val) . $pattern[1] . '\'';
            }
        }
    }
    
    /**
     * Enhanced error handling with modern styling
     * 
     * @param string $text Error message
     * @param string $type Error type
     * @param array $details Additional details
     */
    private function error($text = 'Error!', $type = 'error', $details = [])
    {
        // Log error if Logger available
        if (class_exists('\XcrudRevolution\Logger')) {
            \XcrudRevolution\Logger::error($text, \XcrudRevolution\Logger::CATEGORY_DATABASE, $details);
        }
        
        // Include modern error handler if not already included
        if (!class_exists('\XcrudRevolution\ErrorHandler')) {
            require_once(__DIR__ . '/includes/ErrorHandler.php');
        }
        
        // Add database connection details if available
        $errorDetails = array_merge($details, [
            'Database Type' => $this->dbtype,
            'Host' => $this->dbhost,
            'Database' => $this->dbname,
            'User' => $this->dbuser
        ]);
        
        if ($this->driver && $lastError = $this->driver->getLastError()) {
            $errorDetails['Database Error'] = $lastError;
        } elseif ($this->connect && isset($this->connect->error) && $this->connect->error) {
            $errorDetails['MySQL Error'] = $this->connect->error;
        }
        
        // Use modern error display
        \XcrudRevolution\ErrorHandler::display($text, $type, $errorDetails);
    }
    
    // NEW METHODS FOR ENHANCED FUNCTIONALITY
    
    /**
     * Get current database type
     * 
     * @return string
     */
    public function get_database_type()
    {
        if ($this->driver) {
            return $this->driver->getDriverType();
        }
        return 'mysql';
    }
    
    /**
     * Get database version
     * 
     * @return string
     */
    public function get_database_version()
    {
        if ($this->driver) {
            return $this->driver->getVersion();
        } elseif ($this->connect) {
            return $this->connect->server_info;
        }
        return 'Unknown';
    }
    
    /**
     * Begin transaction
     * 
     * @return bool
     */
    public function begin_transaction()
    {
        if ($this->driver) {
            return $this->driver->beginTransaction();
        } elseif ($this->connect) {
            return $this->connect->begin_transaction();
        }
        return false;
    }
    
    /**
     * Commit transaction
     * 
     * @return bool
     */
    public function commit()
    {
        if ($this->driver) {
            return $this->driver->commit();
        } elseif ($this->connect) {
            return $this->connect->commit();
        }
        return false;
    }
    
    /**
     * Rollback transaction
     * 
     * @return bool
     */
    public function rollback()
    {
        if ($this->driver) {
            return $this->driver->rollback();
        } elseif ($this->connect) {
            return $this->connect->rollback();
        }
        return false;
    }
    
    /**
     * Check if connected
     * 
     * @return bool
     */
    public function is_connected()
    {
        if ($this->driver) {
            return $this->driver->isConnected();
        } elseif ($this->connect) {
            return $this->connect->ping();
        }
        return false;
    }
    
    /**
     * Close connection
     */
    public function close()
    {
        if ($this->driver) {
            $this->driver->close();
        } elseif ($this->connect) {
            $this->connect->close();
        }
    }
    
    /**
     * Get last error
     * 
     * @return string|null
     */
    public function get_last_error()
    {
        if ($this->driver) {
            return $this->driver->getLastError();
        } elseif ($this->connect && $this->connect->error) {
            return $this->connect->error;
        }
        return null;
    }
    
    /**
     * Get Query Builder instance for current database type
     * 
     * @return QueryBuilder
     */
    public function getQueryBuilder()
    {
        return new QueryBuilder($this->get_database_type());
    }
    
    /**
     * Static method to get QueryBuilder for connection instance
     * 
     * @param array|false $connection Connection parameters or false for default
     * @return QueryBuilder
     */
    public static function getQueryBuilderForConnection($connection = false)
    {
        $db = self::get_instance($connection);
        return $db->getQueryBuilder();
    }
    
    /**
     * Create new connection with different database (STATIC METHOD)
     * 
     * @param string $type Database type
     * @param array $config Connection config
     * @return Xcrud_db
     */
    public static function create_connection($type, $config)
    {
        $config['driver'] = $type;
        return self::get_instance($config);
    }
}