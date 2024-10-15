<?php

namespace toubeelib\application\middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface as Response;
use toubeelib\core\services\praticien\PraticienAuthzService;
use Slim\Psr7\Response as SlimResponse;

class AuthzMiddlewares
{
    private PraticienAuthzService $authService;

    public function __construct(PraticienAuthzService $authService)
    {
        $this->authService = $authService;
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $role = $request->getAttribute('role');

        $id = $request->getAttribute('id');

        if (!$this->authService->canAccessPraticienProfile($role, $id)) {
            return $this->forbiddenResponse("You are not authorized to access this profile.");
        }

        return $handler->handle($request);
    }

    private function forbiddenResponse(string $message): Response
    {
        $response = new SlimResponse();
        $response->getBody()->write(json_encode([
            'error' => 'Forbidden',
            'message' => $message
        ]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(403);
    }
}
