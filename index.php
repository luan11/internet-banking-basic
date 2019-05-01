<?php
require_once('src/app/controllers/ViewsController.php');
use \src\app\controllers\ViewsController;

$page = isset($_GET['page']) ? $_GET['page'] : '';
new ViewsController($page);