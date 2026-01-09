<?php

use Controller\PingApiController;
use Controller\ProjectController;
use Controller\ClientController;
use Controller\AbsenceController;
use Controller\TacheApiController;
use Core\Cors;
use Core\Database;
use Core\Response;
use Core\Session;
use Core\Request;
use Core\Router;

use Repository\ProjectsRepository;
use Repository\ClientsRepository;
use Repository\TacheRepository;

session_start();
require __DIR__ . '/../autoload.php';
$registerProjectRoutes = require __DIR__ . '/../config/projectRoutes.php';
$registerClientsRoutes = require __DIR__ . '/../config/clientRoutes.php';
$registerAbsenceRoutes = require __DIR__ . '/../config/absenceRoutes.php';
$registerTachesRoutes = require __DIR__ . '/../config/tachesRoutes.php';
$config = require_once __DIR__ . '/../config/db.php';

Cors::Handle();

$response = new Response();
$session = new Session();
$request = new Request();
$router = new Router();

$projectRepository = new ProjectsRepository(Database::makePdo($config['db']));
$clientRepository = new ClientsRepository(Database::makePdo($config['db']));
$tacheRepository = new TacheRepository(Database::makePdo($config['db']));

$projectController = new ProjectController($response, $projectRepository, $session, $request);
$clientController = new ClientController($response, $clientRepository, $session, $request);
$absenceController = new AbsenceController(Database::makePdo($config['db']));
$tacheApiController = new TacheApiController($response, $tacheRepository, $request);
$pingApiController = new PingApiController();

$registerProjectRoutes($router, $projectController, $pingApiController, $projectRepository);
$registerClientsRoutes($router, $clientController, $pingApiController, $clientRepository);
$registerAbsenceRoutes($router, $pingApiController, $absenceController);
$registerTachesRoutes($router, $pingApiController, $tacheApiController, $tacheRepository);
$router->dispatch($request, $response);