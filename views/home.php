<div class="row mb-2">
	<?php
		if (isset($_SESSION['id']) && $_SESSION['id'] != '') {
	?>
	<p><a alt="Ajouter un article" href="index.php?ctrl=article&action=add_article">Ajouter un article</a></p>
	<?php
		}
	?>
	
	<?php
		// Parcourir le tableau des articles = boucle d'affichage
		foreach($arrArticles as $objArticle){
			include("_partial/article.php");
		}
	?>
</div>


