<?php

	class ErrorCtrl extends MotherCtrl{

		public function error_403(){
			// Variables d'affichage
			$this->_arrData['strH1']	= "Vous n'êtes pas autorisé";
			$this->_arrData['strPar']	= "Page d'erreur";
			
			// Variables de fonctionnement
			$this->_arrData['strPage'] 	= "error_403";
			
			// Affichage
			$this->_display('error_403');
			
		}
		
		
	}