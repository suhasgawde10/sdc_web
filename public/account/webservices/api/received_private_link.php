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
    if (isset($_POST["user_id"]) && isset($_POST["received_status"])) {
        if ($_POST["user_id"] != '' && $_POST["received_status"] != '') {
            $id = '';
            $received_status = '';
            try {
                if (is_numeric($_POST["user_id"])) {
                    $user = $manage->getCustomerCount($_POST["user_id"]);
                    if ($user) {
                        $id = $_POST["user_id"];
                    } else {
                        $error = true;
                        $response["code"] = 181;
                        array_push($message, "This user id is not valid.");
                    }
                } else {
                    $error = true;
                    $response["code"] = 181;
                    array_push($message, "Enter user id only number value.");
                }
                if (isset($_POST["received_status"]) && $_POST["received_status"] == "email" || $_POST["received_status"] == "sms" || $_POST["received_status"] == "both") {
                    $received_status = $_POST["received_status"];
                } else {
                    $error = true;
                    $response["code"] = 181;
                    array_push($message, "Enter only email or sms or both.");
                }
            } catch (Exception $ex) {
                $response["message"] = "Something went wrong.";
            }

            if ($error == false) {
                try {
                    $update = $manage->mu_updateUserReciever($received_status, $id);
                    if ($update) {
                        $response["status"] = true;
                        $response["code"] = 185;
                        $response["received_status"] = $received_status;
                        $response["message"] = "Received private link has been changed successfully";
                    } else {
                        $response["code"] = 181;
                        $response["message"] = "Issue while changed private link.";
                    }
                } catch (Exception $e) {
                    $response["message"] = "Something went wrong.";
                }
            }
            if ($error == true) {
                $response["message"] = $message;
            }
        } else {
            $response["code"] = 180;
            $response["message"] = "all field is required.";
        }
    } else {
        $response["code"] = 100;
        $response["status"] = false;
        $response["message"] = "Parameter not set";
    }
    echo json_encode($response);
}