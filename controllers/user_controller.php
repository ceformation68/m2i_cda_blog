<?php
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	
	require'libs/PHPMailer/Exception.php';
	require'libs/PHPMailer/PHPMailer.php';
	require'libs/PHPMailer/SMTP.php';

	require_once("models/user_model.php"); // le fichier model des articles
	require_once("entities/user_entity.php");
	
	/**
	* Controller qui s'occupe des fonctionnalités des utilisateurs
	*/
	class UserCtrl extends MotherCtrl{
		
		private UserModel $_objUserModel;
		private User $_objUser;
		
		public function __construct(){
			$this->_objUserModel	= new UserModel(); // instancier
			$this->_objUser			= new User(); // instancier
		}
		
		/**
		* Créer un compte
		*/
		public function create_account(){
			
			// Variables d'affichage
			$this->_arrData['strH1']	= "Créer un compte";
			$this->_arrData['strPar']	= "Page permettant de créer son compte";
			
			// Variables de fonctionnement
			$this->_arrData['strPage'] 	= "create_account";
			
			// Variable pour la cofirmation du mot de passe
			$strConfirm_pwd	= $_POST['confirm_pwd']??"";
			
			$this->_objUser->hydrate($_POST);
			
			// initialise le tableau des erreurs
			$arrErrors	= array(); 
			// Si le formulaire a été envoyé
			if (count($_POST) > 0){
				// Si l'utilisateur n'a pas saisi son nom
				if ($this->_objUser->getName() == ""){
					$arrErrors['name'] = "Le nom est obligatoire";
				}
				// Si l'utilisateur n'a pas saisi son prénom
				if ($this->_objUser->getFirstname() == ""){
					$arrErrors['firstname'] = "Le prénom est obligatoire";
				}
				// Si l'utilisateur n'a pas saisi son mail
				if ($this->_objUser->getMail() == ""){
					$arrErrors['mail'] = "Le mail est obligatoire";
				}elseif(!filter_var($this->_objUser->getMail(), FILTER_VALIDATE_EMAIL)){
					$arrErrors['mail'] = "Le mail n'est pas valide";
				}else{
					// Récupère les utilisateurs qui ont l'adresse Mail
					$arrUser		= $this->_objUserModel->findUserByMail($this->_objUser->getMail());
					// Si j'ai un résultat => erreur
					if($arrUser !== false){
						$arrErrors['mail'] = "Le mail est déjà utilisé";
					}
				}
				
				// Si l'utilisateur n'a pas saisi son mot de passe
				if ($this->_objUser->getPwd() == ""){
					$arrErrors['pwd'] = "Le mot de passe est obligatoire";
				}elseif ($this->_objUser->getPwd() != $strConfirm_pwd){
					$arrErrors['confirm_pwd'] = "Le mot de passe et sa confirmation ne correspondent pas";
				}
				
				// Si le formulaires est OK
				if (count($arrErrors) == 0){
					// Ajouter les infos en BDD
					$this->_objUserModel->insert($this->_objUser);
					
					$_SESSION['message']= "Vous compte à bien été créé, vous pouvez vous connecter";
					// Redirection vers la page d'accueil
					header("Location:index.php?ctrl=user&action=login");
					die;
				}		
			}			
			
			$this->_arrData['objUser']		= $this->_objUser;
			$this->_arrData['arrErrors']	= $arrErrors;
			
			// Affichage
			$this->_display('create_account');
		}
		
		public function login(){
			// Variables d'affichage
			$this->_arrData['strH1']	= "Se connecter";
			$this->_arrData['strPar']	= "Page permettant de se connecter";
			
			// Variables de fonctionnement
			$this->_arrData['strPage'] 	= "login";
			
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
					$arrUser 		= $this->_objUserModel->findUserByMail($strMail, false);
					
					// Si aucun utilisateur trouvé
					if($arrUser === false){
						$arrErrors[] = "Erreur dans la connexion";
					}else{
						// On vérifie le mot de passe
						if (password_verify($strPwd, $arrUser['user_pwd'])) {
							// Créer l'utilisateur
							unset($arrUser['user_pwd']); // enlever mdp pour l'hydratation
							$this->_objUser->hydrate($arrUser);

							// Ajouter les informations de l'utilisateur => en session
							$_SESSION['prenom'] = $this->_objUser->getFirstname();
							$_SESSION['id'] 	= $this->_objUser->getId(); // La clé peut être renommée
							$_SESSION['nom'] 	= $this->_objUser->getName();
							$_SESSION['message']= "Vous êtes bien connecté";
							// Redirection vers la page d'accueil
							header("Location:index.php");
							die;
						}else{
							$arrErrors[] = "Erreur dans la connexion";
						}
					}
				}		
			}
			
			$this->_arrData['strMail']		= $strMail;
			$this->_arrData['arrErrors']	= $arrErrors;
			
			// Affichage
			$this->_display('login');
			
		}
		
		public function logout(){
			// Destruction de la session
			session_destroy();
			// Redirection vers la page d'accueil
			header("Location:index.php");
			die;
		}

		public function edit_account(){
			
			// Variables d'affichage
			$this->_arrData['strH1']	= "Modifier un compte";
			$this->_arrData['strPar']	= "Page permettant de modifier un compte";
			
			// Variables de fonctionnement
			$this->_arrData['strPage'] 	= "edit_account";
			
			// Vérifier que l'utilisateur est connecté
			if (!isset($_SESSION['id']) || $_SESSION['id'] == '') {
				// Si l'utilisateur n'est pas connecté => page 403
				header("Location:index.php?ctrl=error&action=error_403");
				die;
			}
			
			// Récupérer les informations de l'utilisateur connecté
			$intID	= $_SESSION['id'];
			
			// Récupère l'utilisateur en fonction de son identifiant
			$arrUser	= $this->_objUserModel->findById($intID);
			
			// Définition des variable, du formulaire ou de la bdd
			$strConfirm_pwd	= $_POST['confirm_pwd']??"";
			
			// initialise le tableau des erreurs
			$arrErrors	= array(); 
			// Si le formulaire a été envoyé
			if (count($_POST) > 0){
				$this->_objUser->hydrate($_POST);

				// Si l'utilisateur n'a pas saisi son nom
				if ($this->_objUser->getName() == ""){
					$arrErrors['name'] = "Le nom est obligatoire";
				}
				// Si l'utilisateur n'a pas saisi son prénom
				if ($this->_objUser->getFirstname() == ""){
					$arrErrors['firstname'] = "Le prénom est obligatoire";
				}
				// Si l'utilisateur n'a pas saisi son mail
				if ($this->_objUser->getMail() == ""){
					$arrErrors['mail'] = "Le mail est obligatoire";
				}elseif(!filter_var($this->_objUser->getMail(), FILTER_VALIDATE_EMAIL)){
					$arrErrors['mail'] = "Le mail n'est pas valide";
				}elseif($this->_objUser->getMail() != $arrUser['user_mail']){
					// Si l'adresse mail a été changée, vérifier son unicité en bdd
					$arrUserExist	= $objUserModel->findUserByMail($this->_objUser->getMail());
					// Si j'ai un résultat => erreur
					if($arrUserExist !== false){
						$arrErrors['mail'] = "Le mail est déjà utilisé";
					}
				}
				
				// Si l'utilisateur veut modifier son mot de passe
				if ( ($this->_objUser->getPwd() != "") 
					&& ($this->_objUser->getPwd() != $strConfirm_pwd) ){
					$arrErrors['confirm_pwd'] = "Le mot de passe et sa confirmation ne correspondent pas";
				}
				
				// Si le formulaires est OK
				if (count($arrErrors) == 0){
					// Modifier les infos en BDD
					$this->_objUserModel->update($this->_objUser);
					
					$_SESSION['prenom'] = $this->_objUser->getFirstname();
					$_SESSION['nom'] 	= $this->_objUser->getName();
					$_SESSION['message']= "Vos informations ont bien été modifiées";
					// Redirection vers la page d'accueil
					header("Location:index.php");
					die;
				}		
			}else{
				$this->_objUser->hydrate($arrUser);
			}			
			$this->_arrData['objUser']		= $this->_objUser;
			$this->_arrData['arrErrors']	= $arrErrors;
			
			// Affichage
			$this->_display('create_account');
		}
		
		public function recover_pwd(){
			// Variables d'affichage
			$this->_arrData['strH1']	= "Réinitialiser son mot de passe";
			$this->_arrData['strPar']	= "Page permettant de réinitaliser son mot de passe";
			
			// Variables de fonctionnement
			$this->_arrData['strPage'] 	= "recover_pwd";
			
			$strCode		= $_GET['code']??'';
			$strPwd			= $_POST['pwd']??"";
			$strConfirm_pwd	= $_POST['confirm_pwd']??"";
			
			// Récupère les utilisateurs en fonction du code
			$arrUser	= $this->_objUserModel->findUserByCode($strCode);

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
						$this->_objUser->setId($arrUser['user_id']);
						$this->_objUser->setPwd($strPwd);
						// Mise à jour du mot de passe de l'utilisateur
						$this->_objUserModel->updatePwd($this->_objUser);
						
						$_SESSION['message']= "Votre mot de passe a bien été modifié, vous pouvez vous connecter";
						// Redirection vers la page de connexion
						header("Location:index.php?ctrl=user&action=login");
						die;
					}	
				}
			}			
			$this->_arrData['arrErrors']	= $arrErrors;
			
			// Affichage
			$this->_display('recover_pwd');
		}
		
		public function forgot_pwd(){
			
			// Variables d'affichage
			$this->_arrData['strH1']	= "Mot de passe oublié";
			$this->_arrData['strPar']	= "Page de saisie du mail pour réinitialiser le mot de passe";
			
			// Variables de fonctionnement
			$this->_arrData['strPage'] 	= "forgot_pwd";
			
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
					$arrUser	= $this->_objUserModel->findUserByMail($strMail, false);
					// Si j'ai un résultat => envoyer un mail 
					if($arrUser !== false){
						$strCode = bin2hex(random_bytes(20));
						// Mise à jour de l'utilisateur (code + date/heure de demande de réinitialisation)
						$this->_objUserModel->majUserCode($strCode, $arrUser['user_id']);
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
						
						$strLien			= "index.php?ctrl_user&action=recover_pwd&code=".$strCode;
						
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
						header("Location:index.php?ctrl=user&action=login");
						die;
					}
				}
			}	

			$this->_arrData['strMail']		= $strMail;
			$this->_arrData['arrErrors']	= $arrErrors;
			// Affichage
			$this->_display('forgot_pwd');
			
		}

	}