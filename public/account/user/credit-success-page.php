<?php
error_reporting(0);
include_once "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include "../controller/ManageUser.php";
$manage = new ManageUser();
include "../controller/validator.php";
$validate = new Validator();
include '../sendMail/sendMail.php';
include("session_includes.php");

if (isset($_GET['android_user_id']) && (isset($_GET['type']) && $_GET['type'] == "android")) {
    $android_url = "android_user_id=" . $_GET['android_user_id'] . "&type=" . $_GET['type'];
    $user_id1 = $security->decryptWebservice($_GET['android_user_id']);
    $validateUserId = $manage->validUserId($user_id1);
    if ($validateUserId) {
        $userSpecificResult = $manage->getUserProfile($user_id1);
        if ($userSpecificResult != null) {
            $android_name = $userSpecificResult["name"];
            $android_email = $userSpecificResult["email"];
            $android_custom_url = $userSpecificResult["custom_url"];
            $android_contact = $userSpecificResult['contact_no'];
            $android_type = $userSpecificResult['type'];
            $api_key = $userSpecificResult['api_key'];
        }
        $_SESSION['type'] = $android_type;
        $_SESSION['email'] = $android_email;
        $_SESSION['name'] = $android_name;
        $_SESSION['contact'] = $android_contact;
        $_SESSION['custom_url'] = $android_custom_url;
        $_SESSION['id'] = $security->encrypt($user_id1);
    } else {
        header('location:404-not-found.php?'.$android_url . '&api_key='.$api_key);
    }
}  else {
    $android_url = "";
}
require('../controller/razorpay-php/Razorpay.php');
require("../controller/razorpay-php/RazorpayMaster.php");

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

$master = new RazorpayMaster();

$success = true;
$error = "Payment Failed";

$payment_type = "RAZORPAY";
$date = date("Y-m-d");
$default_message = "";

if (empty($_POST['razorpay_payment_id']) === false) {
    $api = new Api($keyId, $keySecret);

    try {
        $attributes = array(
            'razorpay_order_id' => $_SESSION['razorpay_order_id'],
            'razorpay_payment_id' => $_POST['razorpay_payment_id'],
            'razorpay_signature' => $_POST['razorpay_signature']
        );

        $api->utility->verifyPaymentSignature($attributes);
    } catch (SignatureVerificationError $e) {
        $success = false;
        $error = 'Razorpay Error : ' . $e->getMessage();
    }
}

