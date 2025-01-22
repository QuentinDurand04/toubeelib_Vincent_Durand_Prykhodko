<?php

namespace rdv\core\services\rdv;

use DI\Container;
use rdv\core\dto\InputRdvDto;
use rdv\core\dto\RdvDTO;

interface ServiceRDVInterface
{

    public function __construct(Container $cont);
    public function getRdvById(string $id): RdvDTO;
    public function getAllRdvs(): array;
        public function creerRendezvous(InputRdvDto $rdv): RdvDTO;
        public function modifRendezVous(InputRdvDto $rdv): RdvDTO;
    public function getListeDisponibilite(string $id): array;
    public function getListeDisponibiliteDate(string $id, string $test_start_Date, string $test_end_Date): array;
    public function getRdvByPatient(string $id):array;

    public function getPlanningPraticien(string $idPraticien, ?string $test_start_Date, ?string $test_end_Date): array;
}
