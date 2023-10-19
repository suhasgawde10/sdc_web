<?php
error_reporting(1);
ob_start();
include_once "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include "../controller/ManageUser.php";
$manage = new ManageUser();
include "../controller/validator.php";
$validate = new Validator();
include '../sendMail/sendMail.php';

if (!isset($_SESSION['email'])) {
    header('location:../login.php');
}
$date = date("Y-m-d");
$maxsize = 10485760;
$dealerError = false;
$admin_discount = false;
$error = false;
$errorMessage = "";

$id = 0;
include("session_includes.php");


$LastInvoiceNo = $manage->getLastInvoiceNumber("INR");
if ($LastInvoiceNo['invoice_no'] == null) {
    $invoice_number = 1001;
} else {
    $invoice_number = $LastInvoiceNo['invoice_no'] + 1;
}

if (isset($_GET['dealer_id'])) {
    $dealer_id = $security->decrypt($_GET['dealer_id']);
} else {
    $dealer_id = '';
}
$getDealerPercent = $manage->getDealerPercentDataByDealerId($dealer_id);
if ($getDealerPercent != "") {
    $percentage = $getDealerPercent['percentage'];
    $forbill = $getDealerPercent['name'];
    $foremail = $getDealerPercent['b_email_id'];
    $foraddr = $getDealerPercent['address'];
    $gstno = $getDealerPercent['gstin_no'];
    $refferalcode = $getDealerPercent['dealer_code'];
}

