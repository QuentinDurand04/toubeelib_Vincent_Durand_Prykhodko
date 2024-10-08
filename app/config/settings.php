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


] ;
