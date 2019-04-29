<?php
require_once('src/app/views/IndexView.php');

use src\app\views\IndexView;

$render = new IndexView("Internet Banking | Acessar/Registrar");
echo $render->generateView();