<?php
/**
 * MySQL to PostgreSQL Database Converter
 * Converts MySQL SQL dumps to PostgreSQL format
 */

class MySQLToPostgreSQLConverter 
{
    private $mysql_sql;
    private $postgresql_sql = '';
    
    public function __construct($mysql_file) 
    {
        if (!file_exists($mysql_file)) {
            throw new Exception("MySQL file not found: $mysql_file");
        }
        $this->mysql_sql = file_get_contents($mysql_file);
    }
    
    public function convert() 
    {
        $lines = explode("\n", $this->mysql_sql);
        $current_statement = '';
        $in_create_table = false;
        $table_name = '';
        $columns = [];
        $primary_keys = [];
        $indexes = [];
        
        foreach ($lines as $line) {
            $line = trim($line);
            
            // Skip MySQL-specific comments and commands
            if (empty($line) || 
                strpos($line, '--') === 0 || 
                strpos($line, '/*') === 0 ||
                strpos($line, 'SET ') === 0 ||
                strpos($line, 'START TRANSACTION') === 0 ||
                strpos($line, 'COMMIT') === 0 ||
                strpos($line, 'AUTOCOMMIT') === 0) {
                continue;
            }
            
            // Handle CREATE TABLE
            if (preg_match('/^CREATE TABLE(?:\s+IF NOT EXISTS)?\s+`?([^`\s]+)`?\s*\(/i', $line, $matches)) {
                $in_create_table = true;
                $table_name = $matches[1];
                $columns = [];
                $primary_keys = [];
                $indexes = [];
                $this->postgresql_sql .= "CREATE TABLE IF NOT EXISTS \"$table_name\" (\n";
                continue;
            }
            
            // Handle table end
            if ($in_create_table && preg_match('/^\)\s*ENGINE=/i', $line)) {
                $in_create_table = false;
                
                // Add columns
                foreach ($columns as $i => $column) {
                    $this->postgresql_sql .= "    $column";
                    if ($i < count($columns) - 1 || !empty($primary_keys)) {
                        $this->postgresql_sql .= ",";
                    }
                    $this->postgresql_sql .= "\n";
                }
                
                // Add primary key constraint
                if (!empty($primary_keys)) {
                    $this->postgresql_sql .= "    PRIMARY KEY (" . implode(', ', $primary_keys) . ")\n";
                }
                
                $this->postgresql_sql .= ");\n\n";
                
                // Add indexes
                foreach ($indexes as $index) {
                    $this->postgresql_sql .= $index . "\n";
                }
                
                continue;
            }
            
            // Handle column definitions inside CREATE TABLE
            if ($in_create_table) {
                if (preg_match('/^`([^`]+)`\s+(.+?)(?:,\s*)?$/i', $line, $matches)) {
                    $column_name = $matches[1];
                    $column_def = rtrim($matches[2], ',');
                    
                    // Convert MySQL types to PostgreSQL types
                    $postgresql_column = $this->convertColumnDefinition($column_name, $column_def);
                    $columns[] = "\"$column_name\" $postgresql_column";
                } 
                // Handle PRIMARY KEY
                elseif (preg_match('/PRIMARY KEY\s*\(([^)]+)\)/i', $line, $matches)) {
                    $primary_keys = array_map(function($key) {
                        return '"' . trim($key, '`') . '"';
                    }, explode(',', $matches[1]));
                }
                // Handle KEY/INDEX
                elseif (preg_match('/(?:KEY|INDEX)\s+`?([^`\s]+)`?\s*\(([^)]+)\)/i', $line, $matches)) {
                    $index_name = $matches[1];
                    $index_columns = str_replace('`', '"', $matches[2]);
                    $indexes[] = "CREATE INDEX IF NOT EXISTS \"{$table_name}_{$index_name}\" ON \"$table_name\" ($index_columns);";
                }
                continue;
            }
            
            // Handle INSERT statements
            if (preg_match('/^INSERT\s+(?:DELAYED\s+)?INTO\s+`?([^`\s]+)`?/i', $line, $matches)) {
                // Convert MySQL INSERT to PostgreSQL INSERT
                $converted_insert = $this->convertInsertStatement($line);
                $this->postgresql_sql .= $converted_insert . "\n";
                continue;
            }
            
            // Handle DROP TABLE
            if (preg_match('/^DROP TABLE(?:\s+IF EXISTS)?\s+`?([^`\s]+)`?/i', $line)) {
                $converted_drop = str_replace('`', '"', $line);
                $this->postgresql_sql .= $converted_drop . ";\n";
                continue;
            }
        }
        
        return $this->postgresql_sql;
    }
    
