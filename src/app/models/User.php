<?php
namespace src\app\models;

require_once('src/app/helpers/Forms.php');

abstract class User {

	protected $userFirstName, $userLastName, $userEmail, $userAccount, $userPassword;
	private $messages = array(
		'errors' => array(),
		'success' => array()
	);

	protected function setError($error){
		array_push($this->messages['errors'], $error);
	}
	
	protected function setSuccess($success){
		array_push($this->messages['success'], $success);
	}

	public function getErrors(){
		return $this->messages['errors'];
	}

	public function getSuccess(){
		return $this->messages['success'];
	}
	
	protected function confirmPasswords($userPassword, $userPasswordConfirm){
		if(filter_var($userPassword, FILTER_SANITIZE_STRING) === filter_var($userPasswordConfirm, FILTER_SANITIZE_STRING)){
			if(strlen($userPassword) === 6){
				$this->userPassword = password_hash($userPassword, PASSWORD_BCRYPT);
			}else{
				$this->setError("Digite uma senha com 6 dígitos.");
			}
		}else{
			$this->setError("As senhas digitadas são diferentes!");
		}
	}

	public static function userIsLoggedIn($action = 'ALTER_VIEW_CONTENT'){
		session_start();
		if(isset($_SESSION['userLogged'])){
			switch($action){
				case 'ALTER_VIEW_CONTENT':
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