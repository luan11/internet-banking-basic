<?php
namespace src\app\models;

require_once('User.php');

class Register extends User {

    public function __construct($formValidator, $userFirstName, $userLastName, $userEmail, $userAccount, $userPassword, $userPasswordConfirm){
        if(\src\app\helpers\Forms::validateInputValidator($formValidator)){
            if(!empty(filter_var($userFirstName, FILTER_SANITIZE_STRING)) && 
            !empty(filter_var($userLastName, FILTER_SANITIZE_STRING)) && 
            !empty(filter_var($userEmail, FILTER_SANITIZE_EMAIL)) && 
            !empty(substr(filter_var($userAccount, FILTER_SANITIZE_STRING), 0, 10)) && 
            !empty(filter_var($userPassword, FILTER_SANITIZE_STRING)) && 
            !empty(filter_var($userPassword, FILTER_SANITIZE_STRING))){
                $this->userFirstName = filter_var($userFirstName, FILTER_SANITIZE_STRING);
                $this->userLastName = filter_var($userLastName, FILTER_SANITIZE_STRING);
                $this->userEmail = filter_var($userEmail, FILTER_SANITIZE_EMAIL);
                $this->userAccount = substr(filter_var($userAccount, FILTER_SANITIZE_STRING), 0, 10);
                $this->confirmPasswords($userPassword, $userPasswordConfirm);

                if(empty($this->getErrors())){
                    if($this->dbRegister($this)){
                        $this->setSuccess('Sua conta foi criada com sucesso, utilize o seu número de conta <span class="badge badge-success">'.$this->userAccount.'</span> para realizar o login.');
                    }else{
                        $this->setError('Não foi possível finalizar o cadastro, tente novamente!');
                    }
                }
            }else{
                $this->setError("É obrigatório o preenchimento de todos os campos!");
            }
        }else{
            $this->setError("Não foi possível concluir o cadastro, tente novamente!");
        }
    }

}