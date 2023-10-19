<?php
include 'whitelist.php';
include "controller/ManageUser.php";
$manage = new ManageUser();
include "controller/validator.php";
$validate = new Validator();
include 'sendMail/sendMail.php';
include "controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
$controller = new Controller();
$con = $controller->connect();
$error = false;
$errorMessage = "";
$error1 = false;
$errorMessage1 = "";
if(isset($_GET['email_register']) && $_GET['email_register'] == 'true'){
    $_SESSION['email_login'] = "true";
}
if(isset($_GET['contact_register']) && $_GET['contact_register'] == 'true'){
    unset($_SESSION['email_login']);
}

if(!isset($_GET['type'])){
    if (isset($_SESSION['email'])) {
        header('location:user/basic-user-info.php');
    }
}
$site_key = "6LeSbAEVAAAAAD7x5O1HkY9NtBkEThRTBK1lfHDI";

/*$getUserId = 1;
$insertMenuBar = $manage->addMenuBar($getUserId);
die();*/
/*@session_start();
session_destroy();*/

if (isset($_GET['dealer_code']) && $_GET['dealer_code'] != "") {
    $validateCode = $manage->validateDealerReferralCode($_GET['dealer_code']);
    if ($validateCode) {
        $_SESSION['refrence_by'] = $_GET['dealer_code'];
        if (isset($_GET['team_id']) && $_GET['team_id'] != "") {
            $_SESSION['team_id'] = $security->decryptWebservice($_GET['team_id']);
        }
    }
} elseif (isset($_GET['referral_by']) && $_GET['referral_by'] != "") {
    $validateCode = $manage->validateUserReferralCode($_GET['referral_by']);
    if ($validateCode) {
        $_SESSION['refrence_by'] = $_GET['referral_by'];
    }
} elseif (isset($_GET['coupon_code']) && $_GET['coupon_code'] != "") {
    $_SESSION['refrence_by'] = $_GET['coupon_code'];
}


//Our custom function.


function GenerateAPIKey()
{
    $key = implode('-', str_split(substr(strtolower(md5(microtime() . rand(1000, 9999))), 0, 30), 6));
    return $key;
}

$api_key = GenerateAPIKey();
//If I want a 4-digit PIN code.

function getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

$xml = simplexml_load_file("http://www.geoplugin.net/xml.gp?ip=" . getRealIpAddr());

$countryName = (string)$xml->geoplugin_countryName;

if($countryName==""){
    $countryName = "India";
}

if (isset($countryName) && $countryName != '' && $countryName != "India") {
    $_SESSION['email_login'] = "true";
}


if (!isset($_SESSION['recaptcha'])) {

    $_SESSION['recaptcha'] = false;
}

if (isset($_POST['send_otp'])) {
    if (isset($_POST['sms_email']) && $_POST['sms_email'] != "") {
        if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
            // Google reCAPTCHA API secret key

            $secretKey = "6LeSbAEVAAAAAH0X1C5mpHlSZVDhH4tsJ9atoGx1";


            // Verify the reCAPTCHA response
            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secretKey . '&response=' . $_POST['g-recaptcha-response']);

            // Decode json data
            $responseData = json_decode($verifyResponse);

            // If reCAPTCHA response is valid
            if ($responseData->success) {
                $_SESSION['recaptcha'] = true;
                $_SESSION['email_login'] = "true";
                if (isset($_POST['sms_email']) && $_POST['sms_email'] != "") {
                    if (!filter_var($_POST['sms_email'], FILTER_VALIDATE_EMAIL)) {
                        $error1 = true;
                        $errorMessage1 .= "Invalid email format.<br>";
                    }
                    $txt_email = mysqli_real_escape_string($con, $_POST['sms_email']);
                } else {
                    $error1 = true;
                    $errorMessage1 .= "Please enter your email.<br>";
                }
                if (!$error1) {
                    $result = $manage->validateRegisterEmail($txt_email);
                    if ($result) {
                        $error1 = true;
                        $errorMessage1 .= "Email ID Already Exists!!";
                    } else {
                        $sms_message = '<table style="width: 100%">
<tr>
<td colspan="2" style=' . $back_image . '>
<div style="' . $overlay . '">
<div style=" margin: 0 auto">
 <div class="user-name-logo" style="padding-top: 10px;">
                        <div style="text-align: center;color: #c4a758;width: 80px;margin: 1px auto;background: white;border-radius: 50%;height: 80px;text-align: center;padding: 5px;">
                            <img src="https://sharedigitalcard.com/assets/img/logo/logo.png" style="padding-top: 15px;width:100%">
                        </div>
                    </div>
                    <div style="text-align: center;color: white;font-weight: 700;padding-bottom: 10px;">
                        <h1 style="font-size: 24px;margin: 0;">Share Digital Card</h1>
                    </div>
</div>
</div>
</td>
</tr>
<tr>
<td colspan="2">
 <div style=" font-size: 18px;">
                   <div class="about-content">
                       <p> Dear Customer,</p>
                    <p>Please check the below otp to verify your email id. Please do not share this otp with anyone for security reasons</p>

                </div>
                <div style="text-align: center;margin: 20px 0;">
                    <div class="otp-inner" style=" height: auto;
            background: #deddd9;
            text-align: center;
            padding: 10px 0;
            width: 30%;
            margin: 0 auto;
            border: 2px dotted #ccc;
            font-size: 18px;
            font-weight: 600;">
                        <label style="background: #deddd9;color: #666563;">Your OTP Is <br><span style="font-weight: bold;background: #deddd9;color: #666563;">' . substr_replace($random_sms, '-', 3, 0) . '</span></label>
                    </div>
                </div>
                </div>
</td>
</tr>
<tr>
<td colspan="2" style=" font-size: 18px;">
<p> For any query email us on <a href="mailto:support@sharedigitalcard.com" class="payment">support@sharedigitalcard.com</a></p>
</td>
</tr>
<tr><td colspan="2" style="padding: 10px;background: #e6e6e6;height: 115px;">
 <div style="width: 85%;margin: 0 auto;">
                <div style=" width: 100%;margin: 0 auto;">
                    <div style="text-align:center">
                        <a href="https://www.facebook.com/sharedigitalcard/"><img src="http://sharedigitalcard.com/user/assets/images/fb.png"></a>
<a href="https://www.instagram.com/sharedigitalcard/"><img src="http://sharedigitalcard.com/user/assets/images/insta.png"></a>
<a href="https://www.youtube.com/watch?v=6T9Ia_2rsig&list=PLg1QyEHQ9MYYBRDxWqLrWLCyvJlSheqTh"><img src="http://sharedigitalcard.com/user/assets/images/yt.png"></a>
                    </div>
                </div>
            </div>
</td></tr>
</table>';
                        if (!$error1) {
                            $subject = "OTP For Registration From - sharedigitalcard.com";
                            $send_sms = $manage->sendMail(MAIL_FROM_NAME, $txt_email, $subject, $sms_message);
                            $_SESSION['tmp_email'] = $txt_email;
                            $_SESSION['random_sms'] = $random_sms;
                            $error1 = false;
                            $errorMessage1 .= "OTP has been sent to your email id.<br>";
                        }
                    }
                }
            } else {
                $error1 = true;
                $errorMessage1 .= "Invalid recaptcha.<br>";
            }
        } else {
            $error1 = true;
            $errorMessage1 .= "Please select recaptcha.<br>";
        }
    } else {

        if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
            // Google reCAPTCHA API secret key

            $secretKey = "6LeSbAEVAAAAAH0X1C5mpHlSZVDhH4tsJ9atoGx1";


            // Verify the reCAPTCHA response
            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secretKey . '&response=' . $_POST['g-recaptcha-response']);

            // Decode json data
            $responseData = json_decode($verifyResponse);

            // If reCAPTCHA response is valid
            if ($responseData->success) {
                $_SESSION['recaptcha'] = true;

                $sms_contact = $_POST['sms_contact'];
                $result = $manage->validateContact($sms_contact);
                if ($result) {
                    $error1 = true;
                    $errorMessage1 .= "User with this contact number already registered!!<br>";
                }
                
                //$sms_message = "Dear Customer, ".substr_replace($random_sms, '-', 3, 0)." is your one-time password - OTP. Sharedigitalcard";
                $sms_message = "Dear%20Customer%2C%20%0AFor%20registration%20into%20the%20website%20or%20mobile%20application%2C%20Your%20One-Time%20Password%20%28OTP%29%20is%20".substr_replace($random_sms, '-', 3, 0).".%20Please%20do%20not%20share%20this%20OTP%20with%20anyone.%20Message%20ID%3A%20asdasd545454%0ABest%20Regards%20%0ADGCARD";
                //$sms_message = "Dear Customer,For registration into the website or mobile application, Your One-Time Password (OTP) is ".substr_replace($random_sms, '-', 3, 0).". Please do not share this OTP with anyone. Message ID: DGCARD Best Regards DGCARD";
                //$sms_message = "Hello Customer, ".substr_replace($random_sms, '-', 3, 0)." is your OTP. Share Digital Card";
                //$send_sms = $manage->sendSMS($_SESSION['admin_email'], $sms_message);

                if (!$error1) {
                //    $send_sms = $manage->sendSMS($sms_contact, $sms_message);
                    $send_sms = $manage->sendSMSWithTemplateId($sms_contact, $sms_message,TEMPLATE_REGISTRATION);
                    $_SESSION['contact'] = $sms_contact;
                    $_SESSION['random_sms'] = $random_sms;
                    $error1 = false;
                    $errorMessage1 .= "OTP has been sent to your entered mobile number.<br>";
                }
            } else {
                $error1 = true;
                $errorMessage1 .= "Invalid recaptcha.<br>";
            }
        } else {
            $error1 = true;
            $errorMessage1 .= "Please select recaptcha.<br>";
        }


    }

}

