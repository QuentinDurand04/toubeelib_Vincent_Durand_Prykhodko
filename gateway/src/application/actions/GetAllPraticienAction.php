<?php

namespace gateway\application\actions;

use DI\Container;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


class GetAllPraticienAction extends AbstractAction{

    public function __construct(Container $cont)
    {
        parent::__construct($cont);
        $this->guzzle = $cont->get('guzzle');
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $response = $this->guzzle->get("api.toubeelib/praticiens");
        return $response;
    }
}
