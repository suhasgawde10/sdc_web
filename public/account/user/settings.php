<?php

ob_start();

include "../controller/ManageUser.php";
$manage = new ManageUser();
include "../controller/validator.php";
$validate = new Validator();
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include '../sendMail/sendMail.php';
$controller = new Controller();
$con = $controller->connect();


$alreadySaved = false;
$alreadySavedBank = false;
$section_id = 7;

if (isset($_GET['token'])  && isset($_GET['type']) && $_GET['type'] == "android") {
    $android_url = "token=" . $_GET['token'] . "&type=" . $_GET['type'];
    $token = $security->decryptWebservice($_GET['token']);
    $seperate_token = explode('+',$token);
    $validateUserId = $manage->validAPIKEYId($seperate_token[0],$seperate_token[1]);
    if ($validateUserId) {
        if(!isset($_SESSION['id']) && !isset($_SESSION['email'])) {
            $userSpecificResult = $manage->getUserProfile($seperate_token[0]);
            if ($userSpecificResult != null) {
                $android_name = $userSpecificResult["name"];
                $android_email = $userSpecificResult["email"];
                $android_custom_url = $userSpecificResult["custom_url"];
                $android_contact = $userSpecificResult['contact_no'];
                $android_type = $userSpecificResult['type'];
            }
            $_SESSION['type'] = $android_type;
            $_SESSION['email'] = $android_email;
            $_SESSION['name'] = $android_name;
            $_SESSION['contact'] = $android_contact;
            $_SESSION['custom_url'] = $android_custom_url;
            $_SESSION['id'] = $security->encrypt($seperate_token[0]);
        }
    } else {
        header('location:404-not-found.php?'.$android_url);
    }
} elseif (!isset($_SESSION['email'])) {
    header('location:../login.php');
} else {
    $android_url = "";
}

$error = false;
$errorMessage = "";

$error1 = false;
$errorMessage1 = "";
$error2 = false;
$errorMessage2 = "";

$id = 0;
include("session_includes.php");

include "validate-page.php";

if(isset($_SESSION['dealer_login_type'])){
    $by = "by dealer.";
}else{
    $by = "by user.";
}
$get_bank_data = $manage->countService($id, $section_id);
if ($get_bank_data) {
    $alreadySavedBank = true;
    $display_bank_result = $manage->getServiceStatus($id, $section_id);
    /*$array = explode(",",$statusOnOFF);*/
}
if(isset($_POST['btn_update_enquiry_email'])){

     if (isset($_POST['enquiry_email']) && $_POST['enquiry_email'] != "") {
         if (!filter_var($_POST['enquiry_email'], FILTER_VALIDATE_EMAIL)) {
             $error = true;
             $errorMessage .= "Invalid email format.<br>";
         }
         $enquiry_email = mysqli_real_escape_string($con, trim($_POST['enquiry_email']));
     } else {
         $error = true;
         $errorMessage .= "Please enter your email.<br>";
     }

    if(!$error){
        $update = $manage->updateEnquiryEmail($enquiry_email);
        if ($update) {
             $action = "Updated";
            $remark = "You have Updated Enquiry Email " .$by;
            $page_name = "settings";
             $insertLog = $manage->insertUserLogData($page_name,$action,$remark);
             $get_user = $manage->getSpecificUserProfile();
            $error = false;
            $errorMessage = "Enquiry Email has been changed successfully!";
        } else {
            $error = true;
            $errorMessage = "Issue while updating please try after some time";
        }
    }


}
if (isset($_POST['update_bank'])) {
    if($display_bank_result['digital_card'] == 1){
        $digital_card_status = 0;
    }else{
        $digital_card_status = 1;
    }
    $result = $manage->updateSectionStatus($section_id, $digital_card_status, $digital_card_status);
    if ($result) {
        $get_bank_data = $manage->countService($id, $section_id);
        if ($get_bank_data) {
            $alreadySavedBank = true;
            $display_bank_result = $manage->getServiceStatus($id, $section_id);
            /*$array = explode(",",$statusOnOFF);*/
        }
        $action = "Updated";
        if($digital_card_status == 0){
            $remark = "Bank Details has been hide ".$by;
        }else{
            $remark = "Bank Details has been unhide ".$by;
        }
        $page_name = "settings";
        $insertLog = $manage->insertUserLogData($page_name,$action,$remark);
        $error = false;
        $errorMessage = "Bank status updated successfully!";
    }else{
        $error = true;
        $errorMessage = "Issue while updating please try after some time";
    }
}

