<?php

use Controller\AppController;
use Core\Database;
use Core\Response;
use Repository\GamesRepository;

session_start();
require __DIR__ . '/../autoload.php';

$config = require_once __DIR__ . '/../config/db.php';

$path = $_SERVER['REQUEST_URI'];

$response = new Response();
$repository = new GamesRepository(Database::makePdo($config['db']));

$appController = new AppController($response, $repository);
$appController->handleRequest($path);

