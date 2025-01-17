<?php

namespace gateway\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteContext;
use gateway\application\actions\AbstractAction;
use gateway\application\renderer\JsonRenderer;
use gateway\core\dto\RdvDTO;
use gateway\core\services\rdv\ServiceRDVInvalidDataException;

class GetRdvId extends AbstractAction
{
    public function __construct(Container $container)
    {
        parent::__construct($container);
        $this->guzzle = $container->get('guzzle.client');
    }

    
    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface {
        
        $response = $this->guzzle->get("/praticiens");
        return $response;

    }
}
