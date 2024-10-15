<?php

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// Créez le container DI
$builder = new ContainerBuilder();

// Ajoutez les définitions
$builder->addDefinitions(__DIR__ . '/settings.php');
$builder->addDefinitions(__DIR__ . '/dependencies.php');

// Configurez Monolog
$logger = new Logger('rendezvous');
$logger->pushHandler(new StreamHandler(__DIR__.'/logs/rendezvous.log', Logger::INFO));

// Ajoutez le logger au conteneur DI
$builder->addDefinitions([
    'logger' => $logger,
]);

$c = $builder->build();

// Créez l'application Slim
$app = AppFactory::createFromContainer($c);

$app->add(new \toubeelib\application\middlewares\Cors);
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware($c->get('displayErrorDetails'), false, false);

// Chargez les routes
$app = (require_once __DIR__ . '/routes.php')($app);
$routeParser = $app->getRouteCollector()->getRouteParser();

// Retournez l'application
return $app;
