<?php 
// Revolution Theme - Innovative Detail View
// Preserve view mode for return to list
$current_view = isset($_REQUEST['view']) ? $_REQUEST['view'] : (isset($_POST['view']) ? $_POST['view'] : 'table');
?>

<div class="revo-container">
    <!-- Sidebar Actions -->
    <aside class="revo-sidebar">
        <div class="revo-sidebar-header">
            <h3 class="revo-brand">
                <span class="revo-brand-icon">◈</span>
                <?php echo ucfirst($mode); ?> Mode
            </h3>
            <button class="revo-theme-toggle" onclick="toggleRevolutionTheme()">
                <span class="revo-theme-icon">🌙</span>
            </button>
        </div>
        
        <div class="revo-actions-section">
            <h4 class="revo-section-title">Actions</h4>
            <?php if($mode != 'view'): ?>
                <?php echo $this->render_button('save_return', 'save', 'list', 'revo-action-card revo-save-return xcrud-action', '<span class="revo-icon">✓</span><span>Save & Return</span>', 'create,edit'); ?>
                <?php echo $this->render_button('save_new', 'save', 'create', 'revo-action-card revo-save-new xcrud-action', '<span class="revo-icon">+</span><span>Save & New</span>', 'create,edit'); ?>
                <?php echo $this->render_button('save_edit', 'save', 'edit', 'revo-action-card revo-save-edit xcrud-action', '<span class="revo-icon">⟲</span><span>Save & Edit</span>', 'create,edit'); ?>
            <?php endif; ?>
            <a href="javascript:void(0);" 
               class="revo-action-card revo-return xcrud-action" 
               data-task="list"
               data-view="<?php echo htmlspecialchars($current_view); ?>">
                <span class="revo-icon">←</span><span>Back to List</span>
            </a>
        </div>
        
        <div class="revo-info-section">
            <h4 class="revo-section-title">Information</h4>
            <div class="revo-info-item">
                <span class="revo-info-label">Mode:</span>
                <span class="revo-info-value"><?php echo ucfirst($mode); ?></span>
            </div>
            <?php if($mode == 'edit' || $mode == 'view'): ?>
            <div class="revo-info-item">
                <span class="revo-info-label">Record ID:</span>
                <span class="revo-info-value">#<?php echo isset($_GET['xcrud']['primary']) ? $_GET['xcrud']['primary'] : 'N/A'; ?></span>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="revo-stats">
            <?php echo $this->render_benchmark(); ?>
        </div>
    </aside>
    
    <!-- Main Form Content -->
    <main class="revo-main">
        <!-- Hidden field to preserve view mode -->
        <input type="hidden" class="xcrud-data" name="view" value="<?php echo htmlspecialchars($current_view); ?>" />
        
        <!-- Header -->
        <header class="revo-header">
            <?php echo $this->render_table_name($mode); ?>
            <div class="revo-header-controls">
                <?php if($mode == 'view'): ?>
                    <span class="revo-badge revo-badge-info">Read Only</span>
                <?php elseif($mode == 'create'): ?>
                    <span class="revo-badge revo-badge-success">New Record</span>
                <?php elseif($mode == 'edit'): ?>
                    <span class="revo-badge revo-badge-warning">Editing</span>
                <?php endif; ?>
            </div>
        </header>
        
        <!-- Form Content -->
        <div class="revo-content">
            <div class="revo-form-container">
                <?php 
                // Render fields with custom layout
                echo $this->render_fields_list(
                    $mode, 
                    array('tag' => 'div', 'class' => 'revo-form'),
                    array('tag' => 'div', 'class' => 'revo-form-row'),
                    array('tag' => 'div', 'class' => 'revo-label'),
                    array('tag' => 'div', 'class' => 'revo-field')
                );
                ?>
            </div>
        </div>
    </main>
</div>

<!-- Revolution Theme Scripts -->
<script>
function toggleRevolutionTheme() {
    document.body.classList.toggle('revo-dark-mode');
    const icon = document.querySelector('.revo-theme-icon');
    icon.textContent = document.body.classList.contains('revo-dark-mode') ? '☀️' : '🌙';
    localStorage.setItem('revo-theme', document.body.classList.contains('revo-dark-mode') ? 'dark' : 'light');
}

// Load saved theme
document.addEventListener('DOMContentLoaded', function() {
    if (localStorage.getItem('revo-theme') === 'dark') {
        document.body.classList.add('revo-dark-mode');
        document.querySelector('.revo-theme-icon').textContent = '☀️';
    }
    
    // Ensure view mode is preserved in all actions
    // Override the list_data function temporarily to add view parameter
    if (window.Xcrud) {
        const originalListData = Xcrud.list_data;
        Xcrud.list_data = function(container, element) {
            const data = originalListData.call(this, container, element);
            // Add view mode to the data
            data.view = '<?php echo htmlspecialchars($current_view); ?>';
            return data;
        };
    }
    
    // Also handle Save & Return button
    const saveReturnBtn = document.querySelector('.revo-save-return');
    if (saveReturnBtn) {
        // Override its click to include view mode
        const originalOnclick = saveReturnBtn.onclick;
        saveReturnBtn.onclick = function(e) {
            // Add view mode to the data
            const viewMode = '<?php echo htmlspecialchars($current_view); ?>';
            sessionStorage.setItem('revo-view-mode', viewMode);
            
            if (originalOnclick) {
                return originalOnclick.call(this, e);
            }
        };
    }
});
</script>