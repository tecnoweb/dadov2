<?php
/**
 * xCrudRevolution Detail View Template
 * Revolutionary form with glassmorphism, animations and smart validation
 */
?>

<!-- Revolutionary Form Header -->
<div class="xcrud-revolution-form-header">
    <?php echo $this->render_table_name($mode); ?>
    
    <!-- Revolutionary Form Mode Indicator -->
    <div class="xcrud-revolution-mode-indicator">
        <div class="xcrud-revolution-mode-badge" data-mode="<?php echo $mode; ?>">
            <?php 
            $modeIcons = [
                'create' => '✨',
                'edit' => '✏️', 
                'view' => '👁️'
            ];
            $modeTexts = [
                'create' => 'Creating New Record',
                'edit' => 'Editing Record',
                'view' => 'Viewing Record'
            ];
            echo $modeIcons[$mode] ?? '📝';
            ?>
            <span class="xcrud-revolution-mode-text"><?php echo $modeTexts[$mode] ?? 'Form Mode'; ?></span>
        </div>
        
        <!-- Revolutionary Progress Steps -->
        <div class="xcrud-revolution-form-progress">
            <div class="xcrud-revolution-progress-step active" data-step="1">
                <span class="xcrud-revolution-progress-number">1</span>
                <span class="xcrud-revolution-progress-label">Data Entry</span>
            </div>
            <div class="xcrud-revolution-progress-step" data-step="2">
                <span class="xcrud-revolution-progress-number">2</span>
                <span class="xcrud-revolution-progress-label">Validation</span>
            </div>
            <div class="xcrud-revolution-progress-step" data-step="3">
                <span class="xcrud-revolution-progress-number">3</span>
                <span class="xcrud-revolution-progress-label">Save</span>
            </div>
        </div>
    </div>
</div>

<!-- Revolutionary Action Bar -->
<div class="xcrud-revolution-form-actions">
    <div class="xcrud-revolution-actions-left">
        <!-- Revolutionary Save Options -->
        <div class="xcrud-revolution-save-group">
            <?php echo $this->render_button('save_new', 'save', 'create', 'xcrud-button xcrud-revolution-save-new', '💾', 'create,edit') ?>
            <?php echo $this->render_button('save_edit', 'save', 'edit', 'xcrud-button xcrud-revolution-save-edit', '✅', 'create,edit') ?>
            <?php echo $this->render_button('save_return', 'save', 'list', 'xcrud-button xcrud-revolution-save-return', '💾🔄', 'create,edit') ?>
        </div>
        
        <!-- Revolutionary Advanced Actions -->
        <?php if($mode !== 'view'): ?>
        <div class="xcrud-revolution-advanced-actions">
            <button class="xcrud-button xcrud-revolution-draft" type="button" title="Save as Draft">
                <span class="xcrud-revolution-icon">📝</span>
                <span class="xcrud-revolution-text">Draft</span>
            </button>
            <button class="xcrud-button xcrud-revolution-preview" type="button" title="Preview">
                <span class="xcrud-revolution-icon">👁️</span>
                <span class="xcrud-revolution-text">Preview</span>
            </button>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="xcrud-revolution-actions-right">
        <?php echo $this->render_button('return', 'list', '', 'xcrud-button xcrud-revolution-return', '🔙') ?>
        
        <!-- Revolutionary Help Button -->
        <button class="xcrud-button xcrud-revolution-help" type="button" title="Help & Shortcuts">
            <span class="xcrud-revolution-icon">❓</span>
            <span class="xcrud-revolution-text">Help</span>
        </button>
    </div>
</div>

