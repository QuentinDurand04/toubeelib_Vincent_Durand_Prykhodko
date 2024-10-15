<?php

namespace toubeelib\infrastructure\repositories;

use toubeelib\core\models\User;
use toubeelib\core\repositoryInterfaces\UserRepositoryInterface;

class BddUserRepository implements UserRepositoryInterface{

    public function getUserByEmail(string $email): ?User
    {
        //TODO
        return null;
    }
}   