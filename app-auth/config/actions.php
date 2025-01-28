<?php

use auth\application\actions\PostSignIn;


return [
    PostSignIn::class => DI\autowire(),
    \auth\application\actions\TokenValidation::class => DI\autowire(),
];
