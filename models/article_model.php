<?php
/**
* Class de la gestion des articles
* @author Christel Ehrhart
* @date 17/06/2025
*/

// Inclure le fichier de connexion PDO
require("models/connexion.php");

class ArticleModel extends Connexion{

	/* Elements de recherche */
	public string $strKeywords 	= "";
	public int $intAuthor 		= 0;
	
	/**
	* Méthode permettant de récupérer les articles 
	* @param int $intLimit Nombre d'articles à récupérer 
	*/
	public function findAll(int $intLimit=0){
		var_dump($_POST);
		// Ecrire la requête comme dans PHPMyAdmin
		$strQuery		= "SELECT article_title, article_img, article_content, article_createdate,
							CONCAT(user_name, ' ', user_firstname) AS article_author
							FROM articles
								INNER JOIN users ON article_creator = user_id";
		// 	avec condition
		/*if (isset($_POST['keywords'])){
			$strKeywords	= $_POST['keywords'];
		}else{
			$strKeywords	= "";
		}*/
		// écriture ternaire
		//$strKeywords	= (isset($_POST['keywords']))?$_POST['keywords']:"";
		// opérateur de coalescence de null
		//$strKeywords	= $_POST['keywords']??$_GET['keywords']??"";
		//$strKeywords	= $_POST['keywords']??"";
		$strAnd	= " WHERE ";
		
		if ($this->strKeywords != ""){
			$strQuery		.= $strAnd." (article_title LIKE '%".$this->strKeywords."%'
									OR article_content LIKE '%".$this->strKeywords."%')";
			$strAnd	= " AND ";
		}
		if ($this->intAuthor > 0){
			$strQuery		.= $strAnd." article_creator = ".$this->intAuthor;
			$strAnd	= " AND ";
		}		
							
		$strQuery		.= " ORDER BY article_createdate DESC ";
							
		if ($intLimit > 0){					
			$strQuery		.= " LIMIT ".$intLimit." OFFSET 0 ";
		}
		var_dump($strQuery);
		// On execute la requête et on demande tous les résultats
		$arrArticles	= $this->_db->query($strQuery)->fetchAll();		
		
		return $arrArticles;
	}
	
	
}