if (isset($_POST['resend_otp'])) {
    if (isset($_SESSION['email_login']) && $_SESSION['email_login'] == "true") {
        $subject = "OTP For Registration From - sharedigitalcard.com";
        $sms_message = '<table style="width: 100%">
<tr>
<td colspan="2" style=' . $back_image . '>
<div style="' . $overlay . '">
<div style=" margin: 0 auto">
 <div class="user-name-logo" style="padding-top: 10px;">
                        <div style="text-align: center;color: #c4a758;width: 80px;margin: 1px auto;background: white;border-radius: 50%;height: 80px;text-align: center;padding: 5px;">
                            <img src="https://sharedigitalcard.com/assets/img/logo/logo.png" style="padding-top: 15px;width:100%">
                        </div>
                    </div>
                    <div style="text-align: center;color: white;font-weight: 700;padding-bottom: 10px;">
                        <h1 style="font-size: 24px;margin: 0;">Share Digital Card</h1>
                    </div>
</div>
</div>
</td>
</tr>
<tr>
<td colspan="2">
 <div style=" font-size: 18px;">
                   <div class="about-content">
                       <p> Dear Customer,</p>
                    <p>Please check the below otp to verify your email id. Please do not share this otp with anyone for security reasons</p>

                </div>
                <div style="text-align: center;margin: 20px 0;">
                    <div class="otp-inner" style=" height: auto;
            background: #deddd9;
            text-align: center;
            padding: 10px 0;
            width: 30%;
            margin: 0 auto;
            border: 2px dotted #ccc;
            font-size: 18px;
            font-weight: 600;">
                        <label style="background: #deddd9;color: #666563;">Your OTP Is <br><span style="color: #666563;
            font-weight: bold;">' . substr_replace($random_sms, '-', 3, 0) . '</span></label>
                    </div>
                </div>
                </div>
</td>
</tr>
<tr>
<td colspan="2" style=" font-size: 18px;">
<p> For any query email us on <a href="mailto:support@sharedigitalcard.com" class="payment">support@sharedigitalcard.com</a></p>
</td>
</tr>
<tr><td colspan="2" style="padding: 10px;background: #e6e6e6;height: 115px;">
 <div style="width: 85%;margin: 0 auto;">
                <div style=" width: 100%;margin: 0 auto;">
                    <div style="text-align:center">
                        <a href="https://www.facebook.com/sharedigitalcard/"><img src="http://sharedigitalcard.com/user/assets/images/fb.png"></a>
<a href="https://www.instagram.com/sharedigitalcard/"><img src="http://sharedigitalcard.com/user/assets/images/insta.png"></a>
<a href="https://www.youtube.com/watch?v=6T9Ia_2rsig&list=PLg1QyEHQ9MYYBRDxWqLrWLCyvJlSheqTh"><img src="http://sharedigitalcard.com/user/assets/images/yt.png"></a>
                    </div>
                </div>

            </div>
</td></tr>
</table>';
        $send_sms = $manage->sendMail(MAIL_FROM_NAME, $_SESSION['tmp_email'], $subject, $sms_message);
        $_SESSION['random_sms'] = $random_sms;
        $error1 = false;
        $errorMessage1 .= "OTP has been re-sent to your email id.<br>";
    } else {
        $result = $manage->validateContact($_SESSION['contact']);
        if ($result) {
            $error1 = true;
            $errorMessage1 .= "User with this contact number already registered!!<br>";
        }
        //$sms_message = "Dear Customer,\n" . substr_replace($random_sms, '-', 3, 0) . " is your one time password (OTP). Please do not share this OTP with anyone for security reasons.";
        //$sms_message = "Dear Customer, ".substr_replace($random_sms, '-', 3, 0)." is your one-time password - OTP. Sharedigitalcard";
        $sms_message = "Dear%20Customer%2C%20%0AFor%20registration%20into%20the%20website%20or%20mobile%20application%2C%20Your%20One-Time%20Password%20%28OTP%29%20is%20".substr_replace($random_sms, '-', 3, 0).".%20Please%20do%20not%20share%20this%20OTP%20with%20anyone.%20Message%20ID%3A%20asdasd545454%0ABest%20Regards%20%0ADGCARD";
        if (!$error1) {
            //$send_sms = $manage->sendSMS($_SESSION['contact'], $sms_message);
            $send_sms = $manage->sendSMSWithTemplateId($_SESSION['contact'], $sms_message,TEMPLATE_REGISTRATION);
            $_SESSION['random_sms'] = $random_sms;
            $error1 = false;
            $errorMessage1 .= "OTP has been re-sent to your entered mobile number.<br>";
        }
    }
}

if (isset($_POST['verify_otp'])) {
    $explode_otp = implode('', $_POST['sms_otp']);
    $sms_otp = trim($explode_otp);
    if ($sms_otp == $_SESSION['random_sms']) {
        echo "<style>.register_otp{ display: block !important;}</style>";
        echo "<style>.sms_registration{ display: none !important;}</style>";
        $_SESSION['verified_status'] = true;
        /*unset($_SESSION["random_sms"]);*/
        /*  unset($_SESSION["contact"]);*/
    } else {
        $error1 = true;
        $errorMessage1 .= "OTP Mismatched<br>";
    }
}

if (isset($_SESSION['verified_status']) && $_SESSION['verified_status'] == true) {
    echo "<style>.register_otp{ display: block !important;}</style>";
    echo "<style>.sms_registration{ display: none !important;}</style>";
}


