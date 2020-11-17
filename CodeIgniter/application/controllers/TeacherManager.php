<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TeacherManager extends CI_Controller 
{

	public $CI;

	public function __construct()// on charge les library/helper/model
	{
		parent::__construct();
		$this->load->library('table');
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->model('QuizzModel');
		$this->load->model('UserModel');
		$this->load->model('CorrectionModel');

	}

	function teacherConnectionPage()// Si l'utilisateur est déjà connecté alors on lui réaffiche la page HomeTeacher sinon on lui affiche la page de connexion.
	{	
		if($this->session->has_userdata('prenom'))
		{
			$tab = $this->QuizzModel->getQuizzsID($this->session->userdata('id'));
			$data['tab'] = $tab;

			$this->load->view('templates/headerTeacherConnection.php');
			$this->load->view('teacherHome.php', $data);
			$this->load->view('templates/footer.php');
		}
		else
		{
			$this->load->view('templates/header.php', $this->isConnected());
			$this->load->view('teacherLogin.php');
			$this->load->view('templates/footer.php');
		}
	}

	/*On teste d'abord si le formulaire est correct si oui on lui affiche la page TeacherHome et on l'enregistre dans la bd 
	sinon on lui réaffiche la page d'inscription.
	*/
	function teacherInscription()
	{

		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('prenom', 'Prenom','required|callback_inscription_check|max_length[20]', array('required' => 'S\'il vous plaît entrez votre prenom.'));
		$this->form_validation->set_rules('nom', 'Nom','required|max_length[20]', array('required' => 'S\'il vous plaît entrez votre nom.'));
		$this->form_validation->set_rules('first_password', 'Mot de passe','required|min_length[8]', array('required' => 'S\'il vous plaît entrez votre mot de passe.',
		'min_length[8]' => '8 characters minimum.'));
		$this->form_validation->set_rules('second_password', 'Comfirmer mot de passe', 'required|callback_password_check', array('required' => 'S\'il vous plaît entrez votre mot de passe.'));

		if (!$this->form_validation->run()) {

			$this->load->view('templates/header.php', $this->isConnected());
			$this->load->view('teacherInscription.php');
			$this->load->view('templates/footer.php');
		} else {

			$firstname = $this->input->post('prenom');
			$lastname = $this->input->post('nom');
			$password = password_hash($this->input->post('first_password'), PASSWORD_BCRYPT);

			$this->UserModel->setNewUser(new User($firstname, $lastname, $password, null));

			$sessiondata = array('prenom' => $firstname,
								 'nom' => $lastname,
								 'id' => $this->UserModel->getUser($firstname, $lastname)->getId());
			$this->session->set_userdata($sessiondata);

			$tab = $this->QuizzModel->getQuizzsID($this->session->userdata('id'));
			$data['tab'] = $tab;
			$this->load->view('templates/headerTeacherConnection.php');
			$this->load->view('teacherHome.php',$data);
			$this->load->view('templates/footer.php');
		}
	}

	/*On teste d'abord si le formulaire est correct via(Required/callback) si oui on lui affiche la page TeacherHome 
	sinon on lui réaffiche la page de connexion.
	*/
	function teacherConnection()
	{
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('prenom', 'Prenom','required', array('required' => 'S\'il vous plaît entrez votre prenom.'));
		$this->form_validation->set_rules('nom', 'Nom','required', array('required' => 'S\'il vous plaît entrez votre nom.'));
		$this->form_validation->set_rules('password', 'Mot de passe','required|callback_connection_check', array('required' => 'S\'il vous plaît entrez votre mot de passe.'));

		if (!$this->form_validation->run()) 
		{
			$this->load->view('templates/header.php', $this->isConnected());
			$this->load->view('teacherLogin.php');
			$this->load->view('templates/footer.php');
		} 
		else 
		{
			$this->session->sess_regenerate(true);
			$sessiondata = array('prenom' => $this->input->post('prenom'),
								 'nom' => $this->input->post('nom'),
								 'id' => $this->UserModel->getUser($this->input->post('prenom'), $this->input->post('nom'))->getId());
			$data = array('isConnisConnected' => true);

			$this->session->set_userdata($sessiondata);

			$tab = $this->QuizzModel->getQuizzsID($this->session->userdata('id'));
			$data['tab'] = $tab;

			$this->load->view('templates/headerTeacherConnection.php', $data);
			$this->load->view('teacherHome.php',$data);
			$this->load->view('templates/footer.php');
		}

		return;
	}

	/*Cette fonction permet de supprimer un quiz à partir d'un id et d'un userId
	*/
	function quizzEditorDelete($id = null)
	{
		if (!is_null($id)) 
		{
			$this->QuizzModel->removeQuizzById($id,$this->session->userdata('id'));
		}
		$this->teacherConnectionPage();
	}

	/*Affiche tous les quiz dans la page de l'editor de quiz qui correspond au quizzid et les transmet aux vues.
	*/
	function quizzEditorDisplay($id = null) {

		$this->session->set_userdata('nbQuestions', 0);
		$this->load->view('templates/headerTeacherConnection.php');
		if (!is_null($id)) 
		{
			$quizz = $this->QuizzModel->getQuizz($id);
			if (!is_null($quizz)) 
			{
				$this->session->set_userdata('nbQuestions', count($quizz->getQuestion()));
				$data['quizz'] = (object)$quizz;
				$data['id'] = $id;
				$data['nb'] = $this->session->userdata('nbQuestions');
				$data['isNew'] = true;
				$this->load->view('teacherQuizzEdit.php', $data);
			}
		} else {
			$data['quizz'] = new Quizz();
			$data['nb'] = $this->session->userdata('nbQuestions');
			$data['isNew'] = true;
			$this->load->view('teacherQuizzEdit.php', $data);
		}
		
		$this->load->view('templates/footer.php');
	}


	/*Permet d'ajoute une question au quiz si l'argument $state est a false elle incremente ou non le nombre de question de la session.
	*/
	function addQuestionDisplay($state = false, $id = null) 
	{
			$quizz = new Quizz();
			$quizz->setName($this->input->post('nomQuizz'));
			$quizz->setStatus($this->input->post('statusQuizz'));

			$quizz->setQuestion();
		if($state)
		{
			$this->session->set_userdata('nbQuestions', $this->session->userdata('nbQuestions') + 1);
		}
		for($i = 0; $i < $this->session->userdata('nbQuestions');$i++) 
		{
			$question = new Question();
			$question->setTitle((!is_null($this->input->post('question'.$i))) ? $this->input->post('question'.$i) : '');
			$question->setImage((!is_null($this->input->post('questionImage'.$i))) ? $this->input->post('questionImage'.$i) : '');
			$question->setAnswer();
			for($j = 0; $j < 4;$j++) 
			{
				$question->addAnswer(new Answer(!is_null($this->input->post('reponse'.$i.$j)) ? $this->input->post('reponse'.$i.$j) : '', 
												!is_null($this->input->post('reponseCorrect'.$i.$j)) ? true : false));
			}
			$quizz->addQuestion($question);
		}

		$quizz->addQuestion(new Question());
		$data['quizz'] = $quizz;
		$data['nb'] = $this->session->userdata('nbQuestions');
		$data['isNew'] = false;
		$data['id'] = $id;
		$this->load->view('templates/headerTeacherConnection.php');
		$this->load->view('teacherQuizzEdit.php', $data);
		$this->load->view('templates/footer.php');
	}

	/*Affiche les statistiques des quiz via l'user Id et les transmet à la vue.
	*/
	function displayStat()
	{
		$data['resultats'] = $this->CorrectionModel->getAllCorrection($this->session->userdata('id'));
		$this->load->view('templates/headerTeacherConnection.php');
		$this->load->view('teacherStatistics.php', $data);
		$this->load->view('templates/footer.php');
	}

	//Cette fonction détruit la session de l'utilisateur et redirige vers la page d'accueil
	function deconnection()
	{
		$this->session->sess_destroy();
		$this->load->view('templates/header.php',$data = array('isConnected' => false));
		$this->load->view('home.php');
		$this->load->view('templates/footer.php');
	}

	/*Après avoir terminé d'éditer un quiz on vérifie via un formulaire si les champs rentrés sont corrects puis
	* On demande au model d'enregistre le quiz dans la BD.
	*/
	function quizzEditor($id = null)
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		if(isset($_POST['addQuestion']))
		{
			$this->addQuestionDisplay(true, $id);
			return;
		}
		$this->form_validation->set_rules('nomQuizz', 'NomQuizz','required', array('required' => 'S\'il vous plaît entrez un nom valide.'));
		for($i = 0; !is_null($this->input->post('question'.$i));$i++) 
		{
			$this->form_validation->set_rules('question'.$i, 'Question'.$i,'required', array('required' => 'S\'il vous plaît entrez un intitulé valide.'));
			$this->form_validation->set_rules('reponse'.$i.'0', 'reponse'.$i.'0','required', array('required' => 'S\'il vous plaît entrez un intitulé valide pour cette reponse.'));
		}
		
		if($this->session->userdata('nbQuestions') == 0)
		{
			$this->addQuestionDisplay(false, $id);
			return;
		}
		$quizz = new Quizz();
		$quizz->setName($this->input->post('nomQuizz'));
		$quizz->setTime((is_numeric($this->input->post('timerH')) ? $this->input->post('timerH') * 3600 : 0) + (is_numeric($this->input->post('timerM')) ? $this->input->post('timerM') * 60: 0) + (is_numeric($this->input->post('timerS')) ? $this->input->post('timerS') : 0));
		$quizz->setStatus($this->input->post('statusQuizz'));

		$quizz->setQuestion();

		for($i = 0; !is_null($this->input->post('question'.$i));$i++) 
		{
			$question = new Question();
			$question->setTitle($this->input->post('question'.$i));
			$question->setImage($this->input->post('questionImage'.$i));
			$question->setAnswer();
			for($j = 0; !empty($this->input->post('reponse'.$i.$j));$j++)
			{
				if(is_null($this->input->post('reponseCorrect'.$i.$j)))
				{
					$question->addAnswer(new Answer($this->input->post('reponse'.$i.$j), 0));
				}
				else
				{
					$question->addAnswer(new Answer($this->input->post('reponse'.$i.$j), 1));
				}
			}
			$quizz->addQuestion($question);
		}
		$this->QuizzModel->addQuizz($quizz, $this->session->userdata('id'), $id);

		$this->teacherConnectionPage();
	}
	//Vérifie si l'utilisateur est connecté return un bouléen à la vue.
	public function isConnected() 
	{
		return $data = array('isConnected' => $this->session->has_userdata('prenom'));
	}

	//verifie la validité du mot de passe
	public function password_check() 
	{
		if ($this->input->post('first_password') !== $this->input->post('second_password')) 
		{
			$this->form_validation->set_message('password_check', 'les mots de passe ne sont pas les mêmes.');
			return FALSE;
		} else 
		{
			return TRUE;
		}
		
	}

	//verifie si le nom ou prenom est deja utilisé dans la bd.
	public function inscription_check() 
	{

		if(!is_null($this->UserModel->getUser($this->input->post('prenom'), $this->input->post('nom')))) 
		{
			$this->form_validation->set_message('inscription_check', 'Ces nom et prenom sont déja pris.');
			return FALSE;
		} 
		else 
		{
			return TRUE;
		}
	}

	//verifie si le mot de passe de l'utilisateur est corect via la clé de hash.
	public function connection_check() 
	{
		$user = $this->UserModel->getUser($this->input->post('prenom'), $this->input->post('nom'));

		if(is_null($user) || !password_verify($this->input->post('password'), $user->getHash())) 
		{
			$this->form_validation->set_message('connection_check', 'Nom, prenom ou mot de passe incorrect.');
			return FALSE;
		} else 
		{
			return TRUE;
		}
	}
}
