<?php
	// Initialisation de la session (car pas de header inclus) 
	session_start();
	// Destruction de la session
	session_destroy();
	// Redirection vers la page d'accueil
	header("Location:index.php");
?>