<?php
include 'whitelist.php';
include "controller/ManageDealer.php";
$manage = new ManageDealer();
include "controller/validator.php";
$validate = new Validator();
include 'sendMail/sendMail.php';
$controller = new Controller();
$con = $controller->connect();
$error = false;
$errorMessage = "";
$error1 = false;
$errorMessage1 = "";
if (isset($_SESSION['dealer_email'])) {
    header('location:dealer/profile.php');
}

/*@session_start();
session_destroy();*/

// $xml = simplexml_load_file("http://www.geoplugin.net/xml.gp?ip=" . getRealIpAddr());

function getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

// $countryName = (string)$xml->geoplugin_countryName;
$countryName = "India";

if ($countryName != "India") {
    $_SESSION['dealer_email_login'] = "true";
}

if (isset($_SESSION['login_contact'])) {
    echo "<style>
.submit_contact{
display: none !important;
}
</style>";
} elseif (isset($_SESSION['dealer_contact'])) {
    echo "<style>
.submit_contact{
display: none !important;
}
</style>";
}

if (isset($_POST['btn_submit_contact'])) {
    if (isset($_POST['sms_email']) && $_POST['sms_email'] != "") {
        $_SESSION['dealer_email_login'] = "true";
        if (isset($_POST['sms_email']) && $_POST['sms_email'] != "") {
            if (!filter_var($_POST['sms_email'], FILTER_VALIDATE_EMAIL)) {
                $error1 = true;
                $errorMessage1 .= "Invalid email format.<br>";
            }
            $txt_email = mysqli_real_escape_string($con, $_POST['sms_email']);
        } else {
            $error1 = true;
            $errorMessage1 .= "Please enter your email.<br>";
        }
        if (!$error1) {
            $result = $manage->validateRegisterEmail($txt_email);
            if ($result) {
                $_SESSION['login_contact'] = $txt_email;
                echo "<style>
.submit_contact{
display: none !important;
}
</style>";
            } else {
                echo "<style>
.submit_contact{
display: none !important;
}
</style>";
                $sms_message = '<table style="width: 100%">
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
                       <p> Dear Customer,</p>
                    <p>Please check the below otp to verify your email id. Please do not share this otp with anyone for security reasons</p>

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
                        <label>Your OTP Is <br><span style=" color: #ef0404;
            font-weight: bold;">' . substr_replace($random_sms, '-', 3, 0) . '</span></label>
                    </div>
                </div>
                </div>
</td>
</tr>
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
                if (!$error1) {
                    $subject = "OTP For Registration From - sharedigitalcard.com";
                    $send_sms = $manage->sendMail(MAIL_FROM_NAME, $txt_email, $subject, $sms_message);
                    $_SESSION['dealer_tmp_email'] = $txt_email;
                    $_SESSION['random_sms'] = $random_sms;
                    $error1 = false;
                    $errorMessage1 .= "OTP has been sent to your email id.<br>";
                }
            }
        }
    } else {
        $result = $manage->validateContact($_POST['sms_contact']);
        if ($result) {
            $_SESSION['login_contact'] = $_POST['sms_contact'];
            echo "<style>
.submit_contact{
display: none !important;
}
</style>";
        } else {
            echo "<style>
.submit_contact{
display: none !important;
}
</style>";
            $_SESSION['dealer_contact'] = $_POST['sms_contact'];
            if (!$error1) {
                $sms_message = "Dear Customer,\n" . substr_replace($random_sms, '-', 3, 0) . " is your one time password (OTP). Please do not share this OTP with anyone for security reasons.";
                $send_sms = $manage->sendSMS($_SESSION['dealer_contact'], $sms_message);
                $_SESSION['random_sms'] = $random_sms;
                $error1 = false;
                $errorMessage1 = "OTP has been sent to your entered mobile number.<br>";
                /*echo "<script>alert('OTP has been sent to your entered mobile number');</script>";
                header('location:dealer-register.php');*/
            }
        }
    }
}

