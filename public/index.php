<?php

use Controller\AppController;
use Core\Response;

session_start();
require __DIR__ . '/../autoload.php';

require_once __DIR__ . '/../src/helpers/debug.php';

$path = $_SERVER['REQUEST_URI'];

$response = new Response();

$appController = new AppController($response);
$appController->handleRequest($path);

