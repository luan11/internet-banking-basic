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
	protected function header(){
		return '<!DOCTYPE html>
		<html lang="pt-br">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta http-equiv="X-UA-Compatible" content="ie=edge">
			<meta name="author" content="Luan Novais">
			<title>'.SYS_PAGES_PREFIX.' | '.$this->pageTitle.'</title>
		</head>
		<body>';
	}

	/**
	 * Gera o Footer padrão da aplicação
	 *
	 * @return void
	 */
	protected function footer(){
		return '</body>
		</html>';
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
		$messages = '';

		if(!empty('error')){
			$messages .= '<div style="color: red;">'.join('<br>', $this->viewMessages['error']).'</div>';
		}
		if(!empty('info')){
			$messages .= '<div style="color: blue;">'.join('<br>', $this->viewMessages['info']).'</div>';
		}
		if(!empty('warning')){
			$messages .= '<div style="color: orange;">'.join('<br>', $this->viewMessages['warning']).'</div>';
		}
		if(!empty('success')){
			$messages .= '<div style="color: green;">'.join('<br>', $this->viewMessages['success']).'</div>';
		}
		
		return $messages;
	}

	/**
	 * Junta o Header, o Body e o Footer e retorna a visualização completa da aplicação
	 *
	 * @return void
	 */
	public function generateView(){
		return $this->header().$this->body().$this->footer();
	}

}