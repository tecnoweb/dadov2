<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Test di base per xCrud con PHP 8
include('xcrud.php');

?>
<!DOCTYPE html>
<html>
<head>
    <title>xCrudRevolution v2 - PHP 8 Test</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container">
        <h1 class="page-header">xCrudRevolution v2 - Test con PHP 8</h1>
        
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">Informazioni Sistema</h3>
            </div>
            <div class="panel-body">
                <p><strong>PHP Version:</strong> <?php echo PHP_VERSION; ?></p>
                <p><strong>Database:</strong> dadov2</p>
                <p><strong>User:</strong> dado</p>
                <p><strong>Status:</strong> 
                    <?php
                    try {
                        $db = Xcrud_db::get_instance();
                        echo '<span class="label label-success">Connesso</span>';
                    } catch (Exception $e) {
                        echo '<span class="label label-danger">Errore: ' . $e->getMessage() . '</span>';
                    }
                    ?>
                </p>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Test CRUD - Tabella Customers</h3>
            </div>
            <div class="panel-body">
                <?php
                try {
                    $xcrud = Xcrud::get_instance();
                    $xcrud->table('customers');
                    $xcrud->columns('customerNumber,customerName,contactLastName,contactFirstName,phone,city,country');
                    $xcrud->fields('customerName,contactLastName,contactFirstName,phone,addressLine1,city,state,postalCode,country');
                    
                    // Aggiungi alcune features
                    $xcrud->unset_csv(false);
                    $xcrud->unset_print(false);
                    $xcrud->limit(10);
                    
                    echo $xcrud->render();
                } catch (Exception $e) {
                    echo '<div class="alert alert-danger">Errore: ' . $e->getMessage() . '</div>';
                }
                ?>
            </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Test CRUD - Tabella Products</h3>
            </div>
            <div class="panel-body">
                <?php
                try {
                    $xcrud2 = Xcrud::get_instance();
                    $xcrud2->table('products');
                    $xcrud2->columns('productCode,productName,productLine,quantityInStock,buyPrice,MSRP');
                    $xcrud2->fields('productCode,productName,productLine,productScale,productVendor,productDescription,quantityInStock,buyPrice,MSRP');
                    
                    // Test relazioni
                    $xcrud2->relation('productLine','productlines','productLine','productLine');
                    
                    echo $xcrud2->render();
                } catch (Exception $e) {
                    echo '<div class="alert alert-danger">Errore: ' . $e->getMessage() . '</div>';
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>