<?php

namespace rdv\core\services\rdv;

use DI\Container;
use DateInterval;
use DateTimeImmutable;
use Error;
use Exception;
use Faker\Core\Uuid;
use Ramsey\Uuid\Uuid as RamseyUuid;
use rdv\core\domain\entities\rdv\RendezVous;
use rdv\core\dto\InputRdvDto;
use rdv\core\dto\RdvDTO;
use rdv\core\repositoryInterfaces\RdvRepositoryInterface;
use rdv\core\services\ServiceRessourceNotFoundException;
use rdv\core\services\ServiceOperationInvalideException;
use rdv\core\services\praticien\ServicePraticien;
use rdv\core\services\praticien\ServicePraticienInterface;
use rdv\core\repositoryInterfaces\RepositoryEntityNotFoundException;


class ServiceRDV implements ServiceRDVInterface {
    private RdvRepositoryInterface $rdvRepository;
    private ServicePraticien $servicePraticien;
    private string $dateFormat;

    public const INTERVAL = 30;
    public const HDEBUT = [9, 00];
    public const HFIN = [17, 30];

    public function __construct(Container $cont)
    {
        $this->rdvRepository = $cont->get(RdvRepositoryInterface::class);
        $this->servicePraticien = $cont->get(ServicePraticienInterface::class);
        $this->dateFormat = $cont->get('date.format');
    }

    public function getRdvById(string $id): RdvDTO
    {
        try {
            $rdv = $this->rdvRepository->getRdvById($id);
        } catch (RepositoryEntityNotFoundException $e) {
            throw new Exception('invalid RDV ID');
        }
        $praticien = $this->servicePraticien->getPraticienById($rdv->getPraticienId());
        return $rdv->toDTO($praticien);

    }

    /*string $praticienID, $patientID, string $specialite, \DateTimeImmutable $dateHeure*/
    public function creerRendezvous(InputRdvDto $inputRdvDto): RdvDTO
    {
        $rdv = RendezVous::fromInputDto($inputRdvDto);

        $id = RamseyUuid::uuid4()->__toString();
        $rdv->setId($id);

        try {
            $praticien = $this->servicePraticien->getPraticienById($rdv->getPraticienId());

            if (!in_array($rdv->getDateHeure(), $this->getListeDisponibilite($rdv->getPraticienId()))) {
                throw new \Exception("Praticien indisponible");
            }
        } catch (\Exception $e) {
            throw new ServiceRDVInvalidDataException("Création de rdv impossible : " . $e->getMessage());
        }
        $this->rdvRepository->addRdv($id, $rdv);
        return $rdv->toDTO($praticien);
    }

    // TODO: transferer methode a ServicePraticien
    public function getListeDisponibilite(string $idPraticien): array
    {

        $results = [];
        $listeRDV = $this->rdvRepository->getRdvByPraticien($idPraticien);
        $listeRDVHorraires = array_map(function ($rdv) {
            if ($rdv->status != RendezVous::ANNULE) {
                $rr= $rdv->dateHeure->format($this->dateFormat);
                return $rr;
            }
        }, $listeRDV);
        $startDate = new \DateTimeImmutable("now");
        $startDate = $startDate->setTime(ServiceRDV::HDEBUT[0], ServiceRDV::HDEBUT[1]);
        $endDate = $startDate->add(new DateInterval("P7D"))->setTime(ServiceRDV::HFIN[0], ServiceRDV::HFIN[1]);

        while ($startDate->diff($endDate)->format('%d') > 0) {
            while ($startDate->format('U') % 86400 <= ServiceRDV::HFIN[0] * 3600 + ServiceRDV::HFIN[1] * 60) {

                if (!in_array($startDate->format($this->dateFormat), $listeRDVHorraires)) {

                    $results[] = $startDate;
                }
                $startDate = $startDate->add(new DateInterval("PT30M"));
            }
            $startDate = $startDate->add(new DateInterval('P1D'))->setTime(ServiceRDV::HDEBUT[0], ServiceRDV::HDEBUT[1]);
        }

        return $results;
    }

    public function getListeDisponibiliteDate(string $idPraticien, ?string $test_start_Date, ?string $test_end_Date): array
    {
        //echo "test for getListeDisponibiliteDate";

        $results = [];
        $listeRDV = $this->rdvRepository->getRdvByPraticien($idPraticien);
        $listeRDVHorraires = array_map(function ($rdv) {
            if ($rdv->status != RendezVous::ANNULE) {
                $rr= $rdv->dateHeure->format($this->dateFormat);
                return $rr;
            }
        }, $listeRDV);

        // ! return vide si start date est vide uniquement 
        $startDate = $test_start_Date != null 
            ? (new \DateTimeImmutable($test_start_Date))->setTime(ServiceRDV::HDEBUT[0], ServiceRDV::HDEBUT[1]) 
            : (new \DateTimeImmutable('now'))->setTime(ServiceRDV::HDEBUT[0], ServiceRDV::HDEBUT[1]);

        $endDate = $test_end_Date != null && $test_end_Date != $test_start_Date
            ? (new \DateTimeImmutable($test_end_Date))->setTime(ServiceRDV::HDEBUT[0], ServiceRDV::HDEBUT[1]) 
            : (new \DateTimeImmutable('now'))->setTime(ServiceRDV::HDEBUT[0], ServiceRDV::HDEBUT[1])->add(new DateInterval('P31D'));

        while ($startDate->diff($endDate)->format('%d') > 0) {
            while ($startDate->format('U') % 86400 <= ServiceRDV::HFIN[0] * 3600 + ServiceRDV::HFIN[1] * 60) {

                if (!in_array($startDate->format($this->dateFormat), $listeRDVHorraires)) {

                    $results[] = $startDate;
                }
                $startDate = $startDate->add(new DateInterval("PT30M"));
            }
            $startDate = $startDate->add(new DateInterval('P1D')) ->setTime(ServiceRDV::HDEBUT[0], ServiceRDV::HDEBUT[1]);
        }

        return $results /*!= null ? $results : "Pas de disponibilité pour ce praticien"*/; 
    }

