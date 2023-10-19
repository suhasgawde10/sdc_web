<?php
ob_start();
include "../controller/ManageDealer.php";
$manage = new ManageDealer();
include "../controller/validator.php";
$validate = new Validator();
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include '../sendMail/sendMail.php';

if (!isset($_SESSION['dealer_email'])) {
    header('location:../dealer-register.php');
}
require_once("functions.php");
$maxsize = 10485760;

$error = false;
$errorMessage = "";

$id = 0;

if(!isset($_GET['user_id']) && $_GET['user_id']!=""){
    header('location:dashboard.php');
}


if(isset($_POST['btn_submit'])){
    if(isset($_POST['drp_new_year']) && $_POST['drp_new_year'] !=''){
        $_SESSION['new_year'] = $_POST['drp_new_year'];
        header('location:plan-selection-page-2.php?user_id='.$_GET['user_id']);
    }
}

include("session_includes.php");

$display_message = $manage->displayDealerProfile();
if ($display_message != null) {
    $message_status = $display_message['message_status'];
    $dealer_status = $display_message['status'];
    $pay_status = $display_message['pay_status'];
    $deal_code = $display_message['dealer_code'];
    $get_percent = $display_message['dealer_percent'];
    $get_prcent_data = $manage->getDealerPricingById($get_percent);
    $dealer_percent = $get_prcent_data['percentage'];
    $dealer_gstn_no = $display_message['gstin_no'];
    $dealer_company = $display_message['c_name'];
}

$orderDescription = "Plan"; //your script should substitute detailed description of your order here ( This field is not mandatory )
$country = "IN";//your script should substitute the customer's country code
$TMPL_CURRENCY = "INR";//your script should substitute the currency symbol in which you want to display amount
$currency = "INR";//your script should substitute the currency symbol in which you want to display amount
$city = "";//your script should substitute the customer's city
$state = "";//your script should substitute the customer's state
$postcode = "";//your script should substitute the customer's zip
$telnocc = "091";//your script should substitute the customer's contry code for tel no
$ip = "127.0.0.1"; // your script should replace it with your ip address
$reservedField1 = ""; //As of now this field is reserved and you need not put anything
$reservedField2 = ""; //As of now this field is reserved and you need not put anything
$terminalid = "";   //terminalid if provided
$paymentMode = ""; //payment type as applicable Credit Cards = CC, Vouchers = PV,  Ewallet = EW, NetBanking = NB
$paymentBrand = ""; //card type as applicable Visa = VISA; MasterCard=MC; Dinners= DINER; Amex= AMEX; Disc= DISC; CUP=CUP
$customerId = "";

/*$processUrl = "https://sandbox.paymentz.com/transaction/Checkout";*/
$processUrl = "https://secure.paymentz.in/transaction/Checkout";
$liveurl = "https://secure.live.com/transaction/PayProcessController";

