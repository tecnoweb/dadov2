<?php
/**
 * xCrudRevolution - Database Schema Converter
 * Convert MySQL demo database to PostgreSQL and SQLite
 * 
 * @package    xCrudRevolution
 * @version    2.0.0
 * @copyright  Copyright (c) 2024 xCrudRevolution. All rights reserved.
 * @website    https://www.xcrudrevolution.com
 */

echo "🔄 xCrudRevolution - Database Schema Converter\n";
echo "===============================================\n\n";

class DatabaseSchemaConverter
{
    private $mysql_dump_file;
    private $output_dir;
    private $mysql_schema = [];
    private $mysql_data = [];
    
    public function __construct($mysql_dump_file, $output_dir = './converted_schemas')
    {
        $this->mysql_dump_file = $mysql_dump_file;
        $this->output_dir = $output_dir;
        
        if (!file_exists($mysql_dump_file)) {
            throw new Exception("MySQL dump file not found: $mysql_dump_file");
        }
        
        if (!is_dir($output_dir)) {
            mkdir($output_dir, 0755, true);
        }
    }
    
    /**
     * Parse MySQL dump file
     */
    public function parseMySQLDump()
    {
        echo "📖 Parsing MySQL dump file...\n";
        
        $content = file_get_contents($this->mysql_dump_file);
        $lines = explode("\n", $content);
        
        $current_table = null;
        $in_create_table = false;
        $in_insert = false;
        $table_sql = [];
        
        foreach ($lines as $line) {
            $line = trim($line);
            
            // Skip comments and empty lines
            if (empty($line) || strpos($line, '--') === 0 || strpos($line, '#') === 0) {
                continue;
            }
            
            // CREATE TABLE detection
            if (preg_match('/^CREATE TABLE `(.+?)`/i', $line, $matches)) {
                $current_table = $matches[1];
                $in_create_table = true;
                $table_sql = [$line];
                continue;
            }
            
            // End of CREATE TABLE
            if ($in_create_table && (strpos($line, ');') !== false || strpos($line, ') ENGINE=') !== false)) {
                $table_sql[] = $line;
                $this->mysql_schema[$current_table] = implode("\n", $table_sql);
                $in_create_table = false;
                $current_table = null;
                continue;
            }
            
            // Continue collecting CREATE TABLE
            if ($in_create_table) {
                $table_sql[] = $line;
                continue;
            }
            
            // INSERT statements
            if (preg_match('/^INSERT INTO `(.+?)`/i', $line, $matches)) {
                $table_name = $matches[1];
                if (!isset($this->mysql_data[$table_name])) {
                    $this->mysql_data[$table_name] = [];
                }
                $this->mysql_data[$table_name][] = $line;
            }
        }
        
        echo "✅ Parsed " . count($this->mysql_schema) . " tables and " . count($this->mysql_data) . " data sets\n\n";
    }
    
    /**
     * Convert MySQL schema to PostgreSQL
     */
    public function convertToPostgreSQL()
    {
        echo "🐘 Converting to PostgreSQL...\n";
        
        $postgresql_schema = [];
        $postgresql_data = [];
        
        foreach ($this->mysql_schema as $table_name => $mysql_sql) {
            $postgresql_schema[$table_name] = $this->convertMySQLToPostgreSQL($mysql_sql, $table_name);
        }
        
        foreach ($this->mysql_data as $table_name => $inserts) {
            $postgresql_data[$table_name] = [];
            foreach ($inserts as $insert) {
                $postgresql_data[$table_name][] = $this->convertMySQLInsertToPostgreSQL($insert);
            }
        }
        
        // Write PostgreSQL files
        $this->writeSchemaFile('postgresql_schema.sql', $postgresql_schema, $postgresql_data, 'postgresql');
        
        echo "✅ PostgreSQL conversion completed\n\n";
    }
    
