<?php
/**
 * xCrudRevolution - Multi-Database Query Builder
 * Abstract SQL queries for MySQL, PostgreSQL, SQLite compatibility
 * 
 * @package    xCrudRevolution
 * @version    2.0.0
 * @copyright  Copyright (c) 2024 xCrudRevolution. All rights reserved.
 * @license    Proprietary License - Unauthorized copying or distribution is prohibited.
 * @website    https://www.xcrudrevolution.com
 */

namespace XcrudRevolution\Database;

class QueryBuilder 
{
    /**
     * Database driver type
     * @var string
     */
    private $driverType;
    
    /**
     * Current SELECT parts
     */
    private $select = [];
    private $from = [];
    private $joins = [];
    private $where = [];
    private $groupBy = [];
    private $having = [];
    private $orderBy = [];
    private $limit = null;
    private $offset = null;
    
    /**
     * Constructor
     * 
     * @param string $driverType Database driver type (mysql, postgresql, sqlite)
     */
    public function __construct($driverType = 'mysql')
    {
        $this->driverType = strtolower($driverType);
    }
    
    /**
     * SELECT clause
     * 
     * @param array|string $columns Columns to select
     * @return self
     */
    public function select($columns = '*')
    {
        if (is_array($columns)) {
            $this->select = array_merge($this->select, $columns);
        } else {
            $this->select[] = $columns;
        }
        return $this;
    }
    
    /**
     * FROM clause
     * 
     * @param string $table Table name
     * @param string|null $alias Table alias
     * @return self
     */
    public function from($table, $alias = null)
    {
        $tableStr = $this->quoteIdentifier($table);
        if ($alias) {
            $tableStr .= ' AS ' . $this->quoteIdentifier($alias);
        }
        $this->from[] = $tableStr;
        return $this;
    }
    
    /**
     * JOIN clause
     * 
     * @param string $table Table to join
     * @param string $condition Join condition
     * @param string $type Join type (INNER, LEFT, RIGHT, FULL)
     * @param string|null $alias Table alias
     * @return self
     */
    public function join($table, $condition, $type = 'INNER', $alias = null)
    {
        $tableStr = $this->quoteIdentifier($table);
        if ($alias) {
            $tableStr .= ' AS ' . $this->quoteIdentifier($alias);
        }
        
        $this->joins[] = strtoupper($type) . ' JOIN ' . $tableStr . ' ON ' . $condition;
        return $this;
    }
    
    /**
     * LEFT JOIN clause
     */
    public function leftJoin($table, $condition, $alias = null)
    {
        return $this->join($table, $condition, 'LEFT', $alias);
    }
    
    /**
     * RIGHT JOIN clause
     */
    public function rightJoin($table, $condition, $alias = null)
    {
        return $this->join($table, $condition, 'RIGHT', $alias);
    }
    
    /**
     * INNER JOIN clause
     */
    public function innerJoin($table, $condition, $alias = null)
    {
        return $this->join($table, $condition, 'INNER', $alias);
    }
    
    /**
     * FULL OUTER JOIN clause (PostgreSQL, SQL Server)
     * Note: MySQL doesn't support FULL OUTER JOIN natively
     */
    public function fullJoin($table, $condition, $alias = null)
    {
        if ($this->driverType === 'mysql') {
            // MySQL doesn't support FULL OUTER JOIN
            // We could emulate it with UNION of LEFT and RIGHT JOIN
            throw new \Exception('FULL OUTER JOIN not supported by MySQL. Use UNION of LEFT and RIGHT JOIN instead.');
        }
        return $this->join($table, $condition, 'FULL OUTER', $alias);
    }
    
    /**
     * CROSS JOIN clause
     */
    public function crossJoin($table, $alias = null)
    {
        $tableStr = $this->quoteIdentifier($table);
        if ($alias) {
            $tableStr .= ' AS ' . $this->quoteIdentifier($alias);
        }
        
        $this->joins[] = 'CROSS JOIN ' . $tableStr;
        return $this;
    }
    
