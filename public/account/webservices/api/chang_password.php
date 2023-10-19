<?php

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

require "protected.php";

if (isset($_POST)) {
    $response = array();
    $message = array();
    $response["status"] = false;
    $error = false;
    $addData = array();
    if (isset($_POST["user_id"]) && isset($_POST["old_password"]) && isset($_POST["new_password"]) && isset($_POST["confirm_password"])) {
        if ($_POST["user_id"] != '' && $_POST["old_password"] != '' && $_POST["new_password"] != '' && $_POST["confirm_password"]) {
            $id = $_POST["user_id"];
            try {
                if (is_numeric($_POST["user_id"])) {
                    $user = $manage->getCustomerCount($_POST["user_id"]);
                    if ($user) {
                    } else {
                        $error = true;
                        $response["code"] = 171;
                        array_push($message, "This user id is not valid.");
                    }
                } else {
                    $error = true;
                    $response["code"] = 171;
                    array_push($message, "Enter user id only number value.");
                }



                if ($_POST["new_password"] == $_POST["confirm_password"]) {
                } else {
                    $error = true;
                    $response["code"] = 172;
                    array_push($message, "new password and confirm password not matched.");
                }
                if ($_POST["old_password"] != $_POST["new_password"]) {
                } else {
                    $error = true;
                    $response["code"] = 173;
                    array_push($message, "Please enter diffrent password.");
                }
            } catch (Exception $ex) {
                $response["message"] = "Something went wrong.";
            }

            if ($error == false) {
                try {
                    $getData = $manage->loginChangePassword($_POST["user_id"]);
                    $oldPassword = $getData['password'];
                    $old_input = $security->encrypt($_POST["old_password"]) . "8523";

                    if ($oldPassword == $old_input) {
                        $confirmPassword = $_POST["confirm_password"];
                        $update = $manage->updatePassword($security->encrypt($confirmPassword) . "8523", $id);
                        if ($update) {
                            $response["status"] = true;
                            $response["code"] = 175;
                            $response["message"] = "Your password has been changed successfully";
                        } else {
                            $response["code"] = 174;
                            $response["message"] = "Issue while changed password.";
                        }
                    } else {
                        $response["code"] = 174;
                        $response["message"] = "Invalid old password! please enter a valid password to reset it.";
                    }
                } catch (Exception $e) {
                    $response["message"] = "Something went wrong.";
                }
            }
            if ($error == true) {
                $response["message"] = $message;
            }
        } else {
            $response["code"] = 170;
            $response["message"] = "all field is required.";
        }
    } else {
        $response["code"] = 100;
        $response["status"] = false;
        $response["message"] = "Parameter not set";
    }
    echo json_encode($response);
}