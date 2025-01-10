<?php
declare(strict_types=1);

use Slim\Exception\HttpNotFoundException;


use gateway\application\actions\GetAllPraticienAction;

return function (\Slim\App $app): \Slim\App {

    $app->get('/', \toubeelib\application\actions\HomeAction::class);

    
    $app->get("/praticiens", GetAllPraticienAction::class )->setName('getPraticien');


    $app->options('/{routes:.+}', function ($request, $response, $args) {
        return $response;
    });

    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
        throw new HttpNotFoundException($request);
    });    

    return $app;
};
