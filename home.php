<?php
	// Variables d'affichage
	$strH1		= "Accueil";
	$strPar		= "Page affichant les 4 derniers articles";
	
	// Variables de fonctionnement
	$strPage 	= "index";
	
	require("_partial/header.php");
	
	// Inclure le fichier de connexion PDO
	/*require("connexion.php");
	
	// Ecrire la requête comme dans PHPMyAdmin
	$strQuery		= "SELECT article_title, article_img, article_content, article_createdate,
							CONCAT(user_name, ' ', user_firstname) AS article_author
						FROM articles
							INNER JOIN users ON article_creator = user_id
						ORDER BY article_createdate DESC 
						LIMIT 4 OFFSET 0;";
	// On execute la requête et on demande tous les résultats
	$arrArticles	= $db->query($strQuery)->fetchAll();
	*/
	require_once("models/article_model.php"); // le fichier model des articles
	$objModelArticle	= new ArticleModel(); // instancier
	$arrArticles		= $objModelArticle->findAll(4); // Récupérer les articles
	
	//var_dump($arrArticles);
?>

<div class="row mb-2">
	<?php
		if (isset($_SESSION['id']) && $_SESSION['id'] != '') {
	?>
	<p><a alt="Ajouter un article" href="add_article.php">Ajouter un article</a></p>
	<?php
		}
	?>
	
	<?php
		require("entities/article_entity.php");
		// Parcourir le tableau des articles
		foreach($arrArticles as $arrDetArticle){
			// Utiliser la classe article
			$objArticle	= new Article();
			//$objArticle->setTitle($arrDetArticle['article_title']);
			$objArticle->hydrate($arrDetArticle);
			
			// Traitement avant affichage
			//$objDate 	= new DateTimeImmutable($arrDetArticle['article_createdate']);
			//$strDate 	= $objDate->format('d/m/Y');
			//$strSummary	= substr($arrDetArticle['article_content'], 0, 50).'...';
			// Affichage d'un article
			include("_partial/article.php");
		}
	?>
</div>

<?php
	include("_partial/footer.php");
?>
