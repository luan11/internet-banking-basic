<?php
namespace src\app\controllers;

require_once('src/app/helpers/PrepareEnvironment.php');
require_once('src/app/helpers/Forms.php');
require_once('src/app/views/IndexView.php');
require_once('src/app/views/LoginView.php');
require_once('src/app/views/RegisterView.php');
require_once('src/app/views/PanelView.php');
require_once('src/app/views/PanelWithdrawView.php');
require_once('src/app/views/PanelDepositView.php');
require_once('src/app/views/PanelHistoryView.php');
require_once('src/app/models/User.php');
require_once('src/app/models/RegisterUser.php');
require_once('src/app/models/LoginUser.php');
require_once('src/app/models/AccountTransactions.php');

class ViewsController {

	public function __construct($page){
		switch($page){
			case '':
				\src\app\helpers\PrepareEnvironment::startEnvironment();
				$render = new \src\app\views\IndexView("Home");

				$userIsLogged = \src\app\models\User::userIsLoggedIn();
				if(!empty($userIsLogged)){
					echo $render->generateView('logged', $userIsLogged['name'], $userIsLogged['balance']);
				}else{					
					echo $render->generateView();
				}
			break;

			case 'login':			
				$render = new \src\app\views\LoginView("Entrar");
				
				if(isset($_POST['formLogin_login'])){
					$user = new \src\app\models\Login($_POST['formLogin_login'], $_POST['formLogin_account'], $_POST['formLogin_pw']);

					if(!empty($user->getErrors())){
						for($i = 0; $i < count($user->getErrors()); $i++){
							$render->setViewMessage($user->getErrors()[$i]);
						}
					}

					if(!empty($user->getSuccess())){
						for($i = 0; $i < count($user->getSuccess()); $i++){
							$render->setViewMessage($user->getSuccess()[$i], 'success');
						}
					}
				}
				
				\src\app\models\User::userIsLoggedIn('REDIRECT');
				echo $render->generateView();
			break;

			case 'cadastro':
				$render = new \src\app\views\RegisterView("Cadastrar");
				
				if(isset($_POST['formRegister_register'])){
					$user = new \src\app\models\Register($_POST['formRegister_register'], $_POST['formRegister_firstName'], $_POST['formRegister_lastName'], $_POST['formRegister_email'], $_POST['formRegister_account'], $_POST['formRegister_pw'], $_POST['formRegister_pwConfirm']);
					
					if(!empty($user->getErrors())){
						for($i = 0; $i < count($user->getErrors()); $i++){
							$render->setViewMessage($user->getErrors()[$i]);
						}
					}

					if(!empty($user->getSuccess())){
						for($i = 0; $i < count($user->getSuccess()); $i++){
							$render->setViewMessage($user->getSuccess()[$i], 'success');
						}
					}
				}
				
				\src\app\models\User::userIsLoggedIn('REDIRECT');
				echo $render->generateView();
			break;

			case 'painel':                
				$subpage = isset($_GET['subpage']) ? $_GET['subpage'] : null;
				if(!is_null($subpage)){
					switch($subpage){
						case 'retirar':
							$render = new \src\app\views\PanelWithdrawView("Retirar");

							if(isset($_POST['formWithdraw_withdraw'])){
								$transact = new \src\app\models\AccountTransactions($_POST['formWithdraw_withdraw'], $_POST['formWithdraw_value'], $_POST['formWithdraw_pw']);
		
								if(empty($transact->getErrors())){
									$transact->withdraw($transact);
								}
		
								if(!empty($transact->getErrors())){
									for($i = 0; $i < count($transact->getErrors()); $i++){
										$render->setViewMessage($transact->getErrors()[$i]);
									}
								}
		
								if(!empty($transact->getSuccess())){
									for($i = 0; $i < count($transact->getSuccess()); $i++){
										$render->setViewMessage($transact->getSuccess()[$i], 'success');
									}
								}
							}
							
							$userIsLogged = \src\app\models\User::userIsLoggedIn();
							if(!empty($userIsLogged)){
								echo $render->generateView('logged', $userIsLogged['name'], $userIsLogged['balance']);
							}else{					
								header('Location: '.SYS_DEFAULT_URI.'/login');
								exit;
							}
						break;

						case 'depositar':
							$render = new \src\app\views\PanelDepositView("Depositar");

							if(isset($_POST['formDeposit_deposit'])){
								$transact = new \src\app\models\AccountTransactions($_POST['formDeposit_deposit'], $_POST['formDeposit_value'], $_POST['formDeposit_pw']);
		
								if(empty($transact->getErrors())){
									$transact->deposit($transact);
								}
		
								if(!empty($transact->getErrors())){
									for($i = 0; $i < count($transact->getErrors()); $i++){
										$render->setViewMessage($transact->getErrors()[$i]);
									}
								}
		
								if(!empty($transact->getSuccess())){
									for($i = 0; $i < count($transact->getSuccess()); $i++){
										$render->setViewMessage($transact->getSuccess()[$i], 'success');
									}
								}
							}
							
							$userIsLogged = \src\app\models\User::userIsLoggedIn();
							if(!empty($userIsLogged)){
								echo $render->generateView('logged', $userIsLogged['name'], $userIsLogged['balance']);
							}else{					
								header('Location: '.SYS_DEFAULT_URI.'/login');
								exit;
							}
						break;

						case 'historico':
							$render = new \src\app\views\PanelHistoryView("Histórico");
							
							$userIsLogged = \src\app\models\User::userIsLoggedIn();
							if(!empty($userIsLogged)){
								echo $render->generateView('logged', $userIsLogged['name'], $userIsLogged['balance']);
							}else{					
								header('Location: '.SYS_DEFAULT_URI.'/login');
								exit;
							}
						break;

						case 'sair':
							\src\app\models\User::logout();
							header('Location: '.SYS_DEFAULT_URI);
							exit;
						break;

						default:
							$render = new \src\app\views\IndexView("Página não encontrada!");
							$render->setViewMessage("<b>404</b><br>Página não encontrada =(", "warning");
							
							$userIsLogged = \src\app\models\User::userIsLoggedIn();
							if(!empty($userIsLogged)){
								echo $render->generateView('logged', $userIsLogged['name'], $userIsLogged['balance']);
							}else{					
								header('Location: '.SYS_DEFAULT_URI.'/login');
								exit;
							}
						break;
					}
				}else{
					$render = new \src\app\views\PanelView("Transferir");
	
					if(isset($_POST['formTransfer_transfer'])){
						$transact = new \src\app\models\AccountTransactions($_POST['formTransfer_transfer'], $_POST['formTransfer_value'], $_POST['formTransfer_pw']);

						if(empty($transact->getErrors())){
							$transact->transfer($transact, $_POST['formTransfer_account']);
						}

						if(!empty($transact->getErrors())){
							for($i = 0; $i < count($transact->getErrors()); $i++){
								$render->setViewMessage($transact->getErrors()[$i]);
							}
						}

						if(!empty($transact->getSuccess())){
							for($i = 0; $i < count($transact->getSuccess()); $i++){
								$render->setViewMessage($transact->getSuccess()[$i], 'success');
							}
						}
					}
					
					$userIsLogged = \src\app\models\User::userIsLoggedIn();
					if(!empty($userIsLogged)){
						echo $render->generateView('logged', $userIsLogged['name'], $userIsLogged['balance']);
					}else{					
						header('Location: '.SYS_DEFAULT_URI.'/login');
						exit;
					}
				}
			break;

			default:
				$render = new \src\app\views\IndexView("Página não encontrada!");
				$render->setViewMessage("<b>404</b><br>Página não encontrada =(", "warning");
				$userIsLogged = \src\app\models\User::userIsLoggedIn();
				if(!empty($userIsLogged)){
					echo $render->generateView('logged', $userIsLogged['name'], $userIsLogged['balance']);
				}else{					
					echo $render->generateView();
				}
			break;
		}
	}

}