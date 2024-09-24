<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use toubeelib\core\services\rdv\ServiceRendezvous;
use toubeelib\core\dto\RendezvousDTO;
use toubeelib\infrastructure\repositories\ArrayRdvRepository;

class RendezVousAction extends AbstractAction
{ /*
    private ServiceRendezvous $rendezVousService;

    public function __construct(ServiceRendezvous $rendezVousService)
    {
        $this->rendezVousService = $rendezVousService;
    }*/

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $id = $args['id'];
        
        try {
            $repo = new ArrayRdvRepository();
            $rendezvous = $repo->getRendezvousById($id);
            
            $rdv = new RendezvousDTO($id, $rendezvous);
            
            $data = [
                'rendez_vous' => $rdv,
                'links' => [
                    'self' => ['href' => "/rdvs/$id/", 'method' => "GET"],
                    'modifier' => ['href' => "/rdvs/$id/", 'method' => "PATCH"],
                    'annuler' => ['href' => "/rdvs/$id/", 'method' => "DELETE"],
                    'praticien' => ['href' => "/praticiens/{$rdv->praticienID}", 'method' => "GET"],
                    'patient' => ['href' => "/patients/{$rdv->patientID}", 'method' => "GET"]
                ]
            ];

            $rs->getBody()->write(json_encode($data));
            return $rs->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (\Exception $e) {
            throw new HttpNotFoundException($rq, "Rendez-vous $id non trouvé");
        }
    }
}
