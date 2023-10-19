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


if (isset($_POST['send_notification'])) {
    $message = "User " . $session_name . " want to purchase " . $_SESSION['new_year'] . " plan of share digital card.\nContact number : " . $session_contact_no;
    $send = $manage->sendSMS($global_contact, $message);
    $send1 = $manage->sendSMS("9773884631", $message);
}
if (isset($_POST['radio_value'])) {
    $radio_value = $_POST['radio_value'];
    $dealer_id = $_POST['dealer_id'];

    if (isset($_POST['android_user_id']) && $_POST['android_user_id'] != "" && $_POST['type'] != "") {
        $android_url = "android_user_id=" . $_POST['android_user_id'] . "&type=" . $_POST['type'];
    }
    if ($radio_value != "Free Trail (5 days)") {
        $getDealerData = $manage->getDealerPercentDataByDealerId($dealer_id);
        if ($getDealerData != "") {
            $percentage = $getDealerData['percentage'];
        }
        $get_select_value = $manage->get_selected_value($radio_value);

        if ($get_select_value['amt'] != null) {
            $reduce_val = $get_select_value['amt'] / 100 * $percentage;
            $amount = $get_select_value['amt'] - $reduce_val;
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
                                            <td class="text-right">' . floor($taxable_amount) . '</td>
                                        </tr>
                                        <tr class="border-total-amount total_amount">
                                            <td><h5><b>Total amount :  </b></h5></td>
                                            <td class="text-right"><h5><b>' . round($newamount1) . '</b></h5></td>
                                        </tr>

                                         <tr class="pamentz_row">
                                         <td colspan="2">

                                         <button class="btn btn-primary form-control submit_btn" name="upgrade_plan" type="button" onclick="sendNotification()">Subscribe now
                                            </button></td>

                                            </tr>';
        $_SESSION['new_year'] = $radio_value;
        $payment_data .= '
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
        $payment_data .= '
        <input type="hidden" name="merchantTransactionId" maxlength="100" value=' . $merchantTransactionId . '>
        <input type="hidden" name="checksum" value=' . $checksum . '>
        <input type="hidden" name="merchantRedirectUrl" value=' . $merchantRedirectUrl . '>';

        $pay_amount = round($newamount1) * 100;
        $returnData = array(
            'status' => 'ok',
            'msg' => 'OTP has been sent to your email id',
            'pay_amount' => $pay_amount,
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
                                            <td class="text-right">' . floor($amount) . '</td>
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



?>