<?php
session_start();
require('config.php');
require('razorpay-php/Razorpay.php');
require("RazorpayMaster.php");

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

$master = new RazorpayMaster();

$success = true;
$error = "Payment Failed";

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

if ($success === true) {
    echo "<p>Your payment was successful</p>
             <p>Payment ID: {$_POST['razorpay_payment_id']}</p>";

    $data = $master->getPaymentDetails($_POST['razorpay_payment_id']);

    echo "<pre>";
    var_dump($data);
    echo "</pre>";

} else {
    echo "<p>Your payment failed</p>
             <p>{$error}</p>";
}