if (isset($_POST['btn_cancel'])) {
    session_destroy();
    header('location:register.php');
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Registration | Online business and visiting card maker in India, Maharashtra, Mumbai.</title>
    <meta name="description"
          content="Free 5 days trial,Free registeration,Digital business card free,Register yourself and complete your digital profile. And use digital business card to connect world with single click.">
    <meta name="keywords"
          content="digital business card, digital visiting card, online visiting card, online business card, visiting card design, maker in, india, maharashtra, mumbai, business card design, customized design, attractive visiting card, share digital card, business card application, visiting card application, app, sign up, register">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <script>
        document.onreadystatechange = function () {
            var btn = document.getElementById("myBtn");
            if (document.readyState !== "complete") {
                btn.setAttribute("disabled", "");
            } else {
                btn.removeAttribute("disabled", "");
            }
        };
    </script>
    <?php

    if (isset($_POST['btn_sharedigitalcard_registration'])) {

        $answer = $security->decryptWebservice($_SESSION["vercode"]);

    if(isset($_POST["captcha"])&&$_POST["captcha"]!="" && $answer==$_POST["captcha"]){

    }else{
        $error = true;
        $errorMessage .= "Please enter valid Sum.<br>";
    }
        if (isset($_POST['txt_name']) && $_POST['txt_name'] != "" && preg_match("/^[a-zA-Z0-9-. ]*$/", $_POST['txt_name'])) {
            $txt_name = mysqli_real_escape_string($con, $_POST['txt_name']);
        } else {
            $error = true;
            $errorMessage .= "Please enter name.<br>";
        }
        if (isset($_SESSION['tmp_email'])) {
            $verified_email_status = 1;
            $txt_email = trim($_SESSION['tmp_email']);
        } else {
            $verified_email_status = 0;
            if (isset($_POST['txt_email']) && $_POST['txt_email'] != "") {
                if (!filter_var($_POST['txt_email'], FILTER_VALIDATE_EMAIL)) {
                    $error = true;
                    $errorMessage .= "Invalid email format.<br>";
                }
                $txt_email = trim(mysqli_real_escape_string($con, $_POST['txt_email']));
            } else {
                $error = true;
                $errorMessage .= "Please enter your email.<br>";
            }

        }
        if (!isset($_SESSION['contact'])) {
            if (isset($_POST['txt_contact']) && $_POST['txt_contact'] != "" && is_numeric($_POST['txt_contact'])) {
                $txt_contact = $_POST['txt_contact'];
            } else {
                $error = true;
                $errorMessage .= "Please Enter Contact Number.<br>";
            }
        } else {
            $txt_contact = $_SESSION['contact'];
        }
        if (isset($_POST['country']) && $_POST['country'] != "") {
            $country = $_POST['country'];
        } else {
            $error = true;
            $errorMessage .= "Please Select country.<br>";
        }
        if (isset($_POST['gender']) && $_POST['gender'] != "" && ($_POST['gender'] == 'Male' OR $_POST['gender'] == 'Female')) {
            $gender = $_POST['gender'];
        } else {
            $error = true;
            $errorMessage .= "Please Select gender.<br>";
        }

        if (!isset($_POST['chk_terms'])) {
            $error = true;
            $errorMessage .= "Please Select our terms and condition.<br>";
        }
        if (!isset($_POST['online_search'])) {
            $online_search = 0;
        } else {
            $online_search = 1;
        }
        if (isset($_POST['txt_password']) && $_POST['txt_password'] != "") {
            if (($_POST['txt_confirm_pass']) !== ($_POST['txt_password'])) {
                $error = true;
                $errorMessage .= "password is not same as above.<br>";
            }
            $txt_password = mysqli_real_escape_string($con, $_POST['txt_password']);
            /*$new_password = $txt_password.$pin;*/
        } else {
            $error = true;
            $errorMessage .= "Please enter your password.<br>";
        }

        if(isset($_POST['txt_company_name']) && !preg_match("/^[a-zA-Z0-9-. ]*$/", $_POST['txt_company_name'])){
            $error = true;
            $errorMessage .= "Please enter valid company name.<br>";
        }

            if (!$error) {

                if (isset($_POST['txt_company_name']) && $_POST['txt_company_name'] != '') {
                    $txt_company_name = $_POST['txt_company_name'];
                    $txt_custom_url = str_replace([' ','.'], '-', trim($txt_company_name));
                    $result = $manage->validateCustomUrl(trim($txt_custom_url));
                    if ($result) {
                        $custom_url = $txt_custom_url . rand(1000, 100000);
                    } else {
                        $custom_url = $txt_custom_url;
                    }
                } else {
                    $txt_company_name = "";
                    $txt_custom_url = str_replace([' ','.'], '-', trim($txt_name));
                    $result = $manage->validateCustomUrl(trim($txt_custom_url));
                    if ($result) {
                        $custom_url = $txt_custom_url . rand(1000, 100000);
                    } else {
                        $custom_url = $txt_custom_url;
                    }
                }
                $custom_url = str_replace([",", "/", "'"], "", $custom_url);
                $custom_url = str_replace("&", "and", $custom_url);
                if (isset($_SESSION['contact'])) {
                    $verify_number = 1;
                    $result = $manage->validateRegisterEmail($_POST['txt_email']);
                    if ($result) {
                        $error = true;
                        $errorMessage .= "Email ID Already Exists!!";
                    }
                } else {
                    $verify_number = 0;
                    $result = $manage->validateContact($txt_contact);
                    if ($result) {
                        $error = true;
                        $errorMessage .= "Contact Number Already Exists!!";
                    }
                }

                if (!$error) {
                    if (!isset($_SESSION['refrence_by']) && !isset($_COOKIE['cookie_source'])) {
                        $_SESSION['refrence_by'] = "google";
                    }elseif(isset($_COOKIE['cookie_source'])){
                        $_SESSION['refrence_by'] = $_COOKIE['cookie_source'];
                    }
                    if (!isset($_SESSION['team_id'])) {
                        $_SESSION['team_id'] = "";
                    }



                    if(isset($_POST['robotest']) && $_POST['robotest'] !=''){
                    }else{

                        $getUserId = $manage->addUser($txt_name, $custom_url, $gender, $_SESSION['refrence_by'], $_SESSION['team_id'], $verify_number, $online_search, $country, "", "", $txt_company_name,$verified_email_status);

                        if ($getUserId != 0) {
                            $type = "User";
                            $_SESSION['user_code'] = "ref100" . $getUserId;
                            $updateDealer = $manage->updateUserCode($getUserId);
                            $insertUser = $manage->addUserLogin($getUserId, $type, $txt_email, $txt_contact, $security->encrypt($txt_password) . "8523", $api_key);/**/
                            if ($insertUser) {
                                $insertCustomUrl = $manage->addCustomUrl($getUserId, $custom_url);
                                $insertMenuBar = $manage->addMenuBar($getUserId);
                                $getSectionDetails = $manage->getSectionDetails();
                                if ($getSectionDetails != null) {
                                    while ($result_data = mysqli_fetch_array($getSectionDetails)) {
                                        $sectionId = $result_data["id"];
                                        if ($sectionId == 7) {
                                            $p_dg_status = 0;
                                        } else {
                                            $p_dg_status = 1;
                                        }
                                        $insertUserSectionEntry = $manage->insertDefaultUserSectionEntry($getUserId, $sectionId, $p_dg_status);
                                    }
                                }
                                if (!file_exists('user/uploads/')) {
                                    mkdir("user/uploads", 0777, true);
                                }

                                mkdir("user/uploads/" . trim($txt_email) . "/profile/", 0777, true);
                                mkdir("user/uploads/" . trim($txt_email) . "/image-slider/", 0777, true);
                                mkdir("user/uploads/" . trim($txt_email) . "/about-us/", 0777, true);
                                mkdir("user/uploads/" . trim($txt_email) . "/service/", 0777, true);
                                mkdir("user/uploads/" . trim($txt_email) . "/images/", 0777, true);
                                mkdir("user/uploads/" . trim($txt_email) . "/testimonials/clients", 0777, true);
                                mkdir("user/uploads/" . trim($txt_email) . "/testimonials/client_review", 0777, true);
                                mkdir("user/uploads/" . trim($txt_email) . "/our-team/", 0777, true);
                                mkdir("user/uploads/" . trim($txt_email) . "/logo/", 0777, true);

                                $_SESSION['email'] = trim($txt_email);
                                $_SESSION['name'] = $txt_name;
                                $_SESSION['id'] = $security->encrypt($getUserId);
                                $_SESSION['type'] = $type;
                                $_SESSION['custom_url'] = $custom_url;
                                $_SESSION['contact'] = $txt_contact;
                                $toName = $_SESSION['name'];
                                $toEmail = $_SESSION['email'];

                                $get_section = $manage->getSectionName();
                                if ($get_section != null) {
                                    $_SESSION['menu'] = array('s_profile' => $get_section['profile'],
                                        's_services' => $get_section['services'],
                                        's_our_service' => $get_section['our_service'],
                                        's_gallery' => $get_section['gallery'],
                                        's_images' => $get_section['images'],
                                        's_videos' => $get_section['videos'],
                                        's_clients' => $get_section['clients'],
                                        's_client_name' => $get_section['client_name'],
                                        's_client_review_tab' => $get_section['client_review'],
                                        's_team' => $get_section['team'],
                                        's_our_team' => $get_section['our_team'],
                                        's_bank' => $get_section['bank'],
                                        's_payment' => $get_section['payment'],
                                        's_basic_info' => $get_section['basic_info'],
                                        's_company_info' => $get_section['company_info']);
                                } else {
                                    $_SESSION['menu'] = array(
                                        's_profile' => "Profile",
                                        's_services' => "Services",
                                        's_our_service' => "Our Services",
                                        's_gallery' => "Gallery",
                                        's_images' => "Images",
                                        's_videos' => "Videos",
                                        's_clients' => "Clients",
                                        's_client_name' => "Clients",
                                        's_client_review_tab' => "Client's Reviews",
                                        's_team' => "Team",
                                        's_our_team' => "Our Team",
                                        's_bank' => "Bank",
                                        's_payment' => "Payment",
                                        's_basic_info' => "Basic Info",
                                        's_company_info' => "Company Info");
                                }

                                $_SESSION['total_percent'] = $manage->getUserProfilePercent();
                                $pending_dot = $manage->getPendingFormCount();
                                $_SESSION['red_dot'] = array();
                                if ($pending_dot['company_name'] == "") {
                                    $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('company_name' => true));
                                } else {
                                    $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('company_name' => false));
                                }
                                if ($pending_dot['service_name'] == "") {
                                    $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('service_name' => true));
                                } else {
                                    $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('service_name' => false));
                                }
                                if ($pending_dot['image_name'] == "") {
                                    $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('image_name' => true));
                                } else {
                                    $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('image_name' => false));
                                }
                                if ($pending_dot['video_link'] == "") {
                                    $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('video_link' => true));
                                } else {
                                    $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('video_link' => false));
                                }
                                if ($pending_dot['client_name'] == "") {
                                    $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('client_name' => true));
                                } else {
                                    $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('client_name' => false));
                                }
                                if ($pending_dot['client_review'] == "") {
                                    $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('client_review' => true));
                                } else {
                                    $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('client_review' => false));
                                }
                                if ($pending_dot['our_team'] == "") {
                                    $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('our_team' => true));
                                } else {
                                    $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('our_team' => false));
                                }
                                if ($pending_dot['bank_name'] == "") {
                                    $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('bank_name' => true));
                                } else {
                                    $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('bank_name' => false));
                                }
                                if ($pending_dot['upi_id'] == "") {
                                    $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('upi_id' => true));
                                } else {
                                    $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('upi_id' => false));
                                }
                                /*update 5 days*/
                                $date1 = date("Y-m-d");
                                $date = date_create("$date1");
                                date_add($date, date_interval_create_from_date_string("5 days"));
                                $final_date = date_format($date, "Y-m-d");
                                $year = "Free Trail (5 days)";
                                $amount = "0";
                                $status = "success";
                                $referal_by = "";
                                $refrenced_by = "";
                                $active_plan = 1;
                                $invoice_no = "";
                                $discount = 0;
                                $paymentMode = "";
                                $paymentBrand = "";
                                $custBankId = "";
                                $timestamp = date('Y-m-d H:i:s');
                                $tax = 0;
                                $gstn_no_status = 0;
                                $insertUserSubscription = $manage->master_trial_data($year, $amount, $date1, $final_date, $status, $referal_by, $refrenced_by, $active_plan, $invoice_no, $discount, $tax, $amount, $paymentBrand, $paymentMode, $custBankId, $timestamp, $gstn_no_status);
                                /* if ($insertUserSubscription) {
                                     $updateUserExpiry = $manage->updateUserExpiryDateById($final_date, $getUserId);
                                     if ($updateUserExpiry) {
                                         $update_email_count = $manage->update_email_countById($getUserId);
                                     }
                                 }*/
                                $toName = $txt_name;
                                $toEmail = trim($txt_email);
                                $message = '<table style="width: 100%">
