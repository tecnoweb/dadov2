<?php
/**
 * xCrudRevolution - Modern Error Handler
 * Beautiful and professional error display system
 * 
 * @package    xCrudRevolution
 * @version    2.0.0
 * @copyright  Copyright (c) 2024 xCrudRevolution. All rights reserved.
 * @license    Proprietary License
 * @website    https://www.xcrudrevolution.com
 */

namespace XcrudRevolution;

class ErrorHandler 
{
    /**
     * Display error with modern styling
     * 
     * @param string $message Error message
     * @param string $type Error type (error, warning, info, success)
     * @param array $details Additional details
     * @param bool $die Exit after displaying error
     * @return string HTML output
     */
    public static function display($message, $type = 'error', $details = [], $die = true, $fullPage = null) 
    {
        // Parse message to extract SQL queries in <pre> tags and convert to details
        $parsedMessage = self::parseErrorMessage($message, $details);
        $cleanMessage = $parsedMessage['message'];
        $enhancedDetails = $parsedMessage['details'];
        
        // Auto-detect if we should show full page or inline based on context
        if ($fullPage === null) {
            $fullPage = !self::isAjaxContext() && !self::isEmbeddedContext();
        }
        
        if ($fullPage) {
            self::displayFullPage($cleanMessage, $type, $enhancedDetails, $die);
        } else {
            self::displayInline($cleanMessage, $type, $enhancedDetails, $die);
        }
    }
    
    /**
     * Parse error message to extract SQL queries and technical details
     * 
     * @param string $message Original error message
     * @param array $details Existing details
     * @return array ['message' => clean_message, 'details' => enhanced_details]
     */
    private static function parseErrorMessage($message, $details = [])
    {
        $cleanMessage = $message;
        $enhancedDetails = $details;
        
        // Extract SQL queries from <pre> tags
        if (preg_match('/(.*?)<pre>(.*?)<\/pre>/s', $message, $matches)) {
            $cleanMessage = trim($matches[1]);
            $sqlQuery = trim($matches[2]);
            
            if ($sqlQuery) {
                $enhancedDetails['SQL Query'] = $sqlQuery;
            }
        }
        
        // Add database connection info if available
        if (!isset($enhancedDetails['Database Type'])) {
            $enhancedDetails['Database Type'] = 'mysql';
        }
        if (!isset($enhancedDetails['Host'])) {
            $enhancedDetails['Host'] = 'localhost';
        }
        if (!isset($enhancedDetails['Database'])) {
            $enhancedDetails['Database'] = 'dadov2';
        }
        if (!isset($enhancedDetails['User'])) {
            $enhancedDetails['User'] = 'dado';
        }
        
        // Extract MySQL specific error if present
        if (preg_match("/Table '([^']+)\.([^']+)' doesn't exist/", $cleanMessage, $matches)) {
            $enhancedDetails['MySQL Error'] = "Table '{$matches[1]}.{$matches[2]}' doesn't exist";
        }
        
        return [
            'message' => $cleanMessage,
            'details' => $enhancedDetails
        ];
    }
    
