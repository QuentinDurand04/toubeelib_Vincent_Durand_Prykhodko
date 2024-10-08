<?php

use Psr\Container\ContainerInterface;

return  [

    'displayErrorDetails' => true,
    'logs.dir' => __DIR__ . '/../var/logs',

    'log.rdv.name' => 'rdv.log',
    'logger.rdv.file' => function(ContainerInterface $c) {
        return $c->get('logs.dir') . '/rdv.log';
    },

    'logger.rdv.level' => \Monolog\Level::Info,

    'praticien.db.config' => 'praticien.db.ini'

    ] ;
