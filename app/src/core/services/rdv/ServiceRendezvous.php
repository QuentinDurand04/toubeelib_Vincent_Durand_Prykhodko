<?php

namespace toubeelib\core\services\rdv;

use Monolog\Logger;
use toubeelib\core\domain\entities\praticien\Praticien;
use toubeelib\core\domain\entities\rdv\Rendezvous;
use toubeelib\core\dto\InputRendezvousDTO;
use toubeelib\core\repositoryInterfaces\RendezvousRepositoryInterface;
use toubeelib\core\dto\RendezvousDTO;
use toubeelib\core\services\praticien\ServicePraticienInterface;
use toubeelib\core\services\praticien\ServicePraticienInvalidDataException;
use toubeelib\core\services\rdv\ServiceRendezvousInterface;
use toubeelib\core\repositoryInterfaces\RepositoryEntityNotFoundException;

class ServiceRendezvous implements ServiceRendezvousInterface
{
    private RendezvousRepositoryInterface $rendezvousRepository;
    private ServicePraticienInterface $servicePraticien;
    //private Logger $logger;

    public function __construct(RendezvousRepositoryInterface $rendezvousRepository, ServicePraticienInterface $servicePraticien)
    {
        $this->rendezvousRepository = $rendezvousRepository;
        $this->servicePraticien = $servicePraticien;
        //$this->logger = $logger;
    }

    public function getRendezvousById(string $id): RendezvousDTO
    {
        try {
            $rendezvous = $this->rendezvousRepository->getRendezvousById($id);
            return new RendezvousDTO($id, $rendezvous);
        } catch (RepositoryEntityNotFoundException $e) {
            throw new ServiceRendezvousInvalidDataException('Rendez-vous not found.');
        }
    }

    public function creerRendezvous(InputRendezvousDTO $inputRendezvousDTO): RendezvousDTO
    {
        try {
            $praticien = $this->servicePraticien->getPraticienById($inputRendezvousDTO->praticienID);
        } catch (ServicePraticienInvalidDataException $e) {
            throw new \Exception("Praticien introuvable avec l'ID fourni.");
        }

        if ($praticien->specialite_label !== $inputRendezvousDTO->specialite) {
            throw new \Exception("La spécialité demandée ne correspond pas à celle du praticien.");
        }

        /*if (!$this->isPraticienDisponible($praticien, $inputRendezvousDTO->dateTime)) {
            throw new \Exception("Le praticien n'est pas disponible à la date et heure demandées.");
        }*/

        $rendezvous = new Rendezvous(
            $inputRendezvousDTO->praticienID,
            $inputRendezvousDTO->patientID,
            $inputRendezvousDTO->specialite
        );

        $rendezvousID = $this->rendezvousRepository->save($rendezvous);

        return new RendezvousDTO($rendezvousID, $rendezvous);
    }

    private function isPraticienDisponible(Praticien $praticien, \DateTime $dateTime): bool
    {
        // TODO: implémenter la logique pour vérifier la disponibilité du praticien
        return true;
    }

    public function modifierRendezvous(string $rendezvousID, InputRendezvousDTO $inputRendezvousDTO): RendezvousDTO
    {
        try {
            $rendezvous = $this->rendezvousRepository->getRendezvousById($rendezvousID);

            $praticienID = $inputRendezvousDTO->praticienID;
            $specialiteID = $inputRendezvousDTO->specialiteID;
            $patientID = $inputRendezvousDTO->patientID;

            $praticien = $this->servicePraticien->getPraticienById($praticienID);
            $specialite = $this->servicePraticien->getSpecialiteById($specialiteID);

            if (!in_array($specialiteID, $praticien->specialites)) {
                throw new \Exception("La spécialité n'est pas valide pour ce praticien.");
            }

            if ($rendezvous->getPraticienID() !== $praticienID || $rendezvous->getSpecialite() !== $specialite) {
                $this->annulerRendezvous($rendezvousID);

                $newRendezvousDTO = new InputRendezvousDTO($praticienID, $specialiteID, $patientID, $inputRendezvousDTO->dateTime);
                return $this->creerRendezvous($newRendezvousDTO);
            } else {
                $rendezvous->setPatientID($patientID);
                $this->rendezvousRepository->save($rendezvous);

                //$this->logger->info("Rendez-vous modifié : ID = $rendezvousID, Nouveau patient = $patientID");

                return new RendezvousDTO($rendezvousID, $rendezvous);
            }
        } catch (\Exception $e) {
            //$this->logger->error("Erreur lors de la modification du rendez-vous : " . $e->getMessage());
            throw $e;
        }
    }

