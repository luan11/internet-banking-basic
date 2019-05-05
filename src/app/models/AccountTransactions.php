<?php
namespace src\app\models;

require_once('User.php');

class AccountTransactions extends User{

    private $transactValue, $transactConfirmId, $transactConfirmPassword, $transactIp;

    public function __construct($formValidate, $transactValue, $transactConfirmPassword){
        if(\src\app\helpers\Forms::validateInputValidator($formValidate)){
            if(floatval(filter_var($transactValue, FILTER_SANITIZE_STRING)) < 0 || empty(filter_var($transactValue, FILTER_SANITIZE_STRING))){
                $this->setError('Valor inserido não permitido!');
            }else{
                $this->transactValue = floatval(filter_var($transactValue, FILTER_SANITIZE_STRING));
                $this->transactConfirmPassword = filter_var($transactConfirmPassword, FILTER_SANITIZE_STRING);
                if(session_status() === PHP_SESSION_NONE){
                    session_start();
                }
                $this->transactConfirmId = $_SESSION['userLogged'];
                $this->transactIp = $_SERVER['REMOTE_ADDR'];
            }
        }else{
            $this->setError('Falha na autenticação, tente novamente!');
        }
    }

    /**
     * Metódo que realiza transferências entre contas
     *
     * @param AccountTransactions $transact
     * @param string $accountReceiver
     * @return void
     */
    public function transfer(AccountTransactions $transact, $accountReceiver){
        $confirmSender = \src\app\services\Database::validateLoggedPassword($transact->transactConfirmId, $transact->transactConfirmPassword);
        if(!empty($confirmSender) && $confirmSender['account'] !== $accountReceiver){
            if($transact->transactValue <= floatval($confirmSender['balance'])){
                $senderNewBalance = floatval($confirmSender['balance']) - $transact->transactValue;
                
                $confirmReceiver = \src\app\services\Database::validateAccountReceiver($accountReceiver);
                if(!empty($confirmReceiver)){
                    $receiverNewBalance = floatval($confirmReceiver->balance_ibbUsers) + $transact->transactValue;

                    if(\src\app\services\Database::doTransact($transact->transactConfirmId, $senderNewBalance)){
                        if(\src\app\services\Database::saveTransactHistory('Envio p/conta ['.$accountReceiver.']', -$transact->transactValue, $transact->transactIp, $transact->transactConfirmId)){
                            if(\src\app\services\Database::doTransact($confirmReceiver->id_ibbUsers, $receiverNewBalance)){
                                if(\src\app\services\Database::saveTransactHistory('Recebimento da conta ['.$confirmSender['account'].']', +$transact->transactValue, $transact->transactIp, $confirmReceiver->id_ibbUsers)){
                                    $transact->setSuccess("Transferência realizada com sucesso!");
                                }else{
                                    $transact->setError("[2] Erro ao documentar transação, porém foi efetuada com sucesso.");
                                }
                            }else{
                                $transact->setError("[2] Erro ao iniciar a transação, tente novamente.");
                            }
                        }else{
                            $transact->setError("[1] Erro ao documentar transação, porém foi efetuada com sucesso.");
                        }
                    }else{
                        $transact->setError("[1] Erro ao iniciar a transação, tente novamente.");
                    }
                }else{
                    $transact->setError("Conta do recebedor não encontrada!");
                }
            }else{
                $transact->setError("Saldo não suficiente!");
            }
        }else{
            $transact->setError("Falha na autenticação!");
        }
    }

    /**
     * Metódo que realiza retirardas
     *
     * @param AccountTransactions $transact
     * @return void
     */
    public function withdraw(AccountTransactions $transact){
        $confirmRequester = \src\app\services\Database::validateLoggedPassword($transact->transactConfirmId, $transact->transactConfirmPassword);
        if(!empty($confirmRequester)){
            if($transact->transactValue <= floatval($confirmRequester['balance'])){
                $senderNewBalance = floatval($confirmRequester['balance']) - $transact->transactValue;

                if(\src\app\services\Database::doTransact($transact->transactConfirmId, $senderNewBalance)){
                    if(\src\app\services\Database::saveTransactHistory('Retirada', -$transact->transactValue, $transact->transactIp, $transact->transactConfirmId)){
                        $transact->setSuccess("Retirada efetuada com sucesso!");
                    }else{
                        $transact->setError("[1] Erro ao documentar transação, porém foi efetuada com sucesso.");
                    }
                }else{
                    $transact->setError("[1] Erro ao iniciar a transação, tente novamente.");
                }
            }else{
                $transact->setError("Saldo não suficiente!");
            }            
        }else{
            $transact->setError("Falha na autenticação!");
        }
    }

    /**
     * Método que realiza depósitos
     *
     * @param AccountTransactions $transact
     * @return void
     */
    public function deposit(AccountTransactions $transact){
        $confirmRequester = \src\app\services\Database::validateLoggedPassword($transact->transactConfirmId, $transact->transactConfirmPassword);
        if(!empty($confirmRequester)){
            $senderNewBalance = floatval($confirmRequester['balance']) + $transact->transactValue;

            if(\src\app\services\Database::doTransact($transact->transactConfirmId, $senderNewBalance)){
                if(\src\app\services\Database::saveTransactHistory('Depósito', +$transact->transactValue, $transact->transactIp, $transact->transactConfirmId)){
                    $transact->setSuccess("Depósito efetuado com sucesso!");
                }else{
                    $transact->setError("[1] Erro ao documentar transação, porém foi efetuada com sucesso.");
                }
            }else{
                $transact->setError("[1] Erro ao iniciar a transação, tente novamente.");
            }            
        }else{
            $transact->setError("Falha na autenticação!");
        }
    }

}