<tr>
<td colspan="2" style=' . $back_image . '>
<div style="' . $overlay . '">
<div style=" margin: 0 auto">
 <div class="user-name-logo" style="padding-top: 10px;">
                        <div style="text-align: center;color: #c4a758;width: 80px;margin: 1px auto;background: white;border-radius: 50%;height: 80px;text-align: center;padding: 5px;">
                            <img src="https://sharedigitalcard.com/assets/img/logo/logo.png" style="padding-top: 15px;width:100%">
                        </div>
                    </div>
                    <div style="text-align: center;color: white;font-weight: 700;padding-bottom: 10px;">
                        <h1 style="font-size: 24px;margin: 0;">Share Digital Card</h1>
                    </div>
</div>
</div>
</td>
</tr>
<tr>
<td colspan="2">
 <div style=" font-size: 18px;">
                     <p>Dear <span class="cust-name">' . ucwords($txt_name) . '</span>,</p>
                    <p> We are happy to welcome you to the digital world of visiting card. Thank you for registration.<br><br>
                        Your registered email id: <span class="email-id">' . $txt_email . '</span><br><br>
                        Please follow the further process and get your digital card. </p>
                        <p>
                        <b>Login URL: <a href="https://sharedigitalcard.com/login.php">https://sharedigitalcard.com/login.php</a></b><br>
                        <b>Username: </b>' . $txt_contact . '/' . $txt_email . '<br><b>Password: </b>' . $txt_password . '
                        </p>
                 <a href="' . SHARED_URL . $custom_url . '" style="' . $btn . ';background: #db5ea5 !important;width: 100%;color: #ffffff;border-radius: 4px;font-size: 16px;padding: 10px 0;">Open Your Digital Card</a>
                    <p>To do any changes in your "Share Digital Card " click on to below button to login to our web portal or you can change your details from mobile application.</p>
                </div>
                        <br>
                        <p>Please do not share username and password with anyone due to security reason.</p>
                </div>
</td>
</tr>
<tr><td colspan="2" style="text-align:center">
<a href="http://sharedigitalcard.com/login.php" style="' . $btn . ';color:white; border-radius: 4px;"><img src="http://sharedigitalcard.com/user/assets/images/laptop.png" style="width: 19%;display: inline-block;vertical-align: middle;padding-right: 5px;color: white;">Click To Login</a>
                   <a target="_blank" href="https://play.google.com/store/apps/details?id=sharedigitalcard.com.digitalcard" style="padding: 0px;display: inline-block;vertical-align: middle;"><img src="https://sharedigitalcard.com/assets/img/playstore.png"
                                                                                          style="width: 135px" alt="digital card app"></a>
