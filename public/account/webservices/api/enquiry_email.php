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
    if (isset($_POST["user_id"]) && isset($_POST["enquiry_email"])) {
        if ($_POST["user_id"] != '' && $_POST["enquiry_email"] != '') {
            $id = '';
            $enquiry_email = '';
            try {
                if (is_numeric($_POST["user_id"])) {
                    $user = $manage->getCustomerCount($_POST["user_id"]);
                    if ($user) {
                        $id = $_POST["user_id"];
                    } else {
                        $error = true;
                        $response["code"] = 151;
                        array_push($message, "This user id is not valid.");
                    }
                } else {
                    $error = true;
                    $response["code"] = 151;
                    array_push($message, "Enter user id only number value.");
                }
                if (filter_var($_POST["enquiry_email"], FILTER_VALIDATE_EMAIL)) {
                    $enquiry_email = $_POST["enquiry_email"];
                } else {
                    $error = true;
                    $response["code"] = 151;
                    array_push($message, "Enter valid email.");
                }
            } catch (Exception $ex) {
                $response["message"] = "Something went wrong.";
            }

            if ($error == false) {
                try {
                    $update = $manage->updateEnquiryEmail($enquiry_email, $id);
                    if ($update) {
                        $response["status"] = true;
                        $response["code"] = 155;
                        $response["enquiry_email"] = $enquiry_email;
                        $response["message"] = "Your enquiry email has been changed successfully";
                    } else {
                        $response["code"] = 151;
                        $response["message"] = "Issue while changed enquiry email.";
                    }
                } catch (Exception $e) {
                    $response["message"] = "Something went wrong.";
                }
            }
            if ($error == true) {
                $response["message"] = $message;
            }
        } else {
            $response["code"] = 150;
            $response["message"] = "all field is required.";
        }
    } else {
        $response["code"] = 100;
        $response["status"] = false;
        $response["message"] = "Parameter not set";
    }
    echo json_encode($response);
}