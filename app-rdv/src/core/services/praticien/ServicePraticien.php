<?php

namespace rdv\core\services\praticien;

// ! Not use
use DI\Container;
use GuzzleHttp\Client;
use rdv\core\domain\entities\praticien\Specialite;
use Respect\Validation\Exceptions\NestedValidationException;
use rdv\core\domain\entities\praticien\Praticien;
// ! Not use


use rdv\core\dto\InputPraticienDTO;
use rdv\core\dto\PraticienDTO;
use rdv\core\dto\SpecialiteDTO;
use rdv\core\repositoryInterfaces\PraticienRepositoryInterface;
use rdv\core\repositoryInterfaces\RepositoryEntityNotFoundException;

class ServicePraticien implements ServicePraticienInterface
{
    private PraticienRepositoryInterface $praticienRepository;
    private Client $guzzle;

    public function __construct(Container $cont)
    {
        $this->praticienRepository = $cont->get(PraticienRepositoryInterface::class);
        $this->guzzle = $cont->get('guzzle.client');
    }

    private function getSpecialiteByLabel(string $label): SpecialiteDTO
    {
        // Assuming there is a method in the repository to get Specialite by label
        $specialite = $this->praticienRepository->getSpecialiteByLabel($label);
        return $specialite->toDTO();
    }

    public function createPraticien(InputPraticienDTO $p): PraticienDTO
    {
        // TODO : valider les données et créer l'entité
        return new PraticienDTO($$p);


    }

    public function getPraticienById(string $id): PraticienDTO
    {
        try {
            $reponse = $this->guzzle->get('praticiens/'.$id);
            $return = json_decode($reponse->getBody()->getContents(), true);
            $praticien = new Praticien($return['nom'], $return['prenom'], $return['adresse'], $return['tel']);
            $praticien->setId($return['id']);
            $spedto = $this->getSpecialiteByLabel($return['specialiteLabel']);
            $praticien->setSpecialite(new Specialite($spedto->id, $spedto->label, $spedto->description));
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
