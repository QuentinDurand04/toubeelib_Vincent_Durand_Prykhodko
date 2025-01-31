<?php

namespace gateway\application\actions;

use DI\Container;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;


class GetPraticienAction extends AbstractAction{

    private $guzzle;

    public function __construct(Container $container)
    {
        $this->guzzle = $container->get('guzzle.client');
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {

        try{
            $response = $this->guzzle->get("api.praticiens/praticiens/".$args['id']);
            return $response;
        }catch(ClientException $e){
            throw new HttpNotFoundException($rq, "Id du praticien invalide");
        }
    }
}
