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

// require "protected.php";

if (isset($_POST)) {
    $response = array();
    $message = array();
    $response["status"] = false;
    $error = false;
    $addData = array();
    if (isset($_POST["user_id"]) && isset($_POST["toggle_value"])) {
        if ($_POST["toggle_value"] != '' && $_POST["toggle_value"] != '') {
            $id = '';
            $toggle_value = '';
            $section_id = 7;
            $website = 0;
            $digitalCard = 0;
            try {
                if (is_numeric($_POST["user_id"])) {
                    $user = $manage->getCustomerCount($_POST["user_id"]);
                    if ($user) {
                        $id = $_POST["user_id"];
                    } else {
                        $error = true;
                        $response["code"] = 161;
                        array_push($message, "This user id is not valid.");
                    }
                } else {
                    $error = true;
                    $response["code"] = 161;
                    array_push($message, "Enter user id only number value.");
                }
                if (isset($_POST["toggle_value"]) && $_POST["toggle_value"] == 0 || $_POST["toggle_value"] == 1) {
                    $toggle_value = $_POST["toggle_value"];
                } else {
                    $error = true;
                    $response["code"] = 161;
                    array_push($message, "enter toggle value enter 0 or 1.");
                }

                if ($toggle_value == 1) {
                    $website = 1;
                    $digitalCard = 1;
                }
                // dd($website);
                // dd($digitalCard);
                // die();
            } catch (Exception $ex) {
                $response["message"] = "Something went wrong.";
            }

            if ($error == false) {
                try {
                    $update = $manage->updateSectionStatus($section_id, $digitalCard, $website, $id);
                    if ($update) {
                        $response["status"] = true;
                        $response["code"] = 165;
                        $response["section_id"] = $section_id;
                        $response["digitalCard"] = $digitalCard;
                        $response["website"] = $website;
                        if ($toggle_value == 1) {
                            $response["message"] = "Bank details unhide successfully ";
                        } else if ($toggle_value == 0) {
                            $response["message"] = "Bank details hide successfully ";
                        }
                    } else {
                        $response["code"] = 161;
                        $response["message"] = "Issue while changed bank details.";
                    }
                } catch (Exception $e) {
                    $response["message"] = "Something went wrong.";
                }
            }
            if ($error == true) {
                $response["message"] = $message;
            }
        } else {
            $response["code"] = 160;
            $response["message"] = "all field is required.";
        }
    } else {
        $response["code"] = 100;
        $response["status"] = false;
        $response["message"] = "Parameter not set";
    }
    echo json_encode($response);
}