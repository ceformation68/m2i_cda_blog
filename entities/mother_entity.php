<?php

class Entity {
	
	protected string $_prefixe;

	private int $_id;
	
	/** 
	* Fonction qui permet de d'hydrater l'objet de manière "automatique"
	* @param array $arrData Tableau des données à hydrater
	*/
	public function hydrate(array $arrData){
		foreach($arrData as $key => $value){
			$strSetter = "set".ucfirst(str_replace($this->_prefixe.'_', '', $key));
			
			if (method_exists($this, $strSetter)){
				$this->$strSetter($value);
			}
		}
		/*$this->setTitle($arrData['article_title']);
		$this->setImg($arrData['article_img']);*/
	}	
	
	public function getId():int{
		return $this->_id;
	}
	public function setId(int $intId){
		$this->_id = $intId;
	}	
	
	
}