<?php

namespace toubeelib\providers\auth;

use Firebase\JWT\JWT;
use toubeelib\core\services\auth\AuthService;
use toubeelib\core\dto\AuthDTO;
use Exception;

class AuthProvider
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function signin(string $email, string $password): array
    {
        try {
            $authDTO = $this->authService->authenticate($email, $password);

            $refreshPayload = [
                'iss' => 'http://auth.toubeelib.net',
                'aud' => 'http://api.toubeelib.net',
                'iat' => time(),
                'exp' => time() + 604800,
                'sub' => $authDTO->getId(),
                'data' => [
                    'role' => $authDTO->getRole(),
                    'email' => $authDTO->getEmail()
                ]
            ];

            $refreshToken = JWT::encode($refreshPayload, $this->authService->getSecret(), 'HS512');

            return [
                'access_token' => $authDTO->getToken(),
                'refresh_token' => $refreshToken
            ];

        } catch (Exception $e) {
            throw new Exception("Invalid credentials");
        }
    }
}