if (isset($_POST['btn_sign_in'])) {
    echo "<style>
.submit_contact{
display: none !important;
}
</style>";
    $pass = $_POST['txt_dealer_password'];
    if (isset($_POST['txt_dealer_password']) && $_POST['txt_dealer_password'] != "") {
        $pass = $_POST['txt_dealer_password'];
    } else {
        $error1 = true;
        $errorMessage1 .= "Please enter password.<br>";
    }
    if (!$error1) {
        /* $checkStatus = $manage->CheckLoginStatus($_SESSION['login_contact'], $_POST['txt_dealer_password']);*/
        $result = $manage->adminLogin($_SESSION['login_contact'], $pass);
        if ($result) {
            $email = $result["email"];
            $contact = $result['contact_no'];
            $user_id = $result["user_id"];
            $type = $result['type'];
            $userSpecificResult = $manage->getDealerProfile($user_id);
            if ($userSpecificResult != null) {
                $name = $userSpecificResult["name"];
                $block_status = $userSpecificResult['block_status'];
                $status = $userSpecificResult['status'];
                $pay_status = $userSpecificResult['pay_status'];
            }
            if ($type == "dealer") {
                $dealer_code = $userSpecificResult['dealer_code'];
            } else {
                $dealer_code = $result['dealer_code'];
            }
            if ($block_status == 0) {
                $error1 = true;
                $errorMessage1 .= "You have been blocked";
            } else {
                $_SESSION['dealer_email'] = $email;
                $_SESSION['dealer_id'] = $user_id;
                $_SESSION['dealer_name'] = $name;
                $_SESSION['dealer_contact'] = $contact;
                $_SESSION['dealer_type'] = $type;
                $_SESSION['dealer_code'] = $dealer_code;
                if ($status == 1 && $pay_status == 1) {
                    header('location:dealer/dashboard.php');
                } elseif ($status == 1 && $pay_status == 0) {
                    header('location:dealer/payment_deposit.php');
                } elseif ($type == "editor") {
                    header('location:dealer/create_digital_card.php');
                } else {
                    header('location:dealer/basic-user-info.php');
                }
            }
        } else {
            $error1 = true;
            $errorMessage1 .= "Invalid username and password";
        }
    }
}
if (isset($_POST['verify_otp'])) {

    echo "<style>
.submit_contact{
display: none !important;
}
</style>";
    $explode_otp = implode('', $_POST['sms_otp']);
    $sms_otp = trim($explode_otp);
    if ($sms_otp == $_SESSION['random_sms']) {
        echo "<style>.register_otp{ display: block !important;}</style>";
        echo "<style>.sms_registration{ display: none !important;}</style>";
        $_SESSION['verified_status'] = true;
        /*unset($_SESSION["random_sms"]);*/
        /*unset($_SESSION["contact"]);*/
    } else {
        $error1 = true;
        $errorMessage1 .= "OTP Mismatched<br>";
    }
}


if (isset($_POST['resend_otp'])) {
    echo "<style>
.submit_contact{
display: none !important;
}
</style>";

    if (!$error1) {
        if (isset($_SESSION['dealer_email_login']) && $_SESSION['dealer_email_login'] == "true") {
            $subject = "OTP For Registration From - sharedigitalcard.com";
            $sms_message = '<table style="width: 100%">
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
                       <p> Dear Customer,</p>
                    <p>Please check the below otp to verify your email id. Please do not share this otp with anyone for security reasons</p>

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
                        <label>Your OTP Is <br><span style=" color: #ef0404;
            font-weight: bold;">' . substr_replace($random_sms, '-', 3, 0) . '</span></label>
                    </div>
                </div>
                </div>
</td>
</tr>
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
            $send_sms = $manage->sendMail(MAIL_FROM_NAME, $_SESSION['dealer_tmp_email'], $subject, $sms_message);
            $_SESSION['random_sms'] = $random_sms;
            $error1 = false;
            $errorMessage1 .= "OTP has been re-sent to your email id.<br>";
        } else {
            $sms_message = "Dear Customer,\n" . substr_replace($random_sms, '-', 3, 0) . " is your one time password (OTP). Please do not share this OTP with anyone for security reasons.";
            $send_sms = $manage->sendSMS($_SESSION['dealer_contact'], $sms_message);
            $_SESSION['random_sms'] = $random_sms;
            $error1 = false;
            $errorMessage1 .= "OTP has been re-sent to your entered mobile number.<br>";
        }
    }
}


if (isset($_SESSION['verified_status']) && $_SESSION['verified_status'] == true) {
    echo "<style>.register_otp{ display: block !important;}</style>";
    echo "<style>.sms_registration{ display: none !important;}</style>";
}

if (isset($_POST['btn_cancel'])) {
    echo "<style>
.submit_contact{
display: none !important;
}
</style>";
    session_destroy();
    header('location:dealer-register.php');
}

