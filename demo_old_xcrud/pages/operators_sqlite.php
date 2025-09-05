<?php
// Note: This demo assumes SQLite database is configured
// You may need to adjust the connection settings

$xcrud = Xcrud::get_instance();
// For SQLite demo, we'll use a different connection if configured
// $xcrud->connection('sqlite_user', 'sqlite_pass', 'path/to/database.db', '', 'utf8', 'sqlite');
$xcrud->table('employees');
$xcrud->table_name('SQLite Operators Test - Employees Table');

// Get the selected operator from URL parameter
$operator = isset($_GET['op']) ? $_GET['op'] : 'basic';

?>

<style>
.operator-menu {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
}
.operator-menu h4 {
    margin-top: 0;
    color: #333;
}
.operator-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
.operator-btn {
    padding: 8px 15px;
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    text-decoration: none;
    color: #333;
    transition: all 0.3s;
}
.operator-btn:hover {
    background: #17a2b8;
    color: white;
    border-color: #17a2b8;
}
.operator-btn.active {
    background: #6c757d;
    color: white;
    border-color: #6c757d;
}
.operator-info {
    background: #f0f4f7;
    padding: 15px;
    border-left: 4px solid #6c757d;
    margin-bottom: 20px;
}
.operator-info h5 {
    margin-top: 0;
    color: #495057;
}
.code-example {
    background: #f4f4f4;
    padding: 10px;
    border-radius: 3px;
    font-family: 'Courier New', monospace;
    margin: 10px 0;
}
.warning-box {
    background: #fff3cd;
    border-left: 4px solid #ffc107;
    padding: 10px;
    margin: 10px 0;
}
.success-box {
    background: #d4edda;
    border-left: 4px solid #28a745;
    padding: 10px;
    margin: 10px 0;
}
</style>

<div class="operator-menu">
    <h4>🔍 SQLite Operator Tests</h4>
    <div class="operator-buttons">
        <a href="?page=operators_sqlite&op=basic" class="operator-btn <?php echo $operator == 'basic' ? 'active' : ''; ?>">Basic Comparisons</a>
        <a href="?page=operators_sqlite&op=set" class="operator-btn <?php echo $operator == 'set' ? 'active' : ''; ?>">IN / NOT IN</a>
        <a href="?page=operators_sqlite&op=range" class="operator-btn <?php echo $operator == 'range' ? 'active' : ''; ?>">BETWEEN</a>
        <a href="?page=operators_sqlite&op=null" class="operator-btn <?php echo $operator == 'null' ? 'active' : ''; ?>">NULL Handling</a>
        <a href="?page=operators_sqlite&op=like" class="operator-btn <?php echo $operator == 'like' ? 'active' : ''; ?>">LIKE Patterns</a>
        <a href="?page=operators_sqlite&op=ilike" class="operator-btn <?php echo $operator == 'ilike' ? 'active' : ''; ?>">ILIKE Emulation</a>
        <a href="?page=operators_sqlite&op=regex" class="operator-btn <?php echo $operator == 'regex' ? 'active' : ''; ?>">REGEXP Fallback</a>
        <a href="?page=operators_sqlite&op=combined" class="operator-btn <?php echo $operator == 'combined' ? 'active' : ''; ?>">Combined Queries</a>
        <a href="?page=operators_sqlite&op=none" class="operator-btn <?php echo $operator == 'none' ? 'active' : ''; ?>">No Filter</a>
    </div>
</div>

<?php

// Reset instance for fresh query
$xcrud = Xcrud::get_instance();
$xcrud->table('employees');
$xcrud->columns('employeeNumber,firstName,lastName,email,jobTitle,officeCode,reportsTo,extension');
$xcrud->limit(20);

