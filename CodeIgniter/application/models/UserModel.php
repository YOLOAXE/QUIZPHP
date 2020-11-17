<?php 
if ( ! defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

require 'User.php';

class UserModel extends CI_Model 
{
	public function __construct()
	{
		$this->load->database();
    }
    /**
     * enregistre un utilisateur dasn la bse de donnée
     * @param $us : objet de type utilisateur à enregistrer
     */
	public function setNewUser($us)
	{
        $data = array(
            'prenom' => $us->getFirstName(),
            'nom' => $us->getLastName(),
            'motDePasse' => $us->getHash(),
        );

        $this->db->insert('User', $data);
    }
    
    /**
     * retourne un objet de type utilisateur correspondant au information données en paramètre
     * @param $firstName : prenom
     * @param $lastName : nom
     * @return : l'utilisateur sous la forme d'un objet si il existe, null sinon 
     */
    public function getUser($firstName, $lastName) 
	{
            $this->db->from('User');
            $this->db->where('prenom', $firstName);
            $this->db->where('nom', $lastName);
            $query = $this->db->get();
            $res = $query->result();
            if (!empty($res)) {
                $res = $res[0];
                return new User($res->prenom, $res->nom, $res->motDePasse, $res->userID);
            }
        return null;
    }
}
?>