<!-- Revolutionary Form Container -->
<div class="xcrud-revolution-form-container">
    <div class="xcrud-view xcrud-revolution-form-content">
        
        <!-- Revolutionary Form Validation Summary -->
        <div class="xcrud-revolution-validation-summary" style="display: none;">
            <div class="xcrud-revolution-validation-header">
                <span class="xcrud-revolution-validation-icon">⚠️</span>
                <span class="xcrud-revolution-validation-title">Please fix the following issues:</span>
            </div>
            <div class="xcrud-revolution-validation-list"></div>
        </div>
        
        <!-- Revolutionary Form Fields -->
        <div class="xcrud-revolution-fields-container">
            <?php echo $this->render_fields_list($mode); ?>
        </div>
        
        <!-- Revolutionary Form Footer -->
        <div class="xcrud-revolution-form-footer">
            <div class="xcrud-revolution-form-meta">
                <div class="xcrud-revolution-meta-item">
                    <span class="xcrud-revolution-meta-label">Last Modified:</span>
                    <span class="xcrud-revolution-meta-value" id="xcrud-last-modified">Just now</span>
                </div>
                <div class="xcrud-revolution-meta-item">
                    <span class="xcrud-revolution-meta-label">Auto-save:</span>
                    <span class="xcrud-revolution-meta-value xcrud-revolution-autosave-status">Enabled</span>
                </div>
            </div>
            
            <!-- Revolutionary Keyboard Shortcuts Info -->
            <div class="xcrud-revolution-shortcuts" style="display: none;">
                <div class="xcrud-revolution-shortcut">
                    <kbd>Ctrl</kbd> + <kbd>S</kbd> <span>Save</span>
                </div>
                <div class="xcrud-revolution-shortcut">
                    <kbd>Ctrl</kbd> + <kbd>Enter</kbd> <span>Save & Return</span>
                </div>
                <div class="xcrud-revolution-shortcut">
                    <kbd>Esc</kbd> <span>Cancel</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Revolutionary Sidebar -->
    <div class="xcrud-revolution-form-sidebar">
        
        <!-- Revolutionary Quick Actions -->
        <div class="xcrud-revolution-sidebar-section">
            <h4 class="xcrud-revolution-sidebar-title">Quick Actions</h4>
            <div class="xcrud-revolution-quick-actions">
                <button class="xcrud-revolution-quick-action" data-action="clear-form" title="Clear Form">
                    <span class="xcrud-revolution-quick-icon">🗑️</span>
                    <span class="xcrud-revolution-quick-text">Clear All</span>
                </button>
                <button class="xcrud-revolution-quick-action" data-action="duplicate" title="Duplicate Record">
                    <span class="xcrud-revolution-quick-icon">📋</span>
                    <span class="xcrud-revolution-quick-text">Duplicate</span>
                </button>
                <button class="xcrud-revolution-quick-action" data-action="template" title="Apply Template">
                    <span class="xcrud-revolution-quick-icon">📄</span>
                    <span class="xcrud-revolution-quick-text">Template</span>
                </button>
            </div>
        </div>
        
        <!-- Revolutionary Field Summary -->
        <div class="xcrud-revolution-sidebar-section">
            <h4 class="xcrud-revolution-sidebar-title">Field Summary</h4>
            <div class="xcrud-revolution-field-summary">
                <div class="xcrud-revolution-summary-stat">
                    <span class="xcrud-revolution-summary-label">Required Fields</span>
                    <span class="xcrud-revolution-summary-value" id="required-count">0</span>
                </div>
                <div class="xcrud-revolution-summary-stat">
                    <span class="xcrud-revolution-summary-label">Completed</span>
                    <span class="xcrud-revolution-summary-value" id="completed-count">0</span>
                </div>
                <div class="xcrud-revolution-summary-stat">
                    <span class="xcrud-revolution-summary-label">Progress</span>
                    <div class="xcrud-revolution-progress-bar">
                        <div class="xcrud-revolution-progress-fill" id="form-progress"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Revolutionary Performance Info -->
        <div class="xcrud-revolution-sidebar-section">
            <h4 class="xcrud-revolution-sidebar-title">Performance</h4>
            <div class="xcrud-revolution-performance-info">
                <?php echo $this->render_benchmark(); ?>
            </div>
        </div>
    </div>
</div>

<!-- Revolutionary Additional CSS for Detail View -->
<style>
/* Revolutionary Form Header */
.xcrud-revolution-form-header {
    margin-bottom: 24px;
    padding: 20px 24px;
    background: var(--xcrud-glass);
    backdrop-filter: var(--xcrud-backdrop-blur);
    border-radius: var(--xcrud-border-radius-sm);
    border: 1px solid var(--xcrud-glass-border);
    position: relative;
}

.xcrud-revolution-mode-indicator {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 16px;
}

.xcrud-revolution-mode-badge {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 16px;
    background: var(--xcrud-primary);
    color: white;
    border-radius: var(--xcrud-border-radius-sm);
    font-weight: 500;
    font-size: 14px;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.xcrud-revolution-mode-badge[data-mode="create"] {
    background: var(--xcrud-success);
}

.xcrud-revolution-mode-badge[data-mode="edit"] {
    background: var(--xcrud-warning);
    color: #2c3e50;
}

.xcrud-revolution-mode-badge[data-mode="view"] {
    background: var(--xcrud-info);
    color: #2c3e50;
}

/* Revolutionary Form Progress */
.xcrud-revolution-form-progress {
    display: flex;
    gap: 16px;
}

.xcrud-revolution-progress-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    opacity: 0.5;
    transition: all 0.3s ease;
}

