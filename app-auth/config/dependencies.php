<?php

use Psr\Container\ContainerInterface;
use auth\core\repositoryInterfaces\AuthRepositoryInterface;
use auth\core\repositoryInterfaces\PatientRepositoryInterface;
use auth\core\repositoryInterfaces\PraticienRepositoryInterface;
use auth\core\repositoryInterfaces\RdvRepositoryInterface;
use auth\core\services\AuthorizationPatientService;
use auth\core\services\AuthorizationPatientServiceInterface;
use auth\core\services\ServiceAuth;
use auth\core\services\ServiceAuthInterface;
use auth\core\services\patient\ServicePatient;
use auth\core\services\patient\ServicePatientInterface;
use auth\core\services\praticien\AuthorizationPraticienService;
use auth\core\services\praticien\AuthorizationPraticienServiceInterface;
use auth\core\services\praticien\ServicePraticien;
use auth\core\services\praticien\ServicePraticienInterface;
use auth\core\services\rdv\AuthorizationRendezVousService;
use auth\core\services\rdv\AuthorizationRendezVousServiceInterface;
use auth\core\services\rdv\ServiceRDV;
use auth\core\services\rdv\ServiceRDVInterface;
use auth\infrastructure\repositories\PgAuthRepository;
use auth\infrastructure\repositories\PgPatientRepository;
use auth\infrastructure\repositories\PgPraticienRepository;
use auth\infrastructure\repositories\PgRdvRepository;
use auth\middlewares\AuthnMiddleware;
use auth\middlewares\AuthzPatient;
use auth\middlewares\AuthzPraticiens;
use auth\middlewares\AuthzRDV;
use auth\middlewares\CorsMiddleware;
use auth\providers\auth\AuthnProviderInterface;
use auth\providers\auth\JWTAuthnProvider;
use auth\providers\auth\JWTManager;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;


return [

    //Repository interface
    PraticienRepositoryInterface::class => DI\autowire(PgPraticienRepository::class),
    RdvRepositoryInterface::class => DI\autowire(PgRdvRepository::class),
    AuthRepositoryInterface::class=> DI\autowire(PgAuthRepository::class),
    PatientRepositoryInterface::class => DI\autowire(PgPatientRepository::class),

    //Services
    ServicePraticienInterface::class => DI\autowire(ServicePraticien::class),
    ServiceRDVInterface::class => DI\autowire(ServiceRDV::class),
    ServiceAuthInterface::class => DI\autowire(ServiceAuth::class),
    ServicePatientInterface::class => Di\autowire(ServicePatient::class),
    AuthorizationRendezVousServiceInterface::class => DI\autowire(AuthorizationRendezVousService::class),
    AuthorizationPatientServiceInterface::class => DI\autowire(AuthorizationPatientService::class),
    AuthorizationPraticienServiceInterface::class => DI\autowire(AuthorizationPraticienService::class),


    AuthzRDV::class => DI\autowire(),
    AuthzPatient::class =>DI\autowire(),
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
    
    Logger::class => DI\create(Logger::class)->constructor('auth_logger', [DI\get(StreamHandler::class)]),


    //midleware 
    AuthnMiddleware::class => DI\autowire(AuthnMiddleware::class),
    CorsMiddleware::class => DI\autowire(CorsMiddleware::class),


];
// $co = new PDO('pgsql:host=auth.db;port=5432;dbname=auth;user=user;password=toto');
