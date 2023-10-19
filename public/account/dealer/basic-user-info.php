<?php
include '../whitelist.php';
ob_start();
include "../controller/ManageDealer.php";
$manage = new ManageDealer();
include "../controller/validator.php";
$validate = new Validator();
include '../sendMail/sendMail.php';
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
if (!isset($_SESSION['dealer_email'])) {
    header('location:../dealer-register.php');
}
require_once("functions.php");
$maxsize = 10485760;
$error = false;
$errorMessage = "";
$errorFile = false;
$errorMessageFile = "";
$emailError = false;
$emailErrorMessage = "";
$contactError = false;
$contactErrorMessage = "";
/*$randomEmail = rand(100, 10000);*/
$randomEmail = 1234;
/*$randomSMS = rand(100, 10000);*/
$randomSMS = 1234;
/*@session_start() ;
session_destroy() ;*/

$id = 0;
$maxsize = 2097152;
include("session_includes.php");



$imgUploadStatus = false;
$fileUploadStatus = false;
$profileUploadStatus = false;
$checkUploadStatus = false;

if ($id != 0) {
    $form_data = $manage->getSpecificDealerProfileByUserId($id);
    if ($form_data != null) {
        $id_proof = $form_data['id_proof'];
        $light_bill = $form_data['light_bill'];
        $img_name = $form_data['img_name'];
        $profilePath = "uploads/" . $session_email . "/profile/" . $form_data['img_name'];
        if($form_data['light_bill']!=""){
            $lightPath ="uploads/" . $session_email . "/light-bill/" . $form_data['light_bill'];
        }else{
            $lightPath = $form_data['light_bill'];
        }
        if($form_data['id_proof']!=""){
            $id_proofPath ='uploads/' . $session_email . '/id-proof/' . $form_data['id_proof'];
        }else{
            $id_proofPath =$form_data['id_proof'];
        }
        if($form_data['cancel_check']!=""){
            $cancelCheckPath ='uploads/' . $session_email . '/id-proof/' . $form_data['cancel_check'];
        }else{
            $cancelCheckPath =$form_data['cancel_check'];
        }

    }
}

/*This method used for update the Branch data*/

