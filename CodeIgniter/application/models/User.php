<?php
/**
 * stock un utilisateur sous la forme d'un objet
 */
class User 
{
    private $firstName; // prenom de l'utilisateur
    private $lastName; // nom de l'utilisateur
    private $hash; // mot de passe hashé de l'utilisateur
    private $id; // identifiant de l'utilisateur

    public function __construct($fn, $ln, $h, $i) 
	{
        $this->firstName = $fn;
        $this->lastName = $ln;
        $this->hash = $h;
        $this->id = $i;
    }

    public function getFirstName() 
	{
        return $this->firstName;
    }

    public function getLastName() 
	{
        return $this->lastName;
    }

    public function getHash() 
	{
        return $this->hash;
    }

    public function getId() 
	{
        return $this->id;
    }
}
?>
