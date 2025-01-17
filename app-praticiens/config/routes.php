<?php
declare(strict_types=1);

use Slim\Exception\HttpNotFoundException;



use praticiens\application\actions\GetPatient;
use praticiens\application\actions\GetPraticien;
use praticiens\application\actions\GetRdvByPatient;

use praticiens\application\actions\PostSignIn;
use praticiens\middlewares\AuthnMiddleware;
use praticiens\middlewares\AuthzPatient;
use praticiens\middlewares\AuthzPraticiens;
use praticiens\middlewares\AuthzRDV;

return function (\Slim\App $app): \Slim\App {

    $app->get('/', \praticiens\application\actions\HomeAction::class);

    //PRATICIENS
    $app->get('/praticiens[/]', \praticiens\application\actions\GetAllPraticien::class)
        ->setName('getAllPraticien');

    $app->get('/praticiens/{id}/rdvs[/]', \praticiens\application\actions\GetPraticienPlanning::class)
        ->setName('planningPraticien');
//        ->add(AuthzPraticiens::class)
//        ->add(AuthnMiddleware::class);

    $app->get("/praticiens/{id}[/]", GetPraticien::class )->setName('getPraticien');
//        ->add(AuthnMiddleware::class);

    $app->post('/signin[/]', PostSignIn::class)->setName('signIn');

    $app->options('/{routes:.+}', function ($request, $response, $args) {
        return $response;
    });
    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
        throw new HttpNotFoundException($request);
    });    

    return $app;
};
