<?php

use gateway\application\actions\GetPraticienAction;
use gateway\application\actions\GetAllPraticienAction;
use gateway\application\actions\HomeAction;

return [
    GetAllPraticienAction::class => DI\autowire(),
    GetPraticienAction::class => DI\autowire(),
    HomeAction::class => DI\autowire(),

];
