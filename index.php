<?php
	session_start();

	// Récupérer les infos dans l'url
	$strController 	= $_GET['ctrl']??"article";
	$strAction		= $_GET['action']??"home";
	
	require_once("controllers/mother_controller.php");
	
	$boolError	= false;
	
	$strCtrlFile = "controllers/".$strController."_controller.php";
	if (file_exists($strCtrlFile)){ // Vérifier la présence du fichier
		require_once($strCtrlFile);
		$strCtrlName	= ucfirst($strController)."Ctrl";
		if (class_exists($strCtrlName)){ // Vérifier le nom de la classe
			$objCtrl 	= new $strCtrlName();
			if (method_exists($objCtrl, $strAction)){ // Vérifier la présence de la méthode dans la classe
				$objCtrl->$strAction();
			}else{
				// Si la page n'existe pas
				$boolError = true;
			}
		}else{
			// Si la page n'existe pas
			$boolError = true;
		}
	}else{
		// Si la page n'existe pas
		$boolError = true;
	}
	
	if ($boolError){
		// Si la page n'existe pas
		header("Location:index.php?ctrl=error&action=error_404");
	}
	