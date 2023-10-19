<?php

$merchantTransactionId = rand(100000000, 1000000000);

require("functions.php");


if (isset($_POST)) {

    if (isset($_POST["memberId"])) {
        $memberId = $_POST['memberId']; //put in your merchantId you received after signing up
        $totype = "paymentz"; // put you partner name
        $amount = $data['amount']; //your script should substitute the amount here
        $TMPL_AMOUNT = $data['amount'];//your script should substitute the amount in that customized currency
        $orderDescription = $data['year'] . "year plan"; //your script should substitute detailed description of your order here ( This field is not mandatory )
        $merchantRedirectUrl = "https://localhost/Digital_card/payment-testing/redirecturl.php"; //You need to change the URL as per your website and the location where you have kept provided redirecturl.php file
        $key = "Vh0Zqc9Q3F4Qxfhjo6uuM8jOznMtRpwB"; //put in the 32 bit alphanumeric key in the quotes provided here
        $country = "IN";//your script should substitute the customer's country code
        $TMPL_CURRENCY = "INR";//your script should substitute the currency symbol in which you want to display amount
        $currency = "INR";//your script should substitute the currency symbol in which you want to display amount
        $city = "Mumbai";//your script should substitute the customer's city
        $state = "Maharashtra";//your script should substitute the customer's state
        $street = "4 Bunglow";//your script should substitute the customer's street
        $postcode = "400052";//your script should substitute the customer's zip
        $phone = "9768904980";//your script should substitute the customer's actual telno
        $telnocc = "091";//your script should substitute the customer's contry code for tel no
        $email = "komal@kubictechnology.com";//your script should substitue the customer's email address
        $ip = "127.0.0.1"; // your script should replace it with your ip address
        $reservedField1 = ""; //As of now this field is reserved and you need not put anything
        $reservedField2 = ""; //As of now this field is reserved and you need not put anything
        $terminalid = "";   //terminalid if provided
        $paymentMode = ""; //payment type as applicable Credit Cards = CC, Vouchers = PV,  Ewallet = EW, NetBanking = NB
        $paymentBrand = ""; //card type as applicable Visa = VISA; MasterCard=MC; Dinners= DINER; Amex= AMEX; Disc= DISC; CUP=CUP
        $customerId = "44444";
        $checksum = "";
        $checksum = getchecksum($memberId, $totype, $amount, $merchantTransactionId, $merchantRedirectUrl, $key);
//$processUrl = "https://sandbox.paymentz.com/transaction/Checkout";
        $liveurl = "https://secure.live.com/transaction/PayProcessController";



        /*$url = 'payment-redirect.php';
        $data = array('amount' => $new_total_amount, 'new_year' => $new_year, 'memberId'=>$memberId);
        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );
        redirect_post($url, $options);*/



    }

}




?>
<html>
<head><title>Payment Page Request </title></head>
<body bgcolor="white">
<font size=4/>
    <form name="frm1" method="post" action="https://sandbox.paymentz.com/transaction/Checkout">
        <input type="hidden" name="memberId" maxlength="10" value=<?php echo $memberId; ?>>
        <input type="hidden" name="totype" value="<?php echo $totype; ?>">
        <input type="hidden" name="amount" value="<?php echo $amount; ?>">
        <input type="hidden" name="TMPL_AMOUNT" value="<?php echo $TMPL_AMOUNT; ?>">
        <input type="hidden" name="merchantTransactionId" maxlength="100" value="<?php echo $merchantTransactionId; ?>">
        <input type="hidden" name="orderDescription" value="<?php echo $orderDescription; ?>">
        <input type="hidden" name="merchantRedirectUrl" value="<?php echo $merchantRedirectUrl; ?>">
        <input type="hidden" name="checksum" value="<?php echo $checksum; ?>">
        <input type="hidden" name="ip" value="<?php echo $ip; ?>">
        <input type="hidden" name="reservedField1" value="<?php echo $reservedField1; ?>">
        <input type="hidden" name="reservedField2" value="<?php echo $reservedField2; ?>">
        <input type="hidden" name="country" value="<?php echo $country; ?>">
        <input type="hidden" name="currency" value="<?php echo $currency; ?>">
        <input type="hidden" name="TMPL_CURRENCY" value="<?php echo $TMPL_CURRENCY; ?>">
        <input type="hidden" name="city" value="<?php echo $city; ?>">
        <input type="hidden" name="state" value="<?php echo $state; ?>">
        <input type="hidden" name="street" value="<?php echo $street; ?>">
        <input type="hidden" name="postcode" value="<?php echo $postcode; ?>">
        <input type="hidden" name="phone" value="<?php echo $phone; ?>">
        <input type="hidden" name="telnocc" value="<?php echo $telnocc; ?>">
        <input type="hidden" name="email" value="<?php echo $email; ?>">
        <input type="hidden" name="terminalid" value="<?php echo $terminalid; ?>">
        <input type="hidden" name="paymentMode" value="<?php echo $paymentMode; ?>">
        <input type="hidden" name="paymentBrand" value="<?php echo $paymentBrand; ?>">
        <input type="hidden" name="customerId" value="<?php echo $customerId; ?>">
        <INPUT id="submit" TYPE="submit" value="submit">
    </form>
</font>
<!--<script src="assets/plugins/jquery/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        $("#submit").trigger("click");
    });
</script>-->
</body>
</html>
