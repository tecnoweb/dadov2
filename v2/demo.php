<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('xcrud.php');
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>🚀 xCrudRevolution v2 - Demo PHP 8</title>
    
    <!-- Bootstrap 3 -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    
    <style>
        body { padding-top: 20px; background: #f5f5f5; }
        .navbar-brand { font-weight: bold; }
        .panel { box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .stats-card { 
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .stats-number { font-size: 32px; font-weight: bold; color: #337ab7; }
        .stats-label { color: #999; font-size: 14px; }
        .success-badge { background: #5cb85c; color: white; padding: 5px 10px; border-radius: 4px; }
        h1 { margin-bottom: 30px; }
        .tab-content { background: white; padding: 20px; border: 1px solid #ddd; border-top: none; }
    </style>
</head>
<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">🚀 xCrudRevolution v2</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#"><i class="fa fa-check-circle"></i> PHP <?php echo PHP_VERSION; ?></a></li>
                <li><a href="#"><i class="fa fa-database"></i> Database: dadov2</a></li>
                <li><a href="https://github.com/tecnoweb/dadov2" target="_blank"><i class="fa fa-github"></i> GitHub</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container" style="margin-top: 70px;">
    
    <div class="row">
        <div class="col-md-12">
            <h1><i class="fa fa-rocket"></i> xCrudRevolution v2 - Demo Completa</h1>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="stats-card text-center">
                <div class="stats-number">✅</div>
                <div class="stats-label">PHP 8 Compatible</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card text-center">
                <div class="stats-number">
                <?php
                    $db = Xcrud_db::get_instance();
                    $db->query("SELECT COUNT(*) as cnt FROM customers");
                    $res = $db->row();
                    echo $res['cnt'];
                ?>
                </div>
                <div class="stats-label">Customers</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card text-center">
                <div class="stats-number">
                <?php
                    $db->query("SELECT COUNT(*) as cnt FROM products");
                    $res = $db->row();
                    echo $res['cnt'];
                ?>
                </div>
                <div class="stats-label">Products</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card text-center">
                <div class="stats-number">
                <?php
                    $db->query("SELECT COUNT(*) as cnt FROM orders");
                    $res = $db->row();
                    echo $res['cnt'];
                ?>
                </div>
                <div class="stats-label">Orders</div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#customers" aria-controls="customers" role="tab" data-toggle="tab">
                        <i class="fa fa-users"></i> Customers
                    </a>
                </li>
                <li role="presentation">
                    <a href="#products" aria-controls="products" role="tab" data-toggle="tab">
                        <i class="fa fa-cube"></i> Products
                    </a>
                </li>
                <li role="presentation">
                    <a href="#orders" aria-controls="orders" role="tab" data-toggle="tab">
                        <i class="fa fa-shopping-cart"></i> Orders
                    </a>
                </li>
                <li role="presentation">
                    <a href="#employees" aria-controls="employees" role="tab" data-toggle="tab">
                        <i class="fa fa-user-tie"></i> Employees
                    </a>
                </li>
                <li role="presentation">
                    <a href="#gallery" aria-controls="gallery" role="tab" data-toggle="tab">
                        <i class="fa fa-image"></i> Gallery
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <!-- Customers Tab -->
                <div role="tabpanel" class="tab-pane active" id="customers">
                    <?php
                    $xcrud = Xcrud::get_instance();
                    $xcrud->table('customers');
                    $xcrud->table_name('Customer Management');
                    
                    // Colonne da mostrare nella lista
                    $xcrud->columns('customerNumber,customerName,contactLastName,contactFirstName,phone,city,country,creditLimit');
                    
                    // Campi nel form
                    $xcrud->fields('customerName,contactLastName,contactFirstName,phone,addressLine1,addressLine2,city,state,postalCode,country,creditLimit');
                    
                    // Validazioni
                    $xcrud->validation_required('customerName,contactLastName,contactFirstName');
                    $xcrud->validation_pattern('phone', '^[0-9\-\.\s\(\)]+$');
                    
                    // Features
                    $xcrud->highlight('creditLimit', '>', 100000, '#5cb85c');
                    $xcrud->highlight('creditLimit', '<', 50000, '#d9534f');
                    $xcrud->column_cut(30, 'addressLine1');
                    $xcrud->limit(15);
                    
                    echo $xcrud->render();
                    ?>
                </div>

                <!-- Products Tab -->
                <div role="tabpanel" class="tab-pane" id="products">
                    <?php
                    $xcrud2 = Xcrud::get_instance();
                    $xcrud2->table('products');
                    $xcrud2->table_name('Product Catalog');
                    
                    // Colonne
                    $xcrud2->columns('productCode,productName,productLine,productScale,quantityInStock,buyPrice,MSRP');
                    
                    // Relazione con productlines
                    $xcrud2->relation('productLine','productlines','productLine','productLine');
                    
                    // Validazioni
                    $xcrud2->validation_required('productCode,productName,productLine');
                    $xcrud2->change_type('buyPrice', 'price', '0', array('prefix'=>'$'));
                    $xcrud2->change_type('MSRP', 'price', '0', array('prefix'=>'$'));
                    
                    // Highlight stock basso
                    $xcrud2->highlight('quantityInStock', '<', 100, '#f0ad4e');
                    $xcrud2->highlight('quantityInStock', '<', 10, '#d9534f');
                    
                    $xcrud2->limit(15);
                    echo $xcrud2->render();
                    ?>
                </div>

                <!-- Orders Tab -->
                <div role="tabpanel" class="tab-pane" id="orders">
                    <?php
                    $xcrud3 = Xcrud::get_instance();
                    $xcrud3->table('orders');
                    $xcrud3->table_name('Order Management');
                    
                    $xcrud3->columns('orderNumber,orderDate,requiredDate,shippedDate,status,customerNumber');
                    
                    // Join con customers per mostrare il nome
                    $xcrud3->relation('customerNumber','customers','customerNumber','customerName');
                    
                    // Formattazione date
                    $xcrud3->change_type('orderDate', 'date');
                    $xcrud3->change_type('requiredDate', 'date');
                    $xcrud3->change_type('shippedDate', 'date');
                    
                    // Highlight status
                    $xcrud3->highlight('status', '=', 'Shipped', '#5cb85c');
                    $xcrud3->highlight('status', '=', 'In Process', '#f0ad4e');
                    $xcrud3->highlight('status', '=', 'Cancelled', '#d9534f');
                    
                    // Subselect per totale ordine
                    $xcrud3->subselect('Order Total','SELECT SUM(quantityOrdered * priceEach) FROM orderdetails WHERE orderNumber = {orderNumber}');
                    
                    $xcrud3->limit(15);
                    echo $xcrud3->render();
                    ?>
                </div>

                <!-- Employees Tab -->
                <div role="tabpanel" class="tab-pane" id="employees">
                    <?php
                    $xcrud4 = Xcrud::get_instance();
                    $xcrud4->table('employees');
                    $xcrud4->table_name('Employee Directory');
                    
                    $xcrud4->columns('employeeNumber,lastName,firstName,extension,email,jobTitle,reportsTo');
                    
                    // Self-relation per manager
                    $xcrud4->relation('reportsTo','employees','employeeNumber','lastName');
                    $xcrud4->relation('officeCode','offices','officeCode','city');
                    
                    // Validazioni
                    $xcrud4->validation_required('firstName,lastName,email');
                    $xcrud4->validation_pattern('email', '^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$');
                    
                    $xcrud4->limit(15);
                    echo $xcrud4->render();
                    ?>
                </div>

                <!-- Gallery Tab -->
                <div role="tabpanel" class="tab-pane" id="gallery">
                    <?php
                    $xcrud5 = Xcrud::get_instance();
                    $xcrud5->table('gallery');
                    $xcrud5->table_name('Image Gallery');
                    
                    // Configurazione per immagini
                    $xcrud5->change_type('image', 'image', false, array(
                        'width' => 300,
                        'path' => '../uploads/gallery',
                        'thumbs' => array(
                            array('width'=> 100, 'marker'=>'_th')
                        )
                    ));
                    
                    $xcrud5->fields('title,description,image');
                    $xcrud5->validation_required('title');
                    
                    echo $xcrud5->render();
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="row" style="margin-top: 40px;">
        <div class="col-md-12">
            <div class="well text-center">
                <p>
                    <strong>xCrudRevolution v2.0</strong> | 
                    PHP <?php echo PHP_VERSION; ?> | 
                    <span class="success-badge">✅ Fase 1 Completata</span> |
                    <a href="https://github.com/tecnoweb/dadov2" target="_blank">GitHub Repository</a>
                </p>
            </div>
        </div>
    </div>

</div>

</body>
</html>