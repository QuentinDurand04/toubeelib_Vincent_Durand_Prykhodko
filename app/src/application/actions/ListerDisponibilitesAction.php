<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use DisponibiliteService; // Service métier à injecter

class ListerDisponibilitesAction extends AbstractAction
{
    private DisponibiliteService $disponibiliteService;

    public function __construct(DisponibiliteService $disponibiliteService)
    {
        $this->disponibiliteService = $disponibiliteService;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $idPraticien = $args['id'];

        try {
            $disponibilites = $this->disponibiliteService->getDisponibilitesByPraticienId($idPraticien);

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
            return $rs->withStatus(404)
                ->withHeader('Content-Type', 'application/json')
                ->getBody()->write(json_encode(['message' => $e->getMessage()]));
        } catch (\Exception $e) {
            return $rs->withStatus(500)
                ->withHeader('Content-Type', 'application/json')
                ->getBody()->write(json_encode(['message' => 'Erreur interne du serveur']));
        }
    }
}
