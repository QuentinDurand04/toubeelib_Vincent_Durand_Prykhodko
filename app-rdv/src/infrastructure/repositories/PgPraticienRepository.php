<?php
namespace rdv\infrastructure\repositories;

use DI\Container;
use GuzzleHttp\Client;
use Monolog\Logger;
use rdv\core\dto\PraticienDTO;
use rdv\core\repositoryInterfaces\RepositoryInternalException;
use rdv\core\domain\entities\praticien\Praticien;
use rdv\core\domain\entities\praticien\Specialite;
use rdv\core\repositoryInterfaces\PraticienRepositoryInterface;
use PDO;
use rdv\core\repositoryInterfaces\RepositoryEntityNotFoundException;
use SebastianBergmann\Environment\Console;

class PgPraticienRepository implements PraticienRepositoryInterface{

    protected PDO $pdo;
    protected PDO $pdoSpe;
    protected Logger $loger;
    protected Client $guzzle;

    public function __construct(Container $cont){
        $this->pdo=$cont->get('pdo.commun');
        $this->pdoSpe=$cont->get('pdo.specialite');
        $this->loger= $cont->get(Logger::class)->withName("PgPraticienRepository");
        $this->guzzle = $cont->get('guzzle.client');
    }
    public function getSpecialiteById(string $id): Specialite
    {
        try{
            $query = 'select * from specialite where id = :id;';
            $speSelect=$this->pdoSpe->prepare($query);
            $speSelect->execute(['id'=> $id]);
            $result=$speSelect->fetch();

            if($result){
                return new Specialite($result['id'], $result['label'], $result['description']);
            }else{
                throw new RepositoryEntityNotFoundException("Specialite $id non trouvé");
            }

        }catch(\PDOException $e){
            
            throw new RepositoryInternalException("erreur");
        }
    }

    public function getSpecialiteByLabel(String $label): Specialite{
        try{
            $query = 'select * from specialite where label = :label;';
            $speSelect=$this->pdoSpe->prepare($query);
            $speSelect->execute(['label'=> $label]);
            $result=$speSelect->fetch();

            if($result){
                return new Specialite($result['id'], $result['label'], $result['description']);
            }else{
                throw new RepositoryEntityNotFoundException("Specialite $label non trouvé");
            }

        }catch(\PDOException $e){
            
            throw new RepositoryInternalException($e->getMessage());
        }
    }

    public function save(Praticien $praticien): string
    {
    }

    public function getPraticienById(string $id): Praticien
    {
        try{
            $reponse = $this->guzzle->get("/praticiens/".$id);
            $result = json_decode($reponse->getBody()->getContents(),true);
            if($result){
                $retour= new Praticien($result['nom'],$result['prenom'],$result['adresse'],$result['tel']);
                $retour->setId($result['id']);
                $spe = $this->getSpecialiteByLabel($result['specialiteLabel']);
                $retour->setSpecialite($spe);
                return $retour;
            }else{
                throw new RepositoryEntityNotFoundException("Praticien $id non trouvé");
            }

        }catch(\PDOException $e){
            throw new RepositoryInternalException('Erreure bd');
        }
    }

    public function searchPraticiens(Praticien $praticien): array
    {
        $query = "
        select 
        praticien.id as id,
        praticien.nom as nom,
        praticien.prenom as prenom,
        praticien.adresse as adresse,
        praticien.tel as tel,
        specialite.id as speid,
        specialite.label as spelabel,
        specialite.description as spedes 
        from 
        praticien,
        specialite
        where 
        praticien.specialite = specialite.id and
        upper(praticien.nom) like upper(:nom) and
        upper(praticien.prenom) like upper(:prenom) and
        upper(praticien.adresse) like upper(:adresse) and
        upper(specialite.label) like upper(:label)
        ;";
        try{
            $searchpraticiens = $this->pdo->prepare($query);
            $val = [
                'nom' => $praticien->nom,
                'prenom' => $praticien->prenom,
                'adresse' => $praticien->adresse,
                'label' => $praticien->specialite->label,
            ];
            $val = array_map(function (string $v){
                return "%$v%";
            },$val);
            $searchpraticiens->execute($val);
            $praticiens = $searchpraticiens->fetchAll();
            if(!$praticiens){
            $this->loger->info("Nombre de praticien trouvé : 0");
                return [];
            }
            $retour = [];
            foreach($praticiens as $p){
                $pra = new Praticien($p['nom'],$p['prenom'],$p['adresse'],$p['tel']);
                $pra->setId($p['id']);
                $pra->setSpecialite(new Specialite($p['speid'],$p['spelabel'],$p['spedes']));
                $retour[]= $pra;
            }
            $this->loger->info("Nombre de praticien trouvé : ". count($retour));
            return $retour;

        }catch(\PDOException $e){
            $this->loger->error($e->getMessage());
            throw new RepositoryInternalException('erreur bd');
        }
    }

    public function getAllPraticiens(): array
    {
        $query = "
        select 
        praticien.id as id,
        praticien.nom as nom,
        praticien.prenom as prenom,
        praticien.adresse as adresse,
        praticien.tel as tel,
        specialite.id as speid,
        specialite.label as spelabel,
        specialite.description as spedes 
        from 
        praticien,
        specialite
        where 
        praticien.specialite = specialite.id
        ;";
        try{
            $allpraticiens = $this->pdo->query($query);
            $praticiens = $allpraticiens->fetchAll();
            if(!$praticiens){
                $this->loger->info("Nombre de praticien trouvé : 0");
                return [];
            }
            $retour = [];
            foreach($praticiens as $p){
                $pra = new Praticien($p['nom'],$p['prenom'],$p['adresse'],$p['tel']);
                $pra->setId($p['id']);
                $pra->setSpecialite(new Specialite($p['speid'],$p['spelabel'],$p['spedes']));
                $retour[]= $pra;
            }
            $this->loger->info("Nombre de praticien trouvé : ". count($retour));
            return $retour;

        }catch(\PDOException $e){
            $this->loger->error($e->getMessage());
            throw new RepositoryInternalException('erreur bd');
        }
    }
}
