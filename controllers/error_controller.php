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
		
		public function error_404(){
			// Variables d'affichage
			$this->_arrData['strH1']	= "La page n'existe pas";
			$this->_arrData['strPar']	= "Page d'erreur";
			
			// Variables de fonctionnement
			$this->_arrData['strPage'] 	= "error_404";
			
			// Affichage
			$this->_display('error_404');
			
		}
		
	}