$get_user = $manage->getSpecificUserProfile();

if(isset($_POST['btn_online_search'])){

    if($get_user['online_search'] == 1){
        $online_status = 0;
    }else{
        $online_status = 1;
    }
    $update = $manage->updateUserOnlineSearch($online_status);
    if($update){
        $action = "Updated";
        if($online_status == 0){
            $remark = "You have Hide business in online ".$by;
        }else{
            $remark = "You have Promoted business in online " .$by;
        }
        $page_name = "settings";
        $insertLog = $manage->insertUserLogData($page_name,$action,$remark);
        $get_user = $manage->getSpecificUserProfile();
        $error = false;
        $errorMessage = "Online search status updated successfully!";
    }else{
        $error = true;
        $errorMessage = "Issue while updating please try after some time";
    }
}
$show_deactivate_modal = false;
$show_delete_modal = false;
if(isset($_POST['btn_deactivate'])){
    $further = mysqli_real_escape_string($con,$_POST['txt_further']);
    $txt_reason = mysqli_real_escape_string($con,$_POST['txt_reason']);
    $txt_pass = $security->encrypt($_POST['txt_pass'])."8523";
    $validPassword = $manage->validUserPassword($txt_pass);
    if($validPassword){
        $insert = $manage->deactivateUserAccount($txt_reason,$further,"deactivated",2);
        if($insert){
            $page_name = "settings";
            $action = "Updated";
            $remark = "You have deactivated your account ".$by;
            $insertLog = $manage->insertUserLogData($page_name,$action,$remark);
            if($android_url !=''){
                header('location:activity://logout');
            }else{
                header('location:../sign-out-all.php');
            }
        }
    }else{
        $show_deactivate_modal = true;
        $error1 = true;
        $errorMessage1 = "The password you have entered is incorrect.";
    }

}
if(isset($_POST['btn_delete'])){
    $further = mysqli_real_escape_string($con,$_POST['txt_further']);
    $txt_reason = mysqli_real_escape_string($con,$_POST['txt_reason']);
$txt_pass = $security->encrypt($_POST['txt_pass'])."8523";
$validPassword = $manage->validUserPassword($txt_pass);
if($validPassword) {
    $insert = $manage->deactivateUserAccount($txt_reason, $further, "Deleted", 3);
    if ($insert) {
        $page_name = "settings";
        $action = "Deleted";
        $remark = "You have deleted your account ".$by;
        $insertLog = $manage->insertUserLogData($page_name,$action,$remark);
        $oldname = 'uploads/' . $session_email . '';
        $newname = 'uploads/' . "deleted_" . $session_email;
        rename($oldname, $newname);
        if($android_url !=''){
            header('location:activity://logout');
        }else{
            header('location:../sign-out-all.php');
        }
    }
}else{
    $show_delete_modal = true;
    $error2 = true;
    $errorMessage2 = "The password you have entered is incorrect.";
}
}


