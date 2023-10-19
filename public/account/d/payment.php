
<?php

require_once "../controller/ManageDesktopCard.php";
$manage = new ManageDesktopCard();
include_once '../sendMail/sendMail.php';
require_once "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include "../data-uri-image.php";
$error = false;
$errorMessage = "";
$error1 = false;
$errorMessage1 = "";
$Themerror = false;
$ThemerrorMessage = "";
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
    $link = "https";
else
    $link = "http";
$link .= "://";
$link = "";
$link .= $_SERVER['HTTP_HOST'];

$link .= $_SERVER['REQUEST_URI'];

$date = date("Y-m-d");
include "assets/common-includes/all-query.php";


$getDetails = $manage->getGatewayPaymentDetails($user_id);
if ($getDetails != null) {
    $upi_id = $getDetails['upi_id'];
    $upi_mobile_no = $getDetails['upi_mobile_no'];
}else{
    $upi_id = "";
    $upi_mobile_no = "";
}

if($BankSectionStatus != 1){
    $redirect = FULL_DESKTOP_URL . "index" . get_full_param();
    header('Location: '.$redirect);
    die();
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <title>Payment - <?php echo $name; ?> - <?php echo $designation; ?> -<?php echo $_SERVER['HTTP_HOST']; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "assets/common-includes/header_includes.php" ?>
</head>

<body class="background_body_image">
<?php
/*
echo $name;
die();*/

?>
<div class="end_sub_overlay">
    <div style="margin-top: 10%;text-align: center;"><!--class="bg-text"-->
        <img src="<?php echo FULL_DESKTOP_URL; ?>assets/images/sub.png" style="width: 40%">
    </div>
</div>

<section>
    <div class="digi-heading"></div>
    <div class="container">
        <div class="digi-web-main">
            <div>
                <?php include "assets/common-includes/left_menu.php" ?><!--Left Menu-->
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-12 bhoechie-tab-container">
                            <div class=" col-md-2  bhoechie-tab-menu-custom">
                                <?php include "assets/common-includes/nav_tab.php" ?>
                            </div>
                            <div class=" col-md-10 bhoechie-tab margin-padding-remover">
                                <div class="bhoechie-tab-content margin-padding-remover"></div>
                                <?php
                                /*                                if ($get_service_status != null) {
                                                                    if (isset($_GET['custom_url']) && $get_service_status['digital_card'] == 1) {
                                                                        $alreadyActiveSet = true;
                                                                        $alreadyActiveContent = true;*/

                                include "assets/common-includes/payment.php";
                                /*   }
                               }
                               */ ?>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

</section>

<script>
    function Payme() {
        var desc = $(".remark1").val();
        var amount = $(".amt1").val();
        if(amount == '') {
            $("#amountReguired1").text('Please enter amount');
        }else{
            window.open('https://www.paypal.com/cgi-bin/webscr?business=<?php echo $paypal_email; ?>&cmd=_xclick&currency_code=USD&amount='+amount+'&item_name='+encodeURIComponent(desc)+'', '_blank', '');
       }

    }
</script>



<!--<script type="text/javascript">
    if (screen.width <= 768 || screen.height == 480) //if 1024x768
        window.location.replace("../<?php /*if(isset($_GET['custom_url'])) echo $_GET['custom_url'];*/ ?>")
</script>-->

<?php include "assets/common-includes/footer.php" ?>
<?php include "assets/common-includes/footer_includes.php" ?>
<?php /*include "../assets/common-includes/mobile-desktop-url-changer.php" */ ?>
</body>
</html>
