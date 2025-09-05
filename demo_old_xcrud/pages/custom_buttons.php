<?php
$xcrud = Xcrud::get_instance();
$xcrud->table('customers');
$xcrud->table_name('Custom Buttons & Actions Demo');

$xcrud->columns('customerNumber,customerName,city,country,creditLimit');
$xcrud->limit(15);

// Add various custom buttons
$xcrud->button('mailto:{email}', 'Send Email', 'fas fa-envelope', 'btn btn-primary btn-sm');
$xcrud->button('tel:{phone}', 'Call Customer', 'fas fa-phone', 'btn btn-success btn-sm');
$xcrud->button('https://maps.google.com?q={city},{country}', 'View on Map', 'fas fa-map-marker-alt', 'btn btn-info btn-sm', 
    array('target' => '_blank'));

// Conditional buttons based on field values
$xcrud->button('javascript:void(0)', 'VIP Customer', 'fas fa-crown', 'btn btn-warning btn-sm', 
    array('onclick' => 'alert("VIP Customer!")'), 'creditLimit', '>', 50000);

$xcrud->button('javascript:showCustomerDetails({customerNumber})', 'Quick Details', 'fas fa-info-circle', 'btn btn-secondary btn-sm');

// JavaScript action buttons
$xcrud->button('javascript:markAsFavorite({customerNumber})', 'Add to Favorites', 'fas fa-heart', 'btn btn-outline-danger btn-sm');
$xcrud->button('javascript:generateReport({customerNumber})', 'Generate Report', 'fas fa-file-pdf', 'btn btn-outline-primary btn-sm');

// Dropdown action menu
$xcrud->button('#', 'Actions ▼', 'fas fa-ellipsis-v', 'btn btn-light btn-sm dropdown-toggle', 
    array('data-toggle' => 'dropdown'), '', '', '', 'dropdown');

echo $xcrud->render();
?>

<style>
.custom-button-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-top: 20px;
}

.button-demo {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.button-demo:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

.button-demo h5 {
    margin-bottom: 10px;
    color: #4a5568;
}

.demo-button {
    display: inline-block;
    padding: 8px 15px;
    margin: 2px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 0.85rem;
    transition: all 0.2s ease;
}
</style>

<div class="custom-button-grid">
    <div class="button-demo">
        <h5><i class="fas fa-mouse-pointer"></i> Link Buttons</h5>
        <a href="#" class="demo-button btn btn-primary btn-sm">
            <i class="fas fa-external-link-alt"></i> External Link
        </a>
        <a href="mailto:test@example.com" class="demo-button btn btn-success btn-sm">
            <i class="fas fa-envelope"></i> Email Link
        </a>
    </div>
    
    <div class="button-demo">
        <h5><i class="fas fa-code"></i> JavaScript Actions</h5>
        <button class="demo-button btn btn-warning btn-sm" onclick="alert('Custom Action!')">
            <i class="fas fa-bolt"></i> JS Action
        </button>
        <button class="demo-button btn btn-info btn-sm" onclick="showModal()">
            <i class="fas fa-window-restore"></i> Open Modal
        </button>
    </div>
    
    <div class="button-demo">
        <h5><i class="fas fa-check-circle"></i> Conditional Buttons</h5>
        <div style="font-size: 0.85rem; color: #666;">
            Buttons shown based on field conditions:
            <code>creditLimit > 50000</code>
        </div>
    </div>
    
    <div class="button-demo">
        <h5><i class="fas fa-palette"></i> Button Styles</h5>
        <button class="demo-button btn btn-primary btn-sm">Primary</button>
        <button class="demo-button btn btn-outline-secondary btn-sm">Outline</button>
        <button class="demo-button btn btn-light btn-sm">Light</button>
    </div>
</div>

<div style="margin-top: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 25px; border-radius: 15px; color: white;">
    <h3 style="margin-top: 0;">🎯 Button Configuration Options</h3>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 20px;">
        <div style="background: rgba(255,255,255,0.15); padding: 15px; border-radius: 10px;">
            <h4 style="margin-top: 0; color: #ffd700;">🔗 Link Types</h4>
            <ul style="margin: 0; padding-left: 20px; font-size: 0.9rem;">
                <li><code>http://example.com</code> - External URL</li>
                <li><code>mailto:{email}</code> - Email link</li>
                <li><code>tel:{phone}</code> - Phone link</li>
                <li><code>javascript:myFunc()</code> - JS function</li>
                <li><code>?page=edit&id={id}</code> - Internal page</li>
            </ul>
        </div>
        
        <div style="background: rgba(255,255,255,0.15); padding: 15px; border-radius: 10px;">
            <h4 style="margin-top: 0; color: #98fb98;">🎨 Styling</h4>
            <ul style="margin: 0; padding-left: 20px; font-size: 0.9rem;">
                <li><code>btn btn-primary</code> - Bootstrap classes</li>
                <li><code>fas fa-icon</code> - FontAwesome icons</li>
                <li><code>btn-sm, btn-lg</code> - Size variations</li>
                <li><code>btn-outline-*</code> - Outline styles</li>
            </ul>
        </div>
        
        <div style="background: rgba(255,255,255,0.15); padding: 15px; border-radius: 10px;">
            <h4 style="margin-top: 0; color: #87ceeb;">⚙️ Conditions</h4>
            <ul style="margin: 0; padding-left: 20px; font-size: 0.9rem;">
                <li><code>field, operator, value</code> - Show if condition met</li>
                <li><code>status, '=', 'active'</code> - Equality check</li>
                <li><code>amount, '>', 1000</code> - Comparison</li>
                <li>Multiple conditions supported</li>
            </ul>
        </div>
        
        <div style="background: rgba(255,255,255,0.15); padding: 15px; border-radius: 10px;">
            <h4 style="margin-top: 0; color: #dda0dd;">🔧 Attributes</h4>
            <ul style="margin: 0; padding-left: 20px; font-size: 0.9rem;">
                <li><code>target="_blank"</code> - Open in new tab</li>
                <li><code>onclick="script"</code> - Click handler</li>
                <li><code>data-*</code> - Custom data attributes</li>
                <li><code>class="custom"</code> - Additional CSS</li>
            </ul>
        </div>
    </div>
    
    <div style="margin-top: 20px; padding: 15px; background: rgba(0,0,0,0.2); border-radius: 10px;">
        <h4 style="margin-top: 0;">💡 Advanced Example:</h4>
        <pre style="margin: 0; color: #fff; font-size: 0.85rem;"><code>$xcrud->button('https://api.service.com/process/{id}', 'Process', 'fas fa-cog', 
    'btn btn-warning', 
    array('target' => '_blank', 'data-confirm' => 'Process this record?'),
    'status', '=', 'pending');</code></pre>
    </div>
</div>

<script>
function showCustomerDetails(customerId) {
    alert('Showing details for customer ID: ' + customerId);
}

function markAsFavorite(customerId) {
    console.log('Added customer ' + customerId + ' to favorites');
    // Here you would make an AJAX call to save the favorite
}

function generateReport(customerId) {
    console.log('Generating report for customer ' + customerId);
    // Here you would trigger report generation
}

function showModal() {
    alert('This would open a custom modal dialog');
}
</script>