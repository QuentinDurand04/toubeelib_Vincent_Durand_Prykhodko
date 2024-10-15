<?php

namespace toubeelib\core\services\auth;

use Firebase\JWT\JWT;
use toubeelib\core\repositoryInterfaces\UserRepositoryInterface;
use toubeelib\core\domain\entities\users\User;
use toubeelib\core\dto\AuthDTO;

class AuthService
{
    private UserRepositoryInterface $userRepository;
    private string $secret;

    public function __construct(UserRepositoryInterface $userRepository, string $secret)
    {
        $this->userRepository = $userRepository;
        $this->secret = $secret;
    }

    public function authenticate(string $email, string $password): AuthDTO
    {
        $user = $this->userRepository->getUserByEmail($email);

        if (!$user || !password_verify($password, $user->getPassword())) {
            throw new \Exception("Invalid credentials");
        }

        $payload = [
            'iss' => 'http://auth.toubeelib.net',
            'aud' => 'http://api.toubeelib.net',
            'iat' => time(),
            'exp' => time() + 3600,
            'sub' => $user->getId(),
            'data' => [
                'role' => $user->getRole(),
                'email' => $user->getEmail()
            ]
        ];

        $token = JWT::encode($payload, $this->secret, 'HS512');

        return new AuthDTO($user->getId(), $user->getEmail(), $user->getRole(), $token);
    }

    public function getSecret(): string
    {
        return $this->secret;
    }
}