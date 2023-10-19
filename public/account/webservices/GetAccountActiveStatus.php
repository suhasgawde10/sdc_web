<?php

header('Content-Type: application/json');
include "../controller/ManageService.php";
include "../controller/EncryptDecrypt.php";
$manage = new ManageService();
$security = new EncryptDecrypt();

$response = array();
$response["success"] = false;



if (isset($_POST['user_id']) && $_POST['user_id'] != ''
&& isset($_POST['api_key']) && $_POST['api_key'] != '') {
    $user_id = $security->decryptWebservice($_POST['user_id']);
    $api_key = $security->decryptWebservice($_POST['api_key']);
    $user = $manage->validateUserIdAndAPIKey($user_id, $api_key);
    if ($user != null) {
        $date = date("Y-m-d");
        if ($user["expiry_date"] < $date) {
            $response["success"] = false;
            $response["message"] = "Sorry, It Seems that your plan is expired, please renew your Digital card Subscription.";
        }elseif($user["status"] == 1) {
            $response["success"] = true;
            $response["message"] = "Active User!";
        }elseif ($user["status"] == 0){
            $response["success"] = false;
            $response["message"] = "You have been blocked please contact to adminstrator to reactivate your account.";
        }elseif ($user["status"] == 2){
            $response["success"] = false;
            $response["message"] = "Your account has been Deactivated, you can reactive your account from the login page.";
        }elseif ($user["status"] == 3){
            $response["success"] = false;
            $response["message"] = "This account has been deleted.";
        }
    } else {
        $response["success"] = false;
        $response["message"] = "Invalid User";
    }
} else {
    $response["success"] = false;
    $response["message"] = "Parameter are missing.";
}
echo json_encode($response);
