<?php
namespace src\app\models;

require_once('User.php');

class AdminManagement extends User{

	private $adminId, $viewContent;
	private const userRolesAcceptables = array('subadmin', 'user');

	public function __construct(){
		if(session_status() === PHP_SESSION_NONE){
			session_start();
		}
		$this->adminId = $_SESSION['userLogged'];

		$usersForManagement = \src\app\services\Database::getUsersForAdminManagement();
		if(!empty($usersForManagement)){
			$usersRows = '';
			for($i = 0; $i < count($usersForManagement); $i++){
				$checkedUser = $usersForManagement[$i]->role_ibbUsers === 'user' ? 'selected' : '';
				$checkedSubAdmin = $usersForManagement[$i]->role_ibbUsers === 'subadmin' ? 'selected' : '';

				$usersRows .= '<tr><td scope="row"><b>#'.$usersForManagement[$i]->id_ibbUsers.'</b></td><td><input type="checkbox" class="acc-edit"></td><td><p class="text-muted my-0">'.$usersForManagement[$i]->firstName_ibbUsers.' '.$usersForManagement[$i]->lastName_ibbUsers.'</p></td><td><input type="text" class="accs-num form-control" maxlength="10" value="'.$usersForManagement[$i]->account_ibbUsers.'"></td><td><input type="password" class="accs-new-password form-control" maxlength="6"></td><td><select class="accs-role form-control"><option value="subadmin" '.$checkedSubAdmin.'>Subadmin</option><option value="user" '.$checkedUser.'>Usuário</option></select></td><td><input type="text" class="accs-balance form-control" value="'.$usersForManagement[$i]->balance_ibbUsers.'"></td><td><input type="checkbox" class="accs-delete" value="'.$usersForManagement[$i]->id_ibbUsers.'"></td><input type="hidden" class="accs-id" value="'.$usersForManagement[$i]->id_ibbUsers.'"></tr>';
			}

			$this->viewContent = '<main><div class="container"><div class="py-5"><form method="post"><table class="table table-striped table-bordered"><thead class="text-center"><tr><th scope="col"><i class="fas fa-hashtag text-info"></i> ID</th><th scope="col"><i class="fas fa-pencil-alt text-warning"></i> ALTERAR?</th><th scope="col"><i class="fas fa-user text-primary"></i> NOME</th><th scope="col"><i class="fas fa-user-circle text-primary"></i> CONTA</th><th scope="col"><i class="fas fa-key text-primary"></i> SENHA</th><th scope="col"><i class="fas fa-cogs text-primary"></i> FUNÇÃO</th><th scope="col"><i class="fas fa-dollar-sign text-success"></i> SALDO</th><th scope="col"><i class="fas fa-times text-danger"></i> EXCLUIR?</th></tr></thead><tbody class="text-center">'.$usersRows.'</tbody></table><button type="submit" class="btn btn-outline-success float-right"><i class="fas fa-save"></i> Salvar alterações</button>'.\src\app\helpers\Forms::generateInputValidator('formAdmin_admin').'</form></div></div></main>';
		}else{
			$this->viewContent = false;
		}
	}

	/**
	 * Retorna a listagem de usuários para gerenciamento em forma de view
	 *
	 * @return void
	 */
	public function getAdminManagementViewContent(){
		return $this->viewContent;
	}