if (isset($_POST['upgrade_plan'])) {
    $get_user_data = $manage->displayAllUserByID($security->decrypt($_GET['user_id']));
    if ($get_user_data != null) {
        $user_expiry_date = $get_user_data['expiry_date'];
        $invoice_name = $get_user_data['company_name'];
        if ($invoice_name == '') {
            $invoice_name = $get_user_data['name'];
        }
        $email = $get_user_data['email'];
        $user_contact = $get_user_data['contact_no'];
        $user_gstno = $get_user_data['gst_no'];
        $user_pan_no = $get_user_data['pan_no'];
    }
    if (isset($_POST['taxable_amount'])) {
        $taxable_amount = $_POST['taxable_amount'];
    }
    if (isset($_POST['total_tax'])) {
        $total_tax = $_POST['total_tax'];
    }
    if (isset($_POST['referral_code']) && $_POST['referral_code'] != "") {
        $referral_code = $_POST['referral_code'];
    } else {
        $referral_code = "";
    }
    if (isset($_POST['dealer_code']) && $_POST['dealer_code'] != "") {
        $dealer_code = $_POST['dealer_code'];
    } else {
        $dealer_code = "";
    }
    if (isset($_POST['admin_discount']) && $_POST['admin_discount'] != "") {
        $admin_discount = $_POST['admin_discount'];
    } else {
        $admin_discount = "";
    }
    if (isset($_POST['total_amount'])) {
        $total_amount = $_POST['total_amount'];
    }
    $paymentMode = "Cash";
    $paymentBrand = "";
    $custBankId = "";
    $timestamp = date('Y-m-d H:i:s');

    if ($_SESSION['new_year'] == "1 year") {
        $month = 12;
    } else if ($_SESSION['new_year'] == "3 year") {
        $month = 36;
    } else if ($_SESSION['new_year'] == "5 year") {
        $month = 60;
    } else {
        $month = "";
    }
    $get_user_data = $manage->displayAllUserByID($security->decrypt($_GET['user_id']));
    if ($get_user_data != null) {
        $user_expiry_date = $get_user_data['expiry_date'];
    }
    if (($user_expiry_date != null OR $user_expiry_date != "0000-00-00") && $user_expiry_date >= $date && $month != "") {
        $expiry_date_in_time = strtotime("+" . $month . " months", strtotime($user_expiry_date));
        $expiry_date = date("Y-m-d", $expiry_date_in_time);
    } elseif (($user_expiry_date != null OR $user_expiry_date != "0000-00-00") && $user_expiry_date <= $date && $month != "") {
        $expiry_date_in_time = strtotime("+" . $month . " months", strtotime($date));
        $expiry_date = date("Y-m-d", $expiry_date_in_time);
    } elseif (($user_expiry_date == null OR $user_expiry_date == "0000-00-00") && $month != "") {
        $expiry_date_in_time = strtotime("+" . $month . " months", strtotime($date));
        $expiry_date = date("Y-m-d", $expiry_date_in_time);
    } else {
        $expiry_date = "";
    }
    $status_success = "success";
    $referal_by = "";
    $refrenced_by = "";
    $active_plan = 1;
    $discount_amount = 0;
//    exit;

    $update_user_plan = $manage->updateUserPlanStatusById($security->decrypt($_GET['user_id']));
    $insertUserSubscription = $manage->insertMannualSubscriptionData($security->decrypt($_GET['user_id']), $_SESSION['new_year'], $taxable_amount, $taxable_amount, $date, $expiry_date, $status_success, $refferalcode, $refrenced_by, $active_plan, $invoice_number, $discount_amount, $total_tax, $total_amount, $paymentBrand, $paymentMode, $custBankId, $timestamp, $payment_type, $forbill, $foremail, $gstno, $user_pan_no, FROM_BILL, FROM_GSTNO, FROM_PAN, SAC_CODE);
    $updateUserSubscription = $manage->updateUserExpiryDateById($expiry_date, $security->decrypt($_GET['user_id']));
    if ($updateUserSubscription) {
        $admin_discount = true;
    }


    $get_user_data = $manage->getUserInvoiceDataByInvoiceNumber($invoice_number);
    if ($get_user_data != null) {
        $user_expiry_date = $get_user_data['expiry_date'];
        $user_start_date = $get_user_data['start_date'];
        $name = $get_user_data['name'];
        $user_contact = $get_user_data['contact_no'];
        $email = $get_user_data['email'];
        $invoice_no = $get_user_data['invoice_no'];
        $year = $get_user_data['year'];
        $taxable_amount = $get_user_data['taxable_amount'];
        $discount = $get_user_data['discount'];
        $tax = $get_user_data['tax'];
        $half_tax = $tax / 2;
        $total_amount = $get_user_data['total_amount'];
        $invoice_gstn_no = $get_user_data['gstn_no'];
    }
    if ($invoice_gstn_no == '') {
        $invoice_gstn_no = "not applicable ";
    }
    $message = '
<table style="width: 100%;border-collapse: collapse;" cellpadding="10" border="1" cellspacing="10">
<tr>
<td colspan="5">
<img src="https://sharedigitalcard.com/assets/img/invoice/header%20(1).PNG" style="width:100%">
</td>
</tr>
<tr>
<td colspan="5" style="text-align: center"><h2><b style="font-weight: 500;">Tax Invoice</b></h2></td>
</tr>
<tr>
<td colspan="3"><strong>KUBIC TECHNOLOGY</strong></td>

<td colspan="2" style="text-align: right">
      <strong>Invoice Number : </strong>#INV' . $invoice_no . '<br>
     <strong>Invoice Date : </strong>' . $user_start_date . '</td>
</tr>


<tr>
<td colspan="3"><strong>Email : </strong>support@sharedigitalcard.com,<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;marketing@sharedigitalcard.com</td>

<td colspan="2" style="text-align: right">
          <strong> Bill for : </strong>' . $forbill . '<br><strong> Email : </strong>' . $foremail . '</td></td>
</tr>
<tr >
<td colspan="3"><strong>GSTN No : </strong> 27APNPC7063F1ZU<br><strong>PAN No : </strong> APNPC7063F</td>
<td colspan="2" style="text-align: right"><strong>GSTN No</strong> : ' . $gstno . '</td>
</tr>

<tr>
<td colspan="5"></td>
</tr>
<tr>
<th>Package</th>
<th>Start Date</th>
<th>Expiry Date</th>
<th>Sac code</th>
<th>Amount</th>
</tr>
<tr>
<td style="text-align: center">' . $year . '</td>
<td style="text-align: center">' . $user_start_date . '</td>
<td style="text-align: center">' . $user_expiry_date . '</td>
<td style="text-align: center">9983</td>
<td style="text-align: right"> ' . $taxable_amount . '</td>
</tr>';
    if ($get_user_data['referenced_by'] == "admin") {
        $message .= '
        <tr><td colspan="3"></td>
    <td>Discount : </td>
    <td style="text-align: right"><label style="background-color: #2b982b;">' . $discount . '</label></td>
</tr>
';
    } elseif ($get_user_data['referenced_by'] == "dealer") {
        $message .= '
<tr><td colspan="3"></td>
<td>Referenced by : </td>
<td style="text-align: right">' . $get_user_data['referral_code'] . '</td>
</tr>
   <tr><td colspan="3"></td>
    <td>+ 4 month</td>
    <td style="text-align: right"><label style="background-color: #2b982b;">FREE</label></td>
    </tr>
';
    } elseif ($get_user_data['referenced_by'] == "user") {
        $message .= '
<tr><td colspan="3"></td>
<td>Referenced by : </td>
<td style="text-align: right">' . $get_user_data['referral_code'] . '</td>
</tr>
    <tr><td colspan="3"></td>
    <td>+ 2 month</td>
    <td style="text-align: right"><label style="background-color: #2b982b;">FREE</label></td>
    </tr>
    ';
    }
    // end
    $message .= '
<tr>
<td colspan="3"></td>
<td> Taxable Amount : </td>
<td style="text-align: right">' . $taxable_amount . '</td>
</tr>';

    if ($get_user_data['gst_no'] != null && substr($get_user_data['gst_no'], 0, 2) != "27") {
        $message .= '
<tr>
<td colspan="3"></td>
<td>IGST (18%): </td>
<td style="text-align: right">' . $tax . '</td>
</tr>';
    } else {
        $message .= '
<tr>
<td colspan="3"></td>
<td>CGST (9%): </td>
<td style="text-align: right">' . $half_tax . '</td>
</tr>
<tr>
<td colspan="3"></td>
<td>SGST (9%): </td>
<td style="text-align: right">' . $half_tax . '</td>
</tr>';
    }
    $message .= '
<tr>
<td>Total Amount : </td>
<td style="text-align: right">
' . $total_amount . '
</td>
</tr>
<tr>
<td colspan="5">
            <strong> Important: </strong>
             <ol>
                  <li>This is an electronic generated invoice so</li>
                 <li>
                     Please read all terms and polices on <a href="https://sharedigitalcard.com/refund-and-return-policy.php" target="_blank">https://sharedigitalcard.com/refund-and-return-policy.php</a> for returns, replacement and other issues.
                 </li>
             </ol>
</td>
</tr>
<tr>
<td colspan="5">
<img src="https://sharedigitalcard.com/assets/img/invoice/footer%20(1).PNG" style="width:100%">
</td>
</tr>
</table><br><br>';
    if ($admin_discount) {
        $sms_message = "Purchased sharedigitalcard of " . $_SESSION['new_year'] . " plan @" . $total_amount . " and Invoice No: #INV" . $invoice_number;
        $sendEmail = $manage->sendMail($name, $email, $sms_message, $message);
        if ($sendEmail) {
            $sendSms = $manage->sendSMS($user_contact, $sms_message);
            if (!$sendSms) {
                $errorMessage .= "Issue while sending sms to dealer but plan has been upgraded";
            } else {
                $errorMessage .= "User Plan Upgraded Successfully";
                $url = 'user-management.php';
                header("Refresh:2; url=" . $url);
            }
        } else {
            $errorMessage .= "Issue while sending email to dealer but plan has been upgraded";
        }
    } elseif ($dealerError) {
        $dealer_subject = $user_name . " has Purchased sharedigitalcard of " . $_SESSION['new_year'] . " plan @" . $total_amount;
        $sendDealerEmail = $manage->sendMail($dealer_name, $dealer_email, $dealer_subject, $message);
        if ($sendDealerEmail) {
            $sendDealerSms = $manage->sendSMS($dealer_contact_no, $dealer_subject);
            if (!$sendDealerSms) {
                $errorMessage .= "Issue while sending sms to dealer but plan has been upgraded";
            } else {
                $errorMessage .= "User Plan Upgraded Successfully";
                $url = 'user-management.php';
                header("Refresh:2; url=" . $url);
            }
        } else {
            $errorMessage .= "Issue while sending email to dealer but plan has been upgraded";
        }
    }
}


