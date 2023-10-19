<?php

include "../controller/ManageDealer.php";
$manage = new ManageDealer();
include "../controller/validator.php";
$validate = new Validator();
include '../sendMail/sendMail.php';
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
$error = false;
$errorMessage = "";
$error1 = false;
$errorMessage1 = "";
/*if (isset($_SESSION['email'])) {
    header('location:user/basic-user-info.php');
}*/
if (!isset($_SESSION['dealer_email'])) {
    header('location:../dealer-register.php');
}

/*@session_start();
session_destroy();*/

/*echo $_SESSION['dealer_code'];
die();*/
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

$xml = simplexml_load_file("http://www.geoplugin.net/xml.gp?ip=" . getRealIpAddr());

$countryName = (string)$xml->geoplugin_countryName;
$random_password = rand(99999, 99999999);


if (isset($_POST['send_otp'])) {
    $sms_contact = $_POST['sms_contact'];
    $result = $manage->validateUserContact($sms_contact);
    if ($result) {
        $error1 = true;
        $errorMessage1 .= "Contact Number Already Exists!!<br>";
    }
    // $sms_message = "Dear Customer,\n" . substr_replace($random_sms,'-',3,0) . " is your one time password (OTP). Please do not share this OTP with anyone for security reasons.";
    $sms_message = "Dear Customer,\n" . substr_replace($random_sms, '-', 3, 0) . " is your one time password (OTP).";
    if (!$error1) {
        $send_sms = $manage->sendSMS($sms_contact, $sms_message);
        $_SESSION['user_contact'] = $sms_contact;
        $_SESSION['random_sms'] = $random_sms;
        $error1 = false;
        $errorMessage1 .= "OTP has been sent to your entered mobile number.<br>";
    }
}

if (isset($_POST['resend_otp'])) {
    $result = $manage->validateUserContact($_SESSION['user_contact']);
    if ($result) {
        $error1 = true;
        $errorMessage1 .= "Contact Number Already Exists!!<br>";
    }
    // $sms_message = "Dear Customer,\n" . substr_replace($random_sms,'-',3,0) . " is your one time password (OTP). Please do not share this OTP with anyone for security reasons.";
    $sms_message = "Dear Customer,\n" . substr_replace($random_sms, '-', 3, 0) . " is your one time password (OTP).";
    if (!$error1) {
        $send_sms = $manage->sendSMS($_SESSION['user_contact'], $sms_message);
        $_SESSION['random_sms'] = $random_sms;
        $error1 = false;
        $errorMessage1 .= "OTP has been re-sent to your entered mobile number.<br>";
    }
}

if (isset($_POST['verify_otp'])) {
    $sms_otp = $_POST['sms_otp'];
    if ($sms_otp == $_SESSION['random_sms']) {
        echo "<style>.register_otp{ display: block !important;}</style>";
        echo "<style>.sms_registration{ display: none !important;}</style>";
        $_SESSION['verified_status'] = true;
        /*unset($_SESSION["random_sms"]);*/
        /*  unset($_SESSION["contact"]);*/
    } else {
        $error1 = true;
        $errorMessage1 .= "OTP Mismatched<br>";
    }
}
function GenerateAPIKey()
{
    $key = implode('-', str_split(substr(strtolower(md5(microtime() . rand(1000, 9999))), 0, 30), 6));
    return $key;
}

$api_key = GenerateAPIKey();


if (isset($_POST['btn_cancel'])) {
    unset($_SESSION["random_sms"]);
    unset($_SESSION["user_contact"]);
    $_SESSION['verified_status'] = false;
    echo "<style>.register_otp{ display: none !important;}</style>";
    echo "<style>.sms_registration{ display: block !important;}</style>";
    header('location:create_digital_card.php');
}
if (isset($_SESSION['verified_status']) && $_SESSION['verified_status'] == true) {
    echo "<style>.register_otp{ display: block !important;}</style>";
    echo "<style>.sms_registration{ display: none !important;}</style>";
}

