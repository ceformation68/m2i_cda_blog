<?php
require_once("entities/mother_entity.php");

class Article extends Entity{
	// Attributs
	private string $_title="";
	private string $_img;
	private string $_content="";
	private string $_createdate;
	private int $_creator;
	private string $_author;
	
	public function __construct(){
		$this->_prefixe = "article";
	}
	
	// Getters et Setters
	public function getTitle():string{
		return $this->_title;
	}
	public function setTitle(string $strTitle){
		$strTitle	= str_replace("<script>", "", $strTitle);
		$strTitle	= str_replace("</script>", "", $strTitle);
		$strTitle	= trim($strTitle);
		$this->_title = $strTitle;
	}
	public function getImg():string{
		return $this->_img;
	}
	public function setImg(string $strImg){
		$this->_img = $strImg;
	}
	public function setNewImg(string $strImg){
		// Récupérer l'extension du fichier
		$arrFileName 	= explode(".", $strImg);
		// L'extension est le dernier élément du tableau
		/*$strExtension	= $arrFileName[count($arrFileName)-1];*/
		$strExtension	= end($arrFileName);

		// Génération d'un nom de fichier avec date + aléatoire
		$objDate 		= new DateTimeImmutable();
		$strImageName	= $objDate->format('YmdHis').bin2hex(random_bytes(5)).".".$strExtension;

		$this->_img = $strImageName;
	}
	
	public function getContent():string{
		return $this->_content;
	}
	public function getSummary(int $intNbCar = 50){
		$strSummary	= substr($this->_content, 0, $intNbCar).'...';
		return $strSummary;
	}
	public function setContent(string $strContent){
		$strContent		= htmlspecialchars(trim($strContent));
		$this->_content = $strContent;
	}
	public function getCreatedate():string{
		return $this->_createdate;
	}
	/**
	* Getter qui permet de formatter la date de création
	* @param string $strFormat le format de sortie, par défaut 'd/m/Y'
	* @return string la date formattée
	*/
	public function getDateFormat(string $strFormat = 'd/m/Y'):string{
		$objDate 	= new DateTimeImmutable($this->_createdate);
		$strDate 	= $objDate->format($strFormat);
		return $strDate;
	}
	public function setCreatedate(string $strCreatedate){
		$this->_createdate = $strCreatedate;
	}
	public function getCreator():int{
		return $this->_creator;
	}
	public function setCreator(int $intCreator){
		$this->_creator = $intCreator;
	}
	public function getAuthor():string{
		return $this->_author;
	}
	public function setAuthor(string $strAuthor){
		$this->_author = $strAuthor;
	}
	
	
}