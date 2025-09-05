<div class="xcrud<?php echo $this->is_rtl ? ' xcrud_rtl' : ''?>" data-instance="<?php echo $this->instance_name ?>">
    <?php 
    // Persist view mode from request
    $current_view = isset($_REQUEST['view']) ? $_REQUEST['view'] : (isset($_POST['view']) ? $_POST['view'] : 'table');
    ?>
    <input type="hidden" class="xcrud-view-mode" name="view" value="<?php echo htmlspecialchars($current_view); ?>" />
    <?php echo $this->render_table_name(false, 'div', true)?>
    <div class="xcrud-container"<?php echo ($this->start_minimized) ? ' style="display:none;"' : '' ?>>
        <div class="xcrud-ajax" id="xcrud-<?php echo $this->instance_name ?>">
            <?php echo $this->render_view() ?>
        </div>
        <div class="xcrud-overlay"></div>
    </div>
    
    <!-- Revolution FAB (Floating Action Button) -->
    <div class="revo-fab" id="revo-fab-<?php echo $this->instance_name ?>">
        <button class="revo-fab-trigger" type="button">
            <i class="fas fa-plus"></i>
        </button>
        <div class="revo-fab-menu" id="revo-fab-menu-<?php echo $this->instance_name ?>">
            <!-- FAB items will be dynamically populated -->
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const instanceName = '<?php echo $this->instance_name ?>';
    const xcrudContainer = document.querySelector('[data-instance="' + instanceName + '"]');
    const fabContainer = document.getElementById('revo-fab-' + instanceName);
    const fabTrigger = fabContainer?.querySelector('.revo-fab-trigger');
    const fabMenu = document.getElementById('revo-fab-menu-' + instanceName);
    
    if (!xcrudContainer || !fabContainer || !fabTrigger || !fabMenu) return;
    
    // Revolution FAB System
    function populateFAB() {
        // Clear existing FAB items
        fabMenu.innerHTML = '';
        
        // Find action buttons in the current view
        const actionButtons = xcrudContainer.querySelectorAll(
            '.revo-btn-success, .revo-btn[class*="add"], ' +
            '.revo-btn[class*="csv"], .revo-btn[class*="export"], ' +
            '.revo-btn[class*="print"], .xcrud-top-actions .revo-btn'
        );
        
        const fabItems = [];
        
        actionButtons.forEach(btn => {
            const btnText = btn.textContent?.toLowerCase() || '';
            const btnClass = btn.className || '';
            
            // Add button
            if (btnClass.includes('success') || btnText.includes('add') || btnText.includes('aggiungi')) {
                fabItems.push({
                    icon: 'fas fa-plus',
                    color: 'revo-fab-add',
                    title: 'Add New Record',
                    action: () => btn.click()
                });
            }
            // Export/CSV button
            else if (btnText.includes('csv') || btnText.includes('export') || btnText.includes('esport')) {
                fabItems.push({
                    icon: 'fas fa-file-csv',
                    color: 'revo-fab-export', 
                    title: 'Export Data',
                    action: () => btn.click()
                });
            }
            // Print button
            else if (btnText.includes('print') || btnText.includes('stampa')) {
                fabItems.push({
                    icon: 'fas fa-print',
                    color: 'revo-fab-print',
                    title: 'Print',
                    action: () => btn.click()
                });
            }
        });
        
        // Create FAB items
        fabItems.forEach(item => {
            const fabItem = document.createElement('button');
            fabItem.className = 'revo-fab-item ' + item.color;
            fabItem.innerHTML = '<i class="' + item.icon + '"></i>';
            fabItem.title = item.title;
            fabItem.addEventListener('click', item.action);
            fabMenu.appendChild(fabItem);
        });
        
        // Show/hide FAB based on available actions
        fabContainer.style.display = fabItems.length > 0 ? 'block' : 'none';
    }
    
    // FAB trigger toggle
    fabTrigger.addEventListener('click', function() {
        fabContainer.classList.toggle('active');
    });
    
    // Close FAB when clicking outside
    document.addEventListener('click', function(e) {
        if (!fabContainer.contains(e.target)) {
            fabContainer.classList.remove('active');
        }
    });
    
    // Initial FAB population
    populateFAB();
    
    // Re-populate FAB after AJAX updates
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                // Delay to ensure DOM is updated
                setTimeout(populateFAB, 100);
            }
        });
    });
    
    observer.observe(xcrudContainer, {
        childList: true,
        subtree: true
    });
});
</script>