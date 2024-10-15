<?php
declare(strict_types=1);

namespace toubeelib\application\config;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

return function( \Slim\App $app):\Slim\App {

    $app->get('/', \toubeelib\application\actions\HomeAction::class);
    $app->get('/rdvs/{id}', \toubeelib\application\actions\RendezVousAction::class);
    $app->patch('/rdvs/{id}', \toubeelib\application\actions\ModifierRendezVousAction::class);
    $app->post('/rdvs', \toubeelib\application\actions\CreerRendezVousAction::class);
    $app->delete('/rdvs/{id}', \toubeelib\application\actions\AnnulerRendezVousAction::class);
    $app->get('/praticiens/{id}/disponibilites', \toubeelib\application\actions\ListerDisponibilitesAction::class);
    $app->post('/signin', \toubeelib\application\actions\SigninAction::class);



    $app->options('/{routes:.+}', function( Request $rq, Response $rs, array $args) : Response {
        
        return $rs;
    });


    return $app;
};