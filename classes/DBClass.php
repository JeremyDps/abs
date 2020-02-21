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

        echo $username . ' ' .$mdp;
        $req = $this->getPDO()->prepare("select * from user where username = ? and mdp = ?");
        $req->execute(array($username, $mdp));
        $isConnecte = $req->fetch();
        echo 'role'  . $isConnecte['role'];

        if($isConnecte['username'] === $username && $isConnecte['mdp'] === $mdp) {

            $prenom = $this->getPDO()->prepare("select prenom from user where username = ?");
            $prenom->execute(array($username));
            $isPrenom = $prenom->fetch();

            $_SESSION['connecte'] = true;
            $_SESSION['prenom'] = $isPrenom['prenom'];
            $_SESSION['username'] = $username;

            if($isConnecte['role'] == "prof"){
                $_SESSION['role'] = "prof";
                $req->closeCursor();
                return 1;
            }

            if($isConnecte['role'] == "admin") {
                $_SESSION['role'] = "admin";
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

        $req = $this->getPDO()->prepare("select distinct cours.nom from cours 
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
        echo 'bnjour' . $_SESSION['idEtu'];
        $req->closeCursor();
    }

    public function updateProfesseurByUser($username, $password, $role) {
        session_start();
        $req = $this->getPDO()->prepare("update user 
                                                  set username = :username, mdp = :password, role = :role
                                                  where id = :id");
        $req->execute(array(
            'username' => $username,
            'password' => $password,
            'role' => strtolower($role),
            'id' => $_SESSION['idProf']
        ));
        $req->closeCursor();
    }

    public function searchStudent($recherche) {
        $coordonneesEtu = array();
        $i = 0;

        if($req = $this->getPDO()->prepare("select * from etudiant where idEtu=:recherche or nom=:recherche or prenom=:recherche or formation=:recherche")) {
            $req->execute(array(
                'recherche' => $recherche
            ));

            while($donnees = $req->fetch()) {
                $coordonneesEtu[$i] = array(
                    'idEtu' => $donnees['idEtu'],
                    'nom' => $donnees['nom'],
                    'prenom' => $donnees['prenom'],
                    'nbr_absence' => $donnees['nbr_absence'],
                    'formation' => $donnees['formation']
                );
                $i++;
            }

            $req->closeCursor();
            return $coordonneesEtu;
        }
        $req->closeCursor();
        return 0;
    }

    public function selectEtuByClasse($classe) {
        $list_etu = array();
        $i = 0;

        if($req = $this->getPDO()->prepare('select idEtu, prenom, nom from etudiant where formation = ? order by nom asc')) {
            $req->execute(array($classe));

            while($donnees = $req->fetch()) {
                $list_etu[$i] = array(
                    'idEtu' => $donnees['idEtu'],
                    'prenom' => $donnees['prenom'],
                    'nom' => $donnees['nom']
                );
                $i++;
            }
            $req->closeCursor();
            return $list_etu;
        } else {
            $req->closeCursor();
            return 0;
        }
    }

    public function selectGroupByClasse($classe) {
        $list_group = array();
        $i = 0;

        if($req = $this->getPDO()->prepare("select groupe.nom from groupe inner join classe on classe.idClasse = groupe.classe_id where classe.nom = ? order by groupe.nom asc")) {
            $req->execute(array($classe));
            while($donnees = $req->fetch()) {
                $list_group[$i] = array(
                    'groupe' => $donnees['nom']
                );
                $i++;
            }
            $req->closeCursor();
            return $list_group;
        } else {
            $req->closeCursor();
            return 0;
        }
    }

    public function selectAllProf() {
        $list_prof = array();
        $i = 0;

        if($req = $this->getPDO()->query('select * from user where role = "admin" or role = "prof" order by nomUser')) {
            while($datas = $req->fetch()) {
                $list_prof[$i] = array(
                    'id' => $datas['id'],
                    'nom' => $datas['nomUser'],
                    'prenom' => $datas['prenom'],
                    'username' => $datas['username'],
                    'role' => $datas['role']
                );

                $i++;
            }

            $req->closeCursor();
            return $list_prof;
        } else {
            echo 'Erreur dans le chargement du tableau';
            return 0;
        }
    }

    public function selectDetailsEtudiant($id) {
        $list_details = array();

        $query = $this->getPDO()->prepare('SELECT * FROM etudiant where idEtu = ?');
        $query->execute(array($id));

        $datas = $query->fetch();

        $list_details['id'] = $datas['idEtu'];
        $list_details['nom'] = $datas['nom'];
        $list_details['prenom'] = $datas['prenom'];
        $list_details['formation'] = $datas['formation'];
        $list_details['nbr_absence'] = $datas['nbr_absence'];
        $list_details['absence_justifiee'] = $datas['absence_justifiee'];
        $list_details['badge'] = $datas['badge_id'];

        $query->closeCursor();

        return $list_details;
    }

    public function selectDetailsProfesseur($id) {
        $list_details = array();

        $query = $this->getPDO()->prepare('SELECT * FROM user where id = ? and (role = "admin" or role = "prof")');
        $query->execute(array($id));

        $datas = $query->fetch();

        $list_details['id'] = $datas['id'];
        $list_details['username'] = $datas['username'];
        $list_details['password'] = $datas['mdp'];
        $list_details['nom'] = $datas['nomUser'];
        $list_details['prenom'] = $datas['prenom'];
        $list_details['role'] = $datas['role'];

        $query->closeCursor();

        return $list_details;
    }

    public function selectAllClasses() {
        $list_classes = array();
        $i = 0;

        if($req = $this->getPDO()->query('select * from classe')) {
            while($datas = $req->fetch()) {
                $list_classes[$i] = array(
                    'id' => $datas['idClasse'],
                    'nom' => $datas['nom']
                );

                $i++;
            }

            $req->closeCursor();
            return $list_classes;
        } else {
            echo 'Erreur dans le chargement du tableau';
            return 0;
        }
    }

    public function insertEtudiant($nom, $prenom, $formation, $badge) {

        $req = $this->getPDO()->prepare("select idClasse from classe where nom = :nom");
        $req->execute(array(
            'nom' => $formation
        ));
        $datas = $req->fetch();

        $query = $this->getPDO()->prepare("insert into etudiant(idEtu, prenom, nom, formation, nbr_absence, absence_justifiee, classe_id, badge_id, etat) values 
                                                    (default, :prenom, :nom, :formation, 0, 0, :classe, :badge, 'present')");

        echo $prenom . ' ' . $nom . ' ' . $formation . ' ' . $datas['idClasse'] . ' ' . $badge;

        $query->execute(array(
            'prenom' => $prenom,
            'nom' => $nom,
            'formation' => $formation,
            'classe' => $datas['idClasse'],
            'badge' => $badge
        ));

        $req->closeCursor();
        $query->closeCursor();
    }

    public function deleteEtu($id) {
        $query = $this->getPDO()->prepare("delete from etudiant where idEtu = :id");
        $query->execute(array(
            'id' => $id
        ));
    }

    public function insertProfesseur($nom, $prenom, $password, $role) {
        $query = $this->getPDO()->prepare("insert into user(id, username, nomUser, prenom, mdp, role) values 
                                                    (default, :username, :nom, :prenom, :password, :role)");

        $userName = strtolower($nom) . '.' . strtolower($prenom);

        echo $userName;

        $query->execute(array(
            'username' => $userName,
            'nom' => $nom,
            'prenom' => $prenom,
            'password' => $password,
            'role' => strtolower($role)
        ));

        $query->closeCursor();
    }

    public function deleteProfesseur($id) {
        $query = $this->getPDO()->prepare("delete from user where id = :id");
        $query->execute(array(
            'id' => $id
        ));
    }
}