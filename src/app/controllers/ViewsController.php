<?php
namespace src\app\controllers;

require_once('src/app/helpers/PrepareEnvironment.php');
require_once("src/app/views/IndexView.php");

class ViewsController {

    public function __construct($page, $form = ""){
        switch($page){
            case "":
                \src\app\helpers\PrepareEnvironment::startEnvironment();
                $render = new \src\app\views\IndexView("Home");
                $render->setViewMessage("Algo deu errado!");
                $render->setViewMessage("Informação!", "info");
                $render->setViewMessage("Warn!", "warning");
                $render->setViewMessage("Sucesso!", "success");
                echo $render->generateView();
            break;
        }
    }

}