<?php

use gateway\application\actions\GetAllPraticienAction;
use Psr\Container\ContainerInterface;
use gateway\application\actions\GetPraticienAction;
use gateway\application\actions\PraticienActions;

return [

    "guzzle.client" => function (ContainerInterface $c) {
        return new GuzzleHttp\Client([
            // Base URI pour des requêtes relatives
            'base_uri' => $c->get('toubeelib.praticien.api'),
        ]);
    },

    GetAllPraticienAction::class => function (ContainerInterface $c) {
        return new GetAllPraticienAction($c->get("guzzle.client"));
    },
    GetPraticienAction::class => function (ContainerInterface $c) {
        return new GetPraticienAction($c->get("guzzle.client"));
    },
    PraticienActions::class => function (ContainerInterface $c) {
        return new PraticienActions($c->get("guzzle.client"));
    },

];
