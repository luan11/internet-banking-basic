<?php
namespace src\app\models;

require_once('src/app/helpers/Forms.php');
require_once('src/app/helpers/Utils.php');
require_once('src/app/services/Database.php');

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
		if(session_status() === PHP_SESSION_NONE){
			session_start();
		}
		if(isset($_SESSION['userLogged'])){
			$confirmUserLogged = \src\app\services\Database::validateLoggedUser($_SESSION['userLogged']);
			if(!empty($confirmUserLogged)){
				switch($action){
					case 'ALTER_VIEW_CONTENT':
						return array(
							'name' => $confirmUserLogged->firstName_ibbUsers.' '.$confirmUserLogged->lastName_ibbUsers,
							'balance' => \src\app\helpers\Utils::formatMoney($confirmUserLogged->balance_ibbUsers)
						);
					break;
					case 'REDIRECT':
						header('Location: '.SYS_DEFAULT_URI.'/painel');
						exit;
					break;
				}
			}else{
				self::logout();
			}
		}else{
			return false;
		}
	}

	protected function dbLogin(User $user){
		return \src\app\services\Database::userLogin($user->userAccount, $user->userPassword);
	}

	protected function dbRegister(User $user){
		return \src\app\services\Database::userRegister($user->userFirstName, $user->userLastName, $user->userEmail, $user->userAccount, $user->userPassword);
	}

	public static function logout(){
		if(session_status() === PHP_SESSION_NONE){
			session_start();
		}
		session_destroy();
	}

}