</td></tr>
<tr>
<td colspan="2" style=" font-size: 18px;">
<p> For any query email us on <a href="mailto:support@sharedigitalcard.com" class="payment">support@sharedigitalcard.com</a></p>
</td>
</tr>
<tr><td colspan="2" style="padding: 10px;background: #e6e6e6;height: 115px;">
 <div style="width: 85%;margin: 0 auto;">
                <div style=" width: 100%;margin: 0 auto;">
                    <div style="text-align:center">
                        <a href="https://www.facebook.com/sharedigitalcard/"><img src="http://sharedigitalcard.com/user/assets/images/fb.png"></a>
<a href="https://www.instagram.com/sharedigitalcard/"><img src="http://sharedigitalcard.com/user/assets/images/insta.png"></a>
<a href="https://www.youtube.com/watch?v=6T9Ia_2rsig&list=PLg1QyEHQ9MYYBRDxWqLrWLCyvJlSheqTh"><img src="http://sharedigitalcard.com/user/assets/images/yt.png"></a>
                    </div>
                </div>

            </div>
</td></tr>
</table>';
                                $sms_message1 = "Dear " . ucwords($txt_name) . ", \nPlease login to fill all your details to complete your digital card.\nURL:sharedigitalcard.com/login.php \nUsername=" . $txt_contact . "\nPassword=" . $txt_password . "\n\nclick here to open your digital card\n" . SHARED_URL . $custom_url;
                                /*end update*/

                                /*$sms_message1 = "Dear " . $_SESSION['name'] . ", \n Thank you for registration. Please complete further process to get your Digital Business Card.";*/
                                $subject = "ShareDigitalCard.com - Registration Successful.";
                           //     $sendMail = $manage->sendMail($toName, $toEmail, $subject, $message);
                                $send_sms = $manage->sendSMS($txt_contact, $sms_message1);
                                $request_message = "New User " . $_SESSION['name'] . ", \n has been registered contact no " . $txt_contact . ", \nPassword :-" . $txt_password . ".";
                                $send_sms1 = $manage->sendSMS($global_contact, $request_message);
                                if (isset($_GET['type']) && $_GET['type'] == 'android') {
                                    unset($_SESSION['verified_status']);
                                    unset($_SESSION['random_sms']);
                                    $_SESSION['recaptcha'] = false;
                                    unset($_SESSION['email_login']);
                                    unset($_SESSION['tmp_email']);
                                    unset($_SESSION['contact']);
                                    echo '<style>.reg_succ_div{ display: block !important; }
.register_otp{ display: none !important; }
.sms_registration{
display: none !important;
}
</style>';

                                } else {
                                    header("location:user/basic-user-info.php");
                                }
                            } else {
                                $error = true;
                                $errorMessage .= "Something went wrong!! Please try again later.";
                            }
                        }

                    }

                }


        }


    }

    if(isset($show_div_in_reg) && $show_div_in_reg=='email'){
        if(!isset($_SESSION["number"])) // for email
        {
            $_SESSION["number"] = 0;
        }

        $_SESSION["number"]=$_SESSION["number"]+1;

        if($_SESSION['number'] == 1){
            $_SESSION['email_login'] = "true";
            $_SESSION["number"]=$_SESSION["number"]+1;
        }
    }
    if (isset($_POST['change_session_email'])) {
        $_SESSION['email_login'] = "true";
    }

    if (isset($_POST['change_session_contact'])) {
        unset($_SESSION['email_login']);
    }

    $countries_array = $manage->getCountryCategory();
    if (isset($_SESSION['contact']) or isset($_SESSION['tmp_email'])) {
        echo '<style>
.sms_otp_box{
display: block !important;
}
.recaptcha-div{
display: none !important; 
}

</style>';
    }
    ?>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    <link href="user/assets/plugins/node-waves/waves.css" rel="stylesheet"/>
    <?php include "assets/common-includes/header_includes.php" ?>
   <!-- <script src="https://www.google.com/recaptcha/api.js" async defer></script>-->

    <style>
        .alert {
            padding: 4px 10px;
        }

        table tbody tr td {
            font-size: 13px;
        }

        .success-message {
            display: none;
        }

        .danger-message {
            display: none;
        }

        .sms_otp_box {
            display: none;
        }
    </style>
</head>

<body>
<!-- preloader area start -->

<!-- preloader area end -->
<!-- header area start -->

