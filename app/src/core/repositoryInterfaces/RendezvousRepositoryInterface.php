<?php

namespace toubeelib\core\repositoryInterfaces;

use toubeelib\core\domain\entities\rdv\Rendezvous;

interface RendezvousRepositoryInterface
{
    public function getRendezvousById(string $id): Rendezvous;
    public function save(Rendezvous $rendezvous): string;
    public function getRendezvousByPraticienId(string $praticienID, \DateTime $debut, \DateTime $fin): array;
}
