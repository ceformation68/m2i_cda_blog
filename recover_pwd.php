<?php
	// Variables d'affichage
	$strH1		= "Réinitialiser son mot de passe";
	$strPar		= "Page permettant de réinitaliser son mot de passe";
	
	// Variables de fonctionnement
	$strPage 	= "recover_pwd";
	
	include("_partial/header.php");
	
	$strCode		= $_GET['code']??'';
	$strPwd			= $_POST['pwd']??"";
	$strConfirm_pwd	= $_POST['confirm_pwd']??"";
	
	// Récupère les utilisateurs en fonction du code
	require("connexion.php");
	$strQuery	= "SELECT user_mail, user_id, user_name
					FROM users 
					WHERE user_code = :code
						AND DATE_ADD(user_code_date, INTERVAL 15 MINUTE) > NOW();";
	$strRqPrep	= $db->prepare($strQuery);	
	$strRqPrep->bindValue(":code", $strCode, PDO::PARAM_STR);
	$strRqPrep->execute();
	$arrUser	= $strRqPrep->fetch();
	
	// initialise le tableau des erreurs
	$arrErrors	= array(); 
	if($arrUser === false){
		$arrErrors[]	= "Le lien de récupération n'est plus valide";
	}else{
		// Si le formulaire a été envoyé
		if (count($_POST) > 0){
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
				
				// Modifier les infos en BDD
				$strQuery		= "UPDATE users 
									SET user_pwd = :pwd
									WHERE user_id = :id;";
									// Possibilité de remettre à null user_code et user_code_date dans la requête
				$strRqPrep	= $db->prepare($strQuery);	
				$strRqPrep->bindValue(":pwd", $strPwdHash, PDO::PARAM_STR);
				$strRqPrep->bindValue(":id", $arrUser['user_id'], PDO::PARAM_INT);
				$strRqPrep->execute();
				
				$_SESSION['message']= "Votre mot de passe a bien été modifié, vous pouvez vous connecter";
				// Redirection vers la page de connexion
				header("Location:login.php");
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
	}else{
?>
	<form method="post">
		<p>
			<label for="pwd">Nouveau mot de passe</label>
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
	}
?>




<?php
	include("_partial/footer.php");
?>