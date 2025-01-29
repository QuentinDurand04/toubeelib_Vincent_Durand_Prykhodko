<?php

namespace gateway\application\actions;

use DI\Container;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;


class PraticienActions extends AbstractAction{

    private $guzzle;
    private $guzzleRdv;

    public function __construct(Container $container)
    {
        $this->guzzle = $container->get('guzzle.client');
        $this->guzzleRdv = $container->get('guzzle.client.rdv');
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try{
            if(!isset($args['id'])){
                $response = $this->guzzle->get("api.praticiens/praticiens");
                //sinon si la requete contient un id et /rdvs
            }elseif($rq->getUri()->getPath() == "/praticiens/".$args['id']."/rdvs"){
                $response = $this->guzzleRdv->get("/rdvs/praticien/".$args['id']);
            }else{
                $response = $this->guzzle->get("api.praticiens/praticiens/".$args['id']);
            }
            return $response;
        }catch(ClientException $e){
            throw ($e);
        }
    }
}
