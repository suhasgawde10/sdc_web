<?php
ob_start();
include "../controller/ManageDealer.php";
$manage = new ManageDealer();
include "../controller/validator.php";
$validate = new Validator();
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include '../sendMail/sendMail.php';

if (!isset($_SESSION['dealer_email'])) {
    header('location:../dealer-register.php');
}
require_once("functions.php");
$maxsize = 10485760;

$error = false;
$errorMessage = "";

$id = 0;

if(!isset($_GET['user_id']) && $_GET['user_id']!=""){
    header('location:dashboard.php');
}

include("session_includes.php");

$display_message = $manage->displayDealerProfile();
if ($display_message != null) {
    $message_status = $display_message['message_status'];
      $dealer_status = $display_message['status'];
      $pay_status = $display_message['pay_status'];
    $deal_code = $display_message['dealer_code'];
    $get_percent = $display_message['dealer_percent'];
    $get_prcent_data = $manage->getDealerPricingById($get_percent);
    $dealer_percent = $get_prcent_data['percentage'];
    $dealer_gstn_no = $display_message['gstin_no'];
}


$sub_plan = $manage->subscriptionPlan();
$sub_plan1 = $manage->subscriptionPlanForTrial();

$form_data = $manage->getSpecificUserProfile($security->decrypt($_GET['user_id']));
if ($form_data != null) {
    $street = $form_data['address'];

}

$orderDescription = "Plan"; //your script should substitute detailed description of your order here ( This field is not mandatory )
$country = "IN";//your script should substitute the customer's country code
$TMPL_CURRENCY = "INR";//your script should substitute the currency symbol in which you want to display amount
$currency = "INR";//your script should substitute the currency symbol in which you want to display amount
$city = "";//your script should substitute the customer's city
$state = "";//your script should substitute the customer's state
$postcode = "";//your script should substitute the customer's zip
$telnocc = "091";//your script should substitute the customer's contry code for tel no
$ip = "127.0.0.1"; // your script should replace it with your ip address
$reservedField1 = ""; //As of now this field is reserved and you need not put anything
$reservedField2 = ""; //As of now this field is reserved and you need not put anything
$terminalid = "";   //terminalid if provided
$paymentMode = ""; //payment type as applicable Credit Cards = CC, Vouchers = PV,  Ewallet = EW, NetBanking = NB
$paymentBrand = ""; //card type as applicable Visa = VISA; MasterCard=MC; Dinners= DINER; Amex= AMEX; Disc= DISC; CUP=CUP
$customerId = "";

/*$processUrl = "https://sandbox.paymentz.com/transaction/Checkout";*/
$processUrl = "https://secure.paymentz.in/transaction/Checkout";
$liveurl = "https://secure.live.com/transaction/PayProcessController";
$get_user_data = $manage->getUserData($security->decrypt($_GET['user_id']));
if ($get_user_data != null) {
    $user_expiry_date = $get_user_data['expiry_date'];
    $u_name = $get_user_data['name'];
    $u_email = $get_user_data['email'];
    $u_contact = $get_user_data['contact_no'];
    $update_user_count = $get_user_data['update_user_count'];
    $get_email_count = $get_user_data['email_count'];
    $custom_url = $get_user_data['custom_url'];
}
$profilePath = "../user/uploads/" . $u_email . "/profile/" . $get_user_data['img_name'];


?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>plan selection</title>
    <?php include "assets/common-includes/header_includes.php" ?>
</head>
<body onload="user_dealer_code()">
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
                                                        <input onclick="user_dealer_code()" type="radio"
                                                               name="rd_sub_plan"
                                                               value="<?php echo $row_data['year']; ?>" <?php if ($row_data['year'] == '1 year') echo "checked" ?>>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-md-4 col-xs-5 text-left">
                                                <input type="hidden" value="<?php echo $row_data['year']; ?>">
                                                <input type="hidden" value="<?php
                                                if ($row_data['amt'] != null)
                                                    echo "Rs: " . $row_data['amt']; ?>">
                                                <?php
                                                if ($row_data['year'] != 'Free Trail (5 days)') {
                                                    ?>
                                                    <h4 class="text-right"><b><?php
                                                            $new_amount = $dealer_percent * $row_data['amt']/100 ;
                                                            $new_amount = $row_data['amt'] - $new_amount;
                                                            $new_amount = round($new_amount);
                                                            echo "Rs: " . $new_amount;
                                                            ?></b></h4>
                                                    <?php
                                                }
                                                ?>
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
                        <div style="padding: 10px;background: #cacaca;"><a href="view_customer.php?user_id=<?php echo $_GET['user_id']; ?>"
                           target="_blank">
                            <div style="display: inline-block;vertical-align: top">
                                <img
                                    src="<?php if (!file_exists($profilePath) && $gender == "Male" or $row_data['img_name'] == "") {
                                        echo "uploads/male_user.png";
                                    } elseif (!file_exists($profilePath) && $gender == "Female" or $row_data['img_name'] == "") {
                                        echo "uploads/female_user.png";
                                    } else {
                                        echo $profilePath;
                                    } ?>" class="user_profile_image">
                            </div>
                            <div style="display: inline-block;">
                                <?php
                                echo $u_name."<br>";
                                echo $u_email." / ".$u_contact;
                                ?>
                            </div>
                            </a></div>
                        <div class="body">
                            <form name="frm1" method="post" action="<?php echo $processUrl; ?>">
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
                                    <thead>

                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
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
                                <input type="hidden" name="phone" value="<?php echo $form_data['contact_no']; ?>">
                                <input type="hidden" name="telnocc" value="<?php echo $telnocc; ?>">
                                <input type="hidden" name="email" value="<?php echo $form_data['email']; ?>">
                                <input type="hidden" name="terminalid" value="<?php echo $terminalid; ?>">
                                <input type="hidden" name="paymentMode" value="<?php echo $paymentMode; ?>">
                                <input type="hidden" name="paymentBrand" value="<?php echo $paymentBrand; ?>">
                                <input type="hidden" name="customerId" value="<?php echo $form_data['user_id']; ?>">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

</section>


<script>
    function user_dealer_code() {
        var get_radio_value = $('input[name=rd_sub_plan]:checked').val();
        var dealer_code = <?php echo "'". $deal_code ."'" ; ?>;
        var dataString = "dealer_code=" + dealer_code + "&year=" + get_radio_value + "&quantity=1" + "&user_id=" + <?php echo $security->decrypt($_GET['user_id']) ?>;
        $.ajax({
            type: "POST",
            url: "credit-ajax.php", // Name of the php files
            data: dataString,
            success: function (html) {
                $(".get_amount tbody").html(html);
                /*return false*/
            }
        });
    }
    function upgradeCreditByQuantity(val) {
        if(val>0){
            var get_radio_value = $('input[name=rd_sub_plan]:checked').val();
            var dealer_code = <?php echo "'". $deal_code ."'" ; ?>;
            var dataString = "dealer_code=" + dealer_code + "&year=" + get_radio_value + "&quantity=" + val + "&user_id=" + <?php echo $security->decrypt($_GET['user_id']) ?>;
            $.ajax({
                type: "POST",
                url: "credit-ajax.php", // Name of the php files
                data: dataString,
                success: function (html) {
                    $(".get_amount tbody").html(html);
                    /*return false*/
                }
            });
        }


    }
</script>

<?php include "assets/common-includes/footer_includes.php" ?>

</body>
</html>