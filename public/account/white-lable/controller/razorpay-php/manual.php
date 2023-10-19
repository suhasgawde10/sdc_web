<?php
require('config.php');
require('razorpay-php/Razorpay.php');
session_start();

use Razorpay\Api\Api;

$api = new Api($keyId, $keySecret);
$orderData = array(
    'receipt' => 3456,
    'amount' => 1 * 100,
    'currency' => 'INR',
    'payment_capture' => 1 // auto capture
);

$razorPayOrder = $api->order->create($orderData);
$razorPayOrderId = $razorPayOrder['id'];
$_SESSION['razorpay_order_id'] = $razorPayOrderId;
$displayAmount = $amount = $orderData['amount'];

$data = array(
    "key" => $keyId,
    "amount" => $amount,
    "name" => "DJ Tiesto",
    "description" => "Tron Legacy",
    "image" => "https://s29.postimg.org/r6dj1g85z/daft_punk.jpg",
    "prefill" => array(
        "name" => "Daft Punk",
        "email" => "customer@merchant.com",
        "contact" => "9999999999",
    ),
    "notes" => array(
        "address" => "Hello World",
        "merchant_order_id" => "12312321",
    ),
    "theme" => array(
        "color" => "#F37254"
    ),
    "order_id" => $razorPayOrderId,
);
$json = json_encode($data);
?>
<button id="rzp-button1">Pay with Razorpay</button>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<form name='razorpayform' action="verify.php" method="POST">
    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
    <input type="hidden" name="razorpay_signature" id="razorpay_signature">
</form>
<script>
    var options = <?php echo $json?>;

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
        e.preventDefault();
    }
</script>
