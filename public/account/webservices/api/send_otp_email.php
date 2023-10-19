<?php

// require "vendor/autoload.php";

// use \Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include '../../controller/ManageApi.php';
$manage = new ManageAPI();
include '../../controller/EncryptDecrypt.php';
$security = new EncryptDecrypt;
include '../../sendMail/sendMail.php';
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

    if (isset($_POST["mobile_no"])) {

        if ($_POST["mobile_no"] != '') {
            $mobile = $_POST["mobile_no"];
            // $app_signature = $_POST["app_signature"];
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
                                $response["code"] = 111;
                                array_push($message, "This account is expiry.");
                            }
                        } else {
                            $error = true;
                            $response["code"] = 111;
                            array_push($message, "This account is inactive.");
                        }
                    } else {
                        $error = true;
                        $response["code"] = 111;
                        array_push($message, "This mobile number is not valid.");
                    }
                } else {
                    $error = true;
                    $response["code"] = 111;
                    array_push($message, "Enter mobile number only number value.");
                }
            } catch (Exception $ex) {
                $response["message"] = "Something went wrong.";
            }

            if ($error == false) {
                try {
                    $otp = rand(1111, 9999);
                    $getLoginTbData = $manage->getLoginTableAndProfile($mobile);
                    $user_id = $getLoginTbData['user_id'];
                    $email = $getLoginTbData['email'];
                    $name = $getLoginTbData['name'];
                    // dd($getLoginTbData);
                    // die();
                    $updateOtp = $manage->updateOtp($otp, $user_id);
                    if ($updateOtp) {
                        if (EMAIL_STATUS == "1") {
                            $toName =   $name;
                            $toEmail =  $email;
                            $subject = "OTP for login";
                            $message = "Dear " . $toName .
                                " ,<br><br>Use this OTP " . $otp . " to verify for login.<br><br>
                                Regards,<br><br>
                                Share Digital Card.";;
                            $sendMail = $manage->sendMail($toName, $toEmail, $subject, $message);
                        }
                        $response["code"] = 115;
                        $response["status"] = true;
                        $response["email"] = $email;
                        $response["message"] = "OTP has been sent to you registered email";
                        $response["otp"] = $otp;
                    } else {
                        $response["message"] = "Issue while send otp.";
                    }
                } catch (Exception $e) {
                    $response["message"] = "Something went wrong.";
                }
            }

            if ($error == true) {
                $response["message"] = $message;
            }
        } else {
            $response["code"] = 111;
            $response["message"] = 'All field is required';
        }
    } else {
        $response["code"] = 110;
        $response["status"] = false;
        $response["message"] = "Parameter not set";
    }
    echo json_encode($response);
}