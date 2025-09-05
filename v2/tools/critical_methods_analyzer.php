<?php
/**
 * xCrudRevolution - Critical Methods Analyzer
 * Identifies the most critical methods that need QueryBuilder conversion
 * Focus on _build_*, _create, _update, _delete methods
 * 
 * @package    xCrudRevolution
 * @version    2.0.0
 * @copyright  Copyright (c) 2024 xCrudRevolution. All rights reserved.
 * @website    https://www.xcrudrevolution.com
 */

echo "🔍 xCrudRevolution - Critical Methods Analyzer\n";
echo "================================================\n\n";

$file = '../xcrud.php';
if (!file_exists($file)) {
    die("❌ xcrud.php not found!\n");
}

$content = file_get_contents($file);
$lines = explode("\n", $content);

// Target critical methods for multi-database conversion
$critical_patterns = [
    '_build_' => 'Query Building Methods',
    '_create' => 'Create Operations', 
    '_update' => 'Update Operations',
    '_delete' => 'Delete Operations',
    '_remove' => 'Remove Operations',
    '_get_table_info' => 'Table Metadata',
    'render_list' => 'List Rendering',
    'render_details' => 'Detail Rendering'
];

// Find all methods and their content
$methods = [];
$current_method = null;
$brace_count = 0;
$in_method = false;
$method_content = [];

foreach ($lines as $lineNum => $line) {
    $trimmedLine = trim($line);
    
    // Find method definitions
    if (preg_match('/^\s*(public|protected|private)\s+(static\s+)?function\s+(\w+)\s*\(/', $trimmedLine, $matches)) {
        // Save previous method if exists
        if ($current_method) {
            $current_method['end_line'] = $lineNum;
            $current_method['content'] = implode("\n", $method_content);
            
            // Check if method matches our critical patterns
            foreach ($critical_patterns as $pattern => $category) {
                if (strpos($current_method['name'], $pattern) !== false) {
                    $current_method['category'] = $category;
                    $current_method['priority'] = 'HIGH';
                    break;
                }
            }
            
            $methods[] = $current_method;
        }
        
        // Start new method
        $method_name = $matches[3];
        $current_method = [
            'name' => $method_name,
            'start_line' => $lineNum + 1,
            'visibility' => $matches[1],
            'static' => !empty($matches[2]),
            'category' => 'Other',
            'priority' => 'LOW',
            'queries' => [],
            'mysql_dependencies' => []
        ];
        $brace_count = 0;
        $in_method = true;
        $method_content = [];
    }
    
    if ($in_method) {
        $method_content[] = $line;
        
        // Count braces to find method end
        $brace_count += substr_count($line, '{') - substr_count($line, '}');
        
        // Check for direct MySQL dependencies
        if (preg_match_all('/\$\w+->query\s*\(\s*["\']([^"\']+)["\']/i', $line, $query_matches)) {
            foreach ($query_matches[1] as $query) {
                $current_method['queries'][] = [
                    'line' => $lineNum + 1,
                    'query' => substr($query, 0, 100) . (strlen($query) > 100 ? '...' : ''),
                    'full_line' => trim($line)
                ];
            }
        }
        
        // Check for MySQL-specific patterns
        $mysql_patterns = [
            'SHOW COLUMNS' => '/SHOW\s+COLUMNS/i',
            'SHOW TABLES' => '/SHOW\s+TABLES/i',
            'CONCAT()' => '/CONCAT\s*\(/i',
            'GROUP_CONCAT()' => '/GROUP_CONCAT\s*\(/i',
            'IFNULL()' => '/IFNULL\s*\(/i',
            'NOW()' => '/NOW\s*\(\)/i',
            'Backticks' => '/`[^`]+`/',
            'LIMIT offset,count' => '/LIMIT\s+\d+\s*,\s*\d+/i'
        ];
        
        foreach ($mysql_patterns as $pattern_name => $pattern) {
            if (preg_match($pattern, $line)) {
                if (!in_array($pattern_name, $current_method['mysql_dependencies'])) {
                    $current_method['mysql_dependencies'][] = $pattern_name;
                }
            }
        }
        
        // Method ended
        if ($brace_count <= 0 && $current_method) {
            $current_method['end_line'] = $lineNum + 1;
            $current_method['content'] = implode("\n", $method_content);
            
            // Final categorization
            foreach ($critical_patterns as $pattern => $category) {
                if (strpos($current_method['name'], $pattern) !== false) {
                    $current_method['category'] = $category;
                    $current_method['priority'] = 'HIGH';
                    break;
                }
            }
            
            $methods[] = $current_method;
            $in_method = false;
            $current_method = null;
            $method_content = [];
        }
    }
}

// Filter and prioritize critical methods
$critical_methods = array_filter($methods, function($method) {
    return $method['priority'] === 'HIGH' || !empty($method['queries']) || !empty($method['mysql_dependencies']);
});

