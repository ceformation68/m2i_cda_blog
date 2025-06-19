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
	
	// Récupère l'utilisateur en fonction de son identifiant
	require("models/user_model.php");
	$objUserModel 	= new UserModel();
	$arrUser		= $objUserModel->findById($intID);
	
	require("entities/user_entity.php");
	$objUser = new User();
	
	// Définition des variable, du formulaire ou de la bdd
	$strConfirm_pwd	= $_POST['confirm_pwd']??"";
	
	// initialise le tableau des erreurs
	$arrErrors	= array(); 
	// Si le formulaire a été envoyé
	if (count($_POST) > 0){
		$objUser->hydrate($_POST);

		// Si l'utilisateur n'a pas saisi son nom
		if ($objUser->getName() == ""){
			$arrErrors['name'] = "Le nom est obligatoire";
		}
		// Si l'utilisateur n'a pas saisi son prénom
		if ($objUser->getFirstname() == ""){
			$arrErrors['firstname'] = "Le prénom est obligatoire";
		}
		// Si l'utilisateur n'a pas saisi son mail
		if ($objUser->getMail() == ""){
			$arrErrors['mail'] = "Le mail est obligatoire";
		}elseif(!filter_var($objUser->getMail(), FILTER_VALIDATE_EMAIL)){
			$arrErrors['mail'] = "Le mail n'est pas valide";
		}elseif($objUser->getMail() != $arrUser['user_mail']){
			// Si l'adresse mail a été changée, vérifier son unicité en bdd
			$arrUserExist	= $objUserModel->findUserByMail($objUser->getMail());
			// Si j'ai un résultat => erreur
			if($arrUserExist !== false){
				$arrErrors['mail'] = "Le mail est déjà utilisé";
			}
		}
		
		// Si l'utilisateur veut modifier son mot de passe
		if ( ($objUser->getPwd() != "") 
			&& ($objUser->getPwd() != $strConfirm_pwd) ){
			$arrErrors['confirm_pwd'] = "Le mot de passe et sa confirmation ne correspondent pas";
		}
		
		// Si le formulaires est OK
		if (count($arrErrors) == 0){
			// Modifier les infos en BDD
			$objUserModel->update($objUser);
			
			$_SESSION['prenom'] = $objUser->getFirstname();
			$_SESSION['nom'] 	= $objUser->getName();
			$_SESSION['message']= "Vos informations ont bien été modifiées";
			// Redirection vers la page d'accueil
			header("Location:index.php");
		}		
	}else{
		$objUser->hydrate($arrUser);
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
			<input name="name" value="<?php echo $objUser->getName(); ?>" id="name" class="form-control 
				<?php if(isset($arrErrors['name'])){ echo 'is-invalid'; } ?>" type="text" >
		</p>
		<p>
			<label for="firstname">Prénom</label>
			<input name="firstname" value="<?php echo $objUser->getFirstname(); ?>" id="firstname" class="form-control 
				<?php if(isset($arrErrors['firstname'])){ echo 'is-invalid'; } ?>" type="text" >
		</p>
		<p>
			<label for="mail">Mail</label>
			<input name="mail" value="<?php echo $objUser->getMail(); ?>" id="mail" class="form-control 
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