<?php

use toubeelib\core\repositoryInterfaces\PraticienRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RendezvousRepositoryInterface;
use toubeelib\core\services\rdv\ServiceRendezvous;
use toubeelib\core\services\rdv\ServiceRendezvousInterface;
use toubeelib\infrastructure\repositories\ArrayPraticienRepository;
use toubeelib\infrastructure\repositories\ArrayRdvRepository;
use Psr\Container\ContainerInterface;
use toubeelib\application\actions\AnnulerRendezVousAction;
use toubeelib\application\actions\ListerDisponibilitesAction;
use toubeelib\application\actions\RendezVousAction;
use toubeelib\application\actions\SigninAction;
use toubeelib\core\services\auth\AuthService;
use toubeelib\core\services\praticien\ServicePraticien;
use toubeelib\core\services\praticien\ServicePraticienInterface;
use toubeelib\core\repositoryInterfaces\UserRepositoryInterface;
use toubeelib\core\services\disponibilite\ServiceDisponibiliteInterface;
use toubeelib\core\services\disponibilite\ServiceDisponibilite;
use toubeelib\providers\auth\AuthProvider;
use toubeelib\infrastructure\repositories\BddUserRepository;

return  [

    'displayErrorDetails' => true,
    'logs.dir' => __DIR__ . '/../var/logs',
    'JWT_SECRET' => 'secret',

    'log.rdv.name' => 'rdv.log',
    'logger.rdv.file' => function(ContainerInterface $c) {
        return $c->get('logs.dir') . '/rdv.log';
    },

    'logger.rdv.level' => \Monolog\Level::Info,

    RendezvousRepositoryInterface::class => new ArrayRdvRepository(),
    PraticienRepositoryInterface::class => new ArrayPraticienRepository(),
    UserRepositoryInterface::class => new BddUserRepository(),
    ServiceDisponibiliteInterface::class => new ServiceDisponibilite($c->get(RendezvousRepositoryInterface::class)),
    AuthService::class => function(ContainerInterface $c){
        return new AuthService($c->get(UserRepositoryInterface::class), $c->get('JWT_SECRET'));
    },
    AuthProvider::class => function(ContainerInterface $c){
        return new AuthProvider($c->get(AuthService::class));
    },
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
    },
    SigninAction::class => function(ContainerInterface $c){
        return new SigninAction($c->get(AuthProvider::class));
    },
    ListerDisponibilitesAction::class => function(ContainerInterface $c){
        return new ListerDisponibilitesAction($c->get(ServiceDisponibiliteInterface::class));
    }

    ] ;
