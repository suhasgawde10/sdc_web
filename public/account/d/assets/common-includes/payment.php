<?php

if (isset($_GET['custom_url'])) {
    if ($parent_id != "") {
        $custom_url = $getParentData['custom_url'];
    } else {
        $custom_url = $_GET['custom_url'];
    }

    $get_bank_result = $manage->mdm_getDigitalCardDetails("bank", $custom_url);
    $get_count_paypal = $manage->countPayPal($default_user_id);
    if ($get_count_paypal != null) {
        $paypal_email = $get_count_paypal['paypal_email'];
        $paypal_link = $get_count_paypal['paypal_link'];
    } else {
        $paypal_email = "";
        $paypal_link = "";
    }
}
if (!$validToken) {
    echo '<style>
    #paymentGateway,#payPalGateway{
        display: block;
        bottom: 10px;
        z-index:1;
         background-color: rgba(0,0,0,0.6);
    }
</style>';
}
echo '<style>
    .modal{
        z-index:99
    }
</style>';
?>




<div class="bhoechie-tab-content margin-padding-remover active">
    <section>
        <div class="content-main  background-theme-cust">
            <div class="all-main-heading">
                <span class="text-color-p"><?php echo $payment; ?></span>
                <?php /*if (isset($_SESSION['email'])) { */ ?><!-- <a title="Add Service" class="add-icon-color fas fa-pencil-alt" href=FULL_WEBSITE_URL."user/payment.php">&nbsp;&nbsp;Edit</a>
                --><?php /*} */ ?>
            </div>
            <div class="cust-coverlay overlay-height">
                <div class="container-fluid padding-remover">
                    <div class="bank-up-div">
                        <div class="bank-detail-padding scrollbar style-11">
                            <?php
                            if ($country == "101") {
                            ?>
                                <div class="bank">
                                    <?php
                                    if ($get_bank_result != null) { ?>
                                        <h4>Pay Using</h4>
                                        <?php
                                        $i = 1;
                                        while ($result_bank_data = mysqli_fetch_array($get_bank_result)) {
                                        ?>

                                            <div class="bank-detail">
                                                <a href="#myBankModal<?php echo $i; ?>" data-toggle="modal"><img src="<?php echo FULL_DESKTOP_URL; ?>assets/images/payment-icon/banked.jpg">
                                                    &nbsp;&nbsp;<?php echo $security->decrypt($result_bank_data['bank_name']); ?>
                                                    <span>Account Details</span></a>

                                                <div class="modal fade " id="myBankModal<?php echo $i; ?>" role="dialog">
                                                    <div class="modal-dialog cust-model-dialog">
                                                        <!-- Modal content-->
                                                        <div class="modal-content">
                                                            <div class="modal-header bank-model-header">
                                                                <button type="button" class="close cust-close" data-dismiss="modal">&times;
                                                                </button>
                                                                <h4 class="modal-title cust-model-heading">Bank
                                                                    Details</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-model">

                                                                    <table class="bank-model-table table-striped">
                                                                        <?php
                                                                        if ($validToken) {
                                                                        ?>
                                                                            <tr>
                                                                                <?php
                                                                                $bank_details_content = "IFSC Code: " . $security->decrypt($result_bank_data['ifsc_code']) . " | Account Number: " . $security->decrypt($result_bank_data['account_number']) . " | Bank Name: " . $security->decrypt($result_bank_data['bank_name']) . " | Name: " . $security->decrypt($result_bank_data['name']);
                                                                                ?>
                                                                                <td colspan="3" class="text-center">
                                                                                    <img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=<?php echo urlencode($bank_details_content); ?>&choe=UTF-8" style="width: 35%" title="Bank Details" />
                                                                                    <h4 class="mb-0">Scan to Pay</h4>
                                                                                </td>
                                                                            </tr>
                                                                        <?php
                                                                        }

                                                                        ?>
                                                                        <tr>
                                                                            <td><i class="fas fa-user"></i></td>
                                                                            <td>Name</td>
                                                                            <td><?php echo $security->decrypt($result_bank_data['name']); ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><i class="fas fa-rupee-sign"></i></td>
                                                                            <td>Bank Name</td>
                                                                            <td><?php echo $security->decrypt($result_bank_data['bank_name']); ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><i class="fas fa-info"></i></td>
                                                                            <td>Account Number</td>
                                                                            <td><?php if (!$validToken) {
                                                                                    echo substr($security->decrypt($result_bank_data['account_number']), 0, 2) . "**********";
                                                                                } else {
                                                                                    echo $security->decrypt($result_bank_data['account_number']);
                                                                                } ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><i class="fas fa-info"></i></td>
                                                                            <td>IFSC Code</td>
                                                                            <td><?php if (!$validToken) {
                                                                                    echo substr($security->decrypt($result_bank_data['ifsc_code']), 0, 2) . "*******";
                                                                                } else {
                                                                                    echo $security->decrypt($result_bank_data['ifsc_code']);
                                                                                } ?></td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer <?php if (!$validToken) echo 'bank_modal_footer_cust"'; ?>">
                                                                <?php
                                                                if (!$validToken) {
                                                                ?>
                                                                    <p>
                                                                        <img src="<?php echo FULL_WEBSITE_URL; ?>assets/img/lock-private.ico" class="bank_private_lock">Bank Details Are
                                                                        Private.
                                                                    </p>
                                                                <?php
                                                                } else {

                                                                ?>
                                                                    <button type="button" class="btn btn-default" onclick="setClipboard('<?php echo $bank_details_content; ?>','Bank details on the clipboard, try to paste it!')">
                                                                        Copy Bank Details
                                                                    </button>
                                                                <?php } ?>

                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                            $i++;
                                        }
                                    } else {
                                        ?>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding_zero padding_zero_both" style="position: relative">
                                            <div class="row">
                                                <div class="card cardupi">
                                                    <div class="text-center">
                                                        <h4>No Bank Information added.</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <?php
                                if ($country == "101") {
                                ?>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding_zero padding_zero_both" style="position: relative">
                                        <div class="row">
                                            <div class="end_sub_overlay" id="paymentGateway">

                                                <div class="upi_id_text">
                                                    <h5><?php if (!$validToken) {
                                                            echo "<img src='" . FULL_WEBSITE_URL . "assets/img/lock-private.ico' class='bank_private_lock'>UPI ID details are private.";
                                                        } else {
                                                            echo 'UPI ID is not configured';
                                                        } ?></h5>
                                                </div>
                                            </div>
                                            <div class="card cardupi">
                                                <div class="card_wallet">
                                                    <div class="upi-head">
                                                        <h2>
                                                            Configure Your Wallet
                                                        </h2>

                                                        <p>Please Check For Your UPI Details And Fill This form</p>
                                                    </div>


                                                    <ul class="upi-pay">
                                                        <li>
                                                            <div class="upi-img-div"><img class="img-circle" src="<?php echo FULL_WEBSITE_URL; ?>user/assets/images/gpay.png">
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="upi-img-div"><img class="img-circle" src="<?php echo FULL_WEBSITE_URL; ?>user/assets/images/paytm-512.png">
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="upi-img-div"><img class="img-circle" src="<?php echo FULL_WEBSITE_URL; ?>user/assets/images/PhonePe-off-campus-drive.png">
                                                            </div>
                                                        </li>


                                                    </ul>
                                                </div>
                                                <?php
                                                if ($validToken) {
                                                    $upi_qr_link = "upi://pay?cu=INR%26pa=" . $upi_id . "%26pn=" . $name;
                                                    $upi_qr_link = str_replace(' ', '%20', $upi_qr_link);
                                                } else {
                                                    $upi_qr_link = "upi@upi";
                                                }
                                                ?>
                                                <div class="card_wallet_div_img">
                                                    <!-- https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=upi://pay?cu=INR%26pa=suhas@axis%26pn=Suhas%20Gawde-->
                                                    <img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=<?php echo $upi_qr_link; ?>&choe=UTF-8" style="width: 70%" title="Bank Details" />
                                                    <h4>Scan to Pay</h4>
                                                    <!--             <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?php /*echo $upi_qr_link; */ ?>" style="width: 100%" title="Bank Details" />-->
                                                    <!--<iframe style="    width: 100%;border: none;
                                                            overflow: hidden;" id="qr-code" src="qr-code-upi.php?upi_data=<?php /*echo urlencode($upi_qr_link) */ ?>"></iframe>-->
                                                </div>
                                                <div class="upi-btn-div">
                                                    <button class="btn btn-primary upi-btn waves-effect" type="button" <?php
                                                                                                                        if ($validToken) {
                                                                                                                        ?> onclick="setClipboard('<?php echo $upi_id; ?>','UPI ID is on the clipboard, try to paste it!')" <?php } ?>>
                                                        Copy UPI ID
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            }
                            ?>

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding_zero padding_zero_both" style="position: relative">
                                <div class="row">
                                    <div class="end_sub_overlay" id="payPalGateway">
                                        <div class="upi_id_text">
                                            <h5><?php if (!$validToken) {
                                                    echo "<img src='" . FULL_WEBSITE_URL . "assets/img/lock-private.ico' class='bank_private_lock'>PayPal details are private.";
                                                } else {
                                                    echo 'PayPal is not configured';
                                                } ?></h5>
                                        </div>
                                    </div>
                                    <div class="card cardupi">
                                        <div class="upi-head">
                                            <h2>
                                                Configure Your PayPal Account
                                            </h2>
                                        </div>
                                        <div class="card_wallet">
                                            <p>Registered Email Id
                                                : <?php if (isset($paypal_email) && $paypal_email != "") {
                                                        echo $paypal_email;
                                                    } else {
                                                        echo "<b>Not Yet Configure</b>";
                                                    } ?></p>

                                            <p>Payme Link : <?php if (isset($paypal_email) && $paypal_email != "") { ?>
                                                    <a href="<?php echo $paypal_link ?>"><?php echo wordwrap($paypal_link, "57", "<br>") ?></a><?php } else {
                                                                                                                                                echo "<b>Not Yet Configured</b>";
                                                                                                                                            } ?>
                                            </p>
                                            <ul class="upi-pay">
                                                <li>
                                                    <div class="upi-img-div"><img class="img-circle" style="border:1px solid #ccc" src="<?php echo FULL_WEBSITE_URL; ?>user/assets/images/paypal.png">
                                                    </div>
                                                </li>
                                            </ul>

                                        </div>
                                        <?php

                                        if (isset($paypal_email) && $paypal_email != "") {
                                            if ($validToken) {
                                                $paypal_qr_code_link = "https://www.paypal.com/cgi-bin/webscr?business=" . $paypal_email . "%26cmd=_xclick%26currency_code=USD%26amount=%26item_name=";
                                            } else {
                                                $paypal_qr_code_link = "https://www.paypal.com/";
                                            } ?>
                                            <div class="card_wallet_div_img text-center">
                                                <!-- https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=upi://pay?cu=INR%26pa=suhas@axis%26pn=Suhas%20Gawde-->
                                                <img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=<?php echo $paypal_qr_code_link; ?>&choe=UTF-8" style="width: 100%" title="Paypal Details" />
                                                <h4>Scan to Pay</h4>
                                                <!--             <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?php /*echo $upi_qr_link; */ ?>" style="width: 100%" title="Bank Details" />-->
                                                <!--<iframe style="    width: 100%;border: none;
    overflow: hidden;" id="qr-code" src="qr-code-upi.php?upi_data=<?php /*echo urlencode($upi_qr_link) */ ?>"></iframe>-->
                                            </div>

                                            <div class="upi-btn-div">
                                                <button class="btn btn-primary upi-btn waves-effect" type="button" <?php
                                                                                                                    if ($validToken) {
                                                                                                                    ?> data-toggle="modal" data-target="#myPayPalModal" <?php } ?>>
                                                    Pay Now
                                                </button>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
                <!-- Modal -->
            </div>
        </div>

    </section>
</div>

<script>
    $('#qr-code').contents().find('#yourItemYouWantToChange').css({
        opacity: 0,
        color: 'purple'
    });
</script>

<div class="modal fade " id="myPayPalModal" role="dialog">
    <div class="modal-dialog cust-model-dialog" style="width: 400px;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bank-model-header">
                <button type="button" class="close cust-close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title cust-model-heading">Pay Using PayPal</h4>
            </div>
            <div class="modal-body">
                <div class="form-model">
                    <form method="post" action="">
                        <label>Amount In Dollar </label>&nbsp;<label class="red">*</label>&nbsp;&nbsp;&nbsp;<label class="amountReguired" id="amountReguired1"></label>
                        <input class="form-control amt1" type="number" name="amt" placeholder="Enter Amount in Dollar " required="required">
                        <label>Remark (Optional)</label>
                        <textarea class="form-control remark1" placeholder="Enter Remark" name="remark" rows="3"></textarea>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" onclick="Payme()">Pay Now
                </button>
            </div>
        </div>

    </div>
</div>