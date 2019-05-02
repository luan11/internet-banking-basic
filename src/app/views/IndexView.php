<?php
namespace src\app\views;

require_once('View.php');

class IndexView extends View {

	public function __construct($pageTitle){
		$this->pageTitle = $pageTitle;
		$this->viewContent = '<main><div class="container"><div class="row"><div class="col-12"><h1 class="text-center mt-5 text-dark"><i class="fas fa-check text-success"></i><br>Otimize suas experências bancárias com o<br><span class="text-success"><i class="fas fa-money-check"></i> Internet Banking Basic</span></h1></div></div><div class="row"><div class="col-md-3 col-sm-12"><div class="card my-5"><div class="card-body"><h4 class="card-title text-center"><i class="fas fa-exchange-alt"></i> Transfira</h4><h6 class="card-subtitle text-center mb-2 text-muted">Realize transferências de maneira simples.</h6></div></div></div><div class="col-md-3 col-sm-12"><div class="card my-5"><div class="card-body"><h4 class="card-title text-center"><i class="fas fa-hand-holding-usd"></i> Retire</h4><h6 class="card-subtitle text-center mb-2 text-muted">Realize retiradas de maneira simples.</h6></div></div></div><div class="col-md-3 col-sm-12"><div class="card my-5"><div class="card-body"><h4 class="card-title text-center"><i class="fas fa-coins"></i> Deposite</h4><h6 class="card-subtitle text-center mb-2 text-muted">Realize depósitos de maneira simples.</h6></div></div></div><div class="col-md-3 col-sm-12"><div class="card my-5"><div class="card-body"><h4 class="card-title text-center"><i class="fas fa-receipt"></i> Tenha controle</h4><h6 class="card-subtitle text-center mb-2 text-muted">Confira o histórico de todas as ações de maneira simples.</h6></div></div></div></div></div></main>';
	}

}