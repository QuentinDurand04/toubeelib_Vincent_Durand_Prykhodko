<?php

namespace toubeelib\core\dto;

class InputRendezvousDTO extends DTO
{
    public string $praticienID;
    public string $patientID;
    public string $specialite;
    //public \DateTime $dateTime;

    public function __construct(string $praticienID, string $patientID, string $specialite)
    {
        $this->praticienID = $praticienID;
        $this->patientID = $patientID;
        $this->specialite = $specialite;
        //$this->dateTime = $dateTime;
    }
}
