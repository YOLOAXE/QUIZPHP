<br>
<br>
<br>
<br>
<br>
<br>
<br>
<div class="container d-flex flex-column">
<?php 
		echo '<div class="p-2"><h1>Clé de correction: </h1></div>';
			$attributs = array(
					'class' => 'text-secondary p-2',
					'id'            => 'KeyInfo0',
					'type'          => 'text',
					'disabled'		=> '',
					'value'       => $key
			);
			echo form_input($attributs);
			echo '<div class="p-2"></div>';
			$attributs = array(
					'class' => 'btn btn-primary p-2',
					'id'            => 'ButtonGetKey0',
					'type'          => 'submit',
					'onclick'		=> 'CopyFonction(0)',
					'content'       => '<i class="fa fa-paste"></i>'
			);
		echo form_button($attributs);
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
<br>
<br>