// Sort by priority and impact
usort($critical_methods, function($a, $b) {
    // Priority: HIGH first
    if ($a['priority'] !== $b['priority']) {
        return $a['priority'] === 'HIGH' ? -1 : 1;
    }
    
    // Then by query count
    $a_queries = count($a['queries']);
    $b_queries = count($b['queries']);
    if ($a_queries !== $b_queries) {
        return $b_queries - $a_queries;
    }
    
    // Then by MySQL dependencies
    return count($b['mysql_dependencies']) - count($a['mysql_dependencies']);
});

// Display results
echo "🎯 CRITICAL METHODS FOR CONVERSION\n";
echo "===================================\n\n";

$categories = [];
foreach ($critical_methods as $method) {
    $categories[$method['category']][] = $method;
}

foreach ($categories as $category => $methods_in_category) {
    echo "📂 $category\n";
    echo str_repeat('─', strlen($category) + 3) . "\n";
    
    foreach ($methods_in_category as $method) {
        $queries = count($method['queries']);
        $deps = count($method['mysql_dependencies']);
        $priority = $method['priority'] === 'HIGH' ? '🔥' : '⚠️';
        
        echo "  $priority {$method['name']}() - Lines: {$method['start_line']}-{$method['end_line']}\n";
        
        if ($queries > 0) {
            echo "    📊 $queries hardcoded queries\n";
            // Show first 2 queries as examples
            foreach (array_slice($method['queries'], 0, 2) as $query) {
                echo "      • Line {$query['line']}: {$query['query']}\n";
            }
        }
        
        if ($deps > 0) {
            echo "    🔗 MySQL dependencies: " . implode(', ', $method['mysql_dependencies']) . "\n";
        }
        
        echo "\n";
    }
    echo "\n";
}

// Conversion plan
echo "📋 CONVERSION EXECUTION PLAN\n";
echo "=============================\n\n";

echo "PHASE 1 - Core Query Building (CRITICAL):\n";
$phase1_methods = array_filter($critical_methods, function($m) {
    return $m['category'] === 'Query Building Methods';
});

foreach ($phase1_methods as $method) {
    echo "  🎯 {$method['name']}() - " . count($method['queries']) . " queries, " . count($method['mysql_dependencies']) . " MySQL deps\n";
}

echo "\nPHASE 2 - CRUD Operations:\n";
$phase2_methods = array_filter($critical_methods, function($m) {
    return in_array($m['category'], ['Create Operations', 'Update Operations', 'Delete Operations', 'Remove Operations']);
});

foreach ($phase2_methods as $method) {
    echo "  🔧 {$method['name']}() - " . count($method['queries']) . " queries, " . count($method['mysql_dependencies']) . " MySQL deps\n";
}

echo "\nPHASE 3 - Table Metadata:\n";
$phase3_methods = array_filter($critical_methods, function($m) {
    return $m['category'] === 'Table Metadata';
});

foreach ($phase3_methods as $method) {
    echo "  📋 {$method['name']}() - " . count($method['queries']) . " queries, " . count($method['mysql_dependencies']) . " MySQL deps\n";
}

echo "\nPHASE 4 - Rendering Methods:\n";
$phase4_methods = array_filter($critical_methods, function($m) {
    return in_array($m['category'], ['List Rendering', 'Detail Rendering']);
});

foreach (array_slice($phase4_methods, 0, 5) as $method) {
    echo "  🎨 {$method['name']}() - " . count($method['queries']) . " queries, " . count($method['mysql_dependencies']) . " MySQL deps\n";
}

// Immediate action recommendations
echo "\n🚀 IMMEDIATE ACTION PLAN\n";
echo "========================\n";

echo "1. START WITH: " . ($phase1_methods ? $phase1_methods[0]['name'] . "()" : "No query building methods found") . "\n";
echo "2. THEN CONVERT: " . ($phase2_methods ? $phase2_methods[0]['name'] . "()" : "No CRUD methods found") . "\n";
echo "3. UPDATE TABLE METADATA: " . ($phase3_methods ? $phase3_methods[0]['name'] . "()" : "No metadata methods found") . "\n";

echo "\n💡 CONVERSION STRATEGY:\n";
echo "1. Create QueryBuilder helper methods for common patterns\n";
echo "2. Replace \$db->query() calls with QueryBuilder methods\n";
echo "3. Handle database-specific SQL differences\n";
echo "4. Test each method after conversion\n";
echo "5. Maintain backward compatibility\n\n";

$total_critical = count($critical_methods);
$total_queries = array_sum(array_map(function($m) { return count($m['queries']); }, $critical_methods));

echo "📈 CONVERSION SCOPE:\n";
echo "• $total_critical critical methods to convert\n";
echo "• $total_queries hardcoded queries to refactor\n";
echo "• Multi-database compatibility required\n";
echo "• Full backward compatibility maintained\n\n";

echo "✨ Analysis complete! Ready to start systematic conversion.\n";
?>