if (isset($_POST['btn_submit'])) {
    echo "<style>
.submit_contact{
display: none !important;
}
</style>";


    if(isset($_POST["tnc_accept"])){
        
    }
    else{
        $error = true;
        $errorMessage .= "Please accept terms and conditions of Dealership before proceeding.<br>";
    }

    if (isset($_POST['txt_name']) && $_POST['txt_name'] != "") {
        $txt_name = $_POST['txt_name'];
    } else {
        $error = true;
        $errorMessage .= "Please enter name.<br>";
    }


    if (isset($_SESSION['dealer_tmp_email'])) {
        $txt_email = $_SESSION['dealer_tmp_email'];
    } else {
        if (isset($_POST['txt_email']) && $_POST['txt_email'] != "") {
            if (!filter_var($_POST['txt_email'], FILTER_VALIDATE_EMAIL)) {
                $error = true;
                $errorMessage .= "Invalid email format.<br>";
            }
            $txt_email = mysqli_real_escape_string($con, $_POST['txt_email']);
        } else {
            $error = true;
            $errorMessage .= "Please enter your email.<br>";
        }

    }
    if (!isset($_SESSION['dealer_contact'])) {
        if (isset($_POST['txt_contact']) && $_POST['txt_contact'] != "" && is_numeric($_POST['txt_contact'])) {
            $txt_contact = $_POST['txt_contact'];
        } else {
            $error = true;
            $errorMessage .= "Please Enter Contact Number.<br>";
        }
    } else {
        $txt_contact = $_SESSION['dealer_contact'];
    }
    if (isset($_POST['gender']) && $_POST['gender'] != "") {
        $gender = $_POST['gender'];
    } else {
        $error = true;
        $errorMessage .= "Please Select gender.<br>";
    }
    if (isset($_POST['txt_password']) && $_POST['txt_password'] != "") {
        if (($_POST['txt_confirm_pass']) !== ($_POST['txt_password'])) {
            $error = true;
            $errorMessage .= "password is not same as above.<br>";
        }
        $txt_password = $_POST['txt_password'];
    } else {
        $error = true;
        $errorMessage .= "Please enter your password.<br>";
    }
    if (!$error) {
        if (isset($_SESSION['dealer_contact'])) {
            $verify_number = 1;
            $result = $manage->validateRegisterEmail($txt_email);
            if ($result) {
                $error = true;
                $errorMessage .= "Email ID Already Exists!!";
            }
        } else {
            $verify_number = 0;
            $result = $manage->validateContact($txt_contact);
            if ($result) {
                $error = true;
                $errorMessage .= "Contact Number Already Exists!!";
            }
        }

        if (!$error) {
            $getUserId = $manage->addUser($txt_name, $gender);
            if ($getUserId != 0) {
                $_SESSION['dealer_code'] = "dealer_" . $getUserId;
                $type = "dealer";
                $updateDealer = $manage->updateDealerCode($getUserId);
                $insertUser = $manage->addUserLogin($getUserId, $type, $txt_email, $txt_contact, $txt_password);
                if ($insertUser) {
                    if (!file_exists('dealer/uploads/')) {
                        mkdir("dealer/uploads", 0777, true);
                    }

                    mkdir("dealer/uploads/" . $txt_email . "/profile/", 0777, true);
                    mkdir("dealer/uploads/" . $txt_email . "/id-proof/", 0777, true);
                    mkdir("dealer/uploads/" . $txt_email . "/light-bill/", 0777, true);

                    $_SESSION['dealer_email'] = $txt_email;
                    $_SESSION['dealer_name'] = $txt_name;
                    $_SESSION['dealer_id'] = $getUserId;
                    $_SESSION['dealer_type'] = $type;
                    $_SESSION['dealer_contact'] = $txt_contact;

                    $toName = $_SESSION['dealer_name'];
                    $toEmail = $_SESSION['dealer_email'];
                    $message = "Dear " . ucwords($_SESSION['dealer_name']) . ",\n
you have successfully registered as a dealer. please fill the details to get an approval from the portal.\n
Thank you";
                    $admin_message = "New Dealer " . ucwords($_SESSION['dealer_name']) . ",\n
you have successfully registered as a dealer.\nContact No :" . $txt_contact . "\nEmail : " . $_SESSION['dealer_email'];
                    $subject = "ShareDigitalCard.com - Registration Successful.";
                    $sendMail = $manage->sendMail($toName, $toEmail, $subject, $message);
                    //$sendToMailAdmin = $manage->sendMailAdmin(ADMIN_NAME, ADMIN_EMAIL, $subject, $admin_message);
                    $send_sms = $manage->sendSMS($txt_contact, $sms_message1);
                    //   $send_sms = $manage->sendSMS($global_contact, $admin_message);
                    //  $send_sms = $manage->sendSMS($global_contact2, $admin_message);
                    header("location:dealer/basic-user-info.php");
                } else {
                    $error = true;
                    $errorMessage .= "Something went wrong!! Please try again later.";
                }
            }
        }
    }
}


