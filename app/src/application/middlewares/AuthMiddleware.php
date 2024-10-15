<?php

namespace toubeelib\application\middlewares;

use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthMiddleware
{
    private $secret;

    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }

    public function __invoke(Request $request, $handler)
    {
        $header = $request->getHeader('Authorization')[0] ?? '';

        if (!$header) {
            throw new \Exception('Authorization header missing');
        }

        $tokenString = sscanf($header, 'Bearer %s')[0];

        try {
            $token = JWT::decode($tokenString, new Key($this->secret, 'HS512'));

            return $handler->handle($request);

        } catch (ExpiredException $e) {
            return $this->errorResponse('Token has expired');
        } catch (SignatureInvalidException $e) {
            return $this->errorResponse('Invalid token signature');
        } catch (BeforeValidException $e) {
            return $this->errorResponse('Token not valid yet');
        } catch (\Exception $e) {
            return $this->errorResponse('Invalid token');
        }
    }

    private function errorResponse($message)
    {
        $response = new \Slim\Psr7\Response();
        $response->getBody()->write(json_encode(['error' => $message]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
}
