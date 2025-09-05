<?php
// Statistics about the framework
$total_demos = count($pagedata);
$new_features = 8; // Count of revolutionary features
$supported_databases = 3; // MySQL, PostgreSQL, SQLite
$sql_operators = 15; // Number of SQL operators supported
?>

<div class="stats-grid">
    <div class="stat-card primary">
        <div class="icon">
            <i class="fas fa-rocket"></i>
        </div>
        <h3><?php echo $total_demos; ?></h3>
        <p>Interactive Demos</p>
    </div>
    
    <div class="stat-card success">
        <div class="icon">
            <i class="fas fa-database"></i>
        </div>
        <h3><?php echo $supported_databases; ?></h3>
        <p>Database Engines</p>
    </div>
    
    <div class="stat-card warning">
        <div class="icon">
            <i class="fas fa-search"></i>
        </div>
        <h3><?php echo $sql_operators; ?>+</h3>
        <p>SQL Operators</p>
    </div>
    
    <div class="stat-card danger">
        <div class="icon">
            <i class="fas fa-code"></i>
        </div>
        <h3><?php echo $new_features; ?></h3>
        <p>New Features</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 30px; margin-top: 40px;">
    
    <!-- Welcome Section -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; border-radius: 20px; color: white; grid-column: 1 / -1;">
        <h2 style="margin: 0 0 15px 0; font-size: 2.5rem; display: flex; align-items: center; gap: 15px;">
            <span style="font-size: 3rem;">🚀</span>
            Welcome to xCrudRevolution!
        </h2>
        <p style="font-size: 1.2rem; margin: 0 0 20px 0; opacity: 0.9; line-height: 1.6;">
            The next evolution of the popular xCrud library. Built for PHP 8+, multi-database support, 
            and modern web development practices. Explore all the revolutionary features below!
        </p>
        <div style="display: flex; gap: 15px; flex-wrap: wrap;">
            <a href="?page=operators_mysql" style="background: rgba(255,255,255,0.2); padding: 10px 20px; border-radius: 8px; color: white; text-decoration: none; transition: all 0.3s;">
                <i class="fas fa-play"></i> Try SQL Operators
            </a>
            <a href="?page=callbacks" style="background: rgba(255,255,255,0.2); padding: 10px 20px; border-radius: 8px; color: white; text-decoration: none; transition: all 0.3s;">
                <i class="fas fa-code"></i> Explore Callbacks
            </a>
            <a href="?page=mass_operations" style="background: rgba(255,255,255,0.2); padding: 10px 20px; border-radius: 8px; color: white; text-decoration: none; transition: all 0.3s;">
                <i class="fas fa-check-double"></i> Mass Operations
            </a>
        </div>
    </div>
    
    <!-- Getting Started -->
    <div class="demo-container" style="margin-bottom: 0;">
        <h3 style="color: #667eea; display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
            <i class="fas fa-play-circle"></i> Quick Start
        </h3>
        <div style="margin-bottom: 15px;">
            <a href="?page=default" style="display: block; padding: 12px 15px; background: #f8f9fa; border-radius: 8px; text-decoration: none; color: #495057; margin-bottom: 8px; transition: all 0.3s;">
                <i class="fas fa-rocket" style="color: #667eea; width: 20px;"></i> Simple Usage
            </a>
            <a href="?page=base_field_types" style="display: block; padding: 12px 15px; background: #f8f9fa; border-radius: 8px; text-decoration: none; color: #495057; margin-bottom: 8px; transition: all 0.3s;">
                <i class="fas fa-keyboard" style="color: #28a745; width: 20px;"></i> Field Types
            </a>
            <a href="?page=validation" style="display: block; padding: 12px 15px; background: #f8f9fa; border-radius: 8px; text-decoration: none; color: #495057; transition: all 0.3s;">
                <i class="fas fa-check-circle" style="color: #17a2b8; width: 20px;"></i> Validation
            </a>
        </div>
    </div>
    
    <!-- Revolutionary Features -->
    <div class="demo-container" style="margin-bottom: 0;">
        <h3 style="color: #e91e63; display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
            <i class="fas fa-star"></i> Revolutionary Features
            <span style="background: #e91e63; color: white; font-size: 0.7rem; padding: 2px 6px; border-radius: 4px;">NEW</span>
        </h3>
        <div style="margin-bottom: 15px;">
            <a href="?page=operators_mysql" style="display: block; padding: 12px 15px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 8px; text-decoration: none; color: white; margin-bottom: 8px; transition: all 0.3s;">
                <i class="fas fa-search" style="width: 20px;"></i> Advanced SQL Operators
            </a>
            <a href="?page=callbacks" style="display: block; padding: 12px 15px; background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); border-radius: 8px; text-decoration: none; color: white; margin-bottom: 8px; transition: all 0.3s;">
                <i class="fas fa-code" style="width: 20px;"></i> Complete Hook System
            </a>
            <a href="?page=mass_operations" style="display: block; padding: 12px 15px; background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); border-radius: 8px; text-decoration: none; color: white; transition: all 0.3s;">
                <i class="fas fa-check-double" style="width: 20px;"></i> Mass Operations
            </a>
        </div>
    </div>
    
    <!-- Database Support -->
    <div class="demo-container" style="margin-bottom: 0;">
        <h3 style="color: #8b5cf6; display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
            <i class="fas fa-database"></i> Multi-Database
        </h3>
        <div style="display: grid; gap: 10px;">
            <div style="display: flex; align-items: center; gap: 10px; padding: 10px; background: #f0f9ff; border-radius: 8px;">
                <div style="width: 40px; height: 40px; background: #3b82f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                    🐬
                </div>
                <div>
                    <div style="font-weight: 600;">MySQL</div>
                    <div style="font-size: 0.85rem; color: #6b7280;">Native support</div>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 10px; padding: 10px; background: #f0f9ff; border-radius: 8px;">
                <div style="width: 40px; height: 40px; background: #336791; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                    🐘
                </div>
                <div>
                    <div style="font-weight: 600;">PostgreSQL</div>
                    <div style="font-size: 0.85rem; color: #6b7280;">Advanced features</div>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 10px; padding: 10px; background: #f0f9ff; border-radius: 8px;">
                <div style="width: 40px; height: 40px; background: #003b57; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                    🪶
                </div>
                <div>
                    <div style="font-weight: 600;">SQLite</div>
                    <div style="font-size: 0.85rem; color: #6b7280;">Lightweight</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Advanced Features -->
    <div class="demo-container" style="margin-bottom: 0;">
        <h3 style="color: #f59e0b; display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
            <i class="fas fa-cog"></i> Advanced Features
        </h3>
        <div style="display: grid; gap: 8px; font-size: 0.9rem;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-layer-group" style="color: #10b981; width: 16px;"></i>
                <a href="?page=nested" style="text-decoration: none; color: #374151;">Nested Tables</a>
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-project-diagram" style="color: #3b82f6; width: 16px;"></i>
                <a href="?page=fk_relation" style="text-decoration: none; color: #374151;">Many-to-Many Relations</a>
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-upload" style="color: #8b5cf6; width: 16px;"></i>
                <a href="?page=uploads" style="text-decoration: none; color: #374151;">File Uploads</a>
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-edit" style="color: #f59e0b; width: 16px;"></i>
                <a href="?page=cke_and_custom" style="text-decoration: none; color: #374151;">Rich Text Editor</a>
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-clone" style="color: #ef4444; width: 16px;"></i>
                <a href="?page=multi_instance" style="text-decoration: none; color: #374151;">Multiple Instances</a>
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-highlighter" style="color: #ec4899; width: 16px;"></i>
                <a href="?page=highlights" style="text-decoration: none; color: #374151;">Row Highlighting</a>
            </div>
        </div>
    </div>
    
    <!-- What's New -->
    <div class="demo-container" style="margin-bottom: 0;">
        <h3 style="color: #dc2626; display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
            <i class="fas fa-fire"></i> What's New in Revolution
        </h3>
        <div style="font-size: 0.9rem; line-height: 1.6;">
            <div style="display: flex; align-items: start; gap: 10px; margin-bottom: 12px;">
                <div style="background: #dc2626; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; flex-shrink: 0;">✨</div>
                <div><strong>15+ SQL Operators:</strong> IN, BETWEEN, LIKE, REGEXP, EXISTS and more</div>
            </div>
            <div style="display: flex; align-items: start; gap: 10px; margin-bottom: 12px;">
                <div style="background: #dc2626; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; flex-shrink: 0;">🔧</div>
                <div><strong>Complete Hook System:</strong> Before/after/replace callbacks</div>
            </div>
            <div style="display: flex; align-items: start; gap: 10px; margin-bottom: 12px;">
                <div style="background: #dc2626; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; flex-shrink: 0;">🗄️</div>
                <div><strong>Multi-Database:</strong> MySQL, PostgreSQL, SQLite support</div>
            </div>
            <div style="display: flex; align-items: start; gap: 10px; margin-bottom: 12px;">
                <div style="background: #dc2626; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; flex-shrink: 0;">⚡</div>
                <div><strong>PHP 8+ Ready:</strong> Modern syntax and performance</div>
            </div>
            <div style="display: flex; align-items: start; gap: 10px;">
                <div style="background: #dc2626; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; flex-shrink: 0;">🎨</div>
                <div><strong>Modern UI:</strong> Beautiful, responsive interface</div>
            </div>
        </div>
    </div>
    
</div>

<style>
.demo-container:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.demo-container a:hover {
    transform: translateX(5px);
}

.stat-card:hover {
    transform: translateY(-8px);
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.stat-card .icon {
    animation: pulse 2s infinite;
}
</style>

<script>
// Add some interactive effects
document.addEventListener('DOMContentLoaded', function() {
    // Animate stat cards on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    document.querySelectorAll('.stat-card, .demo-container').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.6s ease';
        observer.observe(card);
    });
    
    // Animate stats counting up
    document.querySelectorAll('.stat-card h3').forEach(stat => {
        const finalValue = parseInt(stat.textContent);
        let currentValue = 0;
        const increment = finalValue / 30;
        const timer = setInterval(() => {
            currentValue += increment;
            if (currentValue >= finalValue) {
                currentValue = finalValue;
                clearInterval(timer);
            }
            stat.textContent = Math.floor(currentValue) + (stat.textContent.includes('+') ? '+' : '');
        }, 50);
    });
});
</script>