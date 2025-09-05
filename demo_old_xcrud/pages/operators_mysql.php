<?php
$xcrud = Xcrud::get_instance();
$xcrud->table('employees');
$xcrud->table_name('MySQL Operators Test - Employees Table');

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
    background: #007bff;
    color: white;
    border-color: #007bff;
}
.operator-btn.active {
    background: #28a745;
    color: white;
    border-color: #28a745;
}
.operator-info {
    background: #e8f4f8;
    padding: 15px;
    border-left: 4px solid #007bff;
    margin-bottom: 20px;
}
.operator-info h5 {
    margin-top: 0;
    color: #0056b3;
}
.code-example {
    background: #f4f4f4;
    padding: 10px;
    border-radius: 3px;
    font-family: 'Courier New', monospace;
    margin: 10px 0;
}
</style>

<div class="operator-menu">
    <h4>🔍 Select Operator Category to Test</h4>
    <div class="operator-buttons">
        <a href="?page=operators_mysql&op=basic" class="operator-btn <?php echo $operator == 'basic' ? 'active' : ''; ?>">Basic Comparisons</a>
        <a href="?page=operators_mysql&op=set" class="operator-btn <?php echo $operator == 'set' ? 'active' : ''; ?>">IN / NOT IN</a>
        <a href="?page=operators_mysql&op=range" class="operator-btn <?php echo $operator == 'range' ? 'active' : ''; ?>">BETWEEN</a>
        <a href="?page=operators_mysql&op=null" class="operator-btn <?php echo $operator == 'null' ? 'active' : ''; ?>">NULL Handling</a>
        <a href="?page=operators_mysql&op=like" class="operator-btn <?php echo $operator == 'like' ? 'active' : ''; ?>">LIKE Patterns</a>
        <a href="?page=operators_mysql&op=regex" class="operator-btn <?php echo $operator == 'regex' ? 'active' : ''; ?>">REGEXP</a>
        <a href="?page=operators_mysql&op=exists" class="operator-btn <?php echo $operator == 'exists' ? 'active' : ''; ?>">EXISTS</a>
        <a href="?page=operators_mysql&op=combined" class="operator-btn <?php echo $operator == 'combined' ? 'active' : ''; ?>">Combined Queries</a>
        <a href="?page=operators_mysql&op=none" class="operator-btn <?php echo $operator == 'none' ? 'active' : ''; ?>">No Filter (All Data)</a>
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
        echo '<h5>Basic Comparison Operators (=, !=, >, <, >=, <=)</h5>';
        echo '<p>Testing basic SQL comparison operators supported by all databases.</p>';
        echo '<div class="code-example">$xcrud->where(\'officeCode\', \'1\');<br>';
        echo '$xcrud->where(\'employeeNumber >\', 1100);<br>';
        echo '$xcrud->where(\'reportsTo !=\', 1002);</div>';
        echo '</div>';
        
        $xcrud->where('officeCode', '1');
        $xcrud->where('employeeNumber >', 1100);
        $xcrud->where('reportsTo !=', 1002);
        break;
        
    case 'set':
        echo '<div class="operator-info">';
        echo '<h5>IN / NOT IN Operators</h5>';
        echo '<p>Testing set membership operators for checking multiple values.</p>';
        echo '<div class="code-example">$xcrud->where(\'officeCode IN\', [\'1\', \'2\', \'3\']);<br>';
        echo '$xcrud->where(\'jobTitle NOT IN\', [\'President\', \'VP Sales\']);</div>';
        echo '</div>';
        
        $xcrud->where('officeCode IN', ['1', '2', '3']);
        $xcrud->where('jobTitle NOT IN', ['President', 'VP Sales']);
        break;
        
    case 'range':
        echo '<div class="operator-info">';
        echo '<h5>BETWEEN / NOT BETWEEN Operators</h5>';
        echo '<p>Testing range operators for numeric and string values.</p>';
        echo '<div class="code-example">$xcrud->where(\'employeeNumber BETWEEN\', [1100, 1200]);<br>';
        echo '$xcrud->where(\'extension NOT BETWEEN\', [\'x1000\', \'x2000\']);</div>';
        echo '</div>';
        
        $xcrud->where('employeeNumber BETWEEN', [1100, 1200]);
        $xcrud->where('extension NOT BETWEEN', ['x1000', 'x2000']);
        break;
        
    case 'null':
        echo '<div class="operator-info">';
        echo '<h5>IS NULL / IS NOT NULL Operators</h5>';
        echo '<p>Testing NULL value handling. Note: The value parameter is ignored for NULL operators.</p>';
        echo '<div class="code-example">$xcrud->where(\'reportsTo IS NOT NULL\', \'\');<br>';
        echo '// Shows only employees who have a manager</div>';
        echo '</div>';
        
        $xcrud->where('reportsTo IS NOT NULL', '');
        break;
        
    case 'like':
        echo '<div class="operator-info">';
        echo '<h5>LIKE / NOT LIKE Pattern Matching</h5>';
        echo '<p>Testing SQL pattern matching with % and _ wildcards.</p>';
        echo '<div class="code-example">$xcrud->where(\'firstName LIKE\', \'L%\'); // Starts with L<br>';
        echo '$xcrud->where(\'email LIKE\', \'%@classicmodelcars.com\');<br>';
        echo '$xcrud->where(\'lastName NOT LIKE\', \'%son\'); // Doesn\'t end with "son"</div>';
        echo '</div>';
        
        $xcrud->where('firstName LIKE', 'L%'); // Starts with L
        $xcrud->where('email LIKE', '%@classicmodelcars.com');
        $xcrud->where('lastName NOT LIKE', '%son');
        break;
        
    case 'regex':
        echo '<div class="operator-info">';
        echo '<h5>REGEXP / RLIKE Regular Expressions (MySQL Native)</h5>';
        echo '<p>Testing regular expression pattern matching - MySQL has native support.</p>';
        echo '<div class="code-example">$xcrud->where(\'email REGEXP\', \'^[a-z]+@\'); // Email starts with lowercase letters<br>';
        echo '$xcrud->where(\'extension REGEXP\', \'x[0-9]{4}$\'); // Extension format x####<br>';
        echo '$xcrud->where(\'firstName NOT REGEXP\', \'[0-9]\'); // No numbers in name</div>';
        echo '</div>';
        
        $xcrud->where('email REGEXP', '^[a-z]+@'); // Starts with lowercase letters
        $xcrud->where('extension REGEXP', 'x[0-9]{4}$'); // Format: x####
        $xcrud->where('firstName NOT REGEXP', '[0-9]'); // No numbers
        break;
        
    case 'exists':
        echo '<div class="operator-info">';
        echo '<h5>EXISTS / NOT EXISTS Subqueries</h5>';
        echo '<p>Testing subquery existence checks. Shows employees who have/don\'t have subordinates.</p>';
        echo '<div class="code-example">$xcrud->where(\'EXISTS\', \'SELECT 1 FROM employees e2 WHERE e2.reportsTo = employees.employeeNumber\');<br>';
        echo '// Shows only managers (employees who have people reporting to them)</div>';
        echo '</div>';
        
        // Show only managers (employees who have subordinates)
        $xcrud->where('EXISTS', 'SELECT 1 FROM employees e2 WHERE e2.reportsTo = employees.employeeNumber');
        break;
        
    case 'combined':
        echo '<div class="operator-info">';
        echo '<h5>Combined Complex Query</h5>';
        echo '<p>Testing multiple operators together in a single query.</p>';
        echo '<div class="code-example">$xcrud->where(\'officeCode IN\', [\'1\', \'2\', \'3\']);<br>';
        echo '$xcrud->where(\'employeeNumber BETWEEN\', [1100, 1500]);<br>';
        echo '$xcrud->where(\'email LIKE\', \'%@classicmodelcars.com\');<br>';
        echo '$xcrud->where(\'reportsTo IS NOT NULL\', \'\');<br>';
        echo '$xcrud->or_where(\'jobTitle\', \'President\');</div>';
        echo '</div>';
        
        $xcrud->where('officeCode IN', ['1', '2', '3']);
        $xcrud->where('employeeNumber BETWEEN', [1100, 1500]);
        $xcrud->where('email LIKE', '%@classicmodelcars.com');
        $xcrud->where('reportsTo IS NOT NULL', '');
        $xcrud->or_where('jobTitle', 'President');
        break;
        
    case 'none':
    default:
        echo '<div class="operator-info">';
        echo '<h5>No Filter - All Data</h5>';
        echo '<p>Showing all employees without any WHERE conditions applied.</p>';
        echo '</div>';
        break;
}