<?php
if(!isset($_GET['type']) && $_GET['type'] !='android') {
    include "assets/common-includes/header.php";
}
?>
<section class="<?php if(isset($_GET['type']) && $_GET['type'] =='android') { echo 'register-android-area'; }else{ echo 'feature-area';} ?> bg-gray padding_section margin_div background_register" id="feature">
    <div class="container-fluid padding_div">
        <div class="col-md-8 col-sm-6 col-xs-12">
            <div class="hidden-sm hidden-xs col-md-8 col-md-offset-1">
                <div style="position: relative; margin-top: 25%;">
                    <a href="https://www.youtube.com/embed/s9I8gIrvwEc" target="_blank"><img src="assets/img/registrationpage.png"></a>
                    <iframe id="register-video" src="https://www.youtube.com/embed/s9I8gIrvwEc"></iframe>
                </div>
            </div>
        </div>
        <div class="custom-reg-width hidden-sm col-xs-12 margin_top_div">
            <?php
            if (isset($_GET['coupon_code']) && $_GET['coupon_code'] != "") {
                ?>
                <div class="card_demo_card">
                    <a href="https://sharedigitalcard.com/m/index.php?custom_url=ajay-chorge"
                       target="_blank" class="demo_btn"><i class="fa fa-id-card" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;DEMO DIGITAL CARD
                    </a>
                </div>
                <?php
            }
            ?>
            <?php
            if(isset($_GET['type']) && $_GET['type'] =='android') {
                ?>
                    <div class="reg_title">
                        <h2>REGISTER NOW</h2>
                    </div>
                <?php
            }
            ?>
            <div class="card card_width">

                <div class="body register_otp" >
                    <form id="registration_form" method="POST">
                        <?php if ($error) {
                            ?>
                            <div class="alert alert-danger">
                                <?php if (isset($errorMessage)) echo $errorMessage; ?>
                            </div>
                            <?php
                        } else if (!$error && $errorMessage != "") {
                            ?>
                            <div class="alert alert-success">
                                <?php if (isset($errorMessage)) echo $errorMessage; ?>
                            </div>
                            <?php
                        }
                        ?>
                        <div class="msg">
                            <?php
                            if(isset($_GET['type']) && $_GET['type'] =='android') {
                                echo "Let's Get Started";
                            }else{
                              echo 'Registration';
                            }
                            ?>
                        </div>

                        <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>

                            <div class="form-line">
                                <input name="txt_name" type="text" class="form-control" placeholder="Full name"
                                       autofocus
                                       value="<?php if (isset($_POST['txt_name'])) echo $_POST['txt_name']; ?>">
                            </div>
                        </div>
                        <div class="input_email">
                            <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">email</i>
                        </span>

                                <div class="form-line">
                                    <input name="txt_email" type="email" class="form-control" placeholder="Email"
                                           autofocus value="<?php if (isset($_SESSION['tmp_email'])) {
                                        echo $_SESSION['tmp_email'];
                                    } elseif (isset($_POST['txt_email'])) {
                                        echo $_POST['txt_email'];
                                    } ?>" <?php if (isset($_SESSION['tmp_email'])) echo "disabled"; ?>>
                                </div>
                            </div>
                        </div>
                        <div class="input_contact">
                            <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>

                                <div class="form-line">
                                    <input type="text" name="txt_contact" class="form-control"
                                           placeholder="Contact Number"
                                           value="<?php if (isset($_SESSION['contact'])) {
                                               echo $_SESSION['contact'];
                                           } elseif (isset($_POST['txt_contact'])) {
                                               echo $_POST['txt_contact'];
                                           } ?>"
                                           autofocus <?php if (isset($_SESSION['contact'])) echo "disabled"; ?> >
                                </div>
                            </div>
                        </div>
                        <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">home</i>
                        </span>

                            <div class="form-line">
                                <input name="txt_company_name" type="text" class="form-control" placeholder="Company Name(Optional)"
                                       autofocus
                                       value="<?php if (isset($_POST['txt_company_name'])) echo $_POST['txt_company_name']; ?>">
                                <input name="robotest" type="hidden" />
                            </div>
                        </div>
                        <div class="input-group">
                         <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>

                            <div class="form-group form-float">
                                <div class="form-line">
                                    <select id="gender" name="gender" class="form-control">
                                        <option name="male"
                                                value="Male" <?php if (isset($_POST['gender']) && $_POST['gender'] == "Male") echo "selected"; ?>>
                                            Male
                                        </option>
                                        <option name="female"
                                                value="Female" <?php if (isset($_POST['gender']) && $_POST['gender'] == "Female") echo "selected"; ?>>
                                            Female
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="input-group">
                         <span class="input-group-addon">
                          <i class="fa fa-globe" style="font-size: 18px;"></i>
                        </span>

                            <div class="form-group form-float">
                                <div class="form-line">
                                    <select id="country" name="country" class="form-control">
                                       <!-- <option value="101">India</option>-->
                                        <?php
                                        while ($value = mysqli_fetch_array($countries_array)) {
                                            ?>
                                            <option <?php if ($countryName == $value['name']) {
                                                echo "selected";
                                            } elseif (isset($_POST['country']) && $_POST['country'] == $value['name']) {
                                                echo "selected";
                                            } ?> value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!--         <input readonly type="hidden" name="txt_custom_url" class="form-control"
                               placeholder="Custom Url"
                               autofocus value="<?php /*echo $random; */ ?>">-->

                        <div class="input-group" style="margin-bottom: 0">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>

                            <div class="form-progress">
                                <div class="form-line">
                                    <input name="txt_password" type="password" class="form-control"
                                           placeholder="Password" id="exampleInputPassword1"
                                           autofocus>
                                    <!--<span toggle="#exampleInputPassword1"
                                          class="fa fa-fw fa-eye field-icon toggle-password"></span>-->
                                </div>
                                <div class="progress">
                                    <div class="progress-bar"></div>
                                </div>
                            </div>

                        </div>
                        <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>

                            <div class="form-line">
                                <input name="txt_confirm_pass" type="password" class="form-control"
                                       placeholder="Confirm Password" autofocus>
                            </div>
                        </div>
                        <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>

                            <div class="form-line">
                                <input name="captcha" type="number" class="p-left-110 form-control"
                                       placeholder="Enter the sum " autofocus>
                                <img src="captcha.php" class="captcha_img" />
                            </div>
                        </div>

                        <div class="input-group" style="margin-bottom: 0">
                            <input type="checkbox" name="chk_terms" required="required"> I
                                Agree To <a
                                style="color: #2793e6;cursor: pointer" href="terms-and-conditions.php" target='_blank'>Terms & Condition</a> | <a
                                style="color: #2793e6;cursor: pointer" href="privacy-policy.php" target='_blank'>Privacy Policy</a> | <a
                                style="color: #2793e6;cursor: pointer" href="refund-and-return-policy.php" target='_blank'>Return and Refund Policy</a>
                        </div>
                        <div class="input-group">
                            <input type="checkbox" name="online_search" value="1"> Do You Want to promote your business
                            online.
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary form-control" name="btn_sharedigitalcard_registration" id="myBtn"
                                    style="margin-bottom: 10px">SIGN UP
                            </button>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <!--<button type="submit" style="float: left" class="resend_otp" name="btn_cancel">Cancel
                            </button>-->
                            <a href="sign-out-register.php<?php if(isset($_GET['type']) && $_GET['type'] == 'android') echo "?type=".$_GET['type']; ?>" style="float: left; text-decoration: underline">Cancel</a>
                        </div>
                    </form>
                </div>
                <div class="body sms_registration">
                    <form id="sms_verification" method="POST" data-group-name="digits" data-autosubmit="false">
                        <?php if ($error1) {
                            ?>
                            <div class="alert alert-danger">
                                <?php if (isset($errorMessage1)) echo $errorMessage1; ?>
                            </div>
                            <?php
                        } else if (!$error1 && $errorMessage1 != "") {
                            ?>
                            <div class="alert alert-success success_color">
                                <?php if (isset($errorMessage1)) echo $errorMessage1; ?>
                            </div>
                            <?php
                        }
                        ?>
                        <div class="alert alert-danger danger-message">

                        </div>
                        <div class="alert alert-success success_color success-message">

                        </div>
                        <?php
                        if (!isset($_SESSION['contact']) or !isset($_SESSION['tmp_email'])) {
                            ?>
                            <div class="msg p-0">
                                <h5>Create your Account</h5>

                                <p>In order to create your own personalise card for your business. <b>"Go Paperless, Go
                                        Digital"</b></p>
                            </div>
                            <?php
                        }
                        ?>

                        <?php
                        if (isset($_SESSION['email_login']) && $_SESSION['email_login'] == "true") {
                            ?>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="material-icons">email</i>
                                </span>

                                <div class="form-line">
                                    <input type="email" id="sms_email" name="sms_email" class="form-control"
                                           value="<?php
                                           if (isset($_POST['sms_email'])) {
                                               echo $_POST['sms_email'];
                                           } elseif (isset($_SESSION['tmp_email'])) echo $_SESSION['tmp_email']; ?>"
                                           placeholder="Enter Email Id"
                                           autofocus <?php if (isset($_SESSION['tmp_email'])) {
                                        echo "disabled";
                                    } ?> autocomplete="off" required="required">
                                </div>
                            </div>
                            <?php
                        } else {
                            ?>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="material-icons">phone</i>
                                </span>

                                <div class="form-line">
                                    <input type="number" id="contact_no" name="sms_contact" class="form-control"
                                           value="<?php if (isset($_POST['sms_contact'])) {
                                               echo $_POST['sms_contact'];
                                           } elseif (isset($_SESSION['contact'])) echo $_SESSION['contact']; ?>"
                                           placeholder="Contact Number"
                                           autofocus <?php if (isset($_SESSION['contact'])) {
                                        echo "disabled";
                                    } ?> autocomplete="off" onkeypress="return isNumberKey(event)" required="required"
                                           oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                           maxlength="10">
                                </div>
                            </div>
                            <?php } ?>

                            <!-- Google reCAPTCHA box -->
                            <?php
                                /*
                            if (!$_SESSION['recaptcha']) {
                                */?><!--
                                <div class="input-group recaptcha-div">
                                    <div class="g-recaptcha" data-sitekey="<?php /*echo $site_key; */?>"></div>
                                </div>

                                --><?php
                                /*                        }*/
                            ?>
                            <?php
                            /*                        if (isset($_SESSION['contact']) or isset($_SESSION['tmp_email'])) {
                                                    */ ?>
                            <div class="sms_otp_box">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="material-icons">lock</i>
                                </span>
                                <div class="otp_section">
                                    <div class="digit-group">
                                        <input class="send_textbox" type="number" id="digit-1" name="sms_otp[]"
                                               data-next="digit-2" onkeypress="return isNumberKey(event)"
                                               oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                               maxlength="1"/>
                                        <input class="send_textbox" type="number" id="digit-2" name="sms_otp[]"
                                               data-next="digit-3" data-previous="digit-1"
                                               onkeypress="return isNumberKey(event)"
                                               oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                               maxlength="1"/>
                                        <input class="send_textbox" type="number" id="digit-3" name="sms_otp[]"
                                               data-next="digit-4" data-previous="digit-2"
                                               onkeypress="return isNumberKey(event)"
                                               oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                               maxlength="1"/>
                                        <span class="splitter">&ndash;</span>
                                        <input class="send_textbox" type="number" id="digit-4" name="sms_otp[]"
                                               data-next="digit-5" data-previous="digit-3"
                                               onkeypress="return isNumberKey(event)"
                                               oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                               maxlength="1"/>
                                        <input class="send_textbox" type="number" id="digit-5" name="sms_otp[]"
                                               data-next="digit-6" data-previous="digit-4"
                                               onkeypress="return isNumberKey(event)"
                                               oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                               maxlength="1"/>
                                        <input class="send_textbox" type="number" id="digit-6" name="sms_otp[]"
                                               data-previous="digit-5" onkeypress="return isNumberKey(event)"
                                               oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                               maxlength="1"/>
                                    </div>
                                </div>
                                <!-- <div class="form-line">
                                     <input type="number" name="sms_otp" class="form-control" placeholder="OTP"
                                            autofocus onkeypress="return isNumberKey(event)"
                                            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                            maxlength="4">

                                </div>-->
                            </div>
                        </div>

                        <!--  --><?php
                        /*                        }
                                                */ ?>
                        <?php

                        ?>
                        <div class="sms_otp_box">
                            <button type="button" class="btn btn-block bg-pink waves-effect" onclick="verifyOTP()"
                                    name="verify_otp"
                                    style="margin-bottom: 15px">
                                Verify
                                OTP
                            </button>
                            <button type="submit" class="resend_otp" name="resend_otp">Resend OTP</button>

                            <!--<button type="submit" style="float: left" class="resend_otp" name="btn_cancel">Cancel
                            </button>-->

                            <a href="sign-out-register.php<?php if(isset($_GET['type']) && $_GET['type'] == 'android') echo "?type=".$_GET['type']; ?>"
                               style="float: left; text-decoration: underline">Cancel</a>
                            <br>
                            <div class="text-center" style="    width: 100%;
    overflow: hidden;">
                            <?php
                            if (isset($_SESSION['email_login']) && $_SESSION['email_login'] == "true") {
                                if ($countryName == "India") {
                                    ?>
                                    <a href="sign-out-register.php?contact_register=true" class="btn btn-info">Try with Contact</a>
                                <?php }
                            } else { ?>
                                <a href="sign-out-register.php?email_register=true" class="btn btn-info">Try with Email ID</a>
                            <?php } ?>
                                
                            </div>
                        </div>

                    </form>
                    <!-- --><?php
                    /*                        if (!isset($_SESSION['contact']) or !isset($_SESSION['tmp_email'])) {
                                                */ ?>
                    <div class="recaptcha-div">
                        <button type="button" class="btn btn-block bg-pink waves-effect" name="send_otp"
                                style="margin-bottom: 10px;" onclick="sendAjaxRequest()">Send
                            OTP
                        </button>

                        <form method="post" action="">
                            <?php
                            if (isset($_SESSION['email_login']) && $_SESSION['email_login'] == "true") {
                                if ($countryName == "India") {
                                    ?>
                                    <button class="resend_otp" type="submit" name="change_session_contact" style="color: #2793e6;font-size: 13px;float: left;padding-left: 0">Register with Contact
                                    </button>
                                <?php }
                            } else { ?>
                                <button class="resend_otp" type="submit" name="change_session_email" style="color: #2793e6;font-size: 13px;float: left;padding-left: 0">Register with email
                                </button>
                            <?php } ?>

                        </form>
                        <a href="<?php if(!isset($_GET['type'])) { echo "login".$extension;} else { echo "activity://login";}?>" class="resend_otp" style="color: #2793e6;font-size: 13px;">Log In</a>
                    </div>
                    <?php
                    /*                        }
                                            */ ?>

                </div>
                <div class="body reg_succ_div">
                    <div class="form-group text-center">
                        <div>
                            <img src="user/assets/images/check.png" >
                        </div>
                        <div>
                            <p>Dear <?php echo $_SESSION['name'];  ?></p>
                            <p>Your Account has been successfully created</p>
                            <div>
                                <a href="activity://login" type="button" class="btn btn-block bg-pink waves-effect"
                                        style="margin-bottom: 10px;" >Back To Login
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            if (!isset($_SESSION['contact'])) {
                ?>
                <div class="card_width card_bottom card">
                    <div class="body alreay_accout">
                        <div class="need-help">
                            <h5>Need Help</h5>
                            <p>We Would be Happy to help You!!</p>
                            <div class="tabular">
                                <table>
                                    <tbody>
                                    <tr>

                                        <td><i class="fa fa-phone"></i> +91 99677 83583/+91-9768904980
                                        </td>

                                    </tr>
                                    <tr>
                                        <td><i class="fa fa-envelope"></i> support@sharedigitalcard.com</td>

                                    </tr>

                                    </tbody>
                                </table>

                            </div>


                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</section>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header cust-upi-madal">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Terms & Condition</h4>
            </div>
            <div class="modal-body">
                <div class="body">
                    <div class="about-content terms_and_condition_content_point">
                        <p>Please read these terms and
                            conditions carefully before you start using the website. By using our website, you agree to
                            all the below listed terms and conditions.</p>

                        <h5><b>1. Using the Website and Application</b></h5>

                        <p>
                            Once you have become a Share digital card owner, you will be able to view your share digital
                            card on the Website and application. You can also claim, modify and delete any of the
                            contact
                            information which appears about you on the share digital card, including your name, company,
                            job
                            title, phone numbers, email Address, social media profiles, picture, company's logo, bank
                            details and videos. If you encounter Any difficulties while claiming, modifying or deleting
                            any
                            of your Contact Details, please send Us an email to
                            <strong>info@sharedigitalcard.com</strong>.
                        </p>

                        <p>
                            If the share digital Card was created by a Share digital card Owner other than yourself,
                            You will be sent an email by the applicable share digital card Owner through the
                            Application,
                            Inviting you to visit Our Website and review your share digital Card and Claim it.
                            We rely solely on the user (your) permission to create your share digital Card.
                            If you do not want to have the share digital Card on Our Website and/or any of your
                            Contact Details modified/removed, you may simply do so by visiting your share digital
                            Card on the Website or otherwise sending us an email to the email address above.
                        </p>

                        <h5><b>2. Intellectual Properties</b></h5>

                        <p>We are the owner or the licensee of all intellectual property rights in our site,
                            And All the proprietary algorithms and methods, inventions, patents, and patent
                            applications,
                            Copyrightable material, graphics, text, sounds, music, designs, specifications, data,
                            Technical
                            data, videos, interactive features, software (source and/or object code), files,
                            Interface, GUI and trade secrets pertaining thereto (collectively, "Share Digital Card"),
                            Are fully owned or licensed to us and are subject to copyright and other applicable
                            intellectual
                            Property rights under applicable laws, foreign laws and international conventions.
                            If you print, copy or download any part of our site in breach of these terms of use,
                            Your right to use our site will cease immediately and you must, at our option, return
                            Or destroy any copies of the materials you have made.
                        </p>

                        <h5><b>3. Registration</b></h5>

                        <p>To use our website and application you (user) need to provide accurate and authentic
                            information
                            about yourself, and after successful registration, you (user ) need to provide authentic
                            details
                            to create share digital cards. You are (user) solely responsible for updating and
                            maintaining
                            your information.
                        </p>

                        <h5><b>4. Payment</b></h5>

                        <p>You can register directly or after the free trial period. Charges for registration is as per
                            features and as per pricing listed on our website under the Pricing section.
                            Charges can be changed anytime for the subscription as per decision taken by the website
                            business/management team.
                            Charges taken from clients are for the maintenance purpose of their profile and website.
                            Any contact from our end via email will be from our business email ID's only. If you receive
                            any
                            kind of communication from other email ID's, please let let us know and kindly ignore the
                            email.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- footer area start -->
<?php
//if(!isset($_GET['type']) && $_GET['type'] !='android') {
    if(!isset($_GET['type'])) {
    include "assets/common-includes/footer.php";
}
?>
<!-- footer area end -->
<!-- Scripts -->
<?php include "assets/common-includes/footer_includes.php" ?>

<script src="user/assets/plugins/node-waves/waves.js"></script>
<script src="user/assets/js/admin.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="user/assets/js/commonValidation.js" type="text/javascript"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.2.0/zxcvbn.js" type="text/javascript"></script>
<script>
    function verifyOTP() {
        if ($("input[name='sms_otp[]']").val() != '') {
            var otp_number = $("input[name='sms_otp[]']")
                .map(function () {
                    return $(this).val();
                }).get();
            $.ajax({
                url: 'validate-recaptcha.php',
                type: 'POST',
                dataType: "json",
                data: {
                    verify_otp: otp_number
                },
                beforeSend: function () {
                    $('button[name=verify_otp]').text('Verifying OTP...').attr("disabled", "disabled");
                },
                success: function (result) {
                    if (result.status == 'ok') {
                        $('.register_otp').show();
                        $('.sms_registration').hide();
                        /* $('button[name=send_otp]').attr({
                             'type':'submit',
                             'name':'verify_otp'
                         }).removeAttr("onclick").text('Verify OTP');*/
                        $('.success-message').show();
                        $('.success-message').html(result.msg);
                        $('.danger-message').hide();
                    } else {
                        $('button[name=verify_otp]').text('Verify OTP').removeAttr("disabled");
                        $('.success-message').hide();
                        $('.danger-message').show();
                        $('.danger-message').html(result.msg);
                    }
                }
            });
        } else {
            $('.danger-message').show();
            $('.danger-message').html('Please enter OTP');
            return;
        }
    }
</script>
<script>
    function sendAjaxRequest() {
      /*  if (grecaptcha === undefined) {
            $('.danger-message').show();
            $('.danger-message').html('Recaptcha not defined');
            return;
        }*/
        <?php
        if(isset($_SESSION['email_login']) && $_SESSION['email_login'] == "true") {
        ?>
        var email_contact = $('#sms_email').val();
        var type = "email";
        <?php
        }else{
        ?>
        var email_contact = $('#contact_no').val();
        var type = "contact";
        <?php } ?>
      /*  var response = grecaptcha.getResponse();*/

       /* if (!response) {
            $('.danger-message').show();
            $('.danger-message').html('Please select recaptcha');
            return;
        }*/
        /*
        * recaptcha: response, */
        if (email_contact != '') {
            console.log('here');
            $.ajax({
                url: 'validate-recaptcha.php',
                type: 'POST',
                dataType: "json",
                data: {
                     email_contact: email_contact, type: type
                },
                cache: false,
                beforeSend: function () {
                    $('button[name=send_otp]').text('Sending OTP...').attr("disabled", "disabled");
                },
                success: function (result) {

                   /* grecaptcha.reset();*/
                    if (result.status == 'ok') {
                        $('.sms_otp_box').show();
                        $('.recaptcha-div').hide();
                        /* $('button[name=send_otp]').attr({
                             'type':'submit',
                             'name':'verify_otp'
                         }).removeAttr("onclick").text('Verify OTP');*/
                        $('button[name=send_otp]').hide();
                        $('.success-message').show();
                        $('.success-message').html(result.msg);
                        $('.danger-message').hide();
                        if (type == "contact") {
                            $('.input_contact').hide();
                        } else {
                            $('.input_email').hide();
                        }
                    } else {
                        $('button[name=send_otp]').text('Send OTP').removeAttr("disabled");
                        $('.success-message').hide();
                        $('.danger-message').show();
                        $('.danger-message').html(result.msg);
                    }
                }
            });
        } else {
            $('.danger-message').show();
            $('.danger-message').html('Please enter some value');
        }
    }
</script>
<script>

    $('.digit-group').find('input').each(function () {
        $(this).attr('maxlength', 1);
        $(this).on('keyup', function (e) {
            var parent = $($(this).parent());

            if (e.keyCode === 8 || e.keyCode === 37) {
                var prev = parent.find('input#' + $(this).data('previous'));

                if (prev.length) {
                    $(prev).select();
                }
            } else if ((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 65 && e.keyCode <= 90) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode === 39) {
                var next = parent.find('input#' + $(this).data('next'));

                if (next.length) {
                    $(next).select();
                } else {
                    if (parent.data('autosubmit')) {
                        parent.submit();
                    }
                }
            }
        });
    });
</script>

<script>
    $(".toggle-password").click(function () {

        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
</script>
<script>
    $("#contact_no").on("keypress", function (evt) {
        var keycode = evt.charCode || evt.keyCode;
        if (keycode == 46) {
            return false;
        }
    });


</script>
<script>
    $(function () {
        $.fn.bootstrapPasswordMeter = function (options) {
            var settings = $.extend({
                minPasswordLength: 6,
                level0ClassName: 'progress-bar-danger',
                level0Description: 'Weak',
                level1ClassName: 'progress-bar-danger',
                level1Description: 'Not great',
                level2ClassName: 'progress-bar-warning',
                level2Description: 'Better',
                level3ClassName: 'progress-bar-success',
                level3Description: 'Strong',
                level4ClassName: 'progress-bar-success',
                level4Description: 'Very strong',
                parentContainerClass: '.form-progress'
            }, options || {});

            $(this).on("keyup", function () {
                var progressBar = $(this).closest(settings.parentContainerClass).find('.progress-bar');
                var progressBarWidth = 0;
                var progressBarDescription = '';
                if ($(this).val().length >= settings.minPasswordLength) {
                    var zxcvbnObj = zxcvbn($(this).val());
                    progressBar.removeClass(settings.level0ClassName)
                        .removeClass(settings.level1ClassName)
                        .removeClass(settings.level2ClassName)
                        .removeClass(settings.level3ClassName)
                        .removeClass(settings.level4ClassName);
                    switch (zxcvbnObj.score) {
                        case 0:
                            progressBarWidth = 25;
                            progressBar.addClass(settings.level0ClassName);
                            progressBarDescription = settings.level0Description;
                            break;
                        case 1:
                            progressBarWidth = 25;
                            progressBar.addClass(settings.level1ClassName);
                            progressBarDescription = settings.level1Description;
                            break;
                        case 2:
                            progressBarWidth = 50;
                            progressBar.addClass(settings.level2ClassName);
                            progressBarDescription = settings.level2Description;
                            break;
                        case 3:
                            progressBarWidth = 75;
                            progressBar.addClass(settings.level3ClassName);
                            progressBarDescription = settings.level3Description;
                            break;
                        case 4:
                            progressBarWidth = 100;
                            progressBar.addClass(settings.level4ClassName);
                            progressBarDescription = settings.level4Description;
                            break;
                    }
                } else {
                    progressBarWidth = 0;
                    progressBarDescription = '';
                }
                progressBar.css('width', progressBarWidth + '%');
                progressBar.text(progressBarDescription);
            });
        };
        $('#exampleInputPassword1').bootstrapPasswordMeter({minPasswordLength: 3});
    });
</script>

<!--<script src="user/assets/js/pages/examples/sign-up.js"></script>-->
</body>
</html>