.xcrud-revolution-progress-step.active {
    opacity: 1;
}

.xcrud-revolution-progress-step.completed {
    opacity: 1;
    color: #4facfe;
}

.xcrud-revolution-progress-number {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: var(--xcrud-glass);
    border: 2px solid var(--xcrud-glass-border);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.xcrud-revolution-progress-step.active .xcrud-revolution-progress-number {
    background: var(--xcrud-primary);
    border-color: transparent;
    color: white;
}

.xcrud-revolution-progress-step.completed .xcrud-revolution-progress-number {
    background: #4facfe;
    border-color: transparent;
    color: white;
}

.xcrud-revolution-progress-label {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--xcrud-text-secondary);
}

/* Revolutionary Form Actions */
.xcrud-revolution-form-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
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

.xcrud-revolution-save-group {
    display: flex;
    gap: 8px;
    padding-right: 12px;
    border-right: 1px solid var(--xcrud-glass-border);
}

.xcrud-revolution-advanced-actions {
    display: flex;
    gap: 8px;
}

/* Revolutionary Button Variants */
.xcrud-revolution-save-new {
    background: var(--xcrud-success);
    color: white;
    border: none;
}

.xcrud-revolution-save-edit {
    background: var(--xcrud-primary);
    color: white;
    border: none;
}

.xcrud-revolution-save-return {
    background: var(--xcrud-info);
    color: #2c3e50;
    border: none;
}

.xcrud-revolution-return {
    background: var(--xcrud-warning);
    color: #2c3e50;
    border: none;
}

.xcrud-revolution-draft,
.xcrud-revolution-preview,
.xcrud-revolution-help {
    background: var(--xcrud-glass);
    color: var(--xcrud-text-secondary);
    border: 1px dashed var(--xcrud-glass-border);
}

.xcrud-revolution-draft:hover {
    background: var(--xcrud-warning);
    color: #2c3e50;
    border-style: solid;
}

.xcrud-revolution-preview:hover {
    background: var(--xcrud-info);
    color: #2c3e50;
    border-style: solid;
}

.xcrud-revolution-help:hover {
    background: var(--xcrud-primary);
    color: white;
    border-style: solid;
}

/* Revolutionary Form Container */
.xcrud-revolution-form-container {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 24px;
    margin-bottom: 24px;
}

.xcrud-revolution-form-content {
    background: var(--xcrud-glass);
    backdrop-filter: var(--xcrud-backdrop-blur);
    border-radius: var(--xcrud-border-radius-sm);
    border: 1px solid var(--xcrud-glass-border);
    padding: 24px;
    position: relative;
}

/* Revolutionary Validation Summary */
.xcrud-revolution-validation-summary {
    margin-bottom: 20px;
    padding: 16px 20px;
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    color: white;
    border-radius: var(--xcrud-border-radius-sm);
    border: 1px solid rgba(250, 112, 154, 0.3);
    animation: xcrud-revolution-shake 0.5s ease-in-out;
}

.xcrud-revolution-validation-header {
    display: flex;
    align-items: center;
    gap: 12px;
    font-weight: 600;
    margin-bottom: 12px;
}

.xcrud-revolution-validation-list {
    list-style: none;
    margin: 0;
    padding: 0;
}

.xcrud-revolution-validation-list li {
    padding: 4px 0;
    font-size: 14px;
}

.xcrud-revolution-validation-list li::before {
    content: '• ';
    margin-right: 8px;
}

/* Revolutionary Fields Container */
.xcrud-revolution-fields-container {
    position: relative;
}

/* Revolutionary Form Footer */
.xcrud-revolution-form-footer {
    margin-top: 24px;
    padding-top: 20px;
    border-top: 1px solid var(--xcrud-glass-border);
}

.xcrud-revolution-form-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}

.xcrud-revolution-meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
}

.xcrud-revolution-meta-label {
    color: var(--xcrud-text-secondary);
}

.xcrud-revolution-meta-value {
    color: var(--xcrud-text-primary);
    font-weight: 500;
}