    /**
     * Convert MySQL schema to SQLite
     */
    public function convertToSQLite()
    {
        echo "🪶 Converting to SQLite...\n";
        
        $sqlite_schema = [];
        $sqlite_data = [];
        
        foreach ($this->mysql_schema as $table_name => $mysql_sql) {
            $sqlite_schema[$table_name] = $this->convertMySQLToSQLite($mysql_sql, $table_name);
        }
        
        foreach ($this->mysql_data as $table_name => $inserts) {
            $sqlite_data[$table_name] = [];
            foreach ($inserts as $insert) {
                $sqlite_data[$table_name][] = $this->convertMySQLInsertToSQLite($insert);
            }
        }
        
        // Write SQLite files
        $this->writeSchemaFile('sqlite_schema.sql', $sqlite_schema, $sqlite_data, 'sqlite');
        
        echo "✅ SQLite conversion completed\n\n";
    }
    
    /**
     * Convert MySQL CREATE TABLE to PostgreSQL
     */
    private function convertMySQLToPostgreSQL($sql, $table_name)
    {
        // Remove MySQL-specific syntax
        $sql = preg_replace('/ENGINE=\w+/', '', $sql);
        $sql = preg_replace('/DEFAULT CHARSET=\w+/', '', $sql);
        $sql = preg_replace('/COLLATE=\w+/', '', $sql);
        $sql = preg_replace('/AUTO_INCREMENT=\d+/', '', $sql);
        
        // Convert data types
        $type_mapping = [
            'int\(\d+\)' => 'INTEGER',
            'INT\(\d+\)' => 'INTEGER',
            'bigint\(\d+\)' => 'BIGINT',
            'BIGINT\(\d+\)' => 'BIGINT',
            'tinyint\(1\)' => 'BOOLEAN',
            'TINYINT\(1\)' => 'BOOLEAN',
            'tinyint\(\d+\)' => 'SMALLINT',
            'TINYINT\(\d+\)' => 'SMALLINT',
            'varchar\((\d+)\)' => 'VARCHAR($1)',
            'VARCHAR\((\d+)\)' => 'VARCHAR($1)',
            'text' => 'TEXT',
            'TEXT' => 'TEXT',
            'longtext' => 'TEXT',
            'LONGTEXT' => 'TEXT',
            'datetime' => 'TIMESTAMP',
            'DATETIME' => 'TIMESTAMP',
            'timestamp' => 'TIMESTAMP',
            'TIMESTAMP' => 'TIMESTAMP',
            'decimal\((\d+),(\d+)\)' => 'DECIMAL($1,$2)',
            'DECIMAL\((\d+),(\d+)\)' => 'DECIMAL($1,$2)',
            'double' => 'DOUBLE PRECISION',
            'DOUBLE' => 'DOUBLE PRECISION',
            'float' => 'REAL',
            'FLOAT' => 'REAL'
        ];
        
        foreach ($type_mapping as $mysql_type => $pg_type) {
            $sql = preg_replace('/' . $mysql_type . '/i', $pg_type, $sql);
        }
        
        // Convert AUTO_INCREMENT to SERIAL
        $sql = preg_replace('/(\w+)\s+int\s+NOT NULL AUTO_INCREMENT/i', '$1 SERIAL PRIMARY KEY', $sql);
        $sql = preg_replace('/(\w+)\s+INTEGER\s+NOT NULL AUTO_INCREMENT/i', '$1 SERIAL PRIMARY KEY', $sql);
        
        // Remove duplicate PRIMARY KEY if SERIAL was added
        $sql = preg_replace('/SERIAL PRIMARY KEY[^,]*,\s*PRIMARY KEY \([^)]+\)/i', 'SERIAL PRIMARY KEY', $sql);
        
        // Convert backticks to double quotes
        $sql = str_replace('`', '"', $sql);
        
        // Clean up multiple spaces and commas
        $sql = preg_replace('/,\s*,/', ',', $sql);
        $sql = preg_replace('/\s+/', ' ', $sql);
        
        return $sql;
    }
    
