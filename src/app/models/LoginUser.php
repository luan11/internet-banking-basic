<?php
namespace src\app\models;

require_once('User.php');

class Login extends User {

    public function __construct($formValidator, $userAccount, $userPassword){
        if(\src\app\helpers\Forms::validateInputValidator($formValidator)){
            if(!empty(filter_var($userAccount, FILTER_SANITIZE_STRING)) && 
            !empty(filter_var($userPassword, FILTER_SANITIZE_STRING))){
                if(strlen(filter_var($userAccount, FILTER_SANITIZE_STRING)) === 10 && 
                strlen(filter_var($userPassword, FILTER_SANITIZE_STRING)) === 6){
                    $this->userAccount = filter_var($userAccount, FILTER_SANITIZE_STRING);
                    $this->userPassword = filter_var($userPassword, FILTER_SANITIZE_STRING);

                    $login = $this->dbLogin($this);
                    if(!empty($login)){
                        if(session_status() === PHP_SESSION_NONE){
                            session_start();
                        }
                        $_SESSION['userLogged'] = $login;
                    }else{
                        $this->setError("Número de conta e/ou senha incorretos!");
                    }
                }else{
                    $this->setError("Parece que os campos digitados estão fora dos padrões, tente novamente!");
                }
            }else{
                $this->setError("É necessário preencher todos os campos!");
            }
        }else{
            $this->setError("Não foi possível realizar o login, tente novamente!");
        }
    }

}