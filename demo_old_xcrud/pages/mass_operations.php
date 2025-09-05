<?php
$xcrud = Xcrud::get_instance();
$xcrud->table('products');
$xcrud->table_name('Mass Operations Demo - Bulk Actions');

// Enable mass operations
$xcrud->mass_buttons(true);

// Configure which columns to show
$xcrud->columns('productCode,productName,productLine,quantityInStock,buyPrice,MSRP');

// Add custom mass buttons
$xcrud->create_action('mass_discount', 'apply_mass_discount');
$xcrud->create_action('mass_restock', 'mass_restock_products'); 
$xcrud->create_action('mass_export', 'export_selected');

// Add buttons to interface
$xcrud->button('#', 'Apply 10% Discount', 'glyphicon glyphicon-tags', 'btn btn-warning xcrud-mass-action', 
    array('data-task' => 'mass_discount', 'data-confirm' => 'Apply discount to selected items?'));

$xcrud->button('#', 'Restock (+100)', 'glyphicon glyphicon-plus', 'btn btn-success xcrud-mass-action',
    array('data-task' => 'mass_restock', 'data-confirm' => 'Add 100 units to selected products?'));

$xcrud->button('#', 'Export Selected', 'glyphicon glyphicon-download', 'btn btn-info xcrud-mass-action',
    array('data-task' => 'mass_export'));

// Highlight low stock items
$xcrud->highlight_row('quantityInStock', '<', 100, '#ffebee');
$xcrud->highlight_row('quantityInStock', '<', 50, '#ffcdd2');
$xcrud->highlight_row('quantityInStock', '<', 10, '#ef5350', 'color: white');

// Add sum for financial columns
$xcrud->sum('buyPrice', 'align-right', 'Total Cost: ');
$xcrud->sum('MSRP', 'align-right', 'Total MSRP: ');

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