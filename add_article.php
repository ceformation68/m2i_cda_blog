<?php
	// Variables d'affichage
	$strH1		= "Ajouter un article";
	$strPar		= "Page permettant d'ajouter un article";
	
	// Variables de fonctionnement
	$strPage 	= "add_article";

	include("_partial/header.php");
	
	// Vérifier que l'utilisateur est connecté
	if (!isset($_SESSION['id']) || $_SESSION['id'] == '') {
		// Si l'utilisateur n'est pas connecté => page 403
		header("Location:error_403.php");
	}

	// Tableau des types MIME autorisés
	$arrMimeTypes 	= array('image/jpeg', 'image/png');
	$arrImg			= $_FILES['img']??array();

	require("entities/article_entity.php");
	$objArticle = new Article;
	
	// initialise le tableau des erreurs
	$arrErrors	= array(); 
	// Si le formulaire a été envoyé
	if (count($_POST) > 0){
		$objArticle->hydrate($_POST);

		// Si l'utilisateur n'a pas saisi le titre
		if ($objArticle->getTitle() == ""){
			$arrErrors['title'] = "Le titre est obligatoire";
		}
		// Si l'utilisateur n'a pas choisi d'image
		if ($arrImg['error'] == 4){
			$arrErrors['img'] = "L'image est obligatoire";
		/*}else if ( 
			($arrImg['type'] != 'image/jpeg') && ($arrImg['type'] != 'image/png')
		){*/
		}else if (!in_array($arrImg['type'], $arrMimeTypes)){
			$arrErrors['img'] = "L'image n'est pas au bon format";
		}else if ($arrImg['error'] > 0){
			$arrErrors['img'] = "Il y a une erreur sur le fichier image";
		}
		
		// Si l'utilisateur n'a pas saisi de contenu
		if ($objArticle->getContent() == ""){
			$arrErrors['content'] = "Le contenu est obligatoire";
		}		
		
		// Si le formulaires est OK
		if (count($arrErrors) == 0){
			$objArticle->setNewImg($arrImg['name']);

			/* TODO : Amélioration - Traiter le nom de l'image dans un setter à part */
			/* TODO : Redimensionner l'image avant de la mettre sur le serveur */
			
			// Copier l'image
			if (!move_uploaded_file($arrImg['tmp_name'], 'assets/images/'.$objArticle->getImg())){
				$arrErrors['img'] = "Il y a une erreur sur le fichier image";
			}else{
				//$objArticle->setImg($strImageName);
				
				// Ajouter les infos en BDD
				require("models/article_model.php");
				$objArticleModel = new ArticleModel;
				$objArticleModel->insert($objArticle);
				
				$_SESSION['message']= "L'article a bien été ajouté";
				// Redirection vers la page d'accueil
				header("Location:index.php");
			}
		}		
	}
?>
<?php
	/* Affichage des erreurs */
	if (count($arrErrors) > 0){
		echo "<div class='alert alert-danger'>";
		foreach ($arrErrors as $strError){
			echo "<p class='mb-0'>".$strError."</p>";
		}
		echo "</div>";
	}
?>
<form method="post" enctype="multipart/form-data">
	<p>
		<label for="title">Titre</label>
		<input name="title" value="<?php echo $objArticle->getTitle(); ?>" id="title" class="form-control 
			<?php if(isset($arrErrors['title'])){ echo 'is-invalid'; } ?>" type="text" >
	</p>
	<p>
		<label>Image</label>
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

<?php
	include("_partial/footer.php");
?>