$sub_plan = $manage->subscriptionPlan();


?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>plan selection</title>
    <?php include "assets/common-includes/header_includes.php" ?>
    <style>
        #get_amount1 table {
            display: none;
        }

        .upi-btn {
            width: 19%;
            padding: 10px 0;
            top: 40px;
        }

        @media (max-width: 991px) {
            .upi-btn {
                top: unset;
                width: 94%;
                padding: 6px 0;
            }
        }

        @media (max-width: 480px) {
            .upi-btn {
                top: unset;
                width: 94%;
                padding: 6px 0;
            }

            .upi-head h2 {
                font-size: 20px;
            }
        }

        @media (max-width: 360px) {
            .upi-btn {
                top: unset;
                width: 94%;
                padding: 6px 0;
            }

            .upi-head h2 {
                font-size: 20px;
            }
        }

        @media (max-width: 320px) {
            .upi-btn {
                top: unset;
                width: 94%;
                padding: 6px 0;
            }

            .upi-head h2 {
                font-size: 20px;
            }
        }


    </style>
</head>
<body onload="get_value()">
<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>
<section class="content">
    <!-- <div class="up-nav visible-lg visible-md visible-sm hidden-xs">
         <main>
             <div class="page-content" id="applyPage">
                 <ul class="breadcrumbs">
                     <li class="tab-link breadcrumb-item">
                         <a href="create_digital_card.php">
                             <span class="number"><i class="fas fa-user"></i></span>
                             <span class="label">Create Digital Card</span>
                         </a>
                     </li>
                     <li class="tab-link breadcrumb-item active visited" id="crumb5">
                         <a href="payment.php">
                             <span class="number"><i class="fas fa-money-bill-alt"></i></span>
                             <span class="label">Payment</span>
                         </a>
                     </li>
                 </ul>
             </div>
         </main>
     </div>-->
    <div class="clearfix">


        <div class="col-lg-8 col-md-6 col-sm-6 col-xs-12 padding_zero padding_zero_both">
            <?php
            if ($sub_plan != null) {
                ?>
                <ul class="ul_subcription_list">
                    <?php
                    while ($row_data = mysqli_fetch_array($sub_plan)) {
                        ?>
                        <li>
                            <div class="container_k">
                                <div class="content_k">
                                    <div class="row">
                                        <div class="col-md-8 col-xs-7 text-left">
                                            <div class="row">
                                                <label class="radio_plan"><?php echo $row_data['year']; ?>
                                                    <input onclick="get_value()" type="radio" name="rd_sub_plan"
                                                           value="<?php echo $row_data['year']; ?>" <?php if ($row_data['year'] == '1 year') echo "checked" ?>>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-xs-5 text-left">
                                            <input type="hidden" value="<?php echo $row_data['year']; ?>">
                                            <input type="hidden" value="<?php
                                            if ($row_data['amt'] != null) {
                                                $value = round($row_data['amt'] / 100 * $percentage);
                                                $amt = $row_data['amt'] - $value;
                                                echo "Rs: " . $amt;
                                            } ?>">

                                            <h4 class="text-right"><b><?php
                                                    if ($row_data['amt'] != null) {
                                                        $value = round($row_data['amt'] / 100 * $percentage);
                                                        $amt = $row_data['amt'] - $value;
                                                        echo "Rs: " . $amt;
                                                    }
                                                    ?></b>
                                            </h4>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
            <?php
            }
            ?>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
            <div class="row">
                <div class="card">
                    <div class="body">
                        <form name="frm1" method="post" action="">
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
                            <table class="table table-borderless get_amount">
                                <tbody></tbody>
                            </table>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>


<div class="modal fade" id="user_referral_code" role="dialog">
    <div class="modal-dialog cust-model-width">
        <div class="modal-content">
            <div class="modal-header cust-upi-madal">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Have a referral code?</h4>
            </div>
            <div class="modal-body">
                <div class="body">
                    <form method="POST" action="">
                        <input name="referal_code" placeholder="Enter referral code" class="form-control referral_code">&nbsp;&nbsp;&nbsp;
                        <p class="code_msg2"></p>

                        <div class="form-group">
                            <button class="btn btn-primary" type="button" onclick="user_referral_code()">Apply Now
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="user_dealer_code" role="dialog">
    <div class="modal-dialog cust-model-width">
        <div class="modal-content">
            <div class="modal-header cust-upi-madal">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Have a dealer code?</h4>
            </div>
            <div class="modal-body">
                <div class="body">
                    <input name="referal_code" placeholder="Enter dealer code" class="form-control dealer_code">&nbsp;&nbsp;&nbsp;
                    <p class="code_msg1"></p>

                    <div class="form-group">
                        <button class="btn btn-primary" type="button" onclick="user_dealer_code()">Apply Now</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="admin_discount" role="dialog">
    <div class="modal-dialog cust-model-width">
        <div class="modal-content">
            <div class="modal-header cust-upi-madal">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Discount</h4>
            </div>
            <div class="modal-body">
                <div class="body">
                    <input name="txt_discount" placeholder="Enter discount" class="form-control discount_code">&nbsp;&nbsp;&nbsp;
                    <p class="discount_ms"></p>

                    <div class="form-group">
                        <button class="btn btn-primary" type="button" onclick="admin_discount()">Apply Now</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function sendNotification() {
        $('.submit_btn').attr('type', 'submit');
        $('.submit_btn')[0].click();
    }
</script>
<script>
    function get_value(val) {
        var get_radio_value = $('input[name=rd_sub_plan]:checked').val();
        $('input:not(:checked)').parent().removeClass("active");
        $('input:checked').parent().addClass("active");
        var dataString = "radio_value=" + get_radio_value + "&dealer_id=" +<?php echo $dealer_id ?>;
        console.log(dataString);
        $.ajax({
            type: "POST",
            url: "dealer_plan_selection_ajax.php", // Name of the php files
            data: dataString,
            dataType: "json",
            success: function (result) {
                console.log(result);
                $(".get_amount tbody").html(result.data);
            }
        });
    }

</script>
<script>
    function user_referral_code() {
        var refereal_code = $('.referral_code').val();
        var dataString = "refereal_code=" + refereal_code;
        var dataString1 = "check_code=" + refereal_code;
        var dataString2 = "check_refereal_code=" + refereal_code;
        $.ajax({
            type: "POST",
            url: "admin_plan_selection_ajax.php", // Name of the php files
            data: dataString,

            success: function (html) {
                $(".code_msg").html(html);
                /*return false*/
                $(".hide_default").css("display", "none");

            }
        });
        $.ajax({
            type: "POST",
            url: "admin_plan_selection_ajax.php", // Name of the php files
            data: dataString1,
            success: function (html) {
                $(".extra_month").html(html);
                /*return false*/
            }
        });
        $.ajax({
            type: "POST",
            url: "admin_plan_selection_ajax.php", // Name of the php files
            data: dataString2,
            success: function (html) {
                $(".code_msg2").html(html);
                /*return false*/
            }
        });
    }
    function InvalidReferralCode() {
        var refereal_code = 'referetfr';
        var dataString = "refereal_code=" + refereal_code;
        var dataString1 = "check_code=" + refereal_code;
        $.ajax({
            type: "POST",
            url: "admin_plan_selection_ajax.php", // Name of the php files
            data: dataString,
            success: function (html) {
                $(".code_msg").html(html);
                return false
            }
        });
        $.ajax({
            type: "POST",
            url: "admin_plan_selection_ajax.php", // Name of the php files
            data: dataString1,
            success: function (html) {
                $(".extra_month").html(html);
                return false
            }
        });
    }

</script>
<script>

    function user_dealer_code() {
        var get_radio_value = $('input[name=rd_sub_plan]:checked').val();
        var dealer_code = $('.dealer_code').val();
        var dataString = "dealer_code=" + dealer_code + "&year=" + get_radio_value;
        var dataString1 = "check_dealer_code=" + dealer_code;
        $.ajax({
            type: "POST",
            url: "admin_plan_selection_ajax.php", // Name of the php files
            data: dataString,
            success: function (html) {
                $(".get_amount").html(html);
                /*return false*/
            }
        });
        $.ajax({
            type: "POST",
            url: "admin_plan_selection_ajax.php", // Name of the php files
            data: dataString1,
            success: function (html) {
                $(".code_msg1").html(html);
                /*return false*/
            }
        });
    }
    function InvalidDealerCode() {
        var get_radio_value = $('input[name=rd_sub_plan]:checked').val();
        var dealer_code = 'jdhfkjghdskfhjg';
        var dataString = "dealer_code=" + dealer_code + "&year=" + get_radio_value;
        var dataString1 = "check_dealer_code=" + dealer_code;
        $.ajax({
            type: "POST",
            url: "admin_plan_selection_ajax.php", // Name of the php files
            data: dataString,
            success: function (html) {
                $(".get_amount").html(html);
                /*return false*/
            }
        });
    }

</script>

<script>

    function admin_discount() {
        var get_radio_value = $('input[name=rd_sub_plan]:checked').val();
        var discount_code = $('.discount_code').val();
        var dataString = "discount_code=" + discount_code + "&year=" + get_radio_value;
        $.ajax({
            type: "POST",
            url: "admin_plan_selection_ajax.php", // Name of the php files
            data: dataString,
            success: function (html) {
                $(".get_amount").html(html);
                $("#admin_discount").modal("hide");
            }
        });
    }
</script>

<script>

    function addTemporaryValue() {
        var reference_code = <?php if (isset($_POST['grand_amount'])){ echo $_POST['grand_amount']; }elseif(isset($_POST['dealer_code'])){ echo $_POST['dealer_code']; }else{ echo ""; } ?>;
        var year = <?php if(isset($_POST['new_year'])) echo $_POST['new_year']; ?>;
        var dataString = "reference_code=" + reference_code + "&year=" + year;
        $.ajax({
            type: "POST",
            url: "admin_plan_selection_ajax.php", // Name of the php files
            data: dataString,
            success: function (html) {

            }
        });
    }
</script>

<script type="text/javascript">
    function valueChanged() {
        if ($('.coupon_question').is(":checked"))
            $(".answer").show();
        else
            $(".answer").hide();
    }
</script>

<?php include "assets/common-includes/footer_includes.php" ?>

</body>
</html>