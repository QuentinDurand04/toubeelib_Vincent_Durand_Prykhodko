<?php


use Firebase\JWT\JWT;
use toubeelib\core\services\auth\AuthService;

class AuthProvider
{
    private AuthService $authService;
    private string $secret;
    private string $refreshSecret;

    public function __construct(AuthService $authService, string $secret, string $refreshSecret)
    {
        $this->authService = $authService;
        $this->secret = $secret;
        $this->refreshSecret = $refreshSecret;
    }

    public function signin(string $email, string $password): array
    {
        $user = $this->authService->authenticate($email, $password);

        $accessToken = $this->createToken($user, $this->secret);

        $refreshToken = $this->createToken($user, $this->refreshSecret, true);

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'user' => $user
        ];
    }

    private function createToken(array $user, string $secret, bool $isRefresh = false): string
    {
        $payload = [
            'iss' => 'http://auth.myapp.net',
            'aud' => 'http://api.myapp.net',
            'iat' => time(),
            'exp' => time() + ($isRefresh ? 604800 : 3600),
            'sub' => $user['id'],
            'data' => [
                'role' => $user['role'],
                'user' => $user['email']
            ]
        ];

        return JWT::encode($payload, $secret, 'HS512');
    }
}
