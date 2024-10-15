<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use toubeelib\core\services\disponibilite\ServiceDisponibilite;

// Service métier à injecter

class ListerDisponibilitesAction extends AbstractAction
{
    private ServiceDisponibilite $disponibiliteService;

    public function __construct(ServiceDisponibilite $disponibiliteService)
    {
        $this->disponibiliteService = $disponibiliteService;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $idPraticien = $args['id'];

        try {
            $disponibilites = $this->disponibiliteService->getDisponibiliteByPraticienId($idPraticien);

            if (!$disponibilites) {
                throw new HttpNotFoundException($rq, "Aucune disponibilité trouvée pour le praticien $idPraticien");
            }

            $data = [
                'disponibilites' => $disponibilites,
                'links' => [
                    'self' => ['href' => "/praticiens/$idPraticien/disponibilites"],
                    'rdvs' => ['href' => "/rdvs"]
                ]
            ];

            $rs->getBody()->write(json_encode($data));
            return $rs->withHeader('Content-Type', 'application/json')
                ->withStatus(200);

        } catch (HttpNotFoundException $e) {
            $responseBody = $rs->getBody();
            $responseBody->write(json_encode(['message' => $e->getMessage()]));
            return $rs->withStatus(404)
                ->withHeader('Content-Type', 'application/json')
                ->withBody($responseBody);
        } catch (\Exception $e) {
            $responseBody = $rs->getBody();
            $responseBody->write(json_encode(['message' => 'Erreur interne du serveur']));
            return $rs->withStatus(500)
                ->withHeader('Content-Type', 'application/json')
                ->withBody($responseBody);
        }
    }
}
