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
/*
$memberId = "10015"; //put in your merchantId you received after signing up
$totype = "paymentz"; // put you partner name
$key = "E1VAwHnKHf2YmAx6UOnkWs3fmD8fJ87b"; //put in the 32 bit alphanumeric key in the quotes provided here
$merchantTransactionId = rand(10000000,1000000000);*/


require('../controller/razorpay-php/Razorpay.php');

use Razorpay\Api\Api;
$api = new Api($keyId, $keySecret);

$form_data = $manage->getDealerProfile($_SESSION['dealer_id']);
if ($form_data != null) {
    $message_status = $form_data['message_status'];
    $dealer_status = $form_data['status'];
    $pay_status = $form_data['pay_status'];
    $deal_code = $form_data['dealer_code'];
    $dealer_gstn_no = $form_data['gstin_no'];
    $address = $form_data['address'];
    $city = $form_data['city'];//your script should substitute the customer's city
    $state = $form_data['state'];//your script should substitute the customer's state
}

$_SESSION['dealer_pricing_year'] = $_POST['dealer_pricing_year'];

$amount = number_format((float)$_POST['amount'], 2, '.', '');
$tax = $_POST['amount']*18/100;
$total_amount = $tax + $_POST['amount'];

$round_of_amount = round($total_amount);
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
$payment_data .=" <script>
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
        //if($('#term_cond').prop('checked') == true){
            rzp.open();
            $('.chk_error').hide();
            e.preventDefault();
        /*}else{
        $('.chk_error').text('Please check the term & condition checkbox in order to proceed.');
        }*/

        }
    </script>";
$returnData = array(
    'status' => 'ok',
    'msg' => 'success',
    'total_amount'=>round($total_amount),
    'data' => $payment_data
);
echo json_encode($returnData);
exit();


/*echo '<input type="hidden" name="totype" value=' . $totype . '>';
echo '<input type="hidden" name="memberId" value=' . $memberId . '>';
echo '<input type="hidden" name="amount" value=' . $amount . '>';
echo '<input type="hidden" name="TMPL_AMOUNT" value=' . $amount . '>';
$merchantRedirectUrl = "https://sharedigitalcard.com/dealer/payment-success.php";
$checksum = getchecksum($memberId, $totype, $amount, $merchantTransactionId, $merchantRedirectUrl, $key);
echo '<input type="hidden" name="merchantTransactionId" maxlength="100" value=' . $merchantTransactionId . '>';
echo '<input type="hidden" name="checksum" value=' . $checksum . '>';
echo '<input type="hidden" name="merchantRedirectUrl" value=' . $merchantRedirectUrl . '>';*/


?>