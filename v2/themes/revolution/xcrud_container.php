<?php
/**
 * xCrudRevolution Container Template
 * Revolutionary glassmorphism container with advanced animations
 */
?>
<div class="xcrud xcrud-revolution<?php echo $this->is_rtl ? ' xcrud_rtl' : ''?>" data-instance="<?php echo $this->instance_name ?>" data-theme="revolution">
    
    <!-- Revolutionary Header with Dynamic Background -->
    <div class="xcrud-revolution-header">
        <?php echo $this->render_table_name(false, 'div', true)?>
        
        <!-- Revolutionary Status Indicator -->
        <div class="xcrud-revolution-status" data-status="ready">
            <div class="xcrud-revolution-status-dot"></div>
            <span class="xcrud-revolution-status-text">Ready</span>
        </div>
    </div>
    
    <!-- Revolutionary Main Container with Glassmorphism -->
    <div class="xcrud-container xcrud-revolution-container"<?php echo ($this->start_minimized) ? ' style="display:none;"' : '' ?>>
        
        <!-- Revolutionary Progress Bar -->
        <div class="xcrud-revolution-progress">
            <div class="xcrud-revolution-progress-bar"></div>
        </div>
        
        <!-- Revolutionary Content Area -->
        <div class="xcrud-ajax xcrud-revolution-content" id="xcrud-<?php echo $this->instance_name ?>">
            <?php echo $this->render_view() ?>
        </div>
        
        <!-- Revolutionary Overlay with Animated Background -->
        <div class="xcrud-overlay xcrud-revolution-overlay">
            <div class="xcrud-revolution-loader">
                <div class="xcrud-revolution-loader-ring"></div>
                <div class="xcrud-revolution-loader-ring"></div>
                <div class="xcrud-revolution-loader-ring"></div>
                <span class="xcrud-revolution-loader-text">Processing...</span>
            </div>
        </div>
        
        <!-- Revolutionary Floating Action Button -->
        <div class="xcrud-revolution-fab">
            <button class="xcrud-revolution-fab-button" title="Quick Actions">
                <span class="xcrud-revolution-fab-icon">⚡</span>
            </button>
            <div class="xcrud-revolution-fab-menu">
                <a href="#" class="xcrud-revolution-fab-action" data-action="refresh" title="Refresh">🔄</a>
                <a href="#" class="xcrud-revolution-fab-action" data-action="export" title="Export">📊</a>
                <a href="#" class="xcrud-revolution-fab-action" data-action="settings" title="Settings">⚙️</a>
            </div>
        </div>
    </div>
    
    <!-- Revolutionary Footer -->
    <div class="xcrud-revolution-footer">
        <div class="xcrud-revolution-footer-content">
            <span class="xcrud-revolution-powered">Powered by <strong>xCrudRevolution</strong></span>
            <div class="xcrud-revolution-theme-toggle">
                <button class="xcrud-revolution-theme-btn" data-theme="auto" title="Auto Theme">🔄</button>
                <button class="xcrud-revolution-theme-btn" data-theme="light" title="Light Theme">☀️</button>
                <button class="xcrud-revolution-theme-btn" data-theme="dark" title="Dark Theme">🌙</button>
            </div>
        </div>
    </div>
</div>

<!-- Revolutionary Additional CSS for Container Enhancements -->
<style>
.xcrud-revolution {
    --xcrud-animation-duration: 0.6s;
    --xcrud-animation-easing: cubic-bezier(0.34, 1.56, 0.64, 1);
    position: relative;
    animation: xcrud-revolution-fade-in var(--xcrud-animation-duration) var(--xcrud-animation-easing);
}

/* Revolutionary Header */
.xcrud-revolution-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 24px;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
    backdrop-filter: blur(10px);
    border-radius: 12px 12px 0 0;
    border: 1px solid rgba(255, 255, 255, 0.1);
    margin-bottom: -1px;
}

.xcrud-revolution-status {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    color: var(--xcrud-text-secondary);
}

.xcrud-revolution-status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #4facfe;
    animation: xcrud-revolution-pulse 2s infinite;
}

/* Revolutionary Container Enhancements */
.xcrud-revolution-container {
    border-radius: 0 0 16px 16px;
    border-top: none;
    position: relative;
    overflow: hidden;
}

/* Revolutionary Progress Bar */
.xcrud-revolution-progress {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: rgba(255, 255, 255, 0.1);
    overflow: hidden;
    z-index: 1000;
}

