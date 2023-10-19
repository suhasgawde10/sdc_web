<?php
error_reporting(0);
// online
/*define("HOSTNAME", "localhost", false);
define("USERNAME", "dealerwebsite", false);
define("PASSWORD", "Cbyp*714", false);
define("DBNAME", "dealerwebsite", false);*/

// local
/*define("HOSTNAME", "localhost", false);
define("USERNAME", "root", false);
define("PASSWORD", "", false);
define("DBNAME", "manage_dealer", false);*/

define("HOSTNAME", "localhost", false);
define("USERNAME", "kubicdev", false);
define("PASSWORD", "Kub!ctech@2o2o", false);
define("DBNAME", "dealer_website", false);


/*define("MAIL_HOST", "smtp.gmail.com");
define("MAIL_USERNAME", "kubic.testing2@gmail.com");
define("MAIL_PASSWORD", "Kubic@2021");
define("MAIL_PORT", "587");*/

define("MAIL_HOST", "smtp.gmail.com");
define("MAIL_USERNAME", "noreply.automailservice@gmail.com");
define("MAIL_PASSWORD", "cvrkzxuyloqqlglg");
define("MAIL_PORT", "587");

define("MAIL_FROM_NAME", "Share digital mange Dealer");
//define("MAIL_FROM_EMAIL", "info@sarazaracreations.com");
 define("MAIL_FROM_EMAIL", "atulkumar@kubictechnology.in");
define("ONLINE_STATUS", "1");

define("DOMAIN_NAME", "http://sarazaracreations.com/");

define("MAIL_FROM_EMAIL_CC", "abc@gmail.com");

if (ONLINE_STATUS == 1) {
    define("SHARED_URL", "https://sarazaracreations.com/");
} else {
    define("SHARED_URL", "");
}


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

define('SMS_URL', 'http://smspanel.kubictechnology.com/sms-panel/api/http/index.php');
define('SMS_USERNAME', 'anupthakur');
define('SMS_APIKEY', '0ACAD-AD710');
define('SMS_SENDER', 'IMFCCL');
define('SMS_CONTACT', '9004929161');
// define('SMS_CONTACT','8070139237');

#test
/*$keyId = 'rzp_test_OPRPMK6ED408ma';
$keySecret = 'kJYR2kA1Qmc8LuMQOmEjdrPE';*/

#livw
$keyId = 'rzp_live_fHtjuUhrDH9M82';
$keySecret = 'Y2xl2LD8RSz3seW6JLAXCtDi';


define("RAZOR_LIVE_API", $keyId);
define("RAZOR_LIVE_SECRET", $keySecret);
define("CURRENCY_API_KEY", "cc4da57717d1ff26bfac");

define("FROM_BILL", "Incredible Mallakhamb.");
define("FROM_GSTNO", "27ALGPT2668G1ZP");
define("FROM_PAN", "ALGPT2668G");
define("SAC_CODE", "9506");
define('FROM_ADDRESS', 'Mallakhamb Factory, Shop No 61, Shiv Darshan Shopping Centre, Borivali West, Mumbai, Maharashtra, India. Pin- 400091.');
define('FROM_COMPANY_EMAIL', 'info@malla.com');
define('FROM_COMPANY_CONTACT', '+91-8108008444');
// http://smspanel.kubictechnology.com/sms-panel/api/http/index.php?username=anupthakur&apikey=0ACAD-AD710&apirequest=Text&sender=IMFCCL&mobile=9768904980&message=SMSMessage&route=TRANS&format=JSON

?>