$timestamp = date('Y-m-d H:i:s');
$date = date("Y-m-d");
if ($success === true) {
    $data = $master->getPaymentDetails($_POST['razorpay_payment_id']);

    if ($data['payment_status'] == 'captured') {
        $amount = $data['amount'] / 100;
        $paymentBrand = $data['card_network'];
        $paymentMode = $data['payment_method'] . " - " . $data['card_type'] . " - " . $data['upi'] . " - " . $data['bank'];
        $LastInvoiceNo = $manage->getLastInvoiceNumber();
        if ($LastInvoiceNo['invoice_no'] == null) {
            $invoice_number = 1001;
        } else {
            $invoice_number = $LastInvoiceNo['invoice_no'] + 1;
        }

        $get_user_data = $manage->getUserData();
        if ($get_user_data != null) {
            $user_expiry_date = $get_user_data['expiry_date'];
            $name = $get_user_data['name'];
            $email = $get_user_data['email'];
            $user_contact = $get_user_data['contact_no'];
            $user_gstno = $get_user_data['gst_no'];

            $invoice_name = $get_user_data['company_name'];
            if ($invoice_name == '') {
                $invoice_name = $get_user_data['name'];
            }

            $user_pan_no = $get_user_data['pan_no'];

        }

        if (isset($_SESSION['new_year'])) {
            $get_select_value = $manage->get_selected_value($_SESSION['new_year']);
            if ($get_select_value['amt'] != null) {
                $old_amount = $get_select_value['amt'];
            } else {
                $old_amount = 0;
            }
            if (isset($_GET['coupon_code'])) {
                $validateCouponCode = $manage->validateDiscountCode($_GET['coupon_code']);
                if ($validateCouponCode) {
                    $discount_amount = $old_amount * $validateCouponCode['discount'] / 100;
                    $new_total_grand = $old_amount - $discount_amount;
                    $taxable_amount = $new_total_grand * 9 / 100;
                    $new_tax = $taxable_amount + $taxable_amount;
                } else {
                    $discount_amount = 0;
                }

            } elseif (isset($_GET['dealer_code'])) {
                $discount_amount = 0;
            }
            $total_without_tax = $old_amount * $_SESSION['quantity'];
            $taxable_amount = $total_without_tax * 9 / 100;
            $new_tax = $taxable_amount + $taxable_amount;
        }
        $message = '
<table style="width: 100%;border-collapse: collapse;" cellpadding="10" border="1" cellspacing="10">
<tr>
<td colspan="5">
<img src="https://sharedigitalcard.com/assets/img/invoice/header%20(1).PNG" style="width:100%">
</td>
</tr>
<tr>
<td colspan="5" style="text-align: center"><h2><b style="font-weight: 500;">Tax Invoice</b></h2></td>
</tr>
<tr>
<td colspan="3"><strong>KUBIC TECHNOLOGY</strong></td>

<td colspan="2" style="text-align: right">
      <strong>Invoice Number : </strong>INV' . $invoice_number . '<br>
     <strong>Invoice Date : </strong>' . $date . '</td>
</tr>

<tr>
<td colspan="3"><strong>Email : </strong>support@sharedigitalcard.com,<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;marketing@sharedigitalcard.com</td>

<td colspan="2" style="text-align: right">
          <strong> Bill for : </strong>' . $name . '</td>
</tr>
<tr>
<td colspan="3"><strong>GST No : </strong> 27APNPC7063F1ZU<br><strong>PAN No : </strong> APNPC7063F</td>
<td colspan="2" style="text-align: right"><strong> Email : </strong>' . $email . '</td>

</tr>

<tr>
<th>Plan Name</th>
<th>Unit Price</th>
<th>QTY</th>
<th>Sac code</th>
<th>Amount</th>
</tr>
<tr>
<td style="text-align: center">' . $_SESSION['new_year'] . ' plan</td>
<td style="text-align: center">' . $old_amount . '</td>
<td style="text-align: center">' . $_SESSION['quantity'] . '</td>
<td style="text-align: center">9983</td>
<td style="text-align: right"> ' . $total_without_tax . '</td>
</tr>
<tr><td colspan="3"></td>
<td>Taxable Amount : </td>
<td style="text-align: right">' . $total_without_tax . '</td>
</tr>';
        if ($get_user_data['gst_no'] != null && substr($get_user_data['gst_no'], 0, 2) != "27") {
            $message .= '
<tr>
<td colspan="3"></td>
<td>IGST (18%): </td>
<td style="text-align: right">' . $new_tax . '</td>
</tr>';
        } else {
            $message .= '
<tr>
<td colspan="3"></td>
<td>CGST (9%): </td>
<td style="text-align: right">' . $taxable_amount . '</td>
</tr>
<tr>
<td colspan="3"></td>
<td>SGST (9%): </td>
<td style="text-align: right">' . $taxable_amount . '</td>
</tr>';
        }

        $message .= '
<tr>
<td colspan="3"></td>
<td>Total Amount : </td>
<td style="text-align: right">' . $amount . '</td>
</tr>
<tr>
<td colspan="5">
            <strong> Important: </strong>
             <ol>
                  <li>This is an electronic generated invoice so</li>
                 <li>
                     Please read all terms and polices on <a href="https://sharedigitalcard.com/refund-and-return-policy.php" target="_blank">https://sharedigitalcard.com/refund-and-return-policy.php</a> for returns, replacement and other issues.
                 </li>
             </ol>
</td>
</tr>
<tr>
<td colspan="5">
<img src="https://sharedigitalcard.com/assets/img/invoice/footer%20(1).PNG" style="width:100%">
</td>
</tr>
</table>';
        $payment_type = "Razorpay";

        /*message*/
        /*  $get_count_suc = $manage->successOfUserPayment();
          if ($get_count_suc != 1) {*/

        if ($_SESSION['new_year'] == "1 year") {
            $month = 12;
        } else if ($_SESSION['new_year'] == "3 year") {
            $month = 36;
        } else if ($_SESSION['new_year'] == "5 year") {
            $month = 60;
        } else {
            $month = "";
        }
        $user_expiry_date = null;
        if ($user_expiry_date != null && $user_expiry_date >= $date && $month != "") {
            $expiry_date_in_time = strtotime("+" . $month . " months", strtotime($user_expiry_date));
            $expiry_date = date("Y-m-d", $expiry_date_in_time);
        } elseif ($user_expiry_date != null && $user_expiry_date <= $date && $month != "") {
            $expiry_date_in_time = strtotime("+" . $month . " months", strtotime($date));
            $expiry_date = date("Y-m-d", $expiry_date_in_time);
        } elseif ($user_expiry_date == null && $month != "") {
            $expiry_date_in_time = strtotime("+" . $month . " months", strtotime($date));
            $expiry_date = date("Y-m-d", $expiry_date_in_time);
        } else {
            $expiry_date = "";
        }
        $status_success = "success";
        $referal_by = "";
        $refrenced_by = "credit";
        $active_plan = 1;

        $discount_amount = 0;

        $insertUserSubscription = $manage->insertUserCreditData($_SESSION['new_year'], $old_amount, $total_without_tax, $date, $expiry_date,
            $status_success, $referal_by, $refrenced_by, $active_plan, $invoice_number, $discount_amount, $new_tax, $amount,
            $paymentBrand, $paymentMode, $custBankId, $timestamp, $_SESSION['quantity'], $payment_type, $invoice_name, $email, $user_gstno,
            $user_pan_no, FROM_BILL, FROM_GSTNO, FROM_PAN, SAC_CODE, $data['order_id'], $_POST['razorpay_payment_id'], $data['error_code'],
            $data['error_description'], $user_address);

        // $updateUserSubscription = $manage->updateUserExpiryDateForPayment($expiry_date);

        if ($insertUserSubscription) {
            $insertCredit = $manage->mu_insertUserCredit($_SESSION['new_year'], $_SESSION['quantity']);
            $sms_message = "Purchased sharedigitalcard of " . $_SESSION['new_year'] . " plan @" . $amount . " and Invoice No: #INV" . $invoice_number;
             $sendEmail = $manage->sendMail($name, $email, $sms_message, $message);
            $sendSms = $manage->sendSMS($user_contact, $sms_message);
            echo "<style>.success{ display: block!important;}</style>";
        }
    }else {

        if ($_SESSION['new_year'] == "1 year") {
            $month = 12;
        } else if ($_SESSION['new_year'] == "3 year") {
            $month = 36;
        } else if ($_SESSION['new_year'] == "5 year") {
            $month = 60;
        }else{
            $month = "";
        }
        $get_user_data = $manage->displayUserData();
        if ($get_user_data != null) {
            $user_expiry_date = $get_user_data['expiry_date'];
        }

        if (($user_expiry_date != null OR $user_expiry_date != "0000-00-00") && $user_expiry_date >= $date && $month !="") {
            $expiry_date_in_time = strtotime("+" . $month . " months", strtotime($user_expiry_date));
            $expiry_date = date("Y-m-d", $expiry_date_in_time);
        } elseif (($user_expiry_date != null OR $user_expiry_date != "0000-00-00") && $user_expiry_date <= $date && $month !="") {
            $expiry_date_in_time = strtotime("+" . $month . " months", strtotime($date));
            $expiry_date = date("Y-m-d", $expiry_date_in_time);
        } elseif(($user_expiry_date == null OR $user_expiry_date == "0000-00-00") && $month !="") {
            $expiry_date_in_time = strtotime("+" . $month . " months", strtotime($date));
            $expiry_date = date("Y-m-d", $expiry_date_in_time);
        }else{
            $expiry_date = "";
        }

        $status_success = "failed";
        $referal_by = "";
        $refrenced_by = "";
        $active_plan = 0;
        $discount_amount = 0;

        $insertUserSubscription = $manage->insertUserDataForRazor($_SESSION['new_year'],$old_amount, $old_amount, $date, $expiry_date, $status_success, $referal_by, $refrenced_by, $active_plan, $invoice_number, $discount_amount, $new_tax, $amount, $paymentBrand, $paymentMode, $custBankId, $timestamp,$payment_type,$invoice_name,$email,$user_gstno,$user_pan_no,FROM_BILL,FROM_GSTNO,FROM_PAN,SAC_CODE,$data['order_id'],$_POST['razorpay_payment_id'],$data['error_code'],$data['error_description'],$user_address);
        echo "<style>.failed{ display: block!important;}</style>";
    }
} else {

    if ($_SESSION['new_year'] == "1 year") {
        $month = 12;
    } else if ($_SESSION['new_year'] == "3 year") {
        $month = 36;
    } else if ($_SESSION['new_year'] == "5 year") {
        $month = 60;
    }else{
        $month = "";
    }
    $get_user_data = $manage->displayUserData();
    if ($get_user_data != null) {
        $user_expiry_date = $get_user_data['expiry_date'];
    }

    if (($user_expiry_date != null OR $user_expiry_date != "0000-00-00") && $user_expiry_date >= $date && $month !="") {
        $expiry_date_in_time = strtotime("+" . $month . " months", strtotime($user_expiry_date));
        $expiry_date = date("Y-m-d", $expiry_date_in_time);
    } elseif (($user_expiry_date != null OR $user_expiry_date != "0000-00-00") && $user_expiry_date <= $date && $month !="") {
        $expiry_date_in_time = strtotime("+" . $month . " months", strtotime($date));
        $expiry_date = date("Y-m-d", $expiry_date_in_time);
    } elseif(($user_expiry_date == null OR $user_expiry_date == "0000-00-00") && $month !="") {
        $expiry_date_in_time = strtotime("+" . $month . " months", strtotime($date));
        $expiry_date = date("Y-m-d", $expiry_date_in_time);
    }else{
        $expiry_date = "";
    }

    $status_success = "failed";
    $referal_by = "";
    $refrenced_by = "";
    $active_plan = 0;
    $discount_amount = 0;

    $insertUserSubscription = $manage->insertUserDataForRazor($_SESSION['new_year'],$old_amount, $old_amount, $date, $expiry_date, $status_success, $referal_by, $refrenced_by, $active_plan, $invoice_number, $discount_amount, $new_tax, $amount, $paymentBrand, $paymentMode, $custBankId, $timestamp,$payment_type,$invoice_name,$email,$user_gstno,$user_pan_no,FROM_BILL,FROM_GSTNO,FROM_PAN,SAC_CODE,$data['order_id'],$_POST['razorpay_payment_id'],$data['error_code'],$data['error_description'],$user_address);
    echo "<style>.failed{ display: block!important;}</style>";
}


