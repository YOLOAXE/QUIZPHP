<?php

    require 'Quizz.php';

    class QuizzModel extends CI_Model {
    
        public function __construct()
	    {
		    $this->load->database();
        }


        /**
         * Cette méthode permet d'enregistrer un quiz dans la base de donnée
         * @param $q : objet quizz à enregistrer dans la base
         * @param $userID : identifiant de l'utilisateur propriétaire du quizz
         * @param $qID : identifiant du quizz, (si non null, alors cela veut dire qu'il s'agit d'une réécriture d'un quizz éxistant)
         */
        function addQuizz($q, $userID, $qID = null) 
		{
            if (!is_null($qID)) { // si c'est une réécriture
                $quizzTest = $this->getQuizz($qID);
                if ($q->getQuestion() == $quizzTest->getQuestion()) { // si les modification apporté ne concerne pas les questions (changement mineurs)
                    $name = $q->getName(); 
                    $status = $q->getStatus();
                    $time = $q->getTime();
                    $data = array(
                        'nomQuizz' => $name,
                        'etat' => $status,
                        'temps' => $time);

                    $this->db->where('quizzID', $qID); //on met à jour le quiz sans le détruire
                    $this->db->update('Quizz', $data);
                    return;
                }
            } // sinon
            $key = $this->genKey(); // on génère une clé
            $data = array(
                'clef' => $key,
                'etat' => $q->getStatus(),
                'nomQuizz' => $q->getName(),
                'temps' => $q->getTime(),
                'userID' => $userID
            );
            if (!is_null($qID)) { // on détruit l'ancien quiz si il s'agit d'une réécriture
                $this->db->delete('Quizz', array('quizzID' => $qID));
                $data['quizzID'] = $qID;
            }
            $this->db->insert('Quizz', $data); // on enregistre le quiz
            $QID = $this->getQuizzIDByKey($key);
			$allQuestion = $q->getQuestion();
            foreach ($allQuestion as $question) // les questions correspondantes
			{
                $data = array(
                    'nomQuestion' => $question->getTitle(),
                    'quizzID' => $QID,
                    'url_image' => $question->getImage(),
                );
				$this->db->insert('Question', $data);
                $qID = $this->db->insert_id(); // on récupère l'identifiant de la dernière question enregistrée

                foreach ($question->getAnswer() as $answer) // les réponses correspondantes au questions
				{
                    $data = array(
                        'intitule' => $answer->getText(),
                        'isGoodAnswer' => $answer->getIsCorrect(),
                        'questionID' => $qID);

                    $this->db->insert('ChampReponse', $data);
                }
            }
        }

        /**
         * Cette méthode permet de récupérer un quiz dans la base de donnée
         * @param $id : identifiant du quiz à retourner
         * @return : objet de type quizz correspondant à l'identifiant "$id"
         */
        function getQuizz($id) {
            
            $sql = "SELECT * FROM Quizz WHERE quizzID = ".$id.";"; // on récupère le quiz dans la base de donnée
            $query = $this->db->query($sql);
            $res = $query->result();
            if (empty($res)) {
                return null;    
            }
            $res = $res[0];
            $quizz = new Quizz(); // on créé un objet quizz
            $quizz->setName($res->nomQuizz);
            $quizz->setTime($res->temps);
            $quizz->setKey($res->clef);
            $quizz->setStatus($res->etat);

            $sql = "SELECT * FROM Question WHERE quizzID = ".$id.";"; // on récupère ses questions
            $query = $this->db->query($sql);
            $res = $query->result();
            if (empty($res)) {
                return $quizz;    
            }

            $quizz->setQuestion();

            foreach ($res as $row) {
                $question = new Question($row->nomQuestion, $row->url_image);

                $sql = "SELECT * FROM ChampReponse WHERE questionID = ".$row->questionID.";"; // on récupère les réponses
                $query = $this->db->query($sql);
                $res = $query->result();
                if (empty($res)) {
                    break;
                }

                $question->setAnswer();

                foreach ($res as $ansRow) {
                    $question->addAnswer(new Answer($ansRow->intitule, $ansRow->isGoodAnswer));
                }
                $quizz->addQuestion($question);
            }
            return $quizz; // on retourne l'objet quizz
        }

        /**
         * Retourne l'identifiant d'un quiz à partir de sa clé
         * @param $k : clé du quiz à récupérer
         * @return : l'identifiant du quiz si une clé correspond, null sinon
         */
        function getQuizzIDByKey($k) 
		{
            $this->db->select('quizzID');
            $this->db->from('Quizz');
            $this->db->where('clef', $k);
            $query = $this->db->get();
            $res = $query->result();
            if (!empty($res)) {
                $res = $res[0];
                return $res->quizzID;
            }
            return null;
        }

        /**
         * génère une clé unique basé sur l'alphabet et les chiffres
         * @return : la clé
         */
        public function genKey() {
            $key = '';
            $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
            for($i = 0; $i < 20; $i++) {
                $key .= $chars[rand(0, strlen($chars)-1)];
            }
            $this->db->from('Resultat');
            $this->db->where('clef', $key);
            return $key;
        }

        /**
         * retourne tout les identifiant, les noms, les clé et les états de tous les quizzs d'un utilisateur sous la forme d'un tableau de tableaux associatifs
         * @param $id : identifiant de l'utilisateur
         * @return : un tableau correspondant au quizs de l'utilisateur
         */
        public function getQuizzsID($id) 
		{
            $this->db->select('nomQuizz, quizzID, clef, etat');
            $this->db->from('Quizz');
            $this->db->where('userID', $id);
            $query = $this->db->get();
            $res = $query->result();
            if (empty($res)) {
                return null;
            }

            $data = array();

            foreach ($res as $info) {
                $data[] = array('nom' => $info->nomQuizz, 'id' => $info->quizzID, 'key' => $info->clef, 'state' => $info->etat);
            }

            return $data;
        }

        /**
         * supprime un quiz de la base de donnée
         * @param $qID : identifaint du quiz à supprimer
         */
		public function removeQuizzById($qID, $userID)
		{
            $this->db->select('quizzID');
            $this->db->from('Quizz');
            $this->db->where('userID', $userID);
			$query = $this->db->get();
            $res = $query->result();
            if (empty($res)) {
                return;
            }
			$this->db->delete('Quizz', array('quizzID' => $qID));
		}
	}
?>
