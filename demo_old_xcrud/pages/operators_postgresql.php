<?php
// Note: This demo assumes PostgreSQL database is configured
// You may need to adjust the connection settings

$xcrud = Xcrud::get_instance();
// For PostgreSQL demo, configure connection if needed
// $xcrud->connection('pg_user', 'pg_pass', 'pg_database', 'localhost', 'utf8', 'postgresql');
$xcrud->table('employees');
$xcrud->table_name('PostgreSQL Operators Test - Employees Table');

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
    background: #336699;
    color: white;
    border-color: #336699;
}
.operator-btn.active {
    background: #17a2b8;
    color: white;
    border-color: #17a2b8;
}
.operator-info {
    background: #e6f2ff;
    padding: 15px;
    border-left: 4px solid #336699;
    margin-bottom: 20px;
}
.operator-info h5 {
    margin-top: 0;
    color: #003366;
}
.code-example {
    background: #f4f4f4;
    padding: 10px;
    border-radius: 3px;
    font-family: 'Courier New', monospace;
    margin: 10px 0;
}
.postgres-special {
    background: #d4edda;
    border-left: 4px solid #28a745;
    padding: 10px;
    margin: 10px 0;
}
.warning-box {
    background: #fff3cd;
    border-left: 4px solid #ffc107;
    padding: 10px;
    margin: 10px 0;
}
</style>

