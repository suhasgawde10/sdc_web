<?php

include_once "controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include "controller/ManageUser.php";
$manage = new ManageUser();
include "controller/validator.php";
$validate = new Validator();
include 'sendMail/sendMail.php';



require('controller/razorpay-php/Razorpay.php');

require("controller/razorpay-php/RazorpayMaster.php");

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

$master = new RazorpayMaster();

$success = true;
$error = "Payment Failed";

$payment_type = "RAZORPAY";
include 'user/session_includes.php';

$get_user_data = $manage->getUserData();
if ($get_user_data != null) {
    $user_expiry_date = $get_user_data['expiry_date'];
    $email = $get_user_data['email'];
    $user_contact = $get_user_data['contact_no'];
    $user_pan_no = $get_user_data['pan_no'];

    if(isset($_SESSION['invoice_company_name']) && $_SESSION['invoice_company_name'] !=''){
        $invoice_name = $_SESSION['invoice_company_name'];
    }elseif(isset($_SESSION['invoice_name']) && $_SESSION['invoice_name'] !='') {
        $invoice_name = $_SESSION['invoice_name'];
    }elseif($get_user_data['company_name'] !=''){
        $invoice_name = $get_user_data['company_name'];
    } else {
        $invoice_name = $get_user_data['name'];
    }

    if(isset($_SESSION['invoice_gst_no']) && $_SESSION['invoice_gst_no'] !=''){
        $user_gstno = $_SESSION['invoice_gst_no'];
    }else{
        $user_gstno = $get_user_data['gst_no'];
    }


    if(isset($_SESSION['invoice_address']) && $_SESSION['invoice_address'] !=null){
        $user_address = $_SESSION['invoice_address'];
    }else{
        $user_address = $get_user_data['address'];
    }

    $verified_email_status = $get_user_data['verified_email_status'];
}



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
    if($data['payment_status'] == 'captured'){
        $currency_type = strtoupper($data['currency']);


        if (isset($_SESSION['new_year'])) {
            $get_select_value = $manage->get_selected_value($_SESSION['new_year']);
            if($currency_type == "USD"){
                $currency_symbol = "$";
                if ($get_select_value['usd_amt'] != null) {
                    $old_amount = $get_select_value['usd_amt'];
                } else {
                    $old_amount = 0;
                }
            }else {
                $currency_symbol = "&#8377;";
                if ($get_select_value['amt'] != null) {
                    $old_amount = $get_select_value['amt'];
                } else {
                    $old_amount = 0;
                }
            }
            $taxable_amount = $old_amount * 9 / 100;
            $new_tax = $taxable_amount + $taxable_amount;
            if(isset($_SESSION['coupon_code']) && $_SESSION['coupon_code'] !=''){
                $validateCouponCode = $manage->validateDiscountCode($_SESSION['coupon_code']);
                if($validateCouponCode){
                    $discount_amount = $old_amount * $validateCouponCode['discount'] / 100;
                    $new_total_grand = $old_amount - $discount_amount;
                    $taxable_amount = $new_total_grand * 9 / 100;
                    $new_tax = $taxable_amount + $taxable_amount;
                }else{
                    $discount_amount = 0;
                }

            }elseif (isset($_SESSION['user_dealer_code'])) {
                $discount_amount = 0;
            }

        }
        $amount = $data['amount']/100;
        $paymentBrand = $data['card_network'];
        $paymentMode = $data['payment_method'] ." - ".$data['card_type'] ." - ".$data['upi'] ." - ".$data['bank'];
        $LastInvoiceNo = $manage->getLastInvoiceNumber($currency_type);
        if ($LastInvoiceNo['invoice_no'] == null) {
            $invoice_number = 1001;
        } else {
            $invoice_number = $LastInvoiceNo['invoice_no'] + 1;
        }
        /*message*/
        if(isset($_SESSION['coupon_code']) && $_SESSION['coupon_code'] !='' && isset($_SESSION['new_year'])) {
            /*  $get_count_suc = $manage->successOfUserPayment();
              if ($get_count_suc != 1) {*/
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
            $status_success = "success";
            $referal_by = $_SESSION['coupon_code'];
            $refrenced_by = "coupon";
            $active_plan = 1;
            $update_user_plan = $manage->updateUserPlanStatus();
            $insertUserSubscription = $manage->insertUserDataForRazor($_SESSION['new_year'], $old_amount,$new_total_grand, $date, $expiry_date, $status_success,
                $referal_by, $refrenced_by, $active_plan, $invoice_number, $discount_amount, $new_tax, $amount, $paymentBrand, $paymentMode,
                $custBankId, $timestamp,$payment_type,$invoice_name,$email,$user_gstno,$user_pan_no,FROM_BILL,FROM_GSTNO,FROM_PAN,SAC_CODE,$data['order_id'],$_POST['razorpay_payment_id'],$data['error_code'],$data['error_description'],$user_address);
            $updateUserSubscription = $manage->updateUserExpiryDateForPayment($expiry_date);
        } elseif(isset($_SESSION['referral_code']) && $_SESSION['referral_code'] !='' && (isset($_SESSION['new_year']))) {
            $dealer_amount = $old_amount * 0.10;
            if ($_SESSION['new_year'] == "1 year") {
                $month = 14;
            } else if ($_SESSION['new_year'] == "3 year") {
                $month = 38;
            } else if ($_SESSION['new_year'] == "5 year") {
                $month = 62;
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

            $status_success = "success";
            $refrenced_by = "user";
            $active_plan = 1;
            $discount_amount = "0";
            $update_user_plan = $manage->updateUserPlanStatus();
            $insertUserSubscription = $manage->insertUserDataForRazor($_SESSION['new_year'],$old_amount, $old_amount, $date, $expiry_date, $status_success, $_SESSION['referral_code'], $refrenced_by, $active_plan, $invoice_number, $discount_amount, $new_tax, $amount, $paymentBrand, $paymentMode, $custBankId, $timestamp,$payment_type,$invoice_name,$email,$user_gstno,$user_pan_no,FROM_BILL,FROM_GSTNO,FROM_PAN,SAC_CODE,$data['order_id'],$_POST['razorpay_payment_id'],$data['error_code'],$data['error_description'],$user_address);
            $updateUserSubscription = $manage->updateUserExpiryDateForPayment($expiry_date);
            $get_referral_data = $manage->displayRelatedReferralUser($_SESSION['referral_code']);
            if ($get_referral_data != null) {
                $referral_user_expiry_date = $get_referral_data['expiry_date'];
                $referral_user_dealer_code = $get_referral_data['referer_code'];
            }
            if($referral_user_dealer_code !=null){
                $insert_data = array('dealer_code'=>$referral_user_dealer_code,'user_id'=>$get_referral_data['id'],'beneficiary_id'=>$id,
                    'amount'=>$dealer_amount,'date'=>date('Y-m-d'), 'remark'=>'Converted Using My Customer Referral Code For User '.$session_name,
                    'type'=>'customer_ref_code', 'payment_status'=>'pending');
                $update_dealer_payment = $manage->insert($manage->walletHistoryTable,$insert_data);
            }
            if ($referral_user_expiry_date != null) {
                $final_date = strtotime(date("Y-m-d", strtotime($referral_user_expiry_date)) . " +2 month");
                $final_date = date("Y-m-d", $final_date);
            } else {
                $final_date = strtotime(date("Y-m-d", strtotime($date)) . " +2 month");
                $final_date = date("Y-m-d", $final_date);
            }
            $update_referral_user = $manage->update_referral_user($final_date, $_SESSION['referral_code']);

            unset($_SESSION['referral_code']);

            /*}*/
        } elseif (isset($_SESSION['user_dealer_code']) && (isset($_SESSION['new_year']))) {
            if ($_SESSION['new_year'] == "1 year") {
                $month = 16;
            } else if ($_SESSION['new_year'] == "3 year") {
                $month = 40;
            } else if ($_SESSION['new_year'] == "5 year") {
                $month = 64;
            }else{
                $month = "";
            }
            $get_user_data = $manage->displayUserData();
            $dealer_profile = $manage->getDealerProfile($_SESSION['user_dealer_code']);
            $dealer_email = $dealer_profile['email'];
            $dealer_name = $dealer_profile['name'];
            $dealer_contact_no = $dealer_profile['contact_no'];
            $dealer_wallet = $dealer_profile['wallet_amount'];
            if ($get_user_data != null) {
                $user_expiry_date = $get_user_data['expiry_date'];
                $user_name = $get_user_data['name'];
            }
            $get_percent = $dealer_profile['dealer_percent'];
            $get_prcent_data = $manage->getDealerPricingById($get_percent);

            $dealer_percent = $get_prcent_data['percentage'];
            // echo "<br>";
            // echo $old_amount;
            // echo "<br>";
            $dealer_payment = $old_amount*$dealer_percent/100;

            //  $dealer_payment = $old_amount - $dealer_percent;
            /*start*/
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
            /* end */

            $status_success = "success";
            $refrenced_by = "dealer";
            $active_plan = 1;
            $sell_ref = "dealer_link";
            $update_user_plan = $manage->updateUserPlanStatus();
            $insertUserSubscription = $manage->insertUserDataForRazor($_SESSION['new_year'], $old_amount,$old_amount, $date, $expiry_date, $status_success, $_SESSION['user_dealer_code'], $refrenced_by, $active_plan, $invoice_number, $discount_amount, $new_tax, $amount, $paymentBrand, $paymentMode, $custBankId, $timestamp,$payment_type,$invoice_name,$email,$user_gstno,$user_pan_no,FROM_BILL,FROM_GSTNO,FROM_PAN,SAC_CODE,$data['order_id'],$_POST['razorpay_payment_id'],$data['error_code'],$data['error_description'],$user_address);
            $updateUserSubscription = $manage->updateUserExpiryDateWithRefrence($_SESSION['user_dealer_code'],$expiry_date,$sell_ref);

            //  $update_dealer_payment = $manage->updateDealerPayment($dealer_payment, $_SESSION['user_dealer_code']);
            // $update_user_ref = $manage->updateUserReference();
            if ($updateUserSubscription) {
                /* if ($dealer_wallet != null) {
                     $dealer_payment = $dealer_wallet + $dealer_payment;
                 } else {
                     $dealer_payment = 0 + $dealer_payment;
                 }*/
                $insert_data = array('dealer_code'=>$_SESSION['user_dealer_code'],'user_id'=>$id, 'amount'=>$dealer_payment,
                    'date'=>date('Y-m-d'), 'remark'=>'Converted Using Dealer Code','type'=>'dealer_code', 'payment_status'=>'pending');
                $update_dealer_payment = $manage->insert($manage->walletHistoryTable,$insert_data);
                $dealer_subject = $user_name . " has Purchased sharedigitalcard of " . $_SESSION['new_year'] . " plan @" . $amount;
                $sms_message = "Purchased sharedigitalcard of " . $_SESSION['new_year'] . " plan @" . $amount . " and Invoice No: #INV" . $invoice_number;

                $sendDealerEmail = $manage->sendMail($dealer_name, $dealer_email, $dealer_subject, $dealer_subject);
                //$sendUserEmail = $manage->sendMail($invoice_name, $email, $sms_message, $message);
                if ($sendDealerEmail) {
               //     $sendUserSms = $manage->sendSMS($user_contact, $sms_message);
                    if ($sendUserSms) {
                 //       $sendDealerSms = $manage->sendSMS($dealer_contact_no, $dealer_subject);
                        if (!$sendDealerSms) {
                            $default_message .= "Issue while sending sms to dealer";
                        }else {
                            $default_message .= "Issue while sending email to dealer";
                        }
                    } else {
                        $default_message .= "Issue while sending sms to user";
                    }
                } else {
                    $default_message .= "Issue while sending email to user.";
                }
                echo "<style>.success{ display: block!important;}</style>";
            }

            /*}*/
        } else {
            /*  $get_count_suc = $manage->successOfUserPayment();
              if ($get_count_suc != 1) {*/

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

            $status_success = "success";
            $referal_by = "";
            $refrenced_by = "";
            $active_plan = 1;
            $discount_amount = 0;
            $update_user_plan = $manage->updateUserPlanStatus();
            $insertUserSubscription = $manage->insertUserDataForRazor($_SESSION['new_year'],$old_amount, $old_amount, $date, $expiry_date, $status_success, $referal_by, $refrenced_by, $active_plan, $invoice_number, $discount_amount, $new_tax, $amount, $paymentBrand, $paymentMode, $custBankId, $timestamp,$payment_type,$invoice_name,$email,$user_gstno,$user_pan_no,FROM_BILL,FROM_GSTNO,FROM_PAN,SAC_CODE,$data['order_id'],$_POST['razorpay_payment_id'],$data['error_code'],$data['error_description'],$user_address,$currency_type);
            $updateUserSubscription = $manage->updateUserExpiryDateForPayment($expiry_date);
        }

        /* $check_user_pay = $manage->checkUserPayStatus($key);
        if($check_user_pay['dealer_by_pay'] == 1){
            $get_user_data = $manage->getDealerInvoiceData($check_user_pay['referral_code'],$check_user_pay['invoice_no']);
         * */
        if($insertUserSubscription){
            $get_user_data = $manage->getUserInvoiceData($insertUserSubscription);
            if ($get_user_data != null) {
                $currency_type = $get_user_data['currency_type'];
                $user_expiry_date = $get_user_data['end_date'];
                $user_start_date = $get_user_data['start_date'];
                $invoice_name = $get_user_data['for_bill'];
                $for_email = $get_user_data['for_email'];
                $invoice_gstn_no = $get_user_data['for_gstno'];
                $invoice_pan_no = $get_user_data['for_pan'];
                $invoice_gstn_no_for_tax = $get_user_data['for_gstno'];
                $invoice_no = $get_user_data['invoice_no'];
                $year = $get_user_data['year'];
                $taxable_amount = $get_user_data['taxable_amount'];
                $discount = $get_user_data['discount'];
                $tax = $get_user_data['tax'];
                $half_tax = $tax / 2;
                $total_amount = $get_user_data['total_amount'];
                $plan_amount = $get_user_data['plan_amount'];
                $sac_code = $get_user_data['sac_code'];
                $from_name = $get_user_data['from_bill'];
                $from_gstno = $get_user_data['from_gstno'];
                $from_pan = $get_user_data['from_pan'];
                $credit_qty = $get_user_data['credit_qty'];
                $for_address = $get_user_data['user_address'];
                if($for_address !=''){
                    $for_address = "<br><strong> Address : </strong>".wordwrap($for_address,40,"<br>");
                }else{
                    $for_address = '';
                }
                if ($get_user_data['referenced_by'] == 'credit') {
                    $total_plan_amount = $plan_amount * $credit_qty;
                }
                if ($invoice_pan_no != '' && $invoice_gstn_no != '') {
                    $invoice_pan_no = "<br><strong>PAN No : </strong> " . $invoice_pan_no;
                } else {
                    $invoice_pan_no = "";
                }
                if ($invoice_gstn_no == '') {
                    $invoice_gstn_no = "not applicable";
                }
                if(trim($year) == "Life Time"){
                    $user_expiry_date = 'Life Time';
                }
                if($currency_type == 'USD'){
                    $invoice_tax_type= "Invoice";
                }else{
                    $invoice_tax_type= "Tax Invoice";
                }
                $message = '
<table style="width: 100%;border-collapse: collapse;" cellpadding="10" border="1" cellspacing="10">
<tr>
<td colspan="5">
<img src="https://sharedigitalcard.com/assets/img/invoice/header%20(1).PNG" style="width:100%">
</td>
</tr>
<tr>
<td colspan="5" style="text-align: center"><h2><b style="font-weight: 500;">'.$invoice_tax_type.'</b></h2></td>
</tr>
<tr>
<td colspan="3"><strong>' . $from_name . '</strong></td>

<td colspan="2" style="text-align: right">
      <strong>Invoice Number : </strong>#INV' . $invoice_no . '<br>
     <strong>Invoice Date : </strong>' . $user_start_date . '</td>
</tr>


<tr>
<td colspan="3"><strong>Email : </strong>support@sharedigitalcard.com,<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;marketing@sharedigitalcard.com</td>

<td colspan="2" style="text-align: right">
          <strong> Bill for : </strong>' . $invoice_name . '<br><strong> Email : </strong>' . $for_email . $for_address . '</td></td>
</tr>';

                if($currency_type !='USD') {
                    $message .= '<tr>
<td colspan="3"><strong>GSTN No : </strong> ' . $from_gstno . '<br><strong>PAN No : </strong> ' . $from_pan . '</td>
<td colspan="2" style="text-align: right"><strong>GSTN No</strong> : ' . $invoice_gstn_no . '</td>
</tr>
<tr>
<th>Package</th>
<th>Start Date</th>
<th>Expiry Date</th>
<th>Sac code</th>
<th>Amount</th>
</tr>
<tr>
<td style="text-align: center">' . $year . ' plan</td>
<td style="text-align: center">' . $user_start_date . '</td>
<td style="text-align: center">' . $user_expiry_date . '</td>
<td style="text-align: center">' . $sac_code . '</td>
<td style="text-align: right"> ' . $plan_amount . '</td>
</tr>
';
                }else {

                    $message .= '
 <tr>
<th colspan="2">Package</th>
<th>Start Date</th>
<th>Expiry Date</th>
<th>Amount</th>
</tr>
<tr>
<td colspan="2" style="text-align: center">' . $year . ' plan</td>
<td style="text-align: center">' . $user_start_date . '</td>
<td style="text-align: center">' . $user_expiry_date . '</td>
<td style="text-align: right"> '.$currency_symbol . $plan_amount . '</td>
</tr>';
                }

                // start
                if($currency_type !='USD') {
                    if ($get_user_data['referenced_by'] == "coupon" && $discount != '') {
                        $message .= '

<tr>
<td colspan="3"></td>
<td>Coupon Code : </td>
<td style="text-align: right">' . $get_user_data['referral_code'] . '</td>
</tr>
        <tr><td colspan="3"></td>
    <td>Discount : </td>
    <td style="text-align: right"><label style="background-color: #2b982b;">' . $discount . '</label></td>
</tr>
';
                    } elseif ($get_user_data['referenced_by'] == "admin" && $discount != '') {
                        $message .= '
        <tr><td colspan="3"></td>
    <td>Discount : </td>
    <td style="text-align: right"><label style="background-color: #2b982b;">' . $discount . '</label></td>
</tr>
';
                    } elseif ($get_user_data['referenced_by'] == "dealer") {
                        $message .= '
<tr><td colspan="3"></td>
<td>Referenced by : </td>
<td style="text-align: right">' . $get_user_data['referral_code'] . '</td>
</tr>
   <tr><td colspan="3"></td>
    <td>+ 4 month</td>
    <td style="text-align: right"><label style="background-color: #2b982b;">FREE</label></td>
    </tr>
';
                    } elseif ($get_user_data['referenced_by'] == "user") {
                        $message .= '
<tr><td colspan="3"></td>
<td>Referenced by : </td>
<td style="text-align: right">' . $get_user_data['referral_code'] . '</td>
</tr>
    <tr><td colspan="3"></td>
    <td>+ 2 month</td>
    <td style="text-align: right"><label style="background-color: #2b982b;">FREE</label></td>
    </tr>
    ';
                    }
                    // end
                    $message .= '
<tr>
<td colspan="3"></td>
<td> Taxable Amount : </td>
<td style="text-align: right">' . $taxable_amount . '</td>
</tr>';
                    if ($invoice_gstn_no_for_tax != null && substr($invoice_gstn_no_for_tax, 0, 2) != "27") {
                        $message .= '
<tr>
<td colspan="3"></td>
<td>IGST (18%): </td>
<td style="text-align: right">' . $tax . '</td>
</tr>';
                    } else {
                        $message .= '
<tr>
<td colspan="3"></td>
<td>CGST (9%): </td>
<td style="text-align: right">' . $half_tax . '</td>
</tr>
<tr>
<td colspan="3"></td>
<td>SGST (9%): </td>
<td style="text-align: right">' . $half_tax . '</td>
</tr>';
                    }
                }
                $message .= '
<tr>
<td colspan="3"></td>
<td>Total Amount : </td>
<td style="text-align: right">
'.$currency_symbol . $total_amount . '
</td>
</tr>
<tr>
<td colspan="5">
            <strong> Important: </strong>
             <ol>
                  <li>This is an electronic generated invoice.</li>
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
</table>
<br><br><footer></footer>';
                $sms_message = "Purchased sharedigitalcard of " . $_SESSION['new_year'] . " plan @" . $amount . " and Invoice No: #INV" . $invoice_number;

                $sendEmail = $manage->sendMail($invoice_name, $email, $sms_message, $message);
                if($currency_type !='USD') {
                    $sendSms = $manage->sendSMS($user_contact, $sms_message);
                }
                echo "<style>.success{ display: block!important;}</style>";
            }
        }else{
            echo "<style>.failed{ display: block!important;}</style>";
        }
    }else{
        if (isset($_SESSION['new_year'])) {
            $get_select_value = $manage->get_selected_value($_SESSION['new_year']);
            if ($get_select_value['amt'] != null) {
                $old_amount = $get_select_value['amt'];
            } else {
                $old_amount = 0;
            }

            $taxable_amount = $old_amount * 9 / 100;
            $new_tax = $taxable_amount + $taxable_amount;
            if(isset($_SESSION['coupon_code']) && $_SESSION['coupon_code'] !=''){
                $validateCouponCode = $manage->validateDiscountCode($_SESSION['coupon_code']);
                if($validateCouponCode){
                    $discount_amount = $old_amount * $validateCouponCode['discount'] / 100;
                    $new_total_grand = $old_amount - $discount_amount;
                    $taxable_amount = $new_total_grand * 9 / 100;
                    $new_tax = $taxable_amount + $taxable_amount;
                }else{
                    $discount_amount = 0;
                }

            }elseif (isset($_SESSION['user_dealer_code'])) {
                $discount_amount = 0;
            }

        }
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
    if (isset($_SESSION['new_year'])) {
        $get_select_value = $manage->get_selected_value($_SESSION['new_year']);
        if ($get_select_value['amt'] != null) {
            $old_amount = $get_select_value['amt'];
        } else {
            $old_amount = 0;
        }

        $taxable_amount = $old_amount * 9 / 100;
        $new_tax = $taxable_amount + $taxable_amount;
        if(isset($_SESSION['coupon_code']) && $_SESSION['coupon_code'] !=''){
            $validateCouponCode = $manage->validateDiscountCode($_SESSION['coupon_code']);
            if($validateCouponCode){
                $discount_amount = $old_amount * $validateCouponCode['discount'] / 100;
                $new_total_grand = $old_amount - $discount_amount;
                $taxable_amount = $new_total_grand * 9 / 100;
                $new_tax = $taxable_amount + $taxable_amount;
            }else{
                $discount_amount = 0;
            }

        }elseif (isset($_SESSION['user_dealer_code'])) {
            $discount_amount = 0;
        }

    }
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
?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Success page</title>
    <!-- Bootstrap Core Css -->
    <!-- <link href="assets/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">-->
    <!-- Waves Effect Css -->
    <!-- Custom Css -->
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-144581468-1');
    </script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bxslider/4.2.12/jquery.bxslider.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <!--<link rel="stylesheet" href="assets/css/jquery-ui.css">-->

    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/css/slicknav.min.css">

    <!--<link rel="stylesheet" href="assets/css/magnificpopup.css">-->
    <!--<link rel="stylesheet" href="assets/css/jquery.mb.YTPlayer.min.css">-->

    <link rel="stylesheet" href="assets/css/typography.css">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/png" href="assets/img/logo/favicon.png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    <!-- Bootstrap Core Css -->
    <!-- <link href="assets/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">-->
    <!-- Waves Effect Css -->
    <link href="user/assets/plugins/node-waves/waves.css" rel="stylesheet"/>
    <link href="assets/css/form.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>-->

</head>
<body>
<?php
if(!isset($_GET['type'])) {
    ?>
    <header id="header">
        <div class="header-area" style="padding: 10px 0;">
            <div class="container">
                <div class="row">
                    <div class="menu-area">
                        <div class="col-md-2 col-sm-12 col-xs-12 text-center playstore_logo">
                            <div class="logo">
                                <a href="index.php"><img src="assets/img/logo/logo.png" alt="Digital Card logo"></a>
                            </div>
                            <a class="xyz hidden-lg hidden-md hidden-sm hidden-xs" href="#"><img
                                    class="playstore_logo_img" src="assets/img/google-play-badge.png"
                                    alt="digital card app"></a>
                        </div>
                        <div class="col-md-10 hidden-xs hidden-sm">
                            <div class="main-menu">
                                <nav class="nav-menu">
                                    <ul>
                                        <li class=" abc">
                                            <!-- class="xyz" --> <a href="index.php">Suhas Gawde : 9773884631<br>Ajay
                                                Chorge
                                                : 9768904980</a></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="col-sm-12 col-xs-12 visible-sm visible-xs">

                            <div class="row" style="background: #eee">
                                <div class="mobile_menu"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
<?php
}
?>
<section class="feature-area bg-gray padding_section" id="feature">
    <div class="clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding_zero padding_zero_both">
            <div class="">
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
                                $custom_url= $get_user_data['custom_url'];
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
                                    <td><?php echo $currency_symbol. $amount ?></td>
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
                                } elseif (isset($_SESSION['user_dealer_code'])) {
                                    ?>
                                    <tr>
                                        <td>Referral by</td>
                                        <td><?php echo $_SESSION['user_dealer_code']; ?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                            <?php
                            if(isset($_GET['type']) && $_GET['type'] == 'android') {
                                ?>
                                <a href="activity://dashboard"
                                   type="button" target="_blank" name="continue"
                                   class="btn btn-success success_btn ">
                                    <i class="fa fa-file" aria-hidden="true"></i> Back To Dashboard
                                </a>
                            <?php
                            }else {
                                ?>
                                <form method="post" action="">
                                    <a href="https://sharedigitalcard.com/m/index.php?custom_url=<?php echo $custom_url; ?>"
                                       target="_blank" type="button" name="continue" class="success_btn btn btn-info">
                                        <i class="fa fa-id-card" aria-hidden="true"></i> Your Card
                                    </a>
                                    <a href="login.php?view_invoice=<?php echo $security->encrypt($insertUserSubscription); ?>"
                                       type="button" target="_blank" name="continue"
                                       class="btn btn-success success_btn ">
                                        <i class="fa fa-file" aria-hidden="true"></i> View Invoice
                                    </a>
                                    <a href="login.php" target="_blank" type="button" name="continue"
                                       class="btn btn-primary success_btn ">
                                        <i class="fa fa-user" aria-hidden="true"></i> Edit Profile
                                    </a>
                                </form>
                            <?php
                            }
                            ?>
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
                                } elseif (isset($_SESSION['user_dealer_code'])) {
                                    ?>
                                    <tr>
                                        <td>Referral by</td>
                                        <td><?php echo $_SESSION['user_dealer_code']; ?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                            <div>
                                <a href="login.php" class=" btn btn_success_btn_sub">
                                    CONTINUE
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php

unset($_SESSION['referral_code']);
unset($_SESSION['coupon_code']);
unset($_SESSION['user_dealer_code']);
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
