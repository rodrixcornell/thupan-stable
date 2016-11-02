<?php

namespace Service;

class Crypt {
    // seta por padrão o uso de mcrypt
    public static $secure = 'high';

    // uma forma segura de usar base64
    public static function safe_b64encode($string) {
        $data = base64_encode($string);
        $data = str_replace(['+','/','='],['-','_',''],$data);
        return $data;
    }

    // uma forma segura de usar base64
    public static function safe_b64decode($string) {
        $data = str_replace(['-','_'],['+','/'],$string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

    // codifica uma string
    public static function encode($value){
        if(!$value) {
            return false;
        }

        // verifica o nivel se seguranca de criptografia
        self::$secure = defined(CRYPT_SECURE) ? CRYPT_SECURE : self::$secure;

        // se for alto utilizar mcrypt
        if(self::$secure === 'high') {
            $iv_size   = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
            $iv        = mcrypt_create_iv($iv_size, MCRYPT_RAND);
            $value     = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, KEY, $value, MCRYPT_MODE_ECB, $iv);

            return trim(self::safe_b64encode($value));
        } else if(self::$secure === 'low') {
            // se nao, retorna base64
            return base64_encode($value);
        } else {
            // se nao for nenhum dos dois entao falses
            return false;
        }
    }

    public static function decode($value){
        if(!$value) {
            return false;
        }

        // verifica o nivel se seguranca de criptografia
        self::$secure = defined(CRYPT_SECURE) ? CRYPT_SECURE : self::$secure;

        // se for alto utilizar mcrypt
        if(self::$secure === 'high') {
            $value       = self::safe_b64decode($value);
            $iv_size     = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
            $iv          = mcrypt_create_iv($iv_size, MCRYPT_RAND);
            $value       = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, KEY, $value, MCRYPT_MODE_ECB, $iv);

            return trim($value);
        } else if(self::$secure === 'low') {
            // se nao, retorna o decode de base64
            return base64_decode($value);
        } else {
            // se nao for nenhum dos dois entao falses
            return false;
        }
    }
}
