<div class="xcrud revolution-theme<?php echo $this->is_rtl ? ' xcrud_rtl' : ''?>" data-instance="<?php echo $this->instance_name ?>">
    <?php echo $this->render_table_name(false, 'div', true)?>
    <div class="xcrud-container revolution-container"<?php echo ($this->start_minimized) ? ' style="display:none;"' : '' ?>>
        <div class="xcrud-ajax" id="xcrud-<?php echo $this->instance_name ?>">
            <?php echo $this->render_view() ?>
        </div>
        <div class="xcrud-overlay revolution-overlay"></div>
    </div>
    
    <!-- Revolution Floating Action Button -->
    <div class="revolution-fab" id="revolution-fab-<?php echo $this->instance_name ?>">
        <button class="revolution-fab-main" type="button" title="Quick Actions">
            <i class="fas fa-plus"></i>
        </button>
        <div class="revolution-fab-menu">
            <!-- Dynamic buttons will be inserted here via JavaScript -->
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revolution FAB Dynamic Button Integration
    const fabContainer = document.getElementById('revolution-fab-<?php echo $this->instance_name ?>');
    const fabMenu = fabContainer.querySelector('.revolution-fab-menu');
    const xcrudContainer = document.querySelector('[data-instance="<?php echo $this->instance_name ?>"]');
    
    function initRevolutionFAB() {
        if (!xcrudContainer || !fabContainer) return;
        
        // Find existing action buttons
        const actionButtons = xcrudContainer.querySelectorAll('.xcrud-top-actions .btn, .xcrud-nav .btn');
        const fabItems = [];
        
        actionButtons.forEach(btn => {
            const icon = btn.querySelector('i, .glyphicon');
            const text = btn.textContent || btn.title || btn.getAttribute('title');
            
            if (btn.classList.contains('btn-success') || text.toLowerCase().includes('add')) {
                fabItems.push({
                    icon: 'fas fa-plus',
                    title: 'Add New',
                    action: () => btn.click()
                });
            } else if (btn.classList.contains('btn-default') && (text.toLowerCase().includes('csv') || text.toLowerCase().includes('export'))) {
                fabItems.push({
                    icon: 'fas fa-file-csv',
                    title: 'Export CSV',
                    action: () => btn.click()
                });
            } else if (text.toLowerCase().includes('print')) {
                fabItems.push({
                    icon: 'fas fa-print',
                    title: 'Print',
                    action: () => btn.click()
                });
            }
        });
        
        // Create FAB items
        fabItems.forEach(item => {
            const fabItem = document.createElement('button');
            fabItem.className = 'revolution-fab-item';
            fabItem.innerHTML = `<i class="${item.icon}"></i>`;
            fabItem.title = item.title;
            fabItem.addEventListener('click', item.action);
            fabMenu.appendChild(fabItem);
        });
        
        // Show FAB if there are items
        if (fabItems.length > 0) {
            fabContainer.style.display = 'block';
        }
    }
    
    // Initialize FAB
    initRevolutionFAB();
    
    // Re-initialize after AJAX updates
    const observer = new MutationObserver(initRevolutionFAB);
    if (xcrudContainer) {
        observer.observe(xcrudContainer, { childList: true, subtree: true });
    }
});
</script>