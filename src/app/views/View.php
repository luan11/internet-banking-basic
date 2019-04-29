<?php
namespace src\app\views;

require_once('src/app/helpers/PrepareEnvironment.php');

abstract class View {

    protected $pageName;

    protected function header(){
        return '<!DOCTYPE html>
        <html lang="pt-br">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title>'. $this->pageName .'</title>
        </head>
        <body>';
    }

    protected function footer(){
        return '</body>
        </html>';
    }

    abstract protected function body();

    public function generateView(){
        return $this->header().$this->body().$this->footer();
    }

}