<?php
namespace src\app\views;

require_once('View.php');
require_once('src/app/helpers/Forms.php');

class LoginView extends View {

	public function __construct($pageTitle){
		$this->pageTitle = $pageTitle;
		$this->viewContent = '<main><div class="container"><div class="row my-5"><div class="col-8 mx-auto shadow-lg rounded p-4"><h4 class="text-center text-uppercase text-dark">Acessar o Painel</h4><form method="POST"><div class="form-group shadow-sm rounded p-2"><label for="formLogin_account"><i class="fas fa-user-circle"></i> Identificador da conta:</label> <input autocomplete="off" required minlength="10" maxlength="10" type="text" class="form-control" id="formLogin_account" name="formLogin_account" placeholder="Insira o Identificador da sua conta"> <small class="form-text text-muted">* O Identificador da conta possuí 10 caracteres.</small></div><div class="form-group shadow-sm rounded p-2"><label for="formLogin_pw"><i class="fas fa-key"></i> Senha da conta:</label> <input autocomplete="off" required minlength="6" maxlength="6" type="password" class="form-control" id="formLogin_pw" name="formLogin_pw" placeholder="Insira sua senha"> <small class="form-text text-muted">* A senha da conta possuí 6 dígitos.</small></div><button type="submit" class="btn btn-outline-success float-right">Acessar <i class="fas fa-sign-in-alt"></i></button>'.\src\app\helpers\Forms::generateInputValidator('formLogin_login').'</form></div></div></div></main>';
	}

}