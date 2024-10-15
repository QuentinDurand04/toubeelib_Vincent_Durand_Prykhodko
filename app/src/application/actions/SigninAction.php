<?php


namespace toubeelib\application\actions;

use AuthProvider;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;

class SigninAction extends AbstractAction
{
    private AuthProvider $authProvider;

    public function __construct(AuthProvider $authProvider)
    {
        $this->authProvider = $authProvider;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $data = $rq->getParsedBody();

        if (!isset($data['email'], $data['password'])) {
            throw new HttpBadRequestException($rq, 'Email and password are required');
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL) || !is_string($data['password'])) {
            throw new HttpBadRequestException($rq, 'Invalid email or password format');
        }

        try {
            $tokens = $this->authProvider->signin($data['email'], $data['password']);

            $rs->getBody()->write(json_encode($tokens));
            return $rs->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (\Exception $e) {
            $rs->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $rs->withHeader('Content-Type', 'application/json')->withStatus(401);
        }
    }
}
