<?php

namespace auth\core\repositoryInterfaces;

use DI\Container;
use auth\core\domain\entities\praticien\Praticien;
use auth\core\domain\entities\praticien\Specialite;

interface PraticienRepositoryInterface
{

    public function __construct(Container $cont);
    public function getSpecialiteById(string $id): Specialite;
    public function getAllPraticiens(): array;
    public function save(Praticien $praticien): string;
    public function getPraticienById(string $id): Praticien;
    public function searchPraticiens(Praticien $praticien): array;

}
