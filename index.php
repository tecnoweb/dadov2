<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>xCrudRevolution - Demo Hub</title>
    
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    
    <style>
        body { 
            padding-top: 50px; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .main-container {
            margin-top: 50px;
        }
        .demo-card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            transition: transform 0.3s;
        }
        .demo-card:hover {
            transform: translateY(-5px);
        }
        .demo-card h3 {
            color: #333;
            margin-bottom: 20px;
        }
        .demo-card .btn {
            margin: 5px;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin-left: 10px;
        }
        .status-ok { background: #5cb85c; color: white; }
        .status-new { background: #f0ad4e; color: white; }
        .hero-section {
            text-align: center;
            color: white;
            margin-bottom: 50px;
        }
        .hero-section h1 {
            font-size: 48px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .stats-box {
            background: rgba(255,255,255,0.2);
            padding: 20px;
            border-radius: 10px;
            color: white;
            text-align: center;
            margin: 10px;
        }
        .stats-box h4 {
            margin: 0;
            font-size: 24px;
        }
        .stats-box p {
            margin: 5px 0 0 0;
            opacity: 0.9;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">🚀 xCrudRevolution</a>
        </div>
        <div class="navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#"><i class="fa fa-check-circle"></i> PHP <?php echo PHP_VERSION; ?></a></li>
                <li><a href="https://github.com/tecnoweb/dadov2" target="_blank"><i class="fa fa-github"></i> GitHub</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container main-container">
    
    <!-- Hero Section -->
    <div class="hero-section">
        <h1>🚀 xCrudRevolution v2</h1>
        <p class="lead">The Evolution of xCrud - Now with PHP 8+ Support</p>
        
        <div class="row">
            <div class="col-md-3">
                <div class="stats-box">
                    <h4>✅</h4>
                    <p>PHP 8 Ready</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-box">
                    <h4>15+</h4>
                    <p>Demo Pages</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-box">
                    <h4>50+</h4>
                    <p>Hook Points</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-box">
                    <h4>100%</h4>
                    <p>Backward Compatible</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Demo Cards -->
    <div class="row">
        
        <!-- V2 Demo Card -->
        <div class="col-md-6">
            <div class="demo-card">
                <h3>
                    <i class="fa fa-rocket text-primary"></i> xCrudRevolution v2
                    <span class="status-badge status-new">NEW</span>
                </h3>
                <p>La nuova versione con PHP 8+ support, hooks ovunque, e sistema condizionale.</p>
                <ul>
                    <li>✅ PHP 8.4 Compatible</li>
                    <li>✅ OpenSSL Encryption</li>
                    <li>✅ No Deprecated Functions</li>
                    <li>✅ Hook System Ready</li>
                </ul>
                <hr>
                <a href="v2/demo.php" class="btn btn-success btn-lg">
                    <i class="fa fa-play"></i> Demo Completa v2
                </a>
                <a href="v2/test_xcrud.php" class="btn btn-info">
                    <i class="fa fa-flask"></i> Test Basic
                </a>
                <hr>
                <p><small><strong>Database:</strong> dadov2 | <strong>User:</strong> dado</small></p>
            </div>
        </div>

        <!-- Original Demo Card -->
        <div class="col-md-6">
            <div class="demo-card">
                <h3>
                    <i class="fa fa-code text-warning"></i> Demo Originale
                    <span class="status-badge status-ok">CLASSIC</span>
                </h3>
                <p>Tutti gli esempi originali di xCrud con le varie funzionalità.</p>
                <ul>
                    <li>📁 Simple CRUD</li>
                    <li>📁 Relations & Joins</li>
                    <li>📁 File Uploads</li>
                    <li>📁 Validations</li>
                </ul>
                <hr>
                <a href="demo_old_xcrud/index.php" class="btn btn-primary btn-lg">
                    <i class="fa fa-folder-open"></i> Demo Originale
                </a>
                <a href="demo_v2.php" class="btn btn-warning">
                    <i class="fa fa-sync"></i> Demo con v2
                </a>
                <hr>
                <p><small><strong>Path:</strong> demo_old_xcrud/</small></p>
            </div>
        </div>

    </div>

    <!-- Quick Links -->
    <div class="row">
        <div class="col-md-12">
            <div class="demo-card">
                <h3><i class="fa fa-link"></i> Quick Links</h3>
                <div class="row">
                    <div class="col-md-3">
                        <h4>Demo Pages v2</h4>
                        <ul class="list-unstyled">
                            <li><a href="v2/demo.php#customers">Customers</a></li>
                            <li><a href="v2/demo.php#products">Products</a></li>
                            <li><a href="v2/demo.php#orders">Orders</a></li>
                            <li><a href="v2/demo.php#gallery">Gallery</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h4>Original Demos</h4>
                        <ul class="list-unstyled">
                            <li><a href="demo_old_xcrud/index.php?page=simple">Simple</a></li>
                            <li><a href="demo_old_xcrud/index.php?page=validation">Validation</a></li>
                            <li><a href="demo_old_xcrud/index.php?page=join">Joins</a></li>
                            <li><a href="demo_old_xcrud/index.php?page=uploads">Uploads</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h4>Documentation</h4>
                        <ul class="list-unstyled">
                            <li><a href="v2/README.md">README</a></li>
                            <li><a href="v2/TASK.md">Task List</a></li>
                            <li><a href="v2/SISTEMA_HOOKS_COMPLETO.md">Hook System</a></li>
                            <li><a href="documentation/">Docs</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h4>Development</h4>
                        <ul class="list-unstyled">
                            <li><a href="https://github.com/tecnoweb/dadov2" target="_blank">GitHub</a></li>
                            <li><a href="v2/XCRUD_REVOLUTION_MASTER_PLAN.md">Master Plan</a></li>
                            <li><a href="v2/CHAT.md">Dev Chat</a></li>
                            <li><span class="text-muted">Port: 8799</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status -->
    <div class="row">
        <div class="col-md-12">
            <div class="demo-card text-center">
                <h4>Development Status</h4>
                <div class="progress" style="height: 30px;">
                    <div class="progress-bar progress-bar-success" style="width: 10%">
                        <span>FASE 1: PHP 8 ✅</span>
                    </div>
                    <div class="progress-bar progress-bar-info" style="width: 5%">
                        <span>FASE 2: Multi-DB</span>
                    </div>
                    <div class="progress-bar progress-bar-striped" style="width: 85%">
                        <span>In Development...</span>
                    </div>
                </div>
                <br>
                <p>
                    <strong>Current:</strong> FASE 2 - Multi-Database Support | 
                    <strong>PHP:</strong> <?php echo PHP_VERSION; ?> | 
                    <strong>Server:</strong> localhost:8799
                </p>
            </div>
        </div>
    </div>

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>