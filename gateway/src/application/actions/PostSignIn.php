<?php

namespace gateway\application\actions;

use DI\Container;
use gateway\application\renderer\JsonRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


class PostSignIn extends AbstractAction{

    private $guzzle;

    public function __construct(Container $container)
    {
        $this->guzzle = $container->get('guzzle.client');
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $response = $this->guzzle->post("api.auth/signin");
        return $response;
    }
}