	/**
	 * Salva as alterações feitas pelo admin
	 *
	 * @param string $formValidator
	 * @param array $accsNum
	 * @param array $accsPass
	 * @param array $accsRole
	 * @param array $accsBalance
	 * @param array $accsDelete
	 * @param array $accsId
	 * @return void
	 */
	public function saveManagement($formValidator, $accsNum, $accsPass, $accsRole, $accsBalance, $accsDelete, $accsId){
		if(\src\app\helpers\Forms::validateInputValidator($formValidator)){
			$accountsNum = $accsNum;
			$accountsPassword = $accsPass;
			$accountsRole = $accsRole;
			$accountsBalance = $accsBalance;
			$accountsDelete = $accsDelete;
			$accountsId = $accsId;

			if(!empty($accountsDelete)){
				for($i = 0; $i < count($accountsDelete); $i++){
					$keyToRemove = array_search($accountsDelete[$i], $accountsId);
					if(\src\app\services\Database::deleteUser($accountsDelete[$i])){
						try{
							\src\app\services\Database::saveTransactHistory('Remoção da conta ['.$accountsNum[$keyToRemove].']', 0, 'Admin Management', $this->adminId);
						}catch(\Exception $e){
							$this->setError($e->getMessage()." <b>(CÓDIGO DO ERRO: #".$e->getCode().")</b>");
						}

						$this->setSuccess('A conta <b>'.$accountsNum[$keyToRemove].'</b> foi excluída com sucesso!');
					}else{
						$this->setError('Não foi possível excluir a conta <b>'.$accountsNum[$keyToRemove].'</b>');
					}					
					unset($accountsNum[$keyToRemove]);
					unset($accountsPassword[$keyToRemove]);
					unset($accountsRole[$keyToRemove]);
					unset($accountsBalance[$keyToRemove]);
					unset($accountsId[$keyToRemove]);
				}
			}

			if(!empty($accountsNum) && !empty($accountsRole) && !empty($accountsBalance) && !empty($accountsId)){
				$accountsNum = array_values($accountsNum);
				$accountsPassword = array_values($accountsPassword);
				$accountsRole = array_values($accountsRole);
				$accountsBalance = array_values($accountsBalance);
				$accountsId = array_values($accountsId);

				for($i = 0; $i < count($accountsId); $i++){

					if(!in_array($accountsRole[$i], self::userRolesAcceptables)){
						$this->setError('O tipo de função <u>'.$accountsRole[$i].'</u> não é aceitável! <b><i>Erro encontrado na conta de ID #'.$accountsId[$i].'</i></b>');
						$this->setError('<b>A atualização das contas com os respectivos IDs: '.join(' - ', $accountsId).', pode ter sido comprometida. Confira o histórico para mais informações!</b>');
						break;
					}

					try{
						\src\app\services\Database::updateAccountByAdminManagement($accountsNum[$i], floatval($accountsBalance[$i]), $accountsRole[$i], $accountsId[$i]);

						try{
							\src\app\services\Database::saveTransactHistory('Atualização da conta com ID #['.$accountsId[$i].']', floatval($accountsBalance[$i]), 'Admin Management', $this->adminId);
						}catch(\Exception $e){
							$this->setError($e->getMessage()." <b>(CÓDIGO DO ERRO: #".$e->getCode().")</b>");
						}

						$this->setSuccess('As informações da conta ID <b>#'.$accountsId[$i].'</b> foram atualizadas com sucesso!');
					}catch(\Exception $e){
						$this->setError($e->getMessage()." <b>(CÓDIGO DO ERRO: #".$e->getCode().")</b>");
					}

					if(!empty($accountsPassword[$i]) && strlen($accountsPassword[$i]) === 6){
						$accountNewPassword = password_hash($accountsPassword[$i], PASSWORD_BCRYPT);

						try{
							\src\app\services\Database::updateAccountPasswordByAdminManagement($accountNewPassword, $accountsId[$i]);
	
							try{
								\src\app\services\Database::saveTransactHistory('Atualização de senha da conta com ID #['.$accountsId[$i].']', 0, 'Admin Management', $this->adminId);
							}catch(\Exception $e){
								$this->setError($e->getMessage()." <b>(CÓDIGO DO ERRO: #".$e->getCode().")</b>");
							}
	
							$this->setSuccess('A senha da conta ID <b>#'.$accountsId[$i].'</b> foi alterada com sucesso!');
						}catch(\Exception $e){
							$this->setError($e->getMessage()." <b>(CÓDIGO DO ERRO: #".$e->getCode().")</b>");
						}
					}
				}
			}else{
				$this->setError('Nenhuma conta foi selecionada para ser modificada.');
			}

		}else{
			$this->setError("Não foi possível finalizar a operação, tente novamente!");
		}
	}

}