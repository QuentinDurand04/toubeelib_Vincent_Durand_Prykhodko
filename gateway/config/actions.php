<?php


use gateway\application\actions\GetAllPraticienAction;
use gateway\application\actions\HomeAction;


return [
    GetAllPraticienAction::class => DI\autowire(),
    HomeAction::class => DI\autowire(),

];
