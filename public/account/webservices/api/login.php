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
    
    // dd($jwt);
    // die();


    if (isset($_POST["monile_no"]) && isset($_POST["otp"]) && isset($_POST["current_app_version"]) && isset($_POST["android_version"]) && isset($_POST["devices_model"]) && isset($_POST["fcm_token"]) && isset($_POST["longitude"]) && isset($_POST["latitude"]) && isset($_POST["app_locality"])) {

        if ($_POST["monile_no"] != '' && $_POST["otp"] != '' && $_POST["current_app_version"] != '' && $_POST["android_version"] != '' && $_POST["devices_model"] != '' && $_POST["fcm_token"] != '' && $_POST["longitude"] != '' && $_POST["latitude"] != '' && $_POST["app_locality"] != '') {


            $adminData['current_app_version'] = $_POST["current_app_version"];
            $adminData['android_version'] = $_POST["android_version"];
            $adminData['devices_model'] = $_POST["devices_model"];
            $adminData['fcm_token'] = $_POST["fcm_token"];
            $adminData['longitude'] = $_POST["longitude"];
            $adminData['latitude'] = $_POST["latitude"];
            $adminData['app_locality'] = $_POST["app_locality"];
            if (is_numeric($_POST["monile_no"])) {
                $status = 1;
                $today = date("Y-m-d");
                $verifyContact = $manage->getContactCount($_POST["monile_no"]);
                if ($verifyContact) {
                    $adminData['mobile_no'] = $_POST["monile_no"];
                    
                    $status_value = $manage->getstatus($status, $_POST["monile_no"]);
                    if ($status_value) {
                        $expiryDate = $manage->getExpiryDate($today, $_POST["monile_no"]);
                        if ($expiryDate) {
                        } else {
                            $error = true;
                            $response["code"] = 101;
                            array_push($message, "Your digital card plan is expired.");
                        }
                    } else {
                        // $error = true;
                        // $response["code"] = 101;
                        // array_push($message, "Your account is inactive.");
                    }
                } else {
                    $error = true;
                    $response["code"] = 121;
                    array_push($message, "This mobile number is not valid.");
                }
            } else {
                $error = true;
                $response["code"] = 124;
                array_push($message, "This mobile number is not valid.");
            }

            if (is_numeric($_POST["otp"])) {
                $verifyOtp = $manage->getOtpCount($_POST["otp"]);
                if ($verifyOtp) {
                    $adminData['otp'] = $_POST["otp"];
                } else {
                    $error = true;
                    $response["code"] = 121;
                    array_push($message, "This otp is not valid.");
                }
            } else {
                $error = true;
                $response["code"] = 121;
                array_push($message, "Enter otp only number value.");
            }

            $userData = $manage->userDataUserProfile($adminData['mobile_no']);
            $staus = $userData['status'];
            if ($staus == 0) {
                $error = true;
                $response["code"] = 121;
                array_push($message, "Your account is block.");
            } else if ($staus == 2) {
                $error = true;
                $response["code"] = 121;
                array_push($message, "Your account is deactive.");
            } else if ($staus == 3) {
                $error = true;
                $response["code"] = 121;
                array_push($message, "Your account is paramanrtly deleted plese re-register.");
            }


            if ($error == false) {
                $length = 15;
                $token = $security->random_strings($length);
                // dd($token);
                // die();

                $getId = $manage->getLoginTable($adminData['mobile_no']);
                $user_id = $getId['user_id'];
                // dd($user_id);
                // die();

                try {
                    $updateUserData = $manage->updateUserData($adminData, $user_id);
                    if ($updateUserData) {
                        $updateKey = $manage->updateNotificationKey($adminData, $user_id);
                        $getUserData = $manage->getProfileUserData($user_id);
                        // dd($getUserData);
                        // die();
                        if ($getUserData != null) {
                            $jwt = $manage->generateJwtToken($getUserData['id']);
                            $response["status"] = true;
                            $response["code"] = 125;
                            $response["id"] = $getUserData['id'];
                            $response["name"] = $getUserData['name'];
                            $response["custom_url"] = $getUserData['custom_url'];
                            $response["designation"] = $getUserData['designation'];
                            $response["status"] = $getUserData['status'];
                            $response["expiry_date"] = $getUserData['expiry_date'];
                            $response["country"] = $getUserData['country'];
                            $response["state"] = $getUserData['state'];
                            $response["city"] = $getUserData['city'];
                            $response["company_logo"] = $getUserData['company_logo'];
                            $response["verify_number"] = $getUserData['verify_number'];
                            $response["verified_email_status"] = $getUserData['verified_email_status'];
                            $response["company_name"] = $getUserData['company_name'];
                            $response["user_referer_code"] = $getUserData['user_referer_code'];
                            $response["email"] = $getUserData['email'];
                            $response["enquiry_email"] = $getUserData['enquiry_email'];
                            $response["contact_no"] = $getUserData['contact_no'];
                            $response["api_key"] = $getUserData['api_key'];
                            $response["user_notification"] = $getUserData['user_notification'];
                            $response["token"] = $jwt;
                            $response["section_id"] =  $getUserData[''];
                            $response["digitalCard"] = $getUserData[''];
                            $response["website"] =  $getUserData[''];
                            $response["message"] = "User Login successfully";
                        }
                    } else {
                        $response["code"] = 121;
                        $response["message"] = "Issue while updating data.";
                    }
                } catch (Exception $ex) {
                    $response["code"] = 121;
                    $response["message"] = "Something went wrong.";
                }
            }
            if ($error == true) {
                $response["message"] = $message;
            }
        } else {
            $response["code"] = 133;
            $response["message"] = 'All field is required';
        }
    } else {
        $response["code"] = 120;
        $response["status"] = false;
        $response["message"] = "Parameter not set";
    }
    echo json_encode($response);
}