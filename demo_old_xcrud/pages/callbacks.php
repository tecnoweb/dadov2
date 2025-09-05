<?php
$xcrud = Xcrud::get_instance();
$xcrud->table('orders');
$xcrud->table_name('Callbacks Demo - Complete Lifecycle Hooks');
$xcrud->columns('orderNumber,orderDate,requiredDate,shippedDate,status,comments,customerNumber');
$xcrud->limit(15);

// NOTE: In a real application, you would create callback functions like these:
// $xcrud->before_insert('my_before_insert_function');
// $xcrud->after_insert('my_after_insert_function');
// $xcrud->field_callback('status', 'render_status_field');

// For this demo, we'll show highlighting and formatting instead
$xcrud->highlight_row('status', '=', 'Shipped', '#d4edda');
$xcrud->highlight_row('status', '=', 'Cancelled', '#f8d7da');
$xcrud->highlight_row('status', '=', 'On Hold', '#fff3cd');
$xcrud->highlight_row('status', '=', 'In Process', '#cce5ff');

// Column pattern for date formatting
$xcrud->column_pattern('orderDate', '{value}');
$xcrud->column_pattern('shippedDate', '{value}');

echo $xcrud->render();
?>

<div style="margin-top: 40px; padding: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px; color: white;">
    <h3 style="margin-top: 0;">📋 Available Callbacks in xCrudRevolution</h3>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 20px;">
        <div style="background: rgba(255,255,255,0.1); padding: 15px; border-radius: 10px;">
            <h4 style="margin-top: 0; color: #ffd700;">✨ Before Callbacks</h4>
            <ul style="margin: 0; padding-left: 20px;">
                <li><code>before_insert($callback, $path)</code> - Validate before insert</li>
                <li><code>before_update($callback, $path)</code> - Validate before update</li>
                <li><code>before_remove($callback, $path)</code> - Check before delete</li>
                <li><code>before_upload($callback, $path)</code> - Validate files</li>
            </ul>
        </div>
        
        <div style="background: rgba(255,255,255,0.1); padding: 15px; border-radius: 10px;">
            <h4 style="margin-top: 0; color: #90ee90;">✅ After Callbacks</h4>
            <ul style="margin: 0; padding-left: 20px;">
                <li><code>after_insert($callback, $path)</code> - Log after insert</li>
                <li><code>after_update($callback, $path)</code> - Notify after update</li>
                <li><code>after_remove($callback, $path)</code> - Cleanup after delete</li>
                <li><code>after_upload($callback, $path)</code> - Process uploaded files</li>
            </ul>
        </div>
        
        <div style="background: rgba(255,255,255,0.1); padding: 15px; border-radius: 10px;">
            <h4 style="margin-top: 0; color: #87ceeb;">🔄 Replace Callbacks</h4>
            <ul style="margin: 0; padding-left: 20px;">
                <li><code>replace_insert($callback, $path)</code> - Custom insert logic</li>
                <li><code>replace_update($callback, $path)</code> - Custom update logic</li>
                <li><code>replace_remove($callback, $path)</code> - Custom delete logic</li>
            </ul>
        </div>
        
        <div style="background: rgba(255,255,255,0.1); padding: 15px; border-radius: 10px;">
            <h4 style="margin-top: 0; color: #ff69b4;">🎨 Display Callbacks</h4>
            <ul style="margin: 0; padding-left: 20px;">
                <li><code>field_callback($field, $callback)</code> - Custom field render</li>
                <li><code>column_callback($col, $callback)</code> - Format columns</li>
                <li><code>before_list($callback)</code> - Modify list view</li>
                <li><code>before_create($callback)</code> - Prepare create form</li>
            </ul>
        </div>
    </div>
    
    <div style="margin-top: 20px; padding: 15px; background: rgba(255,255,255,0.2); border-radius: 10px;">
        <h4 style="margin-top: 0;">💡 Pro Tips:</h4>
        <ul style="margin: 0; padding-left: 20px;">
            <li>Callbacks receive <code>$postdata</code> object for data manipulation</li>
            <li>Return <code>false</code> in before_* callbacks to cancel operation</li>
            <li>Use <code>$xcrud->set_exception()</code> to show custom error messages</li>
            <li>Access current instance with <code>$this</code> in callback functions</li>
        </ul>
    </div>
</div>