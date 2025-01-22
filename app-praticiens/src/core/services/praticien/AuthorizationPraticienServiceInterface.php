<?php
namespace praticiens\core\services\praticien;

interface AuthorizationPraticienServiceInterface
{
    public function isGranted(string $userId, int $operation, string $ressourceId, int $role): bool;

}
