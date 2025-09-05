# xCrudRevolution - Advanced SQL Operators Documentation

## Overview
xCrudRevolution now supports a comprehensive set of SQL operators for advanced querying capabilities, making it compatible with MySQL, PostgreSQL, and SQLite.

## Supported Operators

### Basic Comparison Operators
| Operator | Description | Example Usage |
|----------|-------------|---------------|
| `=` | Equals | `$xcrud->where('age =', 25)` |
| `!=` or `<>` | Not equals | `$xcrud->where('status !=', 'inactive')` |
| `>` | Greater than | `$xcrud->where('price >', 100)` |
| `<` | Less than | `$xcrud->where('quantity <', 10)` |
| `>=` | Greater than or equal | `$xcrud->where('score >=', 80)` |
| `<=` | Less than or equal | `$xcrud->where('date <=', '2025-01-01')` |

### String Pattern Operators
| Operator | Description | Example Usage |
|----------|-------------|---------------|
| `^=` | Starts with | `$xcrud->where('name ^=', 'John')` |
| `$=` | Ends with | `$xcrud->where('email $=', '@gmail.com')` |
| `~=` | Contains | `$xcrud->where('description ~=', 'important')` |

### SQL Set Operators
| Operator | Description | Example Usage |
|----------|-------------|---------------|
| `IN` | Value in set | `$xcrud->where('category IN', array('A', 'B', 'C'))` |
| `NOT IN` | Value not in set | `$xcrud->where('status NOT IN', array('deleted', 'archived'))` |

### Range Operators
| Operator | Description | Example Usage |
|----------|-------------|---------------|
| `BETWEEN` | Value between two values | `$xcrud->where('age BETWEEN', array(18, 65))` |
| `NOT BETWEEN` | Value not between two values | `$xcrud->where('price NOT BETWEEN', array(10, 50))` |

### NULL Handling Operators
| Operator | Description | Example Usage |
|----------|-------------|---------------|
| `IS NULL` | Check for NULL value | `$xcrud->where('deleted_at IS NULL', '')` |
| `IS NOT NULL` | Check for non-NULL value | `$xcrud->where('email IS NOT NULL', '')` |

### Pattern Matching Operators
| Operator | Description | Example Usage |
|----------|-------------|---------------|
| `LIKE` | SQL LIKE pattern | `$xcrud->where('name LIKE', '%John%')` |
| `NOT LIKE` | Negated LIKE pattern | `$xcrud->where('email NOT LIKE', '%@test.com')` |
| `ILIKE` | Case-insensitive LIKE (PostgreSQL) | `$xcrud->where('title ILIKE', '%important%')` |
| `NOT ILIKE` | Negated case-insensitive LIKE | `$xcrud->where('name NOT ILIKE', 'admin%')` |

### Regular Expression Operators
| Operator | Description | Example Usage | Database Support |
|----------|-------------|---------------|------------------|
| `REGEXP` or `RLIKE` | Regular expression match | `$xcrud->where('phone REGEXP', '^[0-9]{10}$')` | MySQL, PostgreSQL |
| `NOT REGEXP` | Negated regex match | `$xcrud->where('code NOT REGEXP', '[A-Z]{3}')` | MySQL, PostgreSQL |

### Advanced Operators
| Operator | Description | Example Usage |
|----------|-------------|---------------|
| `EXISTS` | Subquery exists | `$xcrud->where('EXISTS', 'SELECT 1 FROM orders WHERE orders.user_id = users.id')` |
| `NOT EXISTS` | Subquery doesn't exist | `$xcrud->where('NOT EXISTS', 'SELECT 1 FROM payments WHERE payments.order_id = orders.id')` |

## Usage Examples

### Basic Examples
```php
// Simple equality
$xcrud->where('status', 'active');
$xcrud->where('status =', 'active'); // Explicit operator

// Not equal
$xcrud->where('status !=', 'deleted');

// Numeric comparisons
$xcrud->where('age >', 18);
$xcrud->where('price <=', 100);
```

### IN Operator Examples
```php
// Using IN with array
$xcrud->where('category IN', array('Electronics', 'Books', 'Music'));

// Using NOT IN
$xcrud->where('status NOT IN', array('deleted', 'archived', 'suspended'));

// Backwards compatibility - array automatically converts to IN
$xcrud->where('id', array(1, 2, 3, 4, 5)); // Becomes: id IN (1,2,3,4,5)
```

### BETWEEN Examples
```php
// Numeric range
$xcrud->where('age BETWEEN', array(18, 65));

// Date range
$xcrud->where('created_at BETWEEN', array('2024-01-01', '2024-12-31'));

// NOT BETWEEN
$xcrud->where('price NOT BETWEEN', array(10, 100));
```

### NULL Handling Examples
```php
// Find records with NULL values
$xcrud->where('deleted_at IS NULL', '');  // Note: value is ignored for IS NULL

// Find records without NULL values
$xcrud->where('email IS NOT NULL', '');
```

