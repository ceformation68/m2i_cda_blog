<?php
	// Variables d'affichage
	$strH1		= "Créer un compte";
	$strPar		= "Page permettant de créer son compte";
	
	// Variables de fonctionnement
	$strPage 	= "create_account";
	
	include("_partial/header.php");
	//var_dump($_POST);
	$strName		= $_POST['name']??"";
	$strFirstname	= $_POST['firstname']??"";
	$strMail		= $_POST['mail']??"";
	$strPwd			= $_POST['pwd']??"";
	$strConfirm_pwd	= $_POST['confirm_pwd']??"";
	
	// Enlever les espaces avant et après => trim()
	$strName		= trim($strName);
	$strFirstname	= trim($strFirstname);
	$strMail		= strtolower(trim($strMail));
	
	// initialise le tableau des erreurs
	$arrErrors	= array(); 
	// Si le formulaire a été envoyé
	if (count($_POST) > 0){
		// Si l'utilisateur n'a pas saisi son nom
		if ($strName == ""){
			$arrErrors['name'] = "Le nom est obligatoire";
		}
		// Si l'utilisateur n'a pas saisi son prénom
		if ($strFirstname == ""){
			$arrErrors['firstname'] = "Le prénom est obligatoire";
		}
		// Si l'utilisateur n'a pas saisi son mail
		if ($strMail == ""){
			$arrErrors['mail'] = "Le mail est obligatoire";
		}elseif(!filter_var($strMail, FILTER_VALIDATE_EMAIL)){
			$arrErrors['mail'] = "Le mail n'est pas valide";
		}else{
			// Récupère les utilisateurs qui ont l'adresse Mail
			require("connexion.php");
			$strQuery	= "SELECT user_mail
							FROM users 
							WHERE user_mail = :mail;";
			$strRqPrep	= $db->prepare($strQuery);	
			$strRqPrep->bindValue(":mail", $strMail, PDO::PARAM_STR);
			$strRqPrep->execute();
			$arrUser	= $strRqPrep->fetch();
			// Si j'ai un résultat => erreur
			if($arrUser !== false){
				$arrErrors['mail'] = "Le mail est déjà utilisé";
			}
		}
		
		// Si l'utilisateur n'a pas saisi son mot de passe
		if ($strPwd == ""){
			$arrErrors['pwd'] = "Le mot de passe est obligatoire";
		}elseif ($strPwd != $strConfirm_pwd){
			$arrErrors['confirm_pwd'] = "Le mot de passe et sa confirmation ne correspondent pas";
		}
		
		// Si le formulaires est OK
		if (count($arrErrors) == 0){
			// Inclure le fichier de connexion PDO
			require("connexion.php");
			
			// Hacher le mot de passe
			$strPwdHash = password_hash($strPwd, PASSWORD_DEFAULT);
			
			// Ajouter les infos en BDD
			$strQuery		= "INSERT INTO users 
			(user_name, user_firstname, user_mail, user_pwd)
			VALUES 
			('".$strName."', '".$strFirstname."', '".$strMail."', '".$strPwdHash."');";
			$db->exec($strQuery);
			
			$_SESSION['message']= "Vous compte à bien été créé, vous pouvez vous connecter";
			// Redirection vers la page d'accueil
			header("Location:login.php");
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
		<label for="name">Nom</label>
		<input name="name" value="<?php echo $strName; ?>" id="name" class="form-control 
			<?php if(isset($arrErrors['name'])){ echo 'is-invalid'; } ?>" type="text" >
	</p>
	<p>
		<label for="firstname">Prénom</label>
		<input name="firstname" value="<?php echo $strFirstname; ?>" id="firstname" class="form-control 
			<?php if(isset($arrErrors['firstname'])){ echo 'is-invalid'; } ?>" type="text" >
	</p>
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
		<label for="confirm_pwd">Confirmation du mot de passe</label>
		<input name="confirm_pwd" id="confirm_pwd" class="form-control 
			<?php if(isset($arrErrors['confirm_pwd'])){ echo 'is-invalid'; } ?>" type="password" >
	</p>
	<p>
		<input class="form-control btn btn-primary" type="submit" >
	</p>
</form>

<?php
	include("_partial/footer.php");
?>