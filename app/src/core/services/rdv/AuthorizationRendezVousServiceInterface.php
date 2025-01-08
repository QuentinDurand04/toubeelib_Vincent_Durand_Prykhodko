<?php
namespace toubeelib\core\services\rdv;

interface AuthorizationRendezVousServiceInterface
{
    public function isGranted(string $userId, int $operation, string $ressourceId, int $role): bool;

}
