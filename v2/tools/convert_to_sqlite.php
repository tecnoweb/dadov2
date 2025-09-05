<?php
/**
 * MySQL to SQLite Database Converter
 * Converts MySQL SQL dumps to SQLite format
 */

class MySQLToSQLiteConverter 
{
    private $mysql_sql;
    private $sqlite_sql = '';
    
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
        $keys = [];
        
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
                $keys = [];
                $this->sqlite_sql .= "CREATE TABLE IF NOT EXISTS `$table_name` (\n";
                continue;
            }
            
            // Handle table end
            if ($in_create_table && preg_match('/^\)\s*ENGINE=/i', $line)) {
                $in_create_table = false;
                
                // Add columns
                foreach ($columns as $i => $column) {
                    $this->sqlite_sql .= "    $column";
                    if ($i < count($columns) - 1 || !empty($primary_keys)) {
                        $this->sqlite_sql .= ",";
                    }
                    $this->sqlite_sql .= "\n";
                }
                
                // Add primary key constraint
                if (!empty($primary_keys)) {
                    $this->sqlite_sql .= "    PRIMARY KEY (" . implode(', ', $primary_keys) . ")\n";
                }
                
                $this->sqlite_sql .= ");\n\n";
                
                // Add indexes
                foreach ($keys as $key) {
                    $this->sqlite_sql .= $key . "\n";
                }
                
                continue;
            }
            
            // Handle column definitions inside CREATE TABLE
            if ($in_create_table) {
                if (preg_match('/^`([^`]+)`\s+(.+?)(?:,\s*)?$/i', $line, $matches)) {
                    $column_name = $matches[1];
                    $column_def = rtrim($matches[2], ',');
                    
                    // Convert MySQL types to SQLite types
                    $sqlite_column = $this->convertColumnDefinition($column_name, $column_def);
                    $columns[] = "`$column_name` $sqlite_column";
                } 
                // Handle PRIMARY KEY
                elseif (preg_match('/PRIMARY KEY\s*\(([^)]+)\)/i', $line, $matches)) {
                    $primary_keys = array_map('trim', explode(',', str_replace('`', '', $matches[1])));
                }
                // Handle KEY/INDEX
                elseif (preg_match('/(?:KEY|INDEX)\s+`?([^`\s]+)`?\s*\(([^)]+)\)/i', $line, $matches)) {
                    $index_name = $matches[1];
                    $index_columns = str_replace('`', '', $matches[2]);
                    $keys[] = "CREATE INDEX IF NOT EXISTS `{$table_name}_{$index_name}` ON `$table_name` ($index_columns);";
                }
                continue;
            }
            
            // Handle INSERT statements
            if (preg_match('/^INSERT\s+(?:DELAYED\s+)?INTO\s+`?([^`\s]+)`?/i', $line, $matches)) {
                // Convert MySQL INSERT to SQLite INSERT
                $converted_insert = $this->convertInsertStatement($line);
                $this->sqlite_sql .= $converted_insert . "\n";
                continue;
            }
            
            // Handle DROP TABLE
            if (preg_match('/^DROP TABLE(?:\s+IF EXISTS)?\s+`?([^`\s]+)`?/i', $line)) {
                $this->sqlite_sql .= $line . ";\n";
                continue;
            }
        }
        
        return $this->sqlite_sql;
    }
    
    private function convertColumnDefinition($column_name, $definition) 
    {
        $definition = trim($definition);
        
        // Handle AUTO_INCREMENT
        if (stripos($definition, 'AUTO_INCREMENT') !== false) {
            $definition = str_ireplace('AUTO_INCREMENT', 'AUTOINCREMENT', $definition);
        }
        
        // Convert MySQL types to SQLite equivalents
        $type_conversions = [
            // Integer types
            '/\btinyint\b/i' => 'INTEGER',
            '/\bsmallint\b/i' => 'INTEGER',
            '/\bmediumint\b/i' => 'INTEGER',
            '/\bint\b/i' => 'INTEGER',
            '/\bbigint\b/i' => 'INTEGER',
            
            // Floating point
            '/\bfloat\b/i' => 'REAL',
            '/\bdouble\b/i' => 'REAL',
            '/\bdecimal\b/i' => 'REAL',
            
            // String types
            '/\bvarchar\b/i' => 'TEXT',
            '/\bchar\b/i' => 'TEXT',
            '/\btext\b/i' => 'TEXT',
            '/\btinytext\b/i' => 'TEXT',
            '/\bmediumtext\b/i' => 'TEXT',
            '/\blongtext\b/i' => 'TEXT',
            
            // Binary types
            '/\bblob\b/i' => 'BLOB',
            '/\btinyblob\b/i' => 'BLOB',
            '/\bmediumblob\b/i' => 'BLOB',
            '/\blongblob\b/i' => 'BLOB',
            '/\bbinary\b/i' => 'BLOB',
            '/\bvarbinary\b/i' => 'BLOB',
            
            // Date/time types
            '/\bdate\b/i' => 'TEXT',
            '/\bdatetime\b/i' => 'TEXT',
            '/\btimestamp\b/i' => 'TEXT',
            '/\btime\b/i' => 'TEXT',
            '/\byear\b/i' => 'INTEGER',
            
            // Other types
            '/\bbit\b/i' => 'INTEGER',
            '/\bpoint\b/i' => 'TEXT', // Store as text in SQLite
            '/\benum\b/i' => 'TEXT',
            '/\bset\b/i' => 'TEXT',
        ];
        
        foreach ($type_conversions as $pattern => $replacement) {
            $definition = preg_replace($pattern, $replacement, $definition);
        }
        
        // Remove MySQL-specific attributes
        $mysql_attributes = [
            '/\bUNSIGNED\b/i',
            '/\bZEROFILL\b/i',
            '/\bCOLLATE\s+\w+/i',
            '/\bCHARACTER\s+SET\s+\w+/i',
            '/\bENGINE\s*=\s*\w+/i',
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
        
        // Ensure statement ends with semicolon
        if (!preg_match('/;\s*$/', $statement)) {
            $statement .= ';';
        }
        
        return $statement;
    }
    
    public function saveToFile($output_file) 
    {
        return file_put_contents($output_file, $this->sqlite_sql);
    }
}

// Convert database_demo.sql
try {
    echo "Converting database_demo.sql to SQLite...\n";
    $converter = new MySQLToSQLiteConverter(__DIR__ . '/../../demo_database/database_demo.sql');
    $sqlite_sql = $converter->convert();
    $converter->saveToFile(__DIR__ . '/../../demo_database/database_demo_sqlite.sql');
    echo "✅ database_demo_sqlite.sql created successfully\n\n";
} catch (Exception $e) {
    echo "❌ Error converting database_demo.sql: " . $e->getMessage() . "\n";
}

// Convert million.sql
try {
    echo "Converting million.sql to SQLite...\n";
    $converter = new MySQLToSQLiteConverter(__DIR__ . '/../../demo_database/million.sql');
    $sqlite_sql = $converter->convert();
    $converter->saveToFile(__DIR__ . '/../../demo_database/million_sqlite.sql');
    echo "✅ million_sqlite.sql created successfully\n\n";
} catch (Exception $e) {
    echo "❌ Error converting million.sql: " . $e->getMessage() . "\n";
}

echo "SQLite conversion completed!\n";
?>