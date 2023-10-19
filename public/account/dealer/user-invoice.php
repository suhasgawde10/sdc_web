<?php
ob_start();
include_once "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include "../controller/ManageDealer.php";
$manage = new ManageDealer();
include "../controller/validator.php";
$validate = new Validator();
include '../sendMail/sendMail.php';

$error = false;
$errorMessage = "";
$display_message = $manage->getSessionDealerProfile();
if ($display_message != null) {
    $dealer_status = $display_message['status'];
    $pay_status = $display_message['pay_status'];
    $deal_code = $display_message['dealer_code'];
    /*  $invoice_gstn_no = $display_message['gstin_no'];
      $invoice_name = $display_message['c_name'];
      $invoice_email = $display_message['b_email_id'];

      if($invoice_name ==""){
          $invoice_name= $display_message['name'];
      }
      if($invoice_email  == ""){
          $invoice_email= $display_message['email'];
      }*/

}

if (isset($_GET['user_invoice_id'])) {
    $keyword_data = explode(",", $_GET['user_invoice_id']);
    $i = 1;
    $message = "";
    $count_invoice = count($keyword_data);
    foreach ($keyword_data as $key) {
        $get_user_data = $manage->getUserInvoiceData($security->decrypt($key));
        if ($get_user_data != null) {
            $user_name = $get_user_data['name'];
            $user_expiry_date = $get_user_data['end_date'];

            $user_start_date = $get_user_data['start_date'];
            $invoice_no = $get_user_data['invoice_no'];

            $year = $get_user_data['year'];
            $amount = $get_user_data['total_amount'];

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
            $invoice_name = $get_user_data['for_bill'];
            $for_email = $get_user_data['for_email'];

            $invoice_gstn_no = $get_user_data['for_gstno'];
            $invoice_pan_no = $get_user_data['for_pan'];
            $invoice_gstn_no_for_tax = $get_user_data['for_gstno'];
            $plan_amount = $get_user_data['plan_amount'];
            $for_address = $get_user_data['user_address'];
        }
        if ($invoice_gstn_no == '') {
            $invoice_gstn_no = "not applicable ";
        }
        if ($for_address != '') {
            $for_address = "<br><strong> Address : </strong>" . wordwrap($for_address, 40, "<br>");
        } else {
            $for_address = '';
        }
        $message .= '
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
<td colspan="3"><strong>Share Digital Card</strong></td>

<td colspan="2" style="text-align: right">
      <strong>Invoice Number : </strong>#INV' . $invoice_no . '<br>
     <strong>Invoice Date : </strong>' . $user_start_date . '</td>
</tr>


<tr>
<td colspan="3"><strong>Email : </strong>support@sharedigitalcard.com</td>

<td colspan="2" style="text-align: right">
          <strong> Bill for : </strong>' . $invoice_name . '<br><strong> Email : </strong>' . $for_email . $for_address . '</td>
          </td>
</tr>
<tr >
<td colspan="3"><strong>GSTN No : </strong> 27AEEFS3851B1Z7<br><strong>PAN No : </strong> AEEFS3851B</td>
<td colspan="2" style="text-align: right"><strong>GSTN No</strong> : ' . $invoice_gstn_no . '</td>
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
<td style="text-align: center">' . $year . ' ( - ' . $user_name . ') </td>
<td style="text-align: center">' . $user_start_date . '</td>
<td style="text-align: center">' . $user_expiry_date . '</td>
<td style="text-align: center">' . $sac_code . '</td>
<td style="text-align: right"> ' . $plan_amount . '</td>
</tr>
<tr>
<td colspan="3"></td>
<td>Taxable Amount : </td>
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

        $message .= '
<tr>
<td colspan="3"></td>
<td>Total Amount : </td>
<td style="text-align: right">
' . $total_amount . '
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


        if (isset($_POST['send_email'])) {
            $subject = "Purchased sharedigitalcard of " . $year . " plan @" . $total_amount . " and Invoice No: #INV" . $invoice_no;
            $sendEmail = $manage->sendMail($invoice_name, $for_email, $subject, $message);
            if ($sendEmail) {
                $error = false;
                $errorMessage = 'Invoice has been sent to your ' . $for_email . ' id.';
            } else {
                $error = false;
                $errorMessage = 'Issue while sending email. Please try after some time.';
            }

        }

    }

}


?>


<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title><?php if ($count_invoice > 1) {
            echo "Invoices";
        } else {
            echo "#" . $invoice_no . "-" . date('d-M-Y', strtotime($user_start_date)) . " - " . $invoice_name;
        } ?></title>
    <?php include "assets/common-includes/header_includes.php" ?>
    <style>
        td, th {
            padding: 10px;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            #printableArea, #printableArea * {
                visibility: visible;
            }

            #printableArea {
                position: absolute;
                left: 0;
                top: 0;
            }

        }

        @media print {
            footer {
                page-break-after: always;
            }
        }

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
    <div class="clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding_zero padding_zero_both">
            <div class="row margin_div_web">
                <form method="post" action="">
                    <div class="text-right">

                        <button class="btn btn-success" onclick="printDiv('printableArea')">
                            Print
                        </button>
                        <button class="btn btn-primary" name="send_email" type="submit">Send Email</button>
                    </div>
                    <br>
                    <div class="col-md-12 text-justify">
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
                        <div class="card">
                            <div class="body">
                                <div id="GFG" class="" style="margin-top: 0px">
                                    <?php
                                    echo $message;
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<?php include "assets/common-includes/footer_includes.php" ?>
<!--<script>
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
    }
</script>-->
<script>
    function printDiv() {
        var divContents = document.getElementById("GFG").innerHTML;
        var a = window.open('', '', 'height=500, width=500');
        a.document.write('<html>');
        a.document.write('<head>');
        a.document.write('<style>' +
            ' @media print {\n' +
            '            footer {page-break-after: always;}\n' +
            '        }\n' +
            '        table { page-break-inside:auto }\n' +
            '        tr    { page-break-inside:avoid; page-break-after:auto }\n' +
            '        thead { display:table-header-group }\n' +
            '        tfoot { display:table-footer-group }\n' +
            '        @page\n' +
            '        {\n' +
            '            size:  auto;   /* auto is the initial value */\n' +
            '            margin: 5mm 10mm;  /* this affects the margin in the printer settings */\n' +
            '        }\n' +
            '    </style></style><title><?php echo "#" . $invoice_no . date('d-M-Y', strtotime($user_start_date)) . " - " . $invoice_name; ?></title>');
        a.document.write('</head>');
        a.document.write('<body>');
        a.document.write(divContents);
        a.document.write('</body></html>');
        a.document.close();
        a.print();
    }
</script>
</body>
</html>
