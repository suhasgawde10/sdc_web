<?php
ob_start();
include "../controller/ManageDealer.php";
$manage = new ManageDealer();
include "../controller/validator.php";
$validate = new Validator();
include '../sendMail/sendMail.php';
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
require_once("functions.php");

$memberId = "10015"; //put in your merchantId you received after signing up
$totype = "paymentz"; // put you partner name
/*$key = "Vh0Zqc9Q3F4Qxfhjo6uuM8jOznMtRpwB";*/
$key = "E1VAwHnKHf2YmAx6UOnkWs3fmD8fJ87b"; //put in the 32 bit alphanumeric key in the quotes provided here
$merchantTransactionId = rand(10000000,1000000000);

$display_message = $manage->displayDealerProfile();
if ($display_message != null) {
    $get_percent = $display_message['dealer_percent'];
    $address = $display_message['address'];
}

require('../controller/razorpay-php/Razorpay.php');

use Razorpay\Api\Api;
$api = new Api($keyId, $keySecret);

if(isset($_POST['dealer_code'])){
    $dealer_code = $_POST['dealer_code'];
    $radio_value = $_POST['year'];
    $user_id = $_POST['user_id'];
    $get_prcent_data = $manage->getDealerPricingById($get_percent);
    $dealer_percent = $get_prcent_data['percentage'];
    $get_select_value = $manage->get_selected_value($radio_value);
    if($get_select_value['amt']!=null){
        $new_amt = $get_select_value['amt'];
        $new_amt = $dealer_percent * $new_amt/100;
        $new_amt = $get_select_value['amt'] - $new_amt;
    }else{
        $new_amt =0;
    }

    if($new_amt !=0){
        $taxable_amount = $new_amt * 18/100;
        $new_total_amount = number_format((float)$taxable_amount + $new_amt, 2, '.', '');
    }else{
        $new_amt =0;
        $taxable_amount = 0;
        $new_total_amount = 0;
    }

    /*echo "Total amount : ". $newamount;*/

   /* $new_grand_amount = $new_amt *10/100;
    $new_total_grand = $new_amt-$new_grand_amount;
    $new_tax = $new_total_grand*18/100;*/
   /* $new_total_amount = $newamount + $new_total_grand;*/
    $payment_data = '<tr>
                                            <td>' . $radio_value . ' Plan &nbsp;</td>
                                            <td class="text-right">' . round($new_amt).'</td>
                                        </tr>
                                        <tr>
                                            <td>Tax (18%) : </td>
                                            <td class="text-right">' . round($taxable_amount). '</td>
                                        </tr>
                                        <tr class="border-total-amount total_amount">
                                            <td><h5><b>Total amount :  </b></h5></td>
                                            <td class="text-right"><h5><b>' . round($new_total_amount). '</b></h5></td>
                                        </tr>';
    if(!isset($_POST['razor_pay'])) {
        if ($radio_value != "Free Trail (5 days)") {
            $payment_data .= '<tr><td colspan="2">
                                         <button class="btn btn-primary form-control" name="pay_now" type="submit">Pay now
                                            </button></td></tr>';
        } else {
            $redirect_url = "free-trial.php?user_id=" . $security->encrypt($user_id);
            $payment_data .= '<tr><td colspan="2">
                                         <a class="btn btn-primary form-control" href="' . $redirect_url . '">CLICK HERE TO GET FREE
                                            </a></td></tr>';
        }
        $payment_data .= '</table>';
    }
        $payment_data .= '<input type="hidden" name="new_total_amount" value=' . $new_total_amount .'>';
        $payment_data .= '<input type="hidden" name="dealer_code" value=' . $dealer_code .'>';

    $_SESSION['new_year'] = $radio_value;

       /* $payment_data .= '<input type="hidden" name="new_year" value=' . $radio_value . '>';

    $payment_data .= '<input type="hidden" name="totype" value=' . $totype . '>';
    $payment_data .= '<input type="hidden" name="memberId" value=' . $memberId . '>';
    $payment_data .= '<input type="hidden" name="amount" value=' . $new_total_amount . '>';
    $payment_data .= '<input type="hidden" name="TMPL_AMOUNT" value=' . $new_total_amount . '>';
    $merchantRedirectUrl = "https://sharedigitalcard.com/dealer/success-page.php?user_id=".$user_id;
    $checksum = getchecksum($memberId, $totype, $new_total_amount, $merchantTransactionId, $merchantRedirectUrl, $key);
    $payment_data .= '<input type="hidden" name="merchantTransactionId" maxlength="100" value=' . $merchantTransactionId . '>';
    $payment_data .= '<input type="hidden" name="checksum" value=' . $checksum . '>';
    $payment_data .= '<input type="hidden" name="merchantRedirectUrl" value=' . $merchantRedirectUrl . '>';*/

    if($new_amt !=0) {
        $round_of_amount = round($new_total_amount);
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
        $payment_data .= " <script>
        var options = $json;

        options.handler = function (response) {
            document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
            document.getElementById('razorpay_signature').value = response.razorpay_signature;
            document.razorpayform.submit();
        };
        options.theme.image_padding = false;
        options.modal = {
            ondismiss: function () {
                console.log('This code runs when the popup is closed');
            },
            escape: true,
            backdropclose: false
        };
        var rzp = new Razorpay(options);
        document.getElementById('rzp-button1').onclick = function (e) {
            getFormData();
            rzp.open();
            e.preventDefault();
        }
    </script>";
    }

    if ($radio_value != "Free Trail (5 days)") {
        $user_plan_status = 'paid';
    } else {
        $user_plan_status = 'trial';
    }
    $pay_amount = round($new_total_amount)*100;
    $current_amount =round($new_total_amount);
    $returnData = array(
        'status' => 'ok',
        'msg' => 'success',
        'pay_amount'=>$pay_amount,
        'current_amount'=>$current_amount,
        'user_plan_status'=>$user_plan_status,
        'data' => $payment_data
    );
    echo json_encode($returnData);
    exit();

}

if(isset($_POST['updatate_company']) && !empty($_POST['updatate_company'])){
    $company = $_POST['updatate_company'];
    $gst = $_POST['gst_no'];
    $update = $manage->md_updateCompanyInfoDealer($company,$gst,$pan_no);
    if($update){
        echo true;
    }else{
        echo false;
    }
}
if (isset($_POST['txt_name']) && $_POST['txt_name'] != '') {
    $_SESSION['invoice_name'] = $_POST['txt_name'];
}
if (isset($_POST['company_name']) && $_POST['company_name'] != '') {
    $_SESSION['invoice_company_name'] = $_POST['company_name'];
}
if (isset($_POST['txt_gst_no']) && $_POST['txt_gst_no'] != '') {
    $_SESSION['invoice_gst_no'] = $_POST['txt_gst_no'];
}
if (isset($_POST['txt_address']) && $_POST['txt_address'] != '') {
    $_SESSION['invoice_address'] = $_POST['txt_address'];
}



/*
 *       <tr class="validate_referral_code"><td id="add_gst_no" colspan="2" class="text-center"><a data-toggle="modal"
                                   data-target="#user_company_info">Do you want to add gst no?</a></td></tr>*/
?>