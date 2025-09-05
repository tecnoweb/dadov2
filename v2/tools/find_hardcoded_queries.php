<?php
/**
 * xCrudRevolution - Hardcoded Query Finder
 * Find all MySQL-specific queries that need to be converted for multi-DB support
 * 
 * @package    xCrudRevolution
 * @version    2.0.0
 * @copyright  Copyright (c) 2024 xCrudRevolution. All rights reserved.
 * @website    https://www.xcrudrevolution.com
 */

echo "🔍 xCrudRevolution - Hardcoded Query Finder\n";
echo "===========================================\n\n";

$files_to_scan = [
    '../xcrud.php',
    '../xcrud_db.php'
];

$mysql_patterns = [
    // MySQL-specific queries
    '/SHOW\s+(TABLES|COLUMNS|FULL\s+COLUMNS)/i',
    '/DESCRIBE\s+\w+/i',
    '/EXPLAIN\s+\w+/i',
    
    // MySQL-specific functions
    '/CONCAT\s*\(/i',
    '/GROUP_CONCAT\s*\(/i',
    '/IFNULL\s*\(/i',
    '/IF\s*\(/i',
    '/SUBSTRING\s*\(/i',
    '/DATE_FORMAT\s*\(/i',
    '/UNIX_TIMESTAMP\s*\(/i',
    '/FROM_UNIXTIME\s*\(/i',
    '/CURDATE\s*\(\)/i',
    '/NOW\s*\(\)/i',
    
    // MySQL-specific syntax
    '/LIMIT\s+\d+\s*,\s*\d+/i',  // MySQL-style LIMIT offset,count
    '/`[^`]+`/i',                 // MySQL backtick identifiers
    '/AUTO_INCREMENT/i',
    '/UNSIGNED/i',
    '/ZEROFILL/i',
    
    // MySQL-specific data types
    '/\b(TINYINT|MEDIUMINT|BIGINT)\b/i',
    '/\b(TINYTEXT|MEDIUMTEXT|LONGTEXT)\b/i',
    '/\b(TINYBLOB|MEDIUMBLOB|LONGBLOB)\b/i',
    '/\bDATETIME\b/i',
    '/\bTIMESTAMP\b/i',
];

$query_patterns = [
    // Direct query calls
    '/\$\w+->query\s*\(\s*["\']([^"\']+)["\']/i',
    '/query\s*\(\s*["\']([^"\']+)["\']/i',
    
    // String concatenation queries
    '/["\']([^"\']*(?:SELECT|INSERT|UPDATE|DELETE|SHOW|DESCRIBE)[^"\']*)["\']/',
];

$total_issues = 0;
$results = [];

foreach ($files_to_scan as $file) {
    if (!file_exists($file)) {
        echo "❌ File not found: $file\n";
        continue;
    }
    
    echo "📁 Scanning: $file\n";
    
    $content = file_get_contents($file);
    $lines = explode("\n", $content);
    $file_issues = [];
    
    // Find direct query patterns
    foreach ($query_patterns as $pattern) {
        preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE);
        
        foreach ($matches[1] as $match) {
            $query = $match[0];
            $offset = $match[1];
            
            // Find line number
            $lineNum = substr_count(substr($content, 0, $offset), "\n") + 1;
            
            // Check if this query has MySQL-specific elements
            $mysql_elements = [];
            foreach ($mysql_patterns as $pattern_name => $mysql_pattern) {
                if (preg_match($mysql_pattern, $query)) {
                    $mysql_elements[] = $pattern_name;
                }
            }
            
            if (!empty($mysql_elements)) {
                $file_issues[] = [
                    'line' => $lineNum,
                    'query' => substr($query, 0, 100) . (strlen($query) > 100 ? '...' : ''),
                    'issues' => $mysql_elements,
                    'full_line' => trim($lines[$lineNum - 1])
                ];
                $total_issues++;
            }
        }
    }
    
    // Find inline SQL in string concatenations and assignments
    foreach ($lines as $lineNum => $line) {
        $line = trim($line);
        
        // Skip comments and empty lines
        if (empty($line) || strpos($line, '//') === 0 || strpos($line, '#') === 0 || strpos($line, '/*') === 0) {
            continue;
        }
        
        // Check for MySQL-specific patterns in any line
        foreach ($mysql_patterns as $pattern_name => $mysql_pattern) {
            if (preg_match($mysql_pattern, $line)) {
                // Make sure it's not already reported
                $already_reported = false;
                foreach ($file_issues as $issue) {
                    if ($issue['line'] === $lineNum + 1) {
                        $already_reported = true;
                        break;
                    }
                }
                
                if (!$already_reported) {
                    $file_issues[] = [
                        'line' => $lineNum + 1,
                        'query' => 'Inline SQL detected',
                        'issues' => [$pattern_name],
                        'full_line' => $line
                    ];
                    $total_issues++;
                }
                break; // Only report first issue per line
            }
        }
    }
    
    if (!empty($file_issues)) {
        $results[$file] = $file_issues;
        echo "  ⚠️  Found " . count($file_issues) . " potential issues\n";
    } else {
        echo "  ✅ No MySQL-specific queries found\n";
    }
    
    echo "\n";
}

// Report results
echo "📊 DETAILED REPORT\n";
echo "==================\n\n";

if (empty($results)) {
    echo "✅ No MySQL-specific queries found!\n";
} else {
    foreach ($results as $file => $issues) {
        echo "📁 $file (" . count($issues) . " issues):\n";
        echo str_repeat('-', strlen($file) + 20) . "\n";
        
        foreach ($issues as $issue) {
            echo "  📍 Line {$issue['line']}: {$issue['query']}\n";
            echo "     Issues: " . implode(', ', $issue['issues']) . "\n";
            echo "     Code: " . htmlspecialchars($issue['full_line']) . "\n\n";
        }
        
        echo "\n";
    }
    
    echo "⚠️  TOTAL ISSUES: $total_issues\n\n";
    
    // Suggestions
    echo "💡 RECOMMENDATIONS:\n";
    echo "===================\n";
    echo "1. Replace SHOW COLUMNS/TABLES with QueryBuilder methods\n";
    echo "2. Convert MySQL-specific functions:\n";
    echo "   - CONCAT() → database-specific concatenation\n";
    echo "   - IFNULL() → COALESCE() (standard SQL)\n";
    echo "   - NOW() → database-specific current timestamp\n";
    echo "   - LIMIT x,y → LIMIT y OFFSET x (standard SQL)\n";
    echo "3. Replace backticks (`) with proper identifier quoting\n";
    echo "4. Map MySQL data types to generic types\n";
    echo "5. Use QueryBuilder for complex queries\n\n";
    
    echo "🔧 PRIORITY FILES TO UPDATE:\n";
    $file_priority = [];
    foreach ($results as $file => $issues) {
        $file_priority[] = ['file' => $file, 'count' => count($issues)];
    }
    
    usort($file_priority, function($a, $b) {
        return $b['count'] - $a['count'];
    });
    
    foreach ($file_priority as $item) {
        echo "   " . $item['count'] . " issues: " . $item['file'] . "\n";
    }
}

echo "\n✨ Scan complete!\n";
echo "📋 Next step: Update the methods with most issues first!\n";