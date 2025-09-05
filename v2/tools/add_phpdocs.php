<?php
/**
 * Tool to add PHPDoc comments to all public and protected methods in xcrud.php
 */

$file = __DIR__ . '/../xcrud.php';
$content = file_get_contents($file);

// PHPDoc templates for known methods
$phpdocs = [
    '__toString' => [
        'description' => 'Magic method to convert the Xcrud instance to string (renders the output)',
        'return' => '@return string The rendered HTML output'
    ],
    'connection' => [
        'description' => 'Set custom database connection parameters',
        'params' => [
            '@param string $user Database username',
            '@param string $pass Database password', 
            '@param string $table Database name',
            '@param string $host Database host (default: localhost)',
            '@param string $encode Character encoding (default: utf8)'
        ],
        'return' => '@return $this Method chaining'
    ],
    'start_minimized' => [
        'description' => 'Set whether the table should start in minimized state',
        'params' => ['@param bool $bool True to start minimized, false otherwise'],
        'return' => '@return $this Method chaining'
    ],
    'remove_confirm' => [
        'description' => 'Enable/disable confirmation dialog for delete operations',
        'params' => ['@param bool $bool True to show confirmation, false to skip'],
        'return' => '@return $this Method chaining'
    ],
    'table' => [
        'description' => 'Set the main database table for CRUD operations',
        'params' => [
            '@param string $name Table name',
            '@param string $prefix Optional table prefix (default: false)'
        ],
        'return' => '@return $this Method chaining'
    ],
    'primary' => [
        'description' => 'Set the primary key field for the table',
        'params' => [
            '@param string $field Primary key field name',
            '@param bool $auto_increment Whether the field is auto-increment (default: true)'
        ],
        'return' => '@return $this Method chaining'
    ],
    'relation' => [
        'description' => 'Create a relation between tables (1:N relationship)',
        'params' => [
            '@param string $field Field name in main table',
            '@param string $rel_tbl Related table name',
            '@param string $rel_field Related field name',
            '@param string|array $rel_name Field(s) to display from related table',
            '@param string $rel_where WHERE condition for relation (optional)',
            '@param bool $multi Multiple selection (default: false)',
            '@param string $rel_separator Separator for multiple fields (default: " ")',
            '@param string $rel_tree Tree field for hierarchical data (optional)',
            '@param string $rel_concat_separator Concat separator (optional)'
        ],
        'return' => '@return $this Method chaining'
    ],
    'fk_relation' => [
        'description' => 'Create a many-to-many relationship using a junction table',
        'params' => [
            '@param string $label Display label for the relation',
            '@param string $field Field in main table',
            '@param string $fk_table Junction/pivot table name',
            '@param string $in_fk_field Field in junction table linking to main table',
            '@param string $out_fk_field Field in junction table linking to related table',
            '@param string $rel_tbl Related table name',
            '@param string $rel_field Related table field',
            '@param string|array $rel_name Field(s) to display from related table',
            '@param string $rel_where WHERE condition (optional)',
            '@param string $rel_orderby ORDER BY clause (optional)',
            '@param bool $multi Allow multiple selection (default: false)',
            '@param string $rel_separator Separator for multiple fields (default: " ")',
            '@param bool $add_data Allow adding new related records (default: true)'
        ],
        'return' => '@return $this Method chaining'
    ],
    'where' => [
        'description' => 'Add WHERE condition to filter records',
        'params' => [
            '@param string $field Field name or custom SQL condition',
            '@param string|array $value Value(s) to compare',
            '@param string $glue SQL operator (AND/OR, default: AND)',
            '@param string $index Optional index for named conditions'
        ],
        'return' => '@return $this Method chaining'
    ],
    'or_where' => [
        'description' => 'Add OR WHERE condition to filter records',
        'params' => [
            '@param string $field Field name or custom SQL condition',
            '@param string|array $value Value(s) to compare',
            '@param string $index Optional index for named conditions'
        ],
        'return' => '@return $this Method chaining'
    ],
    'order_by' => [
        'description' => 'Set the default ORDER BY clause',
        'params' => [
            '@param string|array $field Field name(s) to order by',
            '@param string $desc Direction (ASC/DESC, default: ASC)'
        ],
        'return' => '@return $this Method chaining'
    ],
    'limit' => [
        'description' => 'Set the number of records per page',
        'params' => ['@param int $limit Number of records per page'],
        'return' => '@return $this Method chaining'
    ],
    'limit_list' => [
        'description' => 'Set available options for records per page selector',
        'params' => ['@param array|string $array Array of limit options or comma-separated string'],
        'return' => '@return $this Method chaining'
    ],
    'columns' => [
        'description' => 'Define which columns to display in the grid view',
        'params' => [
            '@param string|array $columns Column names (comma-separated string or array)',
            '@param bool $reverse Reverse selection (exclude specified columns)',
            '@param string $table Table name for multi-table queries (optional)',
            '@param string $mode Display mode (optional)'
        ],
        'return' => '@return $this Method chaining'
    ],
    'fields' => [
        'description' => 'Define which fields to display in create/edit forms',
        'params' => [
            '@param string|array $fields Field names (comma-separated string or array)',
            '@param bool $reverse Reverse selection (exclude specified fields)',
            '@param string $table Table name for multi-table queries (optional)',
            '@param string $mode Display mode (create/edit/view, optional)'
        ],
        'return' => '@return $this Method chaining'
    ],
    'label' => [
        'description' => 'Set custom labels for fields/columns',
        'params' => [
            '@param string|array $field Field name(s)',
            '@param string $label Custom label text'
        ],
        'return' => '@return $this Method chaining'
    ],
    'show_primary_column' => [
        'description' => 'Show or hide the primary key column in grid view',
        'params' => ['@param bool $bool True to show, false to hide'],
        'return' => '@return $this Method chaining'
    ],
    'theme' => [
        'description' => 'Set the UI theme for the grid',
        'params' => ['@param string $name Theme name (bootstrap, bootstrap4, etc.)'],
        'return' => '@return $this Method chaining'
    ],
    'language' => [
        'description' => 'Set the interface language',
        'params' => ['@param string $lang Language code (en, it, de, etc.)'],
        'return' => '@return $this Method chaining'
    ],
    'unset_add' => [
        'description' => 'Remove/disable the Add button',
        'params' => ['@param bool $bool True to hide Add button'],
        'return' => '@return $this Method chaining'
    ],
    'unset_edit' => [
        'description' => 'Remove/disable the Edit button',
        'params' => [
            '@param bool $bool True to hide Edit button',
            '@param string $field Field for conditional hiding (optional)',
            '@param string $operator Comparison operator (optional)',
            '@param mixed $value Value to compare (optional)'
        ],
        'return' => '@return $this Method chaining'
    ],
    'unset_view' => [
        'description' => 'Remove/disable the View button',
        'params' => [
            '@param bool $bool True to hide View button',
            '@param string $field Field for conditional hiding (optional)',
            '@param string $operator Comparison operator (optional)',
            '@param mixed $value Value to compare (optional)'
        ],
        'return' => '@return $this Method chaining'
    ],
    'unset_remove' => [
        'description' => 'Remove/disable the Delete button',
        'params' => [
            '@param bool $bool True to hide Delete button',
            '@param string $field Field for conditional hiding (optional)',
            '@param string $operator Comparison operator (optional)',
            '@param mixed $value Value to compare (optional)'
        ],
        'return' => '@return $this Method chaining'
    ],
    'unset_csv' => [
        'description' => 'Remove/disable CSV export functionality',
        'params' => ['@param bool $bool True to hide CSV export'],
        'return' => '@return $this Method chaining'
    ],
    'unset_print' => [
        'description' => 'Remove/disable print functionality',
        'params' => ['@param bool $bool True to hide print button'],
        'return' => '@return $this Method chaining'
    ],
    'unset_search' => [
        'description' => 'Remove/disable search functionality',
        'params' => ['@param bool $bool True to hide search'],
        'return' => '@return $this Method chaining'
    ],
    'unset_pagination' => [
        'description' => 'Remove/disable pagination controls',
        'params' => ['@param bool $bool True to hide pagination'],
        'return' => '@return $this Method chaining'
    ],
    'unset_sortable' => [
        'description' => 'Remove/disable column sorting functionality',
        'params' => ['@param bool $bool True to disable sorting'],
        'return' => '@return $this Method chaining'
    ],
    'unset_title' => [
        'description' => 'Remove/disable the table title',
        'params' => ['@param bool $bool True to hide title'],
        'return' => '@return $this Method chaining'
    ],
    'unset_numbers' => [
        'description' => 'Remove/disable row numbers',
        'params' => ['@param bool $bool True to hide row numbers'],
        'return' => '@return $this Method chaining'
    ],
    'unset_limitlist' => [
        'description' => 'Remove/disable the records per page selector',
        'params' => ['@param bool $bool True to hide limit selector'],
        'return' => '@return $this Method chaining'
    ],
    'duplicate_button' => [
        'description' => 'Enable/disable the duplicate record button',
        'params' => [
            '@param bool $bool True to show duplicate button',
            '@param string $field Field for conditional display (optional)',
            '@param string $operator Comparison operator (optional)',
            '@param mixed $value Value to compare (optional)'
        ],
        'return' => '@return $this Method chaining'
    ],
    'render' => [
        'description' => 'Main render method - generates the complete CRUD interface HTML',
        'params' => [
            '@param string $name Render mode (optional)',
            '@param string $task Specific task (optional)'
        ],
        'return' => '@return string Generated HTML output'
    ],
    'nested_table' => [
        'description' => 'Create a nested/child table within a parent record',
        'params' => [
            '@param string $instance_name Unique instance name for nested table',
            '@param string $field Field name in parent table',
            '@param string $nested_table Table name for nested data',
            '@param string $nested_field Foreign key field in nested table'
        ],
        'return' => '@return Xcrud New Xcrud instance for the nested table'
    ],
    'mass_buttons' => [
        'description' => 'Enable/disable mass action buttons',
        'params' => ['@param bool $bool True to show mass buttons'],
        'return' => '@return $this Method chaining'
    ],
    'sum' => [
        'description' => 'Calculate and display sum of a numeric column',
        'params' => [
            '@param string|array $field Field name(s) to sum',
            '@param string $class CSS class for sum display',
            '@param string $prefix Text/HTML prefix for sum'
        ],
        'return' => '@return $this Method chaining'
    ],
    'button' => [
        'description' => 'Add a custom button to the interface',
        'params' => [
            '@param string $link Button URL or JavaScript',
            '@param string $name Button label',
            '@param string $icon Icon class',
            '@param string $class CSS class',
            '@param array $params Additional parameters',
            '@param string $condition Condition for display',
            '@param string $field Condition field',
            '@param string $operator Condition operator',
            '@param mixed $value Condition value'
        ],
        'return' => '@return $this Method chaining'
    ],
    'highlight' => [
        'description' => 'Highlight table cells based on conditions',
        'params' => [
            '@param string $field Field to check',
            '@param string $operator Comparison operator',
            '@param mixed $value Value to compare',
            '@param string $color Background color',
            '@param string $class CSS class (optional)'
        ],
        'return' => '@return $this Method chaining'
    ],
    'highlight_row' => [
        'description' => 'Highlight entire table rows based on conditions',
        'params' => [
            '@param string $field Field to check',
            '@param string $operator Comparison operator',
            '@param mixed $value Value to compare',
            '@param string $color Background color',
            '@param string $class CSS class (optional)'
        ],
        'return' => '@return $this Method chaining'
    ],
    'column_class' => [
        'description' => 'Add CSS class to specific columns',
        'params' => [
            '@param string|array $columns Column name(s)',
            '@param string $class CSS class name'
        ],
        'return' => '@return $this Method chaining'
    ],
    'column_width' => [
        'description' => 'Set width for specific columns',
        'params' => [
            '@param string|array $columns Column name(s)',
            '@param string $width Width value (px, %, etc.)'
        ],
        'return' => '@return $this Method chaining'
    ],
    'column_pattern' => [
        'description' => 'Apply a pattern/template to column values',
        'params' => [
            '@param string $field Column name',
            '@param string $pattern Pattern with {value} placeholder'
        ],
        'return' => '@return $this Method chaining'
    ],
    'field_tooltip' => [
        'description' => 'Add tooltip help text to form fields',
        'params' => [
            '@param string|array $field Field name(s)',
            '@param string $tooltip Tooltip text',
            '@param string $icon Icon class (optional)'
        ],
        'return' => '@return $this Method chaining'
    ],
    'pass_var' => [
        'description' => 'Pass a hidden variable through forms',
        'params' => [
            '@param string $field Field name',
            '@param mixed $value Value to pass',
            '@param string $mode Mode (create/edit)',
            '@param bool $eval Evaluate as PHP code (optional)'
        ],
        'return' => '@return $this Method chaining'
    ],
    'before_insert' => [
        'description' => 'Set callback to execute before INSERT operations',
        'params' => [
            '@param callable $callback Callback function',
            '@param string $path File path containing callback (optional)'
        ],
        'return' => '@return $this Method chaining'
    ],
    'after_insert' => [
        'description' => 'Set callback to execute after INSERT operations',
        'params' => [
            '@param callable $callback Callback function',
            '@param string $path File path containing callback (optional)'
        ],
        'return' => '@return $this Method chaining'
    ],
    'before_update' => [
        'description' => 'Set callback to execute before UPDATE operations',
        'params' => [
            '@param callable $callback Callback function',
            '@param string $path File path containing callback (optional)'
        ],
        'return' => '@return $this Method chaining'
    ],
    'after_update' => [
        'description' => 'Set callback to execute after UPDATE operations',
        'params' => [
            '@param callable $callback Callback function',
            '@param string $path File path containing callback (optional)'
        ],
        'return' => '@return $this Method chaining'
    ],
    'before_remove' => [
        'description' => 'Set callback to execute before DELETE operations',
        'params' => [
            '@param callable $callback Callback function',
            '@param string $path File path containing callback (optional)'
        ],
        'return' => '@return $this Method chaining'
    ],
    'after_remove' => [
        'description' => 'Set callback to execute after DELETE operations',
        'params' => [
            '@param callable $callback Callback function',
            '@param string $path File path containing callback (optional)'
        ],
        'return' => '@return $this Method chaining'
    ]
];

