<?php

namespace auth\core\services\praticien;

use DI\Container;
use auth\core\dto\InputPraticienDTO;
use auth\core\dto\PraticienDTO;
use auth\core\dto\SpecialiteDTO;

interface ServicePraticienInterface
{

    public function __construct(Container $cont);
    public function createPraticien(InputPraticienDTO $p): PraticienDTO;
    public function getAllPraticiens(): array;
    public function getPraticienById(string $id): PraticienDTO;
    public function getSpecialiteById(string $id): SpecialiteDTO;
    public function searchPraticien(PraticienDTO $pratSearch): array;


}