    /**
     * Convert MySQL CREATE TABLE to SQLite
     */
    private function convertMySQLToSQLite($sql, $table_name)
    {
        // Remove MySQL-specific syntax
        $sql = preg_replace('/ENGINE=\w+/', '', $sql);
        $sql = preg_replace('/DEFAULT CHARSET=\w+/', '', $sql);
        $sql = preg_replace('/COLLATE=\w+/', '', $sql);
        $sql = preg_replace('/AUTO_INCREMENT=\d+/', '', $sql);
        
        // Convert data types (SQLite is more flexible)
        $type_mapping = [
            'int\(\d+\)' => 'INTEGER',
            'INT\(\d+\)' => 'INTEGER',
            'bigint\(\d+\)' => 'INTEGER',
            'BIGINT\(\d+\)' => 'INTEGER',
            'tinyint\(1\)' => 'INTEGER',
            'TINYINT\(1\)' => 'INTEGER',
            'tinyint\(\d+\)' => 'INTEGER',
            'TINYINT\(\d+\)' => 'INTEGER',
            'varchar\((\d+)\)' => 'TEXT',
            'VARCHAR\((\d+)\)' => 'TEXT',
            'text' => 'TEXT',
            'TEXT' => 'TEXT',
            'longtext' => 'TEXT',
            'LONGTEXT' => 'TEXT',
            'datetime' => 'TEXT',
            'DATETIME' => 'TEXT',
            'timestamp' => 'TEXT',
            'TIMESTAMP' => 'TEXT',
            'decimal\((\d+),(\d+)\)' => 'REAL',
            'DECIMAL\((\d+),(\d+)\)' => 'REAL',
            'double' => 'REAL',
            'DOUBLE' => 'REAL',
            'float' => 'REAL',
            'FLOAT' => 'REAL'
        ];
        
        foreach ($type_mapping as $mysql_type => $sqlite_type) {
            $sql = preg_replace('/' . $mysql_type . '/i', $sqlite_type, $sql);
        }
        
        // Convert AUTO_INCREMENT to SQLite AUTOINCREMENT
        $sql = preg_replace('/(\w+)\s+int\s+NOT NULL AUTO_INCREMENT/i', '$1 INTEGER PRIMARY KEY AUTOINCREMENT', $sql);
        $sql = preg_replace('/(\w+)\s+INTEGER\s+NOT NULL AUTO_INCREMENT/i', '$1 INTEGER PRIMARY KEY AUTOINCREMENT', $sql);
        
        // Remove duplicate PRIMARY KEY if AUTOINCREMENT was added
        $sql = preg_replace('/INTEGER PRIMARY KEY AUTOINCREMENT[^,]*,\s*PRIMARY KEY \([^)]+\)/i', 'INTEGER PRIMARY KEY AUTOINCREMENT', $sql);
        
        // Remove backticks (SQLite doesn't need them)
        $sql = str_replace('`', '', $sql);
        
        // Clean up multiple spaces and commas
        $sql = preg_replace('/,\s*,/', ',', $sql);
        $sql = preg_replace('/\s+/', ' ', $sql);
        
        return $sql;
    }
    
    /**
     * Convert MySQL INSERT to PostgreSQL
     */
    private function convertMySQLInsertToPostgreSQL($sql)
    {
        // Convert backticks to double quotes for table/column names
        $sql = str_replace('`', '"', $sql);
        
        // Convert MySQL date functions
        $sql = str_replace('NOW()', 'CURRENT_TIMESTAMP', $sql);
        
        return $sql;
    }
    
    /**
     * Convert MySQL INSERT to SQLite
     */
    private function convertMySQLInsertToSQLite($sql)
    {
        // Remove backticks
        $sql = str_replace('`', '', $sql);
        
        // Convert MySQL date functions
        $sql = str_replace('NOW()', "datetime('now')", $sql);
        
        return $sql;
    }
    
