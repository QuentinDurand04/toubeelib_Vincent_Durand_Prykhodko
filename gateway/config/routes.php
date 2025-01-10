<?php
declare(strict_types=1);

use Slim\Exception\HttpNotFoundException;



use toubeelib\application\actions\GetPatient;
use toubeelib\application\actions\GetPraticien;
use toubeelib\application\actions\GetRdvByPatient;

use toubeelib\application\actions\PostSignIn;
use toubeelib\middlewares\AuthnMiddleware;
use toubeelib\middlewares\AuthzPatient;
use toubeelib\middlewares\AuthzPraticiens;
use toubeelib\middlewares\AuthzRDV;

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