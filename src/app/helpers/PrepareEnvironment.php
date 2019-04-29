<?php
namespace src\app\helpers;

require_once('src/app/config.php');

class PrepareEnvironment {

    static public function startEnvironment(){
        self::generateHtaccessFile();
    }

    private function generateHtaccessFile(){
        $fileName = '.htaccess';
        $fileContent = "#SYS START \n<IfModule mod_rewrite.c> \nRewriteEngine On \nRewriteBase /".SYS_DEFAULT_PATH."/ \nRewriteRule ^index\.php$ - [L] \nRewriteCond %{REQUEST_FILENAME} !-f \nRewriteCond %{REQUEST_FILENAME} !-d \nRewriteRule . /".SYS_DEFAULT_PATH."/index.php [L] \n</IfModule> \n#SYS END";
        $fileSize = !empty(filesize($fileName)) ? filesize($fileName) : 1;
        
        $fileRead = fopen($fileName, 'r');
        $readedContent = fread($fileRead, $fileSize);
        fclose($fileRead);

        if(empty($readedContent) || strpos($readedContent, '/'.SYS_DEFAULT_PATH.'/') === false){
            $fileWrite = fopen($fileName, 'w');
            fwrite($fileWrite, $fileContent);
            fclose($fileWrite);
        }
    }

}