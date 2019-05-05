<?php
namespace src\app\helpers;

abstract class Utils{

    public static function formatMoney($value){
        return 'R$ '.number_format($value, 2, ',', '.');
    }

    public static function formatDate($date){
        $formatedDate = new \DateTime($date);
        return $formatedDate->format('d/m/Y - H:i:s');
    }

}