<form method="post" enctype="multipart/form-data">
	<p>
		<label for="title">Titre</label>
		<input name="title" value="<?php echo $objArticle->getTitle(); ?>" id="title" class="form-control 
			<?php if(isset($arrErrors['title'])){ echo 'is-invalid'; } ?>" type="text" >
	</p>
	<p>
		<label>Image</label>
		<img src="assets/images/<?php echo $objArticle->getImg(); ?>" >
		<input name="img" class="form-control <?php if(isset($arrErrors['img'])){ echo 'is-invalid'; } ?>" type="file">
	</p>
	<p>
		<label>Contenu</label>
		<textarea name="content" 
		class="form-control <?php if(isset($arrErrors['content'])){ echo 'is-invalid'; } ?>"><?php echo $objArticle->getContent(); ?></textarea>
	</p>	
	<p>
		<input class="form-control btn btn-primary" type="submit" >
	</p>
</form>
