<?php
	require_once("models/article_model.php"); // le fichier model des articles
	require_once("entities/article_entity.php");

	/**
	* Controller qui s'occupe des fonctionnalités des articles
	*/
	class ArticleCtrl extends MotherCtrl{
		
		private ArticleModel $_objArticleModel;
		
		public function __construct(){
			$this->_objArticleModel	= new ArticleModel(); // instancier
		}
		
		/**
		* Page d'accueil
		*/
		public function home(){
			// Variables d'affichage
			$this->_arrData['strH1']	= "Accueil";
			$this->_arrData['strPar']	= "Page affichant les 4 derniers articles";
			
			// Variables de fonctionnement
			$this->_arrData['strPage'] 	= "index";
			
			// Récupérer les articles
			$arrArticles		= $this->_objArticleModel->findAll(4); 

			$this->_arrData['arrArticles']	= $this->_array_to_object($arrArticles);
			// Affichage
			$this->_display('home');

		}
		
		public function blog(){
			// Variables d'affichage
			$this->_arrData['strH1']	= "Blog";
			$this->_arrData['strPar']	= "Page affichant tous les articles, avec une zone de recherche sur les articles";
			
			// Variables de fonctionnement
			$this->_arrData['strPage'] 	= "blog";
			
			$strKeywords	= $_POST['keywords']??"";
			$intPeriod		= $_POST['period']??0;
			$strDate		= $_POST['date']??"";
			$strStartDate	= $_POST['startdate']??"";
			$strEndDate		= $_POST['enddate']??"";
			$intAuthor		= $_POST['author']??0;
			
			// Donner à la classe ArticleModel les infos de recherche
			$this->_objArticleModel->strKeywords	= $strKeywords;
			$this->_objArticleModel->intPeriod		= $intPeriod;
			$this->_objArticleModel->strDate		= $strDate;
			$this->_objArticleModel->strStartDate	= $strStartDate;
			$this->_objArticleModel->strEndDate		= $strEndDate;
			$this->_objArticleModel->intAuthor		= $intAuthor;
			// Récupération des articles
			$arrArticles		= $this->_objArticleModel->findAll(); 			

			$this->_arrData['arrArticles']	= $this->_array_to_object($arrArticles);

			$this->_arrData['strKeywords']	= $strKeywords;
			$this->_arrData['intPeriod']	= $intPeriod;
			$this->_arrData['strDate']		= $strDate;
			$this->_arrData['strStartDate']	= $strStartDate;
			$this->_arrData['strEndDate']	= $strEndDate;
			$this->_arrData['intAuthor']	= $intAuthor;

			// Affichage
			$this->_display('blog');
		}
		
		public function add_article(){
			// Variables d'affichage
			$this->_arrData['strH1']	= "Ajouter un article";
			$this->_arrData['strPar']	= "Page permettant d'ajouter un article";
			
			// Variables de fonctionnement
			$this->_arrData['strPage'] 	= "add_article";

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
					/* TODO : Redimensionner l'image avant de la mettre sur le serveur */
					
					// Copier l'image
					if (!move_uploaded_file($arrImg['tmp_name'], 'assets/images/'.$objArticle->getImg())){
						$arrErrors['img'] = "Il y a une erreur sur le fichier image";
					}else{
						//$objArticle->setImg($strImageName);
						
						// Ajouter les infos en BDD
						$this->_objArticleModel->insert($objArticle);
						
						$_SESSION['message']= "L'article a bien été ajouté";
						// Redirection vers la page d'accueil
						header("Location:index.php");
					}
				}		
			}			
			
			$this->_arrData['arrErrors']	= $arrErrors;
			$this->_arrData['objArticle']	= $objArticle;
			
			// Affichage
			$this->_display('add_article');
			
		}
		
		public function edit_article(){
			// Variables d'affichage
			$this->_arrData['strH1']	= "Modifier un article";
			$this->_arrData['strPar']	= "Page permettant de modifier un article";
			
			// Variables de fonctionnement
			$this->_arrData['strPage'] 	= "edit_article";

			// Vérifier que l'utilisateur est connecté
			if (!isset($_SESSION['id']) || $_SESSION['id'] == '') {
				// Si l'utilisateur n'est pas connecté => page 403
				header("Location:error_403.php");
			}

			// Récupère l'article en fonction de son identifiant
			$arrArticle		= $this->_objArticleModel->findById($_GET['id']);

			// Tableau des types MIME autorisés
			$arrMimeTypes 	= array('image/jpeg', 'image/png');
			$arrImg			= $_FILES['img']??array();

			require("entities/article_entity.php");
			$objArticle = new Article;
			$objArticle->hydrate($arrArticle);

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
				/*if ($arrImg['error'] == 4){
					$arrErrors['img'] = "L'image est obligatoire";
				/*}else if ( 
					($arrImg['type'] != 'image/jpeg') && ($arrImg['type'] != 'image/png')
				){*/
				//}else 
					
				if($arrImg['name']){
					if (!in_array($arrImg['type'], $arrMimeTypes)){
						$arrErrors['img'] = "L'image n'est pas au bon format";
					}else if ($arrImg['error'] > 0){
						$arrErrors['img'] = "Il y a une erreur sur le fichier image";
					}
				}
				
				// Si l'utilisateur n'a pas saisi de contenu
				if ($objArticle->getContent() == ""){
					$arrErrors['content'] = "Le contenu est obligatoire";
				}		
				
				// Si le formulaires est OK
				if (count($arrErrors) == 0){
					if($arrImg['name']){
						$objArticle->setNewImg($arrImg['name']);
						/* TODO : Redimensionner l'image avant de la mettre sur le serveur */
						
						// Copier l'image
						if (!move_uploaded_file($arrImg['tmp_name'], 'assets/images/'.$objArticle->getImg())){
							$arrErrors['img'] = "Il y a une erreur sur le fichier image";
						}
					}
					
					if (count($arrErrors) == 0){
						// Ajouter les infos en BDD
						$this->_objArticleModel->update($objArticle);
						
						$_SESSION['message']= "L'article a bien été modifié";
						// Redirection vers la page d'accueil
						header("Location:index.php");
					}			
					
				}		
			}
			
			$this->_arrData['arrErrors']	= $arrErrors;
			$this->_arrData['objArticle']	= $objArticle;
			
			// Affichage
			$this->_display('edit_article');

		}

		public function delete_article(){
			// Vérifier que l'utilisateur est connecté
			if (!isset($_SESSION['id']) || $_SESSION['id'] == '') {
				// Si l'utilisateur n'est pas connecté => page 403
				header("Location:error_403.php");
			}
			
			$this->_objArticleModel->delete($_GET['id']);	
			
			$_SESSION['message']= "L'article a bien été supprimé";
			// Redirection vers la page d'accueil
			header("Location:index.php");
		}
		
		
		private function _array_to_object(array $arrArticles){
			// Créer un tableau d'objets
			$arrArticlesToDisplay = array();
			// Boucle fonctionnelle
			foreach($arrArticles as $arrDetArticle){
				$objArticle	= new Article();
				$objArticle->hydrate($arrDetArticle);
				$arrArticlesToDisplay[] = $objArticle;
			}	
			return $arrArticlesToDisplay;
		}
	}