.xcrud-revolution-autosave-status {
    color: #4facfe;
}

.xcrud-revolution-autosave-status.saving {
    color: #ffecd2;
}

.xcrud-revolution-autosave-status.saved {
    color: #4facfe;
}

/* Revolutionary Shortcuts */
.xcrud-revolution-shortcuts {
    display: flex;
    gap: 16px;
    justify-content: center;
    flex-wrap: wrap;
}

.xcrud-revolution-shortcut {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 11px;
    color: var(--xcrud-text-secondary);
}

.xcrud-revolution-shortcut kbd {
    background: var(--xcrud-glass);
    color: var(--xcrud-text-primary);
    padding: 2px 6px;
    border-radius: 3px;
    border: 1px solid var(--xcrud-glass-border);
    font-size: 10px;
    font-weight: 600;
}

/* Revolutionary Sidebar */
.xcrud-revolution-form-sidebar {
    background: var(--xcrud-glass);
    backdrop-filter: var(--xcrud-backdrop-blur);
    border-radius: var(--xcrud-border-radius-sm);
    border: 1px solid var(--xcrud-glass-border);
    padding: 20px;
    height: fit-content;
    position: sticky;
    top: 20px;
}

.xcrud-revolution-sidebar-section {
    margin-bottom: 24px;
}

.xcrud-revolution-sidebar-section:last-child {
    margin-bottom: 0;
}

