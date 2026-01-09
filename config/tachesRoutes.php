<?php

use Controller\PingApiController;
use Controller\TacheApiController;
use Core\Router;
use Core\Request;
use Core\Response;

return function (
    Router $router,
    PingApiController $pingApiController,
    TacheApiController $tacheApiController
) {
    // Page d'accueil
//    $router->get('/', [$controller, 'home']);

    // Test API
    $router->get('/ping', [$pingApiController, 'ping']);

    // -------------------------
    // 🚀 ROUTES API TÂCHES
    // -------------------------

    // LISTE
    $router->get('/api/taches', [$tacheApiController, 'index']);

    // CREATE
    $router->post('/api/taches', [$tacheApiController, 'store']);

    // SHOW (regex)
    $router->getRegex('#^/api/taches/(\d+)$#', function (Request $req, Response $res, array $m) use ($tacheApiController) {
        $tacheApiController->show($req, $res, (int)$m[1]);
    });

    // UPDATE (POST)
    $router->post('/api/taches/update', [$tacheApiController, 'update']);

    // DELETE (POST)
    $router->post('/api/taches/delete', [$tacheApiController, 'delete']);


};
