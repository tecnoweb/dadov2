<?php
/**
 * xCrudRevolution Container Template
 * Simplified revolutionary container for better compatibility
 */
?>
<div class="xcrud xcrud-revolution<?php echo $this->is_rtl ? ' xcrud_rtl' : ''?>" data-instance="<?php echo $this->instance_name ?>" data-theme="revolution">
    
    <!-- Revolutionary Header -->
    <div class="xcrud-revolution-header">
        <?php echo $this->render_table_name(false, 'div', true)?>
    </div>
    
    <!-- Revolutionary Main Container -->
    <div class="xcrud-container xcrud-revolution-container"<?php echo ($this->start_minimized) ? ' style="display:none;"' : '' ?>>
        
        <!-- Revolutionary Content Area -->
        <div class="xcrud-ajax xcrud-revolution-content" id="xcrud-<?php echo $this->instance_name ?>">
            <?php echo $this->render_view() ?>
        </div>
        
        <!-- Revolutionary Overlay -->
        <div class="xcrud-overlay xcrud-revolution-overlay">
            <div class="xcrud-revolution-loader">
                <div class="xcrud-revolution-loader-ring"></div>
                <span class="xcrud-revolution-loader-text">Processing...</span>
            </div>
        </div>
    </div>
</div>

<!-- Revolutionary Additional CSS for Container -->
<style>
.xcrud-revolution {
    font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
}

.xcrud-revolution-header {
    margin-bottom: 16px;
    padding: 16px 0;
}

.xcrud-revolution-container {
    position: relative;
}

.xcrud-revolution-content {
    position: relative;
}

.xcrud-revolution-overlay {
    background: rgba(255, 255, 255, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
}

.xcrud-revolution-loader {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 16px;
}

.xcrud-revolution-loader-ring {
    width: 40px;
    height: 40px;
    border: 3px solid rgba(102, 126, 234, 0.2);
    border-top: 3px solid #667eea;
    border-radius: 50%;
    animation: xcrud-revolution-spin 1s linear infinite;
}

.xcrud-revolution-loader-text {
    color: #667eea;
    font-weight: 500;
    font-size: 14px;
}

@keyframes xcrud-revolution-spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>