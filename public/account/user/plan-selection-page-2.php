<?php
ob_start();
error_reporting(0);
date_default_timezone_set("Asia/Kolkata");
include_once "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include "../controller/ManageUser.php";
$manage = new ManageUser();
include "../controller/validator.php";
$validate = new Validator();
include '../sendMail/sendMail.php';

include("android-login.php");


$maxsize = 10485760;

$error = false;
$errorMessage = "";
$error1 = false;
$errorMessage1 = "";

/*require_once "../controller/RazorpayMaster.php";
$payment = new RazorpayMaster();*/
$id = 0;
include("session_includes.php");
$amount = 0;

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

$ip = getRealIpAddr(); // your ip address here
$ip_query = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip));

if($ip_query && $ip_query['status'] == 'success'){
    $countryName = $ip_query['country'];
}else{
    $countryName = '';
}

$form_data = $manage->getSpecificUserProfile();
if ($form_data != null) {
    $street = $form_data['address'];
    $company_name = $form_data['company_name'];
    $gst_no = $form_data['gst_no'];
    $pan_no = $form_data['pan_no'];
    $about_us = $form_data['about_company'];
    $our_mission = $form_data['our_mission'];
    $company_profile = $form_data['company_profile'];
    $city = $form_data['city'];
}


$customerId = 4444;
$orderDescription = "Plan"; //your script should substitute detailed description of your order here ( This field is not mandatory )
$country = "IN";//your script should substitute the customer's country code
$TMPL_CURRENCY = "INR";//your script should substitute the currency symbol in which you want to display amount
$currency = "INR";//your script should substitute the currency symbol in which you want to display amount
$state = "";//your script should substitute the customer's state
$postcode = "";//your script should substitute the customer's zip
$telnocc = "091";//your script should substitute the customer's contry code for tel no
$ip = "127.0.0.1"; // your script should replace it with your ip address
$reservedField1 = ""; //As of now this field is reserved and you need not put anything
$reservedField2 = ""; //As of now this field is reserved and you need not put anything
$terminalid = "";   //terminalid if provided
$paymentMode = ""; //payment type as applicable Credit Cards = CC, Vouchers = PV,  Ewallet = EW, NetBanking = NB
$paymentBrand = ""; //card type as applicable Visa = VISA; MasterCard=MC; Dinners= DINER; Amex= AMEX; Disc= DISC; CUP=CUP


/*$processUrl = "https://sandbox.paymentz.com/transaction/Checkout";*/
$processUrl = "https://secure.paymentz.in/transaction/Checkout";
$liveurl = "https://secure.live.com/transaction/PayProcessController";

$get_user_expiry_count = $manage->selectTheme();
if ($get_user_expiry_count != null) {
    $update_user_count = $get_user_expiry_count['update_user_count'];
    $get_email_count = $get_user_expiry_count['email_count'];
    $referer_code = $get_user_expiry_count['referer_code'];
    $sell_ref = $get_user_expiry_count['sell_ref'];

}
$validateReferealCode = $manage->validateDiscountCode($referer_code);

$sub_plan = $manage->subscriptionPlanWithFree();
$sub_plan1 = $manage->subscriptionPlan();

if ($id != 0) {
    $form_data = $manage->getSpecificUserProfile();
    if ($form_data != null) {
        $name = $form_data['name'];
        $email = $form_data['email'];
        $contact_no = $form_data['contact_no'];
        $company_name = $form_data['company_name'];
        $gst_no = $form_data['gst_no'];
        $user_country = $form_data['country'];
        $address = $form_data['address'];

    }

}

$get_select_value = $manage->get_selected_value($_SESSION['new_year']);

if ($countryName == 'India' OR $countryName == '') {
    if ($get_select_value['amt'] != null) {
        $amount = $get_select_value['amt'];
    } else {
        $amount = 0;
    }
    $taxable_amount = $amount * 18 / 100;
    /*echo "Tax (18%) : ".$taxable_amount . "<br><br>";*/
    $newamount = $taxable_amount + $amount;
    $newamount1 = number_format((float)$newamount, 2, '.', '');
    $currency_symbol = "";
    $round_of_amount = round($newamount);
}else{
    if ($get_select_value['usd_amt'] != null) {
        $amount = $get_select_value['usd_amt'];
    } else {
        $amount = 0;
    }
    $taxable_amount = '';
    $newamount = '';
    $newamount1 = $amount;
    $currency_symbol = "$";
    $round_of_amount = round($amount);
}

