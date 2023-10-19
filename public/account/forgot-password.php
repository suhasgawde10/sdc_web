<?php
error_reporting(1);
include 'whitelist.php';
include "controller/ManageUser.php";
$manage = new ManageUser();
include "controller/validator.php";
$validate = new Validator();
include "controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();

require 'sendMail/sendMail.php';

$error = false;
$errorMessage = "";

$_SESSION['message'] = "";
$failed_status = false;
// unset($_SESSION['forgot_type']);
// unset($_SESSION['forgot_type']);
if(!isset($_GET['resetpassword'])){
    unset($_SESSION['reset_password']);
    unset($_SESSION['forget_opt']);
}

function sendMail($toName, $toEmail, $subject, $message)
{
    $sendMail = new sendMailSystem();
    $status = false;
    $sendMailStatus = $sendMail->sendMail($toName, $toEmail, MAIL_FROM_NAME, MAIL_FROM_EMAIL, $subject, $message);
    if ($sendMailStatus) {
        $status = true;
    } else {
        $status = false;
    }
    return $status;
}

function sendMailWithAttachment($toName, $toEmail, $subject, $message, $attachment)
{
    $sendMail = new sendMailSystem();
    $status = false;
    $sendMailStatus = $sendMail->sendMailWithAttachment($toName, $toEmail, MAIL_FROM_NAME, MAIL_FROM_EMAIL, $subject, $message, $attachment);
    if ($sendMailStatus) {
        $status = true;
    } else {
        $status = false;
    }
    return $status;
}


if (!isset($_SESSION['forgot_type'])) {
    $_SESSION['forgot_type'] = "email_id";
}

