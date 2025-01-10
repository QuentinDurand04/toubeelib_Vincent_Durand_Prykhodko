<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Exceptions\NestedValidationException;
use Slim\Exception\HttpBadRequestException;
use toubeelib\application\renderer\JsonRenderer;

class GetAllRdvs extends AbstractAction
{

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try{

            $rdvs = $this->serviceRdv->getAllRdvs();
            return JsonRenderer::render($rs, 200, $rdvs);

        }catch(NestedValidationException $e){
            throw new HttpBadRequestException($rq, "Id du praticien invalide");
        }
    }

}