<header class="text-center" Style="background-color:  rgb(80, 200, 190)">
<br/>
<br/>
<br/>
<?php
if (!is_null($resultats)) 
{
	foreach($resultats as $resQuizz)
	{
		$i = 1;
		if(!is_null($resQuizz)) 
		{
			echo '
			<div class="container rounded" Style="background-color:  rgb(145, 155, 164)">
			<br>
			    <h1><u>'.$resQuizz[0]->nomQuizz.'</u></h1>
				<table class="table table-sm table-bordered table-secondary">
 		   			<thead>
    	  				<tr Style="background-color: rgba(20, 100, 130, 0.02)">
    	    				<th>#</th>
							<td><h5>Prenom</h5></td>
							<td><h5>nom</h5></td>
							<td><h5>Score</h5></td>
    	  				</tr>
    				</thead>
    			<tbody>';
			foreach ($resQuizz as $resultat) {
				echo '<tr>';
				echo '<th scope="row">'.$i.'</th>';
				echo '<td>'.$resultat->prenom.'</td>';
				echo '<td>'.$resultat->nom.'</td>';
				if($resultat->note >= 10)
				{
					echo '<td Style="background-color: rgba(0, 255, 0, 0.05)">'.$resultat->note.'</td>';
				}
				else
				{
					echo '<td Style="background-color: rgba(255, 0, 0, 0.05)">'.$resultat->note.'</td>';
				}
				echo '</tr>';
				$i++;
			}
			echo
			'</tbody>
  			</table>
  			<br>
			</div>';
		}
		echo "<br>";
	}
} 
else 
{
	echo "<h1>Vous n'avez aucun quiz ou aucun d'eux n'a été fait...</h1>";
	echo "<img class='w-25' src='".base_url('assets/img/c.png')."'/>";
}

?>

<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
</header>
