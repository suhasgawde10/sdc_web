<?php

//error_reporting(0);

/*define("HOSTNAME", "localhost", false);
define("USERNAME", "root", false);
define("PASSWORD", "", false);
define("DBNAME", "sharedigitalcard_vps", false);*/


function getURL()
{
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
        $url = "https://";
    else
        $url = "http://";
    // Append the host(domain name, ip) to the URL.
    $url .= $_SERVER['HTTP_HOST'];

    return $url;
}

$site_url = getURL();

//Online

define("HOSTNAME", "localhost", false);
define("USERNAME", "sharedigitalcard", false);
define("PASSWORD", "wg0qS9^6", false);
define("DBNAME", "sharedigitalcard", false);

//testing
/*define("HOSTNAME", "localhost", false);
define("USERNAME", "sharedigitalcard", false);
define("PASSWORD", "wg0qS9^6", false);
define("DBNAME", "sharedigitalcard_test", false);*/
//Online

/*define("HOSTNAME", "103.224.247.81", false);
define("USERNAME", "digitalcardkubic", false);
define("PASSWORD", "^nU8np17", false);
define("DBNAME", "digitalcardkubic", false);*/


/*Testing online database*/

/*
define("HOSTNAME", "103.224.247.81", false);
define("USERNAME", "testdigital", false);
define("PASSWORD", "&3Sfx8d7", false);
define("DBNAME", "testdigital", false);*/


/*End*/

/*define("MAIL_HOST", "webmail.sharedigitalcard.com");
define("MAIL_USERNAME", "marketing@sharedigitalcard.com");
define("MAIL_PASSWORD", "rZbb61~0");
define("MAIL_PORT", "25");


define("MAIL_FROM_NAME", "Share Digital Card");
define("MAIL_FROM_EMAIL", "marketing@sharedigitalcard.com");

$global_contact = "9768904980";
$global_email = "marketing@sharedigitalcard.com";*/

/*define("MAIL_HOST", "smtp.gmail.com");
define("MAIL_USERNAME", "sharedigitalcard@gmail.com");
define("MAIL_PASSWORD", "Share@2020");
define("MAIL_PORT", "587");*/
$local_online_status = "1"; // 0,1 -- o means local , 1 means onlineh
$share_url_status = "short";    // $share_url_status = "full"/"short";


//TEMPLATE ID
define("TEMPLATE_REGISTRATION", "1207168182823086766");
define("TEMPLATE_FORGOT_PASSWORD", "1207161640255369399");
define("TEMPLATE_REQUEST_CALL", "1207161640313628056");
define("TEMPLATE_DEALER_FORGOT_PASS", "1207161640268384479");
define("TEMPLATE_LEAD", "1207168033340081785");

if ($local_online_status == 1 && $share_url_status == "short") {
    if (strpos($_SERVER['HTTP_HOST'], 'sharedigitalcard.com') !== false) {
        define('FULL_DESKTOP_URL', 'https://sharedigitalcard.com/d/');
        define('FULL_MOBILE_URL', 'https://sharedigitalcard.com/m/');
        define('FULL_WEBSITE_URL', 'https://sharedigitalcard.com/');
    } else {
        define('FULL_DESKTOP_URL', $site_url . '/d/');
        define('FULL_MOBILE_URL', $site_url . '/m/');
        define('FULL_WEBSITE_URL', $site_url . '/');
    }


} elseif ($local_online_status == 0 && $share_url_status == "short") {
    define('FULL_DESKTOP_URL', 'http://localhost/sharedigitalcard_online/d/');
    define('FULL_MOBILE_URL', 'http://localhost/sharedigitalcard_online/m/');
    define('FULL_WEBSITE_URL', 'http://localhost/sharedigitalcard_online/');
} else {
    define('FULL_DESKTOP_URL', '');
    define('FULL_MOBILE_URL', '');
    define('FULL_WEBSITE_URL', '');
}
// SMS Config

if ($local_online_status == 1) {
    $root_file_url = $_SERVER['DOCUMENT_ROOT'] . "/";
} else {
    $root_file_url = $_SERVER['DOCUMENT_ROOT'] . '/sharedigitalcard_online/';
}


$file_handle = fopen($root_file_url . 'controller/config/sms_config.txt', 'r');

$get_sms_config = fread($file_handle, filesize($root_file_url . 'controller/config/sms_config.txt'));
//fclose($get_sms_config);
$get_sms_config = explode(',', $get_sms_config);

