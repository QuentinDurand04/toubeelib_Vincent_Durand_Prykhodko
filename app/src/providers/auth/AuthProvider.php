<?php

namespace toubeelib\providers\auth;

use toubeelib\core\services\auth\AuthService;
use Firebase\JWT\JWT;
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
            $authData = $this->authService->authenticate($email, $password);

            $refreshPayload = [
                'iss' => 'http://auth.toubeelib.net',
                'aud' => 'http://api.toubeelib.net',
                'iat' => time(),
                'exp' => time() + 604800,
                'sub' => $authData['user']['id'],
                'data' => [
                    'role' => $authData['user']['role'],
                    'email' => $authData['user']['email']
                ]
            ];

            $refreshToken = JWT::encode($refreshPayload, $this->authService->getSecret(), 'HS512');

            return [
                'access_token' => $authData['token'],
                'refresh_token' => $refreshToken
            ];

        } catch (Exception $e) {
            throw new Exception("Invalid credentials");
        }
    }
}
