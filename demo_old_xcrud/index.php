<?php
require ('../v2/xcrud.php');
require ('html/pagedata.php');

// Force xcrud to use v2 directory for all resources
Xcrud_config::$scripts_url = '../v2';

session_start();
$theme = isset($_GET['theme']) ? $_GET['theme'] : 'revolution';
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
    case 'default':
        Xcrud_config::$theme = 'default';
        $title_2 = 'Default theme';
        break;
    case 'revolution':
        Xcrud_config::$theme = 'revolution';
        $title_2 = 'Revolution theme - Modern & Glassmorphism';
        break;
    default:
        Xcrud_config::$theme = 'revolution';
        $title_2 = 'Revolution theme - Modern & Glassmorphism';
        break;
}

$page = (isset($_GET['page']) && isset($pagedata[$_GET['page']])) ? $_GET['page'] : 'default';
extract($pagedata[$page]);

$file = dirname(__file__) . '/pages/' . $filename;
$code = file_get_contents($file);

// Use the new revolutionary template
include ('html/template_revolution.php');
