<?php
namespace src\app\services;

require_once('src/app/config.php');

abstract class Database{

	private const hostAndDbName = 'mysql:host='.SYS_DB_HOST.';dbname='.SYS_DB_NAME.';charset=utf8';
	private const dbUser = SYS_DB_USER;
	private const dbUserPassword = SYS_DB_USER_PASSWORD;

	/**
	 * Valida se o usuário logado no sistema é veradeiro
	 *
	 * @param int $loggedUserId
	 * @return void
	 */
	public static function validateLoggedUser($loggedUserId){
		try{
			$connection = new \PDO(self::hostAndDbName, self::dbUser, self::dbUserPassword);
			$connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

			$verifyLoggedUser = $connection->prepare('SELECT firstName_ibbUsers, lastName_ibbUsers, balance_ibbUsers, role_ibbUsers FROM ibb_users WHERE id_ibbUsers = :loggedUserId');
			$verifyLoggedUser->bindParam(':loggedUserId', $loggedUserId);
			$verifyLoggedUser->execute();

			$return = $verifyLoggedUser->fetch(\PDO::FETCH_OBJ);
		}catch(\PDOException $e){
			$return = false;
		}

		$connection = null;

		return $return;
	}

	/**
	 * Valida e realiza o login do usuário no sistema
	 *
	 * @param int $userAccount
	 * @param string $userPassword
	 * @return void
	 */
	public static function userLogin($userAccount, $userPassword){
		try{
			$return = false;

			$connection = new \PDO(self::hostAndDbName, self::dbUser, self::dbUserPassword);
			$connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

			$userLogin = $connection->prepare('SELECT id_ibbUsers, password_ibbUsers FROM ibb_users WHERE BINARY account_ibbUsers = :userAccount');
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

	/**
	 * Faz o registro de um novo usuário no sistema
	 *
	 * @param string $userFirstName
	 * @param string $userLastName
	 * @param string $userEmail
	 * @param string $userAccount
	 * @param string $userPassword
	 * @return void
	 */
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

	/**
	 * Pega os históricos de transação do usuário logado
	 *
	 * @param int $userId
	 * @return void
	 */
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

	/**
	 * Valida a senha do usuário logado no ato de uma transação
	 *
	 * @param int $userId
	 * @param string $userPassword
	 * @return void
	 */
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

	/**
	 * Valida se a conta que vai receber uma transferência é valida
	 *
	 * @param string $accountNumber
	 * @return void
	 */
	public static function validateAccountReceiver($accountNumber){
		try{
			$connection = new \PDO(self::hostAndDbName, self::dbUser, self::dbUserPassword);
			$connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

			$validateReceiver = $connection->prepare('SELECT balance_ibbUsers, id_ibbUsers FROM ibb_users WHERE BINARY account_ibbUsers = :accountNumber');
			$validateReceiver->bindParam(':accountNumber', $accountNumber);
			$validateReceiver->execute();

			$return = $validateReceiver->fetch(\PDO::FETCH_OBJ);
		}catch(\PDOException $e){
			$return = false;
		}

		$connection = null;

		return $return;
	}

	/**
	 * Realiza uma transação (Retirada ou Depósito)
	 *
	 * @param int $userId
	 * @param float $value
	 * @return void
	 */
	public static function doTransact($userId, $value){
		try{
			$connection = new \PDO(self::hostAndDbName, self::dbUser, self::dbUserPassword);
			$connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

			$transact = $connection->prepare('UPDATE ibb_users SET balance_ibbUsers = :balanceValue WHERE id_ibbUsers = :userId');
			$transact->bindParam(':balanceValue', $value);
			$transact->bindParam(':userId', $userId);
			$transact->execute();

			return true;
		}catch(\PDOException $e){
			throw new \Exception("Erro ao efetuar a transação", 1);
		}

		$connection = null;
	}

	/**
	 * Salva a transação realizada no histórico
	 *
	 * @param string $action
	 * @param float $value
	 * @param string $ip
	 * @param int $userId
	 * @return void
	 */
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
			
			return true;
		}catch(\PDOException $e){
			throw new \Exception("Error ao salvar a transação no histórico", 2);
		}

		$connection = null;
	}

	/**
	 * Pega os usuários para o Admin fazer o gerenciamento
	 *
	 * @param string $roleName
	 * @return void
	 */
	public static function getUsersForAdminManagement($roleName = 'admin'){
		try{
			$connection = new \PDO(self::hostAndDbName, self::dbUser, self::dbUserPassword);
			$connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

			$usersForManagement = $connection->prepare('SELECT id_ibbUsers, firstName_ibbUsers, lastName_ibbUsers, account_ibbUsers, balance_ibbUsers FROM ibb_users WHERE role_ibbUsers != :roleName');
			$usersForManagement->bindParam(':roleName', $roleName);
			$usersForManagement->execute();

			$return = $usersForManagement->fetchAll(\PDO::FETCH_CLASS);
		}catch(\PDOException $e){
			$return = false;
		}

		$connection = null;

		return $return;
	}

	/**
	 * Remove um usuário da base de dados
	 *
	 * @param int $userId
	 * @return void
	 */
	public static function deleteUser($userId){
		try{
			$connection = new \PDO(self::hostAndDbName, self::dbUser, self::dbUserPassword);
			$connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

			$userForRemoveHistory = $connection->prepare('DELETE FROM ibb_transacts WHERE userId_ibbTransacts = :userId');
			$userForRemoveHistory->bindParam(':userId', $userId);
			$userForRemoveHistory->execute();

			$userForRemove = $connection->prepare('DELETE FROM ibb_users WHERE id_ibbUsers = :userId');
			$userForRemove->bindParam(':userId', $userId);
			$userForRemove->execute();

			return true;
		}catch(\PDOException $e){
			return false;
		}
	}

	/**
	 * Faz a atualização do número de conta e saldo do usuário
	 *
	 * @param string $accNum
	 * @param float $accBalance
	 * @param int $userId
	 * @return void
	 */
	public static function updateAccountByAdminManagement($accNum, $accBalance, $userId){
		try{
			$connection = new \PDO(self::hostAndDbName, self::dbUser, self::dbUserPassword);
			$connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

			$updateAccount = $connection->prepare('UPDATE ibb_users SET account_ibbUsers = :accountNumber, balance_ibbUsers = :balanceValue WHERE id_ibbUsers = :userId');
			$updateAccount->bindParam(':accountNumber', $accNum);
			$updateAccount->bindParam(':balanceValue', $accBalance);
			$updateAccount->bindParam(':userId', $userId);
			$updateAccount->execute();

			return true;
		}catch(\PDOException $e){
			throw new \Exception("Erro ao efetuar a atualização", 3);
		}

		$connection = null;
	}

}