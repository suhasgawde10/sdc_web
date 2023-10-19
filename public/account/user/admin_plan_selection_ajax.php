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

$form_data = $manage->getSpecificUserProfile();
$company_name = $form_data['company_name'];
$gst_no = $form_data['gst_no'];
$pan_no = $form_data['pan_no'];
$about_us = $form_data['about_company'];
$our_mission = $form_data['our_mission'];
$company_profile = $form_data['company_profile'];
if(isset($_POST['send_notification'])){
    $message = "User ".$session_name." want to purchase " . $_SESSION['new_year']. " plan of share digital card.\nContact number : ".$session_contact_no;
   $send = $manage->sendSMS($global_contact,$message);
    $send1 = $manage->sendSMS("9773884631",$message);
}
if (isset($_POST['radio_value'])) {
    $radio_value = $_POST['radio_value'];
    if (isset($_POST['android_user_id']) && $_POST['android_user_id'] != "" && $_POST['type'] != "") {
        $android_url = "android_user_id=" . $_POST['android_user_id'] . "&type=" . $_POST['type'];
    }
    if ($radio_value != "Free Trail (5 days)") {
        $get_select_value = $manage->get_selected_value($radio_value);
        if ($get_select_value['amt'] != null) {
            $amount = $get_select_value['amt'];
        } else {
            $amount = 0;
        }
        $taxable_amount = $amount * 18 / 100;
        /*echo "Tax (18%) : ".$taxable_amount . "<br><br>";*/
        $newamount = $taxable_amount + $amount;
        $newamount1 = number_format((float)$newamount, 2, '.', '');
        /*echo "Total amount : ". $newamount;*/

        $payment_data = '
<tr>
                                            <td>' . $radio_value . ' Plan &nbsp;</td>
                                            <td class="text-right">' . round($amount) . '</td>
                                        </tr>
                                         <tr class="extra_month">
                                        </tr>
                                        <tr>
                                            <td>Tax (18%) : </td>
                                            <td class="text-right">' . round($taxable_amount) . '</td>
                                        </tr>
                                        <tr class="border-total-amount total_amount">
                                            <td><h5><b>Total amount :  </b></h5></td>
                                            <td class="text-right"><h5><b>' . round($newamount1) . '</b></h5></td>
                                        </tr>
                                        <tr class="validate_referral_code"><td><a data-toggle="modal"
                                   data-target="#user_referral_code">Have a Referral code?</a></td>
                                        <td class="text-right"><a data-toggle="modal"
                                   data-target="#user_dealer_code">Have a dealer code?</a></td></tr>
                                   <tr class="code_msg">
                                   </tr>
                                   <tr class="validate_referral_code"><td id="add_gst_no"><a data-toggle="modal"
                                   data-target="#user_company_info">want to add gst no?</a></td>';
        if (isset($_SESSION['type']) && $_SESSION['type'] == 'Admin') {
            $payment_data .= '
                                   <td class="text-right"><a data-toggle="modal"
                                   data-target="#admin_discount">want to give any discount?</a></td>';
        }else{
            $payment_data .= '<td class="text-right"><a data-toggle="modal"
                                   data-target="#user_coupon_code">Have a coupon code?</a></td>';
        }
        $payment_data .= '</tr>

                                         <tr class="pamentz_row"><td colspan="2">

                                         <button class="btn btn-primary form-control submit_btn" name="upgrade_plan" type="button" onclick="sendNotification()">Subscribe now
                                            </button></td>

                                            </tr>';
 $_SESSION['new_year'] = $radio_value;
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
            $merchantRedirectUrl = "https://sharedigitalcard.com/user/success-page.php?" . $android_url;
        } else {
            $merchantRedirectUrl = "https://sharedigitalcard.com/user/success-page.php";
        }


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
    } else {
        $amount = 0;

        echo '<table class="table table-borderless"><tr>
                                            <td>' . $radio_value . ' Plan &nbsp;</td>
                                            <td class="text-right"><label class="badge badge-success">Free</label></td>
                                        </tr>
                                         <tr class="extra_month">
                                        </tr>
                                        <tr>
                                            <td>Tax (18%) : </td>
                                            <td class="text-right">' . round($amount) . '</td>
                                        </tr>

                                        <tr class="border-total-amount total_amount">
                                            <td><h5><b>Total amount :  </b></h5></td>
                                            <td class="text-right"><h5><b>' . round($amount) . '</b></h5></td>
                                        </tr>
                                   <tr class="code_msg">
                                   </tr>
                                         <tr><td colspan="2">
                                         <a class="btn btn-primary form-control" href="free-trial.php">
                                    CLICK HERE TO GET FREE
                                </a></td></tr>
                                        </table>';
    }
}
if (isset($_POST['get_amount'])) {
    if ($_POST['android_user_id'] != "" && $_POST['type'] != "") {
        $android_url = "android_user_id=" . $_POST['android_user_id'] . "&type=" . $_POST['type'];
    }
    $get_amount = $_POST['get_amount'];
    $get_select_value = $manage->get_selected_value($get_amount);
    if ($get_select_value != null) {
        $amount = $get_select_value['amt'];
    }
    echo $get_amount . " " . $amount;
}