//Email Config
$file_handle = fopen($root_file_url . 'controller/config/email_config.txt', 'r');
$get_mail_config = fread($file_handle, filesize($root_file_url . 'controller/config/email_config.txt'));
//fclose($get_mail_config);
$get_mail_config = explode(',', $get_mail_config);

define("MAIL_HOST", $get_mail_config[0]);
define("MAIL_USERNAME", $get_mail_config[1]);
define("MAIL_PASSWORD", $get_mail_config[2]);
define("MAIL_PORT", $get_mail_config[3]);


/*define("MAIL_HOST", "in-v3.mailjet.com");
define("MAIL_USERNAME", "c224260340a9c9269401313a48a5df12");
define("MAIL_PASSWORD", "f1cbd91f9b0a0f94fab49758b9250352");
define("MAIL_PORT", "587");*/


/*define("MAIL_HOST", "smtp.sendgrid.net");
define("MAIL_USERNAME", "apikey");
define("MAIL_PASSWORD", "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");
define("MAIL_PORT", "587");
*/

define("MAIL_FROM_NAME", "Digital Card");
define("MAIL_FROM_EMAIL", "no-reply@sharedigitalcard.com");
define("DEALER_DISCOUNT", "15");
define("TRIAl_YEAR", "Free Trail (15 days)','Free Trail (5 days)");
define("FILE_NOTE", "<span style='color: red'>Note: Allow only images (Max 2 MB)</span>");
$global_contact = "9768904980";
$global_contact2 = "9773884631";

define("ADMIN_NAME", "Digital Card");
define("ADMIN_EMAIL", "admin@sharedigitalcard.com");

// dealer deposit amount
define("DEPOSIT_AMT", "2000.00");
/*$global_contact = "8070139237";*/
$global_email = "sharedigitalcard@gmail.com";
function generatePIN($digits = 6)
{
    $i = 0; //counter
    $pin = ""; //our default pin is blank.
    while ($i < $digits) {
        //generate a random number between 0 and 9.
        $pin .= mt_rand(0, 9);
        $i++;
    }
    return $pin;
}

$random_sms = generatePIN();
// $random_sms = 123456;


function compressImage($source, $destination, $quality)
{

    $info = getimagesize($source);

    if ($info['mime'] == 'image/jpeg')
        $image = imagecreatefromjpeg($source);

    elseif ($info['mime'] == 'image/gif')
        $image = imagecreatefromgif($source);

    elseif ($info['mime'] == 'image/png')
        $image = imagecreatefrompng($source);

    if (imagejpeg($image, $destination, $quality)) {
        return true;
    } else {
        return false;
    }

}

/*$global_email = "fahim@kubictechnology.com";*/

$back_image = '"background-image: url(https://image.freepik.com/free-vector/colorful-abstract-geometric-backdrop_1023-44.jpg);width: 100%;height: auto;background-repeat: no-repeat;background-size: cover;position: relative;padding: 0;"';
$overlay = 'position: absolute;
            top: 0;
            bottom: 0;
            right: 0;
            left: 0;
            background-color: rgba(86, 86, 86, 0.4);';
$btn = 'padding: 6px 0 1px 0px;
            border-radius: 3px;
            margin: 0;
            color: #fbdedb;
            background-color: #fbdedb;
            display: inline-block;
            background: #e74c3c;
            -webkit-transition: 0.3s;
            -moz-transition: 0.3s;
            -o-transition: 0.3s;
            transition: 0.3s;
            font-family: sans-serif;
            font-weight: 700;
            font-size: .85em;
            text-transform: uppercase;
            text-align: center;
            text-decoration: none;
            -webkit-box-shadow: 0em -0.3rem 0em rgba(0, 0, 0, 0.1) inset;
            -moz-box-shadow: 0em -0.3rem 0em rgba(0, 0, 0, 0.1) inset;
            box-shadow: 0em -0.3rem 0em rgba(0, 0, 0, 0.1) inset;
            position: relative;
            color: white;';


// save in constant file
define("API_KEY", "AAAAHMLbFk8:APA91bEwlgWphUe6g-NNerDYloGq5mAf0_dB71QzuDqGl9pazSjkzN9gMReIcR0y4Y9CnF_-JDfYQWaAYpHF8DKEsC8R-yITdfM5eVVcEteHf7g4yA98QjVTrf5g4chOGtK6oRwUS30I");

/*razor pay start*/

//test mode