require('../controller/razorpay-php/Razorpay.php');

use Razorpay\Api\Api;

$api = new Api($keyId, $keySecret);
if ($countryName == 'India' OR $countryName == '') {
    $currency_type = "INR";
}else{
    $currency_type = "USD";
}
$orderData = array(
    'receipt' => rand(10000, 10000),
    'amount' => $round_of_amount * 100,
    'currency' => $currency_type,
    'payment_capture' => 1 // auto capture
);

$razorPayOrder = $api->order->create($orderData);
$razorPayOrderId = $razorPayOrder['id'];
$_SESSION['razorpay_order_id'] = $razorPayOrderId;
$displayAmount = $round_of_amount = $orderData['amount'];

$data = array(
    "key" => $keyId,
    "amount" => $round_of_amount,
    "name" => $name,
    "description" => $name,
    "image" => "http://sharedigitalcard.com/user/assets/images/logo.png",
    "prefill" => array(
        "name" => $name,
        "email" => $email,
        "contact" => $contact_no,
    ),
    "notes" => array(
        "address" => $address,
        "merchant_order_id" => rand(10000000, 10000000),
    ),
    "theme" => array(
        "color" => "#4668ac"
    ),
    "order_id" => $razorPayOrderId,
);

$json = json_encode($data);

?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php include "assets/common-includes/total_count.php" ?>
    <title>plan selection</title>
    <?php include "assets/common-includes/header_includes.php" ?>
    <style>
        #get_amount1 table {
            display: none;
        }

        .upi-btn {
            width: 19%;
            padding: 10px 0;
            top: 40px;
        }

        input[name="payment_type"]:not(:checked), input[name="payment_type"]:checked {
            position: unset;
            opacity: 1;
        }

    </style>
