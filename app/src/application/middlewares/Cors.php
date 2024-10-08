<?php

namespace toubeelib\application\middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Routing\RouteContext;

class Cors {

    public function __invoke(Request $rq, RequestHandlerInterface $next ): Response {
        $routeContext = RouteContext::fromRequest($rq);
        $routingResults = $routeContext->getRoutingResults();
        $methods = $routingResults->getAllowedMethods();
        $resquestHeaders = $rq->getHeaderLine('Access-Control-Request-Headers');

        $origin = $rq->hasHeader('Origin') ? $rq->getHeaderLine('Origin') : '*';

        $response = $next->handle($rq);

        return $response
            ->withHeader('Access-Control-Allow-Origin', $origin)
            ->withHeader('Access-Control-Allow-Headers', $resquestHeaders)
            ->withHeader('Access-Control-Allow-Methods', implode(', ', $methods))
            ->withHeader('Access-Control-Allow-Credentials', 'true');
    }
}