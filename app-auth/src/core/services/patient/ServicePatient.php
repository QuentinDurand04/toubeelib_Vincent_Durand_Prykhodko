<?php

namespace auth\core\services\patient;

use DI\Container;
use auth\core\dto\PatientDTO;
use auth\core\repositoryInterfaces\PatientRepositoryInterface;

class ServicePatient implements ServicePatientInterface{
    protected PatientRepositoryInterface $repoPatient;
    public function __construct(Container $cont)
    {
        $this->repoPatient = $cont->get(PatientRepositoryInterface::class);
    }

    public function getPatientById(string $id): PatientDTO
    {
        $patient = $this->repoPatient->getPatientById($id);

        return new PatientDTO($patient);
    }
}
