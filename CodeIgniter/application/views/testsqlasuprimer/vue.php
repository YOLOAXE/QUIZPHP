<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(is_array($artiste))
{
	foreach($artiste as $a)
	{
			echo $a->nom." ".$a->prenom."/".$a->anneeNaiss."<br>";
	}
}
else
{
	if(isset($artiste->nom))
	{
		echo $artiste->nom."<br>";
	}
}
?>
