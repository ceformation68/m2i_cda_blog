<?php

	// Récupérer les infos dans l'url
	$strController 	= $_GET['ctrl']??"article";
	$strAction		= $_GET['action']??"home";
	
	require_once("controllers/".$strController."_controller.php");
	$strCtrlName	= ucfirst($strController)."Ctrl";
	$objCtrl 		= new $strCtrlName();
	$objCtrl->$strAction();
	
	/* TODO : Vérifier l'existance du fichier, de la classe et de la méthode
	=> Sinon redirection 404 */