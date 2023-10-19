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

}

if(isset($_POST['dealer_code'])){
    $dealer_code = $_POST['dealer_code'];
    $quantity = $_POST['quantity'];
    $_SESSION['quantity'] = $quantity;
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
    $total_without_tax = $new_amt * $quantity;
    $taxable_amount = $total_without_tax * 18/100;
    /*echo "Tax (18%) : ".$taxable_amount . "<br><br>";*/
    $new_total_amount = number_format((float)$taxable_amount + $total_without_tax, 2, '.', '');
    /*echo "Total amount : ". $newamount;*/
    $plan_year = "'".$radio_value."'";
   /* $new_grand_amount = $new_amt *10/100;
    $new_total_grand = $new_amt-$new_grand_amount;
    $new_tax = $new_total_grand*18/100;*/
   /* $new_total_amount = $newamount + $new_total_grand;*/
        echo '
        <tr>
<td>
Per Card Cost : 
</td>
<td class="text-right">
' . round($new_amt) . '
</td>
</tr>
<tr>
<td>
Quantity : 
</td>
<td class="text-right">
<input class="form-control quantity-cust" value="'.$quantity.'" type="number" placeholder="Enter Quantity" onchange="upgradeCreditByQuantity(this.value)">
</td>
</tr>
<tr>
     
     
                                        <tr>
                                            <td>Total '.$quantity.' Card : </td>
                                            <td class="text-right">' . round($total_without_tax) . '</td>
                                        </tr>
                                        <tr>
                                            <td>Tax (18%) : </td>
                                            <td class="text-right">' . round($taxable_amount). '</td>
                                        </tr>
                                        <tr class="border-total-amount total_amount">
                                            <td><h5><b>Total amount :  </b></h5></td>
                                            <td class="text-right"><h5><b>' . round($new_total_amount). '</b></h5></td>
                                        </tr>
                                        <td colspan="2">
                                         <button class="btn btn-primary form-control" name="pay_now" type="submit">Pay now ' . round($new_total_amount). '
                                            </button></td></tr>';

        echo '<input type="hidden" name="new_total_amount" value=' . $new_total_amount .'>';
        echo '<input type="hidden" name="dealer_code" value=' . $dealer_code .'>';
    $_SESSION['new_year'] = $radio_value;

        echo '<input type="hidden" name="new_year" value=' . $radio_value . '>';

    echo '<input type="hidden" name="totype" value=' . $totype . '>';
    echo '<input type="hidden" name="memberId" value=' . $memberId . '>';
    echo '<input type="hidden" name="amount" value=' . $new_total_amount . '>';
    echo '<input type="hidden" name="TMPL_AMOUNT" value=' . $new_total_amount . '>';
    $merchantRedirectUrl = "https://sharedigitalcard.com/dealer/success-page.php?user_id=".$user_id;
    $checksum = getchecksum($memberId, $totype, $new_total_amount, $merchantTransactionId, $merchantRedirectUrl, $key);
    echo '<input type="hidden" name="merchantTransactionId" maxlength="100" value=' . $merchantTransactionId . '>';
    echo '<input type="hidden" name="checksum" value=' . $checksum . '>';
    echo '<input type="hidden" name="merchantRedirectUrl" value=' . $merchantRedirectUrl . '>';

}


?>