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

        $req = $this->getPDO()->prepare("select distinct cours.matricule from cours 
                                                    inner join prof_cours on cours.idCours = prof_cours.idCours 
                                                    inner join professeur on prof_cours.idProf = professeur.idProf
                                                    inner join user on user.id = professeur.idUser
                                                    where user.username = ?");

        $req->execute(array($username));

        while($donnees = $req->fetch()) {
            $tab_cours[$i] = $donnees['matricule'];
            $i++;
        }

        $req->closeCursor();

        return $tab_cours;
    }

    public function selectAllEtu() {
        $tab_etudiant = array();
        $i = 0;

        $req = $this->getPDO()->query("select etudiant.idEtu, etudiant.prenom, etudiant.nom, etudiant.formation, etudiant.nbr_absence, groupe.nom as nomGroup, groupe_tp.nom as tp
                                                from etudiant inner join groupe on groupe.idGroupe = etudiant.groupe_id
                                                inner join groupe_tp on groupe_tp.idGroupeTP = etudiant.groupe_tp_id
                                                order by etudiant.formation asc, etudiant.nom asc");

        $req->execute();


        while($donnees = $req->fetch()) {
            $tab_etudiant[$i] = array(
                'idEtu' => $donnees['idEtu'],
                'prenom' => $donnees['prenom'],
                'nom' => $donnees['nom'],
                'formation' => $donnees['formation'],
                'groupe' => $donnees['nomGroup'],
                'tp' => $donnees['tp'],
                'nbr_absence' => $donnees['nbr_absence'],
            );

            $i++;
        }
        $req->closeCursor();
        return $tab_etudiant;
    }

    public function updateEtudiantByUser($badge, $groupe, $groupe_tp) {
        session_start();

        echo $groupe . ' ' . $groupe_tp . 'id etu : ' . $_SESSION['idEtu'];

        $idGroupe = $this->getPDO()->prepare("select idGroupe from groupe where nom = :nom");
        $idGroupe->execute(array('nom' => $groupe));
        $datas = $idGroupe->fetch();

        echo $datas['idGroupe'];

        $idGroupeTP = $this->getPDO()->prepare("select idGroupeTP from groupe_tp where nom = :nom");
        $idGroupeTP->execute(array('nom' => $groupe_tp));
        $datas_tp = $idGroupeTP->fetch();

        echo '$datas' . '  ' . $datas_tp['idGroupeTP'];

        $req = $this->getPDO()->prepare("update etudiant 
                                                  set badge_id = :badge, groupe_id = :groupe_id, groupe_tp_id = :groupe_tp  
                                                  where idEtu = :id");
        $req->execute(array(
            'badge' => $badge,
            'groupe_id' => $datas['idGroupe'],
            'groupe_tp' => $datas_tp['idGroupeTP'],
            'id' => $_SESSION['idEtu'],
        ));
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
        $valeurGroupe = '';

        $groupe = $this->getPDO()->query("select count(*) from groupe");
        $groupe->execute();
        $nbr_groupes = $groupe->fetch();

        if($req = $this->getPDO()->prepare("select * from etudiant where idEtu=:recherche or nom=:recherche or prenom=:recherche or formation=:recherche")) {
            $req->execute(array(
                'recherche' => $recherche
            ));

            while($donnees = $req->fetch()) {
                if($donnees['groupe_id'] < 1 || $donnees['groupe_id'] > $nbr_groupes['count(*)']) {
                    $valeurGroupe = 'Aucun groupe';
                }
                $coordonneesEtu[$i] = array(
                    'idEtu' => $donnees['idEtu'],
                    'nom' => $donnees['nom'],
                    'prenom' => $donnees['prenom'],
                    'nbr_absence' => $donnees['nbr_absence'],
                    'formation' => $donnees['formation'],
                    'groupe' => $valeurGroupe
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
        if (!is_numeric($classe)) {

            if($req = $this->getPDO()->prepare('select idEtu, prenom, etudiant.nom from etudiant 
                                                     inner join groupe on groupe.idGroupe = etudiant.groupe_id
                                                     where groupe.nom = ? order by nom asc')) {
                $req->execute(array($classe));
            }
        } else  {
            if ($req = $this->getPDO()->prepare('select idEtu, prenom, nom from etudiant 
                                                         where classe_id = ? order by nom asc')) {
                $req->execute(array($classe));

            }
        }
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

    }

    public function selectEtuByClasseTP($classe) {
        $list_etu = array();
        $i = 0;

        $req = $this->getPDO()->prepare("select etudiant.idEtu, etudiant.nom, etudiant.prenom from etudiant
                                                  inner join groupe_tp on etudiant.groupe_tp_id = groupe_tp.idGroupeTP 
                                                  where groupe_tp.nom = :classe");
        $req->execute(array('classe' => $classe));

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
    }

    public function selectGroupByClasse($classe) {
        $list_group = array();
        $i = 0;

        if($req = $this->getPDO()->prepare("select groupe.nom from groupe 
                                                     inner join classe on classe.idClasse = groupe.classe_id 
                                                     where classe.nom = ? order by groupe.nom asc")) {
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

        $query = $this->getPDO()->prepare('SELECT etudiant.idEtu, etudiant.nom, etudiant.prenom, etudiant.formation, etudiant.nbr_absence,
                                                    etudiant.absence_justifiee, etudiant.badge_id, groupe.nom as nomGroupe, groupe_tp.nom as nomTP, classe.nom as nomClasse
                                                    FROM etudiant inner join groupe on groupe.idGroupe = etudiant.groupe_id
                                                    inner join classe on classe.idClasse = etudiant.classe_id 
                                                    inner join groupe_tp on groupe_tp.classe_td_id = groupe.idgroupe where idEtu = ?');
        $query->execute(array($id));

        $datas = $query->fetch();

        $list_details['id'] = $datas['idEtu'];
        $list_details['nom'] = $datas['nom'];
        $list_details['prenom'] = $datas['prenom'];
        $list_details['formation'] = $datas['formation'];
        $list_details['nbr_absence'] = $datas['nbr_absence'];
        $list_details['absence_justifiee'] = $datas['absence_justifiee'];
        $list_details['groupe'] = $datas['nomGroupe'];
        $list_details['classe'] = $datas['nomClasse'];
        $list_details['tp'] = $datas['nomTP'];
        $list_details['badge'] = $datas['badge_id'];

        $query->closeCursor();

        return $list_details;
    }

    function selectDetailsEtudiantWithoutGroupe($id) {
        $list_details = array();

        $query = $this->getPDO()->prepare('SELECT etudiant.idEtu, etudiant.nom, etudiant.prenom, etudiant.formation, etudiant.nbr_absence,
                                                    etudiant.absence_justifiee, etudiant.badge_id, classe.nom as nomClasse
                                                    FROM etudiant inner join classe on classe.idClasse = etudiant.classe_id where idEtu = ?');
        $query->execute(array($id));

        $datas = $query->fetch();

        $list_details['id'] = $datas['idEtu'];
        $list_details['nom'] = $datas['nom'];
        $list_details['prenom'] = $datas['prenom'];
        $list_details['formation'] = $datas['formation'];
        $list_details['nbr_absence'] = $datas['nbr_absence'];
        $list_details['absence_justifiee'] = $datas['absence_justifiee'];
        $list_details['classe'] = $datas['nomClasse'];
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

    public function insertEtudiant($nom, $prenom, $formation, $groupe, $groupe_tp, $badge) {

        //récupération de l'id de la classe;
        $req = $this->getPDO()->prepare("select idClasse from classe where nom = :nom");
        $req->execute(array(
            'nom' => $formation
        ));
        $datas = $req->fetch();

        echo $datas['idClasse'].' ';


        //récupération de l'id du groupe
        $id_groupe = $this->getPDO()->prepare("select idGroupe from groupe where classe_id = :id and nom = :nom");
        $id_groupe->execute(array(
            'id' => $datas['idClasse'],
            'nom' => $groupe
        ));
        $datas_groupe = $id_groupe->fetch();

        echo $datas_groupe['idGroupe'];

        $id_groupe_tp = $this->getPDO()->prepare("select idGroupeTP from groupe_tp where nom = :nom");
        $id_groupe_tp->execute(array(
            'nom' => $groupe_tp,
        ));
        $datas_groupe_tp = $id_groupe_tp->fetch();

        echo $datas_groupe_tp['idGroupeTP'];

        $query = $this->getPDO()->prepare("insert into etudiant(idEtu, prenom, nom, formation, nbr_absence, absence_justifiee, classe_id, groupe_id, groupe_tp_id, badge_id) values
                                                    (default, :prenom, :nom, :formation, 0, 0, :classe, :groupe, :groupe_tp, :badge)");

        //echo $prenom . ' ' . $nom . ' ' . $formation . ' ' . $datas['idClasse'] . ' ' . $datas_groupe['idGroupe'] . ' ' . $badge;

        $query->execute(array(
            'prenom' => $prenom,
            'nom' => $nom,
            'formation' => $formation,
            'classe' => $datas['idClasse'],
            'groupe' => $datas_groupe['idGroupe'],
            'groupe_tp' => $datas_groupe_tp['idGroupeTP'],
            'badge' => $badge
        ));

        $req->closeCursor();
        $id_groupe->closeCursor();
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

        $idProf = $this->getPDO()->prepare("select id from user where username = :username and mdp = :mdp");

        $insert = $this->getPDO()->prepare("insert into professeur (idProf, idUser) values (default, :id)");

        $userName = strtolower($nom) . '.' . strtolower($prenom);

        $query->execute(array(
            'username' => $userName,
            'nom' => $nom,
            'prenom' => $prenom,
            'password' => $password,
            'role' => strtolower($role)
        ));

        $idProf->execute(array(
            'username' => $userName,
            'mdp' => $password
        ));

        $datas = $idProf->fetch();

        $insert->execute(array('id' => $datas['id']));

        $query->closeCursor();
        $insert->closeCursor();
    }

    public function deleteProfesseur($id) {
        $query = $this->getPDO()->prepare("delete from user where id = :id");
        $query->execute(array(
            'id' => $id
        ));
    }

    public function selectAllGroupes() {
        $list_groupes = array();
        $i = 0;

        if($req = $this->getPDO()->query('select nom from groupe')) {
            while($datas = $req->fetch()) {
                $list_groupes[$i] = array(
                    'nom' => $datas['nom']
                );

                $i++;
            }

            $req->closeCursor();
            return $list_groupes;
        } else {
            echo 'Erreur dans le chargement du tableau';
            return 0;
        }
    }

    public function selectAllGroupByClasse() {
        $list_groupes = array();
        $i = 0;

        if($req = $this->getPDO()->query('select groupe.idGroupe, groupe.nom as nomGroupe, classe.nom as nomClasse from groupe inner join classe on classe.idClasse = groupe.classe_id')) {
            while($datas = $req->fetch()) {
                $list_groupes[$i] = array(
                    'id' => $datas['idGroupe'],
                    'nomGroupe' => $datas['nomGroupe'],
                    'nomClasse' => $datas['nomClasse']
                );

                $i++;
            }

            $req->closeCursor();
            return $list_groupes;
        } else {
            echo 'Erreur dans le chargement du tableau';
            return 0;
        }
    }

    public function deleteGroupe($id) {
        $query = $this->getPDO()->prepare("delete from groupe where idGroupe = :id");
        $query->execute(array(
            'id' => $id
        ));

        $update = $this->getPDO()->prepare("update etudiant set groupe_id = 0 where groupe_id = :id");
        $update->execute(array(
            'id' => $id
        ));
    }

    public function selectGroupById($id) {
        $donnees_groupe = array();

        $query = $this->getPDO()->prepare("select groupe.idGroupe, groupe.nom, classe.nom as nomClasse from groupe 
                                                    inner join classe on classe.idClasse = groupe.classe_id where groupe.idGroupe = :id");
        $query->execute(array('id' => $id));

        $datas = $query->fetch();

        $donnees_groupe['id'] = $datas['idGroupe'];
        $donnees_groupe['nom'] = $datas['nom'];
        $donnees_groupe['classe'] = $datas['nomClasse'];

        return $donnees_groupe;
    }

    public function selectEtuByGroup($id) {
        $list_etu = array();
        $i = 0;

        $query = $this->getPDO()->prepare("select idEtu, nom, prenom from etudiant where groupe_id = :id");
        $query->execute(array('id' => $id));

        while($donnees = $query->fetch()) {
            $list_etu[$i] = array(
                'id' => $donnees['idEtu'],
                'nom' => $donnees['nom'],
                'prenom' => $donnees['prenom']
            );

            $i++;
        }

        return $list_etu;
    }

    public function updateGroupeEtu($etudiant_id, $groupe_id) {
        $query = $this->getPDO()->prepare("update etudiant set groupe_id = :groupe_id where etudiant.idEtu = :etudiant_id");
        $query->execute(array(
            'groupe_id' => $groupe_id,
            'etudiant_id' => $etudiant_id
        ));
    }

    public function insertGroupe($nom, $classe) {

        $req = $this->getPDO()->prepare("select idClasse from classe where nom = :nom");
        $req->execute(array(
            'nom' => $classe
        ));
        $datas = $req->fetch();

        $query = $this->getPDO()->prepare("insert into groupe(idGroupe, nom, classe_id) values 
                                                    (default, :nom, :classe_id)");
        $query->execute(array(
            'nom' => $nom,
            'classe_id' => $datas['idClasse']
        ));

        $req->closeCursor();
        $query->closeCursor();
    }

    public function selectAllCours() {
        $list_cours = array();
        $i = 0;

        $query = $this->getPDO()->query("select cours.idCours, cours.matricule, cours.nom, classe.nom as nomClasse from cours
                                                    inner join classe on classe.idClasse = cours.classe_id order by classe.nom");

        while($datas = $query->fetch()) {
            $list_cours[$i] = array(
                'id' => $datas['idCours'],
                'matricule' => $datas['matricule'],
                'nom' => $datas['nom'],
                'classe' => $datas['nomClasse']
            );

            $i++;
        }

        $query->closeCursor();
        return $list_cours;
    }

    public function deleteCours($id) {

        $query = $this->getPDO()->prepare("delete from cours where idCours = :id");
        $query->execute(array(
            'id' => $id
        ));
    }

    public function selectDetailsCours($id) {
        $list_details = array();

        $query = $this->getPDO()->prepare('SELECT cours.idCours, cours.matricule, cours.nom, classe.nom as nomClasse FROM cours
                                                    inner join classe on classe.idClasse = cours.classe_id where idCours = ?');
        $query->execute(array($id));

        $datas = $query->fetch();

        $list_details['id'] = $datas['idCours'];
        $list_details['matricule'] = $datas['matricule'];
        $list_details['nom'] = $datas['nom'];
        $list_details['classe'] = $datas['nomClasse'];

        $query->closeCursor();

        return $list_details;
    }

    public function updateCours($matricule, $nom, $classe) {
        session_start();
        $idClasse = $this->getPDO()->prepare("select idClasse from classe where nom = :nom");
        $idClasse->execute(array('nom' => $classe));

        $datas = $idClasse->fetch();

        $query = $this->getPDO()->prepare("update cours set matricule= :matricule, nom= :nom, classe_id= :classe_id where idCours = :id");

        echo $matricule . ' ' . $nom . ' ' . $classe . ' ' .$datas['idClasse'];
        $query->execute(array(
            'id' => $_SESSION['idCours'],
            'matricule' => $matricule,
            'nom' => $nom,
            'classe_id' => $datas['idClasse']
        ));
    }

    function insertCours($matricule, $nom, $classe) {
        $idClasse = $this->getPDO()->prepare("select idClasse from classe where nom = :nom");
        $idClasse->execute(array(
            'nom' => $classe
        ));
        $datas = $idClasse->fetch();

        $query = $this->getPDO()->prepare("insert into cours(idCours, matricule, nom, classe_id) values 
                                                    (default, :matricule, :nom, :classe_id)");

        $query->execute(array(
            'matricule' => $matricule,
            'nom' => $nom,
            'classe_id' => $datas['idClasse']
        ));

        $idClasse->closeCursor();
        $query->closeCursor();
    }

    function selectEtuById($id) {
        $etudiant = array();

        $query = $this->getPDO()->prepare("select idEtu, nom, prenom, formation from etudiant where idEtu = :id order by nom asc");
        $query->execute(array('id' => $id));

        $datas = $query->fetch();


        $etudiant['id'] = $datas['idEtu'];
        $etudiant['nom'] = $datas['nom'];
        $etudiant['prenom'] = $datas['prenom'];
        $etudiant['formation'] = $datas['formation'];

        $query->closeCursor();

        return $etudiant;
    }

    function updateClasseEtu($idClasse, $idEtu) {
        $nomClasse = $this->getPDO()->prepare("select nom from classe where idClasse = :idClasse");
        $nomClasse->execute(array('idClasse' => $idClasse));

        $data = $nomClasse->fetch();

        $update = $this->getPDO()->prepare("update etudiant
                                                      set classe_id = :idClasse, formation = :formation, groupe_id = 0, groupe_tp_id = 0
                                                      where idEtu = :idEtu");
        $update->execute(array(
            'idClasse' => $idClasse,
            'formation' => $data['nom'],
            'idEtu' => $idEtu
        ));
    }

    function selectEtuWithoutGroup() {
        $list_etu_sans_groupe = array();
        $i = 0;



        $query = $this->getPDO()->query("SELECT etudiant.idEtu, etudiant.prenom, etudiant.nom, etudiant.formation, etudiant.nbr_absence,
                                                  groupe_tp.nom as tp FROM `etudiant`
                                                  inner join groupe_tp on groupe_tp.idGroupeTP = etudiant.groupe_tp_id
                                                  WHERE etudiant.groupe_id < 1 or etudiant.groupe_id > (select COUNT(*) from groupe) + (select count(*) from groupe_tp)");

        $query->execute();

        while($datas = $query->fetch()) {
            $list_etu_sans_groupe[$i] = array(
                'id' => $datas['idEtu'],
                'nom' => $datas['nom'],
                'prenom' => $datas['prenom'],
                'formation' => $datas['formation'],
                'nbr_absence' => $datas['nbr_absence'],
                'groupe' => 'Aucun groupe',
                'groupe_tp' => $datas['tp']

            );

            $i++;
        }

        $query2 = $this->getPDO()->query("SELECT etudiant.idEtu, etudiant.prenom, etudiant.nom, etudiant.formation, etudiant.nbr_absence,
                                                  groupe.nom as tp FROM `etudiant`
                                                  inner join groupe on groupe.idGroupe = etudiant.groupe_id
                                                  WHERE etudiant.groupe_tp_id < 1 or etudiant.groupe_tp_id > (select COUNT(*) from groupe) + (select count(*) from groupe_tp)");

        $query2->execute();

        while($datas = $query2->fetch()) {
            $list_etu_sans_groupe[$i] = array(
                'id' => $datas['idEtu'],
                'nom' => $datas['nom'],
                'prenom' => $datas['prenom'],
                'formation' => $datas['formation'],
                'nbr_absence' => $datas['nbr_absence'],
                'groupe' => $datas['tp'],
                'groupe_tp' => 'Aucun groupe'

            );

            $i++;
        }

        $query3 = $this->getPDO()->prepare("SELECT etudiant.idEtu, etudiant.prenom, etudiant.nom, etudiant.formation, etudiant.nbr_absence FROM `etudiant` 
                                                     WHERE (etudiant.groupe_tp_id < 1 or etudiant.groupe_tp_id > (select COUNT(*) from groupe) + (select count(*) from groupe_tp)) 
                                                     and (etudiant.groupe_id < 1 or etudiant.groupe_id > (select COUNT(*) from groupe) + (select count(*) from groupe_tp))");

        $query3->execute();

        while($datas = $query3->fetch()) {
            $list_etu_sans_groupe[$i] = array(
                'id' => $datas['idEtu'],
                'nom' => $datas['nom'],
                'prenom' => $datas['prenom'],
                'formation' => $datas['formation'],
                'nbr_absence' => $datas['nbr_absence'],
                'groupe' => 'Aucun groupe',
                'groupe_tp' => 'Aucun groupe'

            );

            $i++;
        }

        $query->closeCursor();
        $query2->closeCursor();
        $query3->closeCursor();
        return $list_etu_sans_groupe;
    }

    function selectIdProf($nom, $prenom, $password, $role) {
        $query = $this->getPDO()->prepare("select id from user where nomUser = :nom and prenom = :prenom and mdp = :password and role = :role");
        $query->execute(array(
            'nom' => $nom,
            'prenom' => $prenom,
            'password' => $password,
            'role' => $role
        ));

        $datas = $query->fetch();

        return $datas['id'];
    }

    function selectClasseByIdProf($id) {
        $list_classes = array();
        $i = 0;

        $query = $this->getPDO()->prepare("select classe.nom from classe 
                                                    inner join prof_classe on classe.idClasse = prof_classe.idClasse
                                                    inner join professeur on professeur.idProf = prof_classe.idProf 
                                                    where professeur.idUser = :id");

        $query->execute(array('id' => $id));

        while($datas = $query->fetch()) {
            $list_classes[$i] = array(
                'nom' => $datas['nom']
            );

            $i++;
        }

        $query->closeCursor();
        return $list_classes;
    }

    function insertClasseProf($classe) {
        $idClasse = $this->getPDO()->prepare("select idClasse from classe where nom = :nom");
        $idClasse->execute(array('nom' => $classe));
        $datas = $idClasse->fetch();

        $idProf = $this->getPDO()->prepare("select idProf from professeur where idUser = :id");
        $idProf->execute(array('id' => $_SESSION['prof_id']));
        $donnees = $idProf->fetch();

        $query = $this->getPDO()->prepare("insert into prof_classe (idProf, idClasse) values (:prof_id, :classe_id) ");
        $query->execute(array(
            'prof_id' => $donnees['idProf'],
            'classe_id' => $datas['idClasse']
        ));

        $idClasse->closeCursor();
        $query->closeCursor();

    }

    function selectCoursByClasse($classe)  {
        $list_cours = array();
        $i = 0;

        $idClasse = $this->getPDO()->prepare("select idClasse from classe where nom = :nom");
        $idClasse->execute(array('nom' => $classe));
        $datas = $idClasse->fetch();

        $query = $this->getPDO()->prepare("select idCours, matricule, nom from cours where classe_id = :id order by matricule asc");
        $query->execute(array('id' => $datas['idClasse']));

        while ($donnees = $query->fetch()) {
            $list_cours[$i] = array(
                'id' => $donnees['idCours'],
                'matricule' => $donnees['matricule'],
                'nom' => $donnees['nom'],
                'classe' => $classe
            );
            $i++;
        }

        $idClasse->closeCursor();
        $query->closeCursor();

        return $list_cours;
    }

    function insertCoursProf($idProf, $idClasse) {
        $idProfesseur = $this->getPDO()->prepare("select idProf from professeur where idUser = :id");
        $idProfesseur->execute(array('id' => $idProf));
        $datas = $idProfesseur->fetch();


        $query = $this->getPDO()->prepare("insert into prof_cours(idProf, idCours) values(:idProf, :idClasse)");
        $query->execute(array(
            'idProf' => $datas['idProf'],
            'idClasse' => $idClasse
        ));

        $query->closeCursor();
    }

    function selectCoursProf($idProf) {
        $list_cours = array();
        $i = 0;

        $idProfesseur = $this->getPDO()->prepare("select idProf from professeur where idUser = :id");
        $idProfesseur->execute(array('id' => $_SESSION['prof_id']));
        $donnees = $idProfesseur->fetch();

        $query = $this->getPDO()->prepare("select cours.idCours, cours.matricule, cours.nom, classe.nom as nomClasse
                                                    from cours inner join prof_cours on prof_cours.idCours = cours.idCours
                                                    inner join classe on classe.idClasse = cours.classe_id
                                                    inner join professeur on professeur.idProf = prof_cours.idProf
                                                    where professeur.idUser = :id");
        $query->execute(array('id' => $idProf));

        while($datas = $query->fetch()) {
            $list_cours[$i] = array(
                'id' => $datas['idCours'],
                'matricule' => $datas['matricule'],
                'nom' => $datas['nom'],
                'classe' => $datas['nomClasse']
            );

            $i++;
        }

        $query->closeCursor();
        return $list_cours;
    }

    function deleteCoursProf($idProf, $idCours) {
        $query = $this->getPDO()->prepare("delete from prof_cours where idProf = :idProf and idCours = :idCours");
        $query->execute(array(
            'idProf' => $idProf,
            'idCours' => $idCours
        ));

        $query->closeCursor();
    }

    function selectAllGroupesTP() {
        $list_groupes = array();
        $i = 0;

        $query = $this->getPDO()->query("select * from groupe_tp");
        $query->execute();
        while ($datas = $query->fetch()) {
            $list_groupes[$i] = array(
                'nom' => $datas['nom']
            );
            $i++;
        }

        $query->closeCursor();
        return $list_groupes;
    }

    function selectGroupeByClasse($classe) {
        $list_groupes = array();
        $i = 0;

        $td = $this->getPDO()->prepare("select distinct groupe.idGroupe, groupe.nom from groupe
                                                 inner join classe on classe.idClasse = groupe.classe_id
                                                 where classe.nom = :nom");
        $td->execute(array('nom' => $classe));

        while($datas = $td->fetch()) {
            $list_groupes[$i] = array(
                'id' => $datas['idGroupe'],
                'nom' => $datas['nom']
            );
            $i++;
        }

        if(!stristr($classe, 'LP')) {
            $tp = $this->getPDO()->prepare("select groupe_tp.idGroupeTP, groupe_tp.nom from groupe_tp 
                                                 inner join groupe on groupe.idGroupe = groupe_tp.classe_td_id
                                                 inner join classe on classe.idClasse = groupe.classe_id
                                                 where classe.nom = :nom");
            $tp->execute(array('nom' => $classe));

            while($datas_tp = $tp->fetch()) {
                $list_groupes[$i] = array(
                    'id' => $datas_tp['idGroupeTP'],
                    'nom' => $datas_tp['nom']
                );
                $i++;
            }
            $tp->closeCursor();
        }

        $td->closeCursor();


        return $list_groupes;

    }

    function selectGroupeTPById($id) {
        $groupe = array();
        $query = $this->getPDO()->prepare("select groupe_tp.idGroupeTP, groupe_tp.nom, classe.nom as classe from groupe_tp
                                                    inner join groupe on groupe.idGroupe = groupe_tp.classe_td_id
                                                    inner join classe on classe.idClasse = groupe.classe_id
                                                    where groupe_tp.idGroupeTP = :id ");
        $query->execute(array('id' => $id));
        $datas = $query->fetch();

        $groupe['id'] = $datas['idGroupeTP'];
        $groupe['nom'] = $datas['nom'];
        $groupe['classe'] = $datas['classe'];

        $query->closeCursor();
        return $groupe;
    }

    function selectEtuByGroupTP($id) {
        $list_etu = array();
        $i = 0;

        $query = $this->getPDO()->prepare("select idEtu, nom, prenom from etudiant where groupe_tp_id = :id");
        $query->execute(array('id' => $id));

        while($donnees = $query->fetch()) {
            $list_etu[$i] = array(
                'id' => $donnees['idEtu'],
                'nom' => $donnees['nom'],
                'prenom' => $donnees['prenom']
            );

            $i++;
        }

        return $list_etu;
    }

    function updateGroupeTPEtu($etudiant_id, $groupe_id) {
        $query = $this->getPDO()->prepare("update etudiant set groupe_tp_id = :groupe_id where etudiant.idEtu = :etudiant_id");
        $query->execute(array(
            'groupe_id' => $groupe_id,
            'etudiant_id' => $etudiant_id
        ));
    }

    function insertGroupeTP($nom, $groupe_td) {
        echo $groupe_td;
        $req = $this->getPDO()->prepare("select idGroupe from groupe where nom = :groupe_td");
        $req->execute(array(
            'groupe_td' => $groupe_td
        ));
        $datas = $req->fetch();

        $query = $this->getPDO()->prepare("insert into groupe_tp(idGroupeTP, nom, classe_td_id) values 
                                                    (default, :nom, :classe_id)");
        $query->execute(array(
            'nom' => $nom,
            'classe_id' => $datas['idGroupe']
        ));

        $req->closeCursor();
        $query->closeCursor();
    }

    function deleteGroupeTP($id) {
        $query = $this->getPDO()->prepare("delete from groupe_tp where idGroupeTP = :id");
        $query->execute(array(
            'id' => $id
        ));

        $update = $this->getPDO()->prepare("update etudiant set groupe_tp_id = 0 where groupe_tp_id = :id");
        $update->execute(array(
            'id' => $id
        ));
    }
    public function countAbs($idEtu) {
        $query = $this->getPDO()->prepare("SELECT COUNT(*) FROM absence WHERE idEtu=$idEtu AND description ='absence non justifiée'");
        $query->execute();
        $result = $query->fetch();
        echo $result['COUNT(*)'];
    }

    public function countAbsJustifier($idEtu) {
        $query = $this->getPDO()->prepare("SELECT COUNT(*) FROM absence WHERE idEtu=$idEtu AND description !='absence non justifiée'");
        $query->execute();
        $result = $query->fetch();
        echo $result['COUNT(*)'];
    }

    public function selectEtuByFormation($classe) {
        $list_etu = array();
        $i = 0;

        $query = $this->getPDO()->prepare("select * from etudiant where formation = :classe");
        $query->execute(array('classe' => $classe));

        while($datas = $query->fetch()) {
            $list_etu[$i] = array(
                'idEtu' => $datas['idEtu'],
                'nom' => $datas['nom'],
                'prenom' => $datas['prenom']
            );
            $i++;
        }

        $query->closeCursor();
        return $list_etu;
    }

    public function selectEtuDifferent($classe) {
        $tab_etudiant = array();
        $i = 0;

        $req = $this->getPDO()->prepare("select etudiant.idEtu, etudiant.prenom, etudiant.nom, etudiant.formation, etudiant.nbr_absence, groupe.nom as nomGroup, groupe_tp.nom as tp
                                                from etudiant inner join groupe on groupe.idGroupe = etudiant.groupe_id
                                                inner join groupe_tp on groupe_tp.idGroupeTP = etudiant.groupe_tp_id
                                                where etudiant.formation <> :classe
                                                order by etudiant.formation asc, etudiant.nom asc");

        $req->execute(array('classe' => $classe));


        while($donnees = $req->fetch()) {
            $tab_etudiant[$i] = array(
                'idEtu' => $donnees['idEtu'],
                'prenom' => $donnees['prenom'],
                'nom' => $donnees['nom'],
                'formation' => $donnees['formation'],
                'groupe' => $donnees['nomGroup'],
                'tp' => $donnees['tp'],
                'nbr_absence' => $donnees['nbr_absence'],
            );

            $i++;
        }
        $req->closeCursor();
        return $tab_etudiant;
    }

}