if (isset($_POST['btn_Send_email'])) {
    if (isset($_POST['email']) && $_POST['email'] != "") {

        $_SESSION['forgot_type'] = $_POST['forgot_type'];

        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $error = true;
            $errorMessage = "Invalid email format.<br>";
        }
        $email = $_POST['email'];


        if (!$error) {
            $result = $manage->validateAdminEmail($_POST['email']);
            if ($result) {
                $error = false;
                $password = generatePIN();
                $_SESSION['forget_opt'] = $password_contact;
                $subject = "Forgot Password - sharedigitalcard.com";
                /* $message = "Use this password $password to sign in ";*/
                $toName = "";
                $toEmail = "$email";
                $message = '<table style="width: 100%">
<tr>
<td colspan="2" style=' . $back_image . '>
<div style="' . $overlay . '">
<div style=" margin: 0 auto">
 <div class="user-name-logo" style="padding-top: 10px;">
                        <div style="text-align: center;color: #c4a758;width: 80px;margin: 1px auto;background: white;border-radius: 50%;height: 80px;text-align: center;padding: 5px;">
                            <img src="https://sharedigitalcard.com/assets/img/logo/logo.png" style="padding-top: 15px;width:100%">
                        </div>
                    </div>
                    <div style="text-align: center;color: white;font-weight: 700;padding-bottom: 10px;">
                        <h1 style="font-size: 24px;margin: 0;">Share Digital Card</h1>
                    </div>
</div>
</div>
</td>
</tr>
<tr>
<td colspan="2">
 <div style=" font-size: 18px;">
                   <div class="about-content">
                    <p>Please use the temporary password provided in this email to log in. Reset your password immediately with a secure password once logged in. The temporary password is valid for 30 minutes. Do not share it with anyone, and remember that we never call to verify temporary passwords.</p>
                </div>
                <div style="text-align: center;margin: 20px 0;">
                    <div class="otp-inner" style=" height: auto;
            background: #deddda;
            text-align: center;
            padding: 10px 0;
            width: 30%;
            margin: 0 auto;
            border: 2px dotted;
            font-size: 18px;
            font-weight: 600;">
                        <label>Your Temporary Password Is <br><span style=" color: #ef0404;
            font-weight: bold;">' . $password . '</span></label>
                    </div>
                </div>
                </div>
</td>
</tr>
<tr><td colspan="2" style="text-align:center">
<a href="http://sharedigitalcard.com/login.php" style="' . $btn . ';color:white; border-radius: 4px;"><img src="http://sharedigitalcard.com/user/assets/images/laptop.png" style="width: 19%;display: inline-block;vertical-align: middle;padding-right: 5px;color: white;">Click To Login</a>
                   <a target="_blank" href="https://play.google.com/store/apps/details?id=sharedigitalcard.com.digitalcard" style="padding: 0px;display: inline-block;vertical-align: middle;"><img src="https://sharedigitalcard.com/assets/img/playstore.png"
                                                                                          style="width: 135px" alt="digital card app"></a>
</td></tr>
<tr>
<td colspan="2" style=" font-size: 18px;">
<p> For any query email us on <a href="mailto:support@sharedigitalcard.com" class="payment">support@sharedigitalcard.com</a></p>
</td>
</tr>
<tr><td colspan="2" style="padding: 10px;background: #e6e6e6;height: 115px;">
 <div style="width: 85%;margin: 0 auto;">
                <div style=" width: 100%;margin: 0 auto;">
                    <div style="text-align:center">
                        <a href="https://www.facebook.com/sharedigitalcard/"><img src="http://sharedigitalcard.com/user/assets/images/fb.png"></a>
<a href="https://www.instagram.com/sharedigitalcard/"><img src="http://sharedigitalcard.com/user/assets/images/insta.png"></a>
<a href="https://www.youtube.com/watch?v=6T9Ia_2rsig&list=PLg1QyEHQ9MYYBRDxWqLrWLCyvJlSheqTh"><img src="http://sharedigitalcard.com/user/assets/images/yt.png"></a>
                    </div>
                </div>

            </div>
</td></tr>
</table>';

                if (!$main_site) {

                    $subject = "Forgot Password";
                    /* $message = "Use this password $password to sign in ";*/
                    $toName = "Mohammed Faheem";
                    $toEmail = "$email";
                    $message = '<table style="width: 100%">
<tr>
<td colspan="2">
 <div style=" font-size: 18px;">
                   <div class="about-content">
                    <p>We have received your Forgot password request, please kindly use the temporary password provided in this email in order to log in. We request you to reset your password immediately with a secure password once log in.</p>
                    <ul style="vertical-align: middle;font-size: 15px;line-height: 20px;font-weight: bold;list-style-type: none;width: 100%;padding: 0;margin: 0 auto;text-align: left;">
                        <li style="margin-bottom: 10px;"> Temporary password is valid for 30 minutes from the time you have generated the same, post which a new Temporary password will have to be generated.</li>
                        <li style="margin-bottom: 10px;"> Do not share your Temporary password with anyone, we never calls to verify Temporary password.</li>
                    </ul>
                </div>
                <div style="text-align: center;margin: 20px 0;">
                    <div class="otp-inner" style=" height: auto;
            background: #deddda;
            text-align: center;
            padding: 10px 0;
            width: 30%;
            margin: 0 auto;
            border: 2px dotted;
            font-size: 18px;
            font-weight: 600;">
                        <label>Your Temporary Password Is <br><span style=" color: #ef0404;
            font-weight: bold;">' . $password . '</span></label>
                    </div>
                </div>
                </div>
</td>
</tr>
</table>';
                }

                $sendMail = sendMail($toName, $toEmail, $subject, $message);
                if ($sendMail) {
                    $updatePassword = $manage->resetPassword($security->encrypt($password) . "8523", $_POST['email']);
                    if ($updatePassword) {
                        header('location:login.php?resetpassword=true');
                    } else {

                    }
                } else {
                    $failed_status = true;
                }
            } else {
                $error = true;
                $errorMessage = "Invalid email.<br>";
            }
        }
    } else {
        $error = true;
        $errorMessage = "Please enter your email.<br>";
    }
}

if (isset($_POST['btn_Send_contact'])) {
    if (isset($_POST['contact']) && $_POST['contact'] != "") {

        $_SESSION['forgot_type'] = $_POST['forgot_type'];
        $_SESSION['contact'] = $_POST['contact'];

        $contact = $_POST['contact'];
        if (!$error) {
            $result = $manage->validateUserContact($contact);
            if ($result) {
                $error = false;
                $password_contact = generatePIN();
                $_SESSION['forget_opt'] = $password_contact;

                if (!$main_site) {
                    $message = "Dear%20User%2C%20For%20forgetting%20a%20password%20in%20the%20Share%20Digital%20Card%20application%2C%20Your%20One-Time%20Password%20%28OTP%29%20is%20".$password_contact.".%20Please%20do%20not%20share%20this%20OTP%20with%20anyone.%20Thank%20you%2C%20Share%20Digital%20Card%20Message%20ID%3A%20asdasdwe";
                }
                else{
                    $message = "Dear%20User%2C%20For%20forgetting%20a%20password%20in%20the%20Share%20Digital%20Card%20application%2C%20Your%20One-Time%20Password%20%28OTP%29%20is%20".$password_contact.".%20Please%20do%20not%20share%20this%20OTP%20with%20anyone.%20Thank%20you%2C%20Share%20Digital%20Card%20Message%20ID%3A%20asdasdwe";
                }
                $send_sms = $manage->sendSMSWithTemplateId($contact, $message, TEMPLATE_FORGOT_PASSWORD);
                if ($send_sms) {
                    // $updatePassword = $manage->resetPasswordContact($security->encrypt($password_contact) . "8523", $contact);
                    // if ($updatePassword) {
                        header('location:forgot-password.php?resetpassword=true');
                    // } else {

                    // }
                } else {
                    $failed_status = true;
                }
            } else {
                $error = true;
                $errorMessage .= "Invalid contact no.<br>";
            }
        }
    } else {
        $error = true;
        $errorMessage .= "Please enter your contact.<br>";
    }
}

