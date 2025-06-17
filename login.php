<?php
	// Variables d'affichage
	$strH1		= "Se connecter";
	$strPar		= "Page permettant de se connecter";
	
	// Variables de fonctionnement
	$strPage 	= "login";
	
	include("_partial/header.php");
	
	$strMail		= $_POST['mail']??"";
	$strPwd			= $_POST['pwd']??"";
	
	// Enlever les espaces avant et après => trim()
	$strMail		= strtolower(trim($strMail));
	
	// initialise le tableau des erreurs
	$arrErrors	= array(); 
	// Si le formulaire a été envoyé
	if (count($_POST) > 0){
		// Si l'utilisateur n'a pas saisi son mail
		if ($strMail == ""){
			$arrErrors['mail'] = "Le mail est obligatoire";
		}		
		
		// Si l'utilisateur n'a pas saisi son mot de passe
		if ($strPwd == ""){
			$arrErrors['pwd'] = "Le mot de passe est obligatoire";
		}
		
		// Si le formulaires est OK
		if (count($arrErrors) == 0){
			// Vérifier les infos en BDD
			
			// Inclure le fichier de connexion PDO
			require("connexion.php");
			$strQuery	= "SELECT user_firstname, user_name, user_pwd, user_id
							FROM users 
							WHERE user_mail = :mail;";
			// La requête est préparée
			$strRqPrep	= $db->prepare($strQuery);
			
			// La définition de la valeur du paramètre et de son type
			$strRqPrep->bindValue(":mail", $strMail, PDO::PARAM_STR);

			// On execute la requête et on demande le résultat
			$strRqPrep->execute();
			//var_dump($strRqPrep->debugDumpParams());
			$arrUser	= $strRqPrep->fetch();
			
			// Si aucun utilisateur trouvé
			if($arrUser === false){
				$arrErrors[] = "Erreur dans la connexion";
			}else{
				// On vérifie le mot de passe
				if (password_verify($strPwd, $arrUser['user_pwd'])) {
					// Ajouter les informations de l'utilisateur => en session
					$_SESSION['prenom'] = $arrUser['user_firstname'];
					$_SESSION['id'] 	= $arrUser['user_id']; // La clé peut être renommée
					$_SESSION['nom'] 	= $arrUser['user_name'];
					$_SESSION['message']= "Vous êtes bien connecté";
					// Redirection vers la page d'accueil
					header("Location:index.php");
				}else{
					$arrErrors[] = "Erreur dans la connexion";
				}
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
<form method="post">
	<p>
		<label for="mail">Mail</label>
		<input name="mail" value="<?php echo $strMail; ?>" id="mail" class="form-control 
			<?php if(isset($arrErrors['mail'])){ echo 'is-invalid'; } ?>" type="text" >
	</p>
	<p>
		<label for="pwd">Mot de passe</label>
		<input name="pwd" id="pwd" class="form-control 
			<?php if(isset($arrErrors['pwd'])){ echo 'is-invalid'; } ?>" type="password" >
	</p>
	<p>
		<input class="form-control btn btn-primary" type="submit" >
	</p>
</form>

<a href="forgot_pwd.php">Mot de passe oublié</a>

<?php
	include("_partial/footer.php");
?>