    /**
     * Write schema file
     */
    private function writeSchemaFile($filename, $schema, $data, $db_type)
    {
        $filepath = $this->output_dir . '/' . $filename;
        $content = [];
        
        // Add header comment
        $content[] = "-- xCrudRevolution - " . strtoupper($db_type) . " Schema";
        $content[] = "-- Converted from MySQL demo database";
        $content[] = "-- Generated on: " . date('Y-m-d H:i:s');
        $content[] = "-- Website: https://www.xcrudrevolution.com";
        $content[] = "";
        
        if ($db_type === 'postgresql') {
            $content[] = "-- PostgreSQL specific settings";
            $content[] = "SET client_encoding = 'UTF8';";
            $content[] = "SET standard_conforming_strings = on;";
            $content[] = "";
        }
        
        if ($db_type === 'sqlite') {
            $content[] = "-- SQLite specific settings";
            $content[] = "PRAGMA foreign_keys = ON;";
            $content[] = "PRAGMA journal_mode = WAL;";
            $content[] = "";
        }
        
        // Add schema
        $content[] = "-- ============================================";
        $content[] = "-- TABLE SCHEMAS";
        $content[] = "-- ============================================";
        $content[] = "";
        
        foreach ($schema as $table_name => $table_sql) {
            $content[] = "-- Table: $table_name";
            $content[] = $table_sql;
            $content[] = "";
        }
        
        // Add data
        $content[] = "-- ============================================";
        $content[] = "-- TABLE DATA";
        $content[] = "-- ============================================";
        $content[] = "";
        
        foreach ($data as $table_name => $inserts) {
            if (!empty($inserts)) {
                $content[] = "-- Data for table: $table_name";
                foreach ($inserts as $insert) {
                    $content[] = $insert;
                }
                $content[] = "";
            }
        }
        
        // Write file
        file_put_contents($filepath, implode("\n", $content));
        
        echo "📄 Generated: $filepath (" . number_format(filesize($filepath)) . " bytes)\n";
    }
}

// Usage
try {
    // Look for MySQL dump files
    $mysql_dumps = [
        '../demo_old_xcrud/database_demo.sql',
        '../database_demo.sql',
        './database_demo.sql',
        '../dadov2.sql'
    ];
    
    $mysql_dump = null;
    foreach ($mysql_dumps as $dump) {
        if (file_exists($dump)) {
            $mysql_dump = $dump;
            break;
        }
    }
    
    if (!$mysql_dump) {
        echo "❌ No MySQL dump file found! Please ensure one of these files exists:\n";
        foreach ($mysql_dumps as $dump) {
            echo "   • $dump\n";
        }
        echo "\n💡 You can export your MySQL database with:\n";
        echo "   mysqldump -u root -p dadov2 > database_demo.sql\n\n";
        exit(1);
    }
    
    echo "✅ Found MySQL dump: $mysql_dump\n\n";
    
    $converter = new DatabaseSchemaConverter($mysql_dump);
    
    // Parse MySQL dump
    $converter->parseMySQLDump();
    
    // Convert to PostgreSQL
    $converter->convertToPostgreSQL();
    
    // Convert to SQLite
    $converter->convertToSQLite();
    
    echo "🎉 CONVERSION COMPLETED!\n";
    echo "======================\n\n";
    echo "📁 Output files created in: ./converted_schemas/\n";
    echo "   • postgresql_schema.sql - Ready for PostgreSQL\n";
    echo "   • sqlite_schema.sql - Ready for SQLite\n\n";
    
    echo "💡 NEXT STEPS:\n";
    echo "==============\n";
    echo "1. PostgreSQL Setup:\n";
    echo "   createdb xcrudrevolution\n";
    echo "   psql xcrudrevolution < converted_schemas/postgresql_schema.sql\n\n";
    echo "2. SQLite Setup:\n";
    echo "   sqlite3 xcrudrevolution.db < converted_schemas/sqlite_schema.sql\n\n";
    echo "3. Test multi-database support with xCrudRevolution!\n\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>