<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Exceptions\NestedValidationException;
use Slim\Exception\HttpBadRequestException;
use toubeelib\application\renderer\JsonRenderer;
use toubeelib\application\actions\AbstractAction;

class GetAllRdvs extends AbstractAction
{
    private \GuzzleHttp\Client $guzzle;

    public function __construct(Container $container)
    {
        parent::__construct($container);
        $this->guzzle = $container->get('guzzle.client');
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
            $response = $this->guzzle->get("/rdvs");
            return $response;
    }


}