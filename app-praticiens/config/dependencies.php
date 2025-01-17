<?php

use Psr\Container\ContainerInterface;
use praticiens\core\repositoryInterfaces\AuthRepositoryInterface;
use praticiens\core\repositoryInterfaces\PatientRepositoryInterface;
use praticiens\core\repositoryInterfaces\PraticienRepositoryInterface;
use praticiens\core\repositoryInterfaces\RdvRepositoryInterface;
use praticiens\core\services\AuthorizationPatientService;
use praticiens\core\services\AuthorizationPatientServiceInterface;
use praticiens\core\services\ServiceAuth;
use praticiens\core\services\ServiceAuthInterface;
use praticiens\core\services\patient\ServicePatient;
use praticiens\core\services\patient\ServicePatientInterface;
use praticiens\core\services\praticien\AuthorizationPraticienService;
use praticiens\core\services\praticien\AuthorizationPraticienServiceInterface;
use praticiens\core\services\praticien\ServicePraticien;
use praticiens\core\services\praticien\ServicePraticienInterface;
use praticiens\core\services\rdv\AuthorizationRendezVousService;
use praticiens\core\services\rdv\AuthorizationRendezVousServiceInterface;
use praticiens\core\services\rdv\ServiceRDV;
use praticiens\core\services\rdv\ServiceRDVInterface;
use praticiens\infrastructure\repositories\PgAuthRepository;
use praticiens\infrastructure\repositories\PgPatientRepository;
use praticiens\infrastructure\repositories\PgPraticienRepository;
use praticiens\middlewares\AuthnMiddleware;
use praticiens\middlewares\AuthzPatient;
use praticiens\middlewares\AuthzPraticiens;
use praticiens\middlewares\AuthzRDV;
use praticiens\middlewares\CorsMiddleware;
use praticiens\providers\auth\AuthnProviderInterface;
use praticiens\providers\auth\JWTAuthnProvider;
use praticiens\providers\auth\JWTManager;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;


return [

    //Repository interface
    PraticienRepositoryInterface::class => DI\autowire(PgPraticienRepository::class),
    //RdvRepositoryInterface::class => DI\autowire(PgRdvRepository::class),
    AuthRepositoryInterface::class=> DI\autowire(PgAuthRepository::class),

    //Services
    ServicePraticienInterface::class => DI\autowire(ServicePraticien::class),
    //ServiceRDVInterface::class => DI\autowire(ServiceRDV::class),
    ServiceAuthInterface::class => DI\autowire(ServiceAuth::class),


    AuthzPraticiens::class => DI\autowire(),

    //PDO
    'pdo.commun' => function(ContainerInterface $c){
        $config= parse_ini_file($c->get('db.config'));
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
    
    Logger::class => DI\create(Logger::class)->constructor('praticiens_logger', [DI\get(StreamHandler::class)]),


    //midleware 
    AuthnMiddleware::class => DI\autowire(AuthnMiddleware::class),
    CorsMiddleware::class => DI\autowire(CorsMiddleware::class),


];
// $co = new PDO('pgsql:host=praticiens.db;port=5432;dbname=praticiens;user=user;password=toto');
