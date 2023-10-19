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

include("session_includes.php");

$display_message = $manage->displayDealerProfile();
if ($display_message != null) {
    $message_status = $display_message['message_status'];
      $dealer_status = $display_message['status'];     $pay_status = $display_message['pay_status'];
    $deal_code = $display_message['dealer_code'];
    $get_percent = $display_message['dealer_percent'];
    $get_prcent_data = $manage->getDealerPricingById($get_percent);
    $dealer_percent = $get_prcent_data['percentage'];
    $dealer_gstn_no = $display_message['gstin_no'];
    $dealer_company = $display_message['c_name'];
}

$form_data = $manage->getSpecificUserProfile($security->decrypt($_GET['user_id']));
if ($form_data != null) {
    $street = $form_data['address'];

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
        $insertUserSubscription = $manage->insertUserData($security->decrypt($_GET['user_id']), $year, $amount, $date1, $final_date, $status, $active_plan, $invoice_no, $tax, $amount, $paymentBrand, $paymentMode, $custBankId, $timestamp,$dealer_gstn_no);
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
        <section class="footer-btm">
            <div class="content text-center">
                <a href="https://kubictechnology.com/" target="_blank"
                   title="Kubic Technology Website Development Company In Mumbai">
                    Kubic Technology Website Development Company In Mumbai
                </a>
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

?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>plan selection</title>
    <?php include "assets/common-includes/header_includes.php" ?>
</head>
<body onload="user_dealer_code()">
<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>
<section class="content">

    <!-- <div class="up-nav visible-lg visible-md visible-sm hidden-xs">
         <main>
             <div class="page-content" id="applyPage">
                 <ul class="breadcrumbs">
                     <li class="tab-link breadcrumb-item">
                         <a href="create_digital_card.php">
                             <span class="number"><i class="fas fa-user"></i></span>
                             <span class="label">Create Digital Card</span>
                         </a>
                     </li>
                     <li class="tab-link breadcrumb-item active visited" id="crumb5">
                         <a href="payment.php">
                             <span class="number"><i class="fas fa-money-bill-alt"></i></span>
                             <span class="label">Payment</span>
                         </a>
                     </li>
                 </ul>
             </div>
         </main>
     </div>-->
    <div class="clearfix">
    <?php
    $get_user_data = $manage->getUserData($security->decrypt($_GET['user_id']));
    if ($get_user_data != null) {
        $update_user_count = $get_user_data['update_user_count'];
    }
    if($update_user_count == 0){
        $sub_plan = $manage->subscriptionPlanForTrial();
    }else{
        $sub_plan = $manage->subscriptionPlan();
    }
?>
        <div class="col-lg-8 col-md-6 col-sm-6 col-xs-12 padding_zero padding_zero_both">
                <?php
                if ($sub_plan != null) {
                    ?>
                    <ul class="ul_subcription_list">
                        <?php
                        while ($row_data = mysqli_fetch_array($sub_plan)) {
                            ?>
                            <li>
                                <div class="container_k">
                                    <div class="content_k">
                                        <div class="row">
                                            <div class="col-md-8 col-xs-7 text-left">
                                                <div class="row">
                                                    <label class="radio_plan"><?php echo $row_data['year']; ?>
                                                        <input onclick="user_dealer_code()" type="radio"
                                                               name="rd_sub_plan"
                                                               value="<?php echo $row_data['year']; ?>" <?php if ($row_data['year'] == '1 year') echo "checked" ?>>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-md-4 col-xs-5 text-left">
                                                <input type="hidden" value="<?php echo $row_data['year']; ?>">
                                                <input type="hidden" value="<?php
                                                if ($row_data['amt'] != null)
                                                    echo "Rs: " . $row_data['amt']; ?>">
                                                <?php
                                                if ($row_data['year'] != 'Free Trail (5 days)') {
                                                    ?>
                                                    <h4 class="text-right"><b><?php
                                                            $new_amount = $dealer_percent * $row_data['amt']/100 ;
                                                            $new_amount = $row_data['amt'] - $new_amount;
                                                            $new_amount = round($new_amount);
                                                            echo "Rs: " . $new_amount;
                                                            ?></b></h4>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php
                        }
                        ?>
                    </ul>
                <?php
                }
                ?>
            </div>
    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
        <div class="row">
            <div class="card">
                <div class="body">
                    <form name="frm1" method="post" action="<?php echo $processUrl; ?>">
                        <?php if ($error) {
                            ?>
                            <div class="alert alert-danger">
                                <a href="#" class="close" data-dismiss="alert"
                                   aria-label="close">&times;</a>
                                <?php if (isset($errorMessage)) echo $errorMessage; ?>
                            </div>
                            <?php
                        } else if (!$error && $errorMessage != "") {
                            ?>
                            <div class="alert alert-success">
                                <a href="#" class="close" data-dismiss="alert"
                                   aria-label="close">&times;</a>
                                <?php if (isset($errorMessage)) echo $errorMessage; ?>
                            </div>
                            <?php
                        }
                        ?>
                        <table class="table table-borderless get_amount">
                            <tr class="">
                                <td>Plan Name :</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Tax (18%) :</td>
                                <td></td>
                            </tr>
                        </table>
                        <input type="hidden" name="orderDescription" value="<?php echo $orderDescription; ?>">
                        <input type="hidden" name="ip" value="<?php echo $ip; ?>">
                        <input type="hidden" name="reservedField1" value="<?php echo $reservedField1; ?>">
                        <input type="hidden" name="reservedField2" value="<?php echo $reservedField2; ?>">
                        <input type="hidden" name="country" value="<?php echo $country; ?>">
                        <input type="hidden" name="currency" value="<?php echo $currency; ?>">
                        <input type="hidden" name="TMPL_CURRENCY" value="<?php echo $TMPL_CURRENCY; ?>">
                        <input type="hidden" name="city" value="<?php echo $city; ?>">
                        <input type="hidden" name="state" value="<?php echo $state; ?>">
                        <input type="hidden" name="street" value="<?php echo substr($display_message['address'],0,90); ?>">
                        <input type="hidden" name="postcode" value="<?php echo $postcode; ?>">
                        <input type="hidden" name="phone" value="<?php echo $_SESSION['dealer_contact']; ?>">
                        <input type="hidden" name="telnocc" value="<?php echo $telnocc; ?>">
                        <input type="hidden" name="email" value="<?php echo $_SESSION['dealer_email']; ?>">
                        <input type="hidden" name="terminalid" value="<?php echo $terminalid; ?>">
                        <input type="hidden" name="paymentMode" value="<?php echo $paymentMode; ?>">
                        <input type="hidden" name="paymentBrand" value="<?php echo $paymentBrand; ?>">
                        <input type="hidden" name="customerId" value="<?php echo $form_data['user_id']; ?>">
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>

</section>

<div class="modal fade" id="user_company_info" role="dialog">
    <div class="modal-dialog cust-model-width">
        <div class="modal-content">
            <div class="modal-header cust-upi-madal">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Update Company Info</h4>
            </div>
            <div class="modal-body">
                <div class="body">
                    <div class="alert alert-danger" style="display: none">
                    </div>
                    <div class="alert alert-success" style="display: none">
                    </div>
                    <div class="width-prf">
                        <label class="form-label">Company Name</label> <span>*</span>

                        <div class="form-group form-float">
                            <div class="form-line">
                                <input name="company_name" class="form-control"
                                       placeholder="Company Name" required
                                       value="<?php if (isset($dealer_company)) echo $dealer_company; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="width-prf">
                        <label class="form-label">GST No</label>

                        <div class="form-group form-float">
                            <div class="form-line">
                                <input name="txt_gst_no" class="form-control"
                                       placeholder="GST NO" required
                                       value="<?php if (isset($dealer_gstn_no)) echo $dealer_gstn_no; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary" type="button" id="update_company_info" onclick="update_company_info()">Save details
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
    function user_dealer_code() {
        var get_radio_value = $('input[name=rd_sub_plan]:checked').val();
        var dealer_code = <?php echo "'". $deal_code ."'" ; ?>;
        var dataString = "dealer_code=" + dealer_code + "&year=" + get_radio_value + "&user_id=" + <?php echo $security->decrypt($_GET['user_id']) ?>;
        $.ajax({
            type: "POST",
            url: "get_radio_value.php", // Name of the php files
            data: dataString,
            success: function (html) {
                $(".get_amount").html(html);
                /*return false*/
            }
        });

    }
</script>

<?php include "assets/common-includes/footer_includes.php" ?>

</body>
</html>