if (isset($_POST['btn_submit'])) {
    if (isset($_POST['txt_name']) && $_POST['txt_name'] != "") {
        $txt_name = $_POST['txt_name'];
    } else {
        $error = true;
        $errorMessage .= "Please enter name.<br>";
    }
    if (isset($_POST['txt_contact']) && $_POST['txt_contact'] != "" && is_numeric($_POST['txt_contact'])) {
        $txt_contact = $_POST['txt_contact'];
    } else {
        $error = true;
        $errorMessage .= "Please enter contact number.<br>";
    }
    if (isset($_POST['txt_email']) && $_POST['txt_email'] != "") {
        if (!filter_var($_POST['txt_email'], FILTER_VALIDATE_EMAIL)) {
            $error = true;
            $errorMessage .= "Invalid email format.<br>";
        }
        $txt_email = trim($_POST['txt_email']);
    } else {
        $error = true;
        $errorMessage .= "Please enter your email.<br>";
    }
    if (isset($_POST['country']) && $_POST['country'] != "") {
        $country = $_POST['country'];
    } else {
        $error = true;
        $errorMessage .= "Please Select country.<br>";
    }

    if (isset($_POST['txt_state']) && $_POST['txt_state'] != "") {
        $txt_state = $_POST['txt_state'];
    } else {
        $error = true;
        $errorMessage .= "Please Select state.<br>";
    }
    if (isset($_POST['txt_city']) && $_POST['txt_city'] != "") {
        $txt_city = $_POST['txt_city'];
    } else {
        $error = true;
        $errorMessage .= "Please Select city.<br>";
    }
    if (isset($_POST['gender']) && $_POST['gender'] != "") {
        $gender = $_POST['gender'];
    } else {
        $error = true;
        $errorMessage .= "Please Select gender.<br>";
    }
    $txt_password = "12345678";
   /* if (isset($_POST['txt_password']) && $_POST['txt_password'] != "") {
        $txt_password = $_POST['txt_password'];
    } else {
        $error = true;
        $errorMessage .= "Please enter your password.<br>";
    }*/
    $result = $manage->validateUserContact($txt_contact);
    if ($result) {
        $error = true;
        $errorMessage .= "Contact Number Already Exists!!<br>";
    }
    if (!$error) {
        if (isset($_POST['txt_company_name']) && $_POST['txt_company_name'] != '') {
            $txt_company_name = $_POST['txt_company_name'];
            $txt_custom_url = str_replace(' ', '-', trim($txt_company_name));
            $result = $manage->validateCustomUrl(trim($txt_custom_url));
            if ($result) {
                $new_custom_url = $txt_custom_url . rand(1000, 100000);
            } else {
                $new_custom_url = $txt_custom_url;
            }
        } else {
            $txt_company_name = "";
            $txt_custom_url = str_replace(' ', '-', trim($txt_name));
            $result = $manage->validateCustomUrl(trim($txt_custom_url));
            if ($result) {
                $new_custom_url = $txt_custom_url . rand(1000, 100000);
            } else {
                $new_custom_url = $txt_custom_url;
            }
        }
        $new_custom_url = str_replace([",", "/", "'"], "", $new_custom_url);
        $new_custom_url = str_replace("&", "and", $new_custom_url);
        $result = $manage->validateUserRegisterEmail($_POST['txt_email']);
        if ($result) {
            $error = true;
            $errorMessage .= "Email ID Already Exists!!";
        } else {
            $sell_ref = "dealer_panel";

            if (isset($_SESSION['dealer_type']) && $_SESSION['dealer_type'] == "dealer") {
                $dealer_id = "";
            } else {
                $dealer_id = $_SESSION['dealer_id'];

            }
            if (!isset($_POST['online_search'])) {
                $online_search = 0;
            } else {
                $online_search = 1;
            }
            $getUserId = $manage->addUserDetails($txt_name, $new_custom_url, $gender, $sell_ref, $dealer_id, $online_search, $country, $txt_state, $txt_city, $txt_company_name);
            if ($getUserId != 0) {
                $type = "User";
                $user_referral_code = "ref100" . $getUserId;
                $updateDealer = $manage->updateUserCode($getUserId, $user_referral_code);
                $insertUser = $manage->addUserLoginDetails($getUserId, $type, $txt_email, $txt_contact, $security->encrypt($txt_password) . "8523", $api_key);

                if ($insertUser) {
                    //  $insertCustomUrl = $manage->addCustomUrl($getUserId, $new_custom_url);
                    $insertMenuBar = $manage->addMenuBar($getUserId);
                    $getSectionDetails = $manage->getSectionDetails();
                    if ($getSectionDetails != null) {
                        while ($result_data = mysqli_fetch_array($getSectionDetails)) {
                            $sectionId = $result_data["id"];
                            if ($sectionId == 7) {
                                $p_dg_status = 0;
                            } else {
                                $p_dg_status = 1;
                            }
                            $insertUserSectionEntry = $manage->insertDefaultUserSectionEntry($getUserId, $sectionId, $p_dg_status);
                        }
                    }
                    if (!file_exists('../user/uploads/')) {
                        mkdir("../user/uploads", 0777, true);
                    }

                    mkdir("../user/uploads/" . trim($txt_email) . "/profile/", 0777, true);
                    mkdir("../user/uploads/" . trim($txt_email) . "/image-slider/", 0777, true);
                    mkdir("../user/uploads/" . trim($txt_email) . "/about-us/", 0777, true);
                    mkdir("../user/uploads/" . trim($txt_email) . "/service/", 0777, true);
                    mkdir("../user/uploads/" . trim($txt_email) . "/images/", 0777, true);
                    mkdir("../user/uploads/" . trim($txt_email) . "/testimonials/clients", 0777, true);
                    mkdir("../user/uploads/" . trim($txt_email) . "/testimonials/client_review", 0777, true);
                    mkdir("../user/uploads/" . trim($txt_email) . "/our-team/", 0777, true);
                    mkdir("../user/uploads/" . trim($txt_email) . "/logo/", 0777, true);

                    $toName = $txt_name;
                    $toEmail = trim($txt_email);

                    $message = '<p>Dear <span class="cust-name">' . ucwords($txt_name) . '</span>,</p>
                    <p>We are happy to welcome you to the digital world of visiting card. Thank you for registration.<br><br>
                        Your registered email id: <span class="email-id">' . trim($txt_email) . '</span><br><br>
                        Please follow the further process and get your digital card. </p>
                        <p>
                        <b>Username: </b>' . $txt_contact . ' OR ' . trim($txt_email) . '<br><b>Password: </b>' . $txt_password . '
                        </p>
                        <br>
                        <p>Please do not share username and password with anyone due to security reason.</p>';

                    $sms_message_new = "Dear " . ucwords($txt_name) . ", Thank you for registration. Please use following details in order to login, Username: " . $txt_contact . "/" . trim($txt_email) . " and Password: " . $txt_password . " Please do not share username and password with anyone due to security reason.";
                    //$sms_message1 = "Dear " . ucwords($txt_name) . ", Please login to fill all your details to complete your digital card.\nURL:" . $site_url . "/login.php \nUsername=" . $txt_contact . "\nPassword=" . $txt_password . "\n\nclick here to open your digital card\n" . SHARED_URL . $new_custom_url;
                    $subject = "Digital Card - Registration Successful.";
                    //$sendMail = $manage->sendMailForDealer($toName, $toEmail, $subject, $message);

                    $get_user_data = $manage->getUserData($getUserId);
                    if ($get_user_data != null) {
                        $user_expiry_date = $get_user_data['expiry_date'];
                        $u_name = $get_user_data['name'];
                        $u_email = $get_user_data['email'];
                        $u_contact = $get_user_data['contact_no'];
                        $update_user_count = $get_user_data['update_user_count'];
                        $get_email_count = $get_user_data['email_count'];
                        $custom_url = $get_user_data['custom_url'];
                    }
                    $date1 = date("Y-m-d");
                    $date = date_create("$date1");
                    date_add($date, date_interval_create_from_date_string("10 days"));
                    $final_date = date_format($date, "Y-m-d");
                    $year = "Free Trail (5 days)";
                    $amount = "0";
                    $status = "success";
                    $referal_by = "";
                    $refrenced_by = "";
                    $active_plan = 1;
                    $invoice_no = "";
                    $discount = 0;
                    $paymentMode = "";
                    $paymentBrand = "";
                    $custBankId = "";
                    $timestamp = date('Y-m-d H:i:s');
                    $tax = 0;
                    $dealer_gstn_no = "";
                    $dealer_by_pay = 1;
                    $payment_type = "RazorPay";
                    $insertUserSubscription = $manage->insertUserData($getUserId, 1, $year, $amount, $amount, $date1, $final_date, $status, $active_plan, $invoice_no, $tax, $amount, $paymentBrand, $paymentMode, $custBankId, $timestamp, $dealer_by_pay, $payment_type, "", "", $dealer_gstn_no, "", FROM_BILL, FROM_GSTNO, FROM_PAN, SAC_CODE, '', '', '', '', '');
                    if ($insertUserSubscription) {
                        $txt_contact = $txt_email = $txt_name = "";
                        $updateUserExpiry = $manage->updateUserExpiryDate($getUserId, $final_date);
                        if ($updateUserExpiry) {
                            $update_email_count = $manage->update_user_email_count($getUserId);
                            $error = false;
                            if (isset($_SESSION['dealer_type']) && $_SESSION['dealer_type'] == "dealer") {
                                $user_id = $security->encrypt($getUserId);
                                //$errorMessage .= 'Digital Card has been successfully created with 5 days trial. click here if you want to upgrade. <a href="plan-selection.php?user_id=' . $user_id . '" class="btn btn-primary">Upgrade Now</a>';
                                $errorMessage .= 'Digital Card has been successfully created with 5 days trial. Share following credentials with Customer: <br><br><b>Username: ' . $u_contact . ' and Password : ' . $txt_password.'<br>';
                            } else {
                                $errorMessage .= 'Digital Card has been successfully created with 5 days trial.';
                            }
                        }
                    }
                    //$sendMail = $manage->sendMailForDealer($toName, $toEmail, $subject, $message);
                    // Always set content-type when sending HTML email
                    /*$headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    $headers .= "From: " . $subject . "\r\n" .
                        "CC: support@sharedigitalcard.com";*/

                    // Always set content-type when sending HTML email
                    /*$headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                    $headers .= 'From: ' . $subject . "\r\n";
                    $headers .= 'Cc: support@sharedigitalcard.com' . "\r\n";

                    mail($toEmail, $subject, $message, $headers);*/

                } else {
                    $error = true;
                    $errorMessage .= "Something went wrong!! Please try again later.";
                }
            }
        }
    }
}
$display_message = $manage->displayDealerProfile();
if ($display_message != null) {
    $message_status = $display_message['message_status'];
    $dealer_status = $display_message['status'];
    $pay_status = $display_message['pay_status'];
    $deal_code = $display_message['dealer_code'];
}

