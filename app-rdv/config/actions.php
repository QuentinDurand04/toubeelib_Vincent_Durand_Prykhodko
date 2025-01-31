<?php

use rdv\application\actions\HomeAction;
use rdv\application\actions\DeleteRdvId;
use rdv\application\actions\GetAllRdvs;
use rdv\application\actions\GetRdvId;
use rdv\application\actions\PostCreateRdv;
use rdv\application\actions\PostSignIn;
use rdv\application\actions\GetRdvByPatient;
use rdv\application\actions\GetRdvsByPraticien;

return [

    GetAllRdvs::class => DI\autowire(),
    GetRdvId::class => DI\autowire(),
    PostCreateRdv::class => DI\autowire(),
    //PostSignIn::class => DI\autowire(),
    //GetRdvByPatient::class => DI\autowire(),
    HomeAction::class => DI\autowire(),
    GetRdvsByPraticien::class => DI\autowire(),
    
    

];
