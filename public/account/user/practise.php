<?php
include "../controller/ManageUser.php";
$manage = new ManageUser();
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
$error = false;
$errorMessage = "";


if(isset($_POST['checkboxValue'])){
    $checkbox = $_POST['checkboxValue'];
    $section_id = 1;
    $website_status = 1;
    $result = $manage->updateSectionStatus($section_id, $website_status, $checkbox);
    if($result){
        echo true;
    }else{
        echo false;
    }
}

?>