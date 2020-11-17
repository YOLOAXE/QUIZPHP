<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Contacts extends CI_Controller 
{
	public function view($id = null)
	{
		$this->load->model('model_contact');
		$this->load->library('table');
		$contacts = $this->model_contact->get_contact($id);
		$data=array('artiste' => $contacts);
		$this->load->view('home.php');
		/*$this->load->view('templates/header');
		$this->load->view('vue', $data);
		$this->load->view('templates/footer');*/
	}
}
?>
