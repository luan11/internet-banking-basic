<?php
namespace src\app\services;

require_once('src/app/config.php');

abstract class Database{

	private const hostAndDbName = 'mysql:host='.SYS_DB_HOST.';dbname='.SYS_DB_NAME.';charset=utf8';
	private const dbUser = SYS_DB_USER;
	private const dbUserPassword = SYS_DB_USER_PASSWORD;

	public static function validateLoggedUser($loggedUserId){
		try{
			$connection = new \PDO(self::hostAndDbName, self::dbUser, self::dbUserPassword);
			$connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

			$verifyLoggedUser = $connection->prepare('SELECT firstName_ibbUsers, lastName_ibbUsers, balance_ibbUsers FROM ibb_users WHERE id_ibbUsers = :loggedUserId');
			$verifyLoggedUser->bindParam(':loggedUserId', $loggedUserId);
			$verifyLoggedUser->execute();

			$return = $verifyLoggedUser->fetch(\PDO::FETCH_OBJ);
		}catch(\PDOException $e){
			$return = false;
		}

		$connection = null;

		return $return;
	}

	public static function userLogin($userAccount, $userPassword){
		try{
			$return = false;

			$connection = new \PDO(self::hostAndDbName, self::dbUser, self::dbUserPassword);
			$connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

			$userLogin = $connection->prepare('SELECT id_ibbUsers, password_ibbUsers FROM ibb_users WHERE account_ibbUsers = :userAccount');
			$userLogin->bindParam(':userAccount', $userAccount);
			$userLogin->execute();

			$result = $userLogin->fetch(\PDO::FETCH_OBJ);
			if(!empty($result)){
				if(password_verify($userPassword, $result->password_ibbUsers)){
					$return = $result->id_ibbUsers;
				}
			}
		}catch(\PDOException $e){
			$return = false;
		}

		$connection = null;

		return $return;
	}

	public static function userRegister($userFirstName, $userLastName, $userEmail, $userAccount, $userPassword){
		try{
			$connection = new \PDO(self::hostAndDbName, self::dbUser, self::dbUserPassword);
			$connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

			$userRegister = $connection->prepare('INSERT INTO ibb_users(firstName_ibbUsers, lastName_ibbUsers, email_ibbUsers, account_ibbUsers, password_ibbUsers) VALUES (:userFirstName, :userLastName, :userEmail, :userAccount, :userPassword)');
			$userRegister->bindParam(':userFirstName', $userFirstName);
			$userRegister->bindParam(':userLastName', $userLastName);
			$userRegister->bindParam(':userEmail', $userEmail);
			$userRegister->bindParam(':userAccount', $userAccount);
			$userRegister->bindParam(':userPassword', $userPassword);
			$userRegister->execute();

			$return = true;
		}catch(\PDOException $e){
			$return = false;
		}

		$connection = null;

		return $return;
	}

	public static function getTransactsHistory($userId){
		try{
			$connection = new \PDO(self::hostAndDbName, self::dbUser, self::dbUserPassword);
			$connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

			$transactHistory = $connection->prepare('SELECT ibb_transacts.action_ibbTransacts, ibb_transacts.date_ibbTransacts, ibb_transacts.value_ibbTransacts, ibb_transacts.ip_ibbTransacts FROM ibb_transacts INNER JOIN ibb_users ON ibb_users.id_ibbUsers = :userId AND ibb_transacts.userId_ibbTransacts = :userId ORDER BY ibb_transacts.date_ibbTransacts DESC');
			$transactHistory->bindParam(':userId', $userId);
			$transactHistory->execute();

			$return = $transactHistory->fetchAll(\PDO::FETCH_CLASS);
		}catch(\PDOException $e){
			$return = false;
		}

		$connection = null;

		return $return;
	}

	public static function validateLoggedPassword($userId, $userPassword){
		try{
			$return = false;

			$connection = new \PDO(self::hostAndDbName, self::dbUser, self::dbUserPassword);
			$connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

			$validatePassword = $connection->prepare('SELECT balance_ibbUsers, account_ibbUsers,password_ibbUsers FROM ibb_users WHERE id_ibbUsers = :userId');
			$validatePassword->bindParam(':userId', $userId);
			$validatePassword->execute();

			$result = $validatePassword->fetch(\PDO::FETCH_OBJ);
			if(!empty($result)){
				if(password_verify($userPassword, $result->password_ibbUsers)){
					$return = array(
						'balance' => $result->balance_ibbUsers,
						'account' => $result->account_ibbUsers
					);
				}
			}
		}catch(\PDOException $e){
			$return = false;
		}

		$connection = null;

		return $return;
	}

	public static function validateAccountReceiver($accountNumber){
		try{
			$connection = new \PDO(self::hostAndDbName, self::dbUser, self::dbUserPassword);
			$connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

			$validateReceiver = $connection->prepare('SELECT balance_ibbUsers, id_ibbUsers FROM ibb_users WHERE account_ibbUsers = :accountNumber');
			$validateReceiver->bindParam(':accountNumber', $accountNumber);
			$validateReceiver->execute();

			$return = $validateReceiver->fetch(\PDO::FETCH_OBJ);
		}catch(\PDOException $e){
			$return = false;
		}

		$connection = null;

		return $return;
	}

	public static function doTransact($userId, $value){
		try{
			$connection = new \PDO(self::hostAndDbName, self::dbUser, self::dbUserPassword);
			$connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

			$transact = $connection->prepare('UPDATE ibb_users SET balance_ibbUsers = :balanceValue WHERE id_ibbUsers = :userId');
			$transact->bindParam(':balanceValue', $value);
			$transact->bindParam(':userId', $userId);
			$transact->execute();

			$return = true;
		}catch(\PDOException $e){
			$return = false;
		}

		$connection = null;

		return $return;
	}

	public static function saveTransactHistory($action, $value, $ip, $userId){
		try{
			$connection = new \PDO(self::hostAndDbName, self::dbUser, self::dbUserPassword);
			$connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

			$saveTransact = $connection->prepare('INSERT INTO ibb_transacts(action_ibbTransacts, value_ibbTransacts, ip_ibbTransacts, userId_ibbTransacts) VALUES (:transactAction, :transactValue, :transactIp, :transactUserId)');
			$saveTransact->bindParam(':transactAction', $action);
			$saveTransact->bindParam(':transactValue', $value);
			$saveTransact->bindParam(':transactIp', $ip);
			$saveTransact->bindParam(':transactUserId', $userId);
			$saveTransact->execute();

			$return = true;
		}catch(\PDOException $e){
			$return = false;
		}

		$connection = null;

		return $return;
	}

}