<?php

use gateway\application\actions\GetPraticienAction;
use gateway\application\actions\GetAllPraticienAction;
use gateway\application\actions\HomeAction;
use gateway\application\actions\PraticienActions;
use gateway\application\actions\PostSignIn;

return [
    GetAllPraticienAction::class => DI\autowire(),
    GetPraticienAction::class => DI\autowire(),
    PraticienActions::class => DI\autowire(),
    HomeAction::class => DI\autowire(),
    PostSignIn::class => DI\autowire(),

];
