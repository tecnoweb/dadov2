<?php
$xcrud = Xcrud::get_instance();
$xcrud->table('products');
$xcrud->table_name('Mass Operations Demo - Available Features');

// Configure which columns to show
$xcrud->columns('productCode,productName,productLine,quantityInStock,buyPrice,MSRP');

// Add custom buttons for demonstration
$xcrud->button('javascript:bulkOperation("discount")', 'Apply Discount', 'fas fa-percent', 'btn btn-warning btn-sm');
$xcrud->button('javascript:bulkOperation("restock")', 'Restock Items', 'fas fa-plus', 'btn btn-success btn-sm');
$xcrud->button('javascript:bulkOperation("export")', 'Export Data', 'fas fa-download', 'btn btn-info btn-sm');

// Highlight low stock items
$xcrud->highlight_row('quantityInStock', '<', 100, '#fff3cd');
$xcrud->highlight_row('quantityInStock', '<', 50, '#f8d7da');
$xcrud->highlight_row('quantityInStock', '<', 10, '#dc3545');

// Add sum for financial columns
$xcrud->sum('buyPrice', 'text-right', 'Total Cost: $');
$xcrud->sum('MSRP', 'text-right', 'Total MSRP: $');

echo $xcrud->render();
?>

<div style="margin-top: 40px;">
    <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 25px; border-radius: 15px; color: white;">
        <h3 style="margin-top: 0;">🚀 Mass Operations Features</h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 20px;">
            <div style="background: rgba(255,255,255,0.2); padding: 15px; border-radius: 10px;">
                <h4 style="margin-top: 0;">✅ Selection</h4>
                <ul style="margin: 0; padding-left: 20px; font-size: 0.95rem;">
                    <li>Select All checkbox</li>
                    <li>Individual row selection</li>
                    <li>Shift+Click multi-select</li>
                    <li>Selected count display</li>
                </ul>
            </div>
            
            <div style="background: rgba(255,255,255,0.2); padding: 15px; border-radius: 10px;">
                <h4 style="margin-top: 0;">⚡ Bulk Actions</h4>
                <ul style="margin: 0; padding-left: 20px; font-size: 0.95rem;">
                    <li>Mass delete</li>
                    <li>Mass update fields</li>
                    <li>Custom bulk operations</li>
                    <li>Export selected rows</li>
                </ul>
            </div>
            
            <div style="background: rgba(255,255,255,0.2); padding: 15px; border-radius: 10px;">
                <h4 style="margin-top: 0;">🔧 Custom Actions</h4>
                <ul style="margin: 0; padding-left: 20px; font-size: 0.95rem;">
                    <li>Define custom callbacks</li>
                    <li>Confirmation dialogs</li>
                    <li>Progress indicators</li>
                    <li>Success/error feedback</li>
                </ul>
            </div>
        </div>
        
        <div style="margin-top: 20px; padding: 15px; background: rgba(0,0,0,0.2); border-radius: 10px;">
            <h4 style="margin-top: 0;">💻 Code Example:</h4>
            <pre style="margin: 0; color: #fff; font-size: 0.9rem;"><code>// Enable mass operations
$xcrud->mass_buttons(true);

// Create custom action
$xcrud->create_action('mass_update', 'my_mass_update_function');

// Add button to interface
$xcrud->button('#', 'Mass Update', 'icon', 'btn-class xcrud-mass-action', 
    array('data-task' => 'mass_update'));</code></pre>
        </div>
    </div>
</div>

<script>
function bulkOperation(operation) {
    switch(operation) {
        case 'discount':
            alert('Mass Discount Operation\n\nIn a real application, this would:\n• Select checked items\n• Apply discount to selected products\n• Update database\n• Refresh the grid');
            break;
        case 'restock':
            alert('Mass Restock Operation\n\nIn a real application, this would:\n• Select checked items\n• Add inventory to selected products\n• Update stock quantities\n• Refresh the grid');
            break;
        case 'export':
            alert('Mass Export Operation\n\nIn a real application, this would:\n• Select checked items\n• Generate CSV/Excel file\n• Download selected data\n• Show export progress');
            break;
    }
}
</script>