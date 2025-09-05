<?php
/**
 * xCrudRevolution - Professional Logging System
 * Advanced logging system for errors, debug, performance, and database operations
 * 
 * @package    xCrudRevolution
 * @version    2.0.0
 * @copyright  Copyright (c) 2024 xCrudRevolution. All rights reserved.
 * @license    Proprietary License - Unauthorized copying or distribution is prohibited.
 * @website    https://www.xcrudrevolution.com
 * 
 * NOTICE: This professional logging system is designed to be the foundation
 * for advanced debugging tools and commercial addons.
 */

namespace XcrudRevolution;

class Logger 
{
    // Log levels following PSR-3 standard
    const EMERGENCY = 'emergency'; // System is unusable
    const ALERT     = 'alert';     // Action must be taken immediately
    const CRITICAL  = 'critical';  // Critical conditions
    const ERROR     = 'error';     // Error conditions
    const WARNING   = 'warning';   // Warning conditions
    const NOTICE    = 'notice';    // Normal but significant condition
    const INFO      = 'info';      // Informational messages
    const DEBUG     = 'debug';     // Debug-level messages
    
    // Log categories
    const CATEGORY_PHP      = 'php';
    const CATEGORY_DATABASE = 'database';
    const CATEGORY_AJAX     = 'ajax';
    const CATEGORY_UPLOAD   = 'upload';
    const CATEGORY_AUTH     = 'auth';
    const CATEGORY_PERFORMANCE = 'performance';
    const CATEGORY_SECURITY = 'security';
    const CATEGORY_SYSTEM   = 'system';
    
    /**
     * @var array Configuration options
     */
    private static $config = [
        'enabled' => true,
        'log_level' => self::DEBUG,
        'log_file' => null, // Auto-generated if null
        'max_file_size' => '10MB',
        'rotate_files' => true,
        'max_files' => 5,
        'date_format' => 'Y-m-d H:i:s',
        'include_stack_trace' => true,
        'include_context' => true,
        'categories' => [
            self::CATEGORY_PHP => true,
            self::CATEGORY_DATABASE => true,
            self::CATEGORY_AJAX => true,
            self::CATEGORY_UPLOAD => true,
            self::CATEGORY_AUTH => true,
            self::CATEGORY_PERFORMANCE => true,
            self::CATEGORY_SECURITY => true,
            self::CATEGORY_SYSTEM => true,
        ]
    ];
    
    /**
     * @var array Log level priorities
     */
    private static $levelPriorities = [
        self::EMERGENCY => 8,
        self::ALERT     => 7,
        self::CRITICAL  => 6,
        self::ERROR     => 5,
        self::WARNING   => 4,
        self::NOTICE    => 3,
        self::INFO      => 2,
        self::DEBUG     => 1,
    ];
    
    /**
     * @var array Active performance timers
     */
    private static $timers = [];
    
    /**
     * @var array Session data for this request
     */
    private static $sessionData = [];
    
    /**
     * Initialize logger with configuration
     * 
     * @param array $config Configuration options
     */
    public static function init($config = []) 
    {
        self::$config = array_merge(self::$config, $config);
        
        // Set default log file if not specified
        if (!self::$config['log_file']) {
            $logDir = __DIR__ . '/../logs';
            if (!is_dir($logDir)) {
                @mkdir($logDir, 0755, true);
            }
            self::$config['log_file'] = $logDir . '/xcrud-' . date('Y-m-d') . '.log';
        }
        
        // Initialize session data
        self::$sessionData = [
            'request_id' => self::generateRequestId(),
            'session_id' => session_id() ?: 'no-session',
            'user_ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'request_method' => $_SERVER['REQUEST_METHOD'] ?? 'unknown',
            'request_uri' => $_SERVER['REQUEST_URI'] ?? 'unknown',
            'php_version' => PHP_VERSION,
            'memory_limit' => ini_get('memory_limit'),
            'start_time' => microtime(true),
            'start_memory' => memory_get_usage(true)
        ];
        
        // Log system startup
        self::info('xCrudRevolution Logger initialized', self::CATEGORY_SYSTEM, [
            'version' => '2.0.0',
            'config' => array_keys(self::$config['categories'])
        ]);
    }
    