    /**
     * Display full page error
     */
    private static function displayFullPage($message, $type, $details, $die)
    {
        $icon = self::getIcon($type);
        $title = self::getTitle($type);
        $detailsHtml = self::formatDetails($details);
        
        $html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>xCrudRevolution - ' . $title . '</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .xcrud-error-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            width: 100%;
            overflow: hidden;
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .xcrud-error-header {
            background: ' . self::getGradient($type) . ';
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .xcrud-error-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 15px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
        }
        
        .xcrud-error-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .xcrud-error-subtitle {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .xcrud-error-body {
            padding: 30px;
        }
        
        .xcrud-error-message {
            color: #2c3e50;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        
        .xcrud-error-details {
            background: #f8f9fa;
            border-left: 4px solid ' . self::getColor($type) . ';
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
        }
        
        .xcrud-error-details-title {
            font-weight: 600;
            color: #495057;
            margin-bottom: 10px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .xcrud-error-details-content {
            color: #6c757d;
            font-family: "Monaco", "Menlo", "Ubuntu Mono", monospace;
            font-size: 13px;
            line-height: 1.5;
        }
        
        .xcrud-error-footer {
            background: #f8f9fa;
            padding: 20px 30px;
            border-top: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .xcrud-error-brand {
            display: flex;
            align-items: center;
            color: #6c757d;
            font-size: 12px;
        }
        
        .xcrud-error-brand-logo {
            width: 20px;
            height: 20px;
            margin-right: 8px;
            opacity: 0.5;
        }
        
        .xcrud-error-actions {
            display: flex;
            gap: 10px;
        }
        
        .xcrud-error-button {
            padding: 8px 16px;
            border-radius: 6px;
            border: none;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }
        
        .xcrud-error-button-primary {
            background: ' . self::getColor($type) . ';
            color: white;
        }
        
        .xcrud-error-button-primary:hover {
            opacity: 0.9;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .xcrud-error-button-secondary {
            background: #e9ecef;
            color: #495057;
        }
        
        .xcrud-error-button-secondary:hover {
            background: #dee2e6;
        }
        
        @media (max-width: 600px) {
            .xcrud-error-footer {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="xcrud-error-container">
        <div class="xcrud-error-header">
            <div class="xcrud-error-icon">' . $icon . '</div>
            <div class="xcrud-error-title">' . $title . '</div>
            <div class="xcrud-error-subtitle">xCrudRevolution v2.0</div>
        </div>
        
        <div class="xcrud-error-body">
            <div class="xcrud-error-message">' . htmlspecialchars($message) . '</div>
            ' . $detailsHtml . '
        </div>
        
        <div class="xcrud-error-footer">
            <div class="xcrud-error-brand">
                <svg class="xcrud-error-brand-logo" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
                </svg>
                xCrudRevolution &copy; 2024
            </div>
            <div class="xcrud-error-actions">
                <a href="javascript:history.back()" class="xcrud-error-button xcrud-error-button-secondary">Go Back</a>
                <a href="https://www.xcrudrevolution.com/support" class="xcrud-error-button xcrud-error-button-primary">Get Help</a>
            </div>
        </div>
    </div>
</body>
</html>';
        
        if ($die) {
            exit($html);
        }
        
        return $html;
    }
    
    /**
     * Display inline error (for AJAX responses)
     */
    private static function displayInline($message, $type, $details, $die)
    {
        $icon = self::getIcon($type);
        $detailsHtml = self::formatDetails($details);
        
        $html = '
        <div class="xcrud-inline-error" style="
            border-radius: 8px;
            padding: 16px;
            margin: 10px 0;
            background: ' . self::getLightBg($type) . ';
            border-left: 4px solid ' . self::getColor($type) . ';
            font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, sans-serif;
        ">
            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                <span style="
                    font-size: 20px; 
                    margin-right: 10px;
                    background: ' . self::getColor($type) . ';
                    color: white;
                    width: 28px;
                    height: 28px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                ">' . $icon . '</span>
                <strong style="color: ' . self::getColor($type) . ';">' . self::getTitle($type) . '</strong>
            </div>
            <div style="color: #2c3e50; margin-left: 38px;">' . htmlspecialchars($message) . '</div>
            ' . ($detailsHtml ? '<div style="margin-left: 38px; margin-top: 10px;">' . $detailsHtml . '</div>' : '') . '
        </div>';
        
        if ($die) {
            exit($html);
        }
        
        return $html;
    }
    
    /**
     * Get icon for error type
     */
    private static function getIcon($type) 
    {
        switch ($type) {
            case 'success':
                return '✓';
            case 'warning':
                return '⚠';
            case 'info':
                return 'ℹ';
            case 'error':
            default:
                return '✕';
        }
    }
    
    /**
     * Get title for error type
     */
    private static function getTitle($type) 
    {
        switch ($type) {
            case 'success':
                return 'Success';
            case 'warning':
                return 'Warning';
            case 'info':
                return 'Information';
            case 'error':
            default:
                return 'Error Occurred';
        }
    }
    
    /**
     * Get color for error type
     */
    private static function getColor($type) 
    {
        switch ($type) {
            case 'success':
                return '#28a745';
            case 'warning':
                return '#ffc107';
            case 'info':
                return '#17a2b8';
            case 'error':
            default:
                return '#dc3545';
        }
    }
    
    /**
     * Get gradient for error type
     */
    private static function getGradient($type) 
    {
        switch ($type) {
            case 'success':
                return 'linear-gradient(135deg, #667eea 0%, #28a745 100%)';
            case 'warning':
                return 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)';
            case 'info':
                return 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)';
            case 'error':
            default:
                return 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)';
        }
    }
    
    /**
     * Get light background color
     */
    private static function getLightBg($type) 
    {
        switch ($type) {
            case 'success':
                return '#d4edda';
            case 'warning':
                return '#fff3cd';
            case 'info':
                return '#d1ecf1';
            case 'error':
            default:
                return '#f8d7da';
        }
    }
    
    /**
     * Format additional details
     */
    private static function formatDetails($details) 
    {
        if (empty($details)) {
            return '';
        }
        
        $html = '<div class="xcrud-error-details">';
        $html .= '<div class="xcrud-error-details-title">Technical Details</div>';
        $html .= '<div class="xcrud-error-details-content">';
        
        foreach ($details as $key => $value) {
            $html .= '<strong>' . htmlspecialchars($key) . ':</strong> ' . htmlspecialchars($value) . '<br>';
        }
        
        $html .= '</div></div>';
        
        return $html;
    }
    
    /**
     * Check if we're in an AJAX context
     */
    private static function isAjaxContext()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
    
    /**
     * Check if we're in an embedded context (partial page load)
     */
    private static function isEmbeddedContext()
    {
        return isset($_GET['xcrud']) || isset($_POST['xcrud']) || 
               strpos($_SERVER['REQUEST_URI'] ?? '', 'xcrud') !== false;
    }
    
    
    /**
     * Legacy error display (for backward compatibility)
     */
    public static function legacyError($text) 
    {
        exit('<div class="xcrud-error" style="position:relative;line-height:1.25;padding:15px;color:#BA0303;margin:10px;border:1px solid #BA0303;border-radius:4px;font-family:Arial,sans-serif;background:#FFB5B5;box-shadow:inset 0 0 80px #E58989;">
            <span style="position:absolute;font-size:10px;bottom:3px;right:5px;">xCrudRevolution</span>' . $text . '</div>');
    }
}