// unset($_SESSION['reset_password']);
// unset($_SESSION['forget_opt']);
if (isset($_POST['reset_passsword'])) {
        // dd("ss");exit;
    $explode_otp = implode('', $_POST['sms_otp']);
    $sms_otp = trim($explode_otp);


    if ($sms_otp == $_SESSION['forget_opt']) {
        $_SESSION['reset_password'] = true;
        header('location:forgot-password.php?resetpassword=hellow');
    }
}
if(isset($_POST['btn_reset'])){
    $contact = $_SESSION['contact'];
    
    $newPassword = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    
    if ($newPassword === $confirmPassword) {
        // dd("sss"); exit;
        $updatePassword = $manage->resetPasswordContact($security->encrypt($newPassword) . "8523", $contact);
        $userSpecificResult = $manage->getUserProfileForOTPLogin($_SESSION['contact']);
        // dd($userSpecificResult);
        // exit;
        if ($userSpecificResult != null) {

            unset($_SESSION['reset_password']);
            unset($_SESSION['forget_opt']);
            $name = $userSpecificResult["name"];
            $custom_url = $userSpecificResult["custom_url"];
            $contact = $userSpecificResult['contact_no'];
            $type = $userSpecificResult['type'];
            $status = $userSpecificResult['status'];
            $expiry_date = $userSpecificResult['expiry_date'];
            $email = $userSpecificResult['email'];
            $user_id = $userSpecificResult['user_id'];
            if ($status == 0) {
                $error = true;
                $errorMessage .= "You have been blocked.<br>";
            } elseif ($status == 2) {
                $_SESSION['user_deactivate'] = "true";
                $_SESSION['admin_email'] = $username;
                $_SESSION['admin_password'] = $pass;
                $error = true;
                $errorMessage .= "Your account was deactivated from your side. Please click below to reactive it,<br><a href='javascript:void(0)' onclick='sendThroughLinkOTP()' style='color: #2793e6;text-decoration: underline'>Activate Now</a>";
            } elseif ($status == 3) {
                $error = true;
                $errorMessage .= "Your account has been Deleted.<br>";
            } else {
                unset($_SESSION['dealer_login_type']); // dealer login
                $_SESSION['type'] = $type;
                $_SESSION['email'] = $email;
                $_SESSION['id'] = $security->encrypt($user_id);
                $_SESSION['name'] = $name;
                $_SESSION['contact'] = $contact;
                $_SESSION['custom_url'] = $custom_url;


                if ($_SESSION['type'] == "Admin" OR $_SESSION['type'] == "Editor") {
                    /* $_SESSION['admin_contact'] = $contact;
                    $_SESSION['admin_email'] = $email;
                    $_SESSION['admin_password'] = $pass;
                    $array = explode(',', $contact);
                    $sms_message = "Dear Customer,\n" . $admin_otp_sms . " is your one time password (OTP). Please do not share this OTP with anyone for security reasons.";
                    foreach ($array as $key) {
                        $send_sms = $manage->sendSMS($key, $sms_message);
                    }
                    $_SESSION['admin_otp'] = $admin_otp_sms;
                    $adminOTPMessage .= "OTP has been sent.<br>";*/
                    unset($_SESSION["admin_otp"]);
                    header('location:user/admin_dashboard.php');
                } else {
                    $get_section = $manage->getSectionName();
                    if ($get_section != null) {
                        $_SESSION['menu'] = array('s_profile' => $get_section['profile'],
                            's_services' => $get_section['services'],
                            's_our_service' => $get_section['our_service'],
                            's_products' => $get_section['products'],
                            's_our_products' => $get_section['our_product'],
                            's_gallery' => $get_section['gallery'],
                            's_images' => $get_section['images'],
                            's_videos' => $get_section['videos'],
                            's_clients' => $get_section['clients'],
                            's_client_name' => $get_section['client_name'],
                            's_client_review_tab' => $get_section['client_review'],
                            's_team' => $get_section['team'],
                            's_our_team' => $get_section['our_team'],
                            's_bank' => $get_section['bank'],
                            's_payment' => $get_section['payment'],
                            's_basic_info' => $get_section['basic_info'],
                            's_company_info' => $get_section['company_info']);
                    } else {
                        $_SESSION['menu'] = array(
                            's_profile' => "Profile",
                            's_services' => "Services",
                            's_our_service' => "Our Services",
                            's_products' => "Products",
                            's_our_products' => "Our Products",
                            's_gallery' => "Gallery",
                            's_images' => "Images",
                            's_videos' => "Videos",
                            's_clients' => "Clients",
                            's_client_name' => "Clients",
                            's_client_review_tab' => "Client's Reviews",
                            's_team' => "Team",
                            's_our_team' => "Our Team",
                            's_bank' => "Bank",
                            's_payment' => "Payment",
                            's_basic_info' => "Basic Info",
                            's_company_info' => "Company Info");
                    }
                    /*if($user_id !=86){*/
                    $_SESSION['total_percent'] = 10;
                    $pending_dot = $manage->getPendingFormCount();
                    $_SESSION['red_dot'] = array();
                    if ($pending_dot['company_name'] == "") {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('company_name' => true));
                    } else {
                        $_SESSION['total_percent'] = $_SESSION['total_percent'] + 10;
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('company_name' => false));
                    }
                    if ($pending_dot['service_name'] == "") {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('service_name' => true));
                    } else {
                        $_SESSION['total_percent'] = $_SESSION['total_percent'] + 10;
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('service_name' => false));
                    }
                    if ($pending_dot['image_name'] == "") {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('image_name' => true));
                    } else {
                        $_SESSION['total_percent'] = $_SESSION['total_percent'] + 10;
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('image_name' => false));
                    }
                    if ($pending_dot['video_link'] == "") {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('video_link' => true));
                    } else {
                        $_SESSION['total_percent'] = $_SESSION['total_percent'] + 10;
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('video_link' => false));
                    }
                    if ($pending_dot['client_name'] == "") {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('client_name' => true));
                    } else {
                        $_SESSION['total_percent'] = $_SESSION['total_percent'] + 10;
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('client_name' => false));
                    }
                    if ($pending_dot['client_review'] == "") {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('client_review' => true));
                    } else {
                        $_SESSION['total_percent'] = $_SESSION['total_percent'] + 10;
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('client_review' => false));
                    }
                    if ($pending_dot['our_team'] == "") {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('our_team' => true));
                    } else {
                        $_SESSION['total_percent'] = $_SESSION['total_percent'] + 10;
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('our_team' => false));
                    }
                    if ($pending_dot['bank_name'] == "") {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('bank_name' => true));
                    } else {
                        $_SESSION['total_percent'] = $_SESSION['total_percent'] + 10;
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('bank_name' => false));
                    }
                    if ($pending_dot['upi_id'] == "") {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('upi_id' => true));
                    } else {
                        $_SESSION['total_percent'] = $_SESSION['total_percent'] + 10;
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('upi_id' => false));
                    }
                    /*     }else{
                            $_SESSION['total_percent'] = 100;
                            $_SESSION['red_dot'] =  array('company_name' =>false, 'service_name' =>false, 'image_name' =>false, 'video_link' =>false, 'client_name' =>false, 'client_review' =>false, 'our_team' =>false, 'bank_name' => false, 'upi_id' =>false);
                        }*/

                    // $five_day = date('Y-m-d', strtotime(date_create("Y-m-d") . ' + 5 days'));
                    $three_day = date('Y-m-d', strtotime(date_create("Y-m-d") . ' + 2 days'));
                    $userSubs = $manage->displaySubscriptionDetailsByIdAlreadyIteate($user_id);
                    if ($userSubs != "") {
                        $plan_name = $userSubs['year'];
                    } else {
                        $plan_name = "plan";
                    }

                    if ($plan_name != 'Life Time') {
                        if (isset($_GET['view_invoice']) && $_GET['view_invoice'] != "") {
                            header('location:user/user-invoice.php?user_invoice_id=' . $_GET['view_invoice']);
                        }/* elseif ($expiry_date <= $three_day) {
                                header('location:user/plan-selection.php');
                            } elseif ($expiry_date <= $date) {
                                header('location:user/plan-selection.php');
                            }*/ else {
                            header('location:user/basic-user-info.php');
                        }
                    } else {
                        header('location:user/basic-user-info.php');
                    }

                }
            }
        }else{
            $error = true;
            $errorMessage .= "Oops! Something went wrong. Please try again.<br>";
        }
    } else {
        // dd($confirmPassword);
        // exit;
        $error = true;
        $errorMessage .= "Passwords do not match. Please try again.<br>";
    }
    // exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!--- Basic Page Needs  -->
    <meta charset="utf-8">
    <title>Forgot password | Online business and visiting card maker in India, Maharashtra, Mumbai.</title>
    <meta name="description"
          content="Please enter your resgister email to get forgot pasword OTP for share digital card.">
    <meta name="keywords"
          content="digital business card, digital visiting card, online visiting card, online business card, visiting card design, maker in, india, maharashtra, mumbai, business card design, customized design, attractive visiting card, share digital card, business card application, visiting card application, app, fogot password, password">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Mobile Specific Meta  -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet"
          type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    <!-- Bootstrap Core Css -->
    <!-- <link href="assets/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">-->
    <!-- Waves Effect Css -->
    <link href="user/assets/plugins/node-waves/waves.css" rel="stylesheet"/>
    <!-- Animation Css -->
    <link href="user/assets/plugins/animate-css/animate.css" rel="stylesheet"/>
    <!-- Custom Css -->
    <?php include "assets/common-includes/header_includes.php" ?>
    <style>
        [type="radio"]:not(:checked), [type="radio"]:checked {
            position: unset;
            left: 0;
            opacity: 1;
        }
    </style>
