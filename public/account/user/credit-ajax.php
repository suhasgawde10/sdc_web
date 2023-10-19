<?php
ob_start();
include "../controller/ManageUser.php";
$manage = new ManageUser();
include "../controller/validator.php";
$validate = new Validator();
include '../sendMail/sendMail.php';
include("android-login.php");
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
require_once("functions.php");


/*require_once "../controller/RazorpayMaster.php";
$payment = new RazorpayMaster();*/

$form_data = $manage->getSpecificUserProfile();
$company_name = $form_data['company_name'];
$gst_no = $form_data['gst_no'];
$pan_no = $form_data['pan_no'];
$about_us = $form_data['about_company'];
$our_mission = $form_data['our_mission'];
$company_profile = $form_data['company_profile'];
$email = $form_data['email'];
$contact_no = $form_data['contact_no'];
$address = $form_data['address'];

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

$xml = simplexml_load_file("http://www.geoplugin.net/xml.gp?ip=" . getRealIpAddr());

$countryName = (string)$xml->geoplugin_countryName;

$memberId = "10015"; //put in your merchantId you received after signing up
$totype = "paymentz"; // put you partner name
/*$key = "Vh0Zqc9Q3F4Qxfhjo6uuM8jOznMtRpwB";*/
$key = "E1VAwHnKHf2YmAx6UOnkWs3fmD8fJ87b"; //put in the 32 bit alphanumeric key in the quotes provided here
$merchantTransactionId = rand(10000000, 1000000000);
include("session_includes.php");

require('../controller/razorpay-php/Razorpay.php');

use Razorpay\Api\Api;
$api = new Api($keyId, $keySecret);

/*For New Card Start*/
if (isset($_POST['radio_value'])) {
    $radio_value = $_POST['radio_value'];
    $quantity = $_POST['quantity'];
    if (isset($_POST['android_user_id']) && $_POST['android_user_id'] != "" && $_POST['type'] != "") {
        $android_url = "android_user_id=" . $_POST['android_user_id'] . "&type=" . $_POST['type'];
    }else{
        $android_url = "";
    }

        $get_select_value = $manage->get_selected_value($radio_value);
        if ($get_select_value['amt'] != null) {
            $amount = $get_select_value['amt'];
        } else {
            $amount = 0;
        }
         $total_without_tax = $amount * $quantity;
        $taxable_amount = $total_without_tax * 18 / 100;
        $newamount = $taxable_amount + $total_without_tax;
         $newamount1 = number_format((float)$newamount, 2, '.', '');

        $plan_year = "'".$radio_value."'";

        $payment_data = '<table class="table table-borderless">
        <tr>
<td>
Per Card Cost : 
</td>
<td class="text-right">
' . round($amount) . '
</td>
</tr>
<tr>
<td>
Quantity : 
</td>
<td class="text-right">
<input class="form-control quantity-cust" value="'.$quantity.'" type="number" placeholder="Enter Quantity" onchange="upgradeCreditByQuantity(' . $plan_year . ',this.value)">
</td>
</tr>
<tr>
     
     
                                        <tr>
                                            <td>Total '.$quantity.' Card : </td>
                                            <td class="text-right">' . round($total_without_tax) . '</td>
                                        </tr>


                                        <tr>
                                            <td>Tax (18%) : </td>
                                            <td class="text-right">' . round($taxable_amount) . '</td>
                                        </tr>
                                        <tr class="border-total-amount total_amount">
                                            <td><h5><b>Total amount :  </b></h5></td>
                                            <td class="text-right"><h5><b>' . round($newamount1) . '</b></h5></td>
                                        </tr>                               
                                         <tr class="pamentz_row">
                                             <td colspan="2">
                                    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                                        <input type="hidden" name="razorpay_signature" id="razorpay_signature">
                                        <button class="btn btn-primary form-control submit_btn" id="rzp-button1"
                                                name="upgrade_plan" type="button">Pay now ' . round($newamount1) . '/-</button>
                                        </td>

                                            </tr>';
        $_SESSION['new_year'] = $radio_value;
        $_SESSION['quantity'] = $quantity;
        $payment_data .='
        <input type="hidden" name="totype" value=' . $totype . '>
        <input type="hidden" name="memberId" value=' . $memberId . '>
        <input type="hidden" name="taxable_amount" value=' . $amount . '>
        <input type="hidden" name="total_amount" value=' . $newamount1 . '>
        <input type="hidden" name="total_tax" value=' . $taxable_amount . '>
        <input type="hidden" name="year" value=' . $radio_value . '>
        <input type="hidden" name="new_year" value=' . $radio_value . '>
        <input type="hidden" name="amount" value=' . $newamount1 . '>
        <input type="hidden" name="TMPL_AMOUNT" value=' . $newamount1 . '>';
        if ($android_url != "") {
            $merchantRedirectUrl = "https://sharedigitalcard.com/user/credit-success-page.php?" . $android_url;
        } else {
            $merchantRedirectUrl = "https://sharedigitalcard.com/user/credit-success-page.php";
        }

    $round_of_amount = round($newamount1);
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
             document.getElementById('closeTheModal').click();
            rzp.open();
            e.preventDefault();
        }
    </script>";

        $checksum = getchecksum($memberId, $totype, $newamount1, $merchantTransactionId, $merchantRedirectUrl, $key);
        $payment_data .='
        <input type="hidden" name="merchantTransactionId" maxlength="100" value=' . $merchantTransactionId . '>
        <input type="hidden" name="checksum" value=' . $checksum . '>
        <input type="hidden" name="merchantRedirectUrl" value=' . $merchantRedirectUrl . '>';

        $pay_amount = round($newamount1)*100;
        $returnData = array(
            'status' => 'ok',
            'msg' => 'OTP has been sent to your email id',
            'pay_amount'=>$pay_amount,
            'data' => $payment_data
        );
        echo json_encode($returnData);
        exit();



}

/*For New Card End */


?>