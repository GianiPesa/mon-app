<?php
/**
 * Created by PhpStorm.
 * User: christophe
 * Date: 18/10/2018
 * Time: 14:55
 */

class PdoBridge
{
    private static $serveur = 'mysql:host=localhost';
    private static $bdd = 'dbname=annuaire';
    private static $user = 'root';
    private static $mdp = 'root';
    private static $monPdoBridge = null;
    /**
     * @var PDO   <--- need by PhpStorm to find Methods of PDO
     */
    private static $monPdo;

    /**
     * Constructeur privé, crée l'instance de PDO qui sera sollicitée
     * pour toutes les méthodes de la classe
     */
    private function __construct()
    {
        PdoBridge::$monPdo = new PDO(PdoBridge::$serveur . ';' . PdoBridge::$bdd, PdoBridge::$user, PdoBridge::$mdp);
        PdoBridge::$monPdo->query("SET CHARACTER SET utf8");
    }

    public function _destruct()
    {
        PdoBridge::$monPdo = null;
    }

    /**
     * Fonction statique qui crée l'unique instance de la classe
     *
     * Appel : $instancePdolafleur = PdoBridge::getPdoBridge();
     * @return l'unique objet de la classe PdoBridge
     */
    public static function getPdoBridge()
    {
        if (PdoBridge::$monPdoBridge == null) {
            PdoBridge::$monPdoBridge = new PdoBridge();
        }
        return PdoBridge::$monPdoBridge;
    }
    public function getLesMembres()
    {
        $sql = 'select id,nom,prenom
        from membres';
        $req = PdoBridge::$monPdo->prepare($sql);
        $req->execute();
        $d = $req->fetchALL(PDO::FETCH_ASSOC);
        return $d;
    }

    public function getMaxId()
    {
        // modifiez la requête sql
        $req = "SELECT MAX(id) AS maxi FROM membres";
        $res = PdoBridge::$monPdo->query($req);
        $lesLignes = $res->fetch();
        return 1 + intval($lesLignes["maxi"]);
    }

    public function setLesMembres($nom, $prenom)
    {
        try{
            $id= $this->getMaxId();
           // On ecrit la requetes
         $sql = "INSERT INTO `membres`
         VALUES (:idMembre,:nom, :prenom)";
         // On prepare la requete
         $query = PdoBridge::$monPdo->prepare($sql);

         // On injecte les valeurs
         $query->bindValue(':idMembre', $id, PDO::PARAM_INT);
         $query->bindValue(':nom', $nom, PDO::PARAM_STR);
         $query->bindValue(':prenom', $prenom, PDO::PARAM_STR);
         
         return true;
        } catch(Exception $e){
            echo 'exeption recu'.$e->getMessage(). "/n";
        }   
        }

    public function deleteMembre($id)
    {
        try{
        // On ecrit la requetes
        $sql = "DELETE FROM `membres` WHERE id=:idMembre";
        // On prepare la requete
        $query = PdoBridge::$monPdo->prepare($sql);

        // On injecte les valeurs
        $query->bindValue(':idMembre', $id, PDO::PARAM_INT);
        
        $query->execute();

        return true;
        } catch(Exception $e){
            echo 'exeption recu'.$e->getMessage(). "/n";
        }  
    } 

    public function getInfoMembres()
    {
        // Pour la liste deroulante de supprimer un membre
        $req = "SELECT * FROM membres";
        $rs = PdoBridge::$monPdo->query($req);
        $ligne = $rs->fetchAll();
        return $ligne;
    }
}