if (isset($_POST['pay_now'])) {
    $get_user_data = $manage->getUserData($security->decrypt($_GET['user_id']));
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
    date_add($date, date_interval_create_from_date_string("5 days"));
    $final_date = date_format($date, "Y-m-d");
    if ($update_user_count == 0) {
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
        $insertUserSubscription = $manage->insertUserData($security->decrypt($_GET['user_id']),1, $year, $amount, $date1, $final_date, $status, $active_plan, $invoice_no, $tax, $amount, $paymentBrand, $paymentMode, $custBankId, $timestamp,$dealer_gstn_no);
        if ($insertUserSubscription) {
            $updateUserExpiry = $manage->updateUserExpiryDate($security->decrypt($_GET['user_id']), $final_date);
            if ($updateUserExpiry) {
                if ($get_email_count == "0") {
                    $toName = $u_name;
                    $toEmail = $u_email;
                    $subject = "Profile Updated Successfully";
                    $email_message = '<!DOCTYPE html><html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Trail Expiration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <style>

        body {
            margin: 0;
            padding: 0;
        }

        .main {
            width: 70%;
            margin: 0 auto;
            box-shadow: 0 0px 5px 5px #ccc;
        }

        .cust-name
        {
            color:blue;
        }

        .content {
            width: 80%;
            margin: 0 auto;
        }

        .thanks-msg {
            background-image: url("https://image.freepik.com/free-vector/colorful-abstract-geometric-backdrop_1023-44.jpg");
            width: 100%;
            height: auto;
            background-repeat: no-repeat;
            background-size: cover;
            position: relative;
        }

        .overlay {
            position: absolute;
            top: 0;
            bottom: 0;
            right: 0;
            left: 0;
            background-color: rgba(86, 86, 86, 0.4);

        }

        .msg {
            text-align: center;
            color: white;
            /* font-size: 20px; */
            font-weight: 700;
            margin: 10px 0;
            padding-top: 4%;
        }

        .msg h1 {
            font-size: 24px;
            margin: 0;
        }

        .msg p {
            font-size: 15px;
        }

        .icon {
            text-align: center;
            color: #c4a758;
            width: 80px;
            margin: 1px auto;
            background: white;
            border-radius: 50%;
            height: 80px;
            text-align: center;
            padding: 5px;
        }

        .icon img {
            width: 100%;
        }

        .user-name-logo h3 {
            text-align: center;
            font-size: 20px;
            font-weight: 700;
            text-decoration: underline;
            text-transform: uppercase;
            color: ghostwhite;

        }

        .email-id {
            font-size: 20px;
            font-weight: bold;
            color: #0c2edc;
        }

        .about-content p {
            font-size: 18px;
            line-height: 25px;
        }

        .details-step {
            vertical-align: middle;
            font-size: 12px;
            line-height: 34px;
            font-weight: bold;
            list-style-type: decimal;
            width: 100%;
            padding: 0;
            margin: 0;
        }

        .details-step li {
            vertical-align: middle;
            position: relative;
            width: 49%;
            display: inline-block;
            margin-bottom: 15px;
        }

        .step {
            background: #eae8e8;
            border-radius: 10px;
            font-size: 15px;
            padding: 5px;
        }

        .about-content span {
            color: #002bd0;
            font-weight: 600;
        }

        .about-content a {
            color: #002bd0;
            text-decoration: none;
        }

        .about-content a:hover {

            font-weight: bold;
            transform: scale(1.2);
        }

        .btn {
            padding: 1em 2.1em 1.1em;
            border-radius: 3px;
            margin: 8px 8px 20px 8px;
            color: #fbdedb;
            background-color: #fbdedb;
            display: inline-block;
            background: #e74c3c;
            -webkit-transition: 0.3s;
            -moz-transition: 0.3s;
            -o-transition: 0.3s;
            transition: 0.3s;
            font-family: sans-serif;
            font-weight: 800;
            font-size: .85em;
            text-transform: uppercase;
            text-align: center;
            text-decoration: none;
            -webkit-box-shadow: 0em -0.3rem 0em rgba(0, 0, 0, 0.1) inset;
            -moz-box-shadow: 0em -0.3rem 0em rgba(0, 0, 0, 0.1) inset;
            box-shadow: 0em -0.3rem 0em rgba(0, 0, 0, 0.1) inset;
            position: relative;
        }

        .btn:active {
            -webkit-transform: scale(0.80);
            -moz-transform: scale(0.80);
            -ms-transform: scale(0.80);
            -o-transform: scale(0.80);
            transform: scale(0.80);
        }

        .btn.block {
            display: block !important;
        }

        .btn.circular {
            border-radius: 50em !important;
        }

        .button-login {
            text-align: center;
        }

        .padding-sec {
            padding: 25px;
        }

        .se-date {
            color: #fb3737 !important;
            font-weight: 600;
        }

        .icon img {
            width: 100%;
        }

        .social-icon {
            width: 100%;
            padding: 0;
            margin: 0;
            vertical-align: top;
            position: relative;
            overflow: hidden;
            text-align: center;

        }

        .social-icon li {
            width: 8%;
            padding: 0;
            margin: 0;
            vertical-align: top;
            position: relative;
            display: inline-block;
        }

        .add {
            width: 100%;
            margin: 0 auto;
        }

        .add-details {
            /* width: 100%; */
            margin: 10px 0;
        }

        .logo {
            width: 15%;
            margin: 0 auto;
        }

        .logo img {
            width: 100%;
        }

        .addres {
            width: 100%;
            margin: 0 auto;
            font-size: 12px;

        }

        .footer-top {
            padding: 10px;
            background: #e6e6e6;
            height: 115px;
        }

        .text-center {
            text-align: center;
        }

        .footer-btm {
            padding: 10px;
            background: #e6e6e6;
        }

        .footer-btm a {
            text-decoration: none;
        }

        .content-foot {
            width: 85%;
            margin: 0 auto;
        }
        .user-name-logo{
        padding-bottom: 1%;
        }
        @media (max-width: 991px) {
        .addres p{
         margin: 0;
         padding-bottom: 10px;
         }
         .button-login p{
         padding-bottom: 10px;
         }
         .main
        {
            width: 100%;
            }
            .padding-sec {
    padding: 10px;
}
    .otp-inner {
    width: 76%;
    padding: 10px;
    }

    .social-icon li{
    width: 8%;
    }
    .add-details {
     margin: 0;
}
.logo {
    width: 104px;
    margin: 0 auto;
}
.footer-top {
    padding: 10px 10px 0 10px;
    height: auto}

        }
        @media (max-width: 480px) {
         .addres p{
         margin: 0;
         padding-bottom: 10px;
         }
         .button-login p{
         padding-bottom: 10px;
         }
         .main
        {
            width: 100%;
            }
            .padding-sec {
            padding: 10px;
              }
              .otp-inner {
    width: 76%;
    padding: 10px;
    }

    .social-icon li{
    width: 14%;
    }
    .add-details {
     margin: 0;
}
.logo {
    width: 104px;
    margin: 0 auto;
}
.footer-top {
    padding: 10px 10px 0 10px;
    height: auto;}
        }
        @media (max-width: 360px) {
         .addres p{
         margin: 0;
         padding-bottom: 10px;
         }
         .button-login p{
         padding-bottom: 10px;
         }
         .main
        {
            width: 100%;
            }
            .padding-sec {
            padding: 10px;
              }
              .otp-inner {
    width: 76%;
    padding: 10px;
    }

    .social-icon li{
    width: 14%;
    }
    .add-details {
     margin: 0;
}
.logo {
    width: 104px;
    margin: 0 auto;
}
.footer-top {
    padding: 10px 10px 0 10px;
    height: auto;
    }

        }
         @media (max-width: 320px) {
         .addres p{
         margin: 0;
         padding-bottom: 10px;
         }
         .button-login p{
         padding-bottom: 10px;
         }
         .main
        {
            width: 100%;
            }
            .padding-sec {
            padding: 10px;
              }
              .otp-inner {
    width: 76%;
    padding: 10px;
    }

    .social-icon li{
    width: 14%;
    }
    .add-details {
     margin: 0;
}
.logo {
    width: 104px;
    margin: 0 auto;
}
.footer-top {
    padding: 10px 10px 0 10px;
    height: auto;
    }

        }


    </style>


</head>
<body>

<section class="padding-sec">
    <section class="main">
        <section class="thanks-msg">
            <div class="overlay">
                <div class="content">
                    <div class="msg">
                        <h1>Share Digital Card</h1>

                        <p>Trail Period Expiration</p>
                    </div>
                    <div class="user-name-logo">
                        <div class="icon">
                            <img src="https://sharedigitalcard.com/user/assets/images/clock.png">
                        </div>
                        <!--<h3>Sachin Pangam</h3>-->
                    </div>
                </div>
            </div>
        </section>
        <section class="about">
            <div class="content">
                <div class="about-content">
                    <p> Dear <span class="cust-name">' . ucwords($u_name) . '</span>,</p>

                    <p>
                        This email regarding the expiration of <span class="payment"> 5 Days </span> trail period of
                        share digital.
                    </p>

                    <p>
                        Staring Date From <span class="se-date">' . $date1 . '</span> To Ending date <span
                            class="se-date">' . $final_date . '</span>.</p>


                    <p>
                        To do any changes in " Digital Card " click on to below button. And for any query email us on
                        <a href="mailto:support@sharedigitalcard.com" class="payment">support@sharedigitalcard.com</a>
                    </p>
                </div>
               <div class="button-login">
                    <a href="http://sharedigitalcard.com/login.php" class="btn orange circular">Click To Login</a>
                </div>
            </div>
        </section>
        <section class="footer-top">
            <div class="content-foot">
                <div class="add">
                    <div class="social">
                        <ul class="social-icon">
                           <li><img src="http://sharedigitalcard.com/user/assets/images/fb.png"> </li>
                            <li><img src="http://sharedigitalcard.com/user/assets/images/insta.png"></li>
                            <li><img src="http://sharedigitalcard.com/user/assets/images/yt.png"></li>
                            <li><img src="http://sharedigitalcard.com/user/assets/images/pin.png"></li>
                        </ul>
                    </div>
                </div>

            </div>

        </section>
    </section>
</section>

</body>
</html>';
                    $sms_mail_message = "Dear " . $u_name . ",\n";
                    $sms_mail_message .= "Your Digital Card is ready\n";
                    $sms_mail_message .= "Never Stop Sharing!!! :)\n";
                    $sms_mail_message .= "Please click on below link to open.\n";
                    $sms_mail_message .= SHARED_URL. $custom_url;
                    $update_email_count = $manage->update_user_email_count($security->decrypt($_GET['user_id']));
                    $send_sms = $manage->sendSMS($u_contact, $sms_mail_message);
                    header('location:dashboard.php');
                } else {
                    header('location:dashboard.php');
                }
            }
        }
    } else {
        $error = true;
        $errorMessage = "you have already availed this offer";
    }
}
$get_user_data = $manage->getUserData($security->decrypt($_GET['user_id']));
if ($get_user_data != null) {
    $update_user_count = $get_user_data['update_user_count'];
}
if($update_user_count == 0){
    $sub_plan = $manage->subscriptionPlanForTrial();
}else{
    $sub_plan = $manage->subscriptionPlan();
}
function fetch_all_data($result)
{
    $all = array();
    while($thing = mysqli_fetch_array($result)) {
        $all[] = $thing;
    }
    return $all;
}
$get_data = fetch_all_data($sub_plan);
?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>plan selection</title>
    <style>
        table{
            margin: 20px 0;
            width: 100%;
        }
        table tbody tr td:nth-child(1){
            text-align: left;
        }
        #get_amount1 table {
            display: none;
        }

        input[name="payment_type"]:not(:checked), input[name="payment_type"]:checked {
            position: unset;
            opacity: 1;
        }
        .pricing_include_ul {
            width: 100%;
            display: inline-block;
            vertical-align: top;
            text-align: center;
        }

        .pricing_include_ul li.price {
            width: 19%;
            display: inline-block;
            vertical-align: top;
            margin-right: 5px;
        }

        .pricing_include_ul li.price:first-child {
            margin-right: 40px;
        }
        .pricing_include_ul li.price {
            width: 32%;
        }

        .dropdown_custom {
            width: 100%;
            padding: 20px;
            background-color: white;
            box-shadow:0 8px 10px 0 rgb(0 0 0 / 13%);
            font-family: 'Lato', sans-serif;
            height:auto;
        }

        .dropdown__switch:checked + .dropdown__options-filter .dropdown__select {
            transform: scaleY(1);
        }
        .dropdown__switch:checked + .dropdown__options-filter .dropdown__filter:after {
            transform: rotate(-135deg);
        }
        .dropdown__options-filter {
            width: 100%;
            cursor: pointer;
        }
        .dropdown__filter {
            position: relative;
            width: 100%;
            display: block;
            padding-left: 0 !important;
            color: #595959;
            background-color: #fff;
            border: 1px solid #d6d6d6;
            border-radius: 0px;
            font-size: 14px;
            text-transform: uppercase;
            transition: .3s;
            margin-top: 0 !important;
        }
        .dropdown__filter:focus {
            border: 1px solid #918FF4;
            outline: none;
            box-shadow: 0 0 5px 3px #918FF4;
        }
        .dropdown__filter::after {
            position: absolute;
            top: 45%;
            right: 20px;
            content: '';
            width: 10px;
            height: 10px;
            border-right: 2px solid #595959;
            border-bottom: 2px solid #595959;
            transform: rotate(45deg) translateX(-45%);
            transition: .2s ease-in-out;
        }
        .dropdown__select {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            margin-top: 5px;
            overflow: hidden;
            box-shadow: 0 5px 10px 0 rgba(152, 152, 152, 0.6);
            transform: scaleY(0);
            transform-origin: top;
            font-weight: 300;
            transition: .2s ease-in-out;
            background: white;
            padding-left: 0 !important;
        }
        .dropdown__filter-selected{
            margin-top: 0 !important;
        }
        .dropdown__select-option {
            padding: 10px;
            background-color: #fff;
            border-bottom: 1px solid #d6d6d6;
            transition: .3s;
            margin-top: 0 !important;
        }
        .dropdown__select-option:last-of-type {
            border-bottom: 0;
        }
        .dropdown__select-option:hover {
            background-color: #f9f9f9;
        }

        .single-price button.pricing_btn{
            display: none;
            width: 100%;
            padding: 10px;
            color: white;
        }.single-price button.pricing_btn i{
             font-size: 16px;
             margin-left: 6px;
             font-weight: 600;
         }
        .single-price{
            padding-bottom: 0;
        }
        .single-price {
            height: auto;
            border-radius: 5px;
            -webkit-box-shadow: 0 3px 10px rgb(0 0 0 / 16%);
            box-shadow: 0 3px 10px rgb(0 0 0 / 16%);
            text-align: center;
            -webkit-transition: all .3s ease 0s;
            -o-transition: all .3s ease 0s;
            transition: all .3s ease 0s;
            background-color: #fff
        }

        .single-price:hover {
            -webkit-box-shadow: 0 3px 10px rgba(0, 0, 0, .1);
            box-shadow: 0 3px 10px rgba(0, 0, 0, .1)
        }

        .prc-head {
            background: #2793e6;
            text-align: center;
            padding: 2px;
            color: white;
        }

        .prc-head span {
            font-size: 18px;
            font-weight: 500;
            color: #fff;
            letter-spacing: 0;
            margin-bottom: 0;
            display: block
        }

        .prc-head h5 {
            font-size: 30px;
            color: #fff;
            letter-spacing: 0;
            font-weight: 500;
            line-height: 53px
        }

        .prc-head h5 small {
            color: #fff
        }

        .prc-head-drp {
            text-align: center;
        }

        .prc-head-drp span {
            font-size: 18px;
            font-weight: 500;
            color: #999;
            letter-spacing: 0;
            margin-bottom: 0;
            display: block
        }

        .prc-head-drp h6 {
            color: red;
            margin-bottom: 0;
            font-weight: 500;
            padding-top: 5px;
        }
        .prc-head-drp h5 {
            font-size: 23px;
            color: #666;
            letter-spacing: 0;
            font-weight: 500;
            margin-bottom: 0;
            line-height: 23px
        }

        .prc-head h5 small {
            color: #333
        }

        .single-price ul {
            text-align: left;
            margin-top: 10px;
            padding-left: 15px;
        }

        .single-price ul li {
            font-weight: 400;
            font-size: 14px;
            color: #666;
            line-height: 8px;
            margin-top: 8px;
            letter-spacing: 0;
            display: inline-block;
            width: 100%;
        }
        .single-price button.pricing_btn {
            font-size: 18px;
            font-weight: 400;
            letter-spacing: 0;
            color: #fff;
            border: 1px solid #2793e6;
            padding: 10px 21px;
            border-radius: 3px;
            display: none;
            margin-top: 10px
        }
        .single-price a:hover {
            color: #fff;
            background-color: #2793e6
        }
        [type="checkbox"] + label:before, [type="checkbox"]:not(.filled-in) + label:after{
            opacity: 0;
        }
        s{
            font-size: 15px;
        }
        .dropdown__options-filter{
            padding-left: 0 !important;
        }
    </style>
    <?php include "assets/common-includes/header_includes.php" ?>
