<?php

use toubeelib\application\actions\DeleteRdvId;
use toubeelib\application\actions\GetAllPraticienAction;
use toubeelib\application\actions\GetPatient;
use toubeelib\application\actions\GetPraticien;
use toubeelib\application\actions\GetRdvId;
use toubeelib\application\actions\HomeAction;

return [
    GetAllPraticienAction::class => DI\autowire(),
    HomeAction::class => DI\autowire(),

];
