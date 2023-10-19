<?php


include "../controller/ManageMobileCard.php";
$manage = new ManageMobileCard();
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();


if (isset($_GET['custom_url'])) {
    $get_data = $manage->mdm_getDigitalCardDetailsOFUser($_GET['custom_url']);
    $parent_id = $get_data['parent_id'];
    if ($parent_id != "") {
        $getParentData = $manage->getSpecificUserProfileById($parent_id);
        $custom_url = $getParentData['custom_url'];
    } else {
        $custom_url = $_GET['custom_url'];
    }

    $get_result = $manage->mdm_getDigitalCardDetails("bank", $custom_url);
} else {
    header('location:../index.php');
}

/*if (isset($_GET['custom_url'])) {
    if($parent_id !=""){
        $custom_url = $getParentData['custom_url'];
    }else{
        $custom_url = $_GET['custom_url'];
    }

    $get_result_data = $manage->mdm_getDigitalCardDetails("bank",$custom_url);
} else {
    header('location:../index.php');
}*/

include "assets/common-includes/count-includes.php";


$getDetails = $manage->getGatewayPaymentDetails($user_id);
if ($getDetails != null) {
    $upi_id = $getDetails['upi_id'];
    $upi_mobile_no = $getDetails['upi_mobile_no'];
} else {
    echo '<style>#paymentGateway{ display: block}</style>';
    $upi_id = "";
    $upi_mobile_no = "";
    $paymentModel = true;
}


/*if($BankSectionStatus != 1){
    $redirect = get_url_param_for_mobile("index.php");
    header('Location: '.$redirect);
    die();
}*/

?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "assets/common-includes/header_includes.php" ?>
    <style>
        .loader-overlay {
            display: none;
            opacity: 0.8;
            background: #fff;
            width: 100%;
            height: 100%;
            z-index: 999;
            top: 0;
            left: 0;
            right: 0;
            bottom:0;
            position: fixed;
        }
    </style>
</head>
<body>
<div class="loader-overlay"></div>
<?php
if (!$validToken) {
    echo "<style>.end_sub_overlay{display: block!important;    background-color: rgba(0,0,0,0.6);}</style>";
}
echo "<style>.end_sub_overlay{z-index:1!important;}</style>";
?>

