<?php

namespace toubeelib\infrastructure\repositories;

use toubeelib\core\repositoryInterfaces\UserRepositoryInterface;
use toubeelib\core\domain\entities\users\User;

class BddUserRepository implements UserRepositoryInterface
{
    public function getUserByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }
}   