<?php

namespace gateway\application\actions;


use DI\Container;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
//use GuzzleHttp;

abstract class AbstractAction
{
    protected Container $cont;


    public function __construct(Container $cont)
    {
    }

    abstract public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface ;
    

}