if (isset($_POST['change_session_email'])) {
    $_SESSION['dealer_email_login'] = "true";
}
if (isset($_POST['change_session_contact'])) {
    unset($_SESSION['dealer_email_login']);
}

$_SESSION['dealer_email_login'] = "true";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Dealer Registration | Online business and visiting card and digital maker in India, Maharashtra,
        Mumbai.</title>
    <meta name="description"
          content="Dealer register,Dealer business card,Dealer digital card,Register yourself and complete your digital profile. And use digital business card to connect world with single click.">
    <meta name="keywords"
          content="digital business card, online visiting card, affordable, attractive business and visiting card design maker in india, maharshatra, mumbai, modern solution for visiting card, business card application for android, share digital card , best digital card, customized, registration, sign up">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    <link href="user/assets/plugins/node-waves/waves.css" rel="stylesheet"/>

    <?php include "assets/common-includes/header_includes.php" ?>
</head>

<body>
<!-- preloader area start -->


<!-- preloader area end -->
<!-- header area start -->
<?php include "assets/common-includes/header.php" ?>
<section class="feature-area bg-gray padding_section margin_div background_dealer_img" id="feature">
    <div class="container-fluid padding_div">
        <div class="col-md-8 col-sm-6 col-xs-12"></div>
        <div class="col-md-3 hidden-sm col-xs-12 custom_deal_card margin_top_div">
            <div class="card card_width">
                <div class="body register_otp">
                    <form id="forgot_password" method="POST">
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
                            Registration
                        </div>
                        <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>

                            <div class="form-line">
                                <input name="txt_name" type="text" class="form-control" placeholder="Full name"
                                       value="<?php if (isset($_POST['txt_name'])) echo $_POST['txt_name']; ?>"
                                       autofocus>
                            </div>
                        </div>
                        <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">email</i>
                        </span>

                            <div class="form-line">
                                <input name="txt_email" type="email" class="form-control" placeholder="Email"
                                       autofocus
                                       value="<?php if (isset($_SESSION['dealer_tmp_email'])) echo $_SESSION['dealer_tmp_email']; ?>" <?php if (isset($_SESSION['dealer_tmp_email'])) echo "disabled"; ?>>
                            </div>
                        </div>
                        <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>

                            <div class="form-line">
                                <input type="text" name="txt_contact" class="form-control"
                                       placeholder="Contact Number"
                                       value="<?php if (isset($_SESSION['dealer_contact'])) echo $_SESSION['dealer_contact']; ?>"
                                       autofocus <?php if (isset($_SESSION['dealer_contact'])) echo "disabled"; ?>>
                            </div>
                        </div>

                        <div class="input-group">
                         <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>

                            <div class="form-group form-float">
                                <div class="form-line">
                                    <select id="gender" name="gender" class="form-control">
                                        <option name="male"
                                                value="Male" <?php if (isset($_POST['gender']) && $_POST['gender'] == "Male") echo "selected"; ?>>
                                            Male
                                        </option>
                                        <option name="female"
                                                value="Female" <?php if (isset($_POST['gender']) && $_POST['gender'] == "Female") echo "selected"; ?>>
                                            Female
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>

                            <div class="form-line">
                                <input name="txt_password" type="password" class="form-control"
                                       placeholder=" Password"
                                       autofocus>
                            </div>
                        </div>
                        <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>

                            <div class="form-line">
                                <input name="txt_confirm_pass" type="password" class="form-control"
                                       placeholder="Confirm Password" autofocus>
                            </div>
                        </div>
                        <div>
                           <input type="checkbox" name="tnc_accept"> I have Read and Accept all <a href="https://sharedigitalcard.com/terms-of-dealership" style="color:#2793e6" target="_blank">Terms & Conditions</a> of Dealership
                           <p style="font-size: 12px; color:red">Note: By clicking this you acknowledge that you accept all terms and conditions of dealership.</p>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-block bg-pink waves-effect" name="btn_submit">
                                Sign Up
                            </button>
                            <div>
                                <button type="submit" style="float: left" class="resend_otp" name="btn_cancel">Cancel
                                </button>
                            </div>
                            <br>
                        </div>
                    </form>
                </div>
                <div class="body sms_registration">
                    <form id="dealer_login" method="POST">
                        <?php if ($error1) {
                            ?>
                            <div class="alert alert-danger">
                                <?php if (isset($errorMessage1)) echo $errorMessage1; ?>
                            </div>
                        <?php
                        } else if (!$error1 && $errorMessage1 != "") {
                            ?>
                            <div class="alert alert-success success_color">
                                <?php if (isset($errorMessage1)) echo $errorMessage1; ?>
                            </div>
                        <?php
                        }
                        ?>
                        <div class="msg p-0">
                            <?php
                            if (isset($_SESSION['login_contact']) or (isset($_GET['sign-in']) && $_GET['sign-in'] == "true")) {
                                ?>
                                <h6 class="text-center">Sign In as dealer</h6>
                            <?php
                            } else {
                                ?>
                                <h5>Create Dealer Account</h5>

                                <p>In order to create your own personalise card for your business. <b>"Go Paperless, Go
                                        Digital"</b></p>
                            <?php
                            }
                            ?>
                        </div>
                        <?php
                        if (isset($_SESSION['dealer_email_login']) && $_SESSION['dealer_email_login'] == "true") {
                            ?>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="material-icons">email</i>
                                </span>

                                <div class="form-line">
                                    <input type="email" id="sms_email" name="sms_email" class="form-control"
                                           value="<?php if (isset($_SESSION['login_contact'])) {
                                               echo $_SESSION['login_contact'];
                                           } elseif (isset($_SESSION['dealer_tmp_email'])) echo $_SESSION['dealer_tmp_email']; ?>"
                                           placeholder="Enter Email Id"
                                           autofocus <?php if (isset($_SESSION['dealer_tmp_email'])) {
                                        echo "disabled";
                                    } ?> autocomplete="off" required="required">
                                </div>
                            </div>
                        <?php
                        } else {
                            ?>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="material-icons">phone</i>
                                </span>

                                <div class="form-line">
                                    <input type="number" name="sms_contact" class="form-control"
                                           value="<?php if (isset($_SESSION['login_contact'])) {
                                               echo $_SESSION['login_contact'];
                                           } elseif (isset($_SESSION['dealer_contact'])) {
                                               echo $_SESSION['dealer_contact'];
                                           } elseif (isset($_GET['contact_number'])) {
                                               echo $_GET['contact_number'];
                                           } ?>"
                                           placeholder="Contact Number" maxlength="10"
                                        <?php if (isset($_SESSION['login_contact'])) {
                                            echo "disabled";
                                        } elseif (isset($_SESSION['dealer_contact'])) {
                                            echo "disabled";
                                        } ?> autocomplete="off" onkeypress="return isNumberKey(event)"
                                           required="required">
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                        <?php
                        if (!isset($_SESSION['dealer_tmp_email'])) {
                        ?>
                        <div class="submit_contact">
                            <div class="col-xs-12">
                                <div class="row">
                                    <button type="submit" class="btn btn-block bg-pink waves-effect"
                                            name="btn_submit_contact">  <?php
                                        if (isset($_SESSION['login_contact']) or (isset($_GET['sign-in']) && $_GET['sign-in'] == "true")) {
                                            ?>SIGN IN<?php } else {
                                            echo "Submit";
                                        } ?>
                                    </button>
                                </div>
                            </div>
                    </form>


                    <div class="col-xs-6" style="padding: 0">
                        <form method="post" action="">
                            <?php
                            if (isset($_SESSION['dealer_email_login']) && $_SESSION['dealer_email_login'] == "true") {
                                if ($countryName == "India") {
                                    ?>
                                    <button class="resend_otp" type="submit" name="change_session_contact"
                                            style="color: #2793e6;font-size: 13px;float: left">Register with Contact
                                    </button>
                                <?php }
                            } else { ?>
                                <button class="resend_otp" type="submit" name="change_session_email"
                                        style="color: #2793e6;font-size: 13px;float: left">Register with email
                                </button>
                            <?php } ?>
                        </form>
                    </div>
                    <div class="col-xs-6 text-right" style="padding: 0">
                        <a href="dealer-forgot-password.php">Forgot Password?</a>
                    </div>

                </div>
                <?php
                }
                ?>
                <form id="login_number" method="POST">
                    <?php
                    if (isset($_SESSION['login_contact'])) {
                        ?>
                        <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="material-icons">lock</i>
                                </span>

                            <div class="form-line">
                                <input type="password" name="txt_dealer_password" class="form-control"
                                       placeholder="Password" autocomplete="off" autofocus>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12">
                                <button class="btn btn-block bg-pink waves-effect" name="btn_sign_in">SIGN IN
                                </button>
                            </div>
                            <div class="col-xs-4">
                                <button type="submit" style="float: left" class="resend_otp" name="btn_cancel">
                                    Cancel
                                </button>
                            </div>
                            <div class="col-xs-8 text-right">
                                <a href="dealer-forgot-password.php">Forgot Password?</a>
                            </div>
                            <!--<div class="col-xs-8 text-right">
                                <a href="forgot-password.php">Forgot Password?</a>
                            </div>-->
                        </div>
                    <?php
                    }
                    ?>
                </form>
                <form method="post" action="">
                    <?php
                    if (isset($_SESSION['dealer_contact']) or isset($_SESSION['dealer_tmp_email'])) {
                        ?>
                        <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="material-icons">lock</i>
                                </span>

                            <!--  <div class="form-line">
                                  <input type="password" name="sms_otp" class="form-control" placeholder="OTP"
                                         autofocus>
                              </div>-->
                            <div class="otp_section">
                                <div class="digit-group">
                                    <input class="send_textbox" type="number" id="digit-1" name="sms_otp[]"
                                           data-next="digit-2" onkeypress="return isNumberKey(event)"
                                           oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                           maxlength="6"/>
                                    <input class="send_textbox" type="number" id="digit-2" name="sms_otp[]"
                                           data-next="digit-3" data-previous="digit-1"
                                           onkeypress="return isNumberKey(event)"
                                           oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                           maxlength="6"/>
                                    <input class="send_textbox" type="number" id="digit-3" name="sms_otp[]"
                                           data-next="digit-4" data-previous="digit-2"
                                           onkeypress="return isNumberKey(event)"
                                           oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                           maxlength="6"/>
                                    <span class="splitter">&ndash;</span>
                                    <input class="send_textbox" type="number" id="digit-4" name="sms_otp[]"
                                           data-next="digit-5" data-previous="digit-3"
                                           onkeypress="return isNumberKey(event)"
                                           oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                           maxlength="6"/>
                                    <input class="send_textbox" type="number" id="digit-5" name="sms_otp[]"
                                           data-next="digit-6" data-previous="digit-4"
                                           onkeypress="return isNumberKey(event)"
                                           oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                           maxlength="6"/>
                                    <input class="send_textbox" type="number" id="digit-6" name="sms_otp[]"
                                           data-previous="digit-5" onkeypress="return isNumberKey(event)"
                                           oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                           maxlength="6"/>

                                           <input type="hidden" value="<?php echo $_SESSION['random_sms']; ?>">
                                </div>
                            </div>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-block bg-pink waves-effect" name="verify_otp">
                                Verify
                                OTP
                            </button>
                            <div>
                                <button type="submit" style="float: left" class="resend_otp" name="btn_cancel">
                                    Cancel
                                </button>
                            </div>
                            <div>
                                <button type="submit" class="resend_otp" name="resend_otp">Resend OTP</button>
                            </div>
                            <br>
                        </div>
                    <?php
                    } ?>
                </form>
            </div>
        </div>

    </div>
    </div>
