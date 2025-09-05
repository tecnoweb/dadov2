<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>xCrudRevolution <?php echo $version; ?> - <?php echo $title_1 ?> / <?php echo $title_2 ?></title>
    
    <!-- Modern Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Prism for code highlighting -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css" rel="stylesheet" />
    
    <style>
        :root {
            /* Color Palette */
            --primary: #667eea;
            --primary-dark: #5a67d8;
            --primary-light: #7c3aed;
            --secondary: #48bb78;
            --danger: #f56565;
            --warning: #ed8936;
            --info: #4299e1;
            --dark: #1a202c;
            --gray-900: #2d3748;
            --gray-800: #4a5568;
            --gray-700: #718096;
            --gray-600: #a0aec0;
            --gray-500: #cbd5e0;
            --gray-400: #e2e8f0;
            --gray-300: #edf2f7;
            --gray-200: #f7fafc;
            --white: #ffffff;
            
            /* Gradients */
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-success: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            --gradient-danger: linear-gradient(135deg, #f56565 0%, #ed64a6 100%);
            --gradient-dark: linear-gradient(135deg, #2d3748 0%, #1a202c 100%);
            
            /* Shadows */
            --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            <?php if($theme == 'revolution'): ?>
            background: linear-gradient(135deg, #0f0f23 0%, #16213e 50%, #667eea 100%);
            <?php else: ?>
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            <?php endif; ?>
            min-height: 100vh;
            position: relative;
        }
        
        <?php if($theme == 'revolution'): ?>
        /* Revolutionary Theme Enhancements */
        .demo-container {
            background: rgba(255, 255, 255, 0.05) !important;
            backdrop-filter: blur(20px) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3) !important;
        }
        
        .content-area {
            background: rgba(255, 255, 255, 0.03) !important;
            backdrop-filter: blur(15px) !important;
        }
        
        .content-header {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.2), rgba(118, 75, 162, 0.2)) !important;
            backdrop-filter: blur(10px) !important;
            border-radius: 16px !important;
            margin-bottom: 24px !important;
        }
        
        .theme-selector a.active {
            background: linear-gradient(135deg, #667eea, #764ba2) !important;
            color: white !important;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4) !important;
            transform: translateY(-2px) !important;
        }
        <?php endif; ?>
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 20% 80%, rgba(102, 126, 234, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(118, 75, 162, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(102, 126, 234, 0.05) 0%, transparent 50%);
            z-index: 1;
        }
        
        .main-container {
            display: flex;
            min-height: 100vh;
            position: relative;
            z-index: 2;
        }
        
        /* Sidebar */
        .sidebar {
            width: 280px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-right: 1px solid rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-lg);
        }
        
        .sidebar-header {
            padding: 25px 20px;
            background: var(--gradient-primary);
            color: white;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .sidebar-header h1 {
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .sidebar-header .version {
            font-size: 0.85rem;
            opacity: 0.9;
            font-weight: 400;
        }
        
        .sidebar-header .logo {
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .menu-category {
            padding: 10px 15px;
            margin: 10px 10px 5px 10px;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--gray-700);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .menu-category i {
            font-size: 0.9rem;
            color: var(--primary);
        }
        
        .menu-items {
            padding: 0 10px 15px;
        }
        
        .menu-item {
            display: block;
            padding: 12px 15px;
            margin-bottom: 4px;
            color: var(--gray-800);
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.2s ease;
            font-size: 0.95rem;
            position: relative;
            overflow: hidden;
        }
        
        .menu-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--gradient-primary);
            transition: left 0.3s ease;
            z-index: -1;
        }
        
        .menu-item:hover {
            color: var(--primary);
            background: rgba(102, 126, 234, 0.1);
            transform: translateX(5px);
        }
        
        .menu-item.active {
            color: white;
            background: var(--gradient-primary);
            font-weight: 500;
            box-shadow: var(--shadow);
        }
        
        .menu-item .badge {
            float: right;
            background: var(--primary);
            color: white;
            font-size: 0.75rem;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: 600;
        }
        
        .menu-item.active .badge {
            background: white;
            color: var(--primary);
        }
        
        /* Content Area */
        .content-area {
            flex: 1;
            background: rgba(255, 255, 255, 0.98);
            margin: 20px;
            border-radius: 20px;
            box-shadow: var(--shadow-xl);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        
        .content-header {
            background: white;
            padding: 30px 40px;
            border-bottom: 1px solid var(--gray-300);
        }
        
        .content-header h2 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 10px;
        }
        
        .content-header .description {
            color: var(--gray-700);
            font-size: 1.05rem;
            line-height: 1.6;
        }
        
        .theme-selector {
            display: inline-flex;
            gap: 5px;
            margin-top: 15px;
            padding: 5px;
            background: var(--gray-200);
            border-radius: 8px;
        }
        
        .theme-selector a {
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            color: var(--gray-700);
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }
        
        .theme-selector a:hover {
            background: white;
            color: var(--primary);
        }
        
        .theme-selector a.active {
            background: var(--primary);
            color: white;
        }
        
        /* Content Body */
        .content-body {
            flex: 1;
            padding: 40px;
            overflow-y: auto;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
        
        .demo-container {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: var(--shadow-lg);
        }
        
        /* Code Block */
        .code-block {
            background: var(--dark);
            border-radius: 12px;
            margin: 30px 0;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }
        
        .code-header {
            background: var(--gray-900);
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .code-header span {
            color: var(--gray-500);
            font-size: 0.85rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .code-actions {
            display: flex;
            gap: 10px;
        }
        
        .code-actions button {
            background: rgba(255, 255, 255, 0.1);
            color: var(--gray-400);
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .code-actions button:hover {
            background: var(--primary);
            color: white;
        }
        
        .code-content {
            padding: 20px;
            overflow-x: auto;
        }
        
        .code-content pre {
            margin: 0;
            font-family: 'JetBrains Mono', 'Courier New', monospace;
            font-size: 0.95rem;
            line-height: 1.6;
        }
        
        /* Floating Action Button */
        .fab {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: var(--gradient-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            box-shadow: var(--shadow-xl);
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        .fab:hover {
            transform: scale(1.1) rotate(90deg);
        }
        
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-xl);
        }
        
        .stat-card .icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }
        
        .stat-card.primary .icon {
            background: rgba(102, 126, 234, 0.1);
            color: var(--primary);
        }
        
        .stat-card.success .icon {
            background: rgba(72, 187, 120, 0.1);
            color: var(--secondary);
        }
        
        .stat-card.warning .icon {
            background: rgba(237, 137, 54, 0.1);
            color: var(--warning);
        }
        
        .stat-card.danger .icon {
            background: rgba(245, 101, 101, 0.1);
            color: var(--danger);
        }
        
        .stat-card h3 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 5px;
        }
        
        .stat-card p {
            color: var(--gray-700);
            font-size: 0.95rem;
        }
        
        /* Responsive */
        .mobile-menu-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            background: white;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 10px;
            box-shadow: var(--shadow-lg);
            cursor: pointer;
            font-size: 1.5rem;
            color: var(--primary);
        }
        
        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .sidebar {
                position: fixed;
                left: -280px;
                top: 0;
                height: 100vh;
                z-index: 1000;
            }
            
            .sidebar.active {
                left: 0;
            }
            
            .content-area {
                margin: 10px;
            }
            
            .content-body {
                padding: 20px;
            }
        }
        
        /* Loading Animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(102, 126, 234, 0.3);
            border-radius: 50%;
            border-top-color: var(--primary);
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease forwards;
        }
    </style>
</head>
<body>
    <button class="mobile-menu-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <div class="main-container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h1>
                    <div class="logo">🚀</div>
                    xCrudRevolution
                </h1>
                <div class="version"><?php echo $version; ?> - Next Generation</div>
            </div>
            
            <!-- Getting Started -->
            <div class="menu-category">
                <i class="fas fa-rocket"></i> Getting Started
            </div>
            <div class="menu-items">
                <a href="?page=default" class="menu-item <?php echo $page == 'default' ? 'active' : ''; ?>">
                    <i class="fas fa-play-circle"></i> Simple Usage
                </a>
                <a href="?page=base_field_types" class="menu-item <?php echo $page == 'base_field_types' ? 'active' : ''; ?>">
                    <i class="fas fa-keyboard"></i> Field Types
                </a>
                <a href="?page=validation" class="menu-item <?php echo $page == 'validation' ? 'active' : ''; ?>">
                    <i class="fas fa-check-circle"></i> Validation
                </a>
            </div>
            
            <!-- Data Operations -->
            <div class="menu-category">
                <i class="fas fa-database"></i> Data Operations
            </div>
            <div class="menu-items">
                <a href="?page=relations" class="menu-item <?php echo $page == 'relations' ? 'active' : ''; ?>">
                    <i class="fas fa-link"></i> Relations
                </a>
                <a href="?page=fk_relation" class="menu-item <?php echo $page == 'fk_relation' ? 'active' : ''; ?>">
                    <i class="fas fa-project-diagram"></i> Many-to-Many
                </a>
                <a href="?page=nested" class="menu-item <?php echo $page == 'nested' ? 'active' : ''; ?>">
                    <i class="fas fa-layer-group"></i> Nested Tables
                </a>
                <a href="?page=nested_in_tabs" class="menu-item <?php echo $page == 'nested_in_tabs' ? 'active' : ''; ?>">
                    <i class="fas fa-folder-open"></i> Nested in Tabs
                </a>
                <a href="?page=join" class="menu-item <?php echo $page == 'join' ? 'active' : ''; ?>">
                    <i class="fas fa-code-branch"></i> Table Joins
                </a>
                <a href="?page=subselect" class="menu-item <?php echo $page == 'subselect' ? 'active' : ''; ?>">
                    <i class="fas fa-filter"></i> Subselect
                </a>
            </div>
            
            <!-- SQL Operators -->
            <div class="menu-category">
                <i class="fas fa-search"></i> SQL Operators
                <span class="badge">NEW</span>
            </div>
            <div class="menu-items">
                <a href="?page=operators_mysql" class="menu-item <?php echo $page == 'operators_mysql' ? 'active' : ''; ?>">
                    <i class="fas fa-dolphin"></i> MySQL Operators
                </a>
                <a href="?page=operators_postgresql" class="menu-item <?php echo $page == 'operators_postgresql' ? 'active' : ''; ?>">
                    <i class="fas fa-elephant"></i> PostgreSQL
                </a>
                <a href="?page=operators_sqlite" class="menu-item <?php echo $page == 'operators_sqlite' ? 'active' : ''; ?>">
                    <i class="fas fa-feather"></i> SQLite
                </a>
            </div>
            
            <!-- UI & UX -->
            <div class="menu-category">
                <i class="fas fa-paint-brush"></i> UI & UX
            </div>
            <div class="menu-items">
                <a href="?page=custom_buttons" class="menu-item <?php echo $page == 'custom_buttons' ? 'active' : ''; ?>">
                    <i class="fas fa-mouse-pointer"></i> Custom Buttons
                </a>
                <a href="?page=grid_customization" class="menu-item <?php echo $page == 'grid_customization' ? 'active' : ''; ?>">
                    <i class="fas fa-table"></i> Grid Customization
                </a>
                <a href="?page=highlights" class="menu-item <?php echo $page == 'highlights' ? 'active' : ''; ?>">
                    <i class="fas fa-highlighter"></i> Highlights
                </a>
                <a href="?page=modals" class="menu-item <?php echo $page == 'modals' ? 'active' : ''; ?>">
                    <i class="fas fa-window-restore"></i> Modals
                </a>
                <a href="?page=grid_tricks" class="menu-item <?php echo $page == 'grid_tricks' ? 'active' : ''; ?>">
                    <i class="fas fa-magic"></i> Grid Tricks
                </a>
            </div>
            
            <!-- Advanced Features -->
            <div class="menu-category">
                <i class="fas fa-cog"></i> Advanced Features
            </div>
            <div class="menu-items">
                <a href="?page=callbacks" class="menu-item <?php echo $page == 'callbacks' ? 'active' : ''; ?>">
                    <i class="fas fa-code"></i> Callbacks
                    <span class="badge">HOT</span>
                </a>
                <a href="?page=action" class="menu-item <?php echo $page == 'action' ? 'active' : ''; ?>">
                    <i class="fas fa-bolt"></i> Ajax Actions
                </a>
                <a href="?page=multi_instance" class="menu-item <?php echo $page == 'multi_instance' ? 'active' : ''; ?>">
                    <i class="fas fa-clone"></i> Multi Instance
                </a>
                <a href="?page=mass_operations" class="menu-item <?php echo $page == 'mass_operations' ? 'active' : ''; ?>">
                    <i class="fas fa-check-double"></i> Mass Operations
                </a>
                <a href="?page=ordering" class="menu-item <?php echo $page == 'ordering' ? 'active' : ''; ?>">
                    <i class="fas fa-sort"></i> Ordering
                </a>
            </div>
            
            <!-- File Handling -->
            <div class="menu-category">
                <i class="fas fa-file"></i> File Handling
            </div>
            <div class="menu-items">
                <a href="?page=uploads" class="menu-item <?php echo $page == 'uploads' ? 'active' : ''; ?>">
                    <i class="fas fa-upload"></i> File Uploads
                </a>
                <a href="?page=image_manipulation" class="menu-item <?php echo $page == 'image_manipulation' ? 'active' : ''; ?>">
                    <i class="fas fa-images"></i> Image Manipulation
                </a>
                <a href="?page=blob" class="menu-item <?php echo $page == 'blob' ? 'active' : ''; ?>">
                    <i class="fas fa-archive"></i> BLOB Storage
                </a>
            </div>
            
            <!-- Integration -->
            <div class="menu-category">
                <i class="fas fa-plug"></i> Integration
            </div>
            <div class="menu-items">
                <a href="?page=cke_and_custom" class="menu-item <?php echo $page == 'cke_and_custom' ? 'active' : ''; ?>">
                    <i class="fas fa-edit"></i> CKEditor
                </a>
                <a href="?page=select2" class="menu-item <?php echo $page == 'select2' ? 'active' : ''; ?>">
                    <i class="fas fa-list-ul"></i> Select2
                </a>
                <a href="?page=dependent_dropdowns" class="menu-item <?php echo $page == 'dependent_dropdowns' ? 'active' : ''; ?>">
                    <i class="fas fa-sitemap"></i> Dependent Dropdowns
                </a>
                <a href="?page=google_maps" class="menu-item <?php echo $page == 'google_maps' ? 'active' : ''; ?>">
                    <i class="fas fa-map-marked-alt"></i> Google Maps
                </a>
                <a href="?page=js_tricks" class="menu-item <?php echo $page == 'js_tricks' ? 'active' : ''; ?>">
                    <i class="fab fa-js"></i> JS Tricks
                </a>
            </div>
            
            <!-- Performance -->
            <div class="menu-category">
                <i class="fas fa-tachometer-alt"></i> Performance
            </div>
            <div class="menu-items">
                <a href="?page=benchmark" class="menu-item <?php echo $page == 'benchmark' ? 'active' : ''; ?>">
                    <i class="fas fa-stopwatch"></i> Benchmark
                </a>
                <a href="?page=million_records" class="menu-item <?php echo $page == 'million_records' ? 'active' : ''; ?>">
                    <i class="fas fa-database"></i> Million Records
                </a>
            </div>
            
            <!-- Templates -->
            <div class="menu-category">
                <i class="fas fa-palette"></i> Templates
            </div>
            <div class="menu-items">
                <a href="?page=comments_like" class="menu-item <?php echo $page == 'comments_like' ? 'active' : ''; ?>">
                    <i class="fas fa-comments"></i> Comments Template
                </a>
                <a href="?page=invoice" class="menu-item <?php echo $page == 'invoice' ? 'active' : ''; ?>">
                    <i class="fas fa-file-invoice"></i> Invoice Template
                </a>
            </div>
            
            <!-- Misc -->
            <div class="menu-category">
                <i class="fas fa-ellipsis-h"></i> Miscellaneous
            </div>
            <div class="menu-items">
                <a href="?page=all_disabled" class="menu-item <?php echo $page == 'all_disabled' ? 'active' : ''; ?>">
                    <i class="fas fa-ban"></i> All Disabled
                </a>
                <a href="?page=sum" class="menu-item <?php echo $page == 'sum' ? 'active' : ''; ?>">
                    <i class="fas fa-calculator"></i> Sum & Totals
                </a>
                <a href="?page=exact_search" class="menu-item <?php echo $page == 'exact_search' ? 'active' : ''; ?>">
                    <i class="fas fa-search-plus"></i> Exact Search
                </a>
            </div>
        </aside>
        
        <!-- Content Area -->
        <main class="content-area">
            <div class="content-header">
                <h2><?php echo $title_1 ?></h2>
                <div class="description"><?php echo $description ?></div>
                
                <div class="theme-selector">
                    <a href="?page=<?php echo $page ?>&theme=revolution" class="<?php echo $theme == 'revolution' ? 'active' : '' ?>">
                        <i class="fas fa-rocket"></i> Revolution
                    </a>
                    <a href="?page=<?php echo $page ?>&theme=default" class="<?php echo $theme == 'default' ? 'active' : '' ?>">
                        <i class="fas fa-th"></i> Default
                    </a>
                    <a href="?page=<?php echo $page ?>&theme=bootstrap" class="<?php echo $theme == 'bootstrap' ? 'active' : '' ?>">
                        <i class="fab fa-bootstrap"></i> Bootstrap
                    </a>
                    <a href="?page=<?php echo $page ?>&theme=minimal" class="<?php echo $theme == 'minimal' ? 'active' : '' ?>">
                        <i class="fas fa-minus"></i> Minimal
                    </a>
                </div>
            </div>
            
            <div class="content-body">
                <!-- Demo Container -->
                <div class="demo-container fade-in">
                    <?php
                    ob_start();
                    include ($file);
                    $out = ob_get_contents();
                    ob_end_clean();
                    echo $out;
                    ?>
                </div>
                
                <!-- Code Block -->
                <div class="code-block fade-in">
                    <div class="code-header">
                        <span><i class="fas fa-code"></i> PHP Source Code</span>
                        <div class="code-actions">
                            <button onclick="copyCode()" title="Copy to clipboard">
                                <i class="fas fa-copy"></i> Copy
                            </button>
                            <button onclick="toggleFullscreen()" title="Fullscreen">
                                <i class="fas fa-expand"></i> Expand
                            </button>
                        </div>
                    </div>
                    <div class="code-content">
                        <pre><code class="language-php"><?php echo htmlspecialchars($code); ?></code></pre>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <!-- Floating Action Button -->
    <div class="fab" onclick="scrollToTop()">
        <i class="fas fa-arrow-up"></i>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-php.min.js"></script>
    
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }
        
        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
        
        function copyCode() {
            const code = document.querySelector('.code-content pre code').textContent;
            navigator.clipboard.writeText(code).then(() => {
                showNotification('Code copied to clipboard!');
            });
        }
        
        function toggleFullscreen() {
            const codeBlock = document.querySelector('.code-block');
            if (!document.fullscreenElement) {
                codeBlock.requestFullscreen();
            } else {
                document.exitFullscreen();
            }
        }
        
        function showNotification(message) {
            const notification = document.createElement('div');
            notification.className = 'notification';
            notification.textContent = message;
            notification.style.cssText = `
                position: fixed;
                bottom: 100px;
                right: 30px;
                background: var(--gradient-primary);
                color: white;
                padding: 15px 25px;
                border-radius: 10px;
                box-shadow: var(--shadow-lg);
                z-index: 9999;
                animation: slideIn 0.3s ease;
            `;
            document.body.appendChild(notification);
            setTimeout(() => notification.remove(), 3000);
        }
        
        // Add smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
        
        // Add page transition effects
        window.addEventListener('load', () => {
            document.querySelectorAll('.fade-in').forEach((el, i) => {
                setTimeout(() => {
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, i * 100);
            });
        });
    </script>
    
    <?php echo Xcrud::load_css(); ?>
    <?php echo Xcrud::load_js(); ?>
</body>
</html>