if (isset($_POST['continue'])) {
    unset($_SESSION['referral_code']);
    if ($android_url != "") {
        header('location:service.php?' . $android_url . '&api_key='.$api_key);
    } else {
        header('location:service.php');
    }
}




?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Success page</title>
    <?php include "assets/common-includes/header_includes.php" ?>
</head>
<body>
<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>
<section class="content">
    <div class="clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding_zero padding_zero_both">
            <div class="row margin_div_web">
                <div class="success">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="success_msg_main_div_k text-center">
                            <?php
                            if ($default_message != null) {
                                ?>
                                <div class="alert alert-danger">
                                    <?php if (isset($default_message)) echo $default_message; ?>
                                </div>
                            <?php
                            }
                            ?>
                            <span class="fa-stack fa-lg">
  <i class="fa fa-circle fa-stack-2x icon-background1"></i>
  <i class="fa fa-check fa-stack-1x icon_checkmark_design"></i>
</span>
                            <br>
                            <br>

                            <h3>SUCCESS!</h3>

                            <p>You have successfully transferred your money.</p>
                            <?php
                            $get_user_data = $manage->getUserData();
                            if ($get_user_data != null) {
                                $user_email = $get_user_data['email'];
                                $user_contact = $get_user_data['contact_no'];
                                $user_name = $get_user_data['name'];
                            } ?>
                            <table class="table table-striped table-bordered">
                                <tbody>
                                <tr>
                                    <td>User Name</td>
                                    <td><?php echo $user_name; ?></td>
                                </tr>
                                <tr>
                                    <td>Plan Name</td>
                                    <td><?php echo $_SESSION['new_year']; ?></td>
                                </tr>
                                <tr>
                                    <td>Amount</td>
                                    <td><?php echo $amount ?></td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td><?php echo "SUCCESS"; ?></td>
                                </tr>
                                <?php
                                if (isset($_SESSION['referral_code']) && $_SESSION['referral_code'] != "") {
                                    ?>
                                    <tr>
                                        <td>Referral by</td>
                                        <td><?php echo $_SESSION['referral_code']; ?></td>
                                    </tr>
                                <?php
                                } elseif (isset($_GET['dealer_code'])) {
                                    ?>
                                    <tr>
                                        <td>Referral by</td>
                                        <td><?php echo $_GET['dealer_code']; ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                                </tbody>
                            </table>
                            <form method="post" action="">
                                <a href="my-subscription-plan.php" class="btn btn-success mt-10">
                                    <i class="far fa-list-alt"></i> View Invoice
                                </a>&nbsp;&nbsp;
                                <a href="dashboard.php"  class="btn btn-primary mt-10">
                                    <i class="fas fa-tachometer-alt"></i> Go To Dashboard
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="failed">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="success_msg_main_div_k text-center">

  <span class="fa-stack fa-lg">
  <i class="fa fa-circle fa-stack-2x icon-background1"></i>
  <i class="fa fa-times fa-stack-1x icon_checkmark_design"></i>
</span>
                            <br>
                            <br>

                            <h3>Failed!</h3>

                            <!--<p>Due to some issue amount is not transfered.</p>-->
                            <?php
                            $get_user_data = $manage->getUserData();
                            if ($get_user_data != null) {
                                $user_email = $get_user_data['email'];
                                $user_contact = $get_user_data['contact_no'];
                                $user_name = $get_user_data['name'];
                            } ?>
                            <table class="table table-striped table-bordered">
                                <tbody>
                                <tr>
                                    <td>User Name</td>
                                    <td><?php echo $user_name; ?></td>
                                </tr>
                                <tr>
                                    <td>Plan Name</td>
                                    <td><?php echo $_SESSION['new_year']; ?></td>
                                </tr>
                                <tr>
                                    <td>Amount</td>
                                    <td><?php echo $amount ?></td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td><?php echo "Failed"; ?></td>
                                </tr>
                                <?php
                                if (isset($_SESSION['referral_code']) && $_SESSION['referral_code'] != "") {
                                    ?>
                                    <tr>
                                        <td>Referral by</td>
                                        <td><?php echo $_SESSION['referral_code']; ?></td>
                                    </tr>
                                <?php
                                } elseif (isset($_GET['dealer_code'])) {
                                    ?>
                                    <tr>
                                        <td>Referral by</td>
                                        <td><?php echo $_GET['dealer_code']; ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                                </tbody>
                            </table>
                            <form method="post" action="">
                                <a href="dashboard.php" class="btn btn-primary mt-10">
                                    <i class="fas fa-tachometer-alt"></i> Go To Dashboard
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php


?>
<?php include "assets/common-includes/footer_includes.php" ?>
<!--<script>
    $(document).ready(function () {
        window.setTimeout(function () {
            location.href = "https://sharedigitalcard.com/user/dashboard.php";
        }, 5000);
    });
</script>-->
</body>
</html>