if (isset($_POST['btn_update'])) {
    if (isset($_POST['dob']) && $_POST['dob'] != "") {
        $validateDob = $validate->validDateChecker($_POST['dob'], "dd/mm/yyyy");
        if ($validateDob) {
            $dob = $_POST['dob'];
        }
    } else {
        $error = true;
        $errorMessage .= 'Please enter date of birth.<br>';
    }
    if (isset($_POST['txt_state']) && $_POST['txt_state'] != "") {
        $txt_state = $_POST['txt_state'];
    } else {
        $error = true;
        $errorMessage .= "Please enter state.<br>";
    }
    if (isset($_POST['txt_city']) && $_POST['txt_city'] != "") {
        $txt_city = $_POST['txt_city'];
    } else {
        $error = true;
        $errorMessage .= "Please enter city.<br>";
    }
    if (isset($_POST['txt_address']) && $_POST['txt_address'] != "") {
        $txt_address = $_POST['txt_address'];
    } else {
        $error = true;
        $errorMessage .= "Please enter address.<br>";
    }
    if (isset($_POST['txt_c_name']) && $_POST['txt_c_name'] != "") {
        $txt_c_name = $_POST['txt_c_name'];
    } else {
        $error = true;
        $errorMessage .= "Please enter company name.<br>";
    }
    if (isset($_POST['drp_type']) && $_POST['drp_type'] != "") {
        $drp_type = $_POST['drp_type'];
    } else {
        $error = true;
        $errorMessage .= "Please enter type.<br>";
    }
    if (isset($_POST['office_address']) && $_POST['office_address'] != "") {
        $office_address = $_POST['office_address'];
    } else {
        $error = true;
        $errorMessage .= "Please enter office address.<br>";
    }


    if (isset($_FILES['upload']) /* 4 means there is no file selected*/) {
        if($_FILES['upload']['error'][0] != 4){
            $imgUploadStatus = true;
            $directory_name = "uploads/" . $session_email . "/light-bill/";
            //$extension = array('.jpg', '.JPG', '.jpeg', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG','.pdf','.doc','.docx','.PDF','.DOC','.DOCX');
            $extension = array('.jpg', '.JPG', '.jpeg', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG');
            $maxsize = 2097152;
            $total = count($_FILES['upload']['name']);
            for ($i = 0; $i < $total; $i++) {
                $filename = $_FILES['upload']['name'][$i];
                $extensionStatus = $validate->validateFileExtension($filename, $extension);
                if (!$extensionStatus) {
                    $error = true;
                    $errorMessage .= "Please select valid file extension";
                }
            }
        }else{
            if ($form_data['light_bill']=="") {
                $error = true;
                $errorMessage .= 'Please upload file<br>';
            }
        }
    }
    if (isset($_FILES['upload-file']) /* 4 means there is no file selected*/) {
        if ($_FILES['upload-file']['error'][0] != 4) {

            $fileUploadStatus = true;
            $directory_file_name = "uploads/" . $session_email . "/id-proof/";
            //$extension = array('.jpg', '.JPG', '.jpeg', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG','.pdf','.doc','.docx','.PDF','.DOC','.DOCX');
            $extension = array('.jpg', '.JPG', '.jpeg', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG');
            $total = count($_FILES['upload-file']['name']);
            for ($i = 0; $i < $total; $i++) {
                $filename = $_FILES['upload-file']['name'][$i];
                $extensionStatus = $validate->validateFileExtension($filename, $extension);
                if (!$extensionStatus) {
                    $error = true;
                    $errorMessage .= "Please select valid file extension";
                }
            }
        }else{
            if ($form_data['id_proof']=="") {
                $error = true;
                $errorMessage .= 'Please upload file<br>';
            }
        }
    }
    if (isset($_FILES['upload-check']) /* 4 means there is no file selected*/) {
        if ($_FILES['upload-check']['error'][0] != 4) {

            $checkUploadStatus = true;
            $directory_file_name = "uploads/" . $session_email . "/id-proof/";
            //$extension = array('.jpg', '.JPG', '.jpeg', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG','.pdf','.doc','.docx','.PDF','.DOC','.DOCX');
            $extension = array('.jpg', '.JPG', '.jpeg', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG');
            $total = count($_FILES['upload-check']['name']);
            for ($i = 0; $i < $total; $i++) {
                $filename = $_FILES['upload-check']['name'][$i];
                $extensionStatus = $validate->validateFileExtension($filename, $extension);
                if (!$extensionStatus) {
                    $error = true;
                    $errorMessage .= "Please select valid file extension";
                }
            }
        }else{
            if ($form_data['cancel_check']=="") {
                $error = true;
                $errorMessage .= 'Please upload cancel check<br>';
            }
        }
    }

    

    if (!$error) {
        $digits = 4;
        $randomNum = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        $newimgname = "";
        $newfilename = "";
        $checkname = "";
        if ($imgUploadStatus) {
        /*    $filename = '/path/to/foo.txt';
            if (file_exists($filename)) {
                echo "The file $filename exists";
            } else {
                echo "The file $filename does not exist";
            }*/

            for ($i = 0; $i < $total; $i++) {
                $filearray = array();
                $tmpFilePath = $_FILES['upload']['tmp_name'][$i];
                $file_extension = substr($_FILES['upload']['name'][$i], (strrpos($_FILES['upload']['name'][$i], '.') + 1));
                $newimgname = $randomNum . '.' . $file_extension;
                $newimgname = str_replace([' ', '_'], '-', $newimgname);
                $newPath = $directory_name . $newimgname;

                if (($_FILES['upload']['size'][$i] <= $maxsize)) {
                    if(strpos($newimgname, "pdf") !== false){
                        $success = true;
                    }
                    else{
                        $success =  compressImage($tmpFilePath, $newPath, 90);
                    }
                    
                    if(!$success) {
                        if (!move_uploaded_file($tmpFilePath, $newPath)) {
                            $error = true;
                            $errorMessage .= "Failed to upload file";
                        }else{
                            if (file_exists($lightPath) && $form_data['light_bill'] !="") {
                                unlink($lightPath);
                            }
                        }
                    }
                }else{
                    $error = true;
                    $errorMessage .= "File Size should be less than 2mb.";

                }

            }
        }

       
        if ($fileUploadStatus) {

            for ($i = 0; $i < $total; $i++) {
                $filearray = array();
                $tmpFilePath1 = $_FILES['upload-file']['tmp_name'][$i];
                $file_extension1 = substr($_FILES['upload-file']['name'][$i], (strrpos($_FILES['upload-file']['name'][$i], '.') + 1));
                $newfilename = $randomNum . '.' . $file_extension1;
                $newfilename = str_replace([' ', '_'], '-', $newfilename);
                $newPath1 = $directory_file_name . $newfilename;
                // echo $newPath1;
                // die();
                if (($_FILES['upload-file']['size'][$i] <= $maxsize)) {
                    
                    if(strpos($newfilename, "pdf") !== false){
                        $success = true;
                    }
                    else{
                        $success =  compressImage($tmpFilePath1, $newPath1, 90);
                    }

                    if(!$success) {
                        if (!move_uploaded_file($tmpFilePath1, $newPath1)) {
                            $error = true;
                            $errorMessage .= "Failed to upload file";
                        }else{
                            if (file_exists($id_proofPath) && $form_data['id_proof']!="") {
                                unlink($id_proofPath);
                            }
                        }
                    }
                }else{
                    $error = true;
                    $errorMessage .= "File Size should be less than 2mb.";

                }
            }
        }

        if ($checkUploadStatus) {

            for ($i = 0; $i < $total; $i++) {
                $filearray = array();
                $tmpFilePath2 = $_FILES['upload-check']['tmp_name'][$i];
                $file_extension2 = substr($_FILES['upload-check']['name'][$i], (strrpos($_FILES['upload-check']['name'][$i], '.') + 1));
                $checkname = $randomNum . '.' . $file_extension2;
                $checkname = str_replace([' ', '_'], '-', $checkname);
                $newPath2 = $directory_file_name . $checkname;
                if (($_FILES['upload-check']['size'][$i] <= $maxsize)) {
                    if(strpos($checkname, "pdf") !== false){
                        $success = true;
                    }
                    else{
                        $success = compressImage($tmpFilePath2, $newPath2, 90);
                    }

                    
                    if(!$success) {
                        if (!move_uploaded_file($tmpFilePath2, $newPath2)) {
                            $error = true;
                            $errorMessage .= "Failed to upload file";
                        }else{
                            if (file_exists($cancelCheckPath) && $form_data['cancel_check']!="") {
                                unlink($cancelCheckPath);
                            }
                        }
                    }
                }else{
                    $error = true;
                    $errorMessage .= "File Size should be less than 2mb.";
                }
            }
        }
        $get_status = $manage->selectTheme();
        if ($get_status != null) {
            $approve_status = $get_status['approve_status'];
        }



       if(!$error){
           if(trim($newimgname) == ''){
               $newimgname =  $form_data['light_bill'];
           }
           if(trim($newfilename)==''){
               $newfilename = $form_data['id_proof'];
           }
           if (trim($checkname) =="") {
               $checkname = $form_data['cancel_check'];
           }

           if ($approve_status == "Approved") {
               $approve_status = "Approved";
               $status = $manage->updateDealerProfile($_POST['txt_name'], $_POST["gender"], $_POST['dob'], $_POST['txt_alt_contact'],
                   $_POST['txt_state'], $_POST['txt_city'], $_POST['txt_address'], $newfilename, $newimgname, $_POST['txt_c_name'],
                   $_POST['drp_type'], $_POST['txt_gstin'], $_POST['pan_no'], $_POST['txt_landline'], $_POST['office_address'],
                   $_POST['txt_website'], $_POST['b_email_id'], $_POST['txt_category'], $approve_status,$_POST['drp_user_type'],$checkname);
               if ($status) {
                   header('location:basic-user-info.php');
               } else {
                   $error = true;
                   $errorMessage .= "Issue while updating details please try again.";
               }
           } elseif ($approve_status == "Rejected") {
               $approve_status = "Pending";
               $status = $manage->updateDealerProfile($_POST['txt_name'], $_POST["gender"], $_POST['dob'],
                   $_POST['txt_alt_contact'], $_POST['txt_state'], $_POST['txt_city'], $_POST['txt_address'],
                   $newfilename, $newimgname, $_POST['txt_c_name'], $_POST['drp_type'], $_POST['txt_gstin'],
                   $_POST['pan_no'], $_POST['txt_landline'], $_POST['office_address'], $_POST['txt_website'], $_POST['b_email_id'],
                   $_POST['txt_category'], $approve_status,$_POST['drp_user_type'],$checkname);
               if ($status) {
                   $get_status = $manage->selectTheme();
                   if ($get_status != null) {
                       $approve_status = $get_status['approve_status'];
                   }
                   if ($approve_status == 'Pending' or $approve_status == '') {
                       $toName = "Kubic";
                       $toEmail = $global_email;
                       $subject = "Existing Dealer Request";
                       $message = "Existing dealer " . $_SESSION['dealer_name'] . " request has been received\nContact No :".$_SESSION['dealer_contact']."\nEmail : ".$_SESSION['dealer_email'];
                       //   $sendEmail = $manage->sendMail($toName, $toEmail, $subject, $message);
                       //  $sms_message = $manage->sendSMS($global_contact, $message);
                   }
                   header('location:basic-user-info.php');
               } else {
                   $error = true;
                   $errorMessage .= "Issue while updating details please try again.";
               }
           } elseif ($approve_status == "Pending") {
               $approve_status = "Pending";
               $status = $manage->updateDealerProfile($_POST['txt_name'], $_POST["gender"], $_POST['dob'], $_POST['txt_alt_contact'],
                   $_POST['txt_state'], $_POST['txt_city'], $_POST['txt_address'], $newfilename, $newimgname, $_POST['txt_c_name'], $_POST['drp_type'], $_POST['txt_gstin'], $_POST['pan_no'], $_POST['txt_landline'], $_POST['office_address'], $_POST['txt_website'], $_POST['b_email_id'], $_POST['txt_category'], $approve_status,$_POST['drp_user_type'],$checkname);
               if ($status) {
                   header('location:basic-user-info.php');
               } else {
                   $error = true;
                   $errorMessage .= "Issue while updating details please try again.";
               }
           } elseif ($approve_status == "") {
               $approve_status = "Pending";
               $status = $manage->updateDealerProfile($_POST['txt_name'], $_POST["gender"],
                   $_POST['dob'], $_POST['txt_alt_contact'], $_POST['txt_state'], $_POST['txt_city'],
                   $_POST['txt_address'], $newfilename, $newimgname, $_POST['txt_c_name'],
                   $_POST['drp_type'], $_POST['txt_gstin'], $_POST['pan_no'], $_POST['txt_landline'],
                   $_POST['office_address'], $_POST['txt_website'], $_POST['b_email_id'], $_POST['txt_category'],
                   $approve_status,$_POST['drp_user_type'],$checkname);
               if ($status) {
                   $get_status = $manage->selectTheme();
                   if ($get_status != null) {
                       $approve_status = $get_status['approve_status'];
                   }
                   if ($approve_status == 'Pending' or $approve_status == '') {
                       $toName = "Kubic";
                       $toEmail = $global_email;
                       $subject = "New Dealer Request";
                       $message = "New dealer " . $_SESSION['dealer_name'] . " request has been received\nContact No :".$_SESSION['dealer_contact']."\nEmail : ".$_SESSION['dealer_email'];
                       //  $sendEmail = $manage->sendMail($toName, $toEmail, $subject, $message);
                       // $adminContact = $global_contact;
                       //   $sms_message = $manage->sendSMS($adminContact, $message);
                   }
                   header('location:basic-user-info.php');
               } else {
                   $error = true;
                   $errorMessage .= "Issue while updating details please try again.";
               }
           }
       }


    }
}

if ($id != 0) {
    if ($form_data != null) {
        $name = $form_data['name'];
        $gender = $form_data['gender'];
        $date_of_birth = $form_data['date_of_birth'];
        $alter_contact_no = $form_data['altr_contact_no'];
        $state = $form_data['state'];
        $city = $form_data['city'];
        $id_proof = $form_data['id_proof'];
        $light_bill = $form_data['light_bill'];
        $c_name = $form_data['c_name'];
        $c_registered = $form_data['c_registered'];
        $gstin_no = $form_data['gstin_no'];
        $pan_no = $form_data['pan_no'];
        $address = $form_data['address'];
        $landline_no = $form_data['landline_no'];
        $office_address = $form_data['office_address'];
        $website = $form_data['website'];
        $b_email_id = $form_data['b_email_id'];
        $img_name = $form_data['img_name'];
        $category = $form_data['category'];
        $user_type = $form_data['user_type'];
        $profilePath = "uploads/" . $session_email . "/profile/" . $form_data['img_name'];
        if($form_data['light_bill']!=""){
            $lightPath ="uploads/" . $session_email . "/light-bill/" . $form_data['light_bill'];
        }else{
            $lightPath = $form_data['light_bill'];
        }
        if($form_data['id_proof']!=""){
            $id_proofPath ='uploads/' . $session_email . '/id-proof/' . $form_data['id_proof'];
        }else{
            $id_proofPath =$form_data['id_proof'];
        }

    }
}

if (isset($_POST['upload_photo'])) {
    if (isset($_FILES['upload']) && $_FILES['upload']['error'][0] != 4 /* 4 means there is no file selected*/) {
        $profileUploadStatus = true;
        $directory_name = "uploads/" . $session_email . "/profile/";
        $extension = array('.jpg', 'JPG', '.JPG', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG');
        $total = count($_FILES['upload']['name']);
        for ($i = 0; $i < $total; $i++) {
            $filename = $_FILES['upload']['name'][$i];
            $extensionStatus = $validate->validateFileExtension($filename, $extension);
            if (!$extensionStatus) {
                $errorFile = true;
                $errorMessageFile = "Please select valid file extension";
            }
            if (($_FILES['upload']['size'][$i] >= $maxsize)) {
                $errorFile = true;
                $errorMessageFile = 'File too large. File must be less than 2 megabytes.';
            }
        }
    } else {
        $errorFile = true;
        $errorMessageFile = "Please select file";
    }
    if (!$errorFile) {
        $digits = 4;
        $randomNum = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        $newfilename = "";
        if ($profileUploadStatus) {
            if (file_exists($form_data['img_name'])) {
                unlink('uploads/' . $session_email . '/profile/' . $form_data['img_name'] . '');
                for ($i = 0; $i < $total; $i++) {
                    $filearray = array();
                    $tmpFilePath = $_FILES['upload']['tmp_name'][$i];
                    $file_original_name = substr($_FILES['upload']['name'][$i], 0, strrpos($_FILES['upload']['name'][$i], '.'));
                    $file_extension = substr($_FILES['upload']['name'][$i], (strrpos($_FILES['upload']['name'][$i], '.') + 1));
                    $newfilename = $file_original_name . "$" . $randomNum . '.' . $file_extension;
                    $removeSpace = str_replace(array(' ', '_'), array('-', '-'), $newfilename);
                    $newFile = strtolower($removeSpace);
                    $newPath = $directory_name . $newFile;
                    if (!move_uploaded_file($tmpFilePath, $newPath)) {
                        $errorFile = true;
                        $errorMessageFile = "Failed to upload file";
                    } else {
                        $filename = $newPath;
                        $resizedFilename = $newPath;
                        $imgData = resize_image($filename, 250, 250);
                        imagepng($imgData, $resizedFilename);
                    }
                }
            } else {
                for ($i = 0; $i < $total; $i++) {
                    $filearray = array();
                    $tmpFilePath = $_FILES['upload']['tmp_name'][$i];
                    $file_original_name = substr($_FILES['upload']['name'][$i], 0, strrpos($_FILES['upload']['name'][$i], '.'));
                    $file_extension = substr($_FILES['upload']['name'][$i], (strrpos($_FILES['upload']['name'][$i], '.') + 1));
                    $newfilename = $file_original_name . "$" . $randomNum . '.' . $file_extension;
                    $removeSpace = str_replace(array(' ', '_'), array('-', '-'), $newfilename);
                    $newFile = strtolower($removeSpace);
                    $newPath = $directory_name . $newFile;
                    if (!move_uploaded_file($tmpFilePath, $newPath)) {
                        $errorFile = true;
                        $errorMessageFile = "Failed to upload file";
                    } else {
                        $filename = $newPath;
                        $resizedFilename = $newPath;
                        $imgData = resize_image($filename, 250, 250);
                        imagepng($imgData, $resizedFilename);
                    }
                }
            }
        }
        $update_photo = $manage->updateProfilePhoto($newFile);
        if ($update_photo) {

            header('location:basic-user-info.php');
        }

    }
}

function resize_image($file, $w, $h, $crop = false)
{
    list($width, $height) = getimagesize($file);
    $r = $width / $height;
    if ($crop) {
        if ($width > $height) {
            $width = ceil($width - ($width * abs($r - $w / $h)));
        } else {
            $height = ceil($height - ($height * abs($r - $w / $h)));
        }
        $newwidth = $w;
        $newheight = $h;
    } else {
        if ($w / $h > $r) {
            $newwidth = $h * $r;
            $newheight = $h;
        } else {
            $newheight = $w / $r;
            $newwidth = $w;
        }
    }

    //Get file extension
    $exploding = explode(".", $file);
    $ext = end($exploding);

    switch ($ext) {
        case "png":
            $src = imagecreatefrompng($file);
            break;
        case "jpg":
            $src = imagecreatefromjpeg($file);
            break;
        case "gif":
            $src = imagecreatefromgif($file);
            break;
        default:
            $src = imagecreatefromjpeg($file);
            break;
    }

    $dst = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

    return $dst;
}

$display_message = $manage->displayDealerProfile();
if ($display_message != null) {
    $message_status = $display_message['message_status'];
    $dealer_status = $display_message['status'];
    $pay_status = $display_message['pay_status'];
    $deal_code = $display_message['dealer_code'];
}
if($pay_status == 0 && $dealer_status==1){
    header('location:payment_deposit.php');
}

?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Basic Information</title>
    <?php include "assets/common-includes/header_includes.php" ?>
    <link rel="stylesheet" href="assets/croppie/croppie.css">

</head>
<body>
<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>
<section class="content">
    <div class="clearfix">
        <?php
        $get_status = $manage->selectTheme();
        if ($get_status != null) {
            $approve_status = $get_status['approve_status'];
            $rejected_message = $get_status['rejected_message'];
        }
        if ($approve_status == "Pending" && $message_status == 1) {
            ?>
            <div class="alert alert-success">
                Your request is in under verification. we might take 2-3hrs for it. you will get the notification in
                your registered mobile number and mail id
            </div>
        <?php
        }elseif ($approve_status == "Pending" && $message_status == 0) {
            ?>
            <div class="alert alert-danger">
                Your account has been created successfully, Please kindly complete your profile with all Mandatory field in order to submit your request as the dealership.
            </div>
        <?php
        } elseif ($approve_status == "Rejected") {
            ?>
            <pre class="alert alert-warning"><?php echo $rejected_message; ?></pre>
        <?php
        }
        ?>
        <div class="col-lg-3 col-md-5 col-sm-12 col-xs-12">
            <div class="row margin_div1">
                <div class="card">
                    <div class="body card_padding">
                        <form id="basic_user_profile" method="POST" action="" enctype="multipart/form-data">
                            <ul class="profile-left-ul">
                                <li>
                                    <div class="form-group form-float text-align-profile" style="position: relative">
                                        <div id="uploaded_image">
                                        <!----><?php /*echo '<img src="" style="width: 15%;border-radius: 50%;" /><br />'; */ ?>
                                        <img
                                            src="<?php if (!file_exists($profilePath) && $gender == "Male" or $form_data['img_name'] == "") {
                                                echo "uploads/male_user.png";
                                            } elseif (!file_exists($profilePath) && $gender == "Female" or $form_data['img_name'] == "") {
                                                echo "uploads/female_user.png";
                                            } else {
                                                echo $profilePath;
                                            } ?>" style="width: 50%;border-radius: 50%;">
                                        </div>
                                        <div class="upload_camera">
                                            <input type="file" name="upload_image" id="upload_image" style="display: none"/>
                                            <a id="OpenImgUpload">
                                                <img
                                                    src="assets/images/camera.png">
                                            </a>
                                        </div>

                                    </div>
                                </li>
                                <li>
                                    <div class="width-prf">
                                        <label class="form-label"><i class="fas fa-user"></i></label>

                                        <div class="form-group form-group-left form-float">
                                            <div class="">
                                                <lable name=label_txt_name"
                                                       class="form-control"> <?php if (isset($name)) echo $name; ?></lable>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="width-prf">
                                        <label><i class="fas fa-restroom"></i></label>

                                        <div class="form-group form-group-left form-float">
                                            <div class="">
                                                <lable name=label_txt_gender"
                                                       class="form-control"> <?php if (isset($gender)) echo $gender; ?></lable>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li>
                                    <div class="width-prf">
                                        <label class="form-label"><i class="fa fa-phone"></i></label>

                                        <div class="form-group form-group-left form-float">
                                            <div class="">
                                                <lable name=label_txt_name"
                                                       class="form-control"> <?php echo $session_contact_no; ?>
                                                </lable>
                                            </div>
                                        </div>
                                        <!--<a title="Edit Contact" class="add-icon-color fas fa-pencil-alt"
                                           href="basic-user-info.php?change_contact=<?php /*echo $session_contact_no; */ ?>"></a>-->
                                </li>
                                <li>
                                    <div class="width-prf">
                                        <label class="form-label"><i class="fas fa-envelope"></i></label>

                                        <div class="form-group form-group-left form-float">
                                            <div class="">
                                                <lable name=label_txt_email"
                                                       class="form-control"><?php echo $session_email; ?>
                                                </lable>
                                            </div>
                                        </div>
                                        <!--<a title="Edit Email" class="add-icon-color fas fa-pencil-alt"
                                           href="basic-user-info.php?change_email=<?php /*echo $session_email; */ ?>"></a>-->
                                    </div>
                                </li>
                            </ul>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9 col-md-7 col-sm-12 col-xs-12 padding_both">
            <div class="row margin_div_web">
                <div class="card">
                    <div class="body">
                        <?php if ($error) {
                            ?>
                            <div class="alert alert-danger">
                                <?php if (isset($errorMessage)) echo $errorMessage; ?>
                            </div>
                        <?php
                        } else if (!$error && $errorMessage != "") {
                            ?>
                            <div class="alert alert-success">
                                <?php if (isset($errorMessage)) echo $errorMessage; ?>
                            </div>
                        <?php
                        }
                        ?>

                        <form method="POST" action="" enctype="multipart/form-data">
                            <fieldset>
                                <legend class="legend_font_size" align="left">Basic Information</legend>
                                <ul class="profile-ul">
                                    <li>
                                        <div class="width-prf">
                                            <label class="form-label">Name</label> <span class="required_field">*</span>

                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input name="txt_name" class="form-control"
                                                           value="<?php if (isset($name)) echo $name; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="width-prf">
                                            <label>Gender</label> <span class="required_field">*</span>

                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <select id="gender" name="gender" class="form-control gender_li"
                                                            required="required">
                                                        <option name="">Select an option</option>
                                                        <option <?php if ($gender == 'Male') echo 'selected' ?>
                                                            name="male">Male
                                                        </option>
                                                        <option <?php if ($gender == 'Female') echo 'selected' ?>
                                                            name="female">Female
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-group">
                                            <label class="control-label">Date of birth</label> <span
                                                class="required_field">*</span>

                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="date" class="form-control" id="dob" name="dob"
                                                           value="<?php if(isset($_POST['dob'])){ echo $_POST['dob']; }elseif(isset($date_of_birth)) echo $date_of_birth; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="width-prf">
                                            <label class="form-label">Alternate Contact (Optional)</label>

                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                                    <input type="text" name="txt_alt_contact" class="form-control"
                                                           onkeypress="return isNumberKey(event)"
                                                           placeholder="Alternate Contact"
                                                           value="<?php if(isset($_POST['txt_alt_contact'])){ echo $_POST['txt_alt_contact']; }elseif(isset($alter_contact_no)) echo $alter_contact_no; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-group">
                                            <label class="control-label">State</label> <span
                                                class="required_field">*</span>

                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" class="form-control" id="dob" name="txt_state"
                                                           placeholder="Enter State"
                                                           value="<?php if(isset($_POST['txt_state'])){ echo $_POST['txt_state']; }elseif(isset($state)) echo $state; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-group">
                                            <label class="control-label">City</label> <span
                                                class="required_field">*</span>

                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" class="form-control" id="dob" name="txt_city"
                                                           value="<?php if(isset($_POST['txt_city'])){ echo $_POST['txt_city']; }elseif(isset($city)) echo $city; ?>"
                                                           placeholder="Enter State">
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="width-prf">
                                            <label>Aadhar card or Pan card</label> <span class="required_field">*</span>
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="file" id="upload-file" name="upload-file[]"
                                                           accept=".png, .jpg, .jpeg,.JPG,.PNG,.JPEG,.pdf,.doc,.docx">
                                                    <?php
                                                    $aadharFilePath = "uploads/$session_email/id-proof/" . $form_data['id_proof'];
                                                    //echo $aadharFilePath;
                                                    if (file_exists($aadharFilePath) && $form_data['id_proof'] != "") echo '<a href="' . $aadharFilePath . '" target="_blank">Preview</a>'; ?>

                                                </div>
                                                <span style='color: red'>Note: Allow only images (Max 2 MB)</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="width-prf">
                                            <label class="form-label">Address Proof</label> <span class="required_field">*</span>
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="file" id="upload" name="upload[]"
                                                           accept=".png, .jpg, .jpeg,.JPG,.PNG,.JPEG,.pdf,.doc,.docx">
                                                    <?php
                                                    $lightFilePath = "uploads/$session_email/light-bill/" . $form_data['light_bill'];
                                                    if (file_exists($lightFilePath ) && $form_data['light_bill'] != "") echo '<a href="' . $lightFilePath  . '" target="_blank">Preview</a>';
                                                    ?>
                                                </div>
                                                <span style='color: red'>Note: Allow only images (Max 2 MB)</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="width-prf">
                                            <label>Cancelled Bank Cheque</label> <span class="required_field">*</span>
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="file" id="upload-check" name="upload-check[]"
                                                           accept=".png, .jpg, .jpeg,.JPG,.PNG,.JPEG,.pdf,.doc,.docx">
                                                    <?php
                                                    $checkFilePath = "uploads/$session_email/id-proof/" . $form_data['cancel_check'];
                                                    if (file_exists($checkFilePath) && $form_data['cancel_check'] != "") echo '<a href="' . $checkFilePath . '" target="_blank">Preview</a>'; ?>

                                                </div>
                                                <span style='color: red'>Note: Allow only images (Max 2 MB)</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="user_address">
                                        <div class="width-prf">
                                            <label class="form-label">Address</label> <span
                                                class="required_field">*</span>

                                            <div class="form-group form-float">
                                                <div class="form-line">
                                    <textarea name="txt_address" class="form-control"
                                              placeholder="Address"><?php if(isset($_POST['txt_address'])){ echo $_POST['txt_address']; }elseif(isset($address)) echo $address; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </fieldset>
                            <div class="altenet-div">
                                <fieldset>
                                    <legend class="legend_font_size" align="left">Company Info</legend>
                                    <ul class="company_profile-ul">
                                        <li>
                                            <div class="width-prf">
                                                <label>User Type</label> <span class="required_field">*</span>

                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <select id="gender" name="drp_user_type"
                                                                class="form-control gender_li" onchange="userTypeFun(this.value)">
                                                            <option <?php if ($user_type == 'Company') echo 'selected' ?>>Company
                                                            </option>
                                                            <option <?php if ($user_type == 'Individual') echo 'selected' ?>>Individual
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <span id="append_here"></span>
                                        <li class="change_pos">
                                            <div class="width-prf">
                                                <label class="form-label">Company Name</label> <span
                                                    class="required_field">*</span>

                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                                        <input type="text" name="txt_c_name" class="form-control"
                                                               placeholder="Enter Company Name"
                                                               value="<?php if(isset($_POST['txt_c_name'])){ echo $_POST['txt_c_name']; }elseif(isset($c_name)) echo $c_name; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="width-prf">
                                                <label>Type</label> <span class="required_field">*</span>

                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <select id="gender" name="drp_type"
                                                                class="form-control gender_li">
                                                            <option name="">Select Type</option>
                                                            <option <?php if ($c_registered == 'Registered') echo 'selected' ?>
                                                                name="registered">Registered
                                                            </option>
                                                            <option <?php if ($c_registered == 'Unregistered') echo 'selected' ?>
                                                                name="Unregistered">Unregistered
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="width-prf">
                                                <label class="form-label">Landline No</label> (Optional)

                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input name="txt_landline" class="form-control"
                                                               placeholder="Landline Number"
                                                               value="<?php if(isset($_POST['txt_landline'])){ echo $_POST['txt_landline']; }elseif(isset($landline_no)) echo $landline_no; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="width-prf">
                                                <label class="form-label">GSTIN No</label> (Optional)

                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                                        <input type="text" name="txt_gstin" class="form-control"
                                                               placeholder="GSTIN number"
                                                               value="<?php if(isset($_POST['txt_gstin'])){ echo $_POST['txt_gstin']; }elseif(isset($gstin_no)) echo $gstin_no; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="width-prf">
                                                <label class="form-label">PAN NO</label> (Optional)

                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input name="pan_no" class="form-control"
                                                               placeholder="PAN number"
                                                               value="<?php if(isset($_POST['pan_no'])){ echo $_POST['pan_no']; }elseif(isset($pan_no)) echo $pan_no; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="change_pos">
                                            <div class="width-prf">
                                                <label class="form-label">Office Address</label> <span
                                                    class="required_field">*</span>

                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                                        <input name="office_address" class="form-control"
                                                               placeholder="Office address"
                                                               value="<?php if(isset($_POST['office_address'])){ echo $_POST['office_address']; }elseif(isset($office_address)) echo $office_address; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="width-prf">
                                                <label class="form-label">Website</label> (Optional)

                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input name="txt_website" type="url" class="form-control"
                                                               placeholder="Enter Website"
                                                               value="<?php if(isset($_POST['txt_website'])){ echo $_POST['txt_website']; }elseif(isset($website)) echo $website; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="change_pos">
                                            <div class="width-prf">
                                                <label class="form-label">Business Email Id</label> (Optional)

                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                                        <input name="b_email_id" class="form-control"
                                                               placeholder="Business email id"
                                                               value="<?php if(isset($_POST['b_email_id'])){ echo $_POST['b_email_id']; }elseif(isset($b_email_id)) echo $b_email_id; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="width-prf">
                                                <label class="form-label">Category</label> (Optional)

                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                                        <input name="txt_category" class="form-control"
                                                               placeholder="Category"
                                                               value="<?php if(isset($_POST['txt_category'])){ echo $_POST['txt_category']; }elseif(isset($category)) echo $category; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </fieldset>
                            </div>
                            <?php
                            if ($dealer_status==0) {
                                ?>
                                <div class="btn-up-div">
                                    <div class="form-group form_inline">
                                        <div class="example">

                                            <button name="btn_update" type="submit"
                                                    class="btn btn-primary waves-effect">Update
                                            </button>
                                        </div>
                                        &nbsp;&nbsp;
                                        <div>
                                            <input type="reset" class="btn btn-default" value="reset">
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include "assets/common-includes/footer_includes.php" ?>
<script src="assets/croppie/croppie.js"></script>
<!--<script type="text/javascript" src="upload.js"></script>-->

<div id="uploadimageModal" class="modal" role="dialog">
    <div class="modal-dialog dialog_width">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Upload & Crop Image</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div id="image_demo" style="width:350px; margin-top:30px"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success crop_image">Upload Image</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){

        $image_crop = $('#image_demo').croppie({
            enableExif: true,
            viewport: {
                width:250,
                height:250,
                type:'circle' //circle
            },
            boundary:{
                width:300,
                height:300
            }
        });

        $('#upload_image').on('change', function(){
            var reader = new FileReader();
            reader.onload = function (event) {
                $image_crop.croppie('bind', {
                    url: event.target.result
                }).then(function(){
                    console.log('jQuery bind complete');
                });
            }
            reader.readAsDataURL(this.files[0]);
            $('#uploadimageModal').modal('show');
        });

        $('.crop_image').click(function(event){
            $image_crop.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function(response){
                $.ajax({
                    url:"upload.php",
                    type: "POST",
                    data:{"image": response},
                    success:function(data)
                    {
                        $('#uploadimageModal').modal('hide');
                        $('#uploaded_image').html(data);
                    }
                });
            })
        });

    });
</script>
<script>


    function userTypeFun(value){
        if(value == 'Individual'){
            $('input[name=txt_c_name]').val('<?php if(isset($name)) echo $name ?>');
            $('input[name=b_email_id]').val('<?php echo $session_email; ?>');
            $('input[name=office_address]').val($('textarea[name=txt_address').val());
            $('ul.company_profile-ul').find('li.change_pos').prependTo('ul.company_profile-ul #append_here');
        }else{
            $('input[name=txt_c_name]').val('');
        }

    }
</script>
<script>
    $('#OpenImgUpload').click(function(){ $('#upload_image').trigger('click'); });
</script>

<!--<script type="text/javascript">
    document.getElementById("b3").onclick = function () {
        swal("Good job!", "You clicked the button!", "success");
    };
</script>-->


</body>
</html>