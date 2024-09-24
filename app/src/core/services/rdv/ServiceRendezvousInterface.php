<?php

namespace toubeelib\core\services\rdv;

use toubeelib\core\dto\InputRendezvousDTO;
use toubeelib\core\dto\RendezvousDTO;

interface ServiceRendezvousInterface
{
    public function getRendezvousById(string $id): RendezvousDTO;
    public function creerRendezvous(InputRendezvousDTO $inputRendezvousDTO): RendezvousDTO;
    public function modifierRendezvous(string $rendezvousID, InputRendezvousDTO $inputRendezvousDTO): RendezvousDTO;
    public function listerDisponibilites(string $praticienID, \DateTime $debut, \DateTime $fin): array;
    public function annulerRendezvous(string $rendezvousId): void;
    public function marquerRendezvousHonore(string $rendezvousId): void;
    public function marquerRendezvousNonHonore(string $rendezvousId): void;
    public function marquerRendezvousPaye(string $rendezvousId): void;
    public function marquerRendezvousTransmis(string $rendezvousId): void;
}
