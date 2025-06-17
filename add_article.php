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
	
	$strTitle		= $_POST['title']??"";
	$arrImg			= $_FILES['img']??array();
	$strContent		= $_POST['content']??"";
	
	// Enlever les espaces avant et après => trim()
	$strTitle		= str_replace("<script>", "", $strTitle);
	$strTitle		= str_replace("</script>", "", $strTitle);
	$strTitle		= htmlspecialchars(trim($strTitle));

	$strContent		= htmlspecialchars(trim($strContent));

	// initialise le tableau des erreurs
	$arrErrors	= array(); 
	// Si le formulaire a été envoyé
	if (count($_POST) > 0){
		// Si l'utilisateur n'a pas saisi le titre
		if ($strTitle == ""){
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
		if ($strContent == ""){
			$arrErrors['content'] = "Le contenu est obligatoire";
		}		
		
		// Si le formulaires est OK
		if (count($arrErrors) == 0){
			// Récupérer l'extension du fichier
			$arrFileName 	= explode(".", $arrImg['name']);
			// L'extension est le dernier élément du tableau
			/*$strExtension	= $arrFileName[count($arrFileName)-1];*/
			$strExtension	= end($arrFileName);

			// Génération d'un nom de fichier avec date + aléatoire
			$objDate 		= new DateTimeImmutable();
			$strImageName	= $objDate->format('YmdHis').bin2hex(random_bytes(5)).".".$strExtension;
			
			/* TODO : Redimensionner l'image avant de la mettre sur le serveur */
			
			// Copier l'image
			if (!move_uploaded_file($arrImg['tmp_name'], 'assets/images/'.$strImageName)){
				$arrErrors['img'] = "Il y a une erreur sur le fichier image";
			}else{
				// Ajouter les infos en BDD
				
				// Inclure le fichier de connexion PDO
				require("connexion.php");
				
				// Ajouter les infos en BDD
				$strQuery	= "INSERT INTO articles (article_title, article_img, article_content, article_createdate, article_creator)
								VALUES ('".$strTitle."', '".$strImageName."', '".$strContent."', NOW(), ".$_SESSION['id'].");";
								
				//var_dump($strQuery);				
				$db->exec($strQuery);
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
		<input name="title" value="<?php echo $strTitle; ?>" id="title" class="form-control 
			<?php if(isset($arrErrors['title'])){ echo 'is-invalid'; } ?>" type="text" >
	</p>
	<p>
		<label>Image</label>
		<input name="img" class="form-control <?php if(isset($arrErrors['img'])){ echo 'is-invalid'; } ?>" type="file">
	</p>
	<p>
		<label>Contenu</label>
		<textarea name="content" 
		class="form-control <?php if(isset($arrErrors['content'])){ echo 'is-invalid'; } ?>"><?php echo $strContent; ?></textarea>
	</p>	
	<p>
		<input class="form-control btn btn-primary" type="submit" >
	</p>
</form>

<?php
	include("_partial/footer.php");
?>