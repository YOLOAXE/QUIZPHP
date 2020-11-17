<br/>
<br/>
<br/>
<div class="container d-flex flex-column">
<?php
	if($quizz->getStatus() == "a" || $quizz->getStatus() == "l")
	{
		echo form_open('StudentManager/valideQuizz/'.$id,array('name' => 'validateQuizz'));
		echo '<div class="d-flex justify-content-around"><u><h1>'.$quizz->getName().'</h1></u></div><br>';

		if(!is_null($quizz->getQuestion())) 
		{
			for($i = 0; $i < $nb;$i++)
			{
				echo '<hr width="100%" class="text-secondary"><h4><div class="justify-content-around">Question n°'.($i+1).': '.$quizz->getQuestion()[$i]->getTitle().'</div></h4>';
				echo '<img src="'.$quizz->getQuestion()[$i]->getImage().'" class="img-thumbnail w-25"><br>';
				for($j = 0;$j < 4;$j++)
				{
					if(isset($quizz->getQuestion()[$i]->getAnswer()[$j]))
					{
						if (!empty($quizz->getQuestion()[$i]->getAnswer()[$j]->getText())) {
							echo '<h5><div class="checkbox"><label>';
							echo form_checkbox('reponseCorrect'.$i.$j,'correct');
							echo ' '.$quizz->getQuestion()[$i]->getAnswer()[$j]->getText().'<label></div></h5>';
						}
					}
				}
			}
		}
		echo'<br><br><br><div class="form-group d-flex">';
		$attributs = array('class' => 'btn btn-primary btn-xl float-right',
						   'type' => 'submit',
						   'value' => 'Soumettre',
							);
		echo form_submit($attributs);
		echo'</div>';
		echo form_close();
	}
	else if($quizz->getStatus() == "p")
	{
		echo '<br><br><br><br><br><div class="d-flex justify-content-around"><h1>Le quiz est encore en préparation...</h1></div>';
		echo '<div class="d-flex justify-content-around"><h2 id="Redirection">Vous allez etre rediriger dans 5s</h2></div><br><br><br><br><br><br><br><br><br><br><br>';
	}
	else
	{
		echo '<br><br><br><br><br><div class="d-flex justify-content-around"><h1>Le quiz a expiré...</h1></div>';
		echo '<div class="d-flex justify-content-around"><h2 id="Redirection">Vous allez être rediriger dans 5s</h2></div><br><br><br><br><br><br><br><br><br><br><br>';
	}
?>
</div>
