<?php


use Core\Request;
use Core\Response;
use Core\Router;
use Controller\SalarieApiController;
use Helper\Debug;



return function (Router $router, SalarieApiController $salarieApiController): void {
    // Define your routes here in the future
    $router->get('/api/salaries', [$salarieApiController, 'salaries']);
    $router->getRegex('#^/api/salaries/(\d+)$#', function (Request $req, Response $res, array $m) use ($salarieApiController) {
        $salarieApiController->salarieById($req, $res, (int)$m[1]);
    });
    $router->post('/api/salaries', [$salarieApiController, 'createSalarie']);
};
 

