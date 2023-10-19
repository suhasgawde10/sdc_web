<?php
ob_start();
/*error_reporting(0);*/
include "../controller/ManageDealer.php";
$manage = new ManageDealer();
include "../controller/validator.php";
$validate = new Validator();
include '../sendMail/sendMail.php';
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();

$date = date("Y-m-d");

$paymentDiv = false;
require("functions.php");
$key = "kS4oNFBT6o3Rvvd7nBkpBIUrf1PsUVaw"; //put in the 32 bit alphanumeric key in the quotes provided here
$paymentId = $_REQUEST['paymentId'];
$merchantTransactionId = $_REQUEST['merchantTransactionId'];
$amount = $_REQUEST['amount'];
$status = $_REQUEST['status'];
//$descriptor = $_REQUEST["descriptor"];
$desc = $_REQUEST["desc"];
$checksum = $_REQUEST['checksum'];
//$billingdiscriptor = $_REQUEST['descriptor'];
//$token = $_REQUEST['token'];
$currency = $_REQUEST['currency'];
$timestamp = $_REQUEST['timestamp'];
$resultCode = $_REQUEST['resultCode'];
$resultDescription = $_REQUEST['resultDescription'];
$cardBin = $_REQUEST['cardBin'];
$cardLast4Digits = $_REQUEST['cardLast4Digits'];
$tmpl_currency = $_REQUEST['tmpl_currency'];
$tmpl_amount = $_REQUEST['tmpl_amount'];


$paymentBrand = $_REQUEST["paymentBrand"];
$paymentMode = $_REQUEST["paymentMode"];
$custBankId = $_REQUEST["custBankId"];
$eci = $_REQUEST["eci"];


$str = "";
$trackingid = "null";

$str = verifychecksum($trackingid, $desc, $amount, $status, $checksum, $key);

//if ($str=="true" && $status=="Y")
$display_message = $manage->displayDealerProfile();
if ($display_message != null) {
    $message_status = $display_message['message_status'];
      $dealer_status = $display_message['status'];
      $pay_status = $display_message['pay_status'];
    $deal_code = $display_message['dealer_code'];
    $invoice_gstn_no = $display_message['gstin_no'];
    $invoice_name = $display_message['c_name'];
    $invoice_email = $display_message['b_email_id'];
    $get_percent = $display_message['dealer_percent'];

    if($invoice_name ==""){
        $invoice_name= $display_message['name'];
    }
    if($invoice_email  == ""){
        $invoice_email= $display_message['email'];
    }
    $dealer_gstn_no = $display_message['gstin_no'];
    $dealer_pan_no = $display_message['pan_no'];
}
if($invoice_gstn_no == ''){
    $invoice_gstn_no = "not applicable";
}
$get_user_data = $manage->getUserData($_GET['user_id']);
if ($get_user_data != null) {
    $user_expiry_date = $get_user_data['expiry_date'];
    $name = $get_user_data['name'];
    $email = $get_user_data['email'];
    $user_contact = $get_user_data['contact_no'];
    $user_name = $get_user_data['name'];

}
/*$amount = 295;
$status = "Y";*/

