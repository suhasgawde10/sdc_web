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

include("session_includes.php");

if ($id != 0) {
    $form_data = $manage->getSpecificDealerProfileByUserId($id);
    if ($form_data != null) {
        $name = $form_data['name'];

        $c_name = $form_data['c_name'];
        $gstin_no = $form_data['gstin_no'];
        $pan_no = $form_data['pan_no'];
        $address = $form_data['address'];
        $website = $form_data['website'];
        $b_email_id = $form_data['b_email_id'];
        $img_name = $form_data['img_name'];
        $category = $form_data['category'];
        $user_type = $form_data['user_type'];
        $get_percent = $form_data['dealer_percent'];
        $message_status = $form_data['message_status'];
        $dealer_status = $form_data['status'];
        $pay_status = $form_data['pay_status'];
        $deal_code = $form_data['dealer_code'];

    }
}


$get_prcent_data = $manage->getDealerPricingById($get_percent);
$dealer_percent = $get_prcent_data['percentage'];
$get_select_value = $manage->get_selected_value($_SESSION['new_year']);
if($get_select_value['amt']!=null){
    $amount = $get_select_value['amt'];
    $amount = $dealer_percent * $amount/100;
    $amount = $get_select_value['amt'] - $amount;
    $amount_without_tax= $get_select_value['amt'] - $amount;

}else{
    $amount =0;
}


$taxable_amount = $amount * 18 / 100;
/*echo "Tax (18%) : ".$taxable_amount . "<br><br>";*/
$newamount = $taxable_amount + $amount;
$round_of_amount = round($newamount);

require('../controller/razorpay-php/Razorpay.php');

use Razorpay\Api\Api;

$api = new Api($keyId, $keySecret);

$orderData = array(
    'receipt' => rand(10000, 10000),
    'amount' => $round_of_amount * 100,
    'currency' => 'INR',
    'payment_capture' => 1 // auto capture
);

$razorPayOrder = $api->order->create($orderData);
$razorPayOrderId = $razorPayOrder['id'];
$_SESSION['razorpay_order_id'] = $razorPayOrderId;
$displayAmount = $round_of_amount = $orderData['amount'];

$data = array(
    "key" => $keyId,
    "amount" => $round_of_amount,
    "name" => $_SESSION['dealer_name'],
    "description" => $_SESSION['dealer_name'],
    "image" => "http://sharedigitalcard.com/user/assets/images/logo.png",
    "prefill" => array(
        "name" => $_SESSION['dealer_name'],
        "email" => $_SESSION['dealer_email'],
        "contact" => $_SESSION['dealer_contact'],
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
<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>
<section class="content">
        <div class="clearfix">


            <div class="col-lg-8 xol-xs-12">
                <div class="card">
                    <div class="header">
                        <a class="btn btn-primary" href="plan-selection.php?user_id=<?php echo $_GET['user_id']; ?>"><i class="fa fa-arrow-left"></i> Back To
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
                                                           value="<?php if (isset($_SESSION['invoice_company_name']) && $_SESSION['invoice_company_name'] != '') echo $_SESSION['invoice_company_name']; elseif (isset($c_name)) echo $c_name; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                        <li>
                                            <div class="width-prf">
                                                <label class="form-label">GST No</label>

                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input name="txt_gst_no" class="form-control"
                                                               placeholder="GST NO"
                                                               value="<?php if (isset($_SESSION['invoice_gst_no']) && $_SESSION['invoice_gst_no'] != '') echo $_SESSION['invoice_gst_no']; elseif (isset($gstin_no)) echo $gstin_no; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
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
                        <form method="post" name='razorpayform' action="success-page.php?user_id=<?php echo $_GET['user_id']; ?>" id="form">
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
                                    <td class="text-right"><?php echo round($amount_without_tax); ?></td>
                                </tr>
                                <tr class="extra_month">
                                </tr>
                                <tr>
                                    <td>Tax (18%) :</td>
                                    <td class="text-right"><?php echo round($taxable_amount); ?></td>
                                </tr>
                                <tr class="border-total-amount total_amount">
                                    <td><h5><b>Total amount : </b></h5></td>
                                    <td class="text-right"><h5><b><?php echo round($newamount); ?></b></h5></td>
                                </tr>

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
            var gst_no = $('input[name=txt_gst_no]').val();
            var address = $('textarea[name=txt_address]').val();
            var name = $('input[name=txt_name]').val();
            var company_name = $('input[name=company_name]').val();
            var dataString = "company_name="+encodeURIComponent(company_name)+"&txt_name="+encodeURIComponent(name)+"&txt_address="+encodeURIComponent(address)+"&txt_gst_no="+encodeURIComponent(gst_no);
            $.ajax({
                type: "POST",
                url: "get_radio_value.php", // Name of the php files
                data: dataString,
                success: function (html) {

                }
            });
        }

    </script>

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