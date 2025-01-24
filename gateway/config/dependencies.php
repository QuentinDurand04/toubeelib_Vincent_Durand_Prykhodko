<?php

use Psr\Container\ContainerInterface;
use gateway\application\actions\PraticienActions;
use gateway\application\actions\PostSignIn;

return [

    "guzzle.client" => function (ContainerInterface $c) {
        return new GuzzleHttp\Client([
            // Base URI pour des requêtes relatives
            //'base_uri' => $c->get(''),
        ]);
    },
    "guzzle.client.rdv" => function (ContainerInterface $c) {
        return new GuzzleHttp\Client([
            // Base URI pour des requêtes relatives
            'base_uri' => $c->get('rdvs.api'),
        ]);
    },

    PraticienActions::class => function (ContainerInterface $c) {
        return new PraticienActions($c->get("guzzle.client"));
    },

    PraticienActions::class => function (ContainerInterface $c) {
        return new PostSignIn($c->get("guzzle.client"));
    },

];
