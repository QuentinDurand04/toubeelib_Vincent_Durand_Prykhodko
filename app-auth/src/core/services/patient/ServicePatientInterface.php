<?php

namespace auth\core\services\patient;

use DI\Container;
use auth\core\dto\InputRdvDto;
use auth\core\dto\PatientDTO;
use auth\core\dto\RdvDTO;

interface ServicePatientInterface
{

    public function __construct(Container $cont);

    public function getPatientById(string $id): PatientDTO;
}
