<?php

namespace toubeelib\core\services\praticien;

// ! Not use
use DI\Container;
use Respect\Validation\Exceptions\NestedValidationException;
use toubeelib\core\domain\entities\praticien\Praticien;
// ! Not use


use toubeelib\core\dto\InputPraticienDTO;
use toubeelib\core\dto\PraticienDTO;
use toubeelib\core\dto\SpecialiteDTO;
use toubeelib\core\repositoryInterfaces\PraticienRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RepositoryEntityNotFoundException;

class ServicePraticien implements ServicePraticienInterface
{
    private PraticienRepositoryInterface $praticienRepository;

    public function __construct(Container $cont)
    {
        $this->praticienRepository = $cont->get(PraticienRepositoryInterface::class);
    }

    public function createPraticien(InputPraticienDTO $p): PraticienDTO
    {
        // TODO : valider les données et créer l'entité
        return new PraticienDTO($$p);


    }

    public function getPraticienById(string $id): PraticienDTO
    {
        try {
            $praticien = $this->praticienRepository->getPraticienById($id);
            return new PraticienDTO($praticien);
        } catch(RepositoryEntityNotFoundException $e) {
            throw new ServicePraticienInvalidDataException('invalid Praticien ID');
        }
    }

    public function getSpecialiteById(string $id): SpecialiteDTO
    {
        try {
            $specialite = $this->praticienRepository->getSpecialiteById($id);
            return $specialite->toDTO();
        } catch(RepositoryEntityNotFoundException $e) {
            throw new ServicePraticienInvalidDataException('invalid Specialite ID');
        }
    }

    public function searchPraticien(PraticienDTO $p): array
    {
        $pratSearch= Praticien::fromDTO($p);
        return array_map(function(Praticien $p){
            return new PraticienDTO($p);
        }, $this->praticienRepository->searchPraticiens($pratSearch));
    }

    public function getAllPraticiens(): array
    {
        return array_map(function(Praticien $p){
            return new PraticienDTO($p);
        }, $this->praticienRepository->getAllPraticiens());
    }
}
