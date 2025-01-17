<?php

namespace rdv\core\services\praticien;

use DI\Container;
use rdv\core\dto\InputPraticienDTO;
use rdv\core\dto\PraticienDTO;
use rdv\core\dto\SpecialiteDTO;

interface ServicePraticienInterface
{

    public function __construct(Container $cont);
    public function createPraticien(InputPraticienDTO $p): PraticienDTO;
    public function getAllPraticiens(): array;
    public function getPraticienById(string $id): PraticienDTO;
    public function getSpecialiteById(string $id): SpecialiteDTO;
    public function searchPraticien(PraticienDTO $pratSearch): array;


}
