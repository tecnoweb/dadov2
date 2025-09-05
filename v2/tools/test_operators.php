<?php
/**
 * Test script for database operator compatibility
 * Tests all SQL operators across different databases
 */

// Include xcrud
require_once '../xcrud.php';

// Test configuration for different databases
$test_configs = [
    'mysql' => [
        'host' => 'localhost',
        'user' => 'root',
        'pass' => '',
        'db' => 'test_xcrud',
        'type' => 'mysql'
    ],
    'postgresql' => [
        'host' => 'localhost',
        'user' => 'postgres',
        'pass' => 'postgres',
        'db' => 'test_xcrud',
        'type' => 'postgresql'
    ],
    'sqlite' => [
        'host' => '',
        'user' => '',
        'pass' => '',
        'db' => 'test_xcrud.db',
        'type' => 'sqlite'
    ]
];

// Test cases for operators
$test_cases = [
    // Basic operators - should work everywhere
    [
        'name' => 'Basic Equality',
        'operator' => '=',
        'test' => function($xcrud) {
            $xcrud->where('status =', 'active');
            return true;
        },
        'supported' => ['mysql', 'postgresql', 'sqlite']
    ],
    [
        'name' => 'Not Equal',
        'operator' => '!=',
        'test' => function($xcrud) {
            $xcrud->where('status !=', 'deleted');
            return true;
        },
        'supported' => ['mysql', 'postgresql', 'sqlite']
    ],
    [
        'name' => 'Greater Than',
        'operator' => '>',
        'test' => function($xcrud) {
            $xcrud->where('age >', 18);
            return true;
        },
        'supported' => ['mysql', 'postgresql', 'sqlite']
    ],
    
    // Set operators - should work everywhere
    [
        'name' => 'IN Operator',
        'operator' => 'IN',
        'test' => function($xcrud) {
            $xcrud->where('category IN', ['A', 'B', 'C']);
            return true;
        },
        'supported' => ['mysql', 'postgresql', 'sqlite']
    ],
    [
        'name' => 'NOT IN Operator',
        'operator' => 'NOT IN',
        'test' => function($xcrud) {
            $xcrud->where('status NOT IN', ['deleted', 'archived']);
            return true;
        },
        'supported' => ['mysql', 'postgresql', 'sqlite']
    ],
    
    // Range operators - should work everywhere
    [
        'name' => 'BETWEEN',
        'operator' => 'BETWEEN',
        'test' => function($xcrud) {
            $xcrud->where('age BETWEEN', [18, 65]);
            return true;
        },
        'supported' => ['mysql', 'postgresql', 'sqlite']
    ],
    [
        'name' => 'NOT BETWEEN',
        'operator' => 'NOT BETWEEN',
        'test' => function($xcrud) {
            $xcrud->where('price NOT BETWEEN', [10, 100]);
            return true;
        },
        'supported' => ['mysql', 'postgresql', 'sqlite']
    ],
    
    // NULL operators - should work everywhere
    [
        'name' => 'IS NULL',
        'operator' => 'IS NULL',
        'test' => function($xcrud) {
            $xcrud->where('deleted_at IS NULL', '');
            return true;
        },
        'supported' => ['mysql', 'postgresql', 'sqlite']
    ],
    [
        'name' => 'IS NOT NULL',
        'operator' => 'IS NOT NULL',
        'test' => function($xcrud) {
            $xcrud->where('email IS NOT NULL', '');
            return true;
        },
        'supported' => ['mysql', 'postgresql', 'sqlite']
    ],
    
    // Pattern matching - universal
    [
        'name' => 'LIKE',
        'operator' => 'LIKE',
        'test' => function($xcrud) {
            $xcrud->where('name LIKE', '%John%');
            return true;
        },
        'supported' => ['mysql', 'postgresql', 'sqlite']
    ],
    [
        'name' => 'NOT LIKE',
        'operator' => 'NOT LIKE',
        'test' => function($xcrud) {
            $xcrud->where('email NOT LIKE', '%@temp%');
            return true;
        },
        'supported' => ['mysql', 'postgresql', 'sqlite']
    ],
    
    // ILIKE - PostgreSQL only (emulated elsewhere)
    [
        'name' => 'ILIKE (case-insensitive)',
        'operator' => 'ILIKE',
        'test' => function($xcrud) {
            $xcrud->where('name ILIKE', '%john%');
            return true;
        },
        'supported' => ['postgresql'],
        'emulated' => ['mysql', 'sqlite']
    ],
    [
        'name' => 'NOT ILIKE',
        'operator' => 'NOT ILIKE',
        'test' => function($xcrud) {
            $xcrud->where('name NOT ILIKE', 'admin%');
            return true;
        },
        'supported' => ['postgresql'],
        'emulated' => ['mysql', 'sqlite']
    ],
    
    // Regular expressions - database specific
    [
        'name' => 'REGEXP (MySQL)',
        'operator' => 'REGEXP',
        'test' => function($xcrud) {
            $xcrud->where('phone REGEXP', '^[0-9]{10}$');
            return true;
        },
        'supported' => ['mysql'],
        'converted' => ['postgresql'], // Converts to ~
        'fallback' => ['sqlite'] // Falls back to LIKE
    ],
    [
        'name' => 'PostgreSQL ~ operator',
        'operator' => '~',
        'test' => function($xcrud) {
            $xcrud->where('code ~', '^[A-Z]{3}');
            return true;
        },
        'supported' => ['postgresql'],
        'converted' => ['mysql'] // Converts to REGEXP
    ],
    [
        'name' => 'PostgreSQL ~* (case-insensitive regex)',
        'operator' => '~*',
        'test' => function($xcrud) {
            $xcrud->where('name ~*', 'john');
            return true;
        },
        'supported' => ['postgresql'],
        'emulated' => ['mysql'] // Uses LOWER() with REGEXP
    ],
    [
        'name' => 'PostgreSQL !~ (negative regex)',
        'operator' => '!~',
        'test' => function($xcrud) {
            $xcrud->where('code !~', '[a-z]');
            return true;
        },
        'supported' => ['postgresql'],
        'converted' => ['mysql'] // Converts to NOT REGEXP
    ],
    
    // SIMILAR TO - PostgreSQL only
    [
        'name' => 'SIMILAR TO (PostgreSQL)',
        'operator' => 'SIMILAR TO',
        'test' => function($xcrud) {
            $xcrud->where('code SIMILAR TO', '[A-Z]{3}-[0-9]{4}');
            return true;
        },
        'supported' => ['postgresql'],
        'fallback' => ['mysql', 'sqlite'] // Falls back to LIKE
    ],
    
    // EXISTS - should work everywhere
    [
        'name' => 'EXISTS',
        'operator' => 'EXISTS',
        'test' => function($xcrud) {
            $xcrud->where('EXISTS', 'SELECT 1 FROM orders WHERE orders.user_id = users.id');
            return true;
        },
        'supported' => ['mysql', 'postgresql', 'sqlite']
    ],
    [
        'name' => 'NOT EXISTS',
        'operator' => 'NOT EXISTS',
        'test' => function($xcrud) {
            $xcrud->where('NOT EXISTS', 'SELECT 1 FROM payments WHERE payments.order_id = orders.id');
            return true;
        },
        'supported' => ['mysql', 'postgresql', 'sqlite']
    ]
];