### Pattern Matching Examples
```php
// LIKE patterns (universal)
$xcrud->where('name LIKE', 'John%');      // Starts with John
$xcrud->where('email LIKE', '%@gmail.com'); // Ends with @gmail.com
$xcrud->where('description LIKE', '%important%'); // Contains 'important'

// ILIKE - PostgreSQL native case-insensitive
// (automatically emulated with LOWER() for MySQL/SQLite)
$xcrud->where('title ILIKE', '%NEWS%');     // PostgreSQL: native ILIKE
                                            // MySQL/SQLite: LOWER(title) LIKE LOWER('%NEWS%')

// NOT LIKE
$xcrud->where('email NOT LIKE', '%@temp%');
$xcrud->where('name NOT ILIKE', 'admin%');  // Case-insensitive NOT LIKE
```

### Regular Expression Examples
```php
// MySQL style (works on MySQL, converted for PostgreSQL)
$xcrud->where('phone REGEXP', '^\\+?[0-9]{10,}$');
$xcrud->where('email RLIKE', '@(gmail|yahoo|outlook)\\.com$');

// PostgreSQL native operators (PostgreSQL only)
$xcrud->where('phone ~', '^\\+?[0-9]{10,}$');        // Case-sensitive regex
$xcrud->where('name ~*', 'john');                    // Case-insensitive regex
$xcrud->where('code !~', '[^A-Z0-9]');              // Negative regex
$xcrud->where('email !~*', '@tempmail');            // Negative case-insensitive

// PostgreSQL SIMILAR TO (SQL standard)
$xcrud->where('code SIMILAR TO', '[A-Z]{3}-[0-9]{4}');

// NOT REGEXP
$xcrud->where('username NOT REGEXP', '[^a-zA-Z0-9_]');
```

### Complex Queries
```php
// Combine multiple conditions
$xcrud->where('status', 'active')
      ->where('age BETWEEN', array(18, 65))
      ->where('email IS NOT NULL', '')
      ->where('category IN', array('premium', 'gold'));

// Using OR conditions
$xcrud->where('status', 'active')
      ->or_where('role IN', array('admin', 'moderator'));
```

## Database Compatibility Matrix

| Operator | MySQL | PostgreSQL | SQLite | Notes |
|----------|-------|------------|--------|-------|
| **Basic Comparisons** |||||
| `=`, `!=`, `<>`, `>`, `<`, `>=`, `<=` | ✅ | ✅ | ✅ | Universal support |
| **Set Operations** |||||
| `IN`, `NOT IN` | ✅ | ✅ | ✅ | Universal support |
| **Range** |||||
| `BETWEEN`, `NOT BETWEEN` | ✅ | ✅ | ✅ | Universal support |
| **NULL Handling** |||||
| `IS NULL`, `IS NOT NULL` | ✅ | ✅ | ✅ | Universal support |
| **Pattern Matching** |||||
| `LIKE`, `NOT LIKE` | ✅ | ✅ | ✅ | Case sensitivity varies |
| `ILIKE`, `NOT ILIKE` | ❌ | ✅ | ❌ | PostgreSQL-only, emulated elsewhere with LOWER() |
| **Regular Expressions** |||||
| `REGEXP`, `RLIKE` | ✅ | ❌ | ⚠️ | MySQL native, SQLite needs extension |
| `~`, `!~` (regex) | ❌ | ✅ | ❌ | PostgreSQL-only operators |
| `~*`, `!~*` (case-insensitive) | ❌ | ✅ | ❌ | PostgreSQL-only operators |
| **PostgreSQL-Specific** |||||
| `SIMILAR TO` | ❌ | ✅ | ❌ | SQL standard regex, PostgreSQL only |
| **Subqueries** |||||
| `EXISTS`, `NOT EXISTS` | ✅ | ✅ | ✅ | Universal support |

### Database-Specific Notes

#### MySQL
- `LIKE` is case-insensitive by default for non-binary strings
- Native `REGEXP`/`RLIKE` support
- No native case-insensitive regex (use LOWER() workaround)

#### PostgreSQL
- `LIKE` is case-sensitive
- `ILIKE` for case-insensitive pattern matching (native)
- Rich regex support with `~`, `!~`, `~*`, `!~*` operators
- `SIMILAR TO` for SQL standard pattern matching
- Automatic conversion: `REGEXP` → `~`, `RLIKE` → `~`

#### SQLite
- `LIKE` is case-insensitive by default
- `REGEXP` requires loading extension (not available by default)
- Falls back to `LIKE` when `REGEXP` unavailable
- No native `ILIKE` (emulated with LOWER())

## Migration Guide

### From Old Syntax
```php
// Old way (still supported)
$xcrud->where('status', 'active');
$xcrud->where('age >', 25);

// New explicit way
$xcrud->where('status =', 'active');
$xcrud->where('age >', 25);

// New operators
$xcrud->where('tags LIKE', '%php%');
$xcrud->where('category IN', array('A', 'B'));
$xcrud->where('deleted_at IS NULL', '');
```

### Performance Considerations
1. **Indexed columns**: Operators like `=`, `IN`, and `BETWEEN` work best with indexes
2. **LIKE patterns**: Leading wildcards (`%text`) prevent index usage
3. **REGEXP**: Can be slow on large datasets without full-text indexes
4. **NULL checks**: `IS NULL` and `IS NOT NULL` are optimized in most databases

## Error Handling
Invalid operator usage will be logged and ignored:
- BETWEEN with non-array or wrong array size
- IS NULL/IS NOT NULL with meaningful values (values are ignored)
- EXISTS without valid subquery

## Future Enhancements
- JSON operators for modern databases
- Full-text search operators
- Spatial/geometry operators
- Custom operator plugins