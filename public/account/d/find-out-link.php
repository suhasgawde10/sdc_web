<?php

include "../controller/ManageMobileCard.php";
$manage = new ManageMobileCard();
include '../sendMail/sendMail.php';
$error = false;
$errorMessage = "";
$error1 = false;
$errorMessage1 = "";

/*@session_start() ;
@session_destroy() ;*/
$link = "";
$link .= $_SERVER['HTTP_HOST'];

$link .= $_SERVER['REQUEST_URI'];

$date = date("Y-m-d");



if (isset($_POST['send_otp'])) {
    $sms_contact = $_POST['sms_contact'];
    /*echo "here";
    die();*/
    $result = $manage->validContactForCustomUrl($sms_contact);
    if($result !=null){
        $custom_url_user = $result['custom_url'];
        $_SESSION['new_custom_url'] = $custom_url_user;
        $send_sms = $manage->sendSMS($sms_contact, substr_replace($random_sms,'-',3,0));
        $_SESSION['sms_contact'] = $sms_contact;
        $_SESSION['random_sms'] = $random_sms;
        $error1 = false;
        $errorMessage1 = "OTP has been send";
    }else{
        $error1 = true;
        $errorMessage1 = "You have entered wrong number";
    }
}


if(isset($_POST['re_send_otp'])){
    $sms_contact = $_POST['sms_contact'];
    $result = $manage->validContactForCustomUrl($sms_contact);
    if($result !=null){
        $custom_url_user = $result['custom_url'];
        $_SESSION['new_custom_url'] = $custom_url_user;
        $send_sms = $manage->sendSMS($sms_contact, substr_replace($random_sms,'-',3,0));
        $_SESSION['sms_contact'] = $sms_contact;
        $_SESSION['random_sms'] = $random_sms;
        $error1 = false;
        $errorMessage1 = "OTP has been re-send ";
    }else{
        $error1 = true;
        $errorMessage1 = "You have entered wrong number";
    }
}




if(isset($_POST['verify_otp'])){
    $sms_otp = $_POST['sms_otp'];
    if($sms_otp==$_SESSION['random_sms']){
        $url = "index.php?custom_url=".$_SESSION['new_custom_url']."";
        $urlMessage = SHARED_URL.$_SESSION['new_custom_url'];
        echo "<script type=\"text/javascript\">
        window.open('".$url."','_self')
    </script>";
        $send_sms = $manage->sendSMS($_SESSION['sms_contact'], $urlMessage);
        session_destroy();
    }else{
        $error1 = true;
        $errorMessage1 .= "OTP Mismatched<br>";
    }
}





?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <title>Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "assets/common-includes/header_includes.php" ?>
    <?php
    $theme = "screenshot-desktop.png";
    ?>
    <style>
        .content-main1 {
            background-image: linear-gradient(to top, #fff1eb 0%, #ace0f9 100%);
            background-size: cover;
            height: 100vh;
        }
    </style>
</head>
<body class="content-main1">
<div class="end_sub_overlay2">
    <div class="bg-sms">
        <form id="forgot_password" method="POST">
            <?php if ($error1) {
                ?>
                <div class="alert alert-danger">
                    <?php if (isset($errorMessage1)) echo $errorMessage1; ?>
                </div>
            <?php
            } else if (!$error1 && $errorMessage1 != "") {
                ?>
                <div class="alert alert-success">
                    <?php if (isset($errorMessage1)) echo $errorMessage1; ?>
                </div>
            <?php
            }
            ?>
            <div class="msg">
                <p>Can't find your digital card.</p>
            </div>
            <div class="form-group">
                <input type="text" name="sms_contact" class="form-control" value="<?php if(isset($_SESSION['sms_contact'])) echo $_SESSION['sms_contact']; ?>"
                       placeholder="Contact Number"
                       autofocus>
            </div>
            <?php
            if(isset($_SESSION['sms_contact'])) {
                ?>
                <div class="form-group">
                    <input type="text" name="sms_otp" class="form-control" placeholder="OTP"
                           autofocus>
                </div>
            <?php
            }
            ?>
            <?php
            if(isset($_SESSION['sms_contact'])) {
                ?>
                <div>
                    <button type="submit" class="btn btn-success form-control" name="verify_otp">
                        Verify
                        OTP
                    </button><br><br>
                    <button class="resend_otp1" name="re_send_otp">Resend Otp</button>
                </div>
            <?php
            }else{
                ?>
                <button type="submit" class="btn btn-default form-control" name="send_otp">Send OTP
                </button>
                <div>OR</div>
                <div>
                    <a href="../register.php">Create an account</a>
                </div>
            <?php
            }
            ?>
        </form>
    </div>
</div>

<?php include "assets/common-includes/footer_includes.php" ?>

</body>
</html>