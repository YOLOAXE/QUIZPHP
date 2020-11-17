<?php

    class CorrectionModel extends CI_Model {
    
        public function __construct()
	    {
            $this->load->database();
            $this->load->model('QuizzModel');
        }

        /**
         * Enregistre la correction d'un utilisateur ayant réalisé un quiz
         * @param $fn : prenom de l'utilisateur
         * @param $ln : nom de l'utilisateur
         * @param $note : score de l'utilisateur
         * @param $id : identifiant du quiz réalisé
         * @return : clé d'accés à la correction
         */
        public function setCorrection($fn, $ln, $note, $id) 
        {
            $key = $this->QuizzModel->genKey();
            $data =  array(
                            'clef' => $key,
                            'note' => $note,
                            'nom' => $ln,
                            'prenom' => $fn);
            $this->db->insert('Resultat', $data);

            $this->db->select('resultatID');
            $this->db->from('Resultat');
            $this->db->where('clef', $key);
            $query = $this->db->get();
            $res = $query->result();
            $res = $res[0];

            $data = array(
                            'quizzID' => $id,
                            'resultatID' => $res->resultatID);
            $this->db->insert('Obtient', $data);
            return $key;
        }

        /**
         * retourne le nom, prenom, note, nom du quiz et identifiant du quiz correspondant à la clé de correction
         * @param $key : clé de la correction à récupérer
         * @return : tableau associatif de la correction si la clé est correcte, null sinon
         */
        public function getCorrection($key) {
            $key = filter_var($key, FILTER_SANITIZE_STRING);
            $this->db->select('*');
            $this->db->from('Resultat');
            $this->db->join('Obtient', 'Resultat.resultatID = Obtient.resultatID');
            $this->db->join('Quizz', 'Obtient.quizzID = Quizz.quizzID');
            $this->db->where('Resultat.clef', $key);
            $query = $this->db->get();
            $res = $query->result();
            if (empty($res)) {
                return null;
            }
            $res = $res[0];
            return array(
                        'nom' => $res->nom,
                        'prenom' => $res->prenom,
                        'nomQuizz' => $res->nomQuizz,
                        'note' => $res->note,
						'quizzID' => $res->quizzID);
        }

        /**
         * retourne l'ensemble des corrections de tout les quizs de l'utilisateur
         * @param $idUser : identifiant de l'utilisateur
         * @return : un tableau de tableaux assosiatifs correspondant à chaques corrections pour chaques quizs
         */
        public function getAllCorrection($idUser) {

            $resultats = array();
            $this->db->select('quizzID');
            $this->db->from('Quizz');
            $this->db->where('userID', $idUser);
            $query = $this->db->get();
            $res = $query->result();
            if (empty($res)) {
                return null;
            }

            foreach ($res as $id) { // pour chaque quizs de l'utilisateur
                $this->db->select('nom, prenom, nomQuizz, note');
                $this->db->from('Resultat');
                $this->db->join('Obtient', 'Resultat.resultatID = Obtient.resultatID');
                $this->db->join('Quizz', 'Obtient.quizzID = Quizz.quizzID');
                $this->db->where('Quizz.quizzID', $id->quizzID);
                $query = $this->db->get();
                $row = $query->result();
                if (empty($row)) {
                    $resultats[] = null; // on retourne null si il n'y à pas de résultat pour cee quiz
                } else {
                    $resultats[] = $row;
                }
            }
			foreach($resultats as $res)
			{
				if($res != null)
				{
					return $resultats;
				}
			}
            return null;
        }

        /**
         * retourne le nom et le prenom correspondant à un resultat
         * @param $key : clé du resultat
         * @return : tableau associatif correspond si la clé existe, null sinon
         */
		public function getNamebykey($key)
		{
			$resultats = array();
            $this->db->select('quizzID');
            $this->db->from('Quizz');
            $this->db->where('clef', $key);
            $query = $this->db->get();
            $res = $query->result();
            if (empty($res)) {
                return null;
            }
			$res = $res[0];
			$this->db->select('nom, prenom');
            $this->db->from('Resultat');
            $this->db->join('Obtient', 'Resultat.resultatID = Obtient.resultatID');
            $this->db->join('Quizz', 'Obtient.quizzID = Quizz.quizzID');
            $this->db->where('Quizz.quizzID', $res->quizzID);
            $query = $this->db->get();
            $row = $query->result();
			if (empty($row)){
				return null;
            } 
			return $row;
		}

    }
?>
