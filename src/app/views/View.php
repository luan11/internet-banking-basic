<?php
namespace src\app\views;

require_once('src/app/config.php');

abstract class View {

	protected $pageTitle;
	protected $viewContent;
	protected $viewMessages = array();
	private const acceptableMessageTypes = array('error', 'info', 'warning', 'success');

	/**
	 * Gera o Header padrão da aplicação
	 *
	 * @return void
	 */
	protected function header($headerType, $loggedUserName, $loggedUserBalance){
		$head = '<!DOCTYPE html><html lang="pt-br"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta http-equiv="X-UA-Compatible" content="ie=edge"><title>'. SYS_PAGES_PREFIX.' | '.$this->pageTitle .'</title><link rel="stylesheet" href="assets/css/style.css"></head><body><header class="ibb-header"><nav class="navbar navbar-expand navbar-dark bg-dark justify-content-between"><a href="'.SYS_DEFAULT_URI.'" class="navbar-brand ml-2"><img src="assets/images/logo_white.png" width="85px" class="d-inline-block align-top img-fluid" alt="LuanDEV Logo"></a>';

		switch($headerType){
			case 'not-logged':
				$head .= '<ul class="navbar-nav"><li class="nav-item"><a class="btn btn-outline-success mr-2" href="'.SYS_DEFAULT_URI.'/login">Acessar</a></li><li class="nav-item"><a class="btn btn-outline-warning mr-2" href="'.SYS_DEFAULT_URI.'/cadastro">Cadastrar</a></li></ul></nav></header>';
			break;

			case 'logged':
				$head .= '<ul class="navbar-nav mr-auto"><li class="nav-item"><a class="nav-link" href="'.SYS_DEFAULT_URI.'/painel"><i class="fas fa-exchange-alt"></i> Transferir</a></li><li class="nav-item"><a class="nav-link" href="'.SYS_DEFAULT_URI.'/painel/retirar"><i class="fas fa-hand-holding-usd"></i> Retirar</a></li><li class="nav-item"><a class="nav-link" href="'.SYS_DEFAULT_URI.'/painel/depositar"><i class="fas fa-coins"></i> Depositar</a></li><li class="nav-item"><a class="nav-link" href="'.SYS_DEFAULT_URI.'/painel/historico"><i class="fas fa-receipt"></i> Histórico de Ações</a></li></ul><ul class="navbar-nav"><li class="nav-item"><p class="navbar-text mb-0 mr-3"><span class="badge badge-success">'.$loggedUserBalance.'</span></p></li><li class="nav-item"><p class="navbar-text text-light mb-0 mr-4"><span class="badge badge-info"><i class="fas fa-user-circle"></i> '.$loggedUserName.'</span></p></li><li class="nav-item"><a class="btn btn-danger mr-2" href="'.SYS_DEFAULT_URI.'/painel/sair">Encerrar sessão</a></li></ul></nav></header>';
			break;
		}

		return $head;
	}

	/**
	 * Gera o Footer padrão da aplicação
	 *
	 * @return void
	 */
	protected function footer(){
		return '<footer class="bg-dark py-2"><div class="container"><p class="text-center text-light mb-0">Todos direitos reservados 2019 &copy; LuanDEV.</p></div></footer>';
	}

	/**
	 * Gera o conteúdo do corpo das páginas da aplicação
	 *
	 * @return void
	 */
	protected function body(){
		$bodyContent = '';

		if(!empty($this->viewMessages)){
			$bodyContent .= $this->getViewMessages();
		}
		$bodyContent .= $this->viewContent;

		return $bodyContent;
	}

	public function setViewMessage($messageContent, $messageType = 'error'){
		if(in_array($messageType, self::acceptableMessageTypes)){			
			$this->viewMessages[$messageType][] = $messageContent;
		}
	}

	private function getViewMessages(){
		$messages = '<div class="container mt-4">';

		if(!empty($this->viewMessages['error'])){
			$messages .= '<div class="alert alert-danger">'.join('<br>', $this->viewMessages['error']).'</div>';
		}		
		if(!empty($this->viewMessages['success'])){
			$messages .= '<div class="alert alert-success">'.join('<br>', $this->viewMessages['success']).'</div>';
		}		
		if(!empty($this->viewMessages['warning'])){
			$messages .= '<div class="alert alert-warning">'.join('<br>', $this->viewMessages['warning']).'</div>';
		}		
		if(!empty($this->viewMessages['info'])){
			$messages .= '<div class="alert alert-info">'.join('<br>', $this->viewMessages['info']).'</div>';
		}

		$messages .= '</div>';
		
		return $messages;
	}

	/**
	 * Junta o Header, o Body e o Footer e retorna a visualização completa da aplicação
	 *
	 * @return void
	 */
	public function generateView($headerType = 'not-logged', $loggedUserName = '', $loggedUserBalance = ''){
		return $this->header($headerType, $loggedUserName, $loggedUserBalance).$this->body().$this->footer();
	}

}