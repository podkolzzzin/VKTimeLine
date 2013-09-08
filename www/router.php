<?php
/**
 * Created by JetBrains PhpStorm.
 * User: podko_000
 * Date: 07.09.13
 * Time: 21:27
 * To change this template use File | Settings | File Templates.
 */

define('BASE_PATH', '/', true);

session_start();
header('Content-Type: text/html; charset=utf-8');

function __autoload($className)
{
    include BASE_PATH."Model/$className.php";
}

$route = $_GET['route'];
list($class, $action) = explode('/', $route);

include BASE_PATH.'Controller/Controller_base.php';
include BASE_PATH.'Controller/'.$class.'.php';
$class = 'Controller_'.$class;
$controller = new $class();
$controller->setDataBase(new DataBase());
$controller->$action();