<?php
// Punta a v2/xcrud.php invece di xcrud/xcrud.php
require ('v2/xcrud.php');
require ('demo_old_xcrud/html/pagedata.php');
session_start();

$theme = isset($_GET['theme']) ? $_GET['theme'] : 'default';
switch ($theme)
{
    case 'bootstrap':
        Xcrud_config::$theme = 'bootstrap';
        $title_2 = 'Bootstrap theme';
        break;
    case 'minimal':
        Xcrud_config::$theme = 'minimal';
        $title_2 = 'Minimal theme';
        break;
    default:
        Xcrud_config::$theme = 'default';
        $title_2 = 'Default theme';
        break;
}

$page = (isset($_GET['page']) && isset($pagedata[$_GET['page']])) ? $_GET['page'] : 'simple';
extract($pagedata[$page]);

// Carica il file della pagina demo
$file = dirname(__file__) . '/demo_old_xcrud/pages/' . $filename;
if (file_exists($file)) {
    $code = file_get_contents($file);
    include ('demo_old_xcrud/html/template.php');
} else {
    // Fallback a una demo semplice
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>xCrudRevolution v2 - Demo</title>
        <meta charset="utf-8">
        <link href="v2/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <script src="v2/plugins/jquery.min.js"></script>
        <script src="v2/plugins/bootstrap/js/bootstrap.min.js"></script>
    </head>
    <body>
        <div class="container">
            <h1>xCrudRevolution v2 - Demo con PHP 8</h1>
            <div class="alert alert-success">
                <strong>✅ PHP 8 Compatible!</strong> Running on PHP <?php echo PHP_VERSION; ?>
            </div>
            
            <?php
            $xcrud = Xcrud::get_instance();
            $xcrud->table('customers');
            echo $xcrud->render();
            ?>
        </div>
    </body>
    </html>
    <?php
}
?>