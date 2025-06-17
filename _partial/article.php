	<article class="col-md-6">
		<div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
			<div class="col p-4 d-flex flex-column position-static">
				<h3 class="mb-0"><?php echo $arrDetArticle['article_title']; ?></h3>
				<div class="mb-1 text-body-secondary"><?php echo $strDate; ?> (<?php echo $arrDetArticle['article_author']; ?>)</div>
				<p class="mb-auto"><?php echo $strSummary; ?> </p>
				<a href="#" class="icon-link gap-1 icon-link-hover stretched-link">Lire la suite</a>
			</div>
			<div class="col-auto d-none d-lg-block">
				<img class="bd-placeholder-img" width="200" height="250" alt="<?php echo $arrDetArticle['article_title']; ?>" src="assets/images/<?php echo $arrDetArticle['article_img']; ?>">
			</div>
		</div>
	</article>	