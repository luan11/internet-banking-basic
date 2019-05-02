<?php
namespace src\app\helpers;

class Forms {

	private const charConjunct = array('a', 'A', 'b', 'B', 'c', 'C', 'd', 'D', 'e', 'E', 'f', 'F', 'g', 'G', 'h', 'H', 'i', 'I', 'j', 'J', 'k', 'K', 'l', 'L', 'm', 'M', 'n', 'N', 'o', 'O', 'p', 'P', 'q', 'Q', 'r', 'R', 's', 'S', 't', 'T', 'u', 'U', 'v', 'V', 'w', 'W', 'x', 'X', 'y', 'Y', 'z', 'Z', 0, 1, 2, 3, 4, 5, 6, 7, 8, 9);

	/**
	 * Gera um input:hidden com valor de um identificador único 
	 *
	 * @param string $verifierName
	 * @return string
	 */
	public static function generateInputValidator($verifierName){
		return '<input type="hidden" name="'.$verifierName.'" value="'.md5(date('Ymd')).'">';
	}

	/**
	 * Gera um número de conta aleatório a partir de um conjunto de caracteres
	 *
	 * @return string
	 */
	public static function generateAccountNumber(){
		$accountNumber = '';
		
		for($i = 0; $i < 10; $i++){
			$accountNumber .= self::charConjunct[rand(0, (count(self::charConjunct)-1))];
		}

		return $accountNumber;
	}

	/**
	 * Verifica o input:hidden gerado à partir do método generateInputValidator
	 *
	 * @param string $inputValidator
	 * @return bool
	 */
	public static function validateInputValidator($inputValidator){
		if(filter_var($inputValidator, FILTER_SANITIZE_STRING) === md5(date('Ymd'))){
			return true;
		}else{
			return false;
		}
	}

}