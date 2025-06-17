<?php
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	
	require'libs/PHPMailer/Exception.php';
	require'libs/PHPMailer/PHPMailer.php';
	require'libs/PHPMailer/SMTP.php';
	
	// Variables d'affichage
	$strH1		= "Mot de passe oublié";
	$strPar		= "Page de saisie du mail pour réinitialiser le mot de passe";
	
	// Variables de fonctionnement
	$strPage 	= "forgot_pwd";
	
	include("_partial/header.php");
	
	$strMail	= $_POST['mail']??"";
	
	// initialise le tableau des erreurs
	$arrErrors	= array(); 
	// Si le formulaire a été envoyé
	if (count($_POST) > 0){
		// Si l'utilisateur n'a pas saisi son mail
		if ($strMail == ""){
			$arrErrors['mail'] = "Le mail est obligatoire";
		}elseif(!filter_var($strMail, FILTER_VALIDATE_EMAIL)){
			$arrErrors['mail'] = "Le mail n'est pas valide";
		}else{
			// Mail ok
			// Récupère les utilisateurs qui ont l'adresse Mail
			require("connexion.php");
			$strQuery	= "SELECT user_mail, user_id, user_name
							FROM users 
							WHERE user_mail = :mail;";
			$strRqPrep	= $db->prepare($strQuery);	
			$strRqPrep->bindValue(":mail", $strMail, PDO::PARAM_STR);
			$strRqPrep->execute();
			$arrUser	= $strRqPrep->fetch();
			// Si j'ai un résultat => envoyer un mail 
			if($arrUser !== false){
				$strCode = bin2hex(random_bytes(20));
				// Mise à jour de l'utilisateur (code + date/heure de demande de réinitialisation)
				$strQuery	= "UPDATE users
								SET user_code = :code,
									user_code_date = NOW()
								WHERE user_id = :id;";
				$strRqPrep	= $db->prepare($strQuery);
				$strRqPrep->bindValue(":code", $strCode, PDO::PARAM_STR);
				$strRqPrep->bindValue(":id", $arrUser['user_id'], PDO::PARAM_INT);
				$strRqPrep->execute();
				// Envoyer le mail
				$mail = new PHPMailer();
				$mail->IsSMTP();
				$mail->Mailer 		= "smtp";
				$mail->SMTPDebug	= 0;
				$mail->SMTPAuth		= TRUE;
				$mail->SMTPAutoTLS	= false; // A désactiver uniquement en local
				$mail->SMTPSecure	= "tls";
				$mail->Port 		= 587;
				$mail->Host 		= "smtp.gmail.com";
				$mail->Username		= '';
				$mail->Password		= '';				
				$mail->IsHTML(true);
				$mail->CharSet		= "utf-8";
				$mail->setFrom('no-reply@gmail.com', 'christel'); // Gmail ne change pas l'adresse
				$mail->addAddress($strMail, $arrUser['user_name']);
				$mail->Subject		= 'BLOG - Réinitialisation du mot de passe';
				
				$strLien			= "recover_pwd.php?code=".$strCode;
				
				$mail->Body 		= "<p>Bonjour ".$arrUser['user_name'].",</p>
										<p>Vous avez demandé la réinitialisation du mot de passe</p>
										<p>Vous pouvez cliquer sur ce lien <a href='".$strLien."'>".$strLien."</a></p>
										<p>Ce lien sera valable 15 minutes</p>
										<p>Si vous n'êtes pas à l'origine de cette demande, vous pouvez ignorer ce mail</p>
										<p>L'équipe du BLOG</p>
										";
				if (!$mail->send()) {
					$arrErrors[] = "Le message n'a pas pu être envoyé, merci de contacter l'administrateur";
				}
			}
			if (count($arrErrors) == 0){
				$_SESSION['message'] = "Votre demande de réinitialisation a été traitée, 
										si vous êtes inscrit vous allez recevoir un mail avec un lien";	
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
	}
?>
<p class="alert alert-info">Merci de saisir un mail, si celui-ci existe nous vous enverrons un lien pour le réinitialiser</p>
<form method="post">
	<p>
		<label for="mail">Mail</label>
		<input name="mail" value="<?php echo $strMail; ?>" id="mail" class="form-control 
			<?php if(isset($arrErrors['mail'])){ echo 'is-invalid'; } ?>" type="text" >
	</p>
	<p>
		<input class="form-control btn btn-primary" type="submit" >
	</p>
</form>

<?php
	include("_partial/footer.php");
?>