</head>
<body onload="user_dealer_code('1 year');">
<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>
<section class="content">
        <div class="clearfix">
           <div class="col-md-12">
               <form method="post" action="">
               <ul class="pricing_include_ul">
                   <li class="price">
                       <div class="single-price">
                           <div class="prc-head">
                               <h6>Digital Card</h6>
                           </div>

                           <input type="hidden"  name="drp_new_year" value="1 year" />
                           <div class="dropdown_custom">
                               <input type="checkbox" class="dropdown__switch" id="filter-switch1" hidden />

                               <label for="filter-switch1" class="dropdown__options-filter">
                                   <ul class="dropdown__filter" role="listbox" tabindex="-1">
                                       <li class="dropdown__filter-selected" aria-selected="true">
                                           <div class="prc-head-drp">
                                               <?php
                                               $i = 1;
                                               foreach($get_data as $key) {
                                                   if ($key['amt'] != '') {
                                                       $new_amount = $dealer_percent * $key['amt'] / 100;
                                                       $new_amount = $key['amt'] - $new_amount;
                                                       $new_amount = round($new_amount);
                                                       ?>
                                                       <h6><?php echo $key['year']; ?></h6>
                                                       <h5>&#8377;<?php echo $new_amount; ?>
                                                       </h5>
                                                       <?php
                                                       break;
                                                   }
                                               }
                                               ?>
                                           </div>
                                       </li>
                                       <li>
                                           <?php
                                           if ($sub_plan != null) {
                                               ?>
                                               <ul class="dropdown__select">
                                                   <?php
                                                   foreach($get_data as $row_data){
                                                       if($row_data['amt'] !=''){
                                                           $new_amount = $dealer_percent * $row_data['amt']/100 ;
                                                           $new_amount = $row_data['amt'] - $new_amount;
                                                           $new_amount = round($new_amount);
                                                       }else{
                                                           $new_amount = 0;
                                                       }
                                                       ?>
                                                       <li class="dropdown__select-option" role="option" data-year="<?php echo $row_data['year']; ?>">
                                                           <div class="prc-head-drp">

                                                               <h6><?php echo $row_data['year']; ?></h6>
                                                               <h5>&#8377;<?php echo $new_amount ?>
                                                               </h5>
                                                           </div>
                                                       </li>
                                                       <?php
                                                   }
                                                   ?>
                                               </ul>
                                               <?php
                                           }
                                           ?>
                                       </li>
                                   </ul>
                               </label>
                           </div>
                      <div>
                          <table class="table table-borderless get_amount">
                              <tbody></tbody>
                          </table>
                      <div class="pt-10">
                          <button name="btn_submit" type="submit" class="btn btn-success pricing_btn form"></button>
                      </div>
                      </div>

                       </div>
                   </li>
               </ul>
               </form>
           </div>
        </div>
    </section>


    <script>
        // Change option selected
        const label = document.querySelector('.dropdown__filter-selected');
        const drpoptions = Array.from(document.querySelectorAll('.dropdown__select-option'));
        const plan_amount = document.querySelector('input[name=drp_new_year]');
        drpoptions.forEach(option => {
            option.addEventListener('click', () => {
                label.innerHTML = option.innerHTML;
                plan_amount.value = option.getAttribute('data-year');
        user_dealer_code(option.getAttribute('data-year'));
            });
        });

        // Close dropdown onclick outside
        document.addEventListener('click', e => {
            const toggle = document.querySelector('.dropdown__switch');
            const element = e.target;

            if (element == toggle) return;

            const isDropdownChild = element.closest('.dropdown__filter');

            if (!isDropdownChild) {
                toggle.checked = false;
            }
        });
    </script>