</head>

<body>

<!-- header area start -->
<?php
if ($main_site) {
    include "assets/common-includes/header.php";
}; ?>
<!-- header area end -->
<section class="feature-area padding_section <?php if ($main_site) {
    echo "bg-gray margin_top_div";
} else {
    echo "login-center";
} ?> " id="feature"
    <?php if ($main_site) { ?> style="background-image: url('assets/img/about/asdfaas.jpg');background-size: cover; background-position: bottom; height: 525px" <?php } ?>>
    <div class="container-fluid">
        <div class="row">
            <?php
            if ($main_site) {
                ?>
                <div class="col-md-8 col-sm-6 col-xs-12">

                </div>
            <?php
            }
            ?>
            <div class="<?php if ($main_site) {
                echo "col-md-3";
            } ?> hidden-sm margin_top_div col-xs-12">
                <div class="card_width card">
                    <div class="body">
                        <form action="" method="post">
                            <?php if(isset($_SESSION['reset_password']) && $_SESSION['reset_password'] == true){ ?>
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
                                <div class="msg">
                                    <h5>Reset Password</h5>
                                </div>
                                <div class="input-group">
                                    <div>
                                        <label for="new_password">New Password</label>
                                        <input type="password" class="form-control" name="password" placeholder="************">
                                    </div>
                                    <div>

                                        <label for="confirm_password">Confirm Password</label>
                                        <input type="password" class="form-control" name="confirm_password" placeholder="************">
                                    </div>
                                </div>
                                <button class="btn btn-block btn-lg bg-pink waves-effect" type="submit" name="btn_reset">RESET PASSWORD </button>
                            <?php
                                }
                            ?>
                        </form>
                    <form id="forgot_password_reset" method="POST" action="">
                        <?php if(isset($_SESSION['forget_opt']) && isset($_GET['resetpassword']) && $_GET['resetpassword'] == 'true'){ ?>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">lock</i>
                            </span>
                        <div class="otp_section">
                            <div class="digit-group">
                                <input class="send_textbox" type="number" id="digit-1" name="sms_otp[]"
                                       data-next="digit-2" onkeypress="return isNumberKey(event)"
                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                       maxlength="1"/>
                                <input class="send_textbox" type="number" id="digit-2" name="sms_otp[]"
                                       data-next="digit-3" data-previous="digit-1"
                                       onkeypress="return isNumberKey(event)"
                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                       maxlength="1"/>
                                <input class="send_textbox" type="number" id="digit-3" name="sms_otp[]"
                                       data-next="digit-4" data-previous="digit-2"
                                       onkeypress="return isNumberKey(event)"
                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                       maxlength="1"/>
                                <span class="splitter">&ndash;</span>
                                <input class="send_textbox" type="number" id="digit-4" name="sms_otp[]"
                                       data-next="digit-5" data-previous="digit-3"
                                       onkeypress="return isNumberKey(event)"
                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                       maxlength="1"/>
                                <input class="send_textbox" type="number" id="digit-5" name="sms_otp[]"
                                       data-next="digit-6" data-previous="digit-4"
                                       onkeypress="return isNumberKey(event)"
                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                       maxlength="1"/>
                                <input class="send_textbox" type="number" id="digit-6" name="sms_otp[]"
                                       data-previous="digit-5" onkeypress="return isNumberKey(event)"
                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                       maxlength="1"/>
                            </div>
                        </div>
                        </div>
                        <button type="submit" class="btn btn-block bg-pink waves-effect" name="reset_passsword" style="margin-bottom: 15px">Reset Password</button>
                    </form>
                    <?php
                        }elseif (!isset($_SESSION['forget_opt'])) {
                        

                        ?>
                        <form id="forgot_password" method="POST" action="">
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
                            <div class="msg">
                                <h5>Forgot password</h5>
                            </div>
                            <input type="radio" name="forgot_type" <?php if (isset($_SESSION['forgot_type']) && $_SESSION['forgot_type'] == "email_id") echo "checked='checked'"; ?> value="email_id"/> &nbsp;Email Id&nbsp;&nbsp;&nbsp;
                            <input type="radio" name="forgot_type"  <?php if (isset($_SESSION['forgot_type']) && $_SESSION['forgot_type'] == "contact_no") echo "checked='checked'"; ?> value="contact_no"/> &nbsp;Contact No
                            <div id="email_id" class="desc show_div">
                                <div class="input-group">
                                    <span class="input-group-addon"> <i class="material-icons">email</i></span>
                                    <div class="form-line">
                                        <input type="email" class="form-control" name="email" placeholder="Enter email">
                                    </div>
                                </div>
                                <button class="btn btn-block btn-lg bg-pink waves-effect" type="submit" name="btn_Send_email">RESET PASSWORD </button>
                                <!-- <div class="row m-t-20 m-b--5 ptb--10 text-center">
                                    <a href="login.php">Sign In!</a>
                                </div> -->
                            </div>
                            <div id="contact_no" class="desc hide_div">
                            <div class="input-group">
                                <span class="input-group-addon"> <i class="material-icons">phone</i>
                                </span>
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="contact" placeholder="Enter contact">
                                    </div>
                                </div>

                                <button class="btn btn-block btn-lg bg-pink waves-effect" type="submit" name="btn_Send_contact">RESET PASSWORD </button>
                               
                            </div>
                        </form>
                        <?php
                        }
                       
                        ?>
                                <div class="row m-t-20 m-b--5 ptb--10 text-center">
                                    <a href="login.php">Sign In!</a>
                                </div>
                    </div>
                </div>
                <?php
                if ($main_site) {
                    ?>
                    <div class="card_width card_bottom card">
                        <div class="body alreay_accout">
                            <div class="need-help">
                                <h5>Need Help</h5>

                                <p>We Would be Happy to help You!!</p>

                                <div class="tabular">
                                    <table>
                                        <tbody>
                                        <tr>

                                            <td><i class="fa fa-phone"></i> +91-9773884631/+91-9768904980
                                            </td>

                                        </tr>
                                        <tr>
                                            <td><i class="fa fa-envelope"></i> support@sharedigitalcard.com</td>

                                        </tr>

                                        </tbody>
                                    </table>

                                </div>


                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {

        <?php
        if(isset($_SESSION["forgot_type"]) && $_SESSION["forgot_type"]=="email_id"){
            echo "$('div.show_div').show();";
             echo "$('div.hide_div').hide();";
        }
        else{
       echo "$('div.show_div').hide();";
             echo "$('div.hide_div').show();";
        }
        ?>

        $("input[name$='forgot_type']").click(function () {
            var test = $(this).val();
            $("div.desc").hide();
            $("#" + test).show();
        });
    });
</script>
<!-- footer area start -->

<?php
if ($main_site) {
    include "assets/common-includes/footer.php";
} ?>
<!-- footer area end -->
<!-- Scripts -->
<?php include "assets/common-includes/footer_includes.php" ?>
</body>

</html>