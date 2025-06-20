<?php
	require_once("models/article_model.php"); // le fichier model des articles

	/**
	* Controller qui s'occupe des fonctionnalités des articles
	*/
	class ArticleCtrl extends MotherCtrl{
		
		private ArticleModel $_objModelArticle;
		
		public function __construct(){
			$this->_objModelArticle	= new ArticleModel(); // instancier
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
			$arrArticles		= $this->_objModelArticle->findAll(4); 
			
			/* TODO : Donner à la vue un tableau d'objets
				=> solution 1 : traitement ici
				=> solution 2 : traitement dans le modèle
			*/
			$this->_arrData['arrArticles']	= $arrArticles;
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
			$this->_objModelArticle->strKeywords	= $strKeywords;
			$this->_objModelArticle->intPeriod		= $intPeriod;
			$this->_objModelArticle->strDate		= $strDate;
			$this->_objModelArticle->strStartDate	= $strStartDate;
			$this->_objModelArticle->strEndDate	= $strEndDate;
			$this->_objModelArticle->intAuthor		= $intAuthor;
			// Récupération des articles
			$arrArticles		= $this->_objModelArticle->findAll(); 			

			$this->_arrData['arrArticles']	= $arrArticles;
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
		}
		
		public function edit_article(){
		}

		public function delete_article(){
		}
		
	}