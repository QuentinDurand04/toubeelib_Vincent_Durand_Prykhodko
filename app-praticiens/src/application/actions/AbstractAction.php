<?php

namespace praticiens\application\actions;


use DI\Container;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use praticiens\core\services\ServiceAuthInterface;
use praticiens\core\services\praticien\ServicePraticienInterface;
use praticiens\core\services\rdv\ServiceRDVInterface;
use praticiens\providers\auth\AuthnProviderInterface;
use praticiens\core\services\patient\ServicePatientInterface;

abstract class AbstractAction
{
    protected ServicePraticienInterface $servicePraticien; 
    protected AuthnProviderInterface $authProvider;
    protected string $formatDate;
    protected Container $cont;

    protected Logger $loger;

    public function __construct(Container $cont)
    {
        $this->servicePraticien = $cont->get(ServicePraticienInterface::class);
        $this->formatDate = $cont->get('date.format');
        $this->loger = $cont->get(Logger::class)->withName(get_class($this));
    }

    abstract public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface ;
    

}
