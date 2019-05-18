<?php
namespace src\app\models;

require_once('User.php');

class AdminManagement extends User{

	private $adminId, $viewContent;

	public function __construct(){
		if(session_status() === PHP_SESSION_NONE){
			session_start();
		}
		$this->adminId = $_SESSION['userLogged'];

		$usersForManagement = \src\app\services\Database::getUsersForAdminManagement();
		if(!empty($usersForManagement)){
			$usersRows = '';
			for($i = 0; $i < count($usersForManagement); $i++){
				$usersRows .= '<tr><td scope="row"><b>#'.$usersForManagement[$i]->id_ibbUsers.'</b></td><td><input type="checkbox" class="acc-edit"></td><td><p class="text-muted my-0">'.$usersForManagement[$i]->firstName_ibbUsers.' '.$usersForManagement[$i]->lastName_ibbUsers.'</p></td><td><input type="text" class="accs-num form-control" maxlength="10" value="'.$usersForManagement[$i]->account_ibbUsers.'"></td><td><input type="text" class="accs-balance form-control" value="'.$usersForManagement[$i]->balance_ibbUsers.'"></td><td><input type="checkbox" class="accs-delete" value="'.$usersForManagement[$i]->id_ibbUsers.'"></td><input type="hidden" class="accs-id" value="'.$usersForManagement[$i]->id_ibbUsers.'"></tr>';
			}

			$this->viewContent = '<main><div class="container"><div class="py-5"><form method="post"><table class="table table-striped table-bordered"><thead class="text-center"><tr><th scope="col">ID</th><th scope="col">ALTERAR?</th><th scope="col">TITULAR DA CONTA</th><th scope="col">Nº DE CONTA</th><th scope="col">SALDO DA CONTA</th><th scope="col">EXCLUIR?</th></tr></thead><tbody class="text-center">'.$usersRows.'</tbody></table><button type="submit" class="btn btn-outline-success float-right"><i class="fas fa-save"></i> Salvar alterações</button>'.\src\app\helpers\Forms::generateInputValidator('formAdmin_admin').'</form></div></div></main>';
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
	 * @param array $accsBalance
	 * @param array $accsDelete
	 * @param array $accsId
	 * @return void
	 */
	public function saveManagement($formValidator, $accsNum, $accsBalance, $accsDelete, $accsId){
		if(\src\app\helpers\Forms::validateInputValidator($formValidator)){
			$accountsNum = $accsNum;
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
					unset($accountsBalance[$keyToRemove]);
					unset($accountsId[$keyToRemove]);
				}
			}

			if(!empty($accountsNum) && !empty($accountsBalance) && !empty($accountsId)){
				$accountsNum = array_values($accountsNum);
				$accountsBalance = array_values($accountsBalance);
				$accountsId = array_values($accountsId);

				for($i = 0; $i < count($accountsId); $i++){
					try{
						\src\app\services\Database::updateAccountByAdminManagement($accountsNum[$i], floatval($accountsBalance[$i]), $accountsId[$i]);

						try{
							\src\app\services\Database::saveTransactHistory('Atualização da conta com ID #['.$accountsId[$i].']', floatval($accountsBalance[$i]), 'Admin Management', $this->adminId);
						}catch(\Exception $e){
							$this->setError($e->getMessage()." <b>(CÓDIGO DO ERRO: #".$e->getCode().")</b>");
						}

						$this->setSuccess('As informações da conta ID <b>#'.$accountsId[$i].'</b> foram atualizadas com sucesso!');
					}catch(\Exception $e){
						$this->setError($e->getMessage()." <b>(CÓDIGO DO ERRO: #".$e->getCode().")</b>");
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