<?php

use toubeelib\core\services\rdv\ServiceRendezvous;
use toubeelib\core\repositoryInterfaces\RendezvousRepositoryInterface;
use toubeelib\core\services\praticien\ServicePraticienInterface;
use Monolog\Logger;
use toubeelib\core\services\rdv\ServiceRendezvousInterface;

// Définir les dépendances
return [
    // Autres définitions...

    // Définition du service Rendezvous
    ServiceRendezvousInterface::class => function ($c) {
        return new ServiceRendezvous(
            $c->get(RendezvousRepositoryInterface::class),
            $c->get(ServicePraticienInterface::class),
            $c->get('logger')
        );
    },
];
