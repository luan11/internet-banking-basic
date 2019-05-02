<?php
namespace src\app\models;

use src\app\views\IndexView;
use src\app\views\View;

require_once('src/app/helpers/Forms.php');

class User {

	private $userFirstName, $userLastName, $userEmail, $userAccount, $userPassword, $errors = array();

	public function __construct($formValidator, $userFirstName, $userLastName, $userEmail, $userAccount, $userPassword, $userPasswordConfirm){
		if(\src\app\helpers\Forms::validateInputValidator($formValidator)){
			$this->newUser($userFirstName, $userLastName, $userEmail, $userAccount, $userPassword, $userPasswordConfirm);
			var_dump($this);
		}else{
			array_push($this->errors, "Algo deu errado, tente realizar o cadastro novamente!");
		}
	}

	public function getErrors(){
		return $this->errors;
	}

	private function newUser($userFirstName, $userLastName, $userEmail, $userAccount, $userPassword, $userPasswordConfirm){
		if(!empty($userFirstName) && !empty($userLastName) && !empty($userEmail) && !empty($userAccount) && !empty($userPassword) && !empty($userPasswordConfirm)){
			$this->userFirstName = filter_var($userFirstName, FILTER_SANITIZE_STRING);
			$this->userLastName = filter_var($userLastName, FILTER_SANITIZE_STRING);
			$this->userEmail = filter_var($userEmail, FILTER_SANITIZE_EMAIL);
			$this->userAccount = substr(filter_var($userAccount, FILTER_SANITIZE_STRING), 0, 10);
			$this->confirmPasswords($userPassword, $userPasswordConfirm);
		}else{
			array_push($this->errors, "É obrigatório o preenchimento de todos os campos!");
		}
	}
	
	private function confirmPasswords($userPassword, $userPasswordConfirm){
		if(filter_var($userPassword, FILTER_SANITIZE_STRING) === filter_var($userPasswordConfirm, FILTER_SANITIZE_STRING)){
			if(strlen($userPassword) === 6){
				$this->userPassword = $userPassword;
			}else{
				array_push($this->errors, "Digite uma senha com 6 dígitos.");
			}
		}else{
			array_push($this->errors, "As senhas digitadas são diferentes!");
		}
	}

	public static function userIsLoggedIn($action = 'ALTER_HEADER'){
		session_start();
		if(isset($_SESSION['userLogged'])){
			switch($action){
				case 'ALTER_HEADER':
					return true;
				break;

				case 'REDIRECT':
					header('Location: '.SYS_DEFAULT_URI.'/painel');
					exit;
				break;
			}
		}else{
			return false;
		}
	}

}