<?php

namespace toubeelib\core\repositoryInterfaces;
use toubeelib\core\domain\entities\users\User;

interface UserRepositoryInterface
{
    public function getUserByEmail(string $email): ?User;
}
