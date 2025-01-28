<?php
declare(strict_types=1);

use Slim\Exception\HttpNotFoundException;



use auth\application\actions\GetPatient;
use auth\application\actions\GetPraticien;
use auth\application\actions\GetRdvByPatient;

use auth\application\actions\PostSignIn;
use auth\middlewares\AuthnMiddleware;
use auth\middlewares\AuthzPatient;
use auth\middlewares\AuthzPraticiens;
use auth\middlewares\AuthzRDV;

return function (\Slim\App $app): \Slim\App {

    $app->get('/', \auth\application\actions\HomeAction::class);

    //RENDEZVOUS
    $app->get('/rdvs[/]', \auth\application\actions\GetAllRdvs::class)
        ->setName('getAllRdvs');

    $app->get('/praticiens/{id}/rdvs[/]', \auth\application\actions\GetPraticienRdvs::class)
        ->setName('rdvPraticien');

    $app->post('/rdvs[/]', \auth\application\actions\PostCreateRdv::class)
        ->setName('createRdv');
//        ->add(AuthnMiddleware::class);
    ;

    $app->get('/rdvs/{id}[/]', \auth\application\actions\GetRdvId::class)
        ->setName('getRdv');
//        ->add(AuthzRDV::class)
//        ->add(AuthnMiddleware::class);

    //PATIENTS
    $app->get('/patients/{id}/rdvs[/]', GetRdvByPatient::class)
        ->setName('rdvPatient');
//        ->add(AuthzPatient::class)
//        ->add(AuthnMiddleware::class);

    $app->get("/patients/{id}[/]", GetPatient::class)
        ->setName('getPatient');
//        ->add(AuthzPatient::class)
//        ->add(AuthnMiddleware::class);

    //PRATICIENS
    $app->get('/praticiens[/]', \auth\application\actions\GetAllPraticien::class)
        ->setName('getAllPraticien');

    /*$app->get('/praticiens/{id}/rdvs[/]', \auth\application\actions\GetPraticienPlanning::class)
        ->setName('planningPraticien');*/
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
