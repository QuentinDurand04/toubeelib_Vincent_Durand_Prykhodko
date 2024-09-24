<?php

use toubeelib\core\repositoryInterfaces\PraticienRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RendezvousRepositoryInterface;
use toubeelib\core\services\rdv\ServiceRendezvous;
use toubeelib\core\services\rdv\ServiceRendezvousInterface;
use toubeelib\infrastructure\repositories\ArrayPraticienRepository;
use toubeelib\infrastructure\repositories\ArrayRdvRepository;
use Psr\Container\ContainerInterface;
use toubeelib\application\actions\RendezVousAction;
use toubeelib\core\services\praticien\ServicePraticien;
use toubeelib\core\services\praticien\ServicePraticienInterface;

return  [

    'displayErrorDetails' => true,
    'logs.dir' => __DIR__ . '/../var/logs',
    'log.prog.name' => 'prog.log',
    'prog.logger' => function(ContainerInterface $c) {
    $logger = new \Monolog\Logger($c->get('log.prog.name'));
    $logger->pushHandler(
    new \Monolog\Handler\StreamHandler(
        $c->get('log.prog.file'),
        $c->get('log.prog.level')));
        return $logger;
    },

    RendezvousRepositoryInterface::class => new ArrayRdvRepository(),
    PraticienRepositoryInterface::class => new ArrayPraticienRepository(),
    ServicePraticienInterface::class => function(ContainerInterface $c){
        return new ServicePraticien($c->get(PraticienRepositoryInterface::class));
    },
    ServiceRendezvousInterface::class => function(ContainerInterface $c){
        return new ServiceRendezvous($c->get(RendezvousRepositoryInterface::class), $c->get(ServicePraticienInterface::class), $c->get('prog.logger'));
    },
    RendezVousAction::class => function(ContainerInterface $c){
        return new RendezVousAction($c->get(ServiceRendezvousInterface::class));
    }

    ] ;
