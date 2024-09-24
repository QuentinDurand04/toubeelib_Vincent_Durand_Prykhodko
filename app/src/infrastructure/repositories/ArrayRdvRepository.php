<?php

namespace toubeelib\infrastructure\repositories;

use DateTime;
use Ramsey\Uuid\Uuid;
use toubeelib\core\domain\entities\rdv\Rendezvous;
use toubeelib\core\repositoryInterfaces\RepositoryEntityNotFoundException;
use toubeelib\core\repositoryInterfaces\RendezvousRepositoryInterface;

class ArrayRdvRepository implements RendezvousRepositoryInterface
{
    private array $rdvs = [];

    public function __construct() {
            $r1 = new Rendezvous('p1', 'pa1', 'A', \DateTime::createFromFormat('Y-m-d H:i','2024-09-02 09:00') );
            $r1->setID('r1');
            $r2 = new Rendezvous('p1', 'pa1', 'A', \DateTime::createFromFormat('Y-m-d H:i','2024-09-02 10:00'));
            $r2->setID('r2');
            $r3 = new Rendezvous('p2', 'pa1', 'A', \DateTime::createFromFormat('Y-m-d H:i','2024-09-02 09:30'));
            $r3->setID('r3');

        $this->rdvs  = ['r1'=> $r1, 'r2'=>$r2, 'r3'=> $r3 ];
    }

    public function getRendezvousById(string $id): Rendezvous
    {
        $rdv = $this->rdvs[$id] ??
            throw new RepositoryEntityNotFoundException("Rendez vous $id not found");

        return $rdv;    
    }

    public function save(Rendezvous $rdv): string{
        //TODO
        return "";
    }

    public function getRendezvousByPraticienId(string $praticienID, DateTime $debut, DateTime $fin): array
    {
        //TODO
        return [];
    }
}