<div class="operator-menu">
    <h4>🐘 PostgreSQL Operator Tests (Advanced Features)</h4>
    <div class="operator-buttons">
        <a href="?page=operators_postgresql&op=basic" class="operator-btn <?php echo $operator == 'basic' ? 'active' : ''; ?>">Basic Comparisons</a>
        <a href="?page=operators_postgresql&op=set" class="operator-btn <?php echo $operator == 'set' ? 'active' : ''; ?>">IN / NOT IN</a>
        <a href="?page=operators_postgresql&op=range" class="operator-btn <?php echo $operator == 'range' ? 'active' : ''; ?>">BETWEEN</a>
        <a href="?page=operators_postgresql&op=null" class="operator-btn <?php echo $operator == 'null' ? 'active' : ''; ?>">NULL Handling</a>
        <a href="?page=operators_postgresql&op=like" class="operator-btn <?php echo $operator == 'like' ? 'active' : ''; ?>">LIKE (Case-Sensitive)</a>
        <a href="?page=operators_postgresql&op=ilike" class="operator-btn <?php echo $operator == 'ilike' ? 'active' : ''; ?>">ILIKE (PostgreSQL)</a>
        <a href="?page=operators_postgresql&op=regex" class="operator-btn <?php echo $operator == 'regex' ? 'active' : ''; ?>">~ Regex Operators</a>
        <a href="?page=operators_postgresql&op=similar" class="operator-btn <?php echo $operator == 'similar' ? 'active' : ''; ?>">SIMILAR TO</a>
        <a href="?page=operators_postgresql&op=combined" class="operator-btn <?php echo $operator == 'combined' ? 'active' : ''; ?>">Combined Query</a>
        <a href="?page=operators_postgresql&op=none" class="operator-btn <?php echo $operator == 'none' ? 'active' : ''; ?>">No Filter</a>
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
        echo '<p>PostgreSQL supports all standard SQL operators with strict typing.</p>';
        echo '<div class="code-example">$xcrud->where(\'officeCode\', \'1\');<br>';
        echo '$xcrud->where(\'employeeNumber >\', 1100);<br>';
        echo '$xcrud->where(\'reportsTo <>\', 1002); // PostgreSQL prefers <> over !=</div>';
        echo '</div>';
        
        $xcrud->where('officeCode', '1');
        $xcrud->where('employeeNumber >', 1100);
        $xcrud->where('reportsTo <>', 1002);
        break;
        
    case 'set':
        echo '<div class="operator-info">';
        echo '<h5>IN / NOT IN Operators with Arrays</h5>';
        echo '<p>PostgreSQL has excellent array support and can use ANY/ALL operators too.</p>';
        echo '<div class="code-example">$xcrud->where(\'officeCode IN\', [\'1\', \'2\', \'3\']);<br>';
        echo '$xcrud->where(\'jobTitle NOT IN\', [\'President\', \'VP Sales\']);</div>';
        echo '<div class="postgres-special">✨ PostgreSQL also supports: = ANY(array), <> ALL(array)</div>';
        echo '</div>';
        
        $xcrud->where('officeCode IN', ['1', '2', '3']);
        $xcrud->where('jobTitle NOT IN', ['President', 'VP Sales']);
        break;
        
    case 'range':
        echo '<div class="operator-info">';
        echo '<h5>BETWEEN / NOT BETWEEN Operators</h5>';
        echo '<p>PostgreSQL BETWEEN is inclusive of both boundaries.</p>';
        echo '<div class="code-example">$xcrud->where(\'employeeNumber BETWEEN\', [1100, 1200]);<br>';
        echo '// Equivalent to: employeeNumber >= 1100 AND employeeNumber <= 1200</div>';
        echo '</div>';
        
        $xcrud->where('employeeNumber BETWEEN', [1100, 1200]);
        break;
        
    case 'null':
        echo '<div class="operator-info">';
        echo '<h5>IS NULL / IS NOT NULL / IS DISTINCT FROM</h5>';
        echo '<p>PostgreSQL has advanced NULL handling including IS DISTINCT FROM.</p>';
        echo '<div class="code-example">$xcrud->where(\'reportsTo IS NOT NULL\', \'\');</div>';
        echo '<div class="postgres-special">✨ PostgreSQL unique: IS DISTINCT FROM handles NULL-safe comparisons</div>';
        echo '</div>';
        
        $xcrud->where('reportsTo IS NOT NULL', '');
        break;
        
    case 'like':
        echo '<div class="operator-info">';
        echo '<h5>LIKE Pattern Matching (Case-Sensitive)</h5>';
        echo '<p>PostgreSQL LIKE is case-sensitive by default, unlike MySQL/SQLite.</p>';
        echo '<div class="code-example">$xcrud->where(\'firstName LIKE\', \'L%\'); // Must start with capital L<br>';
        echo '$xcrud->where(\'email LIKE\', \'%@classicmodelcars.com\');<br>';
        echo '$xcrud->where(\'lastName NOT LIKE\', \'%son\');</div>';
        echo '<div class="warning-box">⚠️ LIKE is case-sensitive in PostgreSQL!</div>';
        echo '</div>';
        
        $xcrud->where('firstName LIKE', 'L%');
        $xcrud->where('email LIKE', '%@classicmodelcars.com');
        $xcrud->where('lastName NOT LIKE', '%son');
        break;
        
    case 'ilike':
        echo '<div class="operator-info">';
        echo '<h5>ILIKE - PostgreSQL Case-Insensitive Pattern Matching</h5>';
        echo '<p>ILIKE is PostgreSQL-specific for case-insensitive pattern matching.</p>';
        echo '<div class="code-example">$xcrud->where(\'firstName ILIKE\', \'%mary%\'); // Matches Mary, MARY, mary, etc.<br>';
        echo '$xcrud->where(\'email ILIKE\', \'L%@%\'); // Case-insensitive email pattern<br>';
        echo '$xcrud->where(\'lastName NOT ILIKE\', \'%SON\'); // Case-insensitive NOT</div>';
        echo '<div class="postgres-special">✨ ILIKE is native to PostgreSQL - no emulation needed!</div>';
        echo '</div>';
        
        $xcrud->where('firstName ILIKE', '%mary%');
        $xcrud->where('email ILIKE', 'L%@%');
        $xcrud->where('lastName NOT ILIKE', '%SON');
        break;
        
    case 'regex':
        echo '<div class="operator-info">';
        echo '<h5>PostgreSQL Regex Operators (~, ~*, !~, !~*)</h5>';
        echo '<p>PostgreSQL has powerful regex operators not found in other databases.</p>';
        echo '<div class="code-example">$xcrud->where(\'email ~\', \'^[a-z]+@\'); // Case-sensitive regex<br>';
        echo '$xcrud->where(\'firstName ~*\', \'mary\'); // Case-insensitive regex<br>';
        echo '$xcrud->where(\'extension !~\', \'[a-z]\'); // Negative regex (no lowercase)<br>';
        echo '$xcrud->where(\'lastName !~*\', \'admin\'); // Negative case-insensitive</div>';
        echo '<div class="postgres-special">✨ PostgreSQL regex operators:<br>';
        echo '• ~ : matches regex (case-sensitive)<br>';
        echo '• ~* : matches regex (case-insensitive)<br>';
        echo '• !~ : does not match regex (case-sensitive)<br>';
        echo '• !~* : does not match regex (case-insensitive)</div>';
        echo '</div>';
        
        $xcrud->where('email ~', '^[a-z]+@');
        $xcrud->where('firstName ~*', 'mary');
        $xcrud->where('extension !~', '[a-z]');
        break;
        
    case 'similar':
        echo '<div class="operator-info">';
        echo '<h5>SIMILAR TO - SQL Standard Pattern Matching</h5>';
        echo '<p>SIMILAR TO combines LIKE simplicity with regex power (PostgreSQL only).</p>';
        echo '<div class="code-example">$xcrud->where(\'extension SIMILAR TO\', \'x[0-9]{4}\'); // Format: x####<br>';
        echo '$xcrud->where(\'email SIMILAR TO\', \'%(@gmail|@yahoo|@hotmail).com\');<br>';
        echo '$xcrud->where(\'jobTitle NOT SIMILAR TO\', \'%(Manager|President)%\');</div>';
        echo '<div class="postgres-special">✨ SIMILAR TO uses SQL standard regex syntax:<br>';
        echo '• % = any string (like LIKE)<br>';
        echo '• _ = any character (like LIKE)<br>';
        echo '• [] = character class (like regex)<br>';
        echo '• {} = repetition (like regex)<br>';
        echo '• | = alternation (like regex)</div>';
        echo '</div>';
        
        $xcrud->where('extension SIMILAR TO', 'x[0-9]{4}');
        $xcrud->where('email SIMILAR TO', '%(@gmail|@yahoo|@hotmail).com');
        break;
        
    case 'combined':
        echo '<div class="operator-info">';
        echo '<h5>Combined PostgreSQL-Specific Query</h5>';
        echo '<p>Demonstrating PostgreSQL\'s unique operators together.</p>';
        echo '<div class="code-example">$xcrud->where(\'officeCode IN\', [\'1\', \'2\', \'3\']);<br>';
        echo '$xcrud->where(\'firstName ILIKE\', \'%a%\'); // Case-insensitive<br>';
        echo '$xcrud->where(\'email ~\', \'@classicmodelcars\\\\.com$\'); // Regex<br>';
        echo '$xcrud->where(\'extension SIMILAR TO\', \'x[0-9]+\'); // SQL pattern<br>';
        echo '$xcrud->where(\'reportsTo IS NOT NULL\', \'\');</div>';
        echo '</div>';
        
        $xcrud->where('officeCode IN', ['1', '2', '3']);
        $xcrud->where('firstName ILIKE', '%a%');
        $xcrud->where('email ~', '@classicmodelcars\\.com$');
        $xcrud->where('extension SIMILAR TO', 'x[0-9]+');
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

