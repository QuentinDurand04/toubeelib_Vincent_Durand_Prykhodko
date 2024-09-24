<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use \toubeelib\core\services\rdv\ServiceRendezvous;

class AnnulerRendezVousAction extends AbstractAction
{
    private ServiceRendezvous $rendezVousService;

    public function __construct(ServiceRendezvous $rendezVousService)
    {
        $this->rendezVousService = $rendezVousService;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $idRdv = $args['id'];

        try {
            $rendezVous = $this->rendezVousService->getRendezVousById($idRdv);
            if (!$rendezVous) {
                throw new HttpNotFoundException($rq, "Rendez-vous $idRdv non trouvé");
            }

            $this->rendezVousService->annulerRendezVous($idRdv);

            $data = [
                'message' => "Rendez-vous annulé avec succès",
                'links' => [
                    'self' => ['href' => "/rdvs/$idRdv"],
                    'creer' => ['href' => "/rdvs"]
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
