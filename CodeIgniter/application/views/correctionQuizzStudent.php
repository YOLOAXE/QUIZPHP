<br>
<br>
<br>
<br>
<br>
<br>
<div class="container d-flex flex-column">
<?php
	echo '<div class="d-flex justify-content-around mb-0 text-success"><h1>CORRECTION</h1></div><br>';
    echo '<div class="d-flex justify-content-around mb-0"><h2><u>'.$nomQuizz.'</u></h2></div><br>';
    echo '<h3 class="text-info">'.$prenom.' '.$nom.'</h3>';
	if($note >= 10)
	{
		echo '<h3 class="text-success">Vous avez eu '.$note.'/20</h3>';
		echo '<h3 class="text-success">'.(($note/20)*100).'%</h3><br><br>';
	}
	else
	{
		echo '<h3 class="text-danger">Vous avez eu '.$note.'/20</h3>';
		echo '<h3 class="text-danger">'.(($note/20)*100).'% de réussite.</h3><br><br>';
	}
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
					if (!empty($quizz->getQuestion()[$i]->getAnswer()[$j]->getText())) 
					{
						if($quizz->getQuestion()[$i]->getAnswer()[$j]->getIsCorrect())
						{
							echo '<h5><div class="checkbox"><label class="text-success">';
							echo form_checkbox('reponseCorrect'.$i.$j,'disabled' ,'checked','disabled');
						}
						else
						{
							echo '<h5><div class="checkbox"><label class="text-danger">';
							echo form_checkbox('reponseCorrect'.$i.$j,'disabled','','disabled');
						}
						echo ' '.$quizz->getQuestion()[$i]->getAnswer()[$j]->getText().'<label></div></h5>';
					}
				}
			}
		}
	}
?>
</div>
<br>
<br>
<br>
<br>
<br>
<br>