// Read the current file
$lines = file($file);
$output = [];
$in_comment = false;

foreach ($lines as $i => $line) {
    // Check if this is a function definition without PHPDoc
    if (preg_match('/^\s*(public|protected)\s+function\s+([a-zA-Z_][a-zA-Z0-9_]*)\s*\(/', $line, $matches)) {
        $visibility = $matches[1];
        $function_name = $matches[2];
        
        // Check if previous lines contain PHPDoc
        $has_phpdoc = false;
        for ($j = $i - 1; $j >= 0 && $j > $i - 5; $j--) {
            if (isset($lines[$j]) && (strpos($lines[$j], '*/') !== false || strpos($lines[$j], '/**') !== false)) {
                $has_phpdoc = true;
                break;
            }
            if (isset($lines[$j]) && trim($lines[$j]) !== '' && strpos($lines[$j], '*') !== 0) {
                break;
            }
        }
        
        // If no PHPDoc and we have a template, add it
        if (!$has_phpdoc && isset($phpdocs[$function_name])) {
            $indent = str_repeat(' ', strspn($line, ' '));
            $doc = $phpdocs[$function_name];
            
            $output[] = $indent . "/**\n";
            $output[] = $indent . " * " . $doc['description'] . "\n";
            $output[] = $indent . " * \n";
            
            if (isset($doc['params'])) {
                foreach ($doc['params'] as $param) {
                    $output[] = $indent . " * " . $param . "\n";
                }
            }
            
            if (isset($doc['return'])) {
                $output[] = $indent . " * " . $doc['return'] . "\n";
            }
            
            $output[] = $indent . " */\n";
        }
    }
    
    $output[] = $line;
}

// Write the updated content
file_put_contents($file . '.documented', implode('', $output));

echo "PHPDoc comments added successfully!\n";
echo "Review the changes in xcrud.php.documented\n";
echo "If satisfied, rename it to xcrud.php\n";