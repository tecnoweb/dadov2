<?php
$xcrud = Xcrud::get_instance();

// Use the largest available table for performance testing
// You can replace this with your largest table
$xcrud->table('orderdetails'); // This table typically has many records
$xcrud->table_name('Performance Test - Large Dataset Handling');

$xcrud->columns('orderNumber,productCode,quantityOrdered,priceEach,orderLineNumber');
$xcrud->limit_list('20,50,100,500,1000'); // Performance testing with different limits
$xcrud->limit(50); // Start with reasonable limit

// Enable benchmark to show performance metrics
$xcrud->benchmark(true);

// Show totals for performance impact testing
$xcrud->sum('quantityOrdered', 'text-right', 'Total Qty: ');
$xcrud->sum('priceEach', 'text-right', 'Avg Price: ');

echo $xcrud->render();
?>

<div style="margin-top: 30px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); padding: 25px; border-radius: 15px; color: white;">
    <h3 style="margin-top: 0;">⚡ Performance Testing Features</h3>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 20px;">
        <div style="background: rgba(255,255,255,0.2); padding: 15px; border-radius: 10px;">
            <h4 style="margin-top: 0; color: #fff;">📊 Benchmarking</h4>
            <ul style="margin: 0; padding-left: 20px; font-size: 0.9rem;">
                <li>Query execution time</li>
                <li>Memory usage tracking</li>
                <li>Row count display</li>
                <li>Database connection info</li>
            </ul>
        </div>
        
        <div style="background: rgba(255,255,255,0.2); padding: 15px; border-radius: 10px;">
            <h4 style="margin-top: 0; color: #fff;">🔧 Performance Tips</h4>
            <ul style="margin: 0; padding-left: 20px; font-size: 0.9rem;">
                <li>Use appropriate limits</li>
                <li>Index your columns</li>
                <li>Avoid 'all' for large tables</li>
                <li>Optimize WHERE clauses</li>
            </ul>
        </div>
        
        <div style="background: rgba(255,255,255,0.2); padding: 15px; border-radius: 10px;">
            <h4 style="margin-top: 0; color: #fff;">🚀 Optimization</h4>
            <ul style="margin: 0; padding-left: 20px; font-size: 0.9rem;">
                <li>Pagination for large datasets</li>
                <li>Lazy loading relations</li>
                <li>Query result caching</li>
                <li>Efficient column selection</li>
            </ul>
        </div>
    </div>
    
    <div style="margin-top: 20px; padding: 15px; background: rgba(0,0,0,0.2); border-radius: 10px;">
        <h4 style="margin-top: 0;">💡 Note:</h4>
        <p style="margin: 0; font-size: 0.9rem;">
            This demo uses the <code>orderdetails</code> table which typically contains many records. 
            For testing with truly large datasets (millions of records), create a dedicated test table 
            or use a table with substantial data in your database.
        </p>
    </div>
</div>