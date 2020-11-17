<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class StudentManager extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('table');
		$this->load->library('session');//On charge la session afin de gérer la gestion de connexion au quiz.
		$this->load->model('QuizzModel');//On charge le Model de quiz afin d'interagir avec le quiz via un objet plutôt qu'un tableau pour afficher sa correction.
		$this->load->model('CorrectionModel');//On charge ce modèle afin d'obtenir les informations de correction d'un quiz via des requêtes Sql
	}

	function displayQuizz($key)//Affiche le quiz via la clé.
	{
		if($this->session->has_userdata('prenomEleve')) //Si l'utilisateur "eleve" possède une variable de session ici c'est quil est passé par une procédure normale de connexion sinon il n'a rien à faire ici.
		{
			$id = $this->QuizzModel->getQuizzIDByKey($key);//On recuper l'id du quiz via la clé donne par "l'enseignant".
			$quizz = $this->QuizzModel->getQuizz($id);//Via l'id du quiz on obtient le modèle quiz, avec lequel on va pouvoir aller chercher la plupart des informations le concernant.
			if (!is_null($quizz)) // On test si le model recuperer n'est pas null avant d'interagire avec.
			{
				$this->session->set_userdata('nbQuestionsQuizz', count($quizz->getQuestion())); //On ajoute une variable de session du nombre de questions pour pouvoir l'utiliser dans tout le contrôler.
				$data['quizz'] = (object)$quizz;//On va chercher le modèle quiz pour le transmettre à la page.
				$data['nb'] = $this->session->userdata('nbQuestionsQuizz');//Argument transmis à la page.
				$data['id'] = $id;//id du quiz
				if(time() - $this->session->userdata('Start_Time') > $quizz->getTime())//En cas de déconnexion involontaire de la page l'eleve peut y retourne afin de finir son test tout en gardant le temps quil avait déjà utilisé.
				{
					$sessiondata = array('Start_Time' => time());//Si le temps total est expirer on remet a zero.
					$this->session->set_userdata($sessiondata);
				}

				$this->load->view('templates/headerQuizzPage.php',$data);//On charge les pages avec les donnes
				$this->load->view('pageQuizz.php',$data);
				$this->load->view('templates/footer.php');
			}
		}
	}

	function displayCorrection()//affiche la correction
	{
		$data = $this->CorrectionModel->getCorrection($this->input->post('key'));//On va chercher la clé dans le formulaire
		$quizz = $this->QuizzModel->getQuizz($data['quizzID']);//On va chercher le quiz via la Clé
		$data['quizz'] = (object)$quizz;
		$data['nb'] = count($quizz->getQuestion());
		$this->load->view('templates/header.php', $this->isConnected());//On affiche la page de correction avec les donnes
		$this->load->view('correctionQuizzStudent.php', $data);
		$this->load->view('templates/footer.php');
	}

	function studentConnectionQuizzPage()//Page de connexion au quiz.
	{
		$this->load->view('templates/header.php', $this->isConnected());
		$this->load->view('studentConnectQuizz.php');
		$this->load->view('templates/footer.php');
	}

	function studentConnectionQuizz()//Réception du formulaire de connexion au quiz via cette function
	{
		$this->load->helper('form');// chargement du helper form 
		$this->load->library('form_validation');// regle de validation

		$this->form_validation->set_rules('prenom', 'Prenom','required|callback_student_name_check', array('required' => 'S\'il vous plaît entrez votre prenom.'));//Regle de prénom/nom est requise et nécessité de ne pas avoir de ja fait le quiz.
		$this->form_validation->set_rules('nom', 'Nom','required', array('required' => 'S\'il vous plaît entrez votre nom.'));//nom requis
		$this->form_validation->set_rules('key', 'Clé','required|callback_key_check', array('required' => 'S\'il vous plaît entrez clé.'));//Regle de clé est requise et nécessité de quelle existe dans la BD.

		if (!$this->form_validation->run()) //Si les regles non pas ete valider alors on lui réaffiche la page de connexion.
		{
			$this->studentConnectionQuizzPage();
		}
		else 
		{
			$this->session->sess_regenerate(true);
			$sessiondata = array('prenomEleve' => $this->input->post('prenom'),
								 'nomEleve' => $this->input->post('nom'),
								 'keyQuizz' => $this->input->post('key'),
								 'Start_Time' => time());
			$data = array('isConnisConnectedQuizz' => true);

			$this->session->set_userdata($sessiondata);
			$this->displayQuizz($this->input->post('key'));
		}
	}

	function studentConnectionCorrectionPage()//Cette fonction permet de se connecter à la correction d'un quiz via la clé donne à la fin d'un quiz.
	{

		$this->load->helper('form');
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('key', 'Clé','required|callback_correction_key_check', array('required' => 'S\'il vous plaît entrez clé.'));

		if ($this->form_validation->run())  
		{
			$this->displayCorrection();
			return;
		}
		$this->load->view('templates/header.php', $this->isConnected());
		$this->load->view('studentConnectCorrection.php');
		$this->load->view('templates/footer.php');	
	}

	function valideQuizz($id)//On valide le quiz et on fait les calculs de la note avant de l'envoyé dans la BD.
	{
		$note = floatval($this->session->userdata('nbQuestionsQuizz'));
		$quizz = $this->QuizzModel->getQuizz($id);
		if (!is_null($quizz)) {
			for($i = 0;$i < $this->session->userdata('nbQuestionsQuizz');$i++) {
				$nbAnswer = count($quizz->getQuestion()[$i]->getAnswer());
				for($j = 0;$j < $nbAnswer;$j++)
				{
					if ($quizz->getQuestion()[$i]->getAnswer()[$j]->getIsCorrect() != (!is_null($this->input->post('reponseCorrect'.$i.$j)) ? 1 : 0)) {
						$note -= 1.0;
						break;
					}
				}
			}
		}
		$note = ($note / $this->session->userdata('nbQuestionsQuizz')) * 20.0;
		if(time() - $this->session->userdata('Start_Time') > ($quizz->getTime() + 5))//Si le Javascript n'a pas automatiquement validé le quiz et l'utilisateur n'a pas validé et en plus dépassé son temps il aura 5 secondes après expiration pour soumettre son quiz.
		{
			$note = 0;
		}
		$key = $this->CorrectionModel->setCorrection($this->session->userdata('prenomEleve'), $this->session->userdata('nomEleve'), $note, $id);
		$data['key'] = $key;
		$this->load->view('templates/header.php', $this->isConnected());
		$this->load->view('keyDisplay.php', $data);
		$this->load->view('templates/footer.php');
	}

	public function key_check()//Vérifie si la clé donne par l'utilisateur est correcte.
	{
		$quizz = $this->QuizzModel->getQuizzIDByKey($this->input->post('key'));

		if(is_null($quizz)) 
		{
			$this->form_validation->set_message('key_check', 'Votre clé est incorrect.');
			return FALSE;
		} 
		else 
		{
			return TRUE;
		}
	}

	public function correction_key_check()//Vérifie si la clé de correction donne par l'utilisateur est correcte.
	{
		$data = $this->CorrectionModel->getCorrection($this->input->post('key'));

		if(is_null($data)) 
		{
			$this->form_validation->set_message('correction_key_check', 'Votre clé est incorrect.');
			return FALSE;
		} 
		else 
		{
			$quizz = $this->QuizzModel->getQuizz($data['quizzID']);
			if($quizz->getStatus() != "e" && $quizz->getStatus() != "l")
			{
				$this->form_validation->set_message('correction_key_check', 'La correction n\'est pas encore disponible pour plus d\'informations veuillez contacter le créateur du quiz.');
				return FALSE;
			}
			return TRUE;
		}
	}

	public function student_name_check()//On vérifie si le nom et le prénom donné par l'utilisateur n'a pas déjà ete utiliser pour faire un quiz.
	{
		$data = $this->CorrectionModel->getNamebykey($this->input->post('key'));
		if(isset($data))
		{
			foreach($data as $np)
			{
				if($np->nom === $this->input->post('nom') && $np->prenom === $this->input->post('prenom'))
				{
					$this->form_validation->set_message('student_name_check', 'Vous ne pouvez faire le quiz qu\'une seule fois.');
					return FALSE;
				}
			}
		}
		return TRUE;
	}

	public function isConnected()//fonction de verification si l'utilisateur "Teacher" est deja connecter.
	{
		return $data = array('isConnected' => $this->session->has_userdata('prenom'));
	}
}
