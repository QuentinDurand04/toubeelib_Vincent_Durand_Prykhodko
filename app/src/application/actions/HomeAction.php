<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubeelib\infrastructure\repositories\BddUserRepository;

 class HomeAction extends AbstractAction
{


    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface {
        $userRepository = new BddUserRepository();
        //$rs->getBody()->write($userRepository->getUserByEmail('test@test.com'));
        return $rs;}

}