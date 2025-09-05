<?php
/**
 * xCrudRevolution List View Template  
 * Revolutionary table with advanced glassmorphism and animations
 */
?>

<!-- Revolutionary List Header -->
<div class="xcrud-revolution-list-header">
    <?php echo $this->render_table_name(); ?>
    
    <!-- Revolutionary Quick Stats -->
    <div class="xcrud-revolution-quick-stats">
        <div class="xcrud-revolution-stat">
            <span class="xcrud-revolution-stat-label">Total Records</span>
            <span class="xcrud-revolution-stat-value" data-counter="<?php echo $this->total_records; ?>">0</span>
        </div>
        <div class="xcrud-revolution-stat">
            <span class="xcrud-revolution-stat-label">Showing</span>
            <span class="xcrud-revolution-stat-value"><?php echo $this->current_records; ?></span>
        </div>
        <div class="xcrud-revolution-stat">
            <span class="xcrud-revolution-stat-label">Pages</span>
            <span class="xcrud-revolution-stat-value"><?php echo $this->total_pages; ?></span>
        </div>
    </div>
</div>

<!-- Revolutionary Action Bar -->
<div class="xcrud-revolution-action-bar">
    <div class="xcrud-revolution-actions-left">
        <?php 
        echo $this->add_button('xcrud-button xcrud-revolution-add', '✨');
        echo $this->csv_button('xcrud-button xcrud-revolution-export', '📊'); 
        echo $this->print_button('xcrud-button xcrud-revolution-print', '🖨️');
        ?>
        
        <!-- Revolutionary Bulk Actions -->
        <div class="xcrud-revolution-bulk-actions">
            <button class="xcrud-button xcrud-revolution-bulk" data-action="select-all" title="Select All">
                <span class="xcrud-revolution-icon">☑️</span>
                <span class="xcrud-revolution-text">Select All</span>
            </button>
            <button class="xcrud-button xcrud-revolution-bulk" data-action="bulk-delete" title="Delete Selected" style="display: none;">
                <span class="xcrud-revolution-icon">🗑️</span>
                <span class="xcrud-revolution-text">Delete Selected</span>
            </button>
        </div>
    </div>
    
    <div class="xcrud-revolution-actions-right">
        <?php echo $this->render_search(); ?>
        
        <!-- Revolutionary View Mode Toggle -->
        <div class="xcrud-revolution-view-toggle">
            <button class="xcrud-revolution-view-btn active" data-view="table" title="Table View">📋</button>
            <button class="xcrud-revolution-view-btn" data-view="grid" title="Grid View">⚏</button>
            <button class="xcrud-revolution-view-btn" data-view="card" title="Card View">🃏</button>
        </div>
    </div>
</div>

<!-- Revolutionary Table Container -->
<div class="xcrud-revolution-table-container">
    <div class="xcrud-list-container xcrud-revolution-list-wrapper">
        <table class="xcrud-list xcrud-revolution-table">
            <thead class="xcrud-revolution-thead">
                <?php echo $this->render_grid_head('tr', 'th'); ?>
            </thead>
            <tbody class="xcrud-revolution-tbody">
                <?php echo $this->render_grid_body('tr', 'td'); ?>
            </tbody>
            <?php if($this->show_totals): ?>
            <tfoot class="xcrud-revolution-tfoot">
                <?php echo $this->render_grid_footer('tr', 'td'); ?>
            </tfoot>
            <?php endif; ?>
        </table>
        
        <!-- Revolutionary Empty State -->
        <div class="xcrud-revolution-empty-state" style="display: none;">
            <div class="xcrud-revolution-empty-icon">📭</div>
            <h3 class="xcrud-revolution-empty-title">No Records Found</h3>
            <p class="xcrud-revolution-empty-text">Try adjusting your search criteria or add new records.</p>
            <?php echo $this->add_button('xcrud-button xcrud-revolution-primary', '✨ Add First Record'); ?>
        </div>
    </div>
</div>

<!-- Revolutionary Navigation -->
<div class="xcrud-revolution-navigation">
    <div class="xcrud-revolution-nav-left">
        <?php echo $this->render_limitlist(true); ?>
        
        <!-- Revolutionary Performance Indicator -->
        <div class="xcrud-revolution-performance">
            <?php echo $this->render_benchmark(); ?>
        </div>
    </div>
    
    <div class="xcrud-revolution-nav-right">
        <?php echo $this->render_pagination(); ?>
    </div>
</div>

<!-- Revolutionary Additional CSS for List View -->
<style>
/* Revolutionary List Header */
.xcrud-revolution-list-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 24px;
    padding: 20px 0;
    border-bottom: 2px solid var(--xcrud-glass-border);
    position: relative;
}

.xcrud-revolution-list-header::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 100px;
    height: 2px;
    background: var(--xcrud-primary);
    animation: xcrud-revolution-expand-line 1s ease-out;
}

