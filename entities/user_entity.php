<?php
require_once("entities/mother_entity.php");

class User extends Entity{
	// Attributs
	private int $_id;
	private string $_name="";
	private string $_firstname="";
	private string $_mail="";
	private string $_pwd;
	
	public function __construct(){
		$this->_prefixe = "user";
	}
	
	public function setName(string $strName){
		$this->_name = trim($strName);
	}
	public function getName():string{
		return $this->_name;
	}
	public function setFirstname(string $strFirstname){
		$this->_firstname = trim($strFirstname);
	}
	public function getFirstname():string{
		return $this->_firstname;
	}
	public function setMail(string $strMail){
		$this->_mail = strtolower(trim($strMail));
	}
	public function getMail():string{
		return $this->_mail;
	}
	public function setPwd(string $strPwd){
		$this->_pwd = $strPwd;
	}
	public function getPwd():string{
		return $this->_pwd;
	}
	
	public function getPwdHash():string{
		return password_hash($this->_pwd, PASSWORD_DEFAULT);
	}
	
}