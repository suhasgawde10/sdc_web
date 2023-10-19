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
    if (isset($_POST["user_id"]) && isset($_POST["business_status"])) {
        if ($_POST["user_id"] != '' && $_POST["business_status"] != '') {
            $id = '';
            $business_status = '';
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
                if (isset($_POST["business_status"]) && $_POST["business_status"] == 0 || $_POST["business_status"] == 1) {
                    $business_status = $_POST["business_status"];
                } else {
                    $error = true;
                    $response["code"] = 181;
                    array_push($message, "Enter only 0 or 1.");
                }
            } catch (Exception $ex) {
                $response["message"] = "Something went wrong.";
            }

            if ($error == false) {
                try {
                    $update = $manage->updateUserOnlineSearch($business_status, $id);
                    if ($update) {
                        $response["status"] = true;
                        $response["code"] = 185;
                        $response["promote_status"] = $business_status;
                        if ($business_status == 1) {
                            $response["message"] = "Promote my business active successfully";
                        } else if ($business_status == 0) {
                            $response["message"] = "Promote my business deactive successfully";
                        }
                    } else {
                        $response["code"] = 181;
                        $response["message"] = "Issue while changed Promote my business.";
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