<?php
declare(strict_types=1);

use Slim\Exception\HttpNotFoundException;

use gateway\application\actions\GetAllPraticienAction;
use gateway\application\actions\HomeAction;

return function (\Slim\App $app): \Slim\App {

    $app->get('/', HomeAction::class);

    
    $app->get("/praticiens", GetAllPraticienAction::class )->setName('getAllPraticiens');


    $app->options('/{routes:.+}', function ($request, $response, $args) {
        return $response;
    });

    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
        throw new HttpNotFoundException($request);
    });    

    return $app;
};
