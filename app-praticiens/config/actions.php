<?php

use gateway\application\actions\HomeAction;
use praticiens\application\actions\DeleteRdvId;
use praticiens\application\actions\GetAllPraticien;
use praticiens\application\actions\GetPatient;
use praticiens\application\actions\GetPraticien;
use praticiens\application\actions\GetRdvId;
use praticiens\application\actions\PatchRdv;
use praticiens\application\actions\PostCreateRdv;
use praticiens\application\actions\PostSignIn;
use praticiens\application\actions\SearchPraticien;
use praticiens\core\services\praticien\ServicePraticienInterface;
use praticiens\application\actions\GetDisposPraticien;
use praticiens\application\actions\GetDisposPraticienDate;
use praticiens\application\actions\GetPraticienPlanning;
use praticiens\core\services\rdv\ServiceRDVInterface;


return [

    GetPraticien::class => DI\autowire(),
    GetAllPraticien::class => DI\autowire(),
    GetPraticienPlanning::class => DI\autowire(),
    PostSignIn::class => DI\autowire(),
    HomeAction::class => DI\autowire(),

    
    

];
