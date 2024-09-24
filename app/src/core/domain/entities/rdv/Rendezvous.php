<?php

namespace toubeelib\core\domain\entities\rdv;

use toubeelib\core\domain\entities\Entity;
use toubeelib\core\dto\RendezvousDTO;

class Rendezvous extends Entity
{
    protected string $praticienID;
    protected string $patientID;
    protected string $specialite;
    protected \DateTime $dateTime;
    protected string $statut;

    public function __construct(string $praticienID, string $patientID, string $specialite, \DateTime $dateTime)
    {
        $this->praticienID = $praticienID;
        $this->patientID = $patientID;
        $this->specialite = $specialite;
        $this->dateTime = $dateTime;
    }

    public function getPraticienID(): string
    {
        return $this->praticienID;
    }

    public function getPatientID(): string
    {
        return $this->patientID;
    }

    public function getSpecialite(): string
    {
        return $this->specialite;
    }

    public function getDateTime(): \DateTime
    {
        return $this->dateTime;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function setPraticienID(string $praticienID): void
    {
        $this->praticienID = $praticienID;
    }

    public function setPatientID(string $patientID): void
    {
        $this->patientID = $patientID;
    }

    public function setSpecialite(string $specialite): void
    {
        $this->specialite = $specialite;
    }

    public function setDateTime(\DateTime $dateTime): void
    {
        $this->dateTime = $dateTime;
    }

    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }

    public function toDTO(): RendezvousDTO
    {
        return new RendezvousDTO($this->id, $this);
    }
}
