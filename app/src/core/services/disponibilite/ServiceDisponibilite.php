<?php

namespace toubeelib\core\services\disponibilite;


use toubeelib\infrastructure\repositories\ArrayRdvRepository;

class ServiceDisponibilite implements ServiceDisponibiliteInterface
{
    private ArrayRdvRepository $arrayRdvRepository;

    public function __construct(ArrayRdvRepository $arrayRdvRepository)
    {
        $this->arrayRdvRepository = $arrayRdvRepository;
    }

    public function getDisponibiliteByPraticienId(string $praticienID): array
    {
        $debut = new \DateTime();

        //$fin = debut + 15 jours
        $fin = new \DateTime();
        $fin->add(new \DateInterval('P15D'));

        $rdvsDates = $this->getNonDisponibiliteFromRdvs($this->arrayRdvRepository->getRendezvousByPraticienId($praticienID, $debut, $fin));


//        var_dump($rdvsDates);
        //$disponibilites = timeline / rdvsDates

        $disponibilites = $this->getDisponibiliteFromRdvsDates($rdvsDates, $debut, $fin);


        return $disponibilites;
    }

    private function getNonDisponibiliteFromRdvs(array $rdvs): array
    {
        //sort by dateDebut
        usort($rdvs, function($a, $b) {
            return $a->getDateTime() <=> $b->getDateTime();
        });

//        var_dump($rdvs);

        //dateDebut + dateFin from rdvs array
        $dates = array_map(function($rdv) {

            $dateDEb = $rdv->getDateTime();
            $dateFin = clone $rdv->getDateTime();
            $dateFin->add(new \DateInterval('PT'.$rdv->__get('duree').'S'));
            return [$dateDEb, $dateFin];
        }, $rdvs);

        return $dates;
    }

    private function getDisponibiliteFromRdvsDates(array $rdvsDates, \DateTime $debut, \DateTime $fin): array
    {

        if(empty($rdvsDates)){
            $disponibilites[] = [$debut, $fin];
//            echo "EMPTY";
            return $disponibilites;
        }
//=================================================================

        //si le premier rdv commence après le début de la période
        if ($debut < $rdvsDates[0][0]) {
            $disponibilites[] = [$debut, $rdvsDates[0][0]];
            echo "DEBUT";
        }

        for ($i = 0; $i < count($rdvsDates) - 1; $i++) {
            $currentRdvEnd = $rdvsDates[$i][1];
            $nextRdvStart = $rdvsDates[$i + 1][0];

            // si la fin du rdv actuel est avant le début du prochain rdv
            if ($currentRdvEnd < $nextRdvStart) {
                $disponibilites[] = [$currentRdvEnd, $nextRdvStart];
            }
        }

        //si le dernier rdv finit avant la fin de la période
        $lastRdvEnd = $rdvsDates[count($rdvsDates)-1][1];
        if ($lastRdvEnd < $fin) {
            $disponibilites[] = [$lastRdvEnd, $fin];
        }

        return $disponibilites;
    }
}