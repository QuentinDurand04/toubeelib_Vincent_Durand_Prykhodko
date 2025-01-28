<?php

use Psr\Container\ContainerInterface;
use rdv\core\repositoryInterfaces\AuthRepositoryInterface;
use rdv\core\repositoryInterfaces\RdvRepositoryInterface;
use rdv\core\services\rdv\AuthorizationRendezVousService;
use rdv\core\services\rdv\AuthorizationRendezVousServiceInterface;
use rdv\core\services\rdv\ServiceRDV;
use rdv\core\services\rdv\ServiceRDVInterface;
use rdv\infrastructure\repositories\PgAuthRepository;
use rdv\infrastructure\repositories\PgRdvRepository;
use rdv\middlewares\AuthnMiddleware;
use rdv\middlewares\AuthzPatient;
use rdv\middlewares\AuthzPraticiens;
use rdv\middlewares\AuthzRDV;
use rdv\middlewares\CorsMiddleware;
use rdv\providers\auth\AuthnProviderInterface;
use rdv\providers\auth\JWTAuthnProvider;
use rdv\providers\auth\JWTManager;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
use rdv\core\repositoryInterfaces\PraticienRepositoryInterface;
use rdv\core\services\praticien\ServicePraticien;
use rdv\infrastructure\repositories\PgPraticienRepository;
use rdv\core\services\patient\ServicePatient;
use rdv\core\services\patient\ServicePatientInterface;
use rdv\core\services\praticien\ServicePraticienInterface;
use rdv\core\repositoryInterfaces\PatientRepositoryInterface;
use rdv\infrastructure\repositories\PgPatientRepository;

return [

    //Repository interface
    RdvRepositoryInterface::class => DI\autowire(PgRdvRepository::class),
    AuthRepositoryInterface::class=> DI\autowire(PgAuthRepository::class),
    PraticienRepositoryInterface::class => DI\autowire(PgPraticienRepository::class),
    PatientRepositoryInterface::class => DI\autowire(PgPatientRepository::class),

    //Services
    ServiceRDVInterface::class => DI\autowire(ServiceRDV::class),
    AuthorizationRendezVousServiceInterface::class => DI\autowire(AuthorizationRendezVousService::class),
    ServicePraticienInterface::class => DI\autowire(ServicePraticien::class),
    ServicePatientInterface::class => DI\autowire(ServicePatient::class),


    AuthzRDV::class => DI\autowire(),

    "guzzle.client" => function (ContainerInterface $c) {
        return new GuzzleHttp\Client([
            // Base URI pour des requêtes relatives
            'base_uri' => $c->get('praticiens.api'),
        ]);
    },

    //PDO
    'pdo.commun' => function(ContainerInterface $c){
        $config= parse_ini_file($c->get('db.config'));
        return new PDO($config['driver'].':host='.$config['host'].';port='.$config['port'].';dbname='.$config['dbname'].';user='.$config['user'].';password='.$config['password']);
    },
    'pdo.specialite' => function(ContainerInterface $c){
        $config= parse_ini_file($c->get('db.config.spe'));
        return new PDO($config['driver'].':host='.$config['host'].';port='.$config['port'].';dbname='.$config['dbname'].';user='.$config['user'].';password='.$config['password']);
    },
    'pdo.auth' => function(ContainerInterface $c){
        $config = parse_ini_file($c->get('auth.db.config'));
        return new PDO($config['driver'].':host='.$config['host'].';port='.$config['port'].';dbname='.$config['dbname'].';user='.$config['user'].';password='.$config['password']);
    },

    //auth
    JWTManager::class=> DI\autowire(JWTManager::class),
    AuthnProviderInterface::class => DI\autowire(JWTAuthnProvider::class),
    
    StreamHandler::class => DI\create(StreamHandler::class)
        ->constructor(DI\get('logs.dir'), Logger::DEBUG)
        ->method('setFormatter', DI\get(LineFormatter::class)),

    
    LineFormatter::class => function() {
        $dateFormat = "Y-m-d H:i"; // Format de la date que tu veux
        $output = "[%datetime%] %channel%.%level_name%: %message% %context%\n"; // Format des logs
        return new LineFormatter($output, $dateFormat);
    },
    
    Logger::class => DI\create(Logger::class)->constructor('rdv_logger', [DI\get(StreamHandler::class)]),


    //midleware 
    //AuthnMiddleware::class => DI\autowire(AuthnMiddleware::class),
    CorsMiddleware::class => DI\autowire(CorsMiddleware::class),


];
// $co = new PDO('pgsql:host=rdv.db;port=5432;dbname=rdv;user=user;password=toto');
