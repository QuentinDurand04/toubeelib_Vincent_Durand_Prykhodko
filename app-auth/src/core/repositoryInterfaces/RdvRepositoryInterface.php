<?php

namespace auth\core\repositoryInterfaces;

use DI\Container;
use SebastianBergmann\CodeCoverage\Report\Html\Renderer;
use auth\core\domain\entities\rdv\RendezVous;
use auth\core\dto\InputRdvDto;

interface RdvRepositoryInterface
{

    public function __construct(Container $cont);
    public function getRdvById(string $id): RendezVous;
    public function getAllRdvs(): array;
    public function addRdv(string $id, RendezVous $rdv):void;
    public function delete(string $id):void;
    public function cancelRdv(string $id,  ): void;
    public function getRdvByPraticien(string $id):array;
    public function getRdvsByPraticien(string $date):array;

    public function getRdvByPatient(string $id):array;
    public function modifierRdv(RendezVous $rdv): void;

}