/* Revolutionary Quick Stats */
.xcrud-revolution-quick-stats {
    display: flex;
    gap: 24px;
}

.xcrud-revolution-stat {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    min-width: 80px;
}

.xcrud-revolution-stat-label {
    font-size: 12px;
    color: var(--xcrud-text-secondary);
    margin-bottom: 4px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.xcrud-revolution-stat-value {
    font-size: 24px;
    font-weight: 700;
    color: var(--xcrud-text-primary);
    background: var(--xcrud-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: xcrud-revolution-counter 2s ease-out;
}

/* Revolutionary Action Bar */
.xcrud-revolution-action-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding: 16px 20px;
    background: var(--xcrud-glass);
    backdrop-filter: var(--xcrud-backdrop-blur);
    border-radius: var(--xcrud-border-radius-sm);
    border: 1px solid var(--xcrud-glass-border);
}

.xcrud-revolution-actions-left,
.xcrud-revolution-actions-right {
    display: flex;
    align-items: center;
    gap: 12px;
}

/* Revolutionary Bulk Actions */
.xcrud-revolution-bulk-actions {
    display: flex;
    gap: 8px;
    margin-left: 16px;
    padding-left: 16px;
    border-left: 1px solid var(--xcrud-glass-border);
}

.xcrud-revolution-bulk {
    background: var(--xcrud-glass);
    color: var(--xcrud-text-secondary);
    border: 1px dashed var(--xcrud-glass-border);
    transition: all 0.3s ease;
}

.xcrud-revolution-bulk:hover {
    background: var(--xcrud-danger);
    color: white;
    border-style: solid;
    transform: scale(1.05);
}

.xcrud-revolution-bulk.active {
    background: var(--xcrud-primary);
    color: white;
    border-style: solid;
}

/* Revolutionary View Toggle */
.xcrud-revolution-view-toggle {
    display: flex;
    background: var(--xcrud-glass);
    border-radius: var(--xcrud-border-radius-sm);
    padding: 4px;
    border: 1px solid var(--xcrud-glass-border);
}

.xcrud-revolution-view-btn {
    width: 40px;
    height: 32px;
    border: none;
    background: transparent;
    color: var(--xcrud-text-secondary);
    cursor: pointer;
    border-radius: calc(var(--xcrud-border-radius-sm) - 4px);
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.xcrud-revolution-view-btn:hover {
    background: rgba(255, 255, 255, 0.1);
    color: var(--xcrud-text-primary);
}

.xcrud-revolution-view-btn.active {
    background: var(--xcrud-primary);
    color: white;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
}

/* Revolutionary Table Container */
.xcrud-revolution-table-container {
    position: relative;
    margin-bottom: 20px;
}

.xcrud-revolution-list-wrapper {
    background: var(--xcrud-glass);
    backdrop-filter: var(--xcrud-backdrop-blur);
    border-radius: var(--xcrud-border-radius-sm);
    border: 1px solid var(--xcrud-glass-border);
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

/* Revolutionary Table Styling */
.xcrud-revolution-table {
    background: transparent;
    margin: 0;
}

.xcrud-revolution-thead tr {
    background: linear-gradient(135deg, var(--xcrud-primary), rgba(102, 126, 234, 0.8));
}

.xcrud-revolution-thead th {
    color: white;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 12px;
    padding: 16px 20px;
    border: none;
    position: relative;
    background: transparent;
}

.xcrud-revolution-thead th:hover {
    background: rgba(255, 255, 255, 0.1);
}

.xcrud-revolution-thead th::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 0;
    height: 2px;
    background: rgba(255, 255, 255, 0.6);
    transition: width 0.3s ease;
}

.xcrud-revolution-thead th:hover::after {
    width: 60%;
}

/* Revolutionary Table Body */
.xcrud-revolution-tbody tr {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
}

.xcrud-revolution-tbody tr:hover {
    transform: scale(1.005);
    z-index: 10;
}

.xcrud-revolution-tbody tr:hover td {
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.xcrud-revolution-tbody tr::before {
    content: '';
    position: absolute;
    left: -100%;
    top: 0;
    bottom: 0;
    width: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.05), transparent);
    transition: left 0.5s ease;
}

.xcrud-revolution-tbody tr:hover::before {
    left: 100%;
}

/* Revolutionary Footer */
.xcrud-revolution-tfoot {
    background: var(--xcrud-glass);
    backdrop-filter: var(--xcrud-backdrop-blur);
}

.xcrud-revolution-tfoot td {
    font-weight: 600;
    color: var(--xcrud-text-primary);
    border-top: 2px solid var(--xcrud-glass-border);
}

/* Revolutionary Empty State */
.xcrud-revolution-empty-state {
    text-align: center;
    padding: 60px 40px;
    background: var(--xcrud-glass);
    backdrop-filter: var(--xcrud-backdrop-blur);
    border-radius: var(--xcrud-border-radius);
    margin: 40px 0;
}

.xcrud-revolution-empty-icon {
    font-size: 64px;
    margin-bottom: 20px;
    opacity: 0.7;
    animation: xcrud-revolution-float 3s ease-in-out infinite;
}

.xcrud-revolution-empty-title {
    font-size: 24px;
    font-weight: 700;
    color: var(--xcrud-text-primary);
    margin-bottom: 12px;
}

.xcrud-revolution-empty-text {
    font-size: 16px;
    color: var(--xcrud-text-secondary);
    margin-bottom: 30px;
    line-height: 1.6;
}

/* Revolutionary Navigation */
.xcrud-revolution-navigation {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 20px;
    background: var(--xcrud-glass);
    backdrop-filter: var(--xcrud-backdrop-blur);
    border-radius: var(--xcrud-border-radius-sm);
    border: 1px solid var(--xcrud-glass-border);
    margin-top: 20px;
}

.xcrud-revolution-nav-left,
.xcrud-revolution-nav-right {
    display: flex;
    align-items: center;
    gap: 16px;
}

/* Revolutionary Performance Indicator */
.xcrud-revolution-performance {
    font-size: 12px;
    color: var(--xcrud-text-secondary);
    display: flex;
    align-items: center;
    gap: 8px;
}

.xcrud-revolution-performance::before {
    content: '⚡';
    font-size: 14px;
}

/* Revolutionary Button Variants */
.xcrud-revolution-add {
    background: var(--xcrud-success);
    color: white;
    border: none;
}

.xcrud-revolution-export {
    background: var(--xcrud-info);
    color: #2c3e50;
    border: none;
}

.xcrud-revolution-print {
    background: var(--xcrud-warning);
    color: #2c3e50;
    border: none;
}

.xcrud-revolution-primary {
    background: var(--xcrud-primary);
    color: white;
    border: none;
}

/* Revolutionary Animations */
@keyframes xcrud-revolution-expand-line {
    0% { width: 0; }
    100% { width: 100px; }
}

@keyframes xcrud-revolution-counter {
    0% { 
        transform: scale(0.5) rotateY(90deg);
        opacity: 0;
    }
    50% {
        transform: scale(1.1) rotateY(0deg);
    }
    100% {
        transform: scale(1) rotateY(0deg);
        opacity: 1;
    }
}

@keyframes xcrud-revolution-float {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
}

/* Revolutionary Responsive Design */
@media (max-width: 768px) {
    .xcrud-revolution-list-header {
        flex-direction: column;
        gap: 16px;
        align-items: center;
    }
    
    .xcrud-revolution-quick-stats {
        gap: 16px;
    }
    
    .xcrud-revolution-action-bar {
        flex-direction: column;
        gap: 16px;
        padding: 16px;
    }
    
    .xcrud-revolution-actions-left,
    .xcrud-revolution-actions-right {
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .xcrud-revolution-bulk-actions {
        margin-left: 0;
        padding-left: 0;
        border-left: none;
        border-top: 1px solid var(--xcrud-glass-border);
        padding-top: 12px;
        margin-top: 12px;
    }
    
    .xcrud-revolution-navigation {
        flex-direction: column;
        gap: 16px;
    }
}

/* Revolutionary Accessibility */
@media (prefers-reduced-motion: reduce) {
    .xcrud-revolution-tbody tr,
    .xcrud-revolution-empty-icon,
    .xcrud-revolution-stat-value {
        animation: none;
        transition: none;
    }
}
</style>

<!-- Revolutionary JavaScript Enhancements -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revolutionary Counter Animation
    const counters = document.querySelectorAll('[data-counter]');
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-counter'));
        const duration = 2000;
        const increment = target / (duration / 16);
        let current = 0;
        
        const timer = setInterval(() => {
            current += increment;
            counter.textContent = Math.floor(current).toLocaleString();
            
            if (current >= target) {
                counter.textContent = target.toLocaleString();
                clearInterval(timer);
            }
        }, 16);
    });
    
    // Revolutionary View Toggle
    const viewBtns = document.querySelectorAll('.xcrud-revolution-view-btn');
    viewBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            viewBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const view = this.getAttribute('data-view');
            document.body.setAttribute('data-xcrud-view', view);
        });
    });
    
    // Revolutionary Bulk Selection
    const selectAllBtn = document.querySelector('[data-action="select-all"]');
    const deleteSelectedBtn = document.querySelector('[data-action="bulk-delete"]');
    
    if (selectAllBtn) {
        selectAllBtn.addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('.xcrud-list input[type="checkbox"]');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            
            checkboxes.forEach(cb => {
                cb.checked = !allChecked;
            });
            
            this.classList.toggle('active');
            
            if (deleteSelectedBtn) {
                deleteSelectedBtn.style.display = !allChecked ? 'flex' : 'none';
            }
        });
    }
});
</script>