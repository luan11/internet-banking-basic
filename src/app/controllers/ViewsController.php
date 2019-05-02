<?php
namespace src\app\controllers;

require_once('src/app/helpers/PrepareEnvironment.php');
require_once('src/app/helpers/Forms.php');
require_once('src/app/views/IndexView.php');
require_once('src/app/views/LoginView.php');
require_once('src/app/views/RegisterView.php');
require_once('src/app/models/User.php');

class ViewsController {

    public function __construct($page){
        switch($page){
            case '':
                \src\app\helpers\PrepareEnvironment::startEnvironment();
				$render = new \src\app\views\IndexView("Home");
				if(\src\app\models\User::userIsLoggedIn()){
					echo $render->generateView('logged', 'Luan', 'R$ 1500,00');
				}else{					
					echo $render->generateView();
				}
            break;

			case 'login':			
				$render = new \src\app\views\LoginView("Entrar");
				
                if(isset($_POST['formLogin_login']) && \src\app\helpers\Forms::validateInputValidator($_POST['formLogin_login'])){
                    var_dump($_POST);
				}
				
				\src\app\models\User::userIsLoggedIn('REDIRECT');
				echo $render->generateView();
            break;

            case 'cadastro':
				$render = new \src\app\views\RegisterView("Cadastrar");
				
                if(isset($_POST['formRegister_register'])){
                    $user = new \src\app\models\User($_POST['formRegister_register'], $_POST['formRegister_firstName'], $_POST['formRegister_lastName'], $_POST['formRegister_email'], $_POST['formRegister_account'], $_POST['formRegister_pw'], $_POST['formRegister_pwConfirm']);
                    
                    if(!empty($user->getErrors())){
                        for($i = 0; $i < count($user->getErrors()); $i++){
                            $render->setViewMessage($user->getErrors()[$i]);
                        }
                    }
				}
				
				\src\app\models\User::userIsLoggedIn('REDIRECT');
                echo $render->generateView();
            break;

            default:
                $render = new \src\app\views\IndexView("Página não encontrada!");
                $render->setViewMessage("<b>404</b><br>Página não encontrada =(", "warning");
                if(\src\app\models\User::userIsLoggedIn()){
					echo $render->generateView('logged', 'Luan', 'R$ 1500,00');
				}else{					
					echo $render->generateView();
				}
            break;
        }
    }

}