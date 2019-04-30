<?php
namespace src\app\views;

require_once('View.php');

class IndexView extends View {

	public function __construct($pageName){
		$this->pageName = $pageName;
		$this->bodyContent = '<main>
			<div class="container">
				<div class="card text-center mt-5">
					<div class="card-header">
						<h2 class="text-uppercase">Internet Banking Basic</h2>
					</div>
					<div class="card-body">
						<h3 class="card-title">Bem-vindo ao sistema!</h3>
						<p class="card-text">Escolha uma das opções abaixo para continuar...</p>
						<p>
							<small class="d-block mb-1 font-italic">Já possuí conta?</small>
							<a href="login" class="btn btn-primary">Acessar o sistema</a>
						</p>
						<p>
							<small class="d-block mb-1 font-italic">Não possuí conta?</small>
							<a href="cadastro" class="btn btn-secondary">Registrar-se no sistema</a>
						</p>
					</div>
				</div>
			</div>
		</main>';
	}

	protected function body(){
		return  $this->bodyContent;
	}

}