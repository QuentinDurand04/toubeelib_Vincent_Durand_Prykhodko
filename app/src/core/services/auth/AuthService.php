<?php

namespace toubeelib\core\services\auth;

use Firebase\JWT\JWT;
use toubeelib\core\repositoryInterfaces\UserRepositoryInterface;

class AuthService
{
    private $userRepository;
    private $secret;

    public function __construct(UserRepositoryInterface $userRepository, string $secret)
    {
        $this->userRepository = $userRepository;
        $this->secret = $secret;
    }

    public function authenticate(string $email, string $password): array
    {
        $user = $this->userRepository->getUserByEmail($email);

        if (!$user || !password_verify($password, $user->password)) {
            throw new \Exception("Invalid credentials");
        }

        $payload = [
            'iss' => 'http://auth.myapp.net',
            'aud' => 'http://api.myapp.net',
            'iat' => time(),
            'exp' => time() + 3600,
            'sub' => $user->id,
            'data' => [
                'role' => $user->role,
                'user' => $user->email
            ]
        ];

        $token = JWT::encode($payload, $this->secret, 'HS512');

        return [
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'role' => $user->role
            ]
        ];
    }
}
