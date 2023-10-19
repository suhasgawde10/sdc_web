<?php

header('Content-Type: application/json');
include "../controller/ManageService.php";
include "../controller/EncryptDecrypt.php";
$manage = new ManageService();
$security = new EncryptDecrypt();

$response = array();
$response["success"] = false;



if (isset($_POST['email_address_mobile_num']) && $_POST['email_address_mobile_num'] != ''
    && isset($_POST['password']) && $_POST['password'] != '') {
  $email_address = $security->decryptWebservice($_POST['email_address_mobile_num']);
   $password = $security->decryptWebservice($_POST['password']);
    $check_password = $security->encrypt($password) . "8523";
    $user = $manage->userLogin($email_address, $check_password);
    if ($user != null) {
        if ($user["status"] == 1) {
            $date = date("Y-m-d");
            if ($user["expiry_date"] < $date) {
                $response["message"] = "Your Digital card Subscription expired, please renew to take advantage.";
            } else {
                $response["success"] = true;
                $response["message"] = "Valid User";
                $response["email"] = $security->encryptWebservice($user["email"]);
                $response["name"] = $security->encryptWebservice($user["name"]);
                $response["img_name"] = $security->encryptWebservice($user["img_name"]);
                $response["expiry_date"] = $security->encryptWebservice($user["expiry_date"]);
                $response["user_id"] = $security->encryptWebservice($user["user_id"]);
                $response["custom_url"] = $security->encryptWebservice($user["custom_url"]);
                $response["contact_no"] = $security->encryptWebservice($user["contact_no"]);
                $response["user_referer_code"] = $security->encryptWebservice($user["user_referer_code"]);
                $response["designation"] = $security->encryptWebservice($user["designation"]);
                $response["api_key"] = $security->encryptWebservice($user["api_key"]);

            
            }
        }elseif ($user["status"] == 0){
            $response["success"] = false;
            $response["message"] = "You have been blocked please contact to adminstrator to reactivate your account.";
        }elseif ($user["status"] == 2){
            $response["success"] = false;
            $response["message"] = "Your account has been Deactivated, you can reactive your account from the login page.";
        }elseif ($user["status"] == 3){
            $response["success"] = false;
            $response["message"] = "This account has been deleted.";
        } else {
            $response["message"] = "Your account has been restricted due to some reason, please email us to reactive.";
        }
    } else {
        $response["success"] = false;
        $response["message"] = "Please enter Valid Email and Password.";
    }
} else {
    $response["success"] = false;
    $response["message"] = "Parameter are missing.";
}
echo json_encode($response);
?>