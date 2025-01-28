<?php

namespace auth\core\repositoryInterfaces;

use DI\Container;
use auth\core\domain\entities\patient\Patient;

interface PatientRepositoryInterface{

    public function __construct(Container $co);
    public function getPatientById(string $id): Patient;
}
