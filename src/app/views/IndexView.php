<?php
namespace src\app\views;

require_once('View.php');

class IndexView extends View {

	public function __construct($pageTitle){
		$this->pageTitle = $pageTitle;
		$this->viewContent = "<h1>". $pageTitle ."</h1>";
	}

}