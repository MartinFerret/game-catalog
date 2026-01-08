<?php

use Controller\PingApiController;
use Controller\AbsenceController;
use Core\Cors;
use Core\Database;
use Core\Request;
use Core\Response;
use Core\Router;

session_start();
require __DIR__ . '/../autoload.php';

$config = require_once __DIR__ . '/../config/db.php';

Cors::handle();

$response = new Response();
$request = new Request();
$router = new Router();
$pdo = Database::makePdo($config['db']);

$pingApiController = new PingApiController();
$absenceController = new AbsenceController($pdo);

$registerRoutes = require __DIR__ . '/../config/routes.php';
$registerRoutes($router, $pingApiController, $absenceController);
$router->dispatch($request, $response);