</head>
<body>
<?php
if (!isset($_GET['android_user_id']) && (!isset($_GET['type']) && $_GET['type'] != "android") && (!isset($_GET['api_key']))) {
?>
<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>
<section class="content custom-m-t-90">
    <?php
    }elseif (isset($_GET['android_user_id']) && (isset($_GET['type']) && $_GET['type'] == "android") && (isset($_GET['api_key']))) {
    ?>
    <section class="androidPlanSection">
        <?php
        }
        ?>
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

            <div class="col-lg-8 xol-xs-12">
                <div class="card">
                    <div class="header">
                        <a class="btn btn-primary" href="plan-selection.php"><i class="fa fa-arrow-left"></i> Back To
                            Plan Selection</a>
                    </div>
                    <div class="body">
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
                        <fieldset>

                            <legend class="legend_font_size" align="left">Billing Address</legend>

                            <form method="post" action="">
                                <ul class="company_profile_ul">
                                    <li>
                                        <div class="width-prf">
                                            <label class="form-label">Full Name</label> <span
                                                class="required_field">*</span>

                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input name="txt_name" class="form-control"
                                                           value="<?php if (isset($_SESSION['invoice_name']) && $_SESSION['invoice_name'] != '') echo $_SESSION['invoice_name']; elseif (isset($name)) echo $name; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="width-prf">
                                            <label class="form-label">Company Name</label> <span>(Optional)</span>

                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input name="company_name" class="form-control"
                                                           placeholder="Company Name"
                                                           value="<?php if (isset($_SESSION['invoice_company_name']) && $_SESSION['invoice_company_name'] != '') echo $_SESSION['invoice_company_name']; elseif (isset($company_name)) echo $company_name; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <?php
                                    if ($countryName == 'India' OR $countryName == '' OR $user_country == '101') {
                                        ?>
                                        <li>
                                            <div class="width-prf">
                                                <label class="form-label">GST No</label>

                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input name="txt_gst_no" class="form-control"
                                                               placeholder="GST NO"
                                                               value="<?php if (isset($_SESSION['invoice_gst_no']) && $_SESSION['invoice_gst_no'] != '') echo $_SESSION['invoice_gst_no']; elseif (isset($gst_no)) echo $gst_no; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                    <li class="user_address">
                                        <div class="width-prf">
                                            <label class="form-label">Address</label>

                                            <div class="form-group form-float">
                                                <div class="form-line">
                                    <textarea name="txt_address" class="form-control"
                                              placeholder="Address"><?php if (isset($_SESSION['invoice_address']) && $_SESSION['invoice_address'] != '') echo $_SESSION['invoice_address']; elseif (isset($address)) echo $address; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                </ul>
                               <!-- <div class="form-group text-center">
                                    <button name="btn_update_profile" type="submit"
                                            class="btn btn-primary waves-effect">Update Profile
                                    </button>
                                </div>-->
                            </form>
                        </fieldset>
                    </div>
                </div>

            </div>

            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                <!--  action="--><?php //echo $processUrl; ?> <!--"-->
                <div class="row plan-selection-card">
                    <div class="body">
                        <form method="post" name='razorpayform' action="success-page-razor.php" id="form">
                            <?php if ($error1) {
                                ?>
                                <div class="alert alert-danger">
                                    <a href="#" class="close" data-dismiss="alert"
                                       aria-label="close">&times;</a>
                                    <?php if (isset($errorMessage1)) echo $errorMessage1; ?>
                                </div>
                            <?php
                            } else if (!$error1 && $errorMessage1 != "") {
                                ?>
                                <div class="alert alert-success">
                                    <a href="#" class="close" data-dismiss="alert"
                                       aria-label="close">&times;</a>
                                    <?php if (isset($errorMessage1)) echo $errorMessage1; ?>
                                </div>
                            <?php
                            }
                            ?>
                            <table class="table table-borderless get_amount">
                                <tbody>
                                <tr>
                                    <td><?php echo $_SESSION['new_year'] ?> Plan &nbsp;</td>
                                    <td class="text-right"><?php echo $currency_symbol. round($amount); ?></td>
                                </tr>
                                <tr class="extra_month">
                                </tr>
                                <?php
                                if ($countryName == 'India' OR $countryName == '') {
                                    ?>
                                    <tr>
                                        <td>Tax (18%) :</td>
                                        <td class="text-right"><?php echo round($taxable_amount); ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                                <tr class="border-total-amount total_amount">
                                    <td><h5><b>Total amount : </b></h5></td>
                                    <td class="text-right"><h5><b><?php echo $currency_symbol. round($newamount1); ?></b></h5></td>
                                </tr>
                                <?php
                                if ($countryName == 'India' OR $countryName == '') {
                                    ?>
                                    <tr class="validate_referral_code">
                                        <td><a data-toggle="modal"
                                               data-target="#user_referral_code">Have a Referral code?</a></td>
                                        <td class="text-right"><a data-toggle="modal"
                                                                  data-target="#user_dealer_code">Have a dealer
                                                code?</a>
                                        </td>
                                    </tr>
                                    <tr class="code_msg"></tr>
                                    <tr class="validate_referral_code text-center">
                                        <td id="add_gst_no" colspan="2"><a data-toggle="modal"
                                                                           data-target="#user_coupon_code">Have a coupon
                                                code?</a></td>
                                    </tr>
                                <?php
                                }
                                ?>
                                <tr class="pamentz_row">
                                    <td colspan="2">
                                        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                                        <input type="hidden" name="razorpay_signature" id="razorpay_signature">
                                        <button class="btn btn-primary form-control submit_btn" id="rzp-button1"
                                                name="upgrade_plan" type="button">Pay now
                                        </button>

                                    </td>
                                    <!-- onclick="sendNotification()" -->
                                </tr>
                                </tbody>
                            </table>
                            <script src="https://checkout.razorpay.com/v1/checkout.js"></script>


                        </form>


                    </div>
                </div>
            </div>

        </div>
    </section>


    <script>


        var options = <?php echo $json ?>;

        options.handler = function (response) {
            document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
            document.getElementById('razorpay_signature').value = response.razorpay_signature;
            document.razorpayform.submit();
        };
        options.theme.image_padding = false;
        options.modal = {
            ondismiss: function () {
                console.log("This code runs when the popup is closed");
            },
            escape: true,
            backdropclose: false
        };
        var rzp = new Razorpay(options);
        document.getElementById('rzp-button1').onclick = function (e) {
            rzp.open();
            getFormData();
            e.preventDefault();
        }
    </script>

    <div class="modal fade" id="user_referral_code" role="dialog">
        <div class="modal-dialog cust-model-width">
            <div class="modal-content">
                <div class="modal-header cust-upi-madal">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Have a referral code?</h4>
                </div>
                <div class="modal-body">
                    <div class="body">
                        <form method="POST" action="">
                            <input name="referal_code" placeholder="Enter referral code"
                                   class="form-control referral_code">&nbsp;&nbsp;&nbsp;
                            <p class="code_msg2"></p>

                            <div class="form-group">
                                <button class="btn btn-primary" type="button" onclick="user_referral_code()">Apply Now
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="user_dealer_code" role="dialog">
        <div class="modal-dialog cust-model-width">
            <div class="modal-content">
                <div class="modal-header cust-upi-madal">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Have a dealer code?</h4>
                </div>
                <div class="modal-body">
                    <div class="body">
                        <input name="referal_code" placeholder="Enter dealer code" class="form-control dealer_code">&nbsp;&nbsp;&nbsp;
                        <p class="code_msg1"></p>

                        <div class="form-group">
                            <button class="btn btn-primary" type="button" onclick="user_dealer_code()">Apply Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="user_coupon_code" role="dialog">
        <div class="modal-dialog cust-model-width">
            <div class="modal-content">
                <div class="modal-header cust-upi-madal">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Have a discount code?</h4>
                </div>
                <div class="modal-body">
                    <div class="body">
                        <input name="coupon_code" placeholder="Enter coupon code" class="form-control">&nbsp;&nbsp;&nbsp;
                        <p class="coupon_msg"></p>

                        <div class="form-group">
                            <button class="btn btn-primary" type="button" onclick="user_coupon_code()">Apply Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        var get_radio_value = '<?php echo $_SESSION['new_year']; ?>';
        function update_company_info() {
            var valid = false;
            var company_name = $('input[name=company_name]').val();
            var gst_no = $('input[name=txt_gst_no]').val();
            if (company_name.trim() == '') {
                $('.alert-danger').show().text('Enter Company Name\n');
                valid = true;
            }
            if (gst_no.trim() == '') {
                $('.alert-danger').show().text('Enter Gst No\n');
                valid = true;
            }
            if (!valid) {
                var dataString = "updatate_company=" + encodeURIComponent(company_name) + "&gst_no=" + encodeURIComponent(gst_no);
                console.log(dataString);
                $.ajax({
                    type: "POST",
                    url: "get_radio_value.php", // Name of the php files
                    data: dataString,
                    beforeSend: function () {
                        // setting a timeout
                        $('#update_company_info').text('Saving...').attr('disabled', 'disabled');
                    },
                    success: function (html) {
                        if (html.trim() == 1) {
                            $('.alert-danger').hide();
                            $('.alert-success').show().text('Company details saved successfully.\n');
                            /*
                             $("#user_company_info").modal("hide");
                             $('#add_gst_no').hide();*/
                            $('#update_company_info').text('Save details').removeAttr('disabled')
                        } else {
                            $('.alert-danger').show().text('Issue while updating please try after some time.');
                            $('#update_company_info').text('Save details').removeAttr('disabled')
                        }

                    }
                });
            }
        }
        function sendNotification() {
            var dataString = "send_notification=plan";
            $.ajax({
                type: "POST",
                url: "get_radio_value.php", // Name of the php files
                data: dataString,
                beforeSend: function () {
                    // setting a timeout
                    $('.submit_btn').text('Processing...');
                },
                success: function (html) {
                    $('.submit_btn').attr('type', 'submit');
                    $('form').attr('action', '<?php echo $processUrl; ?>');
                    $('.submit_btn')[0].click();
                }
            });
        }
    </script>
    <script>
        function getFormData(){
            <?php
            if ($user_country == '101') {
            echo "var gst_no = $('input[name=txt_gst_no]').val();";
            }else{
              echo "var gst_no = ''";
            }?>
            var address = $('textarea[name=txt_address]').val();
            var name = $('input[name=txt_name]').val();
            var company_name = $('input[name=company_name]').val();
            var dataString = "company_name="+encodeURIComponent(company_name)+"&txt_name="+encodeURIComponent(name)+"&txt_address="+encodeURIComponent(address)+"&txt_gst_no="+encodeURIComponent(gst_no);
            console.log(dataString);
            $.ajax({
                type: "POST",
                url: "get_radio_value.php", // Name of the php files
                data: dataString,
                success: function (html) {

                }
            });
        }

    </script>

    <script>
        function get_value(val) {
            var dataString = "radio_value=" + get_radio_value + "<?php if ($android_url != "") echo "&android_user_id=".$_GET['android_user_id']."&type=".$_GET['type']; ?>";
            $.ajax({
                type: "POST",
                url: "get_radio_value.php", // Name of the php files
                data: dataString,
                dataType: "json",
                success: function (result) {
                    $(".get_amount tbody").html(result.data);
                    <?php if (like_match('%ref%', $referer_code) == 1) { ?>
                    default_user_referral_code();
                    <?php } ?>
                }
            });
        }

    </script>
    <script>
        function user_referral_code() {
            var refereal_code = $('.referral_code').val();
            var dataString = "refereal_code=" + refereal_code + "<?php if ($android_url != "") echo "&android_user_id=".$_GET['android_user_id']."&type=".$_GET['type']; ?>";
            var dataString1 = "check_code=" + refereal_code + "<?php if ($android_url != "") echo "&android_user_id=".$_GET['android_user_id']."&type=".$_GET['type']; ?>";
            var dataString2 = "check_refereal_code=" + refereal_code + "<?php if ($android_url != "") echo "&android_user_id=".$_GET['android_user_id']."&type=".$_GET['type']; ?>";
            $.ajax({
                type: "POST",
                url: "get_radio_value.php", // Name of the php files
                data: dataString,
                success: function (html) {
                    $(".code_msg").html(html);
                    /*return false*/
                    $(".hide_default").css("display", "none");

                }
            });
            $.ajax({
                type: "POST",
                url: "get_radio_value.php", // Name of the php files
                data: dataString1,
                success: function (html) {
                    $(".extra_month").html(html);
                    /*return false*/
                }
            });
            $.ajax({
                type: "POST",
                url: "get_radio_value.php", // Name of the php files
                data: dataString2,
                success: function (html) {
                    $(".code_msg2").html(html);
                    /*return false*/
                }
            });
        }

        function default_user_referral_code() {

            var refereal_code = '<?php echo $referer_code; ?>';
            var dataString1 = "check_code=" + refereal_code + "<?php if ($android_url != "") echo "&android_user_id=".$_GET['android_user_id']."&type=".$_GET['type']; ?>";
            var dataString = "refereal_code=" + refereal_code + "<?php if ($android_url != "") echo "&android_user_id=".$_GET['android_user_id']."&type=".$_GET['type']; ?>";
            $.ajax({
                type: "POST",
                url: "get_radio_value.php", // Name of the php files
                data: dataString,
                success: function (html) {
                    $(".code_msg").html(html);
                    /*return false*/
                    $(".hide_default").css("display", "none");

                }
            });
            $.ajax({
                type: "POST",
                url: "get_radio_value.php", // Name of the php files
                data: dataString1,
                success: function (html) {
                    $(".extra_month").html(html);
                    /*return false*/
                }
            });
        }
        function InvalidReferralCode() {
            var refereal_code = 'referetfr';
            var dataString = "refereal_code=" + refereal_code + "<?php if ($android_url != "") echo "&android_user_id=".$_GET['android_user_id']."&type=".$_GET['type']; ?>";
            var dataString1 = "check_code=" + refereal_code + "<?php if ($android_url != "") echo "&android_user_id=".$_GET['android_user_id']."&type=".$_GET['type']; ?>";
            $.ajax({
                type: "POST",
                url: "get_radio_value.php", // Name of the php files
                data: dataString,
                success: function (html) {
                    $(".code_msg").html(html);
                    return false
                }
            });
            $.ajax({
                type: "POST",
                url: "get_radio_value.php", // Name of the php files
                data: dataString1,
                success: function (html) {
                    $(".extra_month").html(html);
                    return false
                }
            });
        }

    </script>
    <script>
        function user_dealer_code() {


            var dealer_code = $('.dealer_code').val();
            var dataString = "dealer_code=" + encodeURIComponent(dealer_code) + "&year=" + get_radio_value + "<?php if ($android_url != "") echo "&android_user_id=".$_GET['android_user_id']."&type=".$_GET['type']; ?>";
            var dataString1 = "check_dealer_code=" + encodeURIComponent(dealer_code) + "<?php if ($android_url != "") echo "&android_user_id=".$_GET['android_user_id']."&type=".$_GET['type']; ?>";
            $.ajax({
                type: "POST",
                url: "get_radio_value.php", // Name of the php files
                data: dataString,
                success: function (html) {
                    $(".get_amount").html(html);
                    /*return false*/
                }
            });
            $.ajax({
                type: "POST",
                url: "get_radio_value.php", // Name of the php files
                data: dataString1,
                success: function (html) {
                    $(".code_msg1").html(html);
                    /*return false*/
                }
            });
        }
        function default_user_dealer_code() {
            var get_radio_value = $('input[name=rd_sub_plan]:checked').val();
            var dealer_code = '<?php echo $referer_code; ?>';
            var dataString = "dealer_code=" + dealer_code + "&year=" + get_radio_value + "<?php if ($android_url != "") echo "&android_user_id=".$_GET['android_user_id']."&type=".$_GET['type']; ?>";
            /*alert(dealer_code);*/
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
        function InvalidDealerCode() {
            var get_radio_value = $('input[name=rd_sub_plan]:checked').val();
            var dealer_code = 'jdhfkjghdskfhjg';
            var dataString = "dealer_code=" + dealer_code + "&year=" + get_radio_value + "<?php if ($android_url != "") echo "&android_user_id=".$_GET['android_user_id']."&type=".$_GET['type']; ?>";
            var dataString1 = "check_dealer_code=" + dealer_code + "<?php if ($android_url != "") echo "&android_user_id=".$_GET['android_user_id']."&type=".$_GET['type']; ?>";
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
    <script>
        function user_coupon_code() {

            var coupon_code = $('input[name=coupon_code]').val();
            var dataString = "coupon_code=" + encodeURIComponent(coupon_code) + "&year=" + get_radio_value + "<?php if ($android_url != "") echo "&android_user_id=".$_GET['android_user_id']."&type=".$_GET['type']; ?>";
            var dataString1 = "check_coupon_code=" + encodeURIComponent(coupon_code) + "<?php if ($android_url != "") echo "&android_user_id=".$_GET['android_user_id']."&type=".$_GET['type']; ?>";
            $.ajax({
                type: "POST",
                url: "get_radio_value.php", // Name of the php files
                data: dataString,
                success: function (html) {
                    $(".get_amount").html(html);
                    /*return false*/
                }
            });
            $.ajax({
                type: "POST",
                url: "get_radio_value.php", // Name of the php files
                data: dataString1,
                success: function (html) {
                    $(".coupon_msg").html(html);
                    /*return false*/
                }
            });
        }
        function default_user_coupon_code() {
            var coupon_code = '<?php echo $referer_code; ?>';
            var dataString = "coupon_code=" + coupon_code + "&year=" + get_radio_value + "<?php if ($android_url != "") echo "&android_user_id=".$_GET['android_user_id']."&type=".$_GET['type']; ?>";
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
        function InvalidDealerCode() {

            var coupon_code = 'jdhfkjghdskfhjg';
            var dataString = "coupon_code=" + coupon_code + "&year=" + get_radio_value + "<?php if ($android_url != "") echo "&android_user_id=".$_GET['android_user_id']."&type=".$_GET['type']; ?>";
            var dataString1 = "check_coupon_code=" + coupon_code + "<?php if ($android_url != "") echo "&android_user_id=".$_GET['android_user_id']."&type=".$_GET['type']; ?>";
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

    <!--<script>

function addTemporaryValue() {
var reference_code = <?php /*if (isset($_POST['grand_amount'])){ echo $_POST['grand_amount']; }elseif(isset($_POST['dealer_code'])){ echo $_POST['dealer_code']; }else{ echo ""; } */ ?>;
            var year = <?php /*if(isset($_POST['new_year'])) echo $_POST['new_year']; */ ?>;
            var dataString = "reference_code=" + reference_code + "&year=" + year + "<?php /*if ($android_url != "") echo "&android_user_id=".$_GET['android_user_id']."&type=".$_GET['type']; */ ?>";
            $.ajax({
                type: "POST",
                url: "get_radio_value.php",
                data: dataString,
                success: function (html) {

                }
            });
        }
    </script>-->

    <script type="text/javascript">
        function valueChanged() {
            if ($('.coupon_question').is(":checked"))
                $(".answer").show();
            else
                $(".answer").hide();
        }
    </script>

    <?php include "assets/common-includes/footer_includes.php" ?>

</body>
</html>