<div class="operator-info" style="background: #e6f7ff; border-color: #336699; margin-top: 20px;">
    <h5>🐘 PostgreSQL Advanced Features</h5>
    <ul style="margin-bottom: 0;">
        <li><strong>ILIKE:</strong> Native case-insensitive pattern matching</li>
        <li><strong>Regex operators (~, ~*, !~, !~*):</strong> Powerful pattern matching</li>
        <li><strong>SIMILAR TO:</strong> SQL standard regex patterns</li>
        <li><strong>Array operators:</strong> ANY, ALL, array contains @></li>
        <li><strong>JSON operators:</strong> ->, ->>, @>, ? (for JSONB columns)</li>
        <li><strong>Full-text search:</strong> @@ operator with tsvector</li>
        <li><strong>Range types:</strong> @>, <@, &&, << , >> operators</li>
    </ul>
</div>

<div class="postgres-special" style="margin-top: 20px;">
    <h5>✨ PostgreSQL-Exclusive Operators in xCrudRevolution</h5>
    <p>These operators work natively in PostgreSQL and are automatically converted or emulated for other databases:</p>
    <ul style="margin-bottom: 0;">
        <li><code>ILIKE / NOT ILIKE</code> - Emulated with LOWER() in MySQL/SQLite</li>
        <li><code>~ / !~</code> - Converted to REGEXP/NOT REGEXP in MySQL</li>
        <li><code>~* / !~*</code> - Case-insensitive regex (emulated in MySQL)</li>
        <li><code>SIMILAR TO</code> - Falls back to LIKE in other databases</li>
    </ul>
</div>