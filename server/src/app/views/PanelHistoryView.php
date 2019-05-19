<?php
namespace src\app\views;

require_once('View.php');

class PanelHistoryView extends View {

    public function __construct($pageTitle){
        $this->pageTitle = $pageTitle;
        
        $historyRows = '';
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }
        if(isset($_SESSION['userLogged'])){            
            $history = \src\app\services\Database::getTransactsHistory($_SESSION['userLogged']);
            if(!empty($history)){
                for($i = 0; $i < count($history); $i++){
                    $historyRows .= '<tr><th scope="row">'.\src\app\helpers\Utils::formatDate($history[$i]->date_ibbTransacts).'</th><td>'.$history[$i]->action_ibbTransacts.'</td><td>'.\src\app\helpers\Utils::formatMoney($history[$i]->value_ibbTransacts).'</td><td>'.$history[$i]->ip_ibbTransacts.'</td></tr>';
                }
            }else{
                $this->setViewMessage('Nenhuma transação realizada e/ou ocorreu algum erro ao carregar.', 'info');
                $historyRows .= '<tr><th scope="row">-</th><td>-</td><td>-</td><td>-</td></tr>';
            }
        }

		$this->viewContent = '<main><div class="container"><div class="row my-5"><table class="table table-striped"><thead class="text-center"><tr><th scope="col">DATA</th><th scope="col">AÇÃO</th><th scope="col">VALOR</th><th scope="col">IP</th></tr></thead><tbody class="text-center">'.$historyRows.'</tbody></table></div></div></main>';
	}

}