<?php
namespace src\app\views;

require_once('View.php');
require_once('src/app/helpers/Forms.php');

class RegisterView extends View {

	public function __construct($pageTitle){
		$this->pageTitle = $pageTitle;
		$this->viewContent = '<main><div class="container"><div class="row my-5"><div class="col-10 mx-auto shadow-lg rounded p-4"><h4 class="text-center text-uppercase text-dark">Cadastro</h4><form method="POST"><div class="form-group shadow-sm rounded p-2 mt-4"><div class="row"><div class="col-md-6 col-sm-12"><input autocomplete="off" required type="text" class="form-control" id="formRegister_firstName" name="formRegister_firstName" placeholder="Nome"></div><div class="col-md-6 col-sm-12"><input autocomplete="off" required type="text" class="form-control" id="formRegister_lastName" name="formRegister_lastName" placeholder="Sobrenome"></div><div class="col-md-12 col-sm-12 mt-2"><input autocomplete="off" required type="email" class="form-control" id="formRegister_email" name="formRegister_email" placeholder="E-mail"></div></div></div><div class="form-group shadow-sm rounded p-2"><label for="formRegister_account"><i class="fas fa-user-circle"></i> Identificador da conta:</label> <input readonly="readonly" autocomplete="off" required minlength="10" maxlength="10" type="text" class="form-control" id="formRegister_account" name="formRegister_account" value="'.\src\app\helpers\Forms::generateAccountNumber().'"></div><div class="form-group shadow-sm rounded p-2"><label for="formRegister_pw"><i class="fas fa-key"></i> Senha da conta:</label> <small class="form-text text-muted">* A senha da conta deve possuir 6 dígitos.</small> <input autocomplete="off" required minlength="6" maxlength="6" type="password" class="form-control mt-2" id="formRegister_pw" name="formRegister_pw" placeholder="Insira sua senha"> <input autocomplete="off" required minlength="6" maxlength="6" type="password" class="form-control mt-2" id="formRegister_pwConfirm" name="formRegister_pwConfirm" placeholder="Confirmar senha"></div><button type="submit" class="btn btn-success float-right">Finalizar <i class="fas fa-check"></i></button>'.\src\app\helpers\Forms::generateInputValidator('formRegister_register').'</form></div></div></div></main>';
	}

}