<?php
/**
* Class de la gestion des utilisateurs
* @author Christel Ehrhart
* @date 17/06/2025
*/

// Inclure le fichier de connexion PDO
require("models/connexion.php");

class UserModel extends Connexion{

	public function findUserByMail(string $strMail):array|bool{
		$strQuery	= "SELECT user_mail
						FROM users 
						WHERE user_mail = :mail;";
		$strRqPrep	= $this->_db->prepare($strQuery);	
		$strRqPrep->bindValue(":mail", $strMail, PDO::PARAM_STR);
		$strRqPrep->execute();
		$arrUser	= $strRqPrep->fetch();		
		
		return $arrUser;
	}
	
	public function insert(object $objUser){
		$strQuery		= "INSERT INTO users 
			(user_name, user_firstname, user_mail, user_pwd)
			VALUES 
			('".$objUser->getName()."', '".$objUser->getFirstname()."', '".$objUser->getMail()."', '".$objUser->getPwdHash()."');";
		$this->_db->exec($strQuery);
	}
	
}