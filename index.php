<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use ctrl\UserController;


require 'models/UserModel.php';

$requestUri = $_SERVER['REQUEST_URI'];

$cleanUri = strtok($requestUri, '?');

$uriSegments = explode('/', trim($cleanUri, '/'));

$controllerName = isset($uriSegments[0]) ? ucfirst($uriSegments[0]) . 'Controller' : 'AccueilController';
$action = isset($uriSegments[1]) ? $uriSegments[1] : 'accueil';

$controllerFile = "ctrl/{$controllerName}.php";

if (file_exists($controllerFile)) {
    require $controllerFile;

    $controller = new $controllerName();

    if (method_exists($controller, $action)) {
        call_user_func(array($controller, $action));
    } else {
        include('views/404.php');
    }
} else {
    include('views/accueil.php');
}