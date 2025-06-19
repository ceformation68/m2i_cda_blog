<?php
/**
* Class de la gestion des utilisateurs
* @author Christel Ehrhart
* @date 17/06/2025
*/

// Inclure le fichier de connexion PDO
require("models/connexion.php");

class UserModel extends Connexion{

	public function findUserByMail(string $strMail, bool $boolMail = true):array|bool{
		if ($boolMail){
			$strQuery	= "SELECT user_mail";
		}else{
			$strQuery	= "SELECT user_firstname, user_name, user_pwd, user_id";
		}
		$strQuery	.= "	FROM users 
							WHERE user_mail = :mail;";
		$strRqPrep	= $this->_db->prepare($strQuery);	
		$strRqPrep->bindValue(":mail", $strMail, PDO::PARAM_STR);
		$strRqPrep->execute();
		$arrUser	= $strRqPrep->fetch();		
		
		return $arrUser;
	}
	
	public function findById(int $intId):array {
		$strQuery	= "SELECT user_name, user_firstname, user_mail
					FROM users 
					WHERE user_id = :id;";
		$strRqPrep	= $this->_db->prepare($strQuery);	
		$strRqPrep->bindValue(":id", $intId, PDO::PARAM_INT);
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
	
	public function update(object $objUser){
			$strQuery		= "UPDATE users 
								SET user_name = :name,
									user_firstname = :firstname,
									user_mail = :mail";
			if ($objUser->getPwd() != ""){
				$strQuery	.=	" , user_pwd = :pwd ";
			}
			$strQuery	.=	" WHERE user_id = :id; ";
			$strRqPrep	= $this->_db->prepare($strQuery);	
			$strRqPrep->bindValue(":name", $objUser->getName(), PDO::PARAM_STR);
			$strRqPrep->bindValue(":firstname", $objUser->getFirstname(), PDO::PARAM_STR);
			$strRqPrep->bindValue(":mail", $objUser->getMail(), PDO::PARAM_STR);
			$strRqPrep->bindValue(":id", $_SESSION['id'], PDO::PARAM_INT);
			
			if ($objUser->getPwd() != ""){
				// Hacher le mot de passe
				//$strPwdHash = password_hash($strPwd, PASSWORD_DEFAULT);
				$strRqPrep->bindValue(":pwd", $objUser->getPwdHash(), PDO::PARAM_STR);
			}
			
			$strRqPrep->execute();		
		
	}
	
}