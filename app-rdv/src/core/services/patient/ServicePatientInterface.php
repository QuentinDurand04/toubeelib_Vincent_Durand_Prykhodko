<?php

namespace rdv\core\services\patient;

use DI\Container;
use rdv\core\dto\InputRdvDto;
use rdv\core\dto\PatientDTO;
use rdv\core\dto\RdvDTO;

interface ServicePatientInterface
{

    public function __construct(Container $cont);

    public function getPatientById(string $id): PatientDTO;
}
