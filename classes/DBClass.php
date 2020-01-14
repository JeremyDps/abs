<?php


class DBClass
{
    private $db_name;
    private $db_user;
    private $db_pass;
    private $db_host;
    private $pdo;

    public function __construct($db_name, $db_user = 'root', $db_pass = '', $db_host = 'localhost')
    {
        $this->db_name = $db_name;
        $this->db_user = $db_user;
        $this->db_pass = $db_pass;
        $this->db_host = $db_host;
    }

    public function getPDO() {
        if($this->pdo === null) {
            try {
                $pdo = new PDO('mysql:host='.$this->db_host.';dbname='.$this->db_name.';charset=utf8', 'root', '');
                $this->pdo = $pdo;
            } catch (Exception $e) {
                die('Erreur : ' . $e->getMessage());
            }
        }
        return $this->pdo;
    }

    public function connection($username, $mdp){
        session_start();
        $req = $this->getPDO()->prepare("select * from user where username = ? and mdp = ?");
        $req->execute(array($username, $mdp));
        $isConnecte = $req->fetch();

        if($isConnecte['username'] === $username && $isConnecte['mdp'] === $mdp) {

            $prenom = $this->getPDO()->prepare("select prenom from user where username = ?");
            $prenom->execute(array($username));
            $isPrenom = $prenom->fetch();

            $_SESSION['connecte'] = true;
            $_SESSION['prenom'] = $isPrenom['prenom'];
            $_SESSION['username'] = $username;

            if($isConnecte['role'] == "prof"){
                $req->closeCursor();
                return 1;
            }

            if($isConnecte['role'] == "admin") {
                $req->closeCursor();
                return 2;
            }

        }else{
            $req->closeCursor();
            $_SESSION['connecte'] = false;
            return false;
        }
    }

    public function selectClasseByProf($username) {
        $tab_classe = array();
        $i = 0;
        $req = $this->getPDO()->prepare("select classe.nom from classe 
                                                            inner join prof_classe on classe.idClasse = prof_classe.idClasse 
                                                            inner join professeur on prof_classe.idProf = professeur.idProf
                                                            inner join user on user.id = professeur.idUser
                                                            where user.username = ?");
        $req->execute(array($username));

        while($donnees = $req->fetch()) {
            $tab_classe[$i] = $donnees['nom'];
            $i++;
        }

        $req->closeCursor();

        return $tab_classe;
    }

    public function selectCoursByProf($username) {
        $tab_cours = array();
        $i = 0;

        $req = $this->getPDO()->prepare("select cours.nom from cours 
                                                    inner join prof_cours on cours.idCours = prof_cours.idCours 
                                                    inner join professeur on prof_cours.idProf = professeur.idProf
                                                    inner join user on user.id = professeur.idUser
                                                    where user.username = ?");

        $req->execute(array($username));

        while($donnees = $req->fetch()) {
            $tab_cours[$i] = $donnees['nom'];
            $i++;
        }

        $req->closeCursor();

        return $tab_cours;
    }

    public function selectAllEtu() {
        $tab_etudiant = array();
        $i = 0;

        $req = $this->getPDO()->query("select * from etudiant");

        $req->execute();

        while($donnees = $req->fetch()) {
            $tab_etudiant[$i] = array(
                'idEtu' => $donnees['idEtu'],
                'prenom' => $donnees['prenom'],
                'nom' => $donnees['nom'],
                'formation' => $donnees['formation'],
                'nbr_absence' => $donnees['nbr_absence'],
            );

            $i++;
        }
        $req->closeCursor();
        return $tab_etudiant;
    }

    public function updateEtudiantByUser($abs, $absNonJustifiee, $badge) {
        session_start();
        $req = $this->getPDO()->prepare("update etudiant 
                                                  set nbr_absence = :abs, absence_justifiee = :absNonJustifiee, badge_id = :badge
                                                  where idEtu = :id");
        $req->execute(array(
            'abs' => $abs,
            'absNonJustifiee' => $absNonJustifiee,
            'badge' => $badge,
            'id' => $_SESSION['idEtu']
        ));
        $req->closeCursor();
    }
}