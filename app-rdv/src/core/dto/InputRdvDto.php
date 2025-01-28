<?php

namespace rdv\core\dto;

use DateTimeImmutable;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use rdv\application\actions\AbstractAction;
use rdv\core\services\rdv\ServiceRDVInvalidDataException;
use function PHPUnit\Framework\isFalse;

class InputRdvDto extends DTO
{
    protected string $praticienId, $specialite, $patientId, $id;
    protected \DateTimeImmutable $dateHeure;

    public function setId(string $id):void{
        $this->id=$id;
    }
    public function getId():string {
        return $this->id;
    }


    public function getPraticienId(): string
    {
        return $this->praticienId;
    }

    public function getPatientId(): string
    {
        return $this->patientId;
    }

    public function getDateHeure(): \DateTimeImmutable
    {
        return $this->dateHeure;
    }

    /**
     * @param string $praticienId
     * @param string $specialite
     * @param string $patientId
     * @param \DateTimeImmutable $dateHeure
     */
    public function __construct(string $praticienId, string $patientId, string $DateHeure)
    {
        $this->praticienId = $praticienId;
        $this->patientId = $patientId;
        $this->dateHeure = \DateTimeImmutable::createFromFormat('Y-m-d H:i', $DateHeure );
        if($this->dateHeure == false){
            throw new ServiceRDVInvalidDataException('format de date invalide');
        }
    }
    /**
     * @param array<int,mixed> $rdv
     * inputRdvDto depuis array avec praticienId, patientId, specialite, dateHeure
     */
    public static function fromArray(array $rdv): InputRdvDto{
        return new InputRdvDto($rdv['praticienId'], $rdv['patientId'], $rdv['dateHeure']);
    }



}