// Function to test operator
function test_operator($db_type, $test_case) {
    try {
        $xcrud = Xcrud::get_instance();
        $xcrud->table('test_table');
        
        // Run the test
        $result = $test_case['test']($xcrud);
        
        // Check if operator is supported
        $is_supported = in_array($db_type, $test_case['supported'] ?? []);
        $is_emulated = in_array($db_type, $test_case['emulated'] ?? []);
        $is_converted = in_array($db_type, $test_case['converted'] ?? []);
        $has_fallback = in_array($db_type, $test_case['fallback'] ?? []);
        
        if ($is_supported) {
            return ['status' => 'NATIVE', 'message' => 'Native support'];
        } elseif ($is_emulated) {
            return ['status' => 'EMULATED', 'message' => 'Emulated with workaround'];
        } elseif ($is_converted) {
            return ['status' => 'CONVERTED', 'message' => 'Converted to equivalent operator'];
        } elseif ($has_fallback) {
            return ['status' => 'FALLBACK', 'message' => 'Fallback to simpler operator'];
        } else {
            return ['status' => 'UNSUPPORTED', 'message' => 'Not supported'];
        }
        
    } catch (Exception $e) {
        return ['status' => 'ERROR', 'message' => $e->getMessage()];
    }
}

// Output test results
echo "xCrudRevolution Operator Compatibility Test\n";
echo "=" . str_repeat("=", 80) . "\n\n";

// Test each database
foreach ($test_configs as $db_name => $config) {
    echo "Testing Database: " . strtoupper($db_name) . "\n";
    echo str_repeat("-", 80) . "\n";
    
    // Table header
    printf("%-30s | %-15s | %-12s | %s\n", "Operator", "Test Name", "Status", "Notes");
    echo str_repeat("-", 80) . "\n";
    
    foreach ($test_cases as $test) {
        $result = test_operator($db_name, $test);
        
        // Color coding for terminal output
        $status_color = '';
        switch($result['status']) {
            case 'NATIVE': $status_color = "\033[32m"; break; // Green
            case 'EMULATED': 
            case 'CONVERTED': $status_color = "\033[33m"; break; // Yellow
            case 'FALLBACK': $status_color = "\033[35m"; break; // Magenta
            case 'UNSUPPORTED': $status_color = "\033[31m"; break; // Red
            case 'ERROR': $status_color = "\033[91m"; break; // Bright Red
        }
        $reset_color = "\033[0m";
        
        printf("%-30s | %-15s | %s%-12s%s | %s\n", 
            $test['operator'],
            substr($test['name'], 0, 15),
            $status_color,
            $result['status'],
            $reset_color,
            $result['message']
        );
    }
    
    echo "\n";
}

// Legend
echo "\nLegend:\n";
echo "-------\n";
echo "\033[32mNATIVE\033[0m     - Native database support\n";
echo "\033[33mEMULATED\033[0m   - Emulated using alternative syntax\n";
echo "\033[33mCONVERTED\033[0m  - Converted to equivalent operator\n";
echo "\033[35mFALLBACK\033[0m   - Falls back to simpler operator\n";
echo "\033[31mUNSUPPORTED\033[0m - Not supported by database\n";
echo "\033[91mERROR\033[0m      - Error occurred during test\n";