<script>


    function update_company_info(){
        var valid = false;
        var company_name = $('input[name=company_name]').val();
        var gst_no = $('input[name=txt_gst_no]').val();
        if(company_name.trim() ==''){
            $('.alert-danger').show().text('Enter Company Name\n');
            valid = true;
        }
        if(gst_no.trim() ==''){
            $('.alert-danger').show().text('Enter Gst No\n');
            valid = true;
        }
        if(!valid) {
            var dataString = "updatate_company="+encodeURIComponent(company_name)+"&gst_no="+encodeURIComponent(gst_no);
            $.ajax({
                type: "POST",
                url: "get_radio_value.php", // Name of the php files
                data: dataString,
                beforeSend: function() {
                    // setting a timeout
                    $('#update_company_info').text('Saving...').attr('disabled','disabled');
                },
                success: function (html) {
                    if(html.trim() == 1){
                        $('.alert-danger').hide();
                        $('.alert-success').show().text('Company details saved successfully.\n');
                        /*
                         $("#user_company_info").modal("hide");
                         $('#add_gst_no').hide();*/
                        $('#update_company_info').text('Save details').removeAttr('disabled')
                    }else {
                        $('.alert-danger').show().text('Issue while updating please try after some time.');
                        $('#update_company_info').text('Save details').removeAttr('disabled')
                    }

                }
            });
        }
    }
    function user_dealer_code(val) {
        var dealer_code = <?php echo "'". $deal_code ."'" ; ?>;
        var dataString = "dealer_code=" + dealer_code + "&razor_pay=true"+"&year=" + encodeURIComponent(val) + "&user_id=" + <?php echo $security->decrypt($_GET['user_id']) ?>;
        $.ajax({
            type: "POST",
            url: "get_radio_value.php", // Name of the php files
            data: dataString,
            dataType:"json",
            success: function (result) {
                if(result.user_plan_status == 'trial'){
                    $('.pricing_btn').text('CLICK HERE TO GET FREE').attr({'onclick':'window.location.href="free-trial.php?user_id=<?php echo $_GET['user_id']; ?>"','type':'button'}).show();
                    $(".get_amount tbody").html(result.data);
                    /*return false*/
                }else{
                    $('.pricing_btn').text('Pay Now '+result.current_amount+'/-').show().removeAttr('onclick').attr('type','submit');
                    $(".get_amount tbody").html(result.data);
                    /*return false*/
                }
            }
        });

    }
</script>

    <?php include "assets/common-includes/footer_includes.php" ?>

</body>
</html>