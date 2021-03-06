<?php
namespace src\app\views;

require_once('src/app/config.php');

abstract class View {

	protected $pageTitle, $viewContent, $viewMessages = array(), $viewScripts = array(),
	$viewMenuLogged = '<li class="nav-item"><a class="nav-link" href="'.SYS_DEFAULT_URI.'/painel"><i class="fas fa-exchange-alt"></i> Transferir</a></li><li class="nav-item"><a class="nav-link" href="'.SYS_DEFAULT_URI.'/painel/retirar"><i class="fas fa-hand-holding-usd"></i> Retirar</a></li><li class="nav-item"><a class="nav-link" href="'.SYS_DEFAULT_URI.'/painel/depositar"><i class="fas fa-coins"></i> Depositar</a></li><li class="nav-item"><a class="nav-link" href="'.SYS_DEFAULT_URI.'/painel/historico"><i class="fas fa-receipt"></i> Histórico de Ações</a></li>';
	private const acceptableMessageTypes = array('error', 'info', 'warning', 'success');

	/**
	 * Gera o Header padrão da aplicação
	 *
	 * @param string $headerType
	 * @param string $loggedUserName
	 * @param string $loggedUserBalance
	 * @return void
	 */
	private function header($headerType, $loggedUserName, $loggedUserBalance){
		$head = '<!DOCTYPE html><html lang="pt-br"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta http-equiv="X-UA-Compatible" content="ie=edge"><title>'. SYS_PAGES_PREFIX.' | '.$this->pageTitle .'</title><link rel="stylesheet" href="'.SYS_DEFAULT_URI.'/assets/css/style.css"></head><body><header class="ibb-header"><nav class="navbar navbar-expand navbar-dark bg-dark justify-content-between"><a href="'.SYS_DEFAULT_URI.'" class="navbar-brand ml-2"><img src="'.SYS_DEFAULT_URI.'/assets/images/logo_white.png" width="85px" class="d-inline-block align-top img-fluid" alt="LuanDEV Logo"></a>';

		switch($headerType){
			case 'not-logged':
				$head .= '<ul class="navbar-nav"><li class="nav-item"><a class="btn btn-outline-success mr-2" href="'.SYS_DEFAULT_URI.'/login">Acessar</a></li><li class="nav-item"><a class="btn btn-outline-warning mr-2" href="'.SYS_DEFAULT_URI.'/cadastro">Cadastrar</a></li></ul></nav></header>';
			break;

			case 'logged':
				$head .= '<ul class="navbar-nav mr-auto">'.$this->viewMenuLogged.'</ul><ul class="navbar-nav"><li class="nav-item"><p class="navbar-text mb-0 mr-3"><span class="badge badge-success">SALDO <i>'.$loggedUserBalance.'</i></span></p></li><li class="nav-item"><p class="navbar-text text-light mb-0 mr-4"><span class="badge badge-info"><i class="fas fa-user-circle"></i> BEM-VINDO(A), '.$loggedUserName.'</span></p></li><li class="nav-item"><a class="btn btn-danger mr-2" href="'.SYS_DEFAULT_URI.'/painel/sair">Encerrar sessão</a></li></ul></nav></header>';
			break;
		}

		return $head;
	}

	/**
	 * Adiciona um script a view
	 *
	 * @param string $scriptWay
	 * @return void
	 */
	public function setScriptOnView($scriptWay){
		array_push($this->viewScripts, '<script src="'.SYS_DEFAULT_URI.$scriptWay.'"></script>');
	}

	/**
	 * Obtém todos os scripts da view
	 *
	 * @return void
	 */
	private function getViewScripts(){
		return join('', $this->viewScripts);
	}

	/**
	 * Insere um novo item ao final do menu
	 *
	 * @param string $itemName
	 * @param string $itemIcon
	 * @param string $itemUrl
	 * @return void
	 */
	public function setItemOnViewMenuLogged($itemName, $itemIcon, $itemUrl){
		$this->viewMenuLogged .= '<li class="nav-item"><a class="nav-link" href="'.SYS_DEFAULT_URI.$itemUrl.'"><i class="'.$itemIcon.'"></i> '.$itemName.'</a></li>';
	}

	/**
	 * Gera o Footer padrão da aplicação
	 *
	 * @return void
	 */
	private function footer(){
		return '<footer class="bg-dark py-2"><div class="container"><p class="text-center text-light mb-0">Todos direitos reservados 2019 &copy; LuanDEV.</p></div></footer>'.$this->getViewScripts();
	}

	/**
	 * Insere o conteúdo para renderização na view
	 *
	 * @param string $viewContent
	 * @return void
	 */
	public function setViewContent($viewContent){
		$this->viewContent = $viewContent;
	}

	/**
	 * Gera o conteúdo do corpo das páginas da aplicação
	 *
	 * @return void
	 */
	private function body(){
		$bodyContent = '';

		if(!empty($this->viewMessages)){
			$bodyContent .= $this->getViewMessages();
		}
		$bodyContent .= $this->viewContent;

		return $bodyContent;
	}

	/**
	 * Insere uma mensagem na view
	 *
	 * @param string $messageContent
	 * @param string $messageType
	 * @return void
	 */
	public function setViewMessage($messageContent, $messageType = 'error'){
		if(in_array($messageType, self::acceptableMessageTypes)){			
			$this->viewMessages[$messageType][] = $messageContent;
		}
	}

	/**
	 * Pega as mensagens inseridas na view
	 *
	 * @return void
	 */
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
	 * @param string $headerType
	 * @param string $loggedUserName
	 * @param string $loggedUserBalance
	 * @return void
	 */
	public function generateView($headerType = 'not-logged', $loggedUserName = '', $loggedUserBalance = ''){
		return $this->header($headerType, $loggedUserName, $loggedUserBalance).$this->body().$this->footer();
	}

}