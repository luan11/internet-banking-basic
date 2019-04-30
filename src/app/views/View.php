<?php
namespace src\app\views;

require_once('src/app/helpers/PrepareEnvironment.php');

abstract class View {

	protected $pageName;
	protected $bodyContent;

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
			<title>'. $this->pageName .'</title>
			<link rel="stylesheet" href="src/assets/css/bootstrap-reboot.min.css">
			<link rel="stylesheet" href="src/assets/css/bootstrap-grid.min.css">
			<link rel="stylesheet" href="src/assets/css/bootstrap.min.css">
		</head>
		<body class="bg-secondary">';
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
	abstract protected function body();

	public function setContentInBody($content){
		$this->bodyContent .= $content;
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