// TEST
// $keyId = 'rzp_test_ReKEe1XaEqKYow';
// $keySecret = 'YyjvDCT9sUyVzpE34fuGedpr';


$file_handle = fopen($root_file_url . 'controller/config/razor_api.txt', 'r');
$get_razor = fread($file_handle, filesize($root_file_url . 'controller/config/razor_api.txt'));
//fclose($get_razor);
$get_razor = explode(',', $get_razor);


// ONLINE
$keyId = $get_razor[0];
$keySecret = $get_razor[1];
$displayCurrency = 'INR';

//These should be commented out in production
// This is for error reporting
// Add it to config.php to report any errors
/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/


define("RAZOR_LIVE_API", $keyId);
define("RAZOR_LIVE_SECRET", $keySecret);
define("CURRENCY_API_KEY", "cc4da57717d1ff26bfac");

define("FROM_BILL", "Share Digital Card");
define("FROM_GSTNO", "27AEEFS3851B1Z7");
define("FROM_PAN", "AEEFS3851B");
define("SAC_CODE", "9983");

/*razor pay end*/
$file_handle = fopen($root_file_url . 'controller/config/reg_email_contact.txt', 'r');
$get_reg_email_contact = fread($file_handle, filesize($root_file_url . 'controller/config/reg_email_contact.txt'));
//fclose($get_reg_email_contact);


$show_div_in_reg = $get_reg_email_contact;//status = email/contact
/*authkey=8b7d2649d2239e549d4d0bbb66ef6ff5&mobiles=9768904980,9773884631&message=hello%0D%0Ahow+are+you&sender=DGCARD&route=4*/
/*sms api key start*/

define('SMS_URL', $get_sms_config[0]);
define('AUTH_KEY', $get_sms_config[1]);
define('SMS_USERNAME', $get_sms_config[2]);
define('SMS_APIKEY', $get_sms_config[3]);
define('SMS_SENDER', $get_sms_config[4]);

/*http://www.alots.in/sms-panel/api/http/index.php?username=DGCARD&apikey=3F7A1-5B10E&apirequest=Text&sender=DGCARD&mobile=9773884631&message=CardMessage&route=TRANS&format=JSON*/
/*sms api key end*/


//SMS On Service & Reviews
$file_handle = fopen($root_file_url . 'controller/config/email_services.txt', 'r');
$get_serv_reviews = fread($file_handle, filesize($root_file_url . 'controller/config/email_services.txt'));
//fclose($get_serv_reviews);
$get_serv_reviews = explode(',', $get_serv_reviews);


if ($share_url_status == "full") {
    if (strpos($_SERVER['HTTP_HOST'], 'sharedigitalcard.com') !== false) {
        define('SHARED_URL', 'https://www.sharedigitalcard.com/m/index.php?custom_url=');
    } else {
        define('SHARED_URL', $site_url . '/m/index.php?custom_url=');
    }
} else {
    if (strpos($_SERVER['HTTP_HOST'], 'sharedigitalcard.com') !== false) {
        define('SHARED_URL', 'https://sharedigitalcard.com/');
    } else {
        define('SHARED_URL', $site_url . '/d/home/');
    }

    /*define('SHARED_URL','http://sharedigitalcard.com/card/');*/
}


function check_url_exits($url)
{
    /*$new_url = str_replace('https','http',$url);*/
    /*$headers = get_headers($url);
    return stripos($headers[0], "200 OK") ? true : false;*/
    return true;
}

function get_full_param()
{
    $output = "?";
    $firstRun = true;

    foreach ($_GET as $key => $val) {
        if (!$firstRun) {
            $output .= "&";
        } else {
            $firstRun = false;
        }
        $output .= $key . "=" . $val;
    }
    return $output;
}

function get_all_get()
{
    global $share_url_status;
    global $local_online_status;
    $output = "?";
    $firstRun = true;

    foreach ($_GET as $key => $val) {
        if (!$firstRun) {
            $output .= "&";
        } else {
            $firstRun = false;
        }
        $output .= $key . "=" . $val;
    }
    if ($local_online_status == 1 && $share_url_status == "short") {
        $output = str_replace('?custom_url=', '', $output);
    } elseif ($local_online_status == 0 && $share_url_status == "short") {
        $output = str_replace('?custom_url=', '', $output);
    }
    return $output;
}


