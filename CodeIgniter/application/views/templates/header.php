<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		<meta name="google-site-verification" content="RyH7JiZNIHgOsAYagZuEOvBBNKBh5KNm3zbvVxpWOZ4" />
		<title>QuizzAcide</title>
		<link rel="icon" type="image/x-icon" href = "/assets/img/favicon.ico" />
		<script src="https://use.fontawesome.com/releases/v5.13.0/js/all.js" crossorigin="anonymous"></script>
		<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
		<link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
		<link rel = "stylesheet" type = "text/css" href="<?php echo base_url("css/styles.css"); ?>">
	</head>
	<body id="page-top">
		<nav class="navbar navbar-expand-lg bg-secondary text-uppercase fixed-top" id="mainNav">
			<div class="container">
				<a class="navbar-brand js-scroll-trigger" href="<?php echo base_url('HomeManager/index'); ?>">QuizAcide</a>
				<button class="navbar-toggler navbar-toggler-right text-uppercase font-weight-bold bg-primary text-white rounded" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">Menu <i class="fas fa-bars"></i></button>
				<div class="collapse navbar-collapse" id="navbarResponsive">
					<ul class="navbar-nav ml-auto">
					<?php
						if($isConnected)
						{
							echo '<li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href='.base_url("TeacherManager/deconnection").'>Déconnexion</a></li>
							      <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href='.base_url("TeacherManager/teacherConnectionPage").'>Home</a></li>';
						}
						else
						{
							echo '<li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href='.base_url("TeacherManager/teacherConnectionPage").'>Connexion</a></li>';     
						}
						echo '<li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href='.base_url("HomeManager/teacherInscriptionPage").'>Inscription</a></li>';
						echo '<li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href='.base_url("StudentManager/studentConnectionCorrectionPage").'>Correction du Quiz</a></li>';
						echo '<li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href='.base_url("StudentManager/studentConnectionQuizzPage").'>Connection à un Quiz</a></li>';
					?>
					</ul>
				</div>
			</div>
		</nav>
		<br>
		<br>
		<br>
