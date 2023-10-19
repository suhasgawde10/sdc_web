<?php

class Validator
{
    function nullChecker($string)
    {
        $value = trim($string);
        if (isset($value) && $value != "") {
            return true;
        } else {
            return false;
        }
    }

    function lengthChecker($string, $min, $max)
    {
        if (strlen($string) >= $min && strlen($string) <= $max) {
            return true;
        } else {
            return false;
        }
    }

    function validEmailChecker($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }

    function validDateChecker($string, $format)
    {
        $pattern = "";
        switch ($format) {
            case "yyyy-mm-dd":
                $pattern = "/^((((19|[2-9]\\d)\\d{2})\\-(0[13578]|1[02])\\-(0[1-9]|[12]\\d|3[01]))|(((19|[2-9]\\d)\\d{2})\\-(0[13456789]|1[012])\\-(0[1-9]|[12]\\d|30))|(((19|[2-9]\\d)\\d{2})\\-02\\-(0[1-9]|1\\d|2[0-8]))|(((1[6-9]|[2-9]\\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))\\-02\\-29))$/";
                break;
            case "yyyy/mm/dd":
                $pattern = "/^((((19|[2-9]\\d)\\d{2})\\/(0[13578]|1[02])\\/(0[1-9]|[12]\\d|3[01]))|(((19|[2-9]\\d)\\d{2})\\/(0[13456789]|1[012])\\/(0[1-9]|[12]\\d|30))|(((19|[2-9]\\d)\\d{2})\\/02\\/(0[1-9]|1\\d|2[0-8]))|(((1[6-9]|[2-9]\\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))\\/02\\/29))$/";
                break;
            case "mm-dd-yyyy":
                $pattern = "/^(((0[13578]|1[02])\\-(0[1-9]|[12]\\d|3[01])\\-((19|[2-9]\\d)\\d{2}))|((0[13456789]|1[012])\\-(0[1-9]|[12]\\d|30)\\-((19|[2-9]\\d)\\d{2}))|(02\\-(0[1-9]|1\\d|2[0-8])\\-((19|[2-9]\\d)\\d{2}))|(02\\-29\\-((1[6-9]|[2-9]\\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/";
                break;
            case "mm/dd/yyyy":
                $pattern = "/^(((0[13578]|1[02])\\/(0[1-9]|[12]\\d|3[01])\\/((19|[2-9]\\d)\\d{2}))|((0[13456789]|1[012])\\/(0[1-9]|[12]\\d|30)\\/((19|[2-9]\\d)\\d{2}))|(02\\/(0[1-9]|1\\d|2[0-8])\\/((19|[2-9]\\d)\\d{2}))|(02\\/29\\/((1[6-9]|[2-9]\\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/";
                break;
            case "dd/mm/yyyy":
                $pattern = "/^(((0[1-9]|[12]\\d|3[01])\\/(0[13578]|1[02])\\/((19|[2-9]\\d)\\d{2}))|((0[1-9]|[12]\\d|30)\\/(0[13456789]|1[012])\\/((19|[2-9]\\d)\\d{2}))|((0[1-9]|1\\d|2[0-8])\\/02\\/((19|[2-9]\\d)\\d{2}))|(29\\/02\\/((1[6-9]|[2-9]\\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/";
                break;
            case "dd-mm-yyyy":
                $pattern = "/^(((0[1-9]|[12]\\d|3[01])\\-(0[13578]|1[02])\\-((19|[2-9]\\d)\\d{2}))|((0[1-9]|[12]\\d|30)\\-(0[13456789]|1[012])\\-((19|[2-9]\\d)\\d{2}))|((0[1-9]|1\\d|2[0-8])\\-02\\-((19|[2-9]\\d)\\d{2}))|(29\\-02\\-((1[6-9]|[2-9]\\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/";
                break;
            default:
                return false;
                break;
        }

        if (preg_match($pattern, $string)) {
            return true;
        } else {
            return false;
        }
    }

    function validContactChecker($contact)
    {

        if ((preg_match("^(?:(?:\\+|0{0,2})91(\\s*[\\-]\\s*)?|[0]?)?[789]\\d{9}$^", $contact)) && (strlen($contact)) == 10) {
            return true;
        } else {
            return false;
        }
    }

    function onlyWordChecker($string)
    {
        if (preg_match("/^[a-zA-Z]+$/", $string)) {
            return true;
        } else {
            return false;
        }
    }

    function onlyNumberChecker($string)
    {
        if (is_numeric($string)) {
            return true;
        } else {
            return false;
        }
    }

    function validURLChecker($string)
    {
        if (preg_match("/[-a-z0-9+&@#\\/%?=~_|!:,.;]*[-a-z0-9+&@#\\/%=~_|]*[.-a-z]/", $string)) {
            return true;
        } else {
            return false;
        }
    }

    function compareValidator($string1, $string2)
    {
        if ($string1 == $string2) {
            return true;
        } else {
            return false;
        }
    }

    function regularExpressionValidator($string, $pattern)
    {
        if (preg_match($pattern, $string)) {
            return true;
        } else {
            return false;
        }
    }

    function standardPasswordChecker($password, $min, $max)
    {
        $expression = "/^(?=.*\\d)(?=.*[@#\\-_$%^&+=§!\\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\\-_$%^&+=§!\\?]{" . $min . "," . $max . "}$/";
        if (preg_match($expression, $password)) {
            return true;
        } else {
            return false;
        }
    }

    function checkFileSize($file_name, $max_size)
    {
        if (filesize($file_name) <= $max_size) {
            return true;
        } else {
            return false;
        }
    }

    public function validateFileExtension($file_name, $extensions)
    {
        $file_extension = strrchr($file_name, '.');

        if (in_array($file_extension, $extensions)) {
            return true;
        } else {
            return false;
        }
    }

    public function validTimeChecker24hrs($time)
    {
        $pattern = preg_match("/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/", $time);
        if ($pattern) {
            return true;
        }
        else {
            return false;
        }
    }
    public function validTimeChecker12hrs($time)
    {
        $pattern = preg_match("/^(?:1[012]|0[0-9]):[0-5][0-9]$/", $time);
        if ($pattern) {
            return true;
        }
        else {
            return false;
        }
    }
    public function validLongitude($longitude)
    {
        $pattern = preg_match("/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\\.(\\d+))|180(\\.0+)?)$/", $longitude);
        if ($pattern) {
            return true;
        }
        else {
            return false;
        }
    }
    public function validLatitude($latitude)
    {
        $pattern = preg_match("/^[-]?(([0-8]?[0-9])\\.(\\d+))|(90(\\.0+)?)$/", $latitude);
        if ($pattern) {
            return true;
        }
        else {
            return false;
        }
    }
}