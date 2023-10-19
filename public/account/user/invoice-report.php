<?php
ob_start();
include_once "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include "../controller/ManageUser.php";
$manage = new ManageUser();
include '../sendMail/sendMail.php';

if (!isset($_SESSION['email'])) {
    header('location:../login.php');
} elseif (isset($_SESSION['email']) && $_SESSION['email'] != "admin@sharedigitalcard.com" && (isset($_SESSION['type']) && $_SESSION['type'] != 'Admin')) {
    header('location:../login.php');
}


$error = false;
$errorMessage = "";


$displaySubscription = $manage->displayInvoiceDetails();
if ($displaySubscription != null) {
    $countDescription = mysqli_num_rows($displaySubscription);
} else {
    $countDescription = 0;
}


if (isset($_POST['search'])) {
//    exit;
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    $status = $_POST['drp_status'];

    $displaySubscription = $manage->displayInvoiceDetailsByDate($from_date, $to_date, $status);
    if ($displaySubscription != null) {
        $countDescription = mysqli_num_rows($displaySubscription);
    } else {
        $countDescription = 0;
    }


}

if (isset($_POST['send_email'])) {
    $checkbox = $_POST['check'];
    $txt_email = $_POST['txt_email'];
    $name = "";
    $message = "";
    /*$keyword_data = explode(',',$checkbox);
    $keyword_data;
    die();*/
    foreach ($checkbox as $key) {
        /* $check_user_pay = $manage->checkUserPayStatus($key);
        if($check_user_pay['dealer_by_pay'] == 1){
            $get_user_data = $manage->getDealerInvoiceData($check_user_pay['referral_code'],$check_user_pay['invoice_no']);
         * */

        $get_user_data = $manage->getUserInvoiceData($key);
        if ($get_user_data != null) {
            $currency_type = $get_user_data['currency_type'];
            $from_name = $get_user_data['from_bill'];
            $user_expiry_date = $get_user_data['end_date'];
            $user_start_date = $get_user_data['start_date'];
            $invoice_name = $get_user_data['for_bill'];
            $for_email = $get_user_data['for_email'];
            $for_email = $get_user_data['for_email'];
            $invoice_no = $get_user_data['invoice_no'];
            $year = $get_user_data['year'];
            $plan_amount = $get_user_data['plan_amount'];
            $taxable_amount = $get_user_data['taxable_amount'];
            $discount = $get_user_data['discount'];
            $tax = $get_user_data['tax'];
            $half_tax = $tax / 2;
            $total_amount = $get_user_data['total_amount'];
            $sac_code = $get_user_data['sac_code'];
            $from_gstno = $get_user_data['from_gstno'];
            $from_pan = $get_user_data['from_pan'];
            $invoice_gstn_no = $get_user_data['for_gstno'];
            $invoice_pan_no = $get_user_data['for_pan'];
            $invoice_gstn_no_for_tax = $get_user_data['for_gstno'];
            $for_address = $get_user_data['user_address'];
            $invoice_type = $get_user_data['type'];
        }
        $credit_qty = $get_user_data['credit_qty'];
        if ($get_user_data['referenced_by'] == 'credit') {
            $total_plan_amount = $plan_amount * $credit_qty;
        }
        if ($invoice_pan_no != '' && $invoice_gstn_no != '') {
            $invoice_pan_no = "<br><strong>PAN No : </strong> " . $invoice_pan_no;
        } else {
            $invoice_pan_no = "";
        }
        if ($invoice_gstn_no == '') {
            $invoice_gstn_no = "not applicable";
        }
        if (trim($year) == "Life Time") {
            $user_expiry_date = 'Life Time';
        }
        if ($for_address != '') {
            $for_address = "<br><strong> Address : </strong>" . wordwrap($for_address, 40, "<br>");
        } else {
            $for_address = '';
        }
        if ($currency_type == 'USD') {
            $currency_symbol = "$";
            $invoice_tax_type = "Invoice";
        } else {
            $invoice_tax_type = "Tax Invoice";
            $currency_symbol = "&#8377;";
        }
        $message .= '
<table style="width: 100%;border-collapse: collapse;" cellpadding="10" border="1" cellspacing="10">
<tr>
<td colspan="5">
<img src="https://sharedigitalcard.com/assets/img/invoice/header%20(1).PNG" style="width:100%">
</td>
</tr>
<tr>
<td colspan="5" style="text-align: center"><h2><b style="font-weight: 500;">' . $invoice_tax_type . '</b></h2></td>
</tr>
<tr>
<td colspan="3"><strong>' . $from_name . '</strong></td>

<td colspan="2" style="text-align: right">
      <strong>Invoice Number : </strong>#INV' . $invoice_no . '<br>
     <strong>Invoice Date : </strong>' . $user_start_date . '</td>
</tr>


<tr>
<td colspan="3"><strong>Email : </strong>support@sharedigitalcard.com,<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;marketing@sharedigitalcard.com</td>

<td colspan="2" style="text-align: right">
          <strong> Bill for : </strong>' . $invoice_name . '<br><strong> Email : </strong>' . $for_email . $for_address . '</td></td>
</tr>';
        if ($currency_type != 'USD') {
            $message .= '<tr>
<td colspan="3"><strong>GSTN No : </strong> ' . $from_gstno . '<br><strong>PAN No : </strong> ' . $from_pan . '</td>
<td colspan="2" style="text-align: right"><strong>GSTN No</strong> : ' . $invoice_gstn_no . '</td>
</tr>';
        }
        if ($currency_type == 'USD') {

            $message .= '
<tr>
<th colspan="2">Package</th>
<th>Start Date</th>
<th>Expiry Date</th>
<th>Amount</th>
</tr>
<tr>
<td colspan="2" style="text-align: center">' . $year . ' plan</td>
<td style="text-align: center">' . $user_start_date . '</td>
<td style="text-align: center">' . $user_expiry_date . '</td>
<td style="text-align: right"> ' . $currency_symbol . $plan_amount . '</td>
</tr>
';
        } elseif ($get_user_data['referenced_by'] == 'credit') {

            $message .= '
<tr>
<th>Plan Name</th>
<th>Unit Price</th>
<th>QTY</th>
<th>Sac code</th>
<th>Amount</th>
</tr>
<tr>
<td style="text-align: center">' . $year . ' plan</td>
<td style="text-align: center">' . $plan_amount . ' </td>
<td style="text-align: center">' . $credit_qty . '</td>
<td style="text-align: center">' . $sac_code . '</td>
<td style="text-align: right"> ' . $total_plan_amount . '</td>
</tr>
';
        } else {
            $message .= '
<tr>
<th>Package</th>
<th>Start Date</th>
<th>Expiry Date</th>
<th>Sac code</th>
<th>Amount</th>
</tr>
<tr>';
            if ($invoice_type == 'dealership') {
                $message .= '<td style="text-align: center">' . $year . ' dealership plan<br>(with 5 year validity)</td>';
            } else {
                $message .= '<td style="text-align: center">' . $year . '</td>';
            }
            $message .= '<td style="text-align: center">' . $user_start_date . '</td>
<td style="text-align: center">' . $user_expiry_date . '</td>
<td style="text-align: center">' . $sac_code . '</td>
<td style="text-align: right"> ' . $plan_amount . '</td>
</tr>
';
        }
        if ($currency_type != 'USD') {
            // start
            if ($get_user_data['referenced_by'] == "coupon" && $discount != '') {
                $message .= '

<tr>
<td colspan="3"></td>
<td>Coupon Code : </td>
<td style="text-align: right">' . $get_user_data['referral_code'] . '</td>
</tr>
        <tr><td colspan="3"></td>
    <td>Discount : </td>
    <td style="text-align: right"><label>' . $discount . '</label></td>
</tr>
';
            } elseif ($get_user_data['referenced_by'] == "admin" && $discount != '') {
                $message .= '
        <tr><td colspan="3"></td>
    <td>Discount : </td>
    <td style="text-align: right"><label>' . $discount . '</label></td>
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
    <td style="text-align: right"><label>FREE</label></td>
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
    <td style="text-align: right"><label>FREE</label></td>
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
            if ($invoice_gstn_no_for_tax != null && substr($invoice_gstn_no_for_tax, 0, 2) != "27") {
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
        }
        $message .= '
<tr>
<td colspan="3"></td>
<td>Total Amount : </td>
<td style="text-align: right">
' . $currency_symbol . $total_amount . '
</td>
</tr>
<tr>
<td colspan="5">
            <strong> Important: </strong>
             <ol>
                  <li>This is an electronic generated invoice.</li>
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
</table>
<br><br><footer></footer>';

    }

    $subject = "All Invoices";
    if ($txt_email != "") {
        if ($message != "") {
            $sendEmail = $manage->sendMail($name, $txt_email, $subject, $message);
            if ($sendEmail) {
                $error = false;
                $errorMessage = 'Invoice has been sent to your ' . $txt_email . ' id.';
            } else {
                $error = true;
                $errorMessage = 'Issue while sending email. Please try after some time.';
            }
        } else {
            $error = true;
            $errorMessage = 'Please select al least one invoice';
        }
    } else {
        $error = true;
        $errorMessage = 'Please enter email id';
    }


}

if (isset($_POST['print'])) {
    if (isset($_POST['check']) && $_POST['check'] != "") {
        $checkbox = $_POST['check'];
    } else {
        $error = true;
        $errorMessage = 'Please select atleast one checkbox';
    }
    if (!$error) {
        $inner_array = array();
        $id = "";
        for ($i = 0; $i < count($checkbox); $i++) {
            if ($id != "") {
                $id .= "," . $security->encrypt($checkbox[$i]);
            } else {
                $id .= $security->encrypt($checkbox[$i]);
            }
        }
        header('location:user-invoice.php?user_invoice_id=' . $id);
    }
}


if (isset($_POST['cancel'])) {
    if (isset($_POST['check']) && $_POST['check'] != "") {
        $checkbox = $_POST['check'];
    } else {
        $error = true;
        $errorMessage = 'Please select atleast one checkbox.';
    }
    if (!$error) {
        $inner_array = array();
        $id = "";
        for ($i = 0; $i < count($checkbox); $i++) {
            $id = $checkbox[$i];
            $condition = array('id' => $id);
            $invoiceData = array('STATUS'=>'cancel');
            $updateInovice = $manage->update($manage->userSubscriptionTable, $invoiceData, $condition);
        }
        if($updateInovice){
            $error = false;
            $errorMessage = 'Invoice Cancel Successfully.';
        }else{
            $error = true;
            $errorMessage = 'Issue while cancel invoice! check your internet or try after some time.';
        }
    }
}
?>


<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Invoice Report</title>
    <?php include "assets/common-includes/header_includes.php" ?>
    <style>
        table {
            page-break-inside: auto
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto
        }

        thead {
            display: table-header-group
        }

        tfoot {
            display: table-footer-group
        }

        @media print {
            footer {
                page-break-after: always;
            }
        }

        @page {
            size: auto;   /* auto is the initial value */
            margin: 5mm 10mm;  /* this affects the margin in the printer settings */
        }
    </style>
</head>
<body>
<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Invoice Report</h2>
        </div>
        <div class="row clearfix">
            <form method="post" action="">
                <div class="col-lg-12 col-md-4 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        <h2>
                                            Manage Invoice <span class="badge"><?php
                                                if (isset($countDescription)) echo $countDescription;
                                                ?></span>
                                        </h2>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="email" name="txt_email" class="form-control"
                                               placeholder="Enter email id to receive report">
                                    </div>
                                    <div class="col-md-2">
                                        <button type='submit' name="send_email" class='btn btn-success'>Send EMAIL
                                        </button>
                                    </div>
                                    <div class="col-md-2">
                                        <button type='submit' name="cancel" class='btn btn-danger'>Cancel Invoice
                                        </button>
                                    </div>
                                    <div class="col-md-1 text-right">
                                        <button type='submit' name="print" class='btn btn-default'>Print</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if ($error) {
                            ?>
                            <div class="alert alert-danger">
                                <?php if (isset($errorMessage)) echo $errorMessage; ?>
                            </div>
                        <?php
                        } else if (!$error && $errorMessage != "") {
                            ?>
                            <div class="alert alert-success">
                                <?php if (isset($errorMessage)) echo $errorMessage; ?>
                            </div>
                        <?php
                        }
                        ?>
                        <div class="col-md-12 invoice_margin">

                            <ul class="ul_search">
                                <li class="ul_search_li">
                                    <div class='form-line'>
                                        <label>From Date</label>
                                        <input type='date' name='from_date' class='form-control'
                                               value="<?php if (isset($from_date)) echo $from_date; ?>">
                                    </div>
                                </li>
                                <li class="ul_search_li">
                                    <div class='form-line'>
                                        <label>To Date</label>
                                        <input type='date' name='to_date' class='form-control'
                                               value="<?php if (isset($to_date)) echo $to_date; ?>">
                                    </div>
                                </li>
                                <li class="ul_search_li">

                                    <select class="form-control show-tick" name="drp_status">
                                        <option value="">All
                                        </option>
                                        <option value="success" <?php if (isset($status) && $status == "success") {
                                            echo 'selected="selected"';
                                        } ?>>SUCCESS
                                        </option>
                                        <option value="failed" <?php if (isset($status) && $status == "failed") {
                                            echo 'selected="selected"';
                                        } ?>>FAILED
                                        </option>
                                        <option value="cancel" <?php if (isset($status) && $status == "cancel") {
                                            echo 'selected="selected"';
                                        } ?>>CANCEL
                                        </option>
                                    </select>
                                </li>
                                <li class="ul_search_li">
                                    <div class='form-inline'>
                                        <button type='submit' name='search' class='btn btn-primary'>Search</button>
                                        <a type='button' href="" class='btn btn-danger'>Cancel</a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="body table-responsive table_scroll">
                            <table class="table table-condensed table-bordered table-striped">
                                <thead>
                                <tr class="back-color">
                                    <th><input type="checkbox" id="checkAl"></th>
                                    <th>Invoice No/Date</th>
                                    <th>Profile</th>
                                    <th>Package</th>
                                    <th>Amount</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Payment Status</th>
                                    <!--<th>Status</th>-->
                                    <th>Action</th>
                                    <!--<th>ACTION</th>-->
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if ($displaySubscription != null) {
                                    $final_amount = 0;
                                    while ($result_data = mysqli_fetch_array($displaySubscription)) {
                                        ?>
                                        <tr <?php if ($result_data['status'] == "success") {
                                            echo 'style="background-color: #B2DFDB"';
                                        } elseif ($result_data['status'] == "failed") {
                                            echo 'style="background-color: #FFCDD2"';
                                        }elseif($result_data['status'] == "cancel"){
                                            echo 'style="background-color: #fba8a8"';
                                        } ?>>
                                            <!--<td><?php /*echo $result_data['name']; */ ?></td>
                                    <td><?php /*echo $result_data['contact']; */ ?></td>-->
                                            <!--<td><?php /*if ($result_data['type'] == 1) {
                                            echo "Digital Card";
                                        } else {
                                            echo "Digital Card + Website (combo)";
                                        } */ ?></td>-->
                                            <td><input type="checkbox" id="checkItem" name="check[]"
                                                       value="<?php echo $result_data["id"]; ?>"></td>
                                            <td><?php echo $result_data['invoice_no'] . " / " . $result_data['timestamp'];
                                                if ($result_data['type'] != null && $result_data['type'] != "1") {
                                                    echo "<br><label class='label label-success'>" . $result_data['type'] . "</label>";
                                                }
                                                ?></td>
                                            <td><?php echo $result_data['for_bill']; ?>
                                                <br><?php echo $result_data['for_email']; ?></td>
                                            <td><?php echo $result_data['year']; ?></td>
                                            <td><?php $final_amount += $result_data['total_amount']; echo $result_data['total_amount']; ?></td>
                                            <td><?php echo $result_data['start_date']; ?></td>
                                            <td><?php echo $result_data['end_date']; ?></td>
                                            <td><?php echo strtoupper($result_data['status']); ?><br><label
                                                    class="label <?php if ($result_data['active_plan'] == "0") {
                                                        echo "label-danger";
                                                    } else {
                                                        echo "label-success";
                                                    } ?>"><?php if ($result_data['active_plan'] == 0) {
                                                        echo "Expired";
                                                    } else {
                                                        echo "Active";
                                                    } ?></label></td>
                                            <td>
                                                <a class="btn btn-default" <?php if ($result_data['invoice_no'] == "" or $result_data['invoice_no'] == 'NULL') {
                                                    echo 'disabled';
                                                    echo ' href="#"';
                                                } else {
                                                    echo 'href="user-invoice.php?user_invoice_id=' . $security->encrypt($result_data['id']) . '"';
                                                } ?>>View Invoice</a></td>

                                        </tr>
                                    <?php
                                    }
                                    ?>
                                <?php
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="10" class="text-center">No data found!</td>
                                    </tr>
                                <?php
                                }
                                ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5" class="text-right"><?php echo $final_amount; ?></td>
                                        <td colspan="4"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div>
                </div>
            </form>
            <!-- <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a href="image-slider.php">
                    <div class="info-box bg-pink hover-expand-effect">
                        <div class="icon">
                            <i class="far fa-image"></i>
                        </div>
                        <div class="content">
                            <div class="text">Image Slider</div>
                            <div class="number count-to"><?php /*if (isset($sliderCount)) echo $sliderCount; */ ?></div>
                        </div>
                    </div>
                </a>
            </div>-->
        </div>
    </div>
</section>
<?php include "assets/common-includes/footer_includes.php" ?>
<script>
    $("#checkAl").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
</script>
</body>
</html>