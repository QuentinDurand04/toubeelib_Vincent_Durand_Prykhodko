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

class RdvsActions extends AbstractAction
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
            //if no id in the request and request is get
            if(!isset($args['id']) && $rq->getMethod() == 'GET'){
                $response = $this->guzzle->get("/rdvs");
                //sinon si la requete contient un id et /rdvs
            }elseif(isset($args['id']) && $rq->getMethod() == 'GET'){
                $response = $this->guzzle->get("/rdvs/".$args['id']);
            }elseif($rq->getMethod() == 'POST'){
                $response = $this->guzzle->post("/rdvs", ['json' => $rq->getParsedBody()]);
            }
            return $response;
        }catch(ClientException $e){
            throw ($e);
        }
    }


}