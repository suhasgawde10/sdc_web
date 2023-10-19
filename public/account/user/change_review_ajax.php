<?php
ob_start();
include "../controller/ManageUser.php";
$manage = new ManageUser();
include "../controller/validator.php";
$validate = new Validator();
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
$controller = new Controller();
$con = $controller->connect();


$error = false;
$errorMessage = "";
include("session_includes.php");

if($_POST){
    $review = mysqli_real_escape_string($con,$_POST['our_review']);
    $invitation_id = rand(100,10000);
    $insert = $manage->insertReview($id,$invitation_id,$review);
    if($insert){
        $token = $id.",".$invitation_id;
        $token_url = "http://sharedigitalcard.com/user/share-your-feedback.php?token=".$security->encryptWebservice($token);
        $whatsapp_token = $review."\n\n".$token_url;
        echo '<input type="text" id="copy_review" value="' . $token_url.'">';
        echo '<input type="hidden" name="whatsapp_review" value="' . $whatsapp_token.'">';
    }
}

?>