function get_url_param($page_name = null)
{
    global $local_online_status;
    global $share_url_status;
    $url = "";
    $new_page_name = str_replace('.php', '', $page_name);
    /* if ($page_name == "index.php") {
         if($local_online_status == 1  && $share_url_status == "short"){
             $url = FULL_DESKTOP_URL . get_all_get();
         }elseif($local_online_status == 0  && $share_url_status == "short"){
             $url = FULL_DESKTOP_URL . get_all_get();
         }else{
             $url = $page_name . get_all_get();
         }
     } else {*/
    if ($local_online_status == 1 && $share_url_status == "short") {
        $url = FULL_DESKTOP_URL . $new_page_name . "/" . get_all_get();
    } elseif ($local_online_status == 0 && $share_url_status == "short") {
        $url = FULL_DESKTOP_URL . $new_page_name . "/" . get_all_get();
    } else {
        $url = $page_name . get_all_get();
    }
    /* }*/
    return $url;

}


function get_url_param_for_mobile($page_name = null)
{
    $url = "";
    $new_page_name = str_replace('.php', '', $page_name);
    global $local_online_status;
    global $share_url_status;
    if ($page_name == "index.php") {
        if ($local_online_status == 1 && $share_url_status == "short") {
            $url = FULL_WEBSITE_URL . get_all_get();
        } elseif ($local_online_status == 0 && $share_url_status == "short") {
            $url = FULL_WEBSITE_URL . get_all_get();
        } else {
            $url = $page_name . get_all_get();
        }
    } else {
        if ($local_online_status == 1 && $share_url_status == "short") {
            $url = FULL_WEBSITE_URL . $new_page_name . "/" . get_all_get();
        } elseif ($local_online_status == 0 && $share_url_status == "short") {
            $url = FULL_WEBSITE_URL . $new_page_name . "/" . get_all_get();
        } else {
            $url = $page_name . get_all_get();
        }
    }
    return $url;

}

function get_url_param_for_mobile_full_url($page_name = null)
{
    $url = "";
    $new_page_name = str_replace('.php', '', $page_name);
    global $local_online_status;
    global $share_url_status;
    if ($page_name == "index.php") {
        if ($local_online_status == 1 && $share_url_status == "short") {
            $url = FULL_WEBSITE_URL . get_all_get_full_url();
        } elseif ($local_online_status == 0 && $share_url_status == "short") {
            $url = FULL_WEBSITE_URL . get_all_get_full_url();
        } else {
            $url = $page_name . get_all_get_full_url();
        }
    } else {
        if ($local_online_status == 1 && $share_url_status == "short") {
            $url = FULL_WEBSITE_URL . $new_page_name . get_all_get_full_url();
        } elseif ($local_online_status == 0 && $share_url_status == "short") {
            $url = FULL_WEBSITE_URL . $new_page_name . get_all_get_full_url();
        } else {
            $url = $page_name . get_all_get_full_url();
        }
    }
    return $url;

}

function get_all_get_full_url()
{
    global $share_url_status;
    global $local_online_status;
    $output = "?";
    $firstRun = true;

    foreach ($_GET as $key => $val) {
        if (!$firstRun) {
            $output .= "&";
        } else {
            $firstRun = false;
        }
        $output .= $key . "=" . $val;
    }
    /*if ($local_online_status == 1 && $share_url_status == "short") {
        $output = str_replace('?custom_url=', '', $output);
    } elseif ($local_online_status == 0 && $share_url_status == "short") {
        $output = str_replace('?custom_url=', '', $output);
    }*/
    return $output;
}

function get_url_param_full_url($page_name = null)
{
    global $local_online_status;
    global $share_url_status;
    $url = "";
    $new_page_name = str_replace('.php', '', $page_name);
    /* if ($page_name == "index.php") {
         if($local_online_status == 1  && $share_url_status == "short"){
             $url = FULL_DESKTOP_URL . get_all_get();
         }elseif($local_online_status == 0  && $share_url_status == "short"){
             $url = FULL_DESKTOP_URL . get_all_get();
         }else{
             $url = $page_name . get_all_get();
         }
     } else {*/
    if ($local_online_status == 1 && $share_url_status == "short") {
        $url = FULL_DESKTOP_URL . $new_page_name . "/" . get_all_get_full_url();
    } elseif ($local_online_status == 0 && $share_url_status == "short") {
        $url = FULL_DESKTOP_URL . $new_page_name . "/" . get_all_get_full_url();
    } else {
        $url = $page_name . get_all_get_full_url();
    }
    /* }*/
    return $url;

}

?>