if (isset($_POST['refereal_code'])) {
    if (isset($_POST['android_user_id']) && $_POST['android_user_id'] != "" && $_POST['type'] != "") {
        $android_url = "android_user_id=" . $_POST['android_user_id'] . "&type=" . $_POST['type'];
    }
    $refereal_code = $_POST['refereal_code'];
    $validateReferealCode = $manage->validateReferalCode($refereal_code);
    if ($validateReferealCode) {
        echo '
        <td>' . $refereal_code . '</td>
                                   <td class="text-right"><a onclick="InvalidReferralCode()" href="#"><i class="fa fa-trash" aria-hidden="true"></i></a></td>
        ';
        echo '<script>
$("#user_referral_code").modal("hide");
</script>';
        $_SESSION['referral_code'] = $refereal_code;
        echo '<input type="hidden" name="referral_code" value=' . $refereal_code . '>';
    } else {
        unset($_SESSION['referral_code']);
    }
}
if (isset($_POST['check_refereal_code'])) {
    if (isset($_POST['android_user_id']) && $_POST['android_user_id'] != "" && $_POST['type'] != "") {
        $android_url = "android_user_id=" . $_POST['android_user_id'] . "&type=" . $_POST['type'];
    }
    $refereal_code = $_POST['check_refereal_code'];
    $validateReferealCode = $manage->validateReferalCode($refereal_code);
    if ($validateReferealCode) {
        $_SESSION['referral_code'] = $refereal_code;
        echo 'Valid Referral code';
        echo '<script>
$("#user_referral_code").modal("hide");
</script>';
    } else {
        unset($_SESSION['referral_code']);
        echo 'Invalid Referral code';
    }
}

if (isset($_POST['check_code'])) {
    if (isset($_POST['android_user_id']) && $_POST['android_user_id'] != "" && $_POST['type'] != "") {
        $android_url = "android_user_id=" . $_POST['android_user_id'] . "&type=" . $_POST['type'];
    }
    $check_code = $_POST['check_code'];
    $validateReferealCode = $manage->validateReferalCode($check_code);
    if ($validateReferealCode) {
        echo '

                                   <td>+ 2 month </td>
                                   <td class="text-right"><label class="label label-success">Free</label></td>
                                   ';
        echo ' <style>
.validate_referral_code{
display: none;
}
</style>';
    } else {
        unset($_SESSION['referral_code']);
    }

}