</section>

<!-- footer area start -->
<?php include "assets/common-includes/footer.php" ?>
<!-- footer area end -->
<!-- Scripts -->
<?php include "assets/common-includes/footer_includes.php" ?>
<script src="user/assets/plugins/node-waves/waves.js"></script>
<script src="user/assets/js/admin.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.js"></script>
<script src="user/assets/js/commonValidation.js" type="text/javascript"></script>
<script type="text/javascript">
    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode != 46 && (charCode < 48 || charCode > 57)))
            return false;
        return true;
    }
</script>
<script>

    $('.digit-group').find('input').each(function () {
        $(this).attr('maxlength', 1);
        $(this).on('keyup', function (e) {
            var parent = $($(this).parent());

            if (e.keyCode === 8 || e.keyCode === 37) {
                var prev = parent.find('input#' + $(this).data('previous'));

                if (prev.length) {
                    $(prev).select();
                }
            } else if ((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 65 && e.keyCode <= 90) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode === 39) {
                var next = parent.find('input#' + $(this).data('next'));

                if (next.length) {
                    $(next).select();
                } else {
                    if (parent.data('autosubmit')) {
                        parent.submit();
                    }
                }
            }
        });
    });
</script>
<!--<script src="user/assets/js/pages/examples/sign-up.js"></script>-->
</body>
</html>