    /**
     * Log emergency message
     */
    public static function emergency($message, $category = self::CATEGORY_SYSTEM, $context = []) 
    {
        self::log(self::EMERGENCY, $message, $category, $context);
    }
    
    /**
     * Log alert message
     */
    public static function alert($message, $category = self::CATEGORY_SYSTEM, $context = []) 
    {
        self::log(self::ALERT, $message, $category, $context);
    }
    
    /**
     * Log critical message
     */
    public static function critical($message, $category = self::CATEGORY_SYSTEM, $context = []) 
    {
        self::log(self::CRITICAL, $message, $category, $context);
    }
    
    /**
     * Log error message
     */
    public static function error($message, $category = self::CATEGORY_PHP, $context = []) 
    {
        self::log(self::ERROR, $message, $category, $context);
    }
    
    /**
     * Log warning message
     */
    public static function warning($message, $category = self::CATEGORY_PHP, $context = []) 
    {
        self::log(self::WARNING, $message, $category, $context);
    }
    
    /**
     * Log notice message
     */
    public static function notice($message, $category = self::CATEGORY_SYSTEM, $context = []) 
    {
        self::log(self::NOTICE, $message, $category, $context);
    }
    
    /**
     * Log info message
     */
    public static function info($message, $category = self::CATEGORY_SYSTEM, $context = []) 
    {
        self::log(self::INFO, $message, $category, $context);
    }
    
    /**
     * Log debug message
     */
    public static function debug($message, $category = self::CATEGORY_SYSTEM, $context = []) 
    {
        self::log(self::DEBUG, $message, $category, $context);
    }
    
    /**
     * Log database query
     */
    public static function query($sql, $executionTime = null, $affectedRows = null, $context = []) 
    {
        if (!self::$config['categories'][self::CATEGORY_DATABASE]) {
            return;
        }
        
        $logContext = array_merge($context, [
            'sql' => $sql,
            'execution_time' => $executionTime,
            'affected_rows' => $affectedRows,
            'memory_usage' => self::formatBytes(memory_get_usage(true))
        ]);
        
        $level = ($executionTime && $executionTime > 1.0) ? self::WARNING : self::DEBUG;
        self::log($level, 'Database Query Executed', self::CATEGORY_DATABASE, $logContext);
    }
    
    /**
     * Log AJAX request
     */
    public static function ajax($action, $data = [], $response = null, $context = []) 
    {
        if (!self::$config['categories'][self::CATEGORY_AJAX]) {
            return;
        }
        
        $logContext = array_merge($context, [
            'action' => $action,
            'request_data' => $data,
            'response_data' => $response,
            'is_ajax' => true
        ]);
        
        self::info('AJAX Request', self::CATEGORY_AJAX, $logContext);
    }
    
    /**
     * Log file upload
     */
    public static function upload($filename, $status, $size = null, $context = []) 
    {
        if (!self::$config['categories'][self::CATEGORY_UPLOAD]) {
            return;
        }
        
        $logContext = array_merge($context, [
            'filename' => $filename,
            'status' => $status,
            'file_size' => $size,
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size')
        ]);
        
        $level = ($status === 'success') ? self::INFO : self::ERROR;
        self::log($level, "File Upload: {$status}", self::CATEGORY_UPLOAD, $logContext);
    }
    
    /**
     * Log authentication events
     */
    public static function auth($event, $user = null, $success = true, $context = []) 
    {
        if (!self::$config['categories'][self::CATEGORY_AUTH]) {
            return;
        }
        
        $logContext = array_merge($context, [
            'event' => $event,
            'user' => $user,
            'success' => $success,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        ]);
        
        $level = $success ? self::INFO : self::WARNING;
        self::log($level, "Auth Event: {$event}", self::CATEGORY_AUTH, $logContext);
    }
    
