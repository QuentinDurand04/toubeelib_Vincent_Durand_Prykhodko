<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use \toubeelib\core\services\rdv\ServiceRendezvous;
use toubeelib\core\dto\RendezVousDTO;

class CreerRendezVousAction extends AbstractAction
{
    private ServiceRendezvous $rendezVousService;

    public function __construct(ServiceRendezvous $rendezVousService)
    {
        $this->rendezVousService = $rendezVousService;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $parsedBody = $rq->getParsedBody();

        if (!isset($parsedBody['id_patient'], $parsedBody['id_praticien'], $parsedBody['specialite_praticien'], $parsedBody['lieu'], $parsedBody['horaire'], $parsedBody['type'])) {
            throw new HttpBadRequestException($rq, "Données manquantes ou invalides pour la création du rendez-vous");
        }

        $rendezVousDTO = new RendezVousDTO(
            $parsedBody['id_patient'],
            $parsedBody['id_praticien'],
            $parsedBody['specialite_praticien'],
            $parsedBody['lieu'],
            $parsedBody['horaire'],
            $parsedBody['type']
        );

        try {
            $idRdv = $this->rendezVousService->createRendezVous($rendezVousDTO);

            $location = "/rdvs/$idRdv";

            $rdv = $this->rendezVousService->getRendezVousById($idRdv);

            $data = [
                'rendez_vous' => $rdv,
                'links' => [
                    'self' => ['href' => $location],
                    'modifier' => ['href' => "$location"],
                    'annuler' => ['href' => "$location"],
                    'praticien' => ['href' => "/praticiens/{$rdv['id_praticien']}"],
                    'patient' => ['href' => "/patients/{$rdv['id_patient']}"]
                ]
            ];

            $rs->getBody()->write(json_encode($data));
            return $rs->withHeader('Content-Type', 'application/json')
                ->withHeader('Location', $location)
                ->withStatus(201);

        } catch (\Exception $e) {
            return $rs->withStatus(500)->withHeader('Content-Type', 'application/json')
                ->getBody()->write(json_encode(['message' => 'Erreur interne du serveur']));
        }
    }
}