if (isset($_POST['btn_update_access'])) {
    $get_user_details = $manage->selectTheme();
    if ($get_user_details['dealer_access'] == "1") {
        $status = 0;
    } else {
        $status = 1;
    }


    $update = $manage->mu_updateDealerAccess($status);
    if ($update) {
        /* $action = "Updated";
         if($online_status == 0){
             $remark = "You have Hide business in online ".$by;
         }else{
             $remark = "You have Promoted business in online " .$by;
         }
         $page_name = "settings";
         $insertLog = $manage->insertUserLogData($page_name,$action,$remark);
         $get_user = $manage->getSpecificUserProfile();*/
        $error = false;
        $errorMessage = "Dealer Access has been updated successfully!";

    } else {
        $error = true;
        $errorMessage = "Issue while updating please try after some time";
    }

}
if (isset($_POST['btn_recive_service'])) {

    if (isset($_POST['drp_service']) && $_POST['drp_service'] == "email") {
        $status = "email";
    }elseif (isset($_POST['drp_service']) && $_POST['drp_service'] == "sms") {
        $status = "sms";
    } else {
        $status = "both";
    }


    $update = $manage->mu_updateUserReciever($status);
    if ($update) {
        /* $action = "Updated";
         if($online_status == 0){
             $remark = "You have Hide business in online ".$by;
         }else{
             $remark = "You have Promoted business in online " .$by;
         }
         $page_name = "settings";
         $insertLog = $manage->insertUserLogData($page_name,$action,$remark);
         $get_user = $manage->getSpecificUserProfile();*/
        $error = false;
        $errorMessage = "Status has been changed successfully!";

    } else {
        $error = true;
        $errorMessage = "Issue while updating please try after some time";
    }

}


?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php include "assets/common-includes/total_count.php" ?>
    <title>Settings</title>
    <?php include "assets/common-includes/header_includes.php" ?>
    <style>
        hr {
            margin: 20px;
            border: 1px solid #ffffff00;
            border-top: 1px solid #26c1d9
        }
        [type="radio"]:not(:checked), [type="radio"]:checked {
            position: unset;
            left: -9999px;
            opacity: 1;
        }
        td, th {
            padding: 10px;
            vertical-align: top;
        }
    </style>
