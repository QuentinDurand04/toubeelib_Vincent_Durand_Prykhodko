<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubeelib\core\services\rdv\ServiceRendezvous;
use toubeelib\core\dto\InputRendezvousDTO;
use Slim\Exception\HttpBadRequestException;

class CreerRendezVousAction extends AbstractAction
{
    private ServiceRendezvous $rdvService;

    public function __construct(ServiceRendezvous $rdvService)
    {
        $this->rdvService = $rdvService;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $data = $rq->getParsedBody();

        if (!isset($data['praticien_id'], $data['patient_id'], $data['specialite'])) {
            throw new HttpBadRequestException($rq, 'Données manquantes ou invalides');
        }

        try {
            $rdvDTO = new InputRendezvousDTO(
                $data['praticien_id'],
                $data['patient_id'],
                $data['specialite']
            );

            $rdvID = $this->rdvService->creerRendezvous($rdvDTO);

            $rs = $rs->withHeader('Location', '/rdvs/' . $rdvID->rendezvousID);
            $rs->getBody()->write(json_encode(['message' => 'Rendez-vous créé avec succès']));
            return $rs->withHeader('Content-Type', 'application/json')->withStatus(201);
        } catch (\Exception $e) {
            $rs->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $rs->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}

