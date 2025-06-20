<?php

	/**
	* Controller qui s'occupe des pages de contenu
	*/
	class PageCtrl {
		
		
		public function about(){
			// Variables d'affichage
			$strH1		= "A propos";
			$strPar		= "Page de contenu";
			
			// Variables de fonctionnement
			$strPage 	= "about";
			
			include("views/_partial/header.php");
			include("views/about.php");
			include("views/_partial/footer.php");
		}

		public function mentions(){
		}

		public function contact(){
		}

	}