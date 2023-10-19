<?php
include '../whitelist.php';
ob_start();
include "../controller/ManageDealer.php";
$manage = new ManageDealer();
include "../controller/validator.php";
$validate = new Validator();
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include '../sendMail/sendMail.php';

/*if (!isset($_SESSION['dealer_email'])) {
    header('location:../dealer-register.php');
}*/


require_once("functions.php");
$maxsize = 10485760;

$error = false;
$errorMessage = "";

$id = 0;

include("session_includes.php");
require_once("functions.php");
$form_data = $manage->getDealerProfile($_SESSION['dealer_id']);
if ($form_data != null) {
    $message_status = $form_data['message_status'];
      $dealer_status = $form_data['status'];
    $pay_status = $form_data['pay_status'];
    $deal_code = $form_data['dealer_code'];
    $dealer_gstn_no = $form_data['gstin_no'];
    $street = $form_data['address'];
    $city = $form_data['city'];//your script should substitute the customer's city
    $state = $form_data['state'];//your script should substitute the customer's state
}
?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Payment Deposit</title>
    <?php include "assets/common-includes/header_includes.php" ?>
    <style>
        h4{
            margin-top: 15px;
            color: #2793e6;
        }
        .table tbody tr td, .table tbody tr th{
            font-size: 13px;
        }
        [type="radio"]:not(:checked), [type="radio"]:checked {
            position: unset;
            opacity: 1;
        }
        .row_amount{
            display: none;
        }
    </style>
</head>
<body onload="getRadioValue()">
<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>
<section class="content">

        <div class="clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 order-sm-1">
                <div class="row">
                    <div class="card">
                        <div class="body" style="overflow: auto">
                            <form method="post" name='razorpayform' action="payment-success.php" id="form">
                                <?php if ($error) {
                                    ?>
                                    <div class="alert alert-danger">
                                        <a href="#" class="close" data-dismiss="alert"
                                           aria-label="close">&times;</a>
                                        <?php if (isset($errorMessage)) echo $errorMessage; ?>
                                    </div>
                                <?php
                                } else if (!$error && $errorMessage != "") {
                                    ?>
                                    <div class="alert alert-success">
                                        <a href="#" class="close" data-dismiss="alert"
                                           aria-label="close">&times;</a>
                                        <?php if (isset($errorMessage)) echo $errorMessage; ?>
                                    </div>
                                <?php
                                }
                                ?>
