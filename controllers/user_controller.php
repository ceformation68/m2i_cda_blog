<?php
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
					header("Location:login.php");
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
			// Initialisation de la session (car pas de header inclus) 
			session_start();
			// Destruction de la session
			session_destroy();
			// Redirection vers la page d'accueil
			header("Location:index.php");
		}
	}