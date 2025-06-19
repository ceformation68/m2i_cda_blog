<?php
	session_start();
	// Vérifier que l'utilisateur est connecté
	if (!isset($_SESSION['id']) || $_SESSION['id'] == '') {
		// Si l'utilisateur n'est pas connecté => page 403
		header("Location:error_403.php");
	}
	
	require("models/article_model.php");
	$objArticleModel 	= new ArticleModel;
	$objArticleModel->delete($_GET['id']);	
	
	$_SESSION['message']= "L'article a bien été supprimé";
	// Redirection vers la page d'accueil
	header("Location:index.php");