<?php
	
	/**
	* Controller qui s'occupe des pages de contenu
	*/
	class PageCtrl extends MotherCtrl{
		
		public function about(){
			// Variables d'affichage
			$this->_arrData['strH1']	= "A propos";
			$this->_arrData['strPar']	= "Page de contenu";
			
			// Variables de fonctionnement
			$this->_arrData['strPage'] 	= "about";
			
			// Affichage
			$this->_display('about');
		}

		public function mentions(){
			// Variables d'affichage
			$this->_arrData['strH1']	= "Mentions légales";
			$this->_arrData['strPar']	= "Page de contenu";
			
			// Variables de fonctionnement
			$this->_arrData['strPage'] 	= "mentions";
			
			// Affichage
			$this->_display('mentions');
		}

		public function contact(){
			// Variables d'affichage
			$this->_arrData['strH1']	= "Contact";
			$this->_arrData['strPar']	= "Page de contact";
			
			// Variables de fonctionnement
			$this->_arrData['strPage'] 	= "contact";
			
			// Affichage
			$this->_display('contact');
		}

	}