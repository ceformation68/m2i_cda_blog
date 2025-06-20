<?php

	class MotherCtrl{
		
		protected array $_arrData = [];
		
		protected function _display(string $strView){
			// Extraction des variables et de leurs données
			extract($this->_arrData); 

			include("views/_partial/header.php");
			include("views/".$strView.".php");
			include("views/_partial/footer.php");
		}
		
		
		
		
	}