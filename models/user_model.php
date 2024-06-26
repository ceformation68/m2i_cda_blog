<?php
/**
 * Classe qui gère les échanges avec la base de données
 * Pour les utilisateurs
 */
    // Inclure le fichier de connexion PDO
    require("models/connexion.php");

    class UserModel extends ModelMother {

        /**
         * Constructeur de la classe
         */
        public function __construct()
        {
            parent::__construct();
        }

        /**
         * Méthode de recherche d'un utilisateur par son adresse mail
         * @param string $strMail Mail à rechercher
         * @return array|bool Tableau de l'utilisateur ou false si non trouvé
         */
        public function getByMail(string $strMail):array|bool{
            $strQuery	= "SELECT user_mail
							FROM users 
							WHERE user_mail = :mail;";
            $strRqPrep	= $this->_db->prepare($strQuery);
            $strRqPrep->bindValue(":mail", $strMail, PDO::PARAM_STR);
            $strRqPrep->execute();
            return $strRqPrep->fetch();
        }

        /**
         * Méthode qui permet d'ajouter un utilisateur en bdd
         * @param object $objUser L'utilisateur
         * @return bool Est-ce que l'ajout s'est bien passé
         */
        public function insert(object $objUser):bool{
            // Ajouter les infos en BDD
            /*$strQuery		= "INSERT INTO users (user_name, user_firstname, user_mail, user_pwd)
                                    VALUES ('".$objUser->getName()."', '".$objUser->getFirstname()."', '".$objUser->getMail()."', '".$objUser->getPwd()."');";
            $this->_db->exec($strQuery);*/
            $strQuery		= "INSERT INTO users (user_name, user_firstname, user_mail, user_pwd)
                                VALUES (:name, :firstname, :mail, :pwd) ";
            $strRqPrep	= $this->_db->prepare($strQuery);
            $strRqPrep->bindValue(":name", $objUser->getName(), PDO::PARAM_STR);
            $strRqPrep->bindValue(":firstname", $objUser->getFirstname(), PDO::PARAM_STR);
            $strRqPrep->bindValue(":mail", $objUser->getMail(), PDO::PARAM_STR);
            $strRqPrep->bindValue(":pwd", $objUser->getPwd(), PDO::PARAM_STR);
            return $strRqPrep->execute();
        }


    }