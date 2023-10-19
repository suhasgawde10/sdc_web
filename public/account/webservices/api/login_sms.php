<?php

// require "vendor/autoload.php";

// use \Firebase\JWT\JWT;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include '../../controller/ManageApi.php';
$manage = new ManageAPI();
include '../../controller/EncryptDecrypt.php';
$security = new EncryptDecrypt;
date_default_timezone_set('Asia/Calcutta');
// echo $security->decrypt('iKOulpmWq3Vnamxo
// ');
// die();
use Twilio\Rest\Client;

if (isset($_POST)) {
    $response = array();
    $message = array();
    $response["status"] = false;
    $error = false;
    $adminData = array();
    // echo "hiii";
    // die();
    // $jwt = $manage->generateJwtToken();

    if (isset($_POST["mobile_no"]) && isset($_POST["app_signature"])) {

        if ($_POST["mobile_no"] != '' && $_POST["app_signature"] != '') {
            $mobile = $_POST["mobile_no"];
            $app_signature = $_POST["app_signature"];
            $status = 1;
            $today = date("Y-m-d");

            try {
                if (is_numeric($mobile)) {
                    $verifyContact = $manage->getContactCount($mobile);
                    // dd($verifyContact);
                    // die();
                    if ($verifyContact) {
                        $status_value = $manage->getstatus($status, $mobile);
                        if ($status_value) {
                            $expiryDate = $manage->getExpiryDate($today, $mobile);
                            if ($expiryDate) {
                            } else {
                                $error = true;
                                $response["code"] = 101;
                                array_push($message, "Your digital card plan is expired.");
                            }
                        } else {
                            $error = true;
                            $response["code"] = 101;
                            array_push($message, "Your account is inactive.");
                        }
                    } else {
                        $error = true;
                        $response["code"] = 101;
                        array_push($message, "This mobile number is not valid.");
                    }
                } else {
                    $error = true;
                    $response["code"] = 101;
                    array_push($message, "Enter mobile number only number value.");
                }
            } catch (Exception $ex) {
                $response["message"] = "Something went wrong.".$ex;
            }

            if ($error == false) {
                try {
                    $otp = rand(1111, 9999);
                    $getLoginTbData = $manage->getLoginTable($mobile);
                    $user_id = $getLoginTbData['user_id'];
                    $email_id = $getLoginTbData['email'];
                    $updateOtp = $manage->updateOtp($otp, $user_id);
                    if ($updateOtp) {
                        if (SMS_STATUS == "1") {
                            $sms_message = "Dear%20Customer%2C%20%0AFor%20login%20into%20the%20website%20or%20mobile%20application%2C%20Your%20One-Time%20Password%20%28OTP%29%20is%20".$otp.".%20Please%20do%20not%20share%20this%20OTP%20with%20anyone.%20Message%20ID%3A%20".$app_signature."%0ABest%20Regards%20%0ADGCARD";
                            $send_sms = $manage->sendSMSWithTemplateId($mobile, $sms_message, TEMPLATE_LOGIN);
                        }
                        $response["code"] = 105;
                        $response["status"] = true;
                        $response["mobile_no"] = $mobile;
                        $response['email'] = $email_id;
                        $response["message"] = "OTP has been sent to you entered Mobile Number";
                        $response["otp"] = $otp;
                    } else {
                        $response["message"] = "Issue while send otp.";
                    }
                } catch (Exception $e) {
                    $response["message"] = "Something went wrong.".$e;
                }
            }

            if ($error == true) {
                $response["message"] = $message;
            }
        } else {
            $response["code"] = 101;
            $response["message"] = 'All field is required';
        }
    } else {
        $response["code"] = 100;
        $response["status"] = false;
        $response["message"] = "Parameter not set";
    }
    echo json_encode($response);
}