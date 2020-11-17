<br/>
<br/>
<br/>
<div class="container d-flex flex-column">
<h1><div class="d-flex justify-content-around"></div>Editeur de Quizz</h1>
<?php
echo '<h3><div class="d-flex justify-content-around">Nombre de questions '.$nb.'</div></h3>';
echo validation_errors();
echo form_open('TeacherManager/quizzEditor/'.(isset($id) ? $id : ''));
$attributs = array('class' => 'form-control',
					'id' => 'nomQuizzID',
					'name' => 'nomQuizz',
					'placeholder' => 'Nom du quiz',
					'required' => 'required',
					'value' => ($isNew === true ? $quizz->getName() : set_value('nomQuizz',null, FALSE)));
echo '<h5><div class="justify-content-around">Titre du quiz</div></h5>';
echo form_input($attributs);
echo form_error('nomQuizz','<small id="NomQuizz" class="text-danger">','</small>');
echo '
<br>
<h5><div class="justify-content-around">Temps imparti</div></h5>
<div class="input-group d-flex justify-content-around">
<h5><div class="p-2">Heure:</div></h5>
';
$attributs = array('class' => 'form-control',
					'id' => 'timerHID',
					'name' => 'timerH',
					'type' => 'number',
					'step' => '1',
					'min' => '0',
					'placeholder' => 'Heure',
					'value' => ($isNew === true ? floor(($quizz->getTime() % (3600 * 24))/(3600)) : set_value('timerH', null, FALSE)));
echo form_input($attributs);
echo form_error('timerH','<small id="timerHID" class="text-danger">','</small>');
echo '<h5><div class="p-2">Minute:</div></h5>';
$attributs = array('class' => 'form-control',
					'id' => 'timerMID',
					'name' => 'timerM',
					'type' => 'number',
					'step' => '1',
					'min' => '0',
					'placeholder' => 'Minute',
					'value' => ($isNew === true ? floor(($quizz->getTime() % 3600)/60) : set_value('timerM', null, FALSE)));
echo form_input($attributs);
echo form_error('timerM','<small id="timerMID" class="text-danger">','</small>');
echo '<h5><div class="p-2">Seconde:</div></h5>';
$attributs = array('class' => 'form-control',
					'id' => 'timerSID',
					'name' => 'timerS',
					'type' => 'number',
					'step' => '1',
					'min' => '0',
					'placeholder' => 'Seconde',
					'value' => ($isNew === true ? floor($quizz->getTime() % 60) : set_value('timerS', null, FALSE)));
echo form_input($attributs);
echo form_error('timerS','<small id="timerSID" class="text-danger">','</small>');
echo '
</div>
';
$attributs = array('class' => 'form-control',
					'id' => 'statusQuizzID',
					'name' => 'statusQuizz',				
					'required' => 'required');
$options = array('p' => 'En preparation...',
				'a' => 'Activé.',
				'e' => 'Expiré',
				'l' => 'Libre');
echo '<h5><div class="justify-content-around">Status</div></h5>';
echo form_dropdown('statusQuizz',$options,($isNew === true ? $quizz->getStatus() : set_value('nomQuizz', null, FALSE)),$attributs);
echo form_error('statusQuizz','<small id="statusQuizzID" class="text-danger">','</small>');
echo'<br><hr noshade/><br>';
if(!is_null($quizz->getQuestion())) 
{
	for($i = 0; $i < $nb;$i++)
	{
		if($i == ($nb-1))
		{
			echo '<div id="target"></div>';
		}
		$attributsQuestion = array('class' => 'form-control',
						'name' => 'question'.$i,
						'id' => 'questionID'.$i,
						'placeholder' => 'intitulé',
						'required' => 'required', 
						'value' => ($isNew === true ? $quizz->getQuestion()[$i]->getTitle() : set_value('question'.$i, null, FALSE)));
		echo '<h5><div class="justify-content-around">Question n°'.($i+1).'</div></h5>';
		echo form_input($attributsQuestion);
		echo form_error('question'.$i,'<small id=questionID'.$i.' class="text-danger">','</small>');

		$attributsQuestionImage = array('class' => 'form-control',					
						'name' => 'questionImage'.$i,
						'id' => 'questionImageID'.$i,
						'placeholder' => 'url',			
						'value' => ($isNew === true ? $quizz->getQuestion()[$i]->getImage() : set_value('questionImage'.$i, null, FALSE)));
		echo '<h5><div class="justify-content-around">Image</div></h5>';
		echo form_input($attributsQuestionImage);
		echo form_error('questionImage'.$i,'<small id=questionImageID'.$i.' class="text-danger">','</small>');
		for($j = 0;$j < 4;$j++)
		{
			echo '<h6><div class="justify-content-around">Réponse n°'.($j+1).'</div></h6>';
			echo '<div class="input-group mb-3">
					<div class="input-group-prepend">
						<div class="input-group-text">';
			echo form_checkbox('reponseCorrect'.$i.$j,'correct',isset($quizz->getQuestion()[$i]->getAnswer()[$j]) ? $quizz->getQuestion()[$i]->getAnswer()[$j]->getIsCorrect() ? 'checked' : '' : '');
			echo '</div>
			</div>';
			$attributsReponse = array('class' => 'form-control',
						'name' => 'reponse'.$i.$j,
						'id' => 'reponseID'.$i.$j,
						'placeholder' => 'intitulé', 
						'value' => isset($quizz->getQuestion()[$i]->getAnswer()[$j]) ? $quizz->getQuestion()[$i]->getAnswer()[$j]->getText()  : '');
			if($j==0)
			{
				$attributsReponse['required'] = 'required';
			} 
			echo form_input($attributsReponse);
			echo form_error('reponse'.$i.$j,'<small id=reponseID'.$i.$j.' class="text-danger">','</small>');
			echo '</div>';
		}
		echo'<br><hr noshade/><br>';
	}
}
echo'<br><br><br><br><br><div class="form-group">';
if($this->session->userdata('nbQuestions') > 0)
{
	echo form_submit('submit', 'Enregistrer', 'class="btn btn-primary btn-xl float-right"');
}
echo form_submit('addQuestion', 'Ajouter une question', 'class="btn btn-primary btn-xl"');
echo'</div>';
echo form_close();
?>
</div>
<br/>
<br/>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<?php 
if(!$isNew)
{
	echo "<script type='text/javascript'>
	$(window).load(function() {
	$('html, body').animate({ scrollTop: $('#target').offset().top }, 1000);
	});
	</script>"; 
}
?>
