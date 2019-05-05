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

    public function transfer(AccountTransactions $transact, $accountReceiver){
        $confirmSender = \src\app\services\Database::validateLoggedPassword($transact->transactConfirmId, $transact->transactConfirmPassword);
        if(!empty($confirmSender) && $confirmSender['account'] !== $accountReceiver){
            if($transact->transactValue <= floatval($confirmSender['balance'])){
                $senderNewBalance = floatval($confirmSender['balance']) - floatval($transact->transactValue);
                
                $confirmReceiver = \src\app\services\Database::validateAccountReceiver($accountReceiver);
                if(!empty($confirmReceiver)){
                    $receiverNewBalance = floatval($confirmReceiver->balance_ibbUsers) + $transact->transactValue;

                    if(\src\app\services\Database::doTransact($transact->transactConfirmId, $senderNewBalance)){
                        if(\src\app\services\Database::saveTransactHistory('Envio p/conta ['.$accountReceiver.']', '-'.$transact->transactValue, $transact->transactIp, $transact->transactConfirmId)){
                            if(\src\app\services\Database::doTransact($confirmReceiver->id_ibbUsers, $receiverNewBalance)){
                                if(\src\app\services\Database::saveTransactHistory('Recebimento da conta ['.$confirmSender['account'].']', '+'.$transact->transactValue, $transact->transactIp, $confirmReceiver->id_ibbUsers)){
                                    $transact->setSuccess("Transferência realizada com sucesso!");
                                }else{
                                    $transact->setError("Transação incompleta, entre em contato com o suporte!");
                                }
                            }else{
                                $transact->setError("Transação incompleta, entre em contato com o suporte!");
                            }
                        }else{
                            $transact->setError("Transação incompleta, entre em contato com o suporte!");
                        }
                    }else{
                        $transact->setError("Transação incompleta, entre em contato com o suporte!");
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

}