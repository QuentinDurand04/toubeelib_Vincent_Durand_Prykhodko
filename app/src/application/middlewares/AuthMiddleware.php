<?php

namespace toubeelib\application\middlewares;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;
use Slim\Psr7\Response as SlimResponse;
use Exception;

class AuthMiddleware
{
    private string $secret;

    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $authHeader = $request->getHeader('Authorization');

        if (!$authHeader || empty($authHeader[0])) {
            return $this->unauthorizedResponse("Missing Authorization header");
        }

        $tokenString = sscanf($authHeader[0], "Bearer %s")[0];

        if (!$tokenString) {
            return $this->unauthorizedResponse("Malformed Authorization header");
        }

        try {
            $token = JWT::decode($tokenString, new Key($this->secret, 'HS512'));

            $user = [
                'id' => $token->sub,
                'email' => $token->data->user,
                'role' => $token->data->role
            ];

            $request = $request->withAttribute('auth_user', $user);

            return $handler->handle($request);

        } catch (ExpiredException $e) {
            return $this->unauthorizedResponse("Token has expired");
        } catch (SignatureInvalidException $e) {
            return $this->unauthorizedResponse("Invalid token signature");
        } catch (BeforeValidException $e) {
            return $this->unauthorizedResponse("Token not yet valid");
        } catch (Exception $e) {
            return $this->unauthorizedResponse("Invalid token");
        }
    }

    private function unauthorizedResponse(string $message): Response
    {
        $response = new SlimResponse();
        $response->getBody()->write(json_encode([
            'error' => 'Unauthorized',
            'message' => $message
        ]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
}
