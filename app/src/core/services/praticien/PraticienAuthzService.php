<?php

namespace toubeelib\core\services\praticien;

class PraticienAuthzService
{
    public function canAccessPraticienProfile(array $role, string $id): bool
    {
        if ($role['role'] == 10) {
            return true;
        }

        if ($role['role'] == 5 && $role['id'] == $id) {
            return true;
        }

        return false;
    }

    public function canModifyPraticienProfile(array $role, string $id): bool
    {
        return $this->canAccessPraticienProfile($role, $id);
    }

    public function canDeletePraticienProfile(array $role): bool
    {
        return $role['role'] == 10;
    }
}
