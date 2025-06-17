<?php
	// Variables d'affichage
	$strH1		= "Modifier un compte";
	$strPar		= "Page permettant de modifier un compte";
	
	// Variables de fonctionnement
	$strPage 	= "edit_account";
	
	include("_partial/header.php");
	
	// Vérifier que l'utilisateur est connecté
	if (!isset($_SESSION['id']) || $_SESSION['id'] == '') {
		// Si l'utilisateur n'est pas connecté => page 403
		header("Location:error_403.php");
	}
	
	// Récupérer les informations de l'utilisateur connecté
	$intID	= $_SESSION['id'];
	
	// Récupère les utilisateurs en fonction de son identifiant
	require("connexion.php");
	$strQuery	= "SELECT user_name, user_firstname, user_mail
					FROM users 
					WHERE user_id = :id;";
	$strRqPrep	= $db->prepare($strQuery);	
	$strRqPrep->bindValue(":id", $intID, PDO::PARAM_INT);
	$strRqPrep->execute();
	$arrUser	= $strRqPrep->fetch();
	
	// Définition des variable, du formulaire ou de la bdd
	$strName		= $_POST['name']??$arrUser['user_name'];
	$strFirstname	= $_POST['firstname']??$arrUser['user_firstname'];
	$strMail		= $_POST['mail']??$arrUser['user_mail'];
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
		}elseif($strMail != $arrUser['user_mail']){
			// Si l'adresse mail a été changée, vérifier son unicité en bdd
			require("connexion.php");
			$strQuery	= "SELECT user_mail
							FROM users 
							WHERE user_mail = :mail;";
			$strRqPrep	= $db->prepare($strQuery);	
			$strRqPrep->bindValue(":mail", $strMail, PDO::PARAM_STR);
			$strRqPrep->execute();
			$arrUserExist	= $strRqPrep->fetch();
			// Si j'ai un résultat => erreur
			if($arrUserExist !== false){
				$arrErrors['mail'] = "Le mail est déjà utilisé";
			}
		}
		
		// Si l'utilisateur veut modifier son mot de passe
		if ( ($strPwd != "") && ($strPwd != $strConfirm_pwd) ){
			$arrErrors['confirm_pwd'] = "Le mot de passe et sa confirmation ne correspondent pas";
		}
		
		// Si le formulaires est OK
		if (count($arrErrors) == 0){
			// Modifier les infos en BDD
			$strQuery		= "UPDATE users 
								SET user_name = :name,
									user_firstname = :firstname,
									user_mail = :mail";
			if ($strPwd != ""){
				$strQuery	.=	" , user_pwd = :pwd ";
			}
			$strQuery	.=	" WHERE user_id = :id; ";
			$strRqPrep	= $db->prepare($strQuery);	
			$strRqPrep->bindValue(":name", $strName, PDO::PARAM_STR);
			$strRqPrep->bindValue(":firstname", $strFirstname, PDO::PARAM_STR);
			$strRqPrep->bindValue(":mail", $strMail, PDO::PARAM_STR);
			$strRqPrep->bindValue(":id", $intID, PDO::PARAM_INT);
			
			if ($strPwd != ""){
				// Hacher le mot de passe
				$strPwdHash = password_hash($strPwd, PASSWORD_DEFAULT);
				$strRqPrep->bindValue(":pwd", $strPwdHash, PDO::PARAM_STR);
			}
			
			$strRqPrep->execute();
			
			$_SESSION['prenom'] = $strFirstname;
			$_SESSION['nom'] 	= $strName;
			$_SESSION['message']= "Vos informations ont bien été modifiées";
			// Redirection vers la page d'accueil
			header("Location:index.php");
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
	<fieldset>
		<legend>Informations</legend>
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
	</fieldset>
	<fieldset>
		<legend>Mot de passe</legend>
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
	</fieldset>
	<p>
		<input class="form-control btn btn-primary" type="submit" >
	</p>
</form>

<?php
	include("_partial/footer.php");
?>