    public function listerDisponibilites(string $praticienID, \DateTime $debut, \DateTime $fin): array
    {
        try {
            $praticien = $this->servicePraticien->getPraticienById($praticienID);
        } catch (ServicePraticienInvalidDataException $e) {
            throw new \Exception("Praticien introuvable avec l'ID fourni.");
        }

        $rendezvousExistants = $this->rendezvousRepository->getRendezvousByPraticienId($praticienID, $debut, $fin);

        $joursConsultation = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi'];
        $heuresConsultation = ['09:00', '10:00', '11:00', '14:00', '15:00', '16:00'];
        $disponibilites = [];
        $currentDate = clone $debut;

        while ($currentDate <= $fin) {
            $jour = strtolower($currentDate->format('l'));

            if (in_array($jour, $joursConsultation)) {
                foreach ($heuresConsultation as $heure) {
                    $creneau = clone $currentDate;
                    $creneau->setTime((int)substr($heure, 0, 2), (int)substr($heure, 3, 2));

                    /*
                    if ($this->isPraticienDisponible($praticien, $rendezvousExistants)) {
                        $disponibilites[] = $creneau;
                    }
                    */
                }
            }
            $currentDate->modify('+1 day');
        }

        return $disponibilites;
    }

    public function annulerRendezvous(string $rendezvousId): void
    {
        try {
            $rendezvous = $this->rendezvousRepository->getRendezvousById($rendezvousId);
            $rendezvous->setStatut('annulé');
            $this->rendezvousRepository->save($rendezvous);

            // Log annulation
            //$this->logger->info("Rendez-vous annulé : ID = $rendezvousId");
        } catch (\Exception $e) {
            //$this->logger->error("Erreur lors de l'annulation du rendez-vous : " . $e->getMessage());
            throw $e;
        }
    }

    public function marquerRendezvousHonore(string $rendezvousId): void
    {
        try {
            $rendezvous = $this->rendezvousRepository->getRendezvousById($rendezvousId);
            $rendezvous->setStatut('honore');
            $this->rendezvousRepository->save($rendezvous);
            //$this->logger->info("Rendezvous marked as honored: $rendezvousId");
        } catch (RepositoryEntityNotFoundException $e) {
            throw new ServiceRendezvousInvalidDataException('Invalid Rendezvous ID');
        }
    }

    public function marquerRendezvousNonHonore(string $rendezvousId): void
    {
        try {
            $rendezvous = $this->rendezvousRepository->getRendezvousById($rendezvousId);
            $rendezvous->setStatut('non_honore');
            $this->rendezvousRepository->save($rendezvous);
            //$this->logger->info("Rendezvous marked as not honored: $rendezvousId");
        } catch (RepositoryEntityNotFoundException $e) {
            throw new ServiceRendezvousInvalidDataException('Invalid Rendezvous ID');
        }
    }

    public function marquerRendezvousPaye(string $rendezvousId): void
    {
        try {
            $rendezvous = $this->rendezvousRepository->getRendezvousById($rendezvousId);
            $rendezvous->setStatut('paye');
            $this->rendezvousRepository->save($rendezvous);
            //$this->logger->info("Rendezvous marked as paid: $rendezvousId");
        } catch (RepositoryEntityNotFoundException $e) {
            throw new ServiceRendezvousInvalidDataException('Invalid Rendezvous ID');
        }
    }

    public function marquerRendezvousTransmis(string $rendezvousId): void
    {
        try {
            $rendezvous = $this->rendezvousRepository->getRendezvousById($rendezvousId);
            $rendezvous->setStatut('transmis');
            $this->rendezvousRepository->save($rendezvous);
            //$this->logger->info("Rendezvous marked as transmitted: $rendezvousId");
        } catch (RepositoryEntityNotFoundException $e) {
            throw new ServiceRendezvousInvalidDataException('Invalid Rendezvous ID');
        }
    }
}
