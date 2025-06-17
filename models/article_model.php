<?php
/**
* Class de la gestion des articles
* @author Christel Ehrhart
* @date 17/06/2025
*/

// Inclure le fichier de connexion PDO
require("models/connexion.php");

class ArticleModel extends Connexion{

	/**
	* Méthode permettant de récupérer les articles 
	* @param int $intLimit Nombre d'articles à récupérer 
	*/
	public function findAll(int $intLimit=0){
		// Ecrire la requête comme dans PHPMyAdmin
		$strQuery		= "SELECT article_title, article_img, article_content, article_createdate,
							CONCAT(user_name, ' ', user_firstname) AS article_author
							FROM articles
								INNER JOIN users ON article_creator = user_id
							ORDER BY article_createdate DESC ";
							
		if ($intLimit > 0){					
			$strQuery		.= "LIMIT ".$intLimit." OFFSET 0 ";
		}
		
		// On execute la requête et on demande tous les résultats
		$arrArticles	= $this->_db->query($strQuery)->fetchAll();		
		
		return $arrArticles;
	}
	
	
}