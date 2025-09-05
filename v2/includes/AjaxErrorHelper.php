<?php
/**
 * xCrudRevolution - AJAX Error Helper
 * Helper for displaying errors in AJAX responses
 * 
 * @package    xCrudRevolution
 * @version    2.0.0
 * @copyright  Copyright (c) 2024 xCrudRevolution. All rights reserved.
 * @license    Proprietary License - Unauthorized copying or distribution is prohibited.
 * @website    https://www.xcrudrevolution.com
 */

namespace XcrudRevolution;

class AjaxErrorHelper 
{
    /**
     * Return JSON error response
     * 
     * @param string $message Error message
     * @param array $details Additional details
     * @param int $code HTTP status code
     * @return void
     */
    public static function jsonError($message, $details = [], $code = 400) 
    {
        http_response_code($code);
        header('Content-Type: application/json');
        
        $response = [
            'success' => false,
            'error' => true,
            'message' => $message,
            'details' => $details,
            'code' => $code,
            'timestamp' => date('Y-m-d H:i:s'),
            'version' => '2.0.0'
        ];
        
        exit(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
    
    /**
     * Return JSON success response
     * 
     * @param mixed $data Response data
     * @param string $message Success message
     * @return void
     */
    public static function jsonSuccess($data = null, $message = 'Success') 
    {
        header('Content-Type: application/json');
        
        $response = [
            'success' => true,
            'error' => false,
            'message' => $message,
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s'),
            'version' => '2.0.0'
        ];
        
        exit(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
    
    /**
     * Return HTML error for AJAX content updates
     * 
     * @param string $message Error message
     * @param string $type Error type
     * @param array $details Additional details
     * @return void
     */
    public static function htmlError($message, $type = 'error', $details = []) 
    {
        if (!class_exists('\XcrudRevolution\ErrorHandler')) {
            require_once(__DIR__ . '/ErrorHandler.php');
        }
        
        exit(ErrorHandler::displayInline($message, $type, $details));
    }
    
    /**
     * Auto-detect AJAX request and format error appropriately
     * 
     * @param string $message Error message
     * @param string $type Error type
     * @param array $details Additional details
     * @return void
     */
    public static function autoError($message, $type = 'error', $details = []) 
    {
        // Check if it's an AJAX request
        $isAjax = (
            isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
        ) || (
            isset($_SERVER['HTTP_ACCEPT']) && 
            strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false
        );
        
        // Check if JSON response is expected
        $expectsJson = (
            isset($_GET['format']) && $_GET['format'] === 'json'
        ) || (
            isset($_POST['format']) && $_POST['format'] === 'json'
        ) || (
            isset($_SERVER['CONTENT_TYPE']) && 
            strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false
        );
        
        if ($isAjax && $expectsJson) {
            self::jsonError($message, $details);
        } elseif ($isAjax) {
            self::htmlError($message, $type, $details);
        } else {
            // Full page error
            if (!class_exists('\XcrudRevolution\ErrorHandler')) {
                require_once(__DIR__ . '/ErrorHandler.php');
            }
            ErrorHandler::display($message, $type, $details);
        }
    }
    
    /**
     * Handle validation errors
     * 
     * @param array $errors Array of field => error message
     * @return void
     */
    public static function validationErrors($errors) 
    {
        $message = 'Validation failed. Please check the highlighted fields.';
        $details = [];
        
        foreach ($errors as $field => $error) {
            $details['Field: ' . $field] = $error;
        }
        
        self::autoError($message, 'warning', $details);
    }
    
    /**
     * Handle database errors with connection details
     * 
     * @param string $message Error message
     * @param array $connectionInfo Database connection info
     * @return void
     */
    public static function databaseError($message, $connectionInfo = []) 
    {
        $details = array_merge([
            'Error Type' => 'Database Connection Error',
            'Time' => date('Y-m-d H:i:s')
        ], $connectionInfo);
        
        self::autoError($message, 'error', $details);
    }
    
    /**
     * Handle file upload errors
     * 
     * @param string $filename Original filename
     * @param string $error Upload error
     * @param array $fileInfo File information
     * @return void
     */
    public static function uploadError($filename, $error, $fileInfo = []) 
    {
        $message = "File upload failed: {$filename}";
        
        $details = array_merge([
            'Filename' => $filename,
            'Upload Error' => $error,
            'Max File Size' => ini_get('upload_max_filesize'),
            'Max POST Size' => ini_get('post_max_size')
        ], $fileInfo);
        
        self::autoError($message, 'warning', $details);
    }
    
    /**
     * Handle permission errors
     * 
     * @param string $action Action that was denied
     * @param string $resource Resource name
     * @return void
     */
    public static function permissionError($action, $resource = null) 
    {
        $message = "Permission denied";
        if ($resource) {
            $message .= " for {$resource}";
        }
        
        $details = [
            'Action Denied' => $action,
            'User' => $_SESSION['xcrud_user'] ?? 'Anonymous',
            'IP Address' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
            'User Agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'
        ];
        
        if ($resource) {
            $details['Resource'] = $resource;
        }
        
        self::autoError($message, 'error', $details);
    }
}