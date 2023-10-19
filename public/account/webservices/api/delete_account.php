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
    if (isset($_POST["user_id"]) && isset($_POST["reason_leaving"]) && isset($_POST["further"]) && isset($_POST["password"])) {
        if ($_POST["user_id"] != '' && $_POST["reason_leaving"] != '' && $_POST["further"] != '' && $_POST["password"] != '') {
            $id = '';
            $reason_leaving = $_POST["reason_leaving"];
            $further = $_POST["further"];
            $password = $security->encrypt($_POST["password"]) . "8523";
            try {
                if (is_numeric($_POST["user_id"])) {
                    $user = $manage->getCustomerCount($_POST["user_id"]);
                    if ($user) {
                        $id = $_POST["user_id"];
                    } else {
                        $error = true;
                        $response["code"] = 191;
                        array_push($message, "This user id is not valid.");
                    }
                } else {
                    $error = true;
                    $response["code"] = 191;
                    array_push($message, "Enter user id only number value.");
                }

                $getId = $manage->getLoginTablePassword($id);
                $dbpassword = $getId['password'];
                if ($password == $dbpassword) {
                } else {
                    $error = true;
                    $response["code"] = 191;
                    array_push($message, "Enter valid password");
                }
            } catch (Exception $ex) {
                $response["message"] = "Something went wrong.";
            }

            if ($error == false) {
                try {
                    $insert = $manage->deactivateUserAccount($reason_leaving, $further, "Deleted", 3, $id);
                    if ($insert) {
                        $response["status"] = true;
                        $response["code"] = 195;
                        $response["message"] = "Your account deleted successfully";
                    } else {
                        $response["code"] = 191;
                        $response["message"] = "Issue while  deleted account.";
                    }
                } catch (Exception $e) {
                    $response["message"] = "Something went wrong.";
                }
            }
            if ($error == true) {
                $response["message"] = $message;
            }
        } else {
            $response["code"] = 190;
            $response["message"] = "all field is required.";
        }
    } else {
        $response["code"] = 100;
        $response["status"] = false;
        $response["message"] = "Parameter not set";
    }
    echo json_encode($response);
}