</head>
<body>
<?php
if (!isset($_GET['token']) && (!isset($_GET['type']))) {
?>
<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>

<section class="content">
    <?php
    if (isset($_SESSION['create_user_status']) && $_SESSION['create_user_status'] == true) {
        include "assets/common-includes/session_button_includes.php";
    }
    ?>
    <?php
    }elseif (isset($_GET['token']) && (isset($_GET['type']) && $_GET['type'] == "android")) {
    ?>
    <section class="androidSection">
        <?php
        function like_match($pattern, $subject)
        {
            $pattern = str_replace('%', '.*', preg_quote($pattern, '/'));
            return (bool)preg_match("/^{$pattern}$/i", $subject);
        }
}
        ?>
        <div class="clearfix padding_bottom_46">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding_zero padding_zero_both">
                    <div class="card">
                        <div class="header">
                            <div class="row cust-row">
                                <div class="col-lg-7"><h2>
                                        Manage Settings
                                    </h2></div>
                            </div>
                        </div>
                        <div class="body" style="overflow: auto;">

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
                            if (like_match('%dealer%', $referral_by) == 1) {
                                ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <form method="POST" action="">
                                            <div class="col-md-8">
                                                <h4>Allow Access for Dealer to Change Your Information</h4>
                                                <!--    <p>Make your Bank Details Private or Public as per your privacy needs</p>-->
                                                <p> &nbsp;&nbsp;- Grant Access - It Will allow users to Grant/Give access to
                                                    its dealer for data modification inside the digital cards.</p>
                                                <p> &nbsp;&nbsp;- Revoke Access - It Will allow users to Revoke/Remove
                                                    access from their dealer for data modification inside the digital cards.
                                                </p>
                                            </div>
                                            <div class="col-md-3">
                                                <?php

                                                if ($get_user_details['dealer_access'] == '1') {
                                                    ?>
                                                    <label class="label label-success">Currently You have given access to
                                                        dealer.</label>
                                                    <button class="btn form-control btn-custom" type="submit"
                                                            name="btn_update_access">
                                                        <i class="fa fa-eye-slash"></i> Revoke Access
                                                    </button>
                                                <?php
                                                } else {
                                                    ?>
                                                    <label class="label label-danger">Currently You have not given access to
                                                        dealer.</label>
                                                    <button class="btn form-control btn-custom" type="submit"
                                                            name="btn_update_access"><i class="fa fa-eye"></i> Grant Access
                                                    </button>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <hr/>
                                <?php
                            }
                            ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <form method="POST" action="">
                                        <div class="col-md-8">
                                            <h4>Enquiry Email Address</h4>
                                            <!--    <p>Make your Bank Details Private or Public as per your privacy needs</p>-->
                                            <p> &nbsp;&nbsp;- Here you can update your Enquiry Email means which <br>&nbsp;&nbsp;&nbsp;&nbsp;email id you want enquiry email</p>
                                            <p> &nbsp;&nbsp;- By default its your default email id you can update it</p>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="email" name="enquiry_email" placeholder="Enter Enquiry Email" value="<?php echo (isset($get_user['enquiry_email']) && trim($get_user['enquiry_email']) !='')?$get_user['enquiry_email']:$session_email; ?>" class="form-control">
                                                </div>
                                            </div>
                                            <button class="btn form-control btn-primary" type="submit"
                                                    name="btn_update_enquiry_email"><i class="fa fa-check"></i> Update Email
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <hr/>
                            <div class="row">
                                <div class="col-md-12">
                                    <form method="POST" action="">
                                        <div class="col-md-8">
                                            <h4>Receive Private Link With</h4>
                                            <!--<p>Make your Bank Details Private or Public as per your privacy needs</p>-->
                                            <p> &nbsp;&nbsp;- Only Email - You will receive a Private link with bank Info Only On Email.</p>
                                            <p> &nbsp;&nbsp;- Only SMS - You will receive a Private link with bank Info Only On SMS.</p>
                                            <p> &nbsp;&nbsp;- SMS and Email Both - You will receive a Private link with bank Info On Email and SMS.</p>

                                        </div>
                                        <div class="col-md-3">
                                            <select class="form-control" name="drp_service" onchange="changeStatus()">
                                                <option value="">
                                                    Select an option
                                                </option>
                                                <option value="email" <?php if($get_user_details['recieve_service'] == "email") echo "selected"; ?>>Email</option>
                                                <option value="sms" <?php if($get_user_details['recieve_service'] == "sms") echo "selected"; ?>>SMS</option>
                                                <option value="both" <?php if($get_user_details['recieve_service'] == "both") echo "selected"; ?>>Both</option>
                                            </select>

                                            <button style="display: none" class="btn form-control btn-custom" type="submit" name="btn_recive_service">
                                                <i class="fa fa-eye-slash"></i> Revoke Access
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <hr/>
                            <div class="col-md-12">
                               <div class="row">
                                   <form method="POST" action="" enctype="multipart/form-data">
                                       <div class="col-md-8">
                                           <h4>Bank Details Privacy</h4>
                                           <p>Make your Bank Details Private or Public as per your privacy needs</p>
                                           <p> &nbsp;&nbsp;- Making It Private - Will Not be Visible to anyone unless you make it public.</p>
                                           <p> &nbsp;&nbsp;- Making It Public - Will be Visible publicly to customers who have your digital card link.</p>
                                       </div>
                                       <div class="col-md-3">
                                           <?php
                                           if ($display_bank_result['digital_card'] == '1') {
                                               ?>
                                               <label class="label label-success">Currently showing your bank details publicly.</label>
                                               <button class="btn form-control btn-custom" type="submit" name="update_bank">
                                                   <i class="fa fa-eye-slash"></i> Hide My Bank Details
                                               </button>
                                           <?php
                                           }else {
                                               ?>
                                               <label class="label label-danger">Currently not showing your bank details publicly.</label>
                                               <button class="btn form-control btn-custom" type="submit" name="update_bank"><i class="fa fa-eye"></i> Unhide My Bank Details</button>
                                           <?php
                                           }
                                           ?>
                                       </div>
                                   </form>
                               </div>
                            </div>
                            <hr/>
                            <div class="col-md-12">
                              <div class="row">
                                  <form method="POST" action="" enctype="multipart/form-data">
                                      <div class="col-md-8">
                                          <h4>Promote My Business</h4>
                                          <p>You can now promote your Digital Card to Generate more leads for your business.</p>
                                          <p> &nbsp;&nbsp;- Promotion On Share Digital Card - Your business will get listed in the Share Digital card website with all information you provided related to business</p>
                                          <p> &nbsp;&nbsp;- Promotion On Google - Your business will get the list down in Google Listing.</p>
                                          <p style="color: red">(Note: In Promote My Business Option  your Bank/UPI details will not be visible publicly)</p>
                                      </div>
                                      <div class="col-md-3">
                                          <?php
                                          if($get_user['online_search'] == "1") {
                                              ?>
                                              <label class="label label-success">Currently we are promoting your business.</label>
                                              <button class="btn form-control btn-custom" type="submit" name="btn_online_search">I Don't Want To Promote My
                                                  Business
                                              </button>
                                          <?php
                                          }else {
                                              ?>
                                              <label class="label label-danger">Currently we are not promoting your business.</label>
                                              <button class="btn form-control btn-custom" type="submit" name="btn_online_search">I Want To Promote My Business
                                              </button>
                                          <?php
                                          }
                                          ?>
                                      </div>
                                  </form>
                              </div>
                            </div>
                            <hr/>
                            <div class="col-md-12">
                              <div class="row">
                                  <form method="POST" action="" enctype="multipart/form-data">
                                      <div class="col-md-8">
                                          <h4>Deactivate account</h4>
                                          <p>Deactivation of account means you are temporarily deactivating account, you can reactivate it back whenever you want.</p>
                                          <p>If you deactivate Account - </p>
                                          <p> &nbsp;&nbsp;- Your Digital card will get deactivated and will not be visible to anyone.</p>
                                          <p> &nbsp;&nbsp;- You can not login into your account as well as Android Application.</p>
                                          <p> &nbsp;&nbsp;- All your Digital Card information will be present at our system and will be restore once you reacivate account.</p>
                                      </div>
                                      <div class="col-md-3 text-center">
                                          <button class="btn form-control btn-custom" type="button" name="btn_deactivate" data-toggle="modal" data-target="#myModal">Deactivate Account
                                          </button>
                                      </div>
                                  </form>
                              </div>
                            </div>
                            <hr/>
                            <div class="col-md-12">
                                <div class="row">
                                    <form method="POST" action="" enctype="multipart/form-data">
                                        <div class="col-md-8">
                                            <h4>Delete account</h4>
                                            <p>Delete of account means you are Permonantly closing your account, you can not reactivate it back.</p>
                                            <p>If you deactivate Account -  </p>
                                            <p> &nbsp;&nbsp;- Your Digital card will get deleted and will not be visible to anyone.</p>
                                            <p> &nbsp;&nbsp;- You can not login into your account as well as Android Application.</p>
                                            <p> &nbsp;&nbsp;- All your Digital Card information will be deleted from our system and reactivation of account is not possible.</p>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <button class="btn form-control btn-custom" type="button" name="btn_delete" data-toggle="modal" data-target="#myModalDelete">Delete Account
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

            </div>
        </div>
    </section>

    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog upi_modal_width">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header cust-upi-madal">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Deactivate Account</h4>
                </div>
                <div class="modal-body overflow_content">
                    <div class="body">
                        <div>
                            <p><b>Are you sure you want to deactivate your account?</b></p>
                            <p>Deactivating your account will disable your profile and remove your name and photo from most thing you have on share digital card.</p>
                        </div>
                        <div class="modal_cust_content">
                            <?php if ($error1) {
                                ?>
                                <div class="alert alert-danger">
                                    <?php if (isset($errorMessage1)) echo $errorMessage1; ?>
                                </div>
                                <?php
                            }
                            ?>
                            <form method="post" action="">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-4 col-xs-12">
                                            <b>Reason for leaving (required)</b>
                                        </div>
                                        <div class="col-md-8 col-xs-12">
                                            <input type="radio" name="txt_reason" checked value="This is temporary. I'll be back."> This is temporary. I'll be back.<br>
                                            <input type="radio" name="txt_reason" value="I dont't Understand how to use share digital card."> I dont't Understand how to use share digital card.<br>
                                            <!--<input type="radio" name="txt_reason" value="I dont't feel safe on share digital card."> I dont't feel safe on share digital card.<br>-->
                                            <input type="radio" name="txt_reason" value="I have a privacy concern."> I have a privacy concern.<br>
                                            <input type="radio" name="txt_reason" value="My account was hacked."> My account was hacked.<br>
                                            <input type="radio" name="txt_reason" value="Other reason"> Other Reason.<br>
                                        </div>

                                             <div class="col-md-4 col-xs-12 paddin_top_deactivate"><b>Please explain further</b></div>
                                             <div class="col-md-8 col-xs-12">
                                                <textarea class="form-control" rows="5" name="txt_further" placeholder="Please explain further .."></textarea>
                                            </div>

                                             <div class="col-md-4 col-xs-12 paddin_top_deactivate"><b>Enter Password : </b></div>
                                             <div class="col-md-8 col-xs-12">
                                                <input class="form-control" type="password" name="txt_pass" placeholder="Enter Password "/>
                                            </div>

                                             <div class="col-md-8 col-xs-12 paddin_top_deactivate">
                                                <button class="btn btn-primary" type="submit" name="btn_deactivate">Deactivate</button>
                                                <button class="btn btn-default">Cancel</button>
                                            </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModalDelete" role="dialog">
        <div class="modal-dialog upi_modal_width">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header cust-upi-madal">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Delete Account</h4>
                </div>
                <div class="modal-body overflow_content">
                    <div class="body">
                        <div>
                            <p><b>Are you sure you want to delete your account?</b></p>
                            <p>Deactivating your account will disable your profile and remove your name and photo from most thing you have on share digital card.</p>
                        </div>
                        <div class="modal_cust_content">

                            <?php if ($error2) {
                                ?>
                                <div class="alert alert-danger">
                                    <?php if (isset($errorMessage2)) echo $errorMessage2; ?>
                                </div>
                                <?php
                            }
                            ?>
                            <form method="post" action="">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-4 col-xs-12">
                                            <b>Reason for leaving (required)</b>
                                        </div>
                                        <div class="col-md-8 col-xs-12">
                                            <input type="radio" name="txt_reason" checked value="This is temporary. I'll be back."> This is temporary. I'll be back.<br>
                                            <input type="radio" name="txt_reason" value="I dont't Understand how to use share digital card."> I dont't Understand how to use share digital card.<br>
                                            <!--<input type="radio" name="txt_reason" value="I dont't feel safe on share digital card."> I dont't feel safe on share digital card.<br>-->
                                            <input type="radio" name="txt_reason" value="I have a privacy concern."> I have a privacy concern.<br>
                                            <input type="radio" name="txt_reason" value="My account was hacked."> My account was hacked.<br>
                                            <input type="radio" name="txt_reason" value="Other reason"> Other Reason.<br>
                                        </div>

                                        <div class="col-md-4 col-xs-12 paddin_top_deactivate"><b>Please explain further</b></div>
                                        <div class="col-md-8 col-xs-12">
                                            <textarea class="form-control" rows="5" name="txt_further" placeholder="Please explain further .."></textarea>
                                        </div>

                                        <div class="col-md-4 col-xs-12 paddin_top_deactivate"><b>Enter Password : </b></div>
                                        <div class="col-md-8 col-xs-12">
                                            <input class="form-control" type="password" name="txt_pass" placeholder="Enter Password "/>
                                        </div>

                                        <div class="col-md-8 col-xs-12 paddin_top_deactivate">
                                            <button class="btn btn-primary" type="submit" name="btn_delete">Delete account</button>
                                            <button class="btn btn-default">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include "assets/common-includes/footer_includes.php" ?>
    <?php
    if($show_deactivate_modal){
        ?>
        <script>
            $('button[name=btn_deactivate]')[0].click();
        </script>
    <?php
    }elseif ($show_delete_modal) {
        ?>
        <script>
            $('button[name=btn_delete]')[0].click();
        </script>
        <?php
    }
    ?>
    <script>
        function changeStatus() {
            $('button[name=btn_recive_service]')[0].click();
        }
    </script>
</body>

</html>