if (isset($_POST['dealer_code'])) {
    unset($_SESSION['referral_code']);
    if (isset($_POST['android_user_id']) && $_POST['android_user_id'] != "" && $_POST['type'] != "") {
        $android_url = "android_user_id=" . $_POST['android_user_id'] . "&type=" . $_POST['type'];
    }
    if (isset($_POST['year'])) {
        $year = $_POST['year'];
    }
    $radio_value = $_POST['year'];
    $get_select_value = $manage->get_selected_value($radio_value);
    if ($get_select_value['amt'] != null) {
        $amount = $get_select_value['amt'];
    } else {
        $amount = 0;
    }
    $taxable_amount = $amount * 18 / 100;
    /*echo "Tax (18%) : ".$taxable_amount . "<br><br>";*/
    $newamount = $taxable_amount + $amount;
    $newamount1 = number_format((float)$newamount, 2, '.', '');
    $dealer_code = $_POST['dealer_code'];
    $validateDealerCode = $manage->validateDealerCode($dealer_code);
    if ($validateDealerCode) {

        echo '<table class="table table-borderless"><tr>
                                            <td>' . $radio_value . ' Plan &nbsp;</td>
                                            <td class="text-right">' . round($amount) . '</td>
                                        </tr>
                                         <tr>
                                        <td>+ 4 month : </td>
                                        <td class="text-right"><label class="label label-success">Free</label></td>
                                        </tr>
                                        <tr>
                                            <td>Tax (18%) : </td>
                                            <td class="text-right">' . round($taxable_amount) . '</td>
                                        </tr>
                                        <tr class="border-total-amount total_amount">
                                            <td><h5><b>Total amount :  </b></h5></td>
                                            <td class="text-right"><h5><b>' . round($newamount1) . '</b></h5></td>
                                        </tr>
                                        <tr>
                                   <td>' . $dealer_code . '</td>
                                   <td class="text-right"><a onclick="InvalidDealerCode()" href="#"><i class="fa fa-trash" aria-hidden="true"></i></a></td>
                                   </tr>';
        if ($radio_value != "Free Trail (5 days)") {
            echo '<tr><td colspan="2">
                                         <button class="btn btn-primary form-control submit_btn" name="upgrade_plan" type="button" onclick="sendNotification()">Subscribe now
                                            </button></td></tr>';
                                            echo '<input type="hidden" name="memberId" value=' . $memberId . '>';
        echo '<input type="hidden" name="taxable_amount" value=' . $amount . '>';
        echo '<input type="hidden" name="total_amount" value=' . $newamount1 . '>';
        echo '<input type="hidden" name="total_tax" value=' . $taxable_amount . '>';
        echo '<input type="hidden" name="year" value=' . $radio_value . '>';
        echo '<input type="hidden" name="amount" value=' . $newamount1 . '>';
        echo '<input type="hidden" name="TMPL_AMOUNT" value=' . $newamount1 . '>';

        echo '<input type="hidden" name="new_total_amount" value=' . $newamount1 . '>';
        echo '<input type="hidden" name="dealer_code" value=' . $dealer_code . '>';
        echo '<input type="hidden" name="new_year" value=' . $radio_value . '>';
        echo '<input type="hidden" name="totype" value=' . $totype . '>';
        if ($android_url != "") {
            $merchantRedirectUrl = "https://sharedigitalcard.com/user/success-page.php?dealer_code=" . $dealer_code . "&" . $android_url;
        } else {
            $merchantRedirectUrl = "https://sharedigitalcard.com/user/success-page.php?dealer_code=" . $dealer_code;
        }
        $checksum = getchecksum($memberId, $totype, $newamount1, $merchantTransactionId, $merchantRedirectUrl, $key);
        echo '<input type="hidden" name="merchantTransactionId" maxlength="100" value=' . $merchantTransactionId . '>';
        echo '<input type="hidden" name="checksum" value=' . $checksum . '>';
        echo '<input type="hidden" name="merchantRedirectUrl" value=' . $merchantRedirectUrl . '>';
        }else{
            echo ' <tr><td colspan="2">
                                         <a class="btn btn-primary form-control" href="free-trial.php">
                                    CLICK HERE TO GET FREE
                                </a></td></tr>';
        }
        echo ' </table>';

        
    } else {
        echo '<table class="table table-borderless"><tr>
                                            <td>' . $radio_value . ' Plan &nbsp;</td>
                                            <td class="text-right">' . round($amount) . '</td>
                                        </tr>
                                         <tr class="extra_month">
                                        </tr>
                                        <tr>
                                            <td>Tax (18%) : </td>
                                            <td class="text-right">' . round($taxable_amount) . '</td>
                                        </tr>

                                        <tr class="border-total-amount total_amount">
                                            <td><h5><b>Total amount :  </b></h5></td>
                                            <td class="text-right"><h5><b>' . round($newamount1) . '</b></h5></td>
                                        </tr>
                                        <tr class="validate_referral_code"><td><a data-toggle="modal"
                                   data-target="#user_referral_code">Have a Referral code?</a></td>
                                        <td class="text-right"><a data-toggle="modal"
                                   data-target="#user_dealer_code">Have a dealer code?</a></td></tr>
                                   <tr class="code_msg">
                                   </tr>
        <tr class="validate_referral_code"><td id="add_gst_no"><a data-toggle="modal"
                                   data-target="#user_company_info">want to add gst no?</a></td>';
        if (isset($_SESSION['type']) && $_SESSION['type'] == 'Admin') {
            echo '
        <td class="text-right"><a data-toggle="modal"
                                   data-target="#admin_discount">want to give any discount?</a></td></tr>';
        }else{
            echo  '<td class="text-right"><a data-toggle="modal"
                                   data-target="#user_coupon_code">Have a coupon code?</a></td></tr>';
        }
                                      if ($radio_value != "Free Trail (5 days)") {
            echo '<tr><td colspan="2">
                                         <button class="btn btn-primary form-control submit_btn" name="upgrade_plan" type="button" onclick="sendNotification()">Subscribe now
        </button></td></tr>';
          echo '<input type="hidden" name="taxable_amount" value=' . $amount . '>';
        echo '<input type="hidden" name="total_amount" value=' . $newamount1 . '>';
        echo '<input type="hidden" name="total_tax" value=' . $taxable_amount . '>';
        echo '<input type="hidden" name="year" value=' . $radio_value . '>';
        echo '<input type="hidden" name="totype" value=' . $totype . '>';
        echo '<input type="hidden" name="memberId" value=' . $memberId . '>';
        echo '<input type="hidden" name="grand_amount" value=' . $newamount1 . '>';
        echo '<input type="hidden" name="new_year" value=' . $radio_value . '>';
        echo '<input type="hidden" name="amount" value=' . $newamount1 . '>';
        echo '<input type="hidden" name="TMPL_AMOUNT" value=' . $newamount1 . '>';
        if ($android_url != "") {
            $merchantRedirectUrl = "https://sharedigitalcard.com/user/success-page.php?" . $android_url;
        } else {
            $merchantRedirectUrl = "https://sharedigitalcard.com/user/success-page.php";
        }
        $checksum = getchecksum($memberId, $totype, $newamount1, $merchantTransactionId, $merchantRedirectUrl, $key);
        echo '<input type="hidden" name="merchantTransactionId" maxlength="100" value=' . $merchantTransactionId . '>';
        echo '<input type="hidden" name="checksum" value=' . $checksum . '>';
        echo '<input type="hidden" name="merchantRedirectUrl" value=' . $merchantRedirectUrl . '>';
        }else{
            echo ' <tr><td colspan="2">
                                         <a class="btn btn-primary form-control" href="free-trial.php">
            CLICK HERE TO GET FREE
        </a></td></tr>';
        }
        echo ' </table>';
      
    }
    $_SESSION['new_year'] = $radio_value;
}

