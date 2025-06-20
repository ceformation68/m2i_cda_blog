	<article class="col-md-6">
		<div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
			<div class="col p-4 d-flex flex-column position-static">
				<h3 class="mb-0"><?php echo htmlentities($objArticle->getTitle()); ?></h3>
				<div class="mb-1 text-body-secondary"><?php echo $objArticle->getDateFormat(); ?> (<?php echo $objArticle->getAuthor(); ?>)</div>
				<p class="mb-auto"><?php echo htmlentities($objArticle->getSummary()); ?> </p>
				<a href="#" class="icon-link gap-1 icon-link-hover">Lire la suite</a>
				
				<?php
					if (isset($_SESSION['id']) && $_SESSION['id'] != '') {
				?>
				<p><a alt="Modifier un article" 
						href="index.php?ctrl=article&action=edit_article&id=<?php echo $objArticle->getId(); ?>">Modifier l'article</a></p>
				<p><a alt="Supprimer un article" 
						href="index.php?ctrl=article&action=delete_article&id=<?php echo $objArticle->getId(); ?>">Supprimer l'article</a></p>
				<?php
					}
				?>				
				
			</div>
			<div class="col-auto d-none d-lg-block">
				<img class="bd-placeholder-img" width="200" height="250" alt="<?php echo $objArticle->getTitle();; ?>" src="assets/images/<?php echo $objArticle->getImg(); ?>">
			</div>
		</div>
	</article>	