    /**
     * Log security events
     */
    public static function security($threat, $severity = 'medium', $context = []) 
    {
        if (!self::$config['categories'][self::CATEGORY_SECURITY]) {
            return;
        }
        
        $logContext = array_merge($context, [
            'threat_type' => $threat,
            'severity' => $severity,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'referer' => $_SERVER['HTTP_REFERER'] ?? 'unknown'
        ]);
        
        $level = ($severity === 'high') ? self::CRITICAL : (($severity === 'medium') ? self::WARNING : self::NOTICE);
        self::log($level, "Security Threat: {$threat}", self::CATEGORY_SECURITY, $logContext);
    }
    
    /**
     * Start performance timer
     */
    public static function startTimer($name) 
    {
        if (!self::$config['categories'][self::CATEGORY_PERFORMANCE]) {
            return;
        }
        
        self::$timers[$name] = [
            'start_time' => microtime(true),
            'start_memory' => memory_get_usage(true)
        ];
    }
    
    /**
     * End performance timer and log result
     */
    public static function endTimer($name, $context = []) 
    {
        if (!self::$config['categories'][self::CATEGORY_PERFORMANCE] || !isset(self::$timers[$name])) {
            return;
        }
        
        $timer = self::$timers[$name];
        $endTime = microtime(true);
        $endMemory = memory_get_usage(true);
        
        $executionTime = $endTime - $timer['start_time'];
        $memoryUsed = $endMemory - $timer['start_memory'];
        
        $logContext = array_merge($context, [
            'timer_name' => $name,
            'execution_time' => number_format($executionTime, 4) . 's',
            'memory_used' => self::formatBytes($memoryUsed),
            'peak_memory' => self::formatBytes(memory_get_peak_usage(true))
        ]);
        
        $level = ($executionTime > 2.0) ? self::WARNING : self::DEBUG;
        self::log($level, "Performance Timer: {$name}", self::CATEGORY_PERFORMANCE, $logContext);
        
        unset(self::$timers[$name]);
    }
    
    /**
     * Main logging method
     */
    private static function log($level, $message, $category, $context = []) 
    {
        if (!self::$config['enabled']) {
            return;
        }
        
        // Check if category is enabled
        if (!isset(self::$config['categories'][$category]) || !self::$config['categories'][$category]) {
            return;
        }
        
        // Check log level
        if (self::$levelPriorities[$level] < self::$levelPriorities[self::$config['log_level']]) {
            return;
        }
        
        // Prepare log entry
        $logEntry = self::formatLogEntry($level, $message, $category, $context);
        
        // Write to log file
        self::writeToFile($logEntry);
        
        // TODO: Add database logging option
        // TODO: Add email alerts for critical errors
        // TODO: Add Slack/Discord webhook notifications
    }
    
