<?php
namespace src\app\views;

require_once('View.php');

class IndexView extends View {

    public function __construct($pageName){
        $this->pageName = $pageName;
    }

    protected function body(){
        return  '<h1>Olá mundo!!!</h1>';
    }

}