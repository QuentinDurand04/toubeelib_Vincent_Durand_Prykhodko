<?php

use toubeelib\core\repositoryInterfaces\PraticienRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RendezvousRepositoryInterface;
use toubeelib\core\services\rdv\ServiceRendezvous;
use toubeelib\core\services\rdv\ServiceRendezvousInterface;
use toubeelib\infrastructure\repositories\ArrayPraticienRepository;
use toubeelib\infrastructure\repositories\ArrayRdvRepository;
use Psr\Container\ContainerInterface;
use toubeelib\application\actions\AnnulerRendezVousAction;
use toubeelib\application\actions\RendezVousAction;
use toubeelib\core\services\praticien\ServicePraticien;
use toubeelib\core\services\praticien\ServicePraticienInterface;


return  [

    'displayErrorDetails' => true,
    'logs.dir' => __DIR__ . '/../var/logs',

    'log.rdv.name' => 'rdv.log',
    'logger.rdv.file' => function(ContainerInterface $c) {
        return $c->get('logs.dir') . '/rdv.log';
    },

    'logger.rdv.level' => \Monolog\Level::Info,

    'praticien.db.config' => 'praticien.db.ini',

    RendezvousRepositoryInterface::class => new ArrayRdvRepository(),
    PraticienRepositoryInterface::class => new ArrayPraticienRepository(),
    ServicePraticienInterface::class => function(ContainerInterface $c){
        return new ServicePraticien($c->get(PraticienRepositoryInterface::class));
    },
    ServiceRendezvousInterface::class => function(ContainerInterface $c){
        return new ServiceRendezvous($c->get(RendezvousRepositoryInterface::class), $c->get(ServicePraticienInterface::class));
    },
    RendezVousAction::class => function(ContainerInterface $c){
        return new RendezVousAction($c->get(ServiceRendezvousInterface::class));
    },
    AnnulerRendezVousAction::class => function(ContainerInterface $c){
        return new AnnulerRendezVousAction($c->get(ServiceRendezvousInterface::class));
    }

    ] ;
