<?php
declare(strict_types=1);

use Slim\Exception\HttpNotFoundException;
use gateway\application\actions\PraticienActions;
use gateway\application\actions\HomeAction;
use gateway\application\actions\RdvsActions;
use gateway\application\actions\PostSignIn;

return function (\Slim\App $app): \Slim\App {

    $app->get('/', HomeAction::class);

    
    $app->get("/praticiens[/]", PraticienActions::class )->setName('getAllPraticiens');

    $app->get("/praticiens/{id}[/]", PraticienActions::class)->setName('getPraticien');

    $app->get("/praticiens/{id}/rdvs[/]", PraticienActions::class)->setName('getRdvsPraticien');

    $app->get("/rdvs[/]", RdvsActions::class)->setName('getAllRdvs');
    
    $app->get("/rdvs/{id}[/]", RdvsActions::class)->setName('getRdvsId');

    $app->post('/rdvs[/]', RdvsActions::class)->setName('createRdv');

    $app->post('/signin[/]', PostSignIn::class)->setName('signIn');


    $app->options('/{routes:.+}', function ($request, $response, $args) {
        return $response;
    });

    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
        throw new HttpNotFoundException($request);
    });    

    return $app;
};