.xcrud-revolution-progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #4facfe, #00f2fe, #4facfe);
    background-size: 200% 100%;
    width: 0%;
    transition: width 0.3s ease;
    animation: xcrud-revolution-gradient-flow 2s linear infinite;
}

.xcrud-revolution-progress-bar.active {
    width: 100%;
}

/* Revolutionary Content Area */
.xcrud-revolution-content {
    min-height: 200px;
    position: relative;
}

/* Revolutionary Overlay & Loader */
.xcrud-revolution-overlay {
    background: rgba(15, 15, 35, 0.95);
    backdrop-filter: blur(8px);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
}

.xcrud-revolution-loader {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 20px;
}

.xcrud-revolution-loader-ring {
    width: 60px;
    height: 60px;
    border: 4px solid rgba(79, 172, 254, 0.2);
    border-top: 4px solid #4facfe;
    border-radius: 50%;
    animation: xcrud-revolution-spin 1s linear infinite;
}

.xcrud-revolution-loader-ring:nth-child(2) {
    width: 80px;
    height: 80px;
    margin-top: -70px;
    animation-duration: 1.5s;
    animation-direction: reverse;
}

.xcrud-revolution-loader-ring:nth-child(3) {
    width: 100px;
    height: 100px;
    margin-top: -90px;
    animation-duration: 2s;
    border-color: rgba(250, 112, 154, 0.2);
    border-top-color: #fa709a;
}

.xcrud-revolution-loader-text {
    color: white;
    font-weight: 500;
    font-size: 16px;
    animation: xcrud-revolution-fade 1.5s ease-in-out infinite alternate;
}

/* Revolutionary FAB */
.xcrud-revolution-fab {
    position: absolute;
    bottom: 24px;
    right: 24px;
    z-index: 1000;
}

.xcrud-revolution-fab-button {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    font-size: 24px;
    cursor: pointer;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    display: flex;
    align-items: center;
    justify-content: center;
}

.xcrud-revolution-fab-button:hover {
    transform: scale(1.1) rotate(90deg);
    box-shadow: 0 12px 35px rgba(102, 126, 234, 0.6);
}

.xcrud-revolution-fab-menu {
    position: absolute;
    bottom: 70px;
    right: 0;
    display: flex;
    flex-direction: column;
    gap: 12px;
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    pointer-events: none;
}

.xcrud-revolution-fab:hover .xcrud-revolution-fab-menu {
    opacity: 1;
    transform: translateY(0);
    pointer-events: all;
}

.xcrud-revolution-fab-action {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.9);
    color: #333;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.xcrud-revolution-fab-action:hover {
    transform: scale(1.1);
    background: white;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
}

/* Revolutionary Footer */
.xcrud-revolution-footer {
    margin-top: 16px;
    padding: 12px 24px;
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border-radius: 0 0 16px 16px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-top: none;
}

.xcrud-revolution-footer-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 12px;
    color: var(--xcrud-text-secondary);
}

.xcrud-revolution-powered strong {
    background: linear-gradient(135deg, #667eea, #764ba2);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.xcrud-revolution-theme-toggle {
    display: flex;
    gap: 8px;
}

.xcrud-revolution-theme-btn {
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    color: var(--xcrud-text-secondary);
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}

.xcrud-revolution-theme-btn:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: scale(1.1);
}

.xcrud-revolution-theme-btn.active {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

/* Revolutionary Animations */
@keyframes xcrud-revolution-fade-in {
    0% {
        opacity: 0;
        transform: translateY(20px) scale(0.95);
    }
    100% {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@keyframes xcrud-revolution-pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

@keyframes xcrud-revolution-spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

@keyframes xcrud-revolution-gradient-flow {
    0% { background-position: 0% 50%; }
    100% { background-position: 200% 50%; }
}

@keyframes xcrud-revolution-fade {
    0% { opacity: 0.7; }
    100% { opacity: 1; }
}

/* Revolutionary Responsive Design */
@media (max-width: 768px) {
    .xcrud-revolution-header {
        padding: 12px 16px;
        flex-direction: column;
        gap: 8px;
    }
    
    .xcrud-revolution-container {
        margin: 10px;
    }
    
    .xcrud-revolution-fab {
        bottom: 16px;
        right: 16px;
    }
    
    .xcrud-revolution-fab-button {
        width: 48px;
        height: 48px;
        font-size: 20px;
    }
    
    .xcrud-revolution-footer-content {
        flex-direction: column;
        gap: 8px;
        text-align: center;
    }
}
</style>