<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use toubeelib\core\dto\InputRendezvousDTO;
use toubeelib\core\services\rdv\exceptions\ServiceRendezvousInvalidDataException;
use toubeelib\core\services\rdv\exceptions\ServiceRendezVousInvalidInputDataException;
use toubeelib\core\services\rdv\ServiceRendezvous;

class ModifierRendezVousAction extends AbstractAction
{
    private ServiceRendezvous $rendezVousService;

    public function __construct(ServiceRendezvous $rendezVousService)
    {
        $this->rendezVousService = $rendezVousService;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $id = $args['id'];
        $queryParams = $rq->getQueryParams();

        if (!isset($queryParams['specialite_praticien']) && !isset($queryParams['id_patient'])) {
            throw new HttpBadRequestException($rq, "Données manquantes ou invalides pour la modification");
        }

        try {
            $rdv = $this->rendezVousService->getRendezVousById($id);

            if (isset($queryParams['specialite_praticien'])) {
                $rdv->specialite = $queryParams['specialite_praticien'];
            }

            if (isset($queryParams['id_patient'])) {
                $rdv->patientID = $queryParams['id_patient'];
            }

            $this->rendezVousService->modifierRendezVous($id, new InputRendezvousDTO($rdv->praticienID, $rdv->patientID, $rdv->specialite, $rdv->dateTime));

            // Construction de la réponse avec les liens HATEOAS
            $data = [
                'rendez_vous' => $rdv,
                'links' => [
                    'self' => ['href' => "/rdvs/$id/"],
                    'modifier' => ['href' => "/rdvs/$id/"],
                    'annuler' => ['href' => "/rdvs/$id/"],
                    'praticien' => ['href' => "/praticiens/{$rdv->praticienID}"],
                    'patient' => ['href' => "/patients/{$rdv->patientID}"]
                ]
            ];

            $rs->getBody()->write(json_encode($data));
            return $rs->withHeader('Content-Type', 'application/json')->withStatus(200);

        }
        catch(ServiceRendezvousInvalidDataException $e) {
            $responseBody = $rs->getBody();
            $responseBody->write(json_encode(['message' => $e->getMessage()]));
            return $rs
                    ->withStatus(404)
                    ->withHeader('Content-Type', 'application/json')
                    ->withBody($responseBody);
        }

        catch(ServiceRendezVousInvalidInputDataException $e) {
            $responseBody = $rs->getBody();
            $responseBody->write(json_encode(['message' => $e->getMessage()]));
            return $rs
                ->withStatus(400)
                ->withHeader('Content-Type', 'application/json')
                ->withBody($responseBody);
        }

        catch (\Exception $e) {
            $responseBody = $rs->getBody();
            $responseBody->write(json_encode(['message' => $e->getMessage()]));
            return $rs
                ->withStatus(500)
                ->withHeader('Content-Type', 'application/json')
                ->withBody($responseBody);
        }
    }
}
