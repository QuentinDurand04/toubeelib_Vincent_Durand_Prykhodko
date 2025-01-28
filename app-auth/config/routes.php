<?php
declare(strict_types=1);

use Slim\Exception\HttpNotFoundException;

use auth\application\actions\PostSignIn;

return function (\Slim\App $app): \Slim\App {

    $app->get('/', \auth\application\actions\HomeAction::class);

    $app->get('/tokens/validate[/]', \auth\application\actions\TokenValidation::class);

    $app->post('/signin[/]', PostSignIn::class)->setName('signIn');

    $app->options('/{routes:.+}', function ($request, $response, $args) {
        return $response;
    });
    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
        throw new HttpNotFoundException($request);
    });

    return $app;
};