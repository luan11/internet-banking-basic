<?php
namespace src\app\helpers;

abstract class Utils{

    /**
     * Recebe um float e transforma para uma string no formato de dinheiro brasileiro 'R$ 0.000,00'
     *
     * @param float $value
     * @return string
     */
    public static function formatMoney($value){
        return 'R$ '.number_format($value, 2, ',', '.');
    }

    /**
     * Recebe uma data em formato timestamp e converte para uma string no formato 'DD-MM-YYYY - H:m:s'
     *
     * @param date $date
     * @return string
     */
    public static function formatDate($date){
        $formatedDate = new \DateTime($date);
        return $formatedDate->format('d/m/Y - H:i:s');
    }

}