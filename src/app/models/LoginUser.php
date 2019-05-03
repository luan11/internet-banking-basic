<?php
namespace src\app\models;

require_once('User.php');

class Login extends User {

    public function __construct($formValidator, $userAccount, $userPassword){
        if(\src\app\helpers\Forms::validateInputValidator($formValidator)){
            if(!empty($userAccount) && !empty($userPassword)){
                if(strlen($userAccount) === 10 && strlen($userPassword) === 6){
                    $this->userAccount = $userAccount;
                    $this->userPassword = $userPassword;

                    var_dump($this);
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