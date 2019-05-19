<?php
namespace src\app\views;

require_once('View.php');

class PanelWithdrawView extends View {

    public function __construct($pageTitle){
		$this->pageTitle = $pageTitle;
		$this->viewContent = '<main><div class="container"><div class="row my-5"><div class="col-8 mx-auto shadow-lg rounded p-4"><h4 class="text-center text-uppercase text-dark"><i class="fas fa-hand-holding-usd"></i> Retirar</h4><form method="POST"><div class="form-group shadow-sm rounded p-2"><input autocomplete="off" required type="number" class="form-control mt-2" id="formWithdraw_value" name="formWithdraw_value" placeholder="Valor a ser retirado"></div><div class="form-group shadow-sm rounded p-2"><p class="text-info"><b>Confirme a senha da sua conta para concluir a operação</b></p><input autocomplete="off" required minlength="6" maxlength="6" type="password" class="form-control mt-2 border-info text-info" id="formWithdraw_pw" name="formWithdraw_pw" placeholder="Senha da sua conta"></div><button type="submit" class="btn btn-success float-right">Concluir <i class="fas fa-check"></i></button>'.\src\app\helpers\Forms::generateInputValidator('formWithdraw_withdraw').'</form></div></div></div></main>';
	}

}