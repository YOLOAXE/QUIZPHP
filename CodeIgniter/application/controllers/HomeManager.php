<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class HomeManager extends CI_Controller //Controllers pour la gestion de la page d'accueil.
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('table');
		$this->load->library('session');//On charge la session pour vérifier si l'utilisateur est déjà connecté afin de lui proposer un bouton de redirection vers la page TeacherHome ainsi qu'une déconnexion
		$this->load->model('QuizzModel');
	}

	function index()
	{
		$this->load->view('templates/header.php', $this->isConnected());//On Charge l'header de la page avec des informations de connexion.
		$this->load->view('home.php');//page home
		$this->load->view('templates/footer.php');//footer
	}

	function teacherInscriptionPage()
	{
		$this->load->view('templates/header.php', $this->isConnected());//header
		$this->load->view('teacherInscription.php');//page d'inscription
		$this->load->view('templates/footer.php');//footer
	}

	public function isConnected() 
	{
		return $data = array('isConnected' => $this->session->has_userdata('prenom'));
		/*Si l'utilisateur c'est déjà connecté alors il possedera dans c'est variable de session un nom ainsi qu'un prénom,
		on peut donc l'identifier afin de lui proposer un bouton de redirection vers la page TeacherHome ainsi qu'une déconnexion*/
	}
}
