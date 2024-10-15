<?php

namespace toubeelib\infrastructure\repositories;

use PDO;
use toubeelib\core\domain\entities\users\User;
use toubeelib\core\repositoryInterfaces\UserRepositoryInterface;

class BddUserRepository implements UserRepositoryInterface{

    public function getUserByEmail(string $email): ?User
    {
        $pdo = new PDO('postgres:host=localhost;dbname='.getenv('POSTGRES_DB'), getenv('POSTGRES_USER'), getenv('POSTGRES_PASSWORD'));
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $user = new User($stmt->fetch()['id'], $stmt->fetch()['email'], $stmt->fetch()['password'], $stmt->fetch()['role']);
        return $user;
    }
}   