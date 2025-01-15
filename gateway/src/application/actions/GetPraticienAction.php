<?php

namespace gateway\application\actions;

use DI\Container;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


class GetPraticienAction extends AbstractAction{

    private $guzzle;

    public function __construct(Container $container)
    {
        $this->guzzle = $container->get('guzzle.client');
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $response = $this->guzzle->get("api.praticiens/praticiens/".$args['id']);
        return $response;
    }
}
