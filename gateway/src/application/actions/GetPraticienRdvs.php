<?php

namespace gateway\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;
use Slim\Exception\HttpBadRequestException;
use gateway\application\renderer\JsonRenderer;

class GetPraticienRdvs extends AbstractAction
{

    //returns list of rdvs for a praticien
    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $idval = Validator::key('id', Validator::Uuid());

        try{
            $idval->assert($args);
            $rdvs = $this->serviceRdv->getRdvsByPraticien($args['id']);
            return JsonRenderer::render($rs, 200, $rdvs);

        }catch(NestedValidationException $e){
            throw new HttpBadRequestException($rq, "Id du praticien invalide");
            }
    }

}