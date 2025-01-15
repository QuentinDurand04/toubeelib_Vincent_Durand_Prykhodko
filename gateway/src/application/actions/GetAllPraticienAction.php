<?php

namespace gateway\application\actions;

use DI\Container;
use gateway\application\renderer\JsonRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


class GetAllPraticienAction extends AbstractAction{

    private \GuzzleHttp\Client $guzzle;

    public function __construct(Container $container)
    {
        parent::__construct($container);
        $this->guzzle = $container->get('guzzle.client');
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $response = $this->guzzle->get("api.praticiens/praticiens");
        return JsonRenderer::render($rs, $response->getStatusCode(), json_decode($response->getBody()->getContents()));
    }
}
