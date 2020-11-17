<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		<title>QuizzAcide</title>
		<link rel="icon" type="image/x-icon" href = "<?php echo base_url(); ?>assets/img/favicon.ico" />
		<script src="https://use.fontawesome.com/releases/v5.13.0/js/all.js" crossorigin="anonymous"></script>
		<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
		<link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
		<link rel = "stylesheet" type = "text/css" href = "<?php echo base_url('css/styles.css'); ?>">
	</head>
	<body id="page-top">
		<nav class="navbar navbar-expand-lg bg-secondary text-uppercase fixed-top" id="mainNav">
			<div class="container">
				<a class="navbar-brand js-scroll-trigger" href="<?php echo base_url('HomeManager'); ?>">QuizAcide</a>
				<button class="navbar-toggler navbar-toggler-right text-uppercase font-weight-bold bg-primary text-white rounded" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">Menu <i class="fas fa-bars"></i></button>
				<div class="collapse navbar-collapse" id="navbarResponsive">
					<ul class="navbar-nav ml-auto">
						<li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="<?php echo base_url('StudentManager/studentConnectionCorrectionPage'); ?>">Correction du Quiz</a></li>
						<li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="<?php echo base_url('StudentManager/studentConnectionQuizzPage'); ?>">Connection à un Quiz</a></li>
						<li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" id="Timer"></a></li>
					</ul>
				</div>
			</div>
		</nav>
		<br>
		<br>
		<br>
<?php

$startTime = time()-$this->session->userdata('Start_Time');

echo
'
<script>
var currentTime = '.$startTime.';
var totalTime = '.$quizz->getTime().';
var status = "'.$quizz->getStatus().'";
var timeWaitR = 5;
	var x = setInterval(function() 
	{
		currentTime += 1;
		var hours = Math.floor((currentTime % (3600 * 24))/(3600));
		var minutes = Math.floor((currentTime % 3600)/60);
		var seconds = Math.floor(currentTime % 60);

		var Thours = Math.floor((totalTime % (3600 * 24))/(3600));
		var Tminutes = Math.floor((totalTime % 3600)/60);
		var Tseconds = Math.floor(totalTime % 60);

		document.getElementById("Timer").innerHTML = hours + "h " + minutes + "m " + seconds + "s " + " / " + Thours + "h " + Tminutes + "m " + Tseconds + "s ";

		if (currentTime >= totalTime || status != "a" && status != "l") 
		{
			if(status == "a" || status == "l")
			{
				clearInterval(x);
				document.getElementById("Timer").innerHTML = "EXPIRÉ";
				document.forms["validateQuizz"].submit();
			}
			else if(status == "p")
			{
				document.getElementById("Timer").innerHTML = "En preparation";
				document.getElementById("Redirection").innerHTML = "Vous allez etre rediriger dans " + timeWaitR + "s";
				timeWaitR--;
			}
			else
			{
				document.getElementById("Timer").innerHTML = "Quizz non disponible";
				document.getElementById("Redirection").innerHTML = "Vous allez etre rediriger dans " + timeWaitR + "s";
				timeWaitR--;
			}
			if(timeWaitR < 0)
			{
				window.location.href = "'.base_url('HomeManager/index').'";
			}
		}
	}, 1000);
</script>
';
?>
