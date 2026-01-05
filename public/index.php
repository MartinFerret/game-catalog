<?php

use Controller\AppController;

session_start();
require __DIR__ . '/../autoload.php';

require_once __DIR__ . '/../src/helpers/debug.php';

$path = $_SERVER['REQUEST_URI'];

$appController = new AppController();
$appController->handleRequest($path);