switch($operator) {
    case 'basic':
        echo '<div class="operator-info">';
        echo '<h5>Basic Comparison Operators</h5>';
        echo '<p>SQLite supports all standard SQL comparison operators.</p>';
        echo '<div class="code-example">$xcrud->where(\'officeCode\', \'1\');<br>';
        echo '$xcrud->where(\'employeeNumber >\', 1100);<br>';
        echo '$xcrud->where(\'reportsTo <=\', 1143);</div>';
        echo '</div>';
        
        $xcrud->where('officeCode', '1');
        $xcrud->where('employeeNumber >', 1100);
        $xcrud->where('reportsTo <=', 1143);
        break;
        
    case 'set':
        echo '<div class="operator-info">';
        echo '<h5>IN / NOT IN Operators</h5>';
        echo '<p>SQLite has full support for IN and NOT IN operators.</p>';
        echo '<div class="code-example">$xcrud->where(\'officeCode IN\', [\'1\', \'2\', \'3\']);<br>';
        echo '$xcrud->where(\'jobTitle NOT IN\', [\'President\', \'VP Sales\']);</div>';
        echo '</div>';
        
        $xcrud->where('officeCode IN', ['1', '2', '3']);
        $xcrud->where('jobTitle NOT IN', ['President', 'VP Sales']);
        break;
        
    case 'range':
        echo '<div class="operator-info">';
        echo '<h5>BETWEEN / NOT BETWEEN Operators</h5>';
        echo '<p>SQLite supports BETWEEN for range queries.</p>';
        echo '<div class="code-example">$xcrud->where(\'employeeNumber BETWEEN\', [1100, 1200]);</div>';
        echo '</div>';
        
        $xcrud->where('employeeNumber BETWEEN', [1100, 1200]);
        break;
        
    case 'null':
        echo '<div class="operator-info">';
        echo '<h5>IS NULL / IS NOT NULL Operators</h5>';
        echo '<p>SQLite handles NULL values according to SQL standard.</p>';
        echo '<div class="code-example">$xcrud->where(\'reportsTo IS NOT NULL\', \'\');</div>';
        echo '</div>';
        
        $xcrud->where('reportsTo IS NOT NULL', '');
        break;
        
    case 'like':
        echo '<div class="operator-info">';
        echo '<h5>LIKE / NOT LIKE Pattern Matching</h5>';
        echo '<p>SQLite LIKE is case-insensitive by default (unlike MySQL/PostgreSQL).</p>';
        echo '<div class="code-example">$xcrud->where(\'firstName LIKE\', \'%a%\'); // Contains \'a\' (case-insensitive)<br>';
        echo '$xcrud->where(\'email NOT LIKE\', \'%@gmail%\');</div>';
        echo '<div class="success-box">✓ SQLite LIKE is case-insensitive by default</div>';
        echo '</div>';
        
        $xcrud->where('firstName LIKE', '%a%');
        $xcrud->where('email NOT LIKE', '%@gmail%');
        break;
        
    case 'ilike':
        echo '<div class="operator-info">';
        echo '<h5>ILIKE Emulation (PostgreSQL operator)</h5>';
        echo '<p>ILIKE is PostgreSQL-specific. xCrudRevolution emulates it using LOWER() function.</p>';
        echo '<div class="code-example">$xcrud->where(\'firstName ILIKE\', \'%mary%\');<br>';
        echo '// Internally converted to: LOWER(firstName) LIKE LOWER(\'%mary%\')</div>';
        echo '<div class="warning-box">⚠️ ILIKE is emulated in SQLite using LOWER() function</div>';
        echo '</div>';
        
        $xcrud->where('firstName ILIKE', '%mary%');
        break;
        
    case 'regex':
        echo '<div class="operator-info">';
        echo '<h5>REGEXP Support / Fallback</h5>';
        echo '<p>SQLite doesn\'t have built-in REGEXP. It requires loading an extension.</p>';
        echo '<div class="code-example">$xcrud->where(\'email REGEXP\', \'[a-z]+@\');<br>';
        echo '// If REGEXP extension not loaded, falls back to LIKE</div>';
        echo '<div class="warning-box">⚠️ REGEXP requires SQLite extension. Without it, xCrudRevolution falls back to LIKE pattern matching.</div>';
        echo '</div>';
        
        // This will attempt REGEXP if available, otherwise fall back to LIKE
        $xcrud->where('email REGEXP', '^[a-z]+');
        break;
        
    case 'combined':
        echo '<div class="operator-info">';
        echo '<h5>Combined Complex Query</h5>';
        echo '<p>Testing multiple SQLite-compatible operators together.</p>';
        echo '<div class="code-example">$xcrud->where(\'officeCode IN\', [\'1\', \'2\']);<br>';
        echo '$xcrud->where(\'employeeNumber BETWEEN\', [1100, 1300]);<br>';
        echo '$xcrud->where(\'lastName LIKE\', \'%son\'); // Case-insensitive in SQLite<br>';
        echo '$xcrud->where(\'reportsTo IS NOT NULL\', \'\');</div>';
        echo '</div>';
        
        $xcrud->where('officeCode IN', ['1', '2']);
        $xcrud->where('employeeNumber BETWEEN', [1100, 1300]);
        $xcrud->where('lastName LIKE', '%son');
        $xcrud->where('reportsTo IS NOT NULL', '');
        break;
        
    case 'none':
    default:
        echo '<div class="operator-info">';
        echo '<h5>No Filter - All Data</h5>';
        echo '<p>Showing all employees without any WHERE conditions.</p>';
        echo '</div>';
        break;
}

echo $xcrud->render();
?>

<div class="operator-info" style="background: #e7f3ff; border-color: #17a2b8; margin-top: 20px;">
    <h5>💡 SQLite Database Characteristics</h5>
    <ul style="margin-bottom: 0;">
        <li><strong>LIKE:</strong> Case-insensitive by default (different from MySQL/PostgreSQL)</li>
        <li><strong>GLOB:</strong> Case-sensitive pattern matching (SQLite-specific)</li>
        <li><strong>REGEXP:</strong> Requires loading extension (not available by default)</li>
        <li><strong>ILIKE:</strong> Not supported (emulated using LOWER())</li>
        <li><strong>Type Affinity:</strong> Dynamic typing system (different from strict typing)</li>
        <li><strong>Collation:</strong> NOCASE collation for case-insensitive comparisons</li>
    </ul>
</div>

<div class="warning-box" style="margin-top: 20px;">
    <h5>⚠️ SQLite Limitations & Workarounds</h5>
    <ul style="margin-bottom: 0;">
        <li><strong>No REGEXP by default:</strong> Falls back to LIKE patterns</li>
        <li><strong>No ILIKE:</strong> Emulated with LOWER(column) LIKE LOWER(value)</li>
        <li><strong>No full-text search operators:</strong> Requires FTS extension</li>
        <li><strong>Limited ALTER TABLE:</strong> Some schema changes require table recreation</li>
    </ul>
</div>