if ($status == "Y") {
    $paymentDiv = true;
    $LastInvoiceNo = $manage->getLastInvoiceNumber();
    if ($LastInvoiceNo['invoice_no'] == null) {
        $invoice_number = 1001;
    } else {
        $invoice_number = $LastInvoiceNo['invoice_no'] + 1;
    }
    //$get_select_value = $manage->get_selected_value($_SESSION['new_year']);
//    $get_select_amount = $manage->get_selected_amount($_SESSION['new_year']);
//    if ($get_select_amount['amt'] != null) {
//        $new_amt1 = $get_select_amount['amt'];
//    } else {
//        $new_amt1 = 0;
//    }

    $get_prcent_data = $manage->getDealerPricingById($get_percent);
    $dealer_percent = $get_prcent_data['percentage'];
    $get_select_value = $manage->get_selected_value($_SESSION['new_year']);
    if($get_select_value['amt']!=null){
        $new_amt = $get_select_value['amt'];
        $new_amt = $dealer_percent * $new_amt/100;
        $new_amt = $get_select_value['amt'] - $new_amt;
    }else{
        $new_amt =0;
    }

   /* $taxable_amount = $new_amt * 9 / 100;
    $new_taxable_amount = $taxable_amount + $taxable_amount;*/

    /*start*/
    $total_without_tax = $new_amt * $_SESSION['quantity'];
    $taxable_amount = $total_without_tax * 9 / 100;
    $new_taxable_amount = $taxable_amount + $taxable_amount;
    /*end*/

        /*$get_count_suc = $manage->successOfUserPayment($_GET['user_id']);
        if ($get_count_suc != 1) {*/
        if ($_SESSION['new_year'] == "1 year") {
            $month = 12;
        } elseif ($_SESSION['new_year'] == "3 year") {
            $month = 36;
        } elseif ($_SESSION['new_year'] == "5 year") {
            $month = 60;
        }elseif($_SESSION['new_year'] == "Life Time"){
            $month = "";
        }
if($_SESSION['new_year'] != "Life Time"){
    $expiry_date_in_time = strtotime("+" . $month . " months", strtotime($date));
    $expiry_date = date("Y-m-d", $expiry_date_in_time);

    if ($user_expiry_date != null && $user_expiry_date >= $date && $month !="") {
        $expiry_date_in_time = strtotime("+" . $month . " months", strtotime($user_expiry_date));
        $expiry_date = date("Y-m-d", $expiry_date_in_time);
    } elseif ($user_expiry_date != null && $user_expiry_date <= $date && $month !="") {
        $expiry_date_in_time = strtotime("+" . $month . " months", strtotime($date));
        $expiry_date = date("Y-m-d", $expiry_date_in_time);
    } elseif($user_expiry_date == null && $month !="") {
        $expiry_date_in_time = strtotime("+" . $month . " months", strtotime($date));
        $expiry_date = date("Y-m-d", $expiry_date_in_time);
    }else{
        $expiry_date = "";
    }

}else{
    $expiry_date = "";
}
    $referal_by = "";
    $refrenced_by = "credit";
        $status_success = "success";
        $active_plan = 1;
         $dealer_by_pay = 1;
    $payment_type = "PAYMENTZ";
       // $insertUserSubscription = $manage->insertUserData($_GET['user_id'], $_SESSION['new_year'], $new_amt, $date, $expiry_date, $status_success, $active_plan, $invoice_number, $new_taxable_amount, $amount, $paymentBrand, $paymentMode, $custBankId, $timestamp,$dealer_gstn_no,$dealer_by_pay);
    $discount_amount = 0;
    $insertUserSubscription = $manage->insertUserCreditData($_SESSION['new_year'], $new_amt,$new_amt, $date, $expiry_date, $status_success,
        $referal_by, $refrenced_by, $active_plan, $invoice_number, $discount_amount, $new_taxable_amount, $amount, $paymentBrand,
        $paymentMode, $custBankId, $timestamp,$dealer_by_pay,$_SESSION['quantity'],$payment_type,$invoice_name,$invoice_email
        ,$dealer_gstn_no,$dealer_pan_no,FROM_BILL,FROM_GSTNO,FROM_PAN,SAC_CODE,$_GET['user_id']);

        if ($insertUserSubscription) {

            $insertCredit = $manage->mu_insertUserCredit($_SESSION['new_year'],$_SESSION['quantity'],$_GET['user_id']);
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
      <strong>Invoice Number : </strong>#INV' . $invoice_number . '<br>
     <strong>Invoice Date : </strong>' . $date . '</td>
</tr>

<tr>
<td colspan="3"><strong>Email : </strong>support@sharedigitalcard.com,<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;marketing@sharedigitalcard.com</td>


<td colspan="2" style="text-align: right">
          <strong> Bill for : </strong>' . $invoice_name . '<br><strong> Email : </strong>' . $invoice_email . '</td>
</tr>
<tr>
<td colspan="3"><strong>GST No : </strong> 27APNPC7063F1ZU<br><strong>PAN No : </strong> APNPC7063F</td>
<td colspan="2" style="text-align: right"><strong> GSTN No : </strong>' . $invoice_gstn_no . '</td>

</tr>

<td colspan="5">
</td>
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
<td style="text-align: center">' . $new_amt . '</td>
<td style="text-align: center">' . $_SESSION['quantity'] . '</td>
<td style="text-align: center">9983</td>
<td style="text-align: right"> ' . $total_without_tax . '</td>
</tr>
<tr>
<td colspan="3"></td>
<td>Taxable Amount : </td>
<td style="text-align: right">' . $total_without_tax . '</td>
</tr>';
            if($display_message['gstin_no'] !=null && substr($display_message['gstin_no'],0,2) !="27"){
                $message .='
<tr>
<td colspan="3"></td>
<td>IGST (18%): </td>
<td style="text-align: right">' . $new_taxable_amount . '</td>
</tr>';
            }else{
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
            $message .='

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
                     Please read all terms and polices on https://sharedigitalcard.com for returns, replacement and other issues.
                 </li>
             </ol>
</td>
</tr>
<tr>
<td colspan="5">
<img src="https://sharedigitalcard.com/assets/img/invoice/footer%20(1).PNG" style="width:100%">
</td>
</tr>
<!--<tr>
<td colspan="2"><strong>Email : </strong>  kubic@gmail.com</td>
<td colspan="1"></td>
<td colspan="2">
      </td>
</tr>-->
</table>';
            $sms_message = "Dear " . strtoupper($_SESSION['dealer_name']). ", thank you for purchasing " . $_SESSION['new_year'] . " plan credit of sharedigitalcard!";
            $sendEmail = $manage->sendMail($_SESSION['dealer_name'], $_SESSION['dealer_email'], $sms_message, $message);
            $sendsms = $manage->sendSMS($user_contact, $sms_message);
            echo "<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>";
        }
} else if ($status == "N") {
        $get_select_value = $manage->get_selected_value($_SESSION['new_year']);
        if ($get_select_value['amt'] != null) {
            $new_amt = $get_select_value['amt'];
        } else {
            $new_amt = 0;
        }
        $taxable_amount = $new_amt * 9 / 100;
        $new_taxable_amount = $taxable_amount + $taxable_amount;
        if ($_SESSION['new_year'] == "1 year") {
            $month = 12;
        } else if ($_SESSION['new_year'] == "3 year") {
            $month = 36;
        } else if ($_SESSION['new_year'] == "5 year") {
            $month = 60;
        }elseif($_SESSION['new_year'] == "Life Time"){
            $month = "";
        }

        $expiry_date_in_time = strtotime("+" . $month . " months", strtotime($date));
        $expiry_date = date("Y-m-d", $expiry_date_in_time);
        $status_success = "failed";
        $active_plan = 0;
        $invoice_number = "";
    $dealer_by_pay = 1;
        $insertUserSubscription = $manage->insertUserData($_GET['user_id'],1, $_SESSION['new_year'], $new_amt, $date, $expiry_date, $status_success, $active_plan, $invoice_number, $new_taxable_amount, $amount, $paymentBrand, $paymentMode, $custBankId, $timestamp,$dealer_gstn_no,$dealer_by_pay);
    echo "<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>";
} else if ($status == "P") {
    /* $display_message = $manage->displayDealerProfile();
     if ($display_message != null) {
         $message_status = $display_message['message_status'];
         $status = $display_message['status'];
         $deal_code = $display_message['dealer_code'];
     }

     if (isset($_GET['user_id']) && (isset($_REQUEST['amount']))) {
         $get_user_data = $manage->getUserData($_GET['user_id']);
         if ($get_user_data != null) {
             $user_expiry_date = $get_user_data['expiry_date'];
             $name = $get_user_data['name'];
             $email = $get_user_data['email'];
         }
         $get_select_value = $manage->get_selected_value($_SESSION['new_year']);
         if ($get_select_value['amt'] != null) {
             $new_amt = $get_select_value['amt'];
         } else {
             $new_amt = 0;
         }
         $taxable_amount = $new_amt * 9 / 100;
         $new_taxable_amount = $taxable_amount + $taxable_amount;
         $get_count_suc = $manage->successOfUserPayment($_GET['user_id']);
         if ($get_count_suc != 1) {
             if ($_SESSION['new_year'] == "1 year") {
                 $month = 12;
             } else if ($_SESSION['new_year'] == "3 year") {
                 $month = 36;
             } else if ($_SESSION['new_year'] == "5 year") {
                 $month = 60;
             }
             $expiry_date_in_time = strtotime("+" . $month . " months", strtotime($date));
             $expiry_date = date("Y-m-d", $expiry_date_in_time);
             $update_user_plan = $manage->updateUserPlanStatus($_GET['user_id']);
             $status_success = "failed";
             $active_plan = 0;
             $invoice_number = "";
             $insertUserSubscription = $manage->insertUserData($_GET['user_id'], $_SESSION['new_year'], $new_amt, $date, $expiry_date, $status_success, $active_plan, $invoice_number, $new_taxable_amount, $amount, $paymentBrand, $paymentMode, $custBankId, $timestamp);
             if ($updateUserSubscription) {
                 echo "<style>.failed{ display: block!important;}</style>";
             }
         }
     } else {
         header('location:create_digital_card.php');
     }*/
    header('location:create_digital_card.php');
} else {
    header('location:create_digital_card.php');

}

?>



<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
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
                <?php

                if ($paymentDiv == true) {
                    ?>
                    <div class="col-md-6 col-md-offset-3">
                        <div class="success_msg_main_div_k text-center">

  <span class="fa-stack fa-lg">
  <i class="fa fa-circle fa-stack-2x icon-background1"></i>
  <i class="fa fa-check fa-stack-1x icon_checkmark_design"></i>
</span>
                            <br>
                            <br>

                            <h3>SUCCESS!</h3>

                            <p>You have successfully transferred your money.</p>
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
                                    <td><?php echo $amount; ?></td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td><?php echo "SUCCESS"; ?></td>
                                </tr>
                                <?php
                                if (isset($_SESSION['dealer_code'])) {
                                    ?>
                                    <tr>
                                        <td>Referral by</td>
                                        <td><?php echo $_SESSION['dealer_code']; ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                                </tbody>
                            </table>
                            <button id="btnClick" class="btn_success_btn_sub"
                                    onclick="window.location.href='create_digital_card.php'">CONTINUE
                            </button>
                        </div>
                    </div>
                <?php } elseif ($paymentDiv == false) { ?>
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
                                    <td><?php if ($amount != "") {
                                            echo $amount;
                                        } else {
                                            echo "Null";
                                        } ?></td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td><?php echo "Failed"; ?></td>
                                </tr>
                                <?php
                                if (isset($_SESSION['dealer_code'])) {
                                    ?>
                                    <tr>
                                        <td>Referral by</td>
                                        <td><?php echo $_SESSION['dealer_email']; ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                                </tbody>
                            </table>
                            <button id="btnClick" class="btn_success_btn_sub"
                                    onclick="window.location.href='create_digital_card.php'">CONTINUE
                            </button>
                        </div>
                    </div>
                <?php } ?>
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
            location.href = "https://sharedigitalcard.com/dealer/create_digital_card.php";
        }, 5000);
    });
</script>-->

</body>
</html>