<!--                               --><?php
//                               echo '<table class="table table-borderless"><tr>
//                                            <td>Deposit amount&nbsp;</td>
//                                            <td class="text-right">' . round(DEPOSIT_AMT).'</td>
//                                        </tr>
//                                        <tr class="border-total-amount total_amount">
//                                            <td><h5><b>Total amount :  </b></h5></td>
//                                            <td class="text-right"><h5><b>' . round(DEPOSIT_AMT). '</b></h5></td>
//                                        </tr>';
//                               echo '<tr><td colspan="2">
//                                         <input type="checkbox" required> <a data-toggle="modal" data-target="#myModal">I agree terms & conditions.</a>
//                                         </td></tr>';
//                               echo '<tr><td colspan="2">
//                                         <button class="btn btn-primary form-control" name="pay_now" type="submit">Pay now 2000/-
//                                            </button></td></tr>';
//                               echo '</table>';
//                               ?>
                                <table class="table table-bordered table-striped">
                                    <?php
                                    $get_price = $manage->getDealerPriceDetails();
                                        ?>
                                    <tr>
                                        <th colspan="7" class="text-center">
                                            <h4>Card Pricing</h4>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th>Enrollment Fees (5 Year Validity)</th>
                                        <th>Percentage Benefit</th>
                                        <th>1 year</th>
                                        <th>3 year</th>
                                        <th>5 year</th>
                                        <th>lifetime</th>
                                    </tr>
                                    <?php
                                    if($get_price !=null) {
                                        $i = 1;
                                        while ($row = mysqli_fetch_array($get_price)) {
                                            ?>
                                            <tr>
                                                <td><input type="radio" name="dealer_percent" onclick="getRadioValue()" value="<?php echo $row['id']; ?>" <?php if($i == 1) echo "checked"; ?>></td>
                                                <td class="row_amount"><?php echo $row['pricing']; ?></td>
                                                <td><?php echo $row['pricing']; ?> + 18% GST</td>
                                                <td><?php echo $percentage = $row['percentage']; ?></td>
                                                <?php
                                                $get_user_plan = $manage->getUserSubscriptionPlan();
                                                while ($row_data = mysqli_fetch_array($get_user_plan)) {
                                                    $total_percent = $percentage*$row_data['amt']/100;
                                                    ?>
                                                    <td ><?php echo round($row_data['amt']-$total_percent); ?></td>
                                                    <?php
                                                }
                                                ?>
                                            </tr>
                                            <?php
                                        $i++;
                                        }
                                    }else {
                                        ?>
                                        <tr>
                                            <td colspan="7">
                                                No Data Found.
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </table><br>
                                <!-- <div style="float: left">
                                    <input type="checkbox" id="term_cond" name="term_cond" required> <a href="../dealership-program.php" target="_blank">I agree terms & conditions.</a>
                                    <br><span class="chk_error"></span>
                                </div> -->
                                <span class="show_area"></span>
                                <div style="float: right">

                                    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                                    <input type="hidden" name="razorpay_signature" id="razorpay_signature">
                                    <button class="btn btn-primary form-control submit_btn amount" id="rzp-button1" style="display: none"
                                            name="upgrade_plan" type="button">Pay now
                                    </button><!--
                                    <button class="btn btn-primary form-control amount" name="pay_now" type="submit" style="display: none"></button>-->
                                    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
                                </div>
                                <input type="hidden" name="orderDescription" value="<?php echo $orderDescription; ?>">
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
                                <input type="hidden" name="phone" value="<?php echo $_SESSION['dealer_contact']; ?>">
                                <input type="hidden" name="telnocc" value="<?php echo $telnocc; ?>">
                                <input type="hidden" name="email" value="<?php echo $_SESSION['dealer_email']; ?>">
                                <input type="hidden" name="terminalid" value="<?php echo $terminalid; ?>">
                                <input type="hidden" name="paymentMode" value="<?php echo $paymentMode; ?>">
                                <input type="hidden" name="paymentBrand" value="<?php echo $paymentBrand; ?>">
                                <input type="hidden" name="customerId" value="<?php echo $_SESSION['dealer_id']; ?>">

                               <!-- --><?php
/*                                echo '<input type="hidden" name="new_total_amount" value=' . DEPOSIT_AMT .'>';
                                echo '<input type="hidden" name="totype" value=' . $totype . '>';
                                echo '<input type="hidden" name="memberId" value=' . $memberId . '>';
                                echo '<input type="hidden" name="amount" value=' . DEPOSIT_AMT . '>';
                                echo '<input type="hidden" name="TMPL_AMOUNT" value=' . DEPOSIT_AMT . '>';
                                $merchantRedirectUrl = "https://sharedigitalcard.com/dealer/payment-success.php";
                                $checksum = getchecksum($memberId, $totype, DEPOSIT_AMT, $merchantTransactionId, $merchantRedirectUrl, $key);
                                echo '<input type="hidden" name="merchantTransactionId" maxlength="100" value=' . $merchantTransactionId . '>';
                                echo '<input type="hidden" name="checksum" value=' . $checksum . '>';
                                echo '<input type="hidden" name="merchantRedirectUrl" value=' . $merchantRedirectUrl . '>';

                                */?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header cust-upi-madal">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Terms & Condition</h4>
            </div>
            <div class="modal-body">
                <div class="body">
                    <div class="about-content terms_and_condition_content_point">

                     <!--   <div class="col-md-12 text-center">
                            <h3 style="margin: 0;color: #db5ea5;">Terms & Condition</h3>
                        </div>-->
                        <h4><b>Welcome to Share Digital Card</b></h4>
                        <p>As you are joining as dealer it is important to give your 100% in the form of dealership by selling maximum card possible, as we are not taking any Investment to join with us and there is no such monthly target the dealers have, it is important to keep Some Terms and conditions to make the Dealership account available for only Genuinely Working and Interested People.</p>

                        <p>We are happy to keep you on board as a dealer in our company</p>

                        <p>Following mentioned points you have to keep in your mind while working as dealer</p>

                        <p>1. To filter Out Genuinely Working and Intrested Dealer we are taking some amount as security deposit which will be refundable.</p>

                        <p>2. Once Dealership account has been Approved, You will need to pay Security Deposit of 2,000 (Two Thousand) Rupees.</p>

                        <p>3. As Mentioned this security deposit is refundable and We will return this amount once you complete your 5 Customer Card Sells in first 6 Months of your dealership.</p>

                        <p>4. Once You process the Security Deposit it will get added to your Virtual Wallet.</p>

                        <p>5. If you unable to sell 5 cards in first 6 Months of dealership, your security deposit is not refundable and also your dealership account will get cancelled from our end , without affecting your previous customer card which you sold.</p>

                        <p>6. It is important to Keep your selling price below the mentioned pricing as per the company rules and regulation.</p>
                        <table class="table" style="margin-bottom: 15px; !important;">
                            <tr>
                                <th>Plan</th>
                                <th>Max Sell Price</th>
                            </tr>
                            <tr>
                                <td>1 Year Plan</td>
                                <td>1,500</td>
                            </tr>
                            <tr>
                                <td>3 Year Plan</td>
                                <td>2,500</td>
                            </tr>
                            <tr>
                                <td>5 Year Plan</td>
                                <td>3,500</td>
                            </tr>
                            <tr>
                                <td>Lifetime Plan</td>
                                <td>5,500</td>
                            </tr>
                        </table>
                        7. If we found or if any customer reported that the dealer is selling the Digital card more than Maximum Price allowed or not following any terms and condition mentioned as above then that dealer's account will be cancelled.

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php include "assets/common-includes/footer_includes.php" ?>

<script>
    function getRadioValue() {
        var pricing_year = $('input[name=dealer_percent]:checked').val();
        var currentRow=$(this).closest("td").find("td:eq(1)").text();
        var amount = 0;
        $('input[type="radio"]:checked').each(function () {
            var $row = $(this).closest("tr");    // Find the row
            amount = $row.find(".row_amount").text(); // Find the text
        });
        var dataString = "dealer_pricing_year="+pricing_year+"&amount="+amount;
        $.ajax({
            type: "POST",
            url: "change_amount.php", // Name of the php files
            data: dataString,
            dataType:"json",
            success: function (result) {
                $('.amount').text('Pay Now '+result.total_amount+'/-').show();
                $('.show_area').html(result.data);
                /*return false*/
            }
        });
    }
</script>

</body>
</html>