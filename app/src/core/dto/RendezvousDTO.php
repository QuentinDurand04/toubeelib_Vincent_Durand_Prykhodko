<?php

namespace toubeelib\core\dto;

use toubeelib\core\domain\entities\rdv\Rendezvous;

class RendezvousDTO extends DTO
{
    public string $rendezvousID;
    public string $praticienID;
    public string $patientID;
    public string $specialite;
    public \DateTime $dateTime;

    public function __construct(string $rendezvousID, Rendezvous $rendezvous)
    {
        $this->rendezvousID = $rendezvousID;
        $this->praticienID = $rendezvous->getPraticienID();
        $this->patientID = $rendezvous->getPatientID();
        $this->specialite = $rendezvous->getSpecialite();
        $this->dateTime = $rendezvous->getDateTime();
    }
}
