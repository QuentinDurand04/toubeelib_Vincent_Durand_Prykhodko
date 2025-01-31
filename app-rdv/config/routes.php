<?php
declare(strict_types=1);

use Slim\Exception\HttpNotFoundException;



use rdv\application\actions\GetPatient;
use rdv\application\actions\GetPraticien;
use rdv\application\actions\GetRdvByPatient;

use rdv\application\actions\PostSignIn;
use rdv\middlewares\AuthnMiddleware;
use rdv\middlewares\AuthzPatient;
use rdv\middlewares\AuthzPraticiens;
use rdv\middlewares\AuthzRDV;

return function (\Slim\App $app): \Slim\App {

    $app->get('/', \rdv\application\actions\HomeAction::class);

    //RENDEZVOUS
    $app->get('/rdvs[/]', \rdv\application\actions\GetAllRdvs::class)
        ->setName('getAllRdvs');

    $app->post('/rdvs[/]', \rdv\application\actions\PostCreateRdv::class)
        ->setName('createRdv');
//        ->add(AuthnMiddleware::class);
    ;

    $app->get('/rdvs/{id}[/]', \rdv\application\actions\GetRdvId::class)
        ->setName('getRdv');
//        ->add(AuthzRDV::class)
//        ->add(AuthnMiddleware::class);

    $app->get('/rdvs/praticien/{id}[/]', \rdv\application\actions\GetRdvsByPraticien::class)
        ->setName('getRdvsByPraticien');

    $app->post('/signin[/]', PostSignIn::class)->setName('signIn');

    $app->options('/{routes:.+}', function ($request, $response, $args) {
        return $response;
    });
    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
        throw new HttpNotFoundException($request);
    });    

    return $app;
};
