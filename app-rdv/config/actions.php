<?php

use gateway\application\actions\HomeAction;
use rdv\application\actions\GetAllRdvs;
use rdv\application\actions\GetRdvId;
use rdv\application\actions\PostCreateRdv;
use rdv\application\actions\PostSignIn;
use rdv\application\actions\GetRdvByPatient;


return [

    GetAllRdvs::class => DI\autowire(),
    GetRdvId::class => DI\autowire(),
    PostCreateRdv::class => DI\autowire(),
    PostSignIn::class => DI\autowire(),
    GetRdvByPatient::class => DI\autowire(),
    HomeAction::class => DI\autowire(),
    
    

];
