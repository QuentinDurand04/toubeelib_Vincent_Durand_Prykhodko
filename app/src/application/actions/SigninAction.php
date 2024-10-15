<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubeelib\providers\auth\AuthProvider;
use Slim\Exception\HttpBadRequestException;

class SigninAction
{
    private AuthProvider $authProvider;

    public function __construct(AuthProvider $authProvider)
    {
        $this->authProvider = $authProvider;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = $request->getParsedBody();

        if (!isset($data['email']) || !isset($data['password'])) {
            throw new HttpBadRequestException($request, 'Email et mot de passe requis');
        }

        try {
            $tokens = $this->authProvider->signin($data['email'], $data['password']);

            $response->getBody()->write(json_encode([
                'message' => 'Authentification réussie',
                'access_token' => $tokens['access_token'],
                'refresh_token' => $tokens['refresh_token']
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);

        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }
    }
}
