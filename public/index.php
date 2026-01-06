<?php

use Controller\AppController;
use Controller\PingApiController;
use Core\Database;
use Core\Request;
use Core\Response;
use Core\Router;
use Core\Session;
use Repository\GamesRepository;

session_start();
require __DIR__ . '/../autoload.php';

$config = require_once __DIR__ . '/../config/db.php';

$response = new Response();
$session = new Session();
$request = new Request();
$router = new Router();
$repository = new GamesRepository(Database::makePdo($config['db']));

$appController = new AppController($response, $repository, $session, $request);
$pingApiController = new PingApiController();

$registerRoutes = require __DIR__ . '/../config/routes.php';
$registerRoutes($router, $appController, $pingApiController);
$router->dispatch($request, $response);

