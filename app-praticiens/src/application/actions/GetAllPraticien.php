<?php

namespace praticiens\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Exceptions\NestedValidationException;
use Slim\Exception\HttpBadRequestException;
use praticiens\application\renderer\JsonRenderer;

class GetAllPraticien extends AbstractAction
{

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try{

            $praticiens = $this->servicePraticien->getAllPraticiens();
            return JsonRenderer::render($rs, 200, $praticiens);

        }catch(NestedValidationException $e){
            throw new HttpBadRequestException($rq, "Id du praticien invalide");
        }
    }
}