    public function getPlanningPraticien(string $idPraticien, ?string $test_start_Date, ?string $test_end_Date): array
    {
        $results = [];
        $startDate = $test_start_Date != null 
            ? (new \DateTimeImmutable($test_start_Date))->setTime(ServiceRDV::HDEBUT[0], ServiceRDV::HDEBUT[1]) 
            : (new \DateTimeImmutable('now'))->setTime(ServiceRDV::HDEBUT[0], ServiceRDV::HDEBUT[1]);

        $endDate = $test_end_Date != null && $test_end_Date != $test_start_Date
            ? (new \DateTimeImmutable($test_end_Date))->setTime(ServiceRDV::HDEBUT[0], ServiceRDV::HDEBUT[1]) 
            : (new \DateTimeImmutable('now'))->setTime(ServiceRDV::HDEBUT[0], ServiceRDV::HDEBUT[1])->add(new DateInterval('P31D'));
        
        $listeRDV = $this->rdvRepository->getRdvByPraticien($idPraticien);
        foreach($listeRDV as $rdv) {
            if ($rdv->status != RendezVous::ANNULE && $rdv->dateHeure->format('U') > $startDate->format('U') && $rdv->dateHeure->format('U') < $endDate->format('U')) {
                $results[] = $rdv->toDTO($this->servicePraticien->getPraticienById($idPraticien));
            }
        }
        
        return $results; 
    }

    public function getRdvsByPraticien(string $idPraticien): array
    {
        try {
            $listeRDV = $this->rdvRepository->getRdvByPraticien($idPraticien);
        } catch (RepositoryEntityNotFoundException $e) {
            throw new ServiceRessourceNotFoundException("Praticien $idPraticien n'existe pas");
        }
        return array_map(function(RendezVous $rdv) {
            return $rdv->toDTO($this->servicePraticien->getPraticienById($rdv->getPraticienId()));
        }, $listeRDV);
    }

    public function getRdvByPatient(string $id) : array {
        try{
        $listeRDV = $this->rdvRepository->getRdvByPatient($id);
        }catch(RepositoryEntityNotFoundException $e){
            throw new ServiceRessourceNotFoundException("Patient $id n'existe pas");
        }
        return array_map(function(RendezVous $rdv) {
                return $rdv->toDTO($this->servicePraticien->getPraticienById($rdv->getPraticienId()));
            },
        $listeRDV);
    }

    /*string $praticienID*/

    public function annulerRendezVous(string $id ): RdvDTO
    {
        try {
            $rdvAAnnuler= $this->getRdvById($id);
            if($rdvAAnnuler->getStatus() == RendezVous::MAINTENU ||
            $rdvAAnnuler->getStatus() == RendezVous::ANNULE){
                $this->rdvRepository->cancelRdv($id);
                $rdvAAnnuler->setStatus(RendezVous::ANNULE);
                return $rdvAAnnuler;
            }else{
                throw new ServiceOperationInvalideException("Rendez vous $id non annulable");
            }
        } catch (RepositoryEntityNotFoundException $e) {
            throw new ServiceRDVInvalidDataException($e->getMessage());
        }
    }

    /* string $id, string $praticienId, string $patientId, string $specialite, \DateTimeImmutable $dateHeure */
    public function modifRendezVous(InputRdvDto $inputRdv) : RdvDTO {
        
        //ancien rdv
        $rdvOld = $this->rdvRepository->getRdvById($inputRdv->getId());

        //praticien du nouveau rdv
        $praticien = $this->servicePraticien->getPraticienById($inputRdv->getPraticienId());
        
        if ($rdvOld->getStatus() != RendezVous::MAINTENU) {
            echo $rdvOld->getStatus();
            throw new \Exception("Impossible de changer les informations d'un rdv qui n'est pas 'maintenu'");
        }
        
        if ($rdvOld->getDateHeure() != $inputRdv->getDateHeure() || $rdvOld->getSpecialite() != $inputRdv->getSpecialite() ) {
            
            $this->annulerRendezVous($inputRdv->getId()); 
            $res = $this->creerRendezvous($inputRdv);
            $this->rdvRepository->modifierRdv(new RendezVous($inputRdv->getPraticienId(), $inputRdv->getPatientId(), $inputRdv->getSpecialite(), $inputRdv->getDateHeure(),$rdvOld->getStatus()));
            $res->status = $rdvOld->getStatus();
            return $res;
        } else {
            if ($praticien->specialiteLabel != $this->servicePraticien->getSpecialiteById($rdvOld->getSpecialite())->label) {
                throw new \Exception($praticien->specialiteLabel . "=!" . $rdvOld->getSpecialite());
            }
            $this->rdvRepository->modifierRdv(new RendezVous($inputRdv->getPraticienId(), $inputRdv->getPatientId(), $inputRdv->getSpecialite(), $inputRdv->getDateHeure(),$rdvOld->getStatus()));


            //$rdvOld->specialiteLabel = $inputRdv->getSpecialite();
            return $rdvOld->toDTO($praticien);
        }
        
    }

    public function getAllRdvs(): array {
        return array_map(function(RendezVous $rdv) {
            return $rdv->toDTO($this->servicePraticien->getPraticienById($rdv->getPraticienId()));
        }, $this->rdvRepository->getAllRdvs());
    }
}
