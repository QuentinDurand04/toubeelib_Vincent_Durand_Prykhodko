<?php

namespace auth\application\actions;

use auth\application\renderer\JsonRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class TokenValidation extends AbstractAction
{

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $token = $rq->getHeader('Authorization');
        if (empty($token)) {
            return JsonRenderer::render($rs, 401, ['message' => 'Token not found']);
        }
        $token = $token[0];
        $token = str_replace('Bearer ', '', $token);
        $token = trim($token);

        try {
            $this->authProvider->validateToken($token);
            return JsonRenderer::render($rs, 200, "Valid token");
        } catch (\Exception $e) {
            return JsonRenderer::render($rs, 401, ['message' => $e->getMessage()]);
        }

    }
}