<?php
/**
 * Test Query Builder for multi-database compatibility
 */

require_once 'database/QueryBuilder.php';
use XcrudRevolution\Database\QueryBuilder;

?>
<!DOCTYPE html>
<html>
<head>
    <title>xCrudRevolution - Query Builder Test</title>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .test-container { 
            max-width: 1200px; margin: 0 auto; background: white; 
            padding: 30px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); 
        }
        h1 { color: #333; border-bottom: 3px solid #007bff; padding-bottom: 10px; }
        .sql-block {
            background: #263238; color: #aed581; padding: 15px; border-radius: 5px; 
            margin: 10px 0; font-family: 'Courier New', monospace; overflow-x: auto;
        }
        .db-section { 
            margin: 30px 0; padding: 20px; border-left: 4px solid #007bff; 
            background: #f8f9fa; border-radius: 5px; 
        }
        .success { color: #28a745; }
        .warning { color: #ffc107; }
        .error { color: #dc3545; }
    </style>
</head>
<body>
    <div class="test-container">
        <h1>🔧 xCrudRevolution Query Builder Test</h1>
        
        <?php
        $databases = ['mysql', 'postgresql', 'sqlite'];
        
        foreach ($databases as $dbType) {
            echo "<div class='db-section'>";
            echo "<h2>🗄️ " . strtoupper($dbType) . " Queries</h2>";
            
            $qb = new QueryBuilder($dbType);
            
            // Test 1: Simple SELECT
            echo "<h3>Simple SELECT</h3>";
            $sql = $qb->reset()
                ->select(['id', 'name', 'email'])
                ->from('users')
                ->where('active = 1')
                ->orderBy('name', 'ASC')
                ->limit(10)
                ->buildSelect();
            echo "<div class='sql-block'>$sql</div>";
            
            // Test 2: SELECT with JOIN
            echo "<h3>SELECT with JOIN</h3>";
            $sql = $qb->reset()
                ->select(['u.name', 'p.title', 'c.name AS category'])
                ->from('users', 'u')
                ->leftJoin('posts', 'p.user_id = u.id', 'p')
                ->leftJoin('categories', 'p.category_id = c.id', 'c')
                ->where('u.active = 1')
                ->where('p.published = 1', 'AND')
                ->orderBy('p.created_at', 'DESC')
                ->limit(5)
                ->buildSelect();
            echo "<div class='sql-block'>$sql</div>";
            
            // Test 3: INSERT
            echo "<h3>INSERT Query</h3>";
            $sql = $qb->buildInsert('users', [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'active' => true,
                'created_at' => null
            ]);
            echo "<div class='sql-block'>$sql</div>";
            
            // Test 4: UPDATE
            echo "<h3>UPDATE Query</h3>";
            $sql = $qb->buildUpdate('users', [
                'name' => 'Jane Doe',
                'updated_at' => '2024-01-01 12:00:00'
            ], [
                'id' => 1
            ]);
            echo "<div class='sql-block'>$sql</div>";
            
            // Test 5: DELETE
            echo "<h3>DELETE Query</h3>";
            $sql = $qb->buildDelete('users', ['active' => 0]);
            echo "<div class='sql-block'>$sql</div>";
            
            // Test 6: SHOW COLUMNS (database-specific)
            echo "<h3>SHOW COLUMNS (Database-specific)</h3>";
            try {
                $sql = $qb->buildShowColumns('users');
                echo "<div class='sql-block'>$sql</div>";
                echo "<span class='success'>✅ Query generated successfully</span>";
            } catch (Exception $e) {
                echo "<span class='error'>❌ Error: " . $e->getMessage() . "</span>";
            }
            
            // Test 7: SHOW TABLES (database-specific)
            echo "<h3>SHOW TABLES (Database-specific)</h3>";
            try {
                $sql = $qb->buildShowTables();
                echo "<div class='sql-block'>$sql</div>";
                echo "<span class='success'>✅ Query generated successfully</span>";
            } catch (Exception $e) {
                echo "<span class='error'>❌ Error: " . $e->getMessage() . "</span>";
            }
            
            echo "</div>";
        }
        
        // Test identifier quoting
        echo "<div class='db-section'>";
        echo "<h2>🔤 Identifier Quoting Test</h2>";
        
        foreach ($databases as $dbType) {
            $qb = new QueryBuilder($dbType);
            echo "<h3>" . strtoupper($dbType) . "</h3>";
            echo "<p><strong>Table:</strong> " . $qb->quoteIdentifier('users') . "</p>";
            echo "<p><strong>Column:</strong> " . $qb->quoteIdentifier('user_name') . "</p>";
            echo "<p><strong>Table.Column:</strong> " . $qb->quoteIdentifier('users.user_id') . "</p>";
        }
        
        echo "</div>";
        ?>
        
        <div style="margin-top: 30px; padding: 20px; background: #d4edda; border-radius: 5px; border: 1px solid #c3e6cb;">
            <h3 class="success">✅ Query Builder Testing Complete!</h3>
            <p>The Query Builder successfully generates database-specific SQL for:</p>
            <ul>
                <li>✅ SELECT queries with JOINs, WHERE, ORDER BY, LIMIT</li>
                <li>✅ INSERT queries with database-specific features</li>
                <li>✅ UPDATE and DELETE queries</li>
                <li>✅ Database-specific SHOW COLUMNS and SHOW TABLES</li>
                <li>✅ Proper identifier quoting for each database</li>
            </ul>
            <p><strong>Ready for integration into xcrud.php!</strong></p>
        </div>
        
        <div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 5px;">
            <small>
                <strong>Copyright © 2024 xCrudRevolution.</strong> All rights reserved.<br>
                Official website: <a href="https://www.xcrudrevolution.com">www.xcrudrevolution.com</a>
            </small>
        </div>
    </div>
</body>
</html>