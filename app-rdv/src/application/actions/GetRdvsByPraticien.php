<?php

namespace rdv\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteContext;
use rdv\application\actions\AbstractAction;
use rdv\application\renderer\JsonRenderer;
use rdv\core\dto\RdvDTO;
use rdv\core\services\ServiceRessourceNotFoundException;
use rdv\core\services\rdv\ServiceRDVInvalidDataException;

class GetRdvsByPraticien extends AbstractAction
{
    public static function ajouterLiensRdv(array $rdvs, ServerRequestInterface $rq):array{
        $routeParser = RouteContext::fromRequest($rq)->getRouteParser();
        $praticienId = $rdvs[0]->getPraticienDTO()->getId();
        return ["rendezVous" => $rdvs,
            "links" => [
                "praticien" => '/praticiens/'.$praticienId,
            ]
        ];
    }
    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface {

        $status = 200;
        try {
            $rdvs = $this->serviceRdv->getRdvsByPraticien($args['id']);
            $data = GetRdvsByPraticien::ajouterLiensRdv($rdvs,$rq);
            $rs = JsonRenderer::render($rs, 200, $data);
            $this->loger->info('GetRdvPraticien du praticien: '.$args['id']);

        } catch (ServiceRessourceNotFoundException $e) {
            $this->loger->error('GetRdvPraticien : '.$args['id'].' : '.$e->getMessage());
            throw new HttpNotFoundException($rq, $e->getMessage());
        }catch (\Exception $e){
            $this->loger->error('GetRdvPraticien : '.$args['id'].' : '.$e->getMessage());
            throw new HttpInternalServerErrorException($rq,$e->getMessage());
        }


        return $rs;
    }
}
