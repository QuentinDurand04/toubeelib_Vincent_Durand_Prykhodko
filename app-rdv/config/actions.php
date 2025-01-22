<?php

use gateway\application\actions\HomeAction;
use rdv\application\actions\DeleteRdvId;
use rdv\application\actions\GetAllRdvs;
use rdv\application\actions\GetPatient;
use rdv\application\actions\GetPraticien;
use rdv\application\actions\GetRdvId;
use rdv\application\actions\PatchRdv;
use rdv\application\actions\PostCreateRdv;
use rdv\application\actions\PostSignIn;
use rdv\application\actions\SearchPraticien;
use rdv\core\services\praticien\ServicePraticienInterface;
use rdv\application\actions\GetDisposPraticien;
use rdv\application\actions\GetDisposPraticienDate;
use rdv\application\actions\GetRdvByPatient;
use rdv\core\services\rdv\ServiceRDVInterface;


return [

    GetAllRdvs::class => DI\autowire(),
    GetRdvId::class => DI\autowire(),
    PostCreateRdv::class => DI\autowire(),
    PostSignIn::class => DI\autowire(),
    GetRdvByPatient::class => DI\autowire(),
    HomeAction::class => DI\autowire(),
    
    

];
