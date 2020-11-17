<header class="text-center" Style="background-color:  rgb(80, 200, 190)">
<br>
<br>
<br>
<br>
<div class="container d-flex flex-column">
<?php 
	echo '<div class="d-flex justify-content-around mb-0"><h2>Bienvenue '.$this->session->userdata('prenom').' '.$this->session->userdata('nom').'</h2></div>
		<div class="divider-custom divider-dark">
		<div class="divider-custom-line"></div>
		<div class="divider-custom-icon"><i class="fa fa-cog fa-spin fa-2x fa-fw"></i></div>
		<div class="divider-custom-line"></div>
	</div>';
	if (!is_null($tab)) 
	{
		foreach ($tab as $element) 
		{
			echo '
			<div class="d-flex">
				<div class="mr-auto p-2"><h3>Quiz : '.$element['nom'].'</h3></div>';

			echo form_open('TeacherManager/quizzEditorDisplay/'.$element['id']);
			$attributs = array(
					'class' => 'btn btn-primary p-2',
					'id'            => 'buttonEdit'.$element['id'],
					'type'          => 'submit',
					'content'       => '<i class="fa fa-cogs"></i>'
			);
			echo form_button($attributs);
			echo form_close();
			echo '<div class="p-2"></div>';
			echo form_open('TeacherManager/quizzEditorDelete/'.$element['id']);
			$attributs = array(
					'class' => 'btn btn-primary p-2',
					'id'            => 'buttonDelete'.$element['id'],
					'type'          => 'submit',
					'content'       => '<i class="fa fa-trash"></i>'
			);
			echo form_button($attributs);
			echo form_close();
			echo '</div>
			<div class="d-flex">
			<div class="p-2"><h4>État : </h4></div>';
			if($element['state'] == 'a')
			{
				echo '<div class="mr-auto text-success p-2"><h4>Activé</h4></div>';
			}
			else if($element['state'] == 'p')
			{
				echo '<div class="mr-auto text-info p-2"><h4>En Preparation</h4></div>';
			}
			else if($element['state'] == 'l')
			{
				echo '<div class="mr-auto text-dark p-2"><h4>Libre</h4></div>';
			}
			else
			{
				echo '<div class="mr-auto text-danger p-2"><h4>Expiré</h4></div>';
			}
			echo '<div class="p-2"><h4>Clé : </h4></div>';
			$attributs = array(
					'class' => 'text-secondary p-2',
					'style' => 'height: 0%; width: 26.5%; font-size: 1.4em;',
					'id'            => 'KeyInfo'.$element['id'],
					'type'          => 'text',
					'disabled'		=> '',
					'value'       => $element['key']
			);
			echo form_input($attributs);
			echo '<div class="p-2"></div>';
			$attributs = array(
					'class' => 'btn btn-primary p-2',
					'id'            => 'ButtonGetKey'.$element['id'],
					'style'			=> 'height: 0%;',
					'type'          => 'submit',
					'onclick'		=> 'CopyFonction('.$element['id'].')',
					'content'       => '<i class="fa fa-paste"></i>'
			);
			echo form_button($attributs);
			echo '</div>
			<hr style="width: 100%;" class="text-secondary">';
		}
	}
	else 
	{
		echo '<h3><div class="d-flex justify-content-around">Aucun quiz...</div></h3><hr noshade/><br>';
	}
	echo form_open('TeacherManager/quizzEditorDisplay/');
	echo '<div class="d-flex justify-content-around">';
	$attributs = array(
					'class' => 'btn btn-primary p-3',
					'id'            => 'buttonAddQuizz',
					'type'          => 'submit',
					'content'       => '<i class="fa fa-plus-square"></i> Ajouter'
	);
	echo form_button($attributs);
	echo'</div>';
	echo form_close();
?>
</div>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
</header>


		





