<?php
error_reporting(E_ERROR | E_PARSE);
include 'whitelist.php';
include "controller/ManageUser.php";
$manage = new ManageUser();
include "controller/validator.php";
$validate = new Validator();
include "controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include 'sendMail/sendMail.php';
$error = false;
$errorMessage = "";
$controller = new Controller();
$con = $controller->connect();


$getAllUserformSactionStatus = $manage->getSectionStatusUserId();
if($getAllUserformSactionStatus != ""){
    while ($row = mysqli_fetch_array($getAllUserformSactionStatus)){
        $id = $row['user_id'];
        $section_id = 11;
        $digital_card = 1;
        $website = 1;
//        `user_id`, `section_id`, `website`, `digital_card`
        $insertData = array('user_id'=>$id,'section_id'=>$section_id,'website'=>$website,'digital_card'=>$digital_card);
        $insert = $manage->insert($manage->sectionStatusTable,$insertData);
    }
    echo "Success";
}
?>