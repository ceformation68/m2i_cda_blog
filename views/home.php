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


