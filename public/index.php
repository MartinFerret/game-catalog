<?php

use Controller\SalarieApiController;
use Core\Cors;
use Core\Database;
use Core\Request;
use Core\Response;
use Core\Router;
use Core\Session;
use Repository\SalarieRepository;

session_start();
require __DIR__ . '/../autoload.php';

$config = require_once __DIR__ . '/../config/db.php';

Cors::handle();

$response = new Response();
$session = new Session();
$request = new Request();
$router = new Router();
$repository = new SalarieRepository(Database::makePdo($config['db']));



$salarieApiController = new SalarieApiController($response, $repository, $session, $request);

$registerRoutes = require __DIR__ . '/../config/routes.php';
$registerRoutes($router, $salarieApiController);
$router->dispatch($request, $response);