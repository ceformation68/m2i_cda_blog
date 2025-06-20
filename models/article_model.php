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
	public int $intPeriod		= 0;		
	public string $strDate		= "";
	public string $strStartDate	= "";
	public string $strEndDate	= "";	
	/**
	* Méthode permettant de récupérer les articles 
	* @param int $intLimit Nombre d'articles à récupérer 
	*/
	public function findAll(int $intLimit=0){

		// Ecrire la requête comme dans PHPMyAdmin
		$strQuery		= "SELECT article_id, article_title, article_img, article_content, 
							article_createdate, article_creator, 
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
		
		// Rechercher par mot clé
		if ($this->strKeywords != ""){
			$strQuery		.= $strAnd." (article_title LIKE '%".$this->strKeywords."%'
									OR article_content LIKE '%".$this->strKeywords."%')";
			$strAnd	= " AND ";
		}

		if ($this->intPeriod === 0){ // Recherche date exacte
			if ($this->strDate != ''){
				$strQuery	.= $strAnd." article_createdate = '".$this->strDate."'";
				$strAnd	= " AND ";
			}
		} else { // Recherche par période
			if ($this->strStartDate != '' && $this->strEndDate != ''){
				$strQuery	.= $strAnd." article_createdate 
								BETWEEN '".$this->strStartDate."' 
								AND '".$this->strEndDate."'";
				$strAnd	= " AND ";
			}
		}

		// Rechercher par auteur
		if ($this->intAuthor > 0){
			$strQuery		.= $strAnd." article_creator = ".$this->intAuthor;
			//$strAnd	= " AND ";
		}		
							
		$strQuery		.= " ORDER BY article_createdate DESC ";
							
		if ($intLimit > 0){					
			$strQuery		.= " LIMIT ".$intLimit." OFFSET 0 ";
		}
		//var_dump($strQuery);
		// On execute la requête et on demande tous les résultats
		$arrArticles	= $this->_db->query($strQuery)->fetchAll();		
		
		/* Possibilité de 'relier' l'entité article 
			et d'hydrater directement pour renvoyer un tableau d'objets */
		return $arrArticles;
	}
	
	public function findById(int $intId){
		$strQuery	= "SELECT article_id, article_title, article_img, article_content
					FROM articles 
					WHERE article_id = :id;";
		$strRqPrep	= $this->_db->prepare($strQuery);	
		$strRqPrep->bindValue(":id", $intId, PDO::PARAM_INT);
		$strRqPrep->execute();
		$arrArticle	= $strRqPrep->fetch();
		
		return $arrArticle;
	}
	
	public function insert(object $objArticle){
		
		// Ajouter les infos en BDD
		$strQuery	= "INSERT INTO articles (article_title, article_img, 		article_content, article_createdate, article_creator)
						VALUES ('".$objArticle->getTitle()."', '".$objArticle->getImg()."', '".$objArticle->getContent()."', NOW(), ".$_SESSION['id'].");";
								
		$this->_db->exec($strQuery);		
	}
	
	public function update(object $objArticle){
		// Modifier les infos en BDD
		$strQuery		= "UPDATE articles 
							SET article_title = :title,
								article_img = :image,
								article_content = :content";
		$strQuery	.=	" WHERE article_id = :id; ";
		$strRqPrep	= $this->_db->prepare($strQuery);	
		$strRqPrep->bindValue(":title", $objArticle->getTitle(), PDO::PARAM_STR);
		$strRqPrep->bindValue(":image", $objArticle->getImg(), PDO::PARAM_STR);
		$strRqPrep->bindValue(":content", $objArticle->getContent(), PDO::PARAM_STR);
		$strRqPrep->bindValue(":id", $objArticle->getId(), PDO::PARAM_INT);
			
		$strRqPrep->execute();			
	}
	
	public function delete(int $intId){
		// Supprimer les infos en BDD
		$strQuery	= "DELETE FROM articles 
						WHERE article_id = ".$intId;
								
		$this->_db->exec($strQuery);	
	}
}