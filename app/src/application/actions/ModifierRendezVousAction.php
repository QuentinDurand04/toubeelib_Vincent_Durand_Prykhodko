<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use \toubeelib\core\services\rdv\ServiceRendezvous;
use Slim\Exception\HttpBadRequestException;

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
        $parsedBody = $rq->getParsedBody();

        if (!isset($parsedBody['specialite_praticien']) && !isset($parsedBody['id_patient'])) {
            throw new HttpBadRequestException($rq, "Données manquantes ou invalides pour la modification");
        }

        try {
            $rdv = $this->rendezVousService->getRendezVousById($id);

            if (isset($parsedBody['specialite_praticien'])) {
                $rdv['specialite_praticien'] = $parsedBody['specialite_praticien'];
            }

            if (isset($parsedBody['id_patient'])) {
                $rdv['id_patient'] = $parsedBody['id_patient'];
            }

            $this->rendezVousService->modifierRendezVous($id, $rdv);

            // Construction de la réponse avec les liens HATEOAS
            $data = [
                'rendez_vous' => $rdv,
                'links' => [
                    'self' => ['href' => "/rdvs/$id/"],
                    'modifier' => ['href' => "/rdvs/$id/"],
                    'annuler' => ['href' => "/rdvs/$id/"],
                    'praticien' => ['href' => "/praticiens/{$rdv['id_praticien']}"],
                    'patient' => ['href' => "/patients/{$rdv['id_patient']}"]
                ]
            ];

            $rs->getBody()->write(json_encode($data));
            return $rs->withHeader('Content-Type', 'application/json')->withStatus(200);

        } catch (\Exception $e) {
            if ($e instanceof HttpNotFoundException) {
                throw new HttpNotFoundException($rq, "Rendez-vous $id non trouvé");
            }

            return $rs->withStatus(500)->withHeader('Content-Type', 'application/json')
                ->getBody()->write(json_encode(['message' => 'Erreur interne du serveur']));
        }
    }
}
