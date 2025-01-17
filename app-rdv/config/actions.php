<?php

use rdv\application\actions\DeleteRdvId;
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
use rdv\core\services\rdv\ServiceRDVInterface;


return [

    GetDisposPraticien::class=>DI\autowire(),
    GetRdvId::class => DI\autowire(),
    PatchRdv::class => DI\autowire(),
    PostCreateRdv::class => DI\autowire(),
    DeleteRdvId::class => DI\autowire(),
    GetDisposPraticienDate::class => DI\autowire(),
    PostSignIn::class => DI\autowire(),
    SearchPraticien::class => DI\autowire(),
    GetPatient::class => DI\autowire(),
    GetPraticien::class => DI\autowire(),

    
    

];