    private function convertColumnDefinition($column_name, $definition) 
    {
        $definition = trim($definition);
        
        // Handle AUTO_INCREMENT -> SERIAL
        if (stripos($definition, 'AUTO_INCREMENT') !== false) {
            if (stripos($definition, 'int') !== false) {
                return 'SERIAL';
            } elseif (stripos($definition, 'bigint') !== false) {
                return 'BIGSERIAL';
            }
        }
        
        // Convert MySQL types to PostgreSQL equivalents
        $type_conversions = [
            // Integer types
            '/\btinyint\s*\(\d+\)/i' => 'SMALLINT',
            '/\btinyint\b/i' => 'SMALLINT',
            '/\bsmallint\b/i' => 'SMALLINT',
            '/\bmediumint\b/i' => 'INTEGER',
            '/\bint\s*\(\d+\)/i' => 'INTEGER',
            '/\bint\b/i' => 'INTEGER',
            '/\bbigint\b/i' => 'BIGINT',
            
            // Floating point
            '/\bfloat\s*\([^)]+\)/i' => 'REAL',
            '/\bfloat\b/i' => 'REAL',
            '/\bdouble\b/i' => 'DOUBLE PRECISION',
            '/\bdecimal\s*\(([^)]+)\)/i' => 'DECIMAL($1)',
            
            // String types
            '/\bvarchar\s*\((\d+)\)/i' => 'VARCHAR($1)',
            '/\bchar\s*\((\d+)\)/i' => 'CHAR($1)',
            '/\btext\b/i' => 'TEXT',
            '/\btinytext\b/i' => 'TEXT',
            '/\bmediumtext\b/i' => 'TEXT',
            '/\blongtext\b/i' => 'TEXT',
            
            // Binary types
            '/\bblob\b/i' => 'BYTEA',
            '/\btinyblob\b/i' => 'BYTEA',
            '/\bmediumblob\b/i' => 'BYTEA',
            '/\blongblob\b/i' => 'BYTEA',
            '/\bbinary\b/i' => 'BYTEA',
            '/\bvarbinary\b/i' => 'BYTEA',
            
            // Date/time types
            '/\bdate\b/i' => 'DATE',
            '/\bdatetime\b/i' => 'TIMESTAMP',
            '/\btimestamp\b/i' => 'TIMESTAMP',
            '/\btime\b/i' => 'TIME',
            '/\byear\b/i' => 'INTEGER',
            
            // Other types
            '/\bbit\b/i' => 'BOOLEAN',
            '/\bpoint\b/i' => 'POINT',
            '/\benum\s*\(([^)]+)\)/i' => 'TEXT CHECK ("' . $column_name . '" IN ($1))',
            '/\bset\b/i' => 'TEXT[]',
        ];
        
        foreach ($type_conversions as $pattern => $replacement) {
            $definition = preg_replace($pattern, $replacement, $definition);
        }
        
        // Handle default values
        if (preg_match('/DEFAULT\s+CURRENT_TIMESTAMP/i', $definition)) {
            $definition = str_ireplace('DEFAULT CURRENT_TIMESTAMP', 'DEFAULT CURRENT_TIMESTAMP', $definition);
        }
        
        // Remove MySQL-specific attributes
        $mysql_attributes = [
            '/\bUNSIGNED\b/i',
            '/\bZEROFILL\b/i',
            '/\bCOLLATE\s+\w+/i',
            '/\bCHARACTER\s+SET\s+\w+/i',
            '/\bENGINE\s*=\s*\w+/i',
            '/\bAUTO_INCREMENT\b/i',
        ];
        
        foreach ($mysql_attributes as $pattern) {
            $definition = preg_replace($pattern, '', $definition);
        }
        
        // Clean up extra spaces
        $definition = preg_replace('/\s+/', ' ', trim($definition));
        
        return $definition;
    }
    
    private function convertInsertStatement($statement) 
    {
        // Remove DELAYED keyword
        $statement = str_ireplace('INSERT DELAYED INTO', 'INSERT INTO', $statement);
        
        // Replace backticks with double quotes
        $statement = str_replace('`', '"', $statement);
        
        // Ensure statement ends with semicolon
        if (!preg_match('/;\s*$/', $statement)) {
            $statement .= ';';
        }
        
        return $statement;
    }
    
    public function saveToFile($output_file) 
    {
        return file_put_contents($output_file, $this->postgresql_sql);
    }
}

// Convert database_demo.sql
try {
    echo "Converting database_demo.sql to PostgreSQL...\n";
    $converter = new MySQLToPostgreSQLConverter(__DIR__ . '/../../demo_database/database_demo.sql');
    $postgresql_sql = $converter->convert();
    $converter->saveToFile(__DIR__ . '/../../demo_database/database_demo_postgresql.sql');
    echo "✅ database_demo_postgresql.sql created successfully\n\n";
} catch (Exception $e) {
    echo "❌ Error converting database_demo.sql: " . $e->getMessage() . "\n";
}

// Convert million.sql
try {
    echo "Converting million.sql to PostgreSQL...\n";
    $converter = new MySQLToPostgreSQLConverter(__DIR__ . '/../../demo_database/million.sql');
    $postgresql_sql = $converter->convert();
    $converter->saveToFile(__DIR__ . '/../../demo_database/million_postgresql.sql');
    echo "✅ million_postgresql.sql created successfully\n\n";
} catch (Exception $e) {
    echo "❌ Error converting million.sql: " . $e->getMessage() . "\n";
}

echo "PostgreSQL conversion completed!\n";
?>