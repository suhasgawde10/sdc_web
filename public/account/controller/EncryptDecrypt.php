<?php

class EncryptDecrypt
{
    function encrypt($string, $key = 5)
    {
        $result = '';
        for ($i = 0, $k = strlen($string); $i < $k; $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) + ord($keychar));
            $result .= $char;
        }
        return base64_encode($result);
    }

    function decrypt($string, $key = 5)
    {
        $result = '';
        $string = base64_decode($string);
        for ($i = 0, $k = strlen($string); $i < $k; $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) - ord($keychar));
            $result .= $char;
        }
        return $result;
    }

    function encryptWebservice($string)
    {
        return base64_encode($string) . $this->random_strings(5);
    }

    function decryptWebservice($string)
    {
        $string = substr($string, 0, strlen($string) - 5);
        $string = base64_decode($string);
        return $string;
    }

    function random_strings($length_of_string)
    {
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($str_result), 0, $length_of_string);
    }
}


?>