?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Create Digital Card</title>
    <?php include "assets/common-includes/header_includes.php" ?>
    <style>
        .sms_registration {
            display: none !important;
        }

        .register_otp {
            display: block !important;
        }
    </style>
</head>
<body>
<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>
<section class="content">

    <?php include 'assets/common-includes/shareUrl.php' ?>
    <?php if (isset($_SESSION['dealer_type']) && $_SESSION['dealer_type'] == "dealer") { ?>
        <div class="col-md-12 up-nav visible-lg visible-md visible-sm hidden-xs">
            <main>
                <div class="page-content" id="applyPage">
                    <ul class="breadcrumbs">
                        <li class="tab-link breadcrumb-item breadcrumb-width-50 active visited">
                            <a href="create_digital_card.php">
                                <span class="number"><i class="fas fa-user"></i></span>
                                <span class="label">Create Digital Card</span>
                            </a>
                        </li>
                        <li class="tab-link breadcrumb-item breadcrumb-width-50 animated infinite pulse" id="crumb5">
                            <a href="#">
                                <span class="number"><i class="fas fa-money-bill-alt"></i></span>
                                <span class="label">Payment</span>
                            </a>
                        </li>
                        <!--<li class="tab-link breadcrumb-item ">
                            <a href="service.php">
                                <span class="number"><i class="far fa-list-alt"></i></span>
                                <span class="label">Services</span>
                            </a>
                        </li>-->
                    </ul>
                </div>
            </main>
        </div>
    <?php
    }
    ?>
    <div class="">
        <div class="col-md-5 hidden-sm col-xs-12 margin_top_div">
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
                        <div class="msg p-0 input-group">
                            <h5>Create Digital Card</h5>

                            <p>In order to create digital card. <b>"Go Paperless, Go
                                    Digital"</b></p>
                        </div>
                        <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>

                            <div class="form-line">
                                <input name="txt_name" type="text" class="form-control" placeholder="Full name"
                                       autofocus
                                       value="<?php if (isset($_POST['txt_name'])) echo $_POST['txt_name']; ?>">
                            </div>
                        </div>
                        <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">email</i>
                        </span>

                            <div class="form-line">
                                <input name="txt_email" type="email" class="form-control" placeholder="Email"
                                       value="<?php if (isset($_POST['txt_email'])) echo $_POST['txt_email']; ?>"
                                       autofocus>
                            </div>
                        </div>
                        <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">phone</i>
                        </span>

                            <div class="form-line">
                                <input type="number" name="txt_contact" class="form-control"
                                       placeholder="Contact Number"
                                       value="<?php if (isset($_POST['txt_contact'])) echo $_POST['txt_contact']; ?>"
                                       autofocus onkeypress="return isNumberKey(event)" required="required">
                            </div>
                        </div>
                        <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">home</i>
                        </span>

                            <div class="form-line">
                                <input name="txt_company_name" type="text" class="form-control"
                                       placeholder="Company Name(Optional)"
                                       autofocus
                                       value="<?php if (isset($_POST['txt_company_name'])) echo $_POST['txt_company_name']; ?>">
                            </div>
                        </div>

                        <div class="input-group">
                         <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>

                            <div class="form-group form-float">
                                <div class="form-line">
                                    <select id="gender" name="gender" class="form-control">
                                        <option name="male" value="Male">Male
                                        </option>
                                        <option name="female" value="Female">Female
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="input-group">
                         <span class="input-group-addon">
                          <i class="fa fa-globe" style="font-size: 18px;"></i>
                        </span>

                            <div class="form-group form-float">
                                <div class="form-line" style="z-index: 999">
                                    <select id="country" name="country" class="form-control" data-live-search="true"
                                            onchange="getStateDataByCountry(this.value)">
                                        <option value="">select country</option>
                                        <?php
                                        $countries_array = $manage->getCountryCategory();
                                        while ($value = mysqli_fetch_array($countries_array)) {
                                            ?>
                                            <option
                                                value="<?php echo $value['id']; ?>" <?php if ($countryName == $value['name']) {
                                                echo "selected";
                                            } ?>><?php echo $value['name']; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="input-group">
                         <span class="input-group-addon">
                          <i class="fa fa-globe" style="font-size: 18px;"></i>
                        </span>

                            <div class="form-group form-float">
                                <div class="form-line" style="z-index: 99">
                                    <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                    <!--<input type="text" name="txt_state"
                                                                           class="form-control"
                                                                           placeholder="Enter State"
                                                                           value="<?php /*if (isset($state) && $state !=""){ echo $state; }else{ echo $current_region; } */ ?>">-->
                                    <div id="state_select">
                                        <select name="txt_state"
                                                class="gender_li form-control"
                                                onchange="getCityByStateId(this.value)">
                                            <option value="">Select state</option>

                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="input-group">
                         <span class="input-group-addon">
                          <i class="fa fa-globe" style="font-size: 18px;"></i>
                        </span>

                            <div class="form-group form-float">
                                <div class="form-line" style="z-index: 9">
                                    <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                    <!--<input type="text" name="txt_city"
                                                                           class="form-control"
                                                                           placeholder="Enter City"
                                                                           value="<?php /*if (isset($city) && $city !=""){ echo $city; }else{ echo $current_city; } */ ?>">-->
                                    <div id="city_select">
                                        <select name="txt_city" data-live-search="true"
                                                class="gender_li form-control">
                                            <option value="">Select city</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="input-group">
                            <input type="checkbox" name="online_search" value="1"> Do You Want to promote your business
                            online.
                        </div>
                        <!--  <input readonly type="hidden" name="txt_custom_url" class="form-control"
                               placeholder="Custom Url"
                               autofocus value="<?php /*echo $random; */ ?>">-->
                        <input name="txt_password" type="hidden" value="<?php echo $random_password; ?>"
                               class="form-control">

                        <div class="form-group">
                            <p>Note: Once digital card created, We will send credentials on Email and
                                SMS.</p>
                        </div>

                        <div>
                            <button type="submit" class="btn btn-primary" name="btn_submit" id="myButton">Create Digital
                                Card
                            </button>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <button type="submit" class="btn btn-default" name="btn_cancel">cancel
                            </button>
                        </div>
                    </form>
                </div>
                <div class="body sms_registration">
                    <form id="register_number" method="POST">
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
                            <h5>Create Digital Card</h5>

                            <p>In order to create digital card. <b>"Go Paperless, Go
                                    Digital"</b></p>
                        </div>
                        <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="material-icons">phone</i>
                                </span>

                            <div class="form-line">
                                <input type="number" name="sms_contact" class="form-control"
                                       value="<?php if (isset($_SESSION['user_contact'])) echo $_SESSION['user_contact']; ?>"
                                       placeholder="Contact Number"
                                       autofocus <?php if (isset($_SESSION['user_contact'])) {
                                    echo "disabled";
                                } ?> autocomplete="off" onkeypress="return isNumberKey(event)" required="required">
                            </div>
                        </div>
                        <?php
                        if (isset($_SESSION['user_contact'])) {
                            ?>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="material-icons">lock</i>
                                </span>

                                <div class="form-line">
                                    <input type="password" name="sms_otp" class="form-control" placeholder="OTP"
                                           autofocus>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                        <?php
                        if (isset($_SESSION['user_contact'])) {
                            ?>
                            <div>
                                <button type="submit" class="btn btn-block bg-pink waves-effect" name="verify_otp">
                                    Verify
                                    OTP
                                </button>
                                <button type="submit" class="resend_otp" name="resend_otp">Resend Otp</button>
                                <button type="submit" style="float: left" class="resend_otp" name="btn_cancel">cancel
                                </button>
                                <br>
                            </div>
                        <?php
                        } else {
                            ?>
                            <button type="submit" class="btn btn-block bg-pink waves-effect" name="send_otp">Send
                                OTP
                            </button>
                        <?php
                        }
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>


<script type="text/javascript">
    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode != 46 && (charCode < 48 || charCode > 57)))
            return false;
        return true;
    }
</script>

<script>

    function getStateDataByCountry(value) {
        var dataString = 'country_id=' + value;
        if (value != '') {
            $.ajax({
                url: "get_city_ajax.php",
                type: "POST",
                data: dataString,
                success: function (html) {
                    $('#state_select').html(html);
                    getCityByStateId($('select[name=txt_city]').val());
                }
            });
        } else {
            $('#state_select').html(' <select name="txt_city" class="form-control"><option value="">select an option</option></select>');
        }
    }
    function getCityByStateId(value) {

        var dataString = 'state_id=' + value;
        if (value != '') {
            $.ajax({
                url: "get_city_ajax.php",
                type: "POST",
                data: dataString,
                success: function (html) {
                    $('#city_select').html(html);
                }
            });
        } else {
            $('#city_select').html(' <select name="txt_city" class="form-control"><option value="">select an option</option></select>');
        }
    }

</script>

<?php include "assets/common-includes/footer_includes.php" ?>
<script>
    <?php
  if (isset($countryName) && $countryName !=''){
  ?>
    $(document).ready(function () {
        var country_value = $('#country').val();
        getStateDataByCountry(country_value);
    });
    <?php
    }
    ?>
</script>
</body>
</html>