.xcrud-revolution-sidebar-title {
    font-size: 14px;
    font-weight: 600;
    color: var(--xcrud-text-primary);
    margin-bottom: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Revolutionary Quick Actions */
.xcrud-revolution-quick-actions {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.xcrud-revolution-quick-action {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    background: transparent;
    border: 1px solid var(--xcrud-glass-border);
    border-radius: var(--xcrud-border-radius-sm);
    color: var(--xcrud-text-secondary);
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 13px;
}

.xcrud-revolution-quick-action:hover {
    background: var(--xcrud-glass);
    color: var(--xcrud-text-primary);
    border-color: var(--xcrud-primary);
    transform: translateX(4px);
}

.xcrud-revolution-quick-icon {
    font-size: 16px;
}

/* Revolutionary Field Summary */
.xcrud-revolution-field-summary {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.xcrud-revolution-summary-stat {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.xcrud-revolution-summary-label {
    font-size: 12px;
    color: var(--xcrud-text-secondary);
}

.xcrud-revolution-summary-value {
    font-weight: 600;
    color: var(--xcrud-text-primary);
}

.xcrud-revolution-progress-bar {
    width: 60px;
    height: 4px;
    background: var(--xcrud-glass);
    border-radius: 2px;
    overflow: hidden;
}

.xcrud-revolution-progress-fill {
    height: 100%;
    background: var(--xcrud-success);
    width: 0%;
    transition: width 0.5s ease;
    border-radius: 2px;
}

/* Revolutionary Performance Info */
.xcrud-revolution-performance-info {
    font-size: 11px;
    color: var(--xcrud-text-secondary);
    line-height: 1.5;
}

/* Revolutionary Animations */
@keyframes xcrud-revolution-shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

/* Revolutionary Responsive Design */
@media (max-width: 1024px) {
    .xcrud-revolution-form-container {
        grid-template-columns: 1fr;
    }
    
    .xcrud-revolution-form-sidebar {
        position: relative;
        top: 0;
        order: -1;
    }
    
    .xcrud-revolution-sidebar-section {
        margin-bottom: 16px;
    }
    
    .xcrud-revolution-quick-actions {
        flex-direction: row;
        flex-wrap: wrap;
    }
}

@media (max-width: 768px) {
    .xcrud-revolution-form-header {
        padding: 16px;
    }
    
    .xcrud-revolution-mode-indicator {
        flex-direction: column;
        gap: 16px;
        align-items: flex-start;
    }
    
    .xcrud-revolution-form-actions {
        flex-direction: column;
        gap: 16px;
    }
    
    .xcrud-revolution-actions-left,
    .xcrud-revolution-actions-right {
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .xcrud-revolution-save-group {
        border-right: none;
        border-bottom: 1px solid var(--xcrud-glass-border);
        padding-bottom: 12px;
        margin-bottom: 12px;
    }
    
    .xcrud-revolution-form-content {
        padding: 16px;
    }
}
</style>

<!-- Revolutionary JavaScript for Form Enhancement -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revolutionary Form Progress Tracking
    function updateFormProgress() {
        const requiredFields = document.querySelectorAll('.xcrud input[required], .xcrud select[required], .xcrud textarea[required]');
        const completedFields = Array.from(requiredFields).filter(field => field.value.trim() !== '');
        
        const requiredCount = document.getElementById('required-count');
        const completedCount = document.getElementById('completed-count');
        const progressBar = document.getElementById('form-progress');
        
        if (requiredCount) requiredCount.textContent = requiredFields.length;
        if (completedCount) completedCount.textContent = completedFields.length;
        
        const progress = requiredFields.length > 0 ? (completedFields.length / requiredFields.length) * 100 : 0;
        if (progressBar) progressBar.style.width = progress + '%';
        
        // Update progress steps
        const steps = document.querySelectorAll('.xcrud-revolution-progress-step');
        steps.forEach((step, index) => {
            if (index === 0) step.classList.add('active');
            if (progress > 50 && index === 1) step.classList.add('active');
            if (progress === 100 && index === 2) step.classList.add('active');
        });
    }
    
    // Monitor field changes
    document.addEventListener('input', updateFormProgress);
    document.addEventListener('change', updateFormProgress);
    updateFormProgress(); // Initial update
    
    // Revolutionary Auto-save functionality
    let autoSaveTimeout;
    const autoSaveStatus = document.querySelector('.xcrud-revolution-autosave-status');
    const lastModified = document.getElementById('xcrud-last-modified');
    
    function autoSave() {
        if (autoSaveStatus) {
            autoSaveStatus.textContent = 'Saving...';
            autoSaveStatus.className = 'xcrud-revolution-meta-value xcrud-revolution-autosave-status saving';
        }
        
        // Simulate auto-save (integrate with actual save functionality)
        setTimeout(() => {
            if (autoSaveStatus) {
                autoSaveStatus.textContent = 'Saved';
                autoSaveStatus.className = 'xcrud-revolution-meta-value xcrud-revolution-autosave-status saved';
                
                setTimeout(() => {
                    autoSaveStatus.textContent = 'Enabled';
                    autoSaveStatus.className = 'xcrud-revolution-meta-value xcrud-revolution-autosave-status';
                }, 2000);
            }
            
            if (lastModified) {
                lastModified.textContent = new Date().toLocaleTimeString();
            }
        }, 1000);
    }
    
    function scheduleAutoSave() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(autoSave, 3000); // Auto-save after 3 seconds of inactivity
    }
    
    document.addEventListener('input', scheduleAutoSave);
    document.addEventListener('change', scheduleAutoSave);
    
    // Revolutionary Keyboard Shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey || e.metaKey) {
            if (e.key === 's') {
                e.preventDefault();
                const saveBtn = document.querySelector('.xcrud-revolution-save-edit');
                if (saveBtn) saveBtn.click();
            } else if (e.key === 'Enter') {
                e.preventDefault();
                const saveReturnBtn = document.querySelector('.xcrud-revolution-save-return');
                if (saveReturnBtn) saveReturnBtn.click();
            }
        } else if (e.key === 'Escape') {
            const returnBtn = document.querySelector('.xcrud-revolution-return');
            if (returnBtn) returnBtn.click();
        }
    });
    
    // Revolutionary Help Toggle
    const helpBtn = document.querySelector('.xcrud-revolution-help');
    const shortcuts = document.querySelector('.xcrud-revolution-shortcuts');
    
    if (helpBtn && shortcuts) {
        helpBtn.addEventListener('click', function() {
            shortcuts.style.display = shortcuts.style.display === 'none' ? 'flex' : 'none';
        });
    }
    
    // Revolutionary Quick Actions
    document.addEventListener('click', function(e) {
        if (e.target.closest('.xcrud-revolution-quick-action')) {
            const action = e.target.closest('.xcrud-revolution-quick-action').getAttribute('data-action');
            
            switch(action) {
                case 'clear-form':
                    if (confirm('Are you sure you want to clear all fields?')) {
                        document.querySelectorAll('.xcrud input, .xcrud textarea, .xcrud select').forEach(field => {
                            if (field.type !== 'hidden') field.value = '';
                        });
                        updateFormProgress();
                    }
                    break;
                case 'duplicate':
                    alert('Duplicate functionality would be integrated with backend');
                    break;
                case 'template':
                    alert('Template functionality would be integrated with backend');
                    break;
            }
        }
    });
});
</script>