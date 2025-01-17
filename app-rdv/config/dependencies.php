<?php

use Psr\Container\ContainerInterface;
use rdv\core\repositoryInterfaces\AuthRepositoryInterface;
use rdv\core\repositoryInterfaces\PatientRepositoryInterface;
use rdv\core\repositoryInterfaces\PraticienRepositoryInterface;
use rdv\core\repositoryInterfaces\RdvRepositoryInterface;
use rdv\core\services\AuthorizationPatientService;
use rdv\core\services\AuthorizationPatientServiceInterface;
use rdv\core\services\ServiceAuth;
use rdv\core\services\ServiceAuthInterface;
use rdv\core\services\patient\ServicePatient;
use rdv\core\services\patient\ServicePatientInterface;
use rdv\core\services\praticien\AuthorizationPraticienService;
use rdv\core\services\praticien\AuthorizationPraticienServiceInterface;
use rdv\core\services\praticien\ServicePraticien;
use rdv\core\services\praticien\ServicePraticienInterface;
use rdv\core\services\rdv\AuthorizationRendezVousService;
use rdv\core\services\rdv\AuthorizationRendezVousServiceInterface;
use rdv\core\services\rdv\ServiceRDV;
use rdv\core\services\rdv\ServiceRDVInterface;
use rdv\infrastructure\repositories\PgAuthRepository;
use rdv\infrastructure\repositories\PgPatientRepository;
use rdv\infrastructure\repositories\PgPraticienRepository;
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
    
    Logger::class => DI\create(Logger::class)->constructor('rdv_logger', [DI\get(StreamHandler::class)]),


    //midleware 
    AuthnMiddleware::class => DI\autowire(AuthnMiddleware::class),
    CorsMiddleware::class => DI\autowire(CorsMiddleware::class),


];
// $co = new PDO('pgsql:host=rdv.db;port=5432;dbname=rdv;user=user;password=toto');