<section>
    <div class="content-main">
        <div class="overlay overlay-height">
            <?php include "assets/common-includes/profile.php"; ?>
            <div class="payment-heading sticky_tab">
                <h3><?php echo $payment; ?></h3>
            </div>
            <div class="padding-right-scroll">
                <div class="bank-detail-padding scrollbar style-11">
                    <?php
                    if ($country == "101") {
                        ?>
                        <div class="bank">
                            <h4>Pay Using</h4>
                            <?php
                            if ($get_result != null) {
                                $i = 1;
                                while ($result_data = mysqli_fetch_array($get_result)) {
                                    ?>
                                    <div class="bank-detail">
                                        <div>
                                            <img src="<?php echo FULL_MOBILE_URL; ?>assets/images/payment-icon/banked.jpg" style="width: 100%;">
                                        </div>
                                        <div data-target="#myModal<?php echo $i; ?>" data-toggle="modal">
                                            <?php echo $security->decrypt($result_data['bank_name']); ?>
                                        </div>
                                        <div>
                                        <span data-target="#myModal<?php echo $i; ?>" data-toggle="modal"
                                              class="contact-icon-btm text-center">
                                            <i class="fa fa-eye"></i> view
                                        </span>
                                        </div>
                                        <div class="modal cust-model payment_modal_padding"
                                             id="myModal<?php echo $i; ?>" role="dialog"
                                             style="background: rgba(0,0,0,0.6) !important;">
                                            <div class="modal-dialog modal_margin animated fadeInUpBig">
                                                <div class="modal-content modal_width">
                                                    <div class="modal-header">
                                                        <button type="button" class="close"
                                                                data-dismiss="modal">&times;
                                                        </button>
                                                        <h4 class="modal-title cust-model-heading">Bank Details</h4>
                                                    </div>
                                                    <div class="modal-body" style="padding: 0">
                                                        <div class="form-model">
                                                            <table class="bank-model-table table-striped">
                                                                <?php
                                                                if ($validToken) {
                                                                    ?>
                                                                    <tr>
                                                                        <?php
                                                                        $bank_details_content = "IFSC Code: " . $security->decrypt($result_data['ifsc_code']) . " | Account Number: " . $security->decrypt($result_data['account_number']) . " | Bank Name: " . $security->decrypt($result_data['bank_name']) . " | Name: " . $security->decrypt($result_data['name']);
                                                                        ?>
                                                                        <td colspan="3" class="text-center">
                                                                            <img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=<?php echo urlencode($bank_details_content); ?>&choe=UTF-8"
                                                                                 style="width: 35%"
                                                                                 title="Bank Details"/>
                                                                            <h4 class="pb-0">Scan to Pay</h4>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                }

                                                                ?>
                                                                <tr>
                                                                    <td><i class="fas fa-user"></i>&nbsp;Name</td>
                                                                    <td><?php echo $security->decrypt($result_data['name']); ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><i class="fas fa-rupee-sign"></i>&nbsp;Bank Name
                                                                    </td>
                                                                    <td><?php echo $security->decrypt($result_data['bank_name']); ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><i class="fas fa-info"></i>&nbsp;Account Number
                                                                    </td>
                                                                    <td><?php if (!$validToken) {
                                                                            echo substr($security->decrypt($result_data['account_number']), 0, 2) . "**********";
                                                                        } else {
                                                                            echo $security->decrypt($result_data['account_number']);
                                                                        } ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><i class="fas fa-info"></i>&nbsp;IFSC Code</td>
                                                                    <td><?php if (!$validToken) {
                                                                            echo substr($security->decrypt($result_data['ifsc_code']), 0, 2) . "*******";
                                                                        } else {
                                                                            echo $security->decrypt($result_data['ifsc_code']);
                                                                        } ?></td>
                                                                </tr>
                                                                <tr>


                                                                    <?php
                                                                    if (!$validToken) {
                                                                        ?>
                                                                        <td colspan="2" class="bank_modal_footer_cust">
                                                                            <p>
                                                                                <img src="../assets/img/lock-private.ico"
                                                                                     class="bank_private_lock">Bank
                                                                                Details Are Private</p>
                                                                        </td>
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <td colspan="2">
                                                                            <button type="button"
                                                                                    class="btn form-control btn-primary"
                                                                                    onclick="setClipboard('<?php echo $bank_details_content; ?>','Bank details on the clipboard, try to paste it!')">
                                                                                Copy Bank Details
                                                                            </button>
                                                                        </td>
                                                                        <?php
                                                                    }
                                                                    ?>

                                                                </tr>
                                                            </table>

                                                        </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
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

                                <div class="gateway" style="position: relative">

                                    <div class="gateway-detail" style="position: relative">
                                        <div class="text-center">
                                            <h4 class="marg_padd_zero">No Bank Information added.</h4>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

                        </div>
                        <?php
                    }
                    $get_count_paypal = $manage->countPayPal($user_id);
                    if ($get_count_paypal != null) {
                        $paypal_email = $get_count_paypal['paypal_email'];
                        $paypal_link = $get_count_paypal['paypal_link'];
                    } else {
                        $paypal_email = "";
                        $paypal_link = "";
                    }
                    if ($paypal_email == "") {
                        echo '<style>#payPalGateway{display:block;z-index: 0}</style>';
                    }
                    if ($country == "101") {
                        ?>
                        <div class="gateway" style="position: relative">
                            <h4>Transfer Money</h4>
                            <!--                        --><?php //if(!$paymentModel){ echo "" ; }
                            ?>
                            <div class="gateway-detail" id="sample" style="position: relative">
                                <div class="end_sub_overlay" id="paymentGateway">
                                    <div class="upi_id_text">
                                        <h5><?php if (!$validToken) {
                                                echo '<img src="../assets/img/lock-private.ico" class="bank_private_lock">UPI ID details are private.';
                                            } else {
                                                echo 'UPI ID is not configured';
                                            } ?></h5>
                                    </div>
                                </div>
                                <h5>Pay through wallet app google pay phone pe etc..</h5>
                                <div class="upi_card_wallet">
                                    <a href="#"><img src="<?php echo FULL_MOBILE_URL; ?>assets/images/gpay.png"></a>&nbsp;
                                    <a href="#"><img src="<?php echo FULL_MOBILE_URL; ?>assets/images/paytm-512.png"></a>&nbsp;
                                    <a href="#"><img src="<?php echo FULL_MOBILE_URL; ?>assets/images/PhonePe-off-campus-drive.png"></a>
                                    <div class="text-left  pt-15">
                                        <button class="btn btn-primary" type="button"
                                                onclick="setClipboard('<?php if ($validToken) echo $upi_id; ?>','UPI ID is on the clipboard, try to paste it!')">
                                            Copy UPI ID
                                        </button>
                                        <button class="btn btn-success"
                                                id="sample" <?php if ($paymentModel OR !$validToken) {
                                            echo "disabled style='opacity: 1;'";
                                        } else {
                                            echo "data-target='#paymentModel' data-toggle='modal'";
                                        } ?> >Pay
                                            Now
                                        </button>
                                    </div>
                                </div>
                                <div class="upi_card_wallet_code">
                                    <?php

                                    if ($validToken) {
                                        $upi_qr_link = "upi://pay?cu=INR%26pa=" . $upi_id . "%26pn=" . $name;
                                        $upi_qr_link = str_replace(' ', '%20', $upi_qr_link);
                                    }else{
                                        $upi_qr_link = "upi@upi";
                                    }
                                   ?>

                                    <!-- https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=upi://pay?cu=INR%26pa=suhas@axis%26pn=Suhas%20Gawde-->
                                    <div>
                                        <img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=<?php echo $upi_qr_link; ?>&choe=UTF-8"
                                             style="width: 100%" title="Upi Details"/>
                                        <h4>Scan to Pay</h4>
                                    </div>
                                    <!--             <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?php /*echo $upi_qr_link; */ ?>" style="width: 100%" title="Bank Details" />-->
                                    <!--<iframe style="    width: 100%;border: none;
    overflow: hidden;" id="qr-code" src="qr-code-upi.php?upi_data=<?php /*echo urlencode($upi_qr_link) */ ?>"></iframe>-->

                                </div>


                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="gateway" style="position: relative">
                        <h4>Transfer Money Using PayPal</h4>
                        <!--                        --><?php //if(!$paymentModel){ echo "" ; } ?>
                        <div class="gateway-detail" id="sample" style="position: relative;margin-bottom: 70px;">
                            <div class="end_sub_overlay" id="payPalGateway">
                                <div class="upi_id_text">
                                    <h5><?php if (!$validToken) {
                                            echo '<img src="../assets/img/lock-private.ico" class="bank_private_lock">PayPal details are private.';;
                                        } else {
                                            echo 'PayPal is not configured';
                                        } ?></h5>
                                </div>
                            </div>
                            <p><b>Registered Email Id</b> : <?php echo $paypal_email ?></p>

                            <div>
                                <div class="upi_card_wallet">
                                    <p><b>Payme Link</b> : <a
                                                href="<?php echo $paypal_link ?>"><?php echo wordwrap($paypal_link, "57", "<br>") ?></a>
                                    </p>
                                    <a href="#"><img class="img-circle" style="border:1px solid #ccc;width: 23%;"
                                                     src="../user/assets/images/paypal.png"></a>
                                    <div class="text-left pt-15">
                                        <button class="btn btn-success"
                                                id="sample" <?php echo "data-target='#payPalModel' data-toggle='modal'"; ?> >
                                            Pay Now
                                        </button>
                                    </div>
                                </div>
                                <div class="upi_card_wallet_code">
                                    <?php
                                    if ($validToken) {
                                        $paypal_qr_code_link = "https://www.paypal.com/cgi-bin/webscr?business=" . $paypal_email . "%26cmd=_xclick%26currency_code=USD%26amount=%26item_name=";
                                    }else{
                                        $paypal_qr_code_link = "https://www.paypal.com/";
                                    }
                                    ?>
                                    <img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=<?php echo $paypal_qr_code_link; ?>&choe=UTF-8"
                                         style="width: 100%" title="Paypal Details"/>
                                    <h4>Scan to Pay</h4>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <?php include "assets/common-includes/footer.php" ?>
        </div>
    </div>
</section>

<div class="modal share_modal_padding cust-model" id="paymentModel" role="dialog"
     style="background: rgba(0,0,0,0.6) !important;">
    <div class="modal-dialog modal_margin animated fadeInUpBig">
        <div class="modal-content modal_width">
            <div class="modal-header ">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title cust-model-heading">Transfer Money</h4>
            </div>
            <div class="modal-body">

                <div class="form-model">
                    <form method="post" action="">

                        <label>Amount </label>&nbsp;<label class="red">*</label>&nbsp;&nbsp;&nbsp;<label
                                class="amountReguired" id="amountReguired"></label>
                        <input class="form-control" id="amt" type="number" name="amt" required="required">
                        <label>Remark (Optional)</label>
                        <textarea class="form-control" id="remark" name="remark" rows="3"></textarea>

                        <div class="form-group pay_now_btn">
                            <!--<button type="button" class="form-control btn btn-primary">Pay Now</button>-->
                            <a href="#" class="btn btn-primary" onclick="pay_now_modal()" name="pay_now_modal">pay now
                            </a>
                            <!--<button type="submit" class="btn btn-primary" name="pay_now_modal" onclick="location.href='upi://pay?pa=<?php /*echo $upi_id; */ ?>&pn=<?php /*echo $upi_mobile_no; */ ?>&mc=null&tid=null&tr=test101&tn=This%20is%20test%20payment&am=10&mam=null&cu=INR&url=null'">pay now</button>-->
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
            </div>
        </div>
    </div>
</div>
<div class="modal share_modal_padding cust-model" id="payPalModel" role="dialog"
     style="background: rgba(0,0,0,0.6) !important;">
    <div class="modal-dialog modal_margin animated fadeInUpBig">
        <div class="modal-content modal_width">
            <div class="modal-header ">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title cust-model-heading">Pay Using PayPal</h4>
            </div>
            <div class="modal-body">
                <div class="form-model">
                    <form method="post" action="">
                        <label>Amount In Dollar</label>&nbsp;<label class="red">*</label>&nbsp;&nbsp;&nbsp;<label
                                class="amountReguired" id="amountReguired1"></label>
                        <input class="form-control" id="amt1" type="number" name="amt" required="required">
                        <label>Remark (Optional)</label>
                        <textarea class="form-control" id="remark1" name="remark" rows="3"></textarea>

                        <div class="form-group pay_now_btn">
                            <!--<button type="button" class="form-control btn btn-primary">Pay Now</button>-->
                            <a href="#" class="btn btn-primary" onclick="Payme()" name="pay_now_modal">pay now
                            </a>
                            <!--<button type="submit" class="btn btn-primary" name="pay_now_modal" onclick="location.href='upi://pay?pa=<?php /*echo $upi_id; */ ?>&pn=<?php /*echo $upi_mobile_no; */ ?>&mc=null&tid=null&tr=test101&tn=This%20is%20test%20payment&am=10&mam=null&cu=INR&url=null'">pay now</button>-->
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
            </div>
        </div>
    </div>
</div>

<script>
    var div_top = $('.sticky_tab').offset().top;

    $(window).scroll(function () {
        var window_top = $(window).scrollTop() - 0;
        if (window_top > div_top) {
            if (!$('.sticky_tab').is('.sticky')) {
                $('.sticky_tab').addClass('sticky');
            }
        } else {
            $('.sticky_tab').removeClass('sticky');
        }
    });
</script>
<script>
    function Payme() {
        var remark = $("#remark1").val();
        var amount = $("#amt1").val();
        if (amount == '') {
            $("#amountReguired1").text('Please enter amount');
        } else {
            location.href = 'https://www.paypal.com/cgi-bin/webscr?business=<?php echo $paypal_email; ?>&cmd=_xclick&currency_code=USD&amount=' + amount + '&item_name=' + encodeURIComponent(remark);
        }
        return false;
    }
</script>

<script type="text/javascript">
    function pay_now_modal() {
        //alert("hi");
        var remark = $("#remark").val();
        var amount = $("#amt").val();
        if (amount == '') {
            $("#amountReguired1").html('Please enter amount');
        } else {
            /*console.log("upi://pay?pa=Q85477279@ybl&pn=9768904980&mc=null&tid=null&tr=" + remark + "&tn=This%20is%20test%20payment&am=" + amount + "&mam=null&cu=INR&url=null");*/
            location.href = "upi://pay?pa=<?php echo $upi_id; ?>&pn=<?php echo $upi_mobile_no;  ?>&mc=null&tid=null&tr=" + remark + "&tn=" + remark + "&am=" + amount + "&mam=null&cu=INR&url=null";
        }
        return false;
    };

</script>


<?php include "assets/common-includes/footer_includes.php" ?>

<script>
//    $('.load-mobile-redirect').click(function () {
//        $(".loader-overlay").css("display", "block");
//        $('.loader-overlay').html('<img src="<?php //echo FULL_MOBILE_URL ?>//assets/images/loader-below.gif" style="width: 100%;height: 60vh;"/>');
////        return false
//    });
</script>
</body>
</html>