    /**
     * WHERE clause
     * 
     * @param string $condition WHERE condition
     * @param string $operator Logical operator (AND, OR)
     * @return self
     */
    public function where($condition, $operator = 'AND')
    {
        if (!empty($this->where) && $operator) {
            $this->where[] = strtoupper($operator);
        }
        $this->where[] = '(' . $condition . ')';
        return $this;
    }
    
    /**
     * OR WHERE clause
     */
    public function orWhere($condition)
    {
        return $this->where($condition, 'OR');
    }
    
    /**
     * WHERE IN clause
     * 
     * @param string $column Column name
     * @param array $values Values array
     * @param bool $not Use NOT IN
     * @return self
     */
    public function whereIn($column, $values, $not = false)
    {
        if (empty($values)) {
            return $this;
        }
        
        $column = $this->quoteIdentifier($column);
        $valuesStr = implode(', ', array_map(function($v) {
            return is_numeric($v) ? $v : "'" . addslashes($v) . "'";
        }, $values));
        
        $operator = $not ? 'NOT IN' : 'IN';
        $condition = "$column $operator ($valuesStr)";
        
        return $this->where($condition);
    }
    
    /**
     * GROUP BY clause
     */
    public function groupBy($columns)
    {
        if (is_array($columns)) {
            foreach ($columns as $column) {
                $this->groupBy[] = $this->quoteIdentifier($column);
            }
        } else {
            $this->groupBy[] = $this->quoteIdentifier($columns);
        }
        return $this;
    }
    
    /**
     * HAVING clause
     */
    public function having($condition)
    {
        $this->having[] = $condition;
        return $this;
    }
    
    /**
     * ORDER BY clause
     * 
     * @param string $column Column name
     * @param string $direction ASC or DESC
     * @return self
     */
    public function orderBy($column, $direction = 'ASC')
    {
        $column = $this->quoteIdentifier($column);
        $direction = strtoupper($direction);
        $this->orderBy[] = "$column $direction";
        return $this;
    }
    
    /**
     * LIMIT and OFFSET
     * 
     * @param int $limit Number of records to limit
     * @param int $offset Number of records to skip
     * @return self
     */
    public function limit($limit, $offset = null)
    {
        $this->limit = (int)$limit;
        if ($offset !== null) {
            $this->offset = (int)$offset;
        }
        return $this;
    }
    
    /**
     * Build SELECT query
     * 
     * @return string Complete SQL query
     */
    public function buildSelect()
    {
        $sql = 'SELECT ';
        
        // SELECT columns
        if (empty($this->select)) {
            $sql .= '*';
        } else {
            $sql .= implode(', ', $this->select);
        }
        
        // FROM tables
        if (!empty($this->from)) {
            $sql .= ' FROM ' . implode(', ', $this->from);
        }
        
        // JOINs
        if (!empty($this->joins)) {
            $sql .= ' ' . implode(' ', $this->joins);
        }
        
        // WHERE conditions
        if (!empty($this->where)) {
            $sql .= ' WHERE ' . implode(' ', $this->where);
        }
        
        // GROUP BY
        if (!empty($this->groupBy)) {
            $sql .= ' GROUP BY ' . implode(', ', $this->groupBy);
        }
        
        // HAVING
        if (!empty($this->having)) {
            $sql .= ' HAVING ' . implode(' AND ', $this->having);
        }
        
        // ORDER BY
        if (!empty($this->orderBy)) {
            $sql .= ' ORDER BY ' . implode(', ', $this->orderBy);
        }
        
        // LIMIT and OFFSET (database-specific)
        $sql .= $this->buildLimitClause();
        
        return $sql;
    }
    
