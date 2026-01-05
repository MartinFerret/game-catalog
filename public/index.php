<?php

use Controller\AppController;
use Core\Database;
use Core\Request;
use Core\Response;
use Core\Router;
use Core\Session;
use Repository\GamesRepository;

session_start();
require __DIR__ . '/../autoload.php';
require __DIR__ . '/../config/routes.php';

$config = require_once __DIR__ . '/../config/db.php';

$path = $_SERVER['REQUEST_URI'];

$response = new Response();
$session = new Session();
$request = new Request();
$router = new Router();
$repository = new GamesRepository(Database::makePdo($config['db']));

$appController = new AppController($response, $repository, $session, $request);

$registerRoutes = require __DIR__ . '/../config/routes.php';
$registerRoutes($router, $appController);
$router->dispatch($request, $response);

