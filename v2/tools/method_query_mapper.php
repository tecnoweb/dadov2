<?php
/**
 * xCrudRevolution - Method Query Mapper
 * Maps hardcoded queries to their methods for systematic conversion
 * 
 * @package    xCrudRevolution
 * @version    2.0.0
 * @copyright  Copyright (c) 2024 xCrudRevolution. All rights reserved.
 * @website    https://www.xcrudrevolution.com
 */

echo "🗺️ xCrudRevolution - Method Query Mapper\n";
echo "========================================\n\n";

$file = '../xcrud.php';
if (!file_exists($file)) {
    die("❌ xcrud.php not found!\n");
}

$content = file_get_contents($file);
$lines = explode("\n", $content);

// Find all methods and their line ranges
$methods = [];
$current_method = null;
$brace_count = 0;
$in_method = false;

foreach ($lines as $lineNum => $line) {
    $line = trim($line);
    
    // Find method definitions
    if (preg_match('/^\s*(public|protected|private)\s+(static\s+)?function\s+(\w+)\s*\(/', $line, $matches)) {
        $method_name = $matches[3];
        $current_method = [
            'name' => $method_name,
            'start_line' => $lineNum + 1,
            'visibility' => $matches[1],
            'static' => !empty($matches[2]),
            'queries' => []
        ];
        $brace_count = 0;
        $in_method = true;
    }
    
    if ($in_method) {
        // Count braces to find method end
        $brace_count += substr_count($line, '{') - substr_count($line, '}');
        
        // Check for queries in this line
        if (preg_match('/\$\w+->query\s*\(\s*["\']([^"\']+)["\']/', $line, $query_matches)) {
            $current_method['queries'][] = [
                'line' => $lineNum + 1,
                'query' => substr($query_matches[1], 0, 80) . (strlen($query_matches[1]) > 80 ? '...' : ''),
                'full_line' => $line
            ];
        }
        
        // Method ended
        if ($brace_count <= 0 && $current_method) {
            $current_method['end_line'] = $lineNum + 1;
            $methods[] = $current_method;
            $in_method = false;
            $current_method = null;
        }
    }
}

// Filter methods that have queries and sort by priority
$methods_with_queries = array_filter($methods, function($method) {
    return !empty($method['queries']);
});

usort($methods_with_queries, function($a, $b) {
    return count($b['queries']) - count($a['queries']);
});

// Display results
echo "📊 METHODS WITH HARDCODED QUERIES\n";
echo "==================================\n\n";

$total_methods = count($methods_with_queries);
$total_queries = array_sum(array_map(function($m) { return count($m['queries']); }, $methods_with_queries));

echo "Found $total_methods methods with $total_queries hardcoded queries\n\n";

// Priority categories
$critical_methods = [];
$important_methods = [];
$normal_methods = [];

foreach ($methods_with_queries as $method) {
    $query_count = count($method['queries']);
    
    if ($query_count >= 10) {
        $critical_methods[] = $method;
    } elseif ($query_count >= 3) {
        $important_methods[] = $method;
    } else {
        $normal_methods[] = $method;
    }
}

// Display Critical Methods (10+ queries)
if (!empty($critical_methods)) {
    echo "🔥 CRITICAL METHODS (10+ queries)\n";
    echo "=================================\n";
    foreach ($critical_methods as $method) {
        echo "  🎯 {$method['name']}() - " . count($method['queries']) . " queries\n";
        echo "     Lines: {$method['start_line']}-{$method['end_line']}\n";
        echo "     Sample queries:\n";
        foreach (array_slice($method['queries'], 0, 3) as $query) {
            echo "       • Line {$query['line']}: {$query['query']}\n";
        }
        echo "\n";
    }
    echo "\n";
}

// Display Important Methods (3-9 queries)
if (!empty($important_methods)) {
    echo "⚠️ IMPORTANT METHODS (3-9 queries)\n";
    echo "==================================\n";
    foreach ($important_methods as $method) {
        echo "  🔧 {$method['name']}() - " . count($method['queries']) . " queries\n";
        echo "     Lines: {$method['start_line']}-{$method['end_line']}\n";
    }
    echo "\n";
}

// Create conversion plan
echo "📋 CONVERSION PLAN\n";
echo "==================\n\n";

echo "PHASE 1 - Core Database Operations:\n";
$phase1_methods = ['_get_table_info', '_create', '_update', '_remove', '_clone_row'];
foreach ($phase1_methods as $method_name) {
    $method = array_filter($methods_with_queries, function($m) use ($method_name) {
        return $m['name'] === $method_name;
    });
    if (!empty($method)) {
        $method = array_values($method)[0];
        echo "  ✅ {$method['name']}() - " . count($method['queries']) . " queries\n";
    }
}

echo "\nPHASE 2 - Query Building:\n";
$phase2_methods = ['_build_select_list', '_build_select_details', '_build_where', '_build_order_by', '_build_limit', '_build_table_join'];
foreach ($phase2_methods as $method_name) {
    $method = array_filter($methods_with_queries, function($m) use ($method_name) {
        return $m['name'] === $method_name;
    });
    if (!empty($method)) {
        $method = array_values($method)[0];
        echo "  🔧 {$method['name']}() - " . count($method['queries']) . " queries\n";
    }
}

echo "\nPHASE 3 - Rendering:\n";
$phase3_methods = array_filter($methods_with_queries, function($m) {
    return strpos($m['name'], 'render_') === 0 || strpos($m['name'], '_render') !== false;
});
foreach (array_slice($phase3_methods, 0, 5) as $method) {
    echo "  🎨 {$method['name']}() - " . count($method['queries']) . " queries\n";
}

echo "\nPHASE 4 - Relations:\n";
$phase4_methods = array_filter($methods_with_queries, function($m) {
    return strpos($m['name'], 'relation') !== false || strpos($m['name'], '_build_rel') !== false;
});
foreach ($phase4_methods as $method) {
    echo "  🔗 {$method['name']}() - " . count($method['queries']) . " queries\n";
}

echo "\n💡 RECOMMENDATIONS:\n";
echo "===================\n";
echo "1. Start with CRITICAL methods (most impact)\n";
echo "2. Focus on _build_* methods first (core query generation)\n";
echo "3. Convert database operations (_create, _update, _remove)\n";
echo "4. Update rendering methods last (mostly SELECT queries)\n";
echo "5. Create helper methods for common query patterns\n\n";

echo "🎯 TOP 10 METHODS TO CONVERT FIRST:\n";
echo "===================================\n";
foreach (array_slice($methods_with_queries, 0, 10) as $i => $method) {
    $priority = $i + 1;
    echo "  $priority. {$method['name']}() - " . count($method['queries']) . " queries (Lines: {$method['start_line']}-{$method['end_line']})\n";
}

echo "\n✨ Analysis complete! Ready for systematic conversion.\n";
?>