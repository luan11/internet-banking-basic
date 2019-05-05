<?php
date_default_timezone_set('America/Sao_Paulo');

require_once('src/app/controllers/ViewsController.php');
use \src\app\controllers\ViewsController;

/**
 * Inicia a aplicação
 */
$page = isset($_GET['page']) ? $_GET['page'] : '';
new ViewsController($page);