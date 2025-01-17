<?php
namespace rdv\core\services\rdv;
use Psr\Container\ContainerInterface;
use rdv\core\repositoryInterfaces\RdvRepositoryInterface;
use rdv\core\repositoryInterfaces\RepositoryEntityNotFoundException;
use rdv\core\services\ServiceRessourceNotFoundException;
use rdv\core\services\rdv\AuthorizationRendezVousServiceInterface;

class AuthorizationRendezVousService implements AuthorizationRendezVousServiceInterface{
    protected RdvRepositoryInterface $rdvrepo;
    public function __construct(ContainerInterface $co)
    {
        $this->rdvrepo = $co->get(RdvRepositoryInterface::class);
    }
    public function isGranted(string $userId, int $operation, string $ressourceId, int $role): bool
    {
        try{
        $rdv = $this->rdvrepo->getRdvById($ressourceId);
        }catch(RepositoryEntityNotFoundException $e){
            throw new ServiceRessourceNotFoundException("Rendez vous $ressourceId n'existe pas");
        }
        if($role === 0 && $userId === $rdv->patientId){
            return true;
        }else if($role === 10 && $userId === $rdv->praticienId) {
            return true;
        }else
            return false;
    

    }

}