    /**
     * Build INSERT query
     * 
     * @param string $table Table name
     * @param array $data Associative array of column => value
     * @return string
     */
    public function buildInsert($table, $data)
    {
        if (empty($data)) {
            throw new \Exception('Insert data cannot be empty');
        }
        
        $table = $this->quoteIdentifier($table);
        $columns = array_map([$this, 'quoteIdentifier'], array_keys($data));
        $values = array_map([$this, 'quoteValue'], array_values($data));
        
        $sql = "INSERT INTO $table (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $values) . ")";
        
        // Add RETURNING clause for PostgreSQL to get insert ID
        if ($this->driverType === 'postgresql') {
            $sql .= ' RETURNING *';
        }
        
        return $sql;
    }
    
    /**
     * Build INSERT query from prepared columns and values (for compatibility with xcrud)
     * 
     * @param string $table Table name
     * @param array $columns Column names (already quoted)
     * @param array $values Values (already escaped)
     * @return string Complete INSERT query
     */
    public function buildInsertFromParts($table, $columns, $values)
    {
        $table = $this->quoteIdentifier($table);
        return "INSERT INTO {$table} (" . implode(',', $columns) . ") VALUES (" . implode(',', $values) . ")";
    }
    
    /**
     * Build bulk INSERT query (multiple rows)
     * 
     * @param string $table Table name
     * @param array $columns Column names
     * @param array $rows Array of value arrays
     * @return string Complete bulk INSERT query
     */
    public function buildBulkInsert($table, $columns, $rows)
    {
        if (empty($columns) || empty($rows)) {
            throw new \Exception('No columns or rows provided for bulk INSERT');
        }
        
        $table = $this->quoteIdentifier($table);
        $quotedColumns = array_map(function($col) {
            // Remove backticks if already quoted
            $col = trim($col, '`');
            return $this->quoteIdentifier($col);
        }, $columns);
        
        return "INSERT INTO {$table} (" . implode(',', $quotedColumns) . ") VALUES " . implode(',', $rows);
    }
    
    /**
     * Build UPDATE query
     * 
     * @param string $table Table name
     * @param array $data Associative array of column => value
     * @param array $where WHERE conditions
     * @return string
     */
    public function buildUpdate($table, $data, $where = [])
    {
        if (empty($data)) {
            throw new \Exception('Update data cannot be empty');
        }
        
        $table = $this->quoteIdentifier($table);
        
        // SET clause
        $setParts = [];
        foreach ($data as $column => $value) {
            $column = $this->quoteIdentifier($column);
            $value = $this->quoteValue($value);
            $setParts[] = "$column = $value";
        }
        
        $sql = "UPDATE $table SET " . implode(', ', $setParts);
        
        // WHERE clause
        if (!empty($where)) {
            $whereParts = [];
            foreach ($where as $column => $value) {
                $column = $this->quoteIdentifier($column);
                $value = $this->quoteValue($value);
                $whereParts[] = "$column = $value";
            }
            $sql .= ' WHERE ' . implode(' AND ', $whereParts);
        }
        
        return $sql;
    }
    
    /**
     * Build UPDATE query with custom WHERE clause and optional LIMIT
     * 
     * @param string $table Table name
     * @param array $data Data to update (key => value)
     * @param string $whereClause Custom WHERE clause (already formatted)
     * @param int|null $limit Optional LIMIT clause
     * @return string Complete UPDATE query
     */
    public function buildUpdateCustomWhere($table, $data, $whereClause, $limit = null)
    {
        if (empty($data)) {
            throw new \Exception('Update data cannot be empty');
        }
        
        $table = $this->quoteIdentifier($table);
        $setParts = [];
        
        foreach ($data as $column => $value) {
            $column = $this->quoteIdentifier(trim($column, '`'));
            $setParts[] = "$column = " . $this->quoteValue($value);
        }
        
        $sql = "UPDATE $table SET " . implode(', ', $setParts) . " WHERE $whereClause";
        
        // Add LIMIT if specified (MySQL specific, not supported by all databases)
        if ($limit && $this->driverType === 'mysql') {
            $sql .= " LIMIT $limit";
        }
        
        return $sql;
    }
    
    /**
     * Build UPDATE query from prepared SET parts and WHERE clause (for compatibility)
     * 
     * @param string $table Table name
     * @param array $setParts Already formatted SET parts
     * @param string $whereClause Custom WHERE clause
     * @param string|null $joins Optional JOIN clauses
     * @param int|null $limit Optional LIMIT
     * @return string Complete UPDATE query
     */
    public function buildUpdateFromParts($table, $setParts, $whereClause, $joins = null, $limit = null)
    {
        $table = $this->quoteIdentifier($table);
        
        if ($joins) {
            // UPDATE with JOINs
            $sql = "UPDATE $table AS $table $joins SET " . implode(",\r\n", $setParts) . " WHERE $whereClause";
        } else {
            $sql = "UPDATE $table SET " . implode(",\r\n", $setParts) . " WHERE $whereClause";
        }
        
        // Add LIMIT if specified (MySQL specific)
        if ($limit && $this->driverType === 'mysql') {
            $sql .= " LIMIT $limit";
        }
        
        return $sql;
    }
    
    /**
     * Build DELETE query
     * 
     * @param string $table Table name
     * @param array $where WHERE conditions
     * @return string
     */
    public function buildDelete($table, $where = [])
    {
        $table = $this->quoteIdentifier($table);
        $sql = "DELETE FROM $table";
        
        // WHERE clause
        if (!empty($where)) {
            $whereParts = [];
            foreach ($where as $column => $value) {
                $column = $this->quoteIdentifier($column);
                $value = $this->quoteValue($value);
                $whereParts[] = "$column = $value";
            }
            $sql .= ' WHERE ' . implode(' AND ', $whereParts);
        }
        
        return $sql;
    }
    
    /**
     * Build DELETE query with custom WHERE clause and optional LIMIT
     * 
     * @param string $table Table name
     * @param string $whereClause Custom WHERE clause (already formatted)
     * @param int|null $limit Optional LIMIT clause
     * @return string Complete DELETE query
     */
    public function buildDeleteCustomWhere($table, $whereClause, $limit = null)
    {
        $table = $this->quoteIdentifier($table);
        $sql = "DELETE FROM $table WHERE $whereClause";
        
        // Add LIMIT if specified (MySQL specific, not supported by all databases)
        if ($limit && $this->driverType === 'mysql') {
            $sql .= " LIMIT $limit";
        }
        
        return $sql;
    }
    
    /**
     * Build SHOW COLUMNS query (database-specific)
     * 
     * @param string $table Table name
     * @return string
     */
    public function buildShowColumns($table)
    {
        $table = $this->quoteIdentifier($table);
        
        switch ($this->driverType) {
            case 'mysql':
                return "SHOW FULL COLUMNS FROM $table";
                
            case 'postgresql':
                return "
                    SELECT 
                        column_name AS Field,
                        data_type AS Type,
                        is_nullable AS Null,
                        column_default AS Default,
                        '' AS Key,
                        '' AS Extra,
                        '' AS Privileges,
                        '' AS Comment
                    FROM information_schema.columns 
                    WHERE table_name = " . $this->quoteValue(trim($table, '`"[]')) . "
                    ORDER BY ordinal_position
                ";
                
            case 'sqlite':
                return "PRAGMA table_info($table)";
                
            default:
                throw new \Exception("Unsupported database type: {$this->driverType}");
        }
    }
    
    /**
     * Build SHOW TABLES query (database-specific)
     * 
     * @return string
     */
    public function buildShowTables()
    {
        switch ($this->driverType) {
            case 'mysql':
                return 'SHOW TABLES';
                
            case 'postgresql':
                return "SELECT tablename AS Tables_in_database FROM pg_tables WHERE schemaname = 'public' ORDER BY tablename";
                
            case 'sqlite':
                return "SELECT name AS Tables_in_database FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%' ORDER BY name";
                
            default:
                throw new \Exception("Unsupported database type: {$this->driverType}");
        }
    }
    
    /**
     * Build LIMIT clause (database-specific)
     * 
     * @return string
     */
    private function buildLimitClause()
    {
        if ($this->limit === null) {
            return '';
        }
        
        switch ($this->driverType) {
            case 'mysql':
            case 'sqlite':
                $sql = " LIMIT {$this->limit}";
                if ($this->offset !== null) {
                    $sql .= " OFFSET {$this->offset}";
                }
                return $sql;
                
            case 'postgresql':
                $sql = " LIMIT {$this->limit}";
                if ($this->offset !== null) {
                    $sql .= " OFFSET {$this->offset}";
                }
                return $sql;
                
            default:
                return " LIMIT {$this->limit}";
        }
    }
    
    /**
     * Quote identifier (table/column names) - database-specific
     * 
     * @param string $identifier Identifier to quote
     * @return string
     */
    public function quoteIdentifier($identifier)
    {
        // Handle complex identifiers like table.column
        if (strpos($identifier, '.') !== false) {
            $parts = explode('.', $identifier);
            return implode('.', array_map([$this, 'quoteIdentifier'], $parts));
        }
        
        // Skip if already quoted or is a function/expression
        if (preg_match('/^[`"\[].*[`"\]]$/', $identifier) || 
            preg_match('/\(|\)|\*|AS\s+/i', $identifier) ||
            is_numeric($identifier)) {
            return $identifier;
        }
        
        switch ($this->driverType) {
            case 'mysql':
                return "`$identifier`";
                
            case 'postgresql':
                return "\"$identifier\"";
                
            case 'sqlite':
                return "[$identifier]";
                
            default:
                return "`$identifier`";
        }
    }
    
    /**
     * Quote value for SQL
     * 
     * @param mixed $value Value to quote
     * @return string
     */
    public function quoteValue($value)
    {
        if ($value === null) {
            return 'NULL';
        }
        
        if (is_bool($value)) {
            return $this->driverType === 'postgresql' 
                ? ($value ? 'TRUE' : 'FALSE')
                : ($value ? '1' : '0');
        }
        
        if (is_numeric($value)) {
            return (string)$value;
        }
        
        return "'" . addslashes($value) . "'";
    }
    
    /**
     * Reset query builder
     * 
     * @return self
     */
    public function reset()
    {
        $this->select = [];
        $this->from = [];
        $this->joins = [];
        $this->where = [];
        $this->groupBy = [];
        $this->having = [];
        $this->orderBy = [];
        $this->limit = null;
        $this->offset = null;
        
        return $this;
    }
    
    // MYSQL-SPECIFIC FUNCTION CONVERTERS
    
    /**
     * Database-agnostic CONCAT function
     * 
     * @param array $columns Columns to concatenate
     * @param string $separator Optional separator
     * @return string SQL CONCAT expression
     */
    public function concat($columns, $separator = null)
    {
        if (empty($columns)) {
            return "''";
        }
        
        // Quote identifiers
        $quotedColumns = array_map(function($col) {
            return is_string($col) && !preg_match('/^[\'\"]/', $col) ? $this->quoteIdentifier($col) : $col;
        }, $columns);
        
        switch ($this->driverType) {
            case 'mysql':
                if ($separator !== null) {
                    return 'CONCAT_WS(' . $this->escapeValue($separator) . ', ' . implode(', ', $quotedColumns) . ')';
                } else {
                    return 'CONCAT(' . implode(', ', $quotedColumns) . ')';
                }
                
            case 'postgresql':
                if ($separator !== null) {
                    return 'CONCAT_WS(' . $this->escapeValue($separator) . ', ' . implode(', ', $quotedColumns) . ')';
                } else {
                    return implode(' || ', $quotedColumns);
                }
                
            case 'sqlite':
                // SQLite doesn't have CONCAT_WS, emulate it
                if ($separator !== null) {
                    $nonNullColumns = array_map(function($col) {
                        return "CASE WHEN $col IS NULL THEN '' ELSE CAST($col AS TEXT) END";
                    }, $quotedColumns);
                    return implode(' || ' . $this->escapeValue($separator) . ' || ', $nonNullColumns);
                } else {
                    return implode(' || ', $quotedColumns);
                }
                
            default:
                throw new \Exception("Unsupported database type for CONCAT: {$this->driverType}");
        }
    }
    
    /**
     * Database-agnostic GROUP_CONCAT function
     * 
     * @param string $column Column to group
     * @param string $separator Separator (default: ', ')
     * @param bool $distinct Use DISTINCT
     * @param string|null $orderBy ORDER BY clause
     * @return string SQL GROUP_CONCAT expression
     */
    public function groupConcat($column, $separator = ', ', $distinct = false, $orderBy = null)
    {
        $column = $this->quoteIdentifier($column);
        $separatorStr = $this->escapeValue($separator);
        $distinctStr = $distinct ? 'DISTINCT ' : '';
        $orderStr = $orderBy ? " ORDER BY $orderBy" : '';
        
        switch ($this->driverType) {
            case 'mysql':
                return "GROUP_CONCAT({$distinctStr}{$column}{$orderStr} SEPARATOR {$separatorStr})";
                
            case 'postgresql':
                if ($distinct) {
                    throw new \Exception('PostgreSQL STRING_AGG does not support DISTINCT directly');
                }
                return "STRING_AGG({$column}{$orderStr}, {$separatorStr})";
                
            case 'sqlite':
                return "GROUP_CONCAT({$distinctStr}{$column}{$orderStr}, {$separatorStr})";
                
            default:
                throw new \Exception("Unsupported database type for GROUP_CONCAT: {$this->driverType}");
        }
    }
    
    /**
     * Database-agnostic FIND_IN_SET function
     * 
     * @param string $needle Value to find
     * @param string $haystack Comma-separated list column
     * @return string SQL FIND_IN_SET expression
     */
    public function findInSet($needle, $haystack)
    {
        $needle = is_string($needle) && !preg_match('/^[\'\"]/', $needle) ? $this->quoteIdentifier($needle) : $needle;
        $haystack = $this->quoteIdentifier($haystack);
        
        switch ($this->driverType) {
            case 'mysql':
                return "FIND_IN_SET({$needle}, {$haystack})";
                
            case 'postgresql':
                // Use array functions or LIKE with delimiters
                return "{$needle} = ANY(STRING_TO_ARRAY({$haystack}, ','))";
                
            case 'sqlite':
                // Emulate FIND_IN_SET with LIKE and proper delimiters
                return "(',' || {$haystack} || ',') LIKE ('%,' || {$needle} || ',%')";
                
            default:
                throw new \Exception("Unsupported database type for FIND_IN_SET: {$this->driverType}");
        }
    }
    
    /**
     * Database-agnostic CAST function
     * 
     * @param string $column Column to cast
     * @param string $type Target type
     * @return string SQL CAST expression
     */
    public function cast($column, $type)
    {
        $column = $this->quoteIdentifier($column);
        
        // Map MySQL types to database-specific types
        $typeMap = [
            'mysql' => [
                'unsigned' => 'UNSIGNED',
                'signed' => 'SIGNED',
                'decimal' => 'DECIMAL',
                'char' => 'CHAR',
                'binary' => 'BINARY',
                'date' => 'DATE',
                'datetime' => 'DATETIME',
                'time' => 'TIME'
            ],
            'postgresql' => [
                'unsigned' => 'INTEGER',
                'signed' => 'INTEGER',
                'decimal' => 'DECIMAL',
                'char' => 'VARCHAR',
                'binary' => 'BYTEA',
                'date' => 'DATE',
                'datetime' => 'TIMESTAMP',
                'time' => 'TIME'
            ],
            'sqlite' => [
                'unsigned' => 'INTEGER',
                'signed' => 'INTEGER',
                'decimal' => 'REAL',
                'char' => 'TEXT',
                'binary' => 'BLOB',
                'date' => 'TEXT',
                'datetime' => 'TEXT',
                'time' => 'TEXT'
            ]
        ];
        
        $mappedType = $typeMap[$this->driverType][strtolower($type)] ?? strtoupper($type);
        
        return "CAST({$column} AS {$mappedType})";
    }
    
    /**
     * Helper method to escape values safely
     */
    private function escapeValue($value)
    {
        if (is_null($value)) {
            return 'NULL';
        } elseif (is_bool($value)) {
            return $value ? '1' : '0';
        } elseif (is_numeric($value)) {
            return (string)$value;
        } else {
            return "'" . addslashes($value) . "'";
        }
    }
    
    /**
     * Database-agnostic comparison operations
     * Supports all xCrud comparison operators including MySQL-specific ones
     * 
     * @param mixed $val1 First value
     * @param string $operator Comparison operator
     * @param mixed $val2 Second value
     * @return bool Result of comparison
     */
    public function compare($val1, $operator, $val2)
    {
        switch ($operator) {
            case '=':
            case 'eq':
                return ($val1 == $val2) ? true : false;
            case '>':
            case 'gt':
                return ($val1 > $val2) ? true : false;
            case '<':
            case 'lt':
                return ($val1 < $val2) ? true : false;
            case '>=':
            case 'gte':
                return ($val1 >= $val2) ? true : false;
            case '<=':
            case 'lte':
                return ($val1 <= $val2) ? true : false;
            case '!=':
            case '<>':
            case 'ne':
                return ($val1 != $val2) ? true : false;
            case '^=': // starts with
            case 'starts_with':
                return (mb_strpos($val1, $val2, 0, 'UTF-8') === 0) ? true : false;
            case '$=': // ends with
            case 'ends_with':
                return (mb_strpos($val1, $val2, 0, 'UTF-8') == (mb_strlen($val1, 'UTF-8') - mb_strlen($val2, 'UTF-8'))) ? true : false;
            case '~=': // contains
            case 'contains':
                return (mb_strpos($val1, $val2, 0, 'UTF-8') !== false) ? true : false;
            case 'in':
                return is_array($val2) ? in_array($val1, $val2) : false;
            case 'not_in':
                return is_array($val2) ? !in_array($val1, $val2) : true;
            case 'between':
                return is_array($val2) && count($val2) == 2 ? ($val1 >= $val2[0] && $val1 <= $val2[1]) : false;
            case 'not_between':
                return is_array($val2) && count($val2) == 2 ? !($val1 >= $val2[0] && $val1 <= $val2[1]) : true;
            case 'is_null':
                return is_null($val1);
            case 'is_not_null':
                return !is_null($val1);
            case 'regex':
            case 'regexp':
                return preg_match('/' . $val2 . '/', $val1) === 1;
            case 'not_regex':
            case 'not_regexp':
                return preg_match('/' . $val2 . '/', $val1) !== 1;
            default:
                return false;
        }
    }
    
    /**
     * Generate SQL comparison expression
     * 
     * @param string $column Column name
     * @param string $operator Comparison operator
     * @param mixed $value Value to compare
     * @return string SQL comparison expression
     */
    public function buildComparison($column, $operator, $value)
    {
        $column = $this->quoteIdentifier($column);
        $escapedValue = $this->escapeValue($value);
        
        switch ($operator) {
            case '=':
            case '>':
            case '<':
            case '>=':
            case '<=':
            case '!=':
                return "{$column} {$operator} {$escapedValue}";
                
            case '^=': // starts with
                return "{$column} LIKE {$this->escapeValue($value . '%')}";
                
            case '$=': // ends with
                return "{$column} LIKE {$this->escapeValue('%' . $value)}";
                
            case '~=': // contains
                return "{$column} LIKE {$this->escapeValue('%' . $value . '%')}";
                
            default:
                throw new \Exception("Unsupported comparison operator: {$operator}");
        }
    }
    
    /**
     * Database-agnostic NOW() function
     * 
     * @return string SQL NOW expression
     */
    public function now()
    {
        switch ($this->driverType) {
            case 'mysql':
                return 'NOW()';
            case 'postgresql':
                return 'NOW()';
            case 'sqlite':
                return "DATETIME('now')";
            default:
                return 'CURRENT_TIMESTAMP';
        }
    }
    
    /**
     * Database-agnostic COALESCE function (replaces MySQL IFNULL)
     * 
     * @param array $columns Columns to check for NULL
     * @param mixed $defaultValue Default value if all are NULL
     * @return string SQL COALESCE expression
     */
    public function coalesce($columns, $defaultValue = null)
    {
        $quotedColumns = array_map(function($col) {
            return is_string($col) && !preg_match('/^[\'\"]/', $col) ? $this->quoteIdentifier($col) : $col;
        }, $columns);
        
        if ($defaultValue !== null) {
            $quotedColumns[] = $this->escapeValue($defaultValue);
        }
        
        return 'COALESCE(' . implode(', ', $quotedColumns) . ')';
    }
    
    /**
     * Database-agnostic SUBSTRING function
     * 
     * @param string $column Column name
     * @param int $start Start position (1-based)
     * @param int|null $length Length of substring
     * @return string SQL SUBSTRING expression
     */
    public function substring($column, $start, $length = null)
    {
        $column = $this->quoteIdentifier($column);
        
        switch ($this->driverType) {
            case 'mysql':
                return $length ? "SUBSTRING({$column}, {$start}, {$length})" : "SUBSTRING({$column}, {$start})";
            case 'postgresql':
                return $length ? "SUBSTRING({$column} FROM {$start} FOR {$length})" : "SUBSTRING({$column} FROM {$start})";
            case 'sqlite':
                return $length ? "SUBSTR({$column}, {$start}, {$length})" : "SUBSTR({$column}, {$start})";
            default:
                return $length ? "SUBSTRING({$column}, {$start}, {$length})" : "SUBSTRING({$column}, {$start})";
        }
    }
    
    /**
     * Database-agnostic LENGTH function
     * 
     * @param string $column Column name
     * @return string SQL LENGTH expression
     */
    public function length($column)
    {
        $column = $this->quoteIdentifier($column);
        
        switch ($this->driverType) {
            case 'mysql':
                return "CHAR_LENGTH({$column})";
            case 'postgresql':
                return "LENGTH({$column})";
            case 'sqlite':
                return "LENGTH({$column})";
            default:
                return "LENGTH({$column})";
        }
    }
    
    /**
     * Database-agnostic UPPER function
     * 
     * @param string $column Column name
     * @return string SQL UPPER expression
     */
    public function upper($column)
    {
        $column = $this->quoteIdentifier($column);
        return "UPPER({$column})";
    }
    
    /**
     * Database-agnostic LOWER function
     * 
     * @param string $column Column name
     * @return string SQL LOWER expression
     */
    public function lower($column)
    {
        $column = $this->quoteIdentifier($column);
        return "LOWER({$column})";
    }
    
    /**
     * Get current driver type
     * 
     * @return string
     */
    public function getDriverType()
    {
        return $this->driverType;
    }
    
    /**
     * Set driver type
     * 
     * @param string $driverType
     * @return self
     */
    public function setDriverType($driverType)
    {
        $this->driverType = strtolower($driverType);
        return $this;
    }
}