if (isset($_POST['check_dealer_code'])) {
    if (isset($_POST['android_user_id']) && $_POST['android_user_id'] != "" &&
        isset($_POST['type']) && $_POST['type'] != "") {
        $android_url = "android_user_id=" . $_POST['android_user_id'] . "&type=" . $_POST['type'];
    }
    $dealer_code = $_POST['check_dealer_code'];
    $validateDealerCode = $manage->validateDealerCode($dealer_code);
    if ($validateDealerCode) {
        echo '<script>
$("#user_dealer_code").modal("hide");
</script>';
    } else {
        echo 'Invalid Dealer Code';
    }
}


if (isset($_POST['discount_code'])) {
    if (isset($_POST['year'])) {
        $year = $_POST['year'];
    }
    $radio_value = $_POST['year'];
    $get_select_value = $manage->get_selected_value($radio_value);
    if ($get_select_value['amt'] != null) {
        $amount = $get_select_value['amt'];
    } else {
        $amount = 0;
    }
    $taxable_amount = $amount * 18 / 100;
    /*echo "Tax (18%) : ".$taxable_amount . "<br><br>";*/
    $newamount = $taxable_amount + $amount;
    $newamount1 = number_format((float)$newamount, 2, '.', '');
    /*echo "Total amount : ". $newamount;*/
    $discount_code = $_POST['discount_code'];
    $new_grand_amount = $amount * $discount_code / 100;
    $new_total_grand = $amount - $new_grand_amount;
    $new_tax = $new_total_grand * 18 / 100;
    $new_total_amount = $new_tax + $new_total_grand;
    $new_total_amount1 = number_format((float)$new_total_amount, 2, '.', '');

    echo '<table class="table table-borderless"><tr>
                                            <td>' . $radio_value . ' Plan &nbsp;</td>
                                            <td class="text-right">' . round($amount) . '</td>
                                        </tr>
                                         <tr>
                                        <td>Discount (' . $discount_code . '%) : </td>
                                        <td class="text-right"><label class="label label-success">- ' . round($new_grand_amount) . '</label></td>
                                        </tr>
                                        <tr>
                                            <td>Tax (18%) : </td>
                                            <td class="text-right">' . round($new_tax) . '</td>
                                        </tr>
                                        <tr class="border-total-amount total_amount">
                                            <td><h5><b>Total amount :  </b></h5></td>
                                            <td class="text-right"><h5><b>' . round($new_total_amount1) . '</b></h5></td>
                                        </tr>
                                        <tr>
                                   <td>Want to remove?</td>
                                   <td class="text-right"><a onclick="InvalidDealerCode()" href="#"><i class="fa fa-trash" aria-hidden="true"></i></a></td>
                                   </tr>';
                                         if ($radio_value != "Free Trail (5 days)") {
            echo '<tr><td colspan="2">
                                         <button class="btn btn-primary form-control" name="upgrade_plan" type="submit">Subscribe now
    </button></td></tr>';
        }else{
            echo ' <tr><td colspan="2">
                                         <a class="btn btn-primary form-control" href="free-trial.php">
        CLICK HERE TO GET FREE
    </a></td></tr>';
        }
        echo ' </table>';
    echo '<input type="hidden" name="taxable_amount" value=' . $amount . '>';
    echo '<input type="hidden" name="total_amount" value=' . $new_total_amount1 . '>';
    echo '<input type="hidden" name="admin_discount" value=' . $new_grand_amount . '>';
    echo '<input type="hidden" name="total_tax" value=' . $new_tax . '>';
    echo '<input type="hidden" name="year" value=' . $radio_value . '>';
    echo '<input type="hidden" name="new_total_amount" value=' . $new_total_amount1 . '>';
    echo '<input type="hidden" name="new_year" value=' . $radio_value . '>';
    echo '<input type="hidden" name="totype" value=' . $totype . '>';
    echo '<input type="hidden" name="memberId" value=' . $memberId . '>';
    echo '<input type="hidden" name="amount" value=' . $new_total_amount1 . '>';
    echo '<input type="hidden" name="TMPL_AMOUNT" value=' . $new_total_amount1 . '>';


}