// Additional test operators for demonstration
if ($operator == 'basic') {
    // Show the SQL being generated (for educational purposes)
    echo '<div class="operator-info" style="background: #fff3cd; border-color: #ffc107;">';
    echo '<h5>📝 Additional Basic Operator Examples</h5>';
    echo '<ul>';
    echo '<li><strong>Starts with (^=):</strong> $xcrud->where(\'firstName ^=\', \'A\');</li>';
    echo '<li><strong>Ends with ($=):</strong> $xcrud->where(\'lastName $=\', \'son\');</li>';
    echo '<li><strong>Contains (~=):</strong> $xcrud->where(\'email ~=\', \'mary\');</li>';
    echo '<li><strong>Not equal (<>):</strong> $xcrud->where(\'officeCode <>\', \'1\');</li>';
    echo '</ul>';
    echo '</div>';
}

echo $xcrud->render();
?>

<div class="operator-info" style="background: #f0f8ff; border-color: #5bc0de; margin-top: 20px;">
    <h5>💡 MySQL Database Notes</h5>
    <ul style="margin-bottom: 0;">
        <li><strong>LIKE:</strong> Case-insensitive by default for non-binary strings</li>
        <li><strong>REGEXP/RLIKE:</strong> Native support for regular expressions</li>
        <li><strong>ILIKE:</strong> Not supported (use LOWER() for case-insensitive)</li>
        <li><strong>Regex operators (~, !~):</strong> Not supported (PostgreSQL-specific)</li>
        <li><strong>SIMILAR TO:</strong> Not supported (PostgreSQL-specific)</li>
    </ul>
</div>