    /**
     * Format log entry
     */
    private static function formatLogEntry($level, $message, $category, $context) 
    {
        $timestamp = date(self::$config['date_format']);
        
        $entry = [
            'timestamp' => $timestamp,
            'request_id' => self::$sessionData['request_id'] ?? uniqid('req_', true),
            'level' => strtoupper($level),
            'category' => strtoupper($category),
            'message' => $message,
        ];
        
        // Add context if enabled
        if (self::$config['include_context'] && !empty($context)) {
            $entry['context'] = $context;
        }
        
        // Add stack trace for errors
        if (self::$config['include_stack_trace'] && in_array($level, [self::ERROR, self::CRITICAL, self::ALERT, self::EMERGENCY])) {
            $entry['stack_trace'] = self::getStackTrace();
        }
        
        // Add session data for important events
        if (in_array($level, [self::WARNING, self::ERROR, self::CRITICAL, self::ALERT, self::EMERGENCY])) {
            $entry['session'] = array_merge(self::$sessionData, [
                'current_memory' => self::formatBytes(memory_get_usage(true)),
                'peak_memory' => self::formatBytes(memory_get_peak_usage(true)),
                'execution_time' => number_format(microtime(true) - self::$sessionData['start_time'], 4) . 's'
            ]);
        }
        
        return json_encode($entry, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n";
    }
    
    /**
     * Write log entry to file
     */
    private static function writeToFile($logEntry) 
    {
        // Initialize log file path if not set
        if (empty(self::$config['log_file'])) {
            $logDir = dirname(dirname(__FILE__)) . '/logs';
            if (!is_dir($logDir)) {
                @mkdir($logDir, 0777, true);
            }
            self::$config['log_file'] = $logDir . '/xcrud-' . date('Y-m-d') . '.log';
        }
        
        $logFile = self::$config['log_file'];
        
        // Check file size and rotate if necessary
        if (file_exists($logFile) && self::$config['rotate_files']) {
            $fileSize = filesize($logFile);
            $maxSize = self::parseSize(self::$config['max_file_size']);
            
            if ($fileSize >= $maxSize) {
                self::rotateLogFiles();
            }
        }
        
        // Write log entry
        @file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Rotate log files
     */
    private static function rotateLogFiles() 
    {
        $logFile = self::$config['log_file'];
        $maxFiles = self::$config['max_files'];
        
        // Shift existing files
        for ($i = $maxFiles - 1; $i > 0; $i--) {
            $oldFile = $logFile . '.' . $i;
            $newFile = $logFile . '.' . ($i + 1);
            
            if (file_exists($oldFile)) {
                @rename($oldFile, $newFile);
            }
        }
        
        // Move current log file
        if (file_exists($logFile)) {
            @rename($logFile, $logFile . '.1');
        }
        
        // Remove old files
        for ($i = $maxFiles + 1; $i <= $maxFiles + 10; $i++) {
            $oldFile = $logFile . '.' . $i;
            if (file_exists($oldFile)) {
                @unlink($oldFile);
            }
        }
    }
    
    /**
     * Get stack trace
     */
    private static function getStackTrace() 
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 10);
        $formattedTrace = [];
        
        foreach ($trace as $frame) {
            if (isset($frame['file']) && isset($frame['line'])) {
                $formattedTrace[] = basename($frame['file']) . ':' . $frame['line'] . 
                    (isset($frame['function']) ? ' ' . $frame['function'] . '()' : '');
            }
        }
        
        return $formattedTrace;
    }
    
    /**
     * Generate unique request ID
     */
    private static function generateRequestId() 
    {
        return substr(md5(microtime(true) . mt_rand()), 0, 8);
    }
    
    /**
     * Parse size string to bytes
     */
    private static function parseSize($size) 
    {
        $unit = strtoupper(substr($size, -2));
        $value = (int)$size;
        
        switch ($unit) {
            case 'GB': return $value * 1024 * 1024 * 1024;
            case 'MB': return $value * 1024 * 1024;
            case 'KB': return $value * 1024;
            default: return $value;
        }
    }
    
    /**
     * Format bytes to human readable
     */
    private static function formatBytes($bytes, $precision = 2) 
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return number_format($bytes, $precision) . ' ' . $units[$i];
    }
    
    /**
     * Get current configuration
     */
    public static function getConfig() 
    {
        return self::$config;
    }
    
    /**
     * Update configuration
     */
    public static function setConfig($key, $value) 
    {
        if (isset(self::$config[$key])) {
            self::$config[$key] = $value;
        }
    }
    
    /**
     * Enable/disable specific category
     */
    public static function toggleCategory($category, $enabled = true) 
    {
        if (isset(self::$config['categories'][$category])) {
            self::$config['categories'][$category] = $enabled;
        }
    }
    
    /**
     * Get log statistics
     */
    public static function getStats() 
    {
        $logFile = self::$config['log_file'];
        
        if (!file_exists($logFile)) {
            return null;
        }
        
        return [
            'file_size' => self::formatBytes(filesize($logFile)),
            'file_path' => $logFile,
            'created' => date('Y-m-d H:i:s', filemtime($logFile)),
            'entries_today' => self::countLogEntries('today'),
            'errors_today' => self::countLogEntries('today', 'error'),
            'active_timers' => count(self::$timers)
        ];
    }
    
    /**
     * Count log entries
     */
    private static function countLogEntries($period = 'today', $level = null) 
    {
        // This is a simplified implementation
        // In production, you might want to use a more efficient method
        return 0; // Placeholder
    }
}