if (isset($_POST['coupon_code'])) {
    unset($_SESSION['referral_code']);
    if (isset($_POST['android_user_id']) && $_POST['android_user_id'] != "" && $_POST['type'] != "") {
        $android_url = "android_user_id=" . $_POST['android_user_id'] . "&type=" . $_POST['type'];
    }
    if (isset($_POST['year'])) {
        $year = $_POST['year'];
    }
    $radio_value = $_POST['year'];
    $get_select_value = $manage->get_selected_value($radio_value);
    if ($get_select_value['amt'] != null) {
        $amount = $get_select_value['amt'];
    } else {
        $amount = 0;
    }
    $taxable_amount = $amount * 18 / 100;
    /*echo "Tax (18%) : ".$taxable_amount . "<br><br>";*/
    $newamount = $taxable_amount + $amount;
    $newamount1 = number_format((float)$newamount, 2, '.', '');
    /*echo "Total amount : ". $newamount;*/
    $coupon_code = $_POST['coupon_code'];
    $validateCouponCode = $manage->validateDiscountCode($coupon_code);

    if ($validateCouponCode) {
        $new_grand_amount = $amount * $validateCouponCode['discount'] / 100;
        $new_total_grand = $amount - $new_grand_amount;
        $new_tax = $new_total_grand * 18 / 100;
        $new_total_amount = $new_tax + $new_total_grand;
        $new_total_amount1 = number_format((float)$new_total_amount, 2, '.', '');
    }else{
        $new_tax = $taxable_amount;
        $new_total_amount1 = $newamount1;
    }

    echo '<table class="table table-borderless"><tr>
                                            <td>' . $radio_value . ' Plan &nbsp;</td>
                                            <td class="text-right">' . round($amount) . '</td>
                                        </tr>';
    if ($validateCouponCode) {
        echo '<tr>
                                        <td>Discount (' . $validateCouponCode['discount'] . '%) : </td>
                                        <td class="text-right"><label class="label label-success">- ' . round($new_grand_amount) . '</label></td>
                                        </tr>';
        echo '<tr>
                                        <td>Taxable Amount : </td>
                                        <td class="text-right"> ' . round($new_total_grand) . '</td>
                                        </tr>';
    }
                                       echo ' <tr>
                                            <td>Tax (18%) : </td>
                                            <td class="text-right">' . round($new_tax) . '</td>
                                        </tr>
                                        <tr class="border-total-amount total_amount">
                                            <td><h5><b>Total amount :  </b></h5></td>
                                            <td class="text-right"><h5><b>' . round($new_total_amount1) . '</b></h5></td>
                                        </tr>
                                        <tr>';

    if ($validateCouponCode) {
        echo '<td>' . $coupon_code . '</td>
                                   <td class="text-right"><a onclick="InvalidDealerCode()" href="#"><i class="fa fa-trash" aria-hidden="true"></i></a></td>
                                   </tr>';
    }else {
        echo ' <tr class="validate_referral_code"><td><a data-toggle="modal"
                                   data-target="#user_referral_code">Have a Referral code?</a></td>
                                        <td class="text-right"><a data-toggle="modal"
                                   data-target="#user_dealer_code">Have a dealer code?</a></td></tr>
                                   <tr class="code_msg">
                                   </tr>
                                    <tr class="validate_referral_code"><td id="add_gst_no"><a data-toggle="modal"
                                   data-target="#user_company_info">want to add gst no?</a></td>';
        if (isset($_SESSION['type']) && $_SESSION['type'] == 'Admin') {
            echo '
        <td class="text-right"><a data-toggle="modal"
                                   data-target="#admin_discount">want to give any discount?</a></td></tr>';
        }else{
            echo  '<td class="text-right"><a data-toggle="modal"
                                   data-target="#user_coupon_code">Have a coupon code?</a></td></tr>';
        }
    }
        if ($radio_value != "Free Trail (5 days)") {
            echo '<tr><td colspan="2">
                                         <button class="btn btn-primary form-control submit_btn" name="upgrade_plan" type="button" onclick="sendNotification()">Subscribe now
                                            </button></td></tr>';
        }else{
            echo ' <tr><td colspan="2">
                                         <a class="btn btn-primary form-control" href="free-trial.php">
                                    CLICK HERE TO GET FREE
                                </a></td></tr>';
        }
        echo ' </table>';
        echo '<input type="hidden" name="taxable_amount" value=' . $amount . '>';
        echo '<input type="hidden" name="total_amount" value=' . $new_total_amount1 . '>';
        echo '<input type="hidden" name="total_tax" value=' . $new_tax . '>';
        echo '<input type="hidden" name="year" value=' . $radio_value . '>';
        echo '<input type="hidden" name="new_total_amount" value=' . $new_total_amount1 . '>';
        echo '<input type="hidden" name="dealer_code" value=' . $coupon_code . '>';
        echo '<input type="hidden" name="new_year" value=' . $radio_value . '>';
        echo '<input type="hidden" name="totype" value=' . $totype . '>';
        echo '<input type="hidden" name="memberId" value=' . $memberId . '>';
        echo '<input type="hidden" name="amount" value=' . $new_total_amount1 . '>';
        echo '<input type="hidden" name="TMPL_AMOUNT" value=' . $new_total_amount1 . '>';

    if($validateCouponCode){
        if ($android_url != "") {
            $merchantRedirectUrl = "https://sharedigitalcard.com/user/success-page.php?coupon_code=" . $coupon_code . "&" . $android_url;
        } else {
            $merchantRedirectUrl = "https://sharedigitalcard.com/user/success-page.php?coupon_code=" . $coupon_code;
        }
    }else{
        if ($android_url != "") {
            $merchantRedirectUrl = "https://sharedigitalcard.com/user/success-page.php?" . $android_url;
        } else {
            $merchantRedirectUrl = "https://sharedigitalcard.com/user/success-page.php";
        }
    }
        $checksum = getchecksum($memberId, $totype, $new_total_amount1, $merchantTransactionId, $merchantRedirectUrl, $key);
        echo '<input type="hidden" name="merchantTransactionId" maxlength="100" value=' . $merchantTransactionId . '>';
        echo '<input type="hidden" name="checksum" value=' . $checksum . '>';
        echo '<input type="hidden" name="merchantRedirectUrl" value=' . $merchantRedirectUrl . '>';
        $_SESSION['new_year'] = $radio_value;
}

if (isset($_POST['check_coupon_code'])) {
    if (isset($_POST['android_user_id']) && $_POST['android_user_id'] != "" &&
        isset($_POST['type']) && $_POST['type'] != "") {
        $android_url = "android_user_id=" . $_POST['android_user_id'] . "&type=" . $_POST['type'];
    }
    $coupon_code = $_POST['check_coupon_code'];
    $validateCouponCode = $manage->validateDiscountCode($coupon_code);
    if ($validateCouponCode) {
        echo '<script>
$("#user_coupon_code").modal("hide");
</script>';
    } else {
        echo 'Invalid Coupon Code';
    }
}
if(isset($_POST['updatate_company']) && !empty($_POST['updatate_company'])){
    $company = $_POST['updatate_company'];
    $gst = $_POST['gst_no'];
    $update = $manage->updateCompany($company,$gst,$pan_no,$about_us,$our_mission,$company_profile);
    if($update){
        echo true;
    }else{
        echo false;
    }
}


?>