<?php

namespace gateway\application\actions;

use DI\Container;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Exceptions\NestedValidationException;
use Slim\Exception\HttpBadRequestException;
use gateway\application\renderer\JsonRenderer;
use gateway\application\actions\AbstractAction;
use GuzzleHttp\Exception\ClientException;

class GetAllRdvs extends AbstractAction
{
    private \GuzzleHttp\Client $guzzle;

    public function __construct(Container $container)
    {
        parent::__construct($container);
        $this->guzzle = $container->get('guzzle.client.rdv');
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try{
            if(!isset($args['id'])){
                $response = $this->guzzle->get("/rdvs");
                //sinon si la requete contient un id et /rdvs
            }elseif($rq->getUri()->getPath() == "/rdvs/".$args['id']){
                $response = $this->guzzle->get("/rdvs/".$args['id']);
            }
            return $response;
        }catch(ClientException $e){
            throw ($e);
        }
    }


}