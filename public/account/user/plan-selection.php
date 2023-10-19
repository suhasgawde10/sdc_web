<?php
ob_start();
error_reporting(0);
date_default_timezone_set("Asia/Kolkata");
include_once "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include "../controller/ManageUser.php";
$manage = new ManageUser();
include "../controller/validator.php";
$validate = new Validator();
include '../sendMail/sendMail.php';

include("android-login.php");

require_once("functions.php");
unset($_SESSION['referral_code']);

$maxsize = 10485760;

$error = false;
$errorMessage = "";

/*require_once "../controller/RazorpayMaster.php";
$payment = new RazorpayMaster();*/
$id = 0;
include("session_includes.php");
$amount = 0;

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

$ip = getRealIpAddr(); // your ip address here
$ip_query = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip));

if($ip_query && $ip_query['status'] == 'success'){
    $countryName = $ip_query['country'];
}else{
    $countryName = '';
}

$form_data = $manage->getSpecificUserProfile();
if ($form_data != null) {
    $street = $form_data['address'];
    $company_name = $form_data['company_name'];
    $gst_no = $form_data['gst_no'];
    $pan_no = $form_data['pan_no'];
    $about_us = $form_data['about_company'];
    $our_mission = $form_data['our_mission'];
    $company_profile = $form_data['company_profile'];
    $city = $form_data['city'];
}

$get_user_expiry_count = $manage->selectTheme();
if ($get_user_expiry_count != null) {
    $update_user_count = $get_user_expiry_count['update_user_count'];
    $get_email_count = $get_user_expiry_count['email_count'];
    $referer_code = $get_user_expiry_count['referer_code'];
    $sell_ref = $get_user_expiry_count['sell_ref'];

}
$validateReferealCode = $manage->validateDiscountCode($referer_code);

if (isset($_POST['try_now'])) {
    $date1 = date("Y-m-d");
    $date = date_create("$date1");
    date_add($date, date_interval_create_from_date_string("5 days"));
    $final_date = date_format($date, "Y-m-d");
    /*echo "here";
    die();*/
    $get_user_expiry_count = $manage->selectTheme();
    if ($get_user_expiry_count != null) {
        $update_user_count = $get_user_expiry_count['update_user_count'];
        $get_email_count = $get_user_expiry_count['email_count'];
        $referer_code = $get_user_expiry_count['referer_code'];
    }
    $updateUserExpiry = $manage->updateUserExpiryDate($final_date);
    if ($updateUserExpiry) {
        header('location:payment.php');
    }
}

$sub_plan = $manage->subscriptionPlanWithFree();
$sub_plan1 = $manage->subscriptionPlan();

if(isset($_POST['btn_submit'])){
    if(isset($_POST['drp_new_year']) && $_POST['drp_new_year'] !=''){
        $_SESSION['new_year'] = $_POST['drp_new_year'];
        header('location:plan-selection-page-2.php');
    }
}
function fetch_all_data($result)
{
    $all = array();
    while($thing = mysqli_fetch_array($result)) {
        $all[] = $thing;
    }
    return $all;
}
$get_data = fetch_all_data($sub_plan1);

?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php include "assets/common-includes/total_count.php" ?>
    <title>plan selection</title>
    <?php include "assets/common-includes/header_includes.php" ?>
    <style>
        table{
            margin: 20px 0;
            width: 100%;
        }
        table tbody tr td:nth-child(1){
            text-align: left;
        }
        #get_amount1 table {
            display: none;
        }

        input[name="payment_type"]:not(:checked), input[name="payment_type"]:checked {
            position: unset;
            opacity: 1;
        }
        .pricing_include_ul {
            width: 100%;
            display: inline-block;
            vertical-align: top;
            text-align: center;
        }

        .pricing_include_ul li.price {
            width: 19%;
            display: inline-block;
            vertical-align: top;
            margin-right: 5px;
        }

         .pricing_include_ul li.price:first-child {
             margin-right: 40px;
         }
        .pricing_include_ul li.price {
            width: 32%;
        }

        .dropdown_custom {
            width: 100%;
            padding: 20px;
            background-color: white;
            box-shadow:0 8px 10px 0 rgb(0 0 0 / 13%);
            font-family: 'Lato', sans-serif;
            height:auto;
        }

        .dropdown__switch:checked + .dropdown__options-filter .dropdown__select {
            transform: scaleY(1);
        }
        .dropdown__switch:checked + .dropdown__options-filter .dropdown__filter:after {
            transform: rotate(-135deg);
        }
        .dropdown__options-filter {
            width: 100%;
            cursor: pointer;
        }
        .dropdown__filter {
            position: relative;
            width: 100%;
            display: block;
            padding-left: 0 !important;
            color: #595959;
            background-color: #fff;
            border: 1px solid #d6d6d6;
            border-radius: 0px;
            font-size: 14px;
            text-transform: uppercase;
            transition: .3s;
            margin-top: 0 !important;
        }
        .dropdown__filter:focus {
            border: 1px solid #918FF4;
            outline: none;
            box-shadow: 0 0 5px 3px #918FF4;
        }
        .dropdown__filter::after {
            position: absolute;
            top: 45%;
            right: 20px;
            content: '';
            width: 10px;
            height: 10px;
            border-right: 2px solid #595959;
            border-bottom: 2px solid #595959;
            transform: rotate(45deg) translateX(-45%);
            transition: .2s ease-in-out;
        }
        .dropdown__select {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            margin-top: 5px;
            overflow: hidden;
            box-shadow: 0 5px 10px 0 rgba(152, 152, 152, 0.6);
            transform: scaleY(0);
            transform-origin: top;
            font-weight: 300;
            transition: .2s ease-in-out;
            background: white;
            padding-left: 0 !important;
        }
        .dropdown__filter-selected{
            margin-top: 0 !important;
        }
        .dropdown__select-option {
            padding: 10px;
            background-color: #fff;
            border-bottom: 1px solid #d6d6d6;
            transition: .3s;
            margin-top: 0 !important;
        }
        .dropdown__select-option:last-of-type {
            border-bottom: 0;
        }
        .dropdown__select-option:hover {
            background-color: #f9f9f9;
        }

        .single-price button.pricing_btn{
            display: none;
            width: 100%;
            padding: 10px;
            color: white;
        }.single-price button.pricing_btn i{
             font-size: 16px;
             margin-left: 6px;
             font-weight: 600;
         }
        .single-price{
            padding-bottom: 0;
        }
        .single-price {
            height: auto;
            border-radius: 5px;
            -webkit-box-shadow: 0 3px 10px rgb(0 0 0 / 16%);
            box-shadow: 0 3px 10px rgb(0 0 0 / 16%);
            text-align: center;
            -webkit-transition: all .3s ease 0s;
            -o-transition: all .3s ease 0s;
            transition: all .3s ease 0s;
            background-color: #fff
        }

        .single-price:hover {
            -webkit-box-shadow: 0 3px 10px rgba(0, 0, 0, .1);
            box-shadow: 0 3px 10px rgba(0, 0, 0, .1)
        }

        .prc-head {
            background: #2793e6;
            text-align: center;
            padding: 2px;
            color: white;
        }

        .prc-head span {
            font-size: 18px;
            font-weight: 500;
            color: #fff;
            letter-spacing: 0;
            margin-bottom: 0;
            display: block
        }

        .prc-head h5 {
            font-size: 30px;
            color: #fff;
            letter-spacing: 0;
            font-weight: 500;
            line-height: 53px
        }

        .prc-head h5 small {
            color: #fff
        }

        .prc-head-drp {
            text-align: center;
        }

        .prc-head-drp span {
            font-size: 18px;
            font-weight: 500;
            color: #999;
            letter-spacing: 0;
            margin-bottom: 0;
            display: block
        }

        .prc-head-drp h6 {
            color: red;
            margin-bottom: 0;
            font-weight: 500;
            padding-top: 5px;
        }
        .prc-head-drp h5 {
             font-size: 23px;
             color: #666;
             letter-spacing: 0;
             font-weight: 500;
            margin-bottom: 0;
             line-height: 23px
         }

        .prc-head h5 small {
            color: #333
        }

        .single-price ul {
            text-align: left;
            margin-top: 10px;
            padding-left: 15px;
        }

        .single-price ul li {
            font-weight: 400;
            font-size: 14px;
            color: #666;
            line-height: 8px;
            margin-top: 8px;
            letter-spacing: 0;
            display: inline-block;
            width: 100%;
        }
        .single-price button.pricing_btn {
            font-size: 18px;
            font-weight: 400;
            letter-spacing: 0;
            color: #fff;
            border: 1px solid #2793e6;
            padding: 10px 21px;
            border-radius: 3px;
            display: none;
            margin-top: 10px
        }

        .single-price a:hover {
            color: #fff;
            background-color: #2793e6
        }
        [type="checkbox"] + label{
            padding-left: 0;
        }
        [type="checkbox"] + label:before, [type="checkbox"]:not(.filled-in) + label:after{
            opacity: 0;
        }
        s{
            font-size: 15px;
        }

    </style>
</head>
<body>
<?php
if (!isset($_GET['android_user_id']) && (!isset($_GET['type']) && $_GET['type'] != "android") && (!isset($_GET['api_key']))) {
?>
<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>
<section class="content custom-m-t-90">
    <?php
    }elseif (isset($_GET['android_user_id']) && (isset($_GET['type']) && $_GET['type'] == "android") && (isset($_GET['api_key']))) {
    ?>
    <section class="androidPlanSection">
        <?php
        }
        ?>

        <div class="clearfix">
           <div class="col-md-12">
               <form method="post" action="">
               <ul class="pricing_include_ul">
                   <li class="price">
                       <div class="single-price">
                           <div class="prc-head">
                               <h6>Digital Card</h6>
                           </div>

                           <input type="hidden"  name="drp_new_year" value="1 year" />
                           <div class="dropdown_custom">
                               <input type="checkbox" class="dropdown__switch" id="filter-switch1" hidden />

                               <label for="filter-switch1" class="dropdown__options-filter">
                                   <ul class="dropdown__filter" role="listbox" tabindex="-1">
                                       <li class="dropdown__filter-selected" aria-selected="true">
                                           <div class="prc-head-drp">
                                               <?php
                                               foreach($get_data as $key){
                                               if ($countryName == 'India' OR $countryName == '') {
                                                   $original_amount = "&#8377;999";
                                                   $amount = "&#8377;".$key['amt'];
                                               }else{
                                                   $original_amount = "$15";
                                                   $amount = "$".$key['usd_amt'];
                                               }
                                                   ?>
                                                   <h6><?php echo $key['year']; ?></h6>
                                                   <h5><?php echo $amount; ?> <s class="original_price"><?php echo $original_amount; ?></s>
                                                   </h5>
                                               <?php
                                               break;
                                               }
                                               ?>
                                           </div>
                                       </li>
                                       <li>
                                           <?php
                                           if ($sub_plan1 != null) {
                                               ?>
                                               <ul class="dropdown__select">
                                                   <?php
                                                   foreach($get_data as $row_data){
                                                       $year = $row_data['year'];
                                                       if($year =='3 year'){
                                                           $year = '2 year + 1 year (Free)';
                                                       }elseif($year =='5 year'){
                                                           $year = '3 year + 2 year (Free)';
                                                       }
                                                       if ($countryName == 'India' OR $countryName == '') {
                                                           $currency_symbol = "&#8377;";
                                                           $new_amount = $row_data['amt'];
                                                           if ($new_amount == "599") {
                                                               $original_amount = "999";
                                                           } elseif ($new_amount == "1199") {
                                                               $original_amount = "1999";
                                                           } elseif ($new_amount == "1799") {
                                                               $original_amount = "2999";
                                                           } elseif ($new_amount == "2999") {
                                                               $original_amount = "4999";
                                                           }
                                                       }else{
                                                           $currency_symbol = "$";
                                                           $new_amount = $row_data['usd_amt'];
                                                           if ($new_amount == "10") {
                                                               $original_amount = "15";
                                                           } elseif ($new_amount == "20") {
                                                               $original_amount = "25";
                                                           } elseif ($new_amount == "30") {
                                                               $original_amount = "35";
                                                           } elseif ($new_amount == "50") {
                                                               $original_amount = "55";
                                                           }
                                                       }
                                                       ?>
                                                       <li class="dropdown__select-option" role="option" data-year="<?php echo $row_data['year']; ?>">
                                                           <div class="prc-head-drp">

                                                               <h6><?php echo $year; ?></h6>
                                                               <h5><?php echo $currency_symbol.$new_amount; ?> <s class="original_price"><?php echo $currency_symbol.$original_amount; ?></s>
                                                               </h5>
                                                           </div>
                                                       </li>
                                                   <?php
                                                   }
                                                   ?>
                                               </ul>
                                               <?php
                                           }
                                           ?>
                                       </li>
                                   </ul>
                               </label>
                           </div>
                      <div>
                          <table class="table table-borderless get_amount">
                              <tbody></tbody>
                          </table>
                      <div class="pt-10">
                          <button name="btn_submit" type="submit" class="btn btn-success pricing_btn form"></button>
                      </div>
                      </div>

                       </div>
                   </li>
               </ul>
               </form>
           </div>
        </div>
    </section>


    <script>
        // Change option selected
        const label = document.querySelector('.dropdown__filter-selected');
        const options = Array.from(document.querySelectorAll('.dropdown__select-option'));
        const plan_amount = document.querySelector('input[name=drp_new_year]');
        options.forEach(option => {
            option.addEventListener('click', () => {
                label.innerHTML = option.innerHTML;
                plan_amount.value = option.getAttribute('data-year');
        get_value(option.getAttribute('data-year'));
            });
        });

        // Close dropdown onclick outside
        document.addEventListener('click', e => {
            const toggle = document.querySelector('.dropdown__switch');
            const element = e.target;

            if (element == toggle) return;

            const isDropdownChild = element.closest('.dropdown__filter');

            if (!isDropdownChild) {
                toggle.checked = false;
            }
        });
    </script>
<script>
    function update_company_info(){
        var valid = false;
        var company_name = $('input[name=company_name]').val();
        var gst_no = $('input[name=txt_gst_no]').val();
        if(company_name.trim() ==''){
            $('.alert-danger').show().text('Enter Company Name\n');
          valid = true;
        }
        if(gst_no.trim() ==''){
            $('.alert-danger').show().text('Enter Gst No\n');
            valid = true;
        }
        if(!valid) {
            var dataString = "updatate_company="+encodeURIComponent(company_name)+"&gst_no="+encodeURIComponent(gst_no);
            console.log(dataString);
            $.ajax({
                type: "POST",
                url: "get_radio_value.php", // Name of the php files
                data: dataString,
                beforeSend: function() {
                    // setting a timeout
                    $('#update_company_info').text('Saving...').attr('disabled','disabled');
                },
                success: function (html) {
                    if(html.trim() == 1){
                        $('.alert-danger').hide();
                        $('.alert-success').show().text('Company details saved successfully.\n');
                        /*
                        $("#user_company_info").modal("hide");
                        $('#add_gst_no').hide();*/
                        $('#update_company_info').text('Save details').removeAttr('disabled')
                    }else {
                        $('.alert-danger').show().text('Issue while updating please try after some time.');
                        $('#update_company_info').text('Save details').removeAttr('disabled')
                    }

                }
            });
        }
    }
    function sendNotification(){
        var dataString = "send_notification=plan";
        $.ajax({
            type: "POST",
            url: "get_radio_value.php", // Name of the php files
            data: dataString,
            beforeSend: function() {
                // setting a timeout
                $('.submit_btn').text('Processing...');
            },
            success: function (html) {
                $('.submit_btn').attr('type','submit');
               $('form').attr('action','<?php echo $processUrl; ?>');
                $('.submit_btn')[0].click();
            }
        });
    }

</script>
    <?php
        if ($validateReferealCode) {
             ?>
        <script>
            window.onload = function () {
                default_user_coupon_code();
            }
        </script>
        <?php
        }elseif(like_match('%dealer%', $referer_code) == 1) {
             ?>
        <script>
            window.onload = function () {
                default_user_dealer_code();
            }
        </script>
    <?php
        }else{
         ?>
    <script>
        window.onload = function () {
            get_value('1 year');
        }
    </script>
    <!-- --><?php
        }
         ?>
    <script>
        function get_value(val) {
            var dataString = "radio_value=" + val + "&razor_pay=true" + "<?php if ($android_url != "") echo "&android_user_id=".$_GET['android_user_id']."&type=".$_GET['type']; ?>";
            $.ajax({
                type: "POST",
                url: "get_radio_value.php", // Name of the php files
                data: dataString,
                dataType:"json",
                success: function (result) {
                    $('.pricing_btn').text('Pay Now '+result.current_amount+'/-').show();
                    $(".get_amount tbody").html(result.data);
                    <?php if (like_match('%ref%', $referer_code) == 1) { ?>
                    default_user_referral_code();
                    <?php } ?>
                }
            });
        }

    </script>
    <script>
        function user_referral_code() {
            var refereal_code = $('.referral_code').val();
            var dataString = "refereal_code=" + refereal_code + "<?php if ($android_url != "") echo "&android_user_id=".$_GET['android_user_id']."&type=".$_GET['type']; ?>";
            var dataString1 = "check_code=" + refereal_code + "<?php if ($android_url != "") echo "&android_user_id=".$_GET['android_user_id']."&type=".$_GET['type']; ?>";
            var dataString2 = "check_refereal_code=" + refereal_code + "<?php if ($android_url != "") echo "&android_user_id=".$_GET['android_user_id']."&type=".$_GET['type']; ?>";
            $.ajax({
                type: "POST",
                url: "get_radio_value.php", // Name of the php files
                data: dataString,
                success: function (html) {
                    $(".code_msg").html(html);
                    /*return false*/
                    $(".hide_default").css("display", "none");

                }
            });
            $.ajax({
                type: "POST",
                url: "get_radio_value.php", // Name of the php files
                data: dataString1,
                success: function (html) {
                    $(".extra_month").html(html);
                    /*return false*/
                }
            });
            $.ajax({
                type: "POST",
                url: "get_radio_value.php", // Name of the php files
                data: dataString2,
                success: function (html) {
                    $(".code_msg2").html(html);
                    /*return false*/
                }
            });
        }

        function default_user_referral_code() {

            var refereal_code = '<?php echo $referer_code; ?>';
            var dataString1 = "check_code=" + refereal_code + "<?php if ($android_url != "") echo "&android_user_id=".$_GET['android_user_id']."&type=".$_GET['type']; ?>";
            var dataString = "refereal_code=" + refereal_code + "<?php if ($android_url != "") echo "&android_user_id=".$_GET['android_user_id']."&type=".$_GET['type']; ?>";
            $.ajax({
                type: "POST",
                url: "get_radio_value.php", // Name of the php files
                data: dataString,
                success: function (html) {
                    $(".code_msg").html(html);
                    /*return false*/
                    $(".hide_default").css("display", "none");

                }
            });
            $.ajax({
                type: "POST",
                url: "get_radio_value.php", // Name of the php files
                data: dataString1,
                success: function (html) {
                    $(".extra_month").html(html);
                    /*return false*/
                }
            });
        }
        function InvalidReferralCode() {
            var refereal_code = 'referetfr';
            var dataString = "refereal_code=" + refereal_code + "<?php if ($android_url != "") echo "&android_user_id=".$_GET['android_user_id']."&type=".$_GET['type']; ?>";
            var dataString1 = "check_code=" + refereal_code + "<?php if ($android_url != "") echo "&android_user_id=".$_GET['android_user_id']."&type=".$_GET['type']; ?>";
            $.ajax({
                type: "POST",
                url: "get_radio_value.php", // Name of the php files
                data: dataString,
                success: function (html) {
                    $(".code_msg").html(html);
                    return false
                }
            });
            $.ajax({
                type: "POST",
                url: "get_radio_value.php", // Name of the php files
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
            /* if (get_radio_value == 'Free Trail (15 days)') {
             $(".validate_referral_code").css({"display": "none"});
             $('.validate_referral_code').hide();
             $('.horizontal_row').hide();
             $('.answer').hide();
             } else {
             $(".validate_referral_code").css({"display": "block"});
             $('.validate_referral_code').show();
             $('.coupon_question').show();
             $('.horizontal_row').show();
             }*/
            var dealer_code = $('.dealer_code').val();
            var dataString = "dealer_code=" + encodeURIComponent(dealer_code) + "&year=" + get_radio_value + "<?php if ($android_url != "") echo "&android_user_id=".$_GET['android_user_id']."&type=".$_GET['type']; ?>";
            var dataString1 = "check_dealer_code=" + encodeURIComponent(dealer_code) + "<?php if ($android_url != "") echo "&android_user_id=".$_GET['android_user_id']."&type=".$_GET['type']; ?>";
            $.ajax({
                type: "POST",
                url: "get_radio_value.php", // Name of the php files
                data: dataString,
                success: function (html) {
                    $(".get_amount").html(html);
                    /*return false*/
                }
            });
            $.ajax({
                type: "POST",
                url: "get_radio_value.php", // Name of the php files
                data: dataString1,
                success: function (html) {
                    $(".code_msg1").html(html);
                    /*return false*/
                }
            });
        }
        function default_user_dealer_code() {
            var get_radio_value = $('input[name=rd_sub_plan]:checked').val();
            var dealer_code = '<?php echo $referer_code; ?>';
            var dataString = "dealer_code=" + dealer_code + "&year=" + get_radio_value + "<?php if ($android_url != "") echo "&android_user_id=".$_GET['android_user_id']."&type=".$_GET['type']; ?>";
            /*alert(dealer_code);*/
            $.ajax({
                type: "POST",
                url: "get_radio_value.php", // Name of the php files
                data: dataString,
                success: function (html) {
                    $(".get_amount").html(html);
                    /*return false*/
                }
            });
        }
        function InvalidDealerCode() {
            var get_radio_value = $('input[name=rd_sub_plan]:checked').val();
            var dealer_code = 'jdhfkjghdskfhjg';
            var dataString = "dealer_code=" + dealer_code + "&year=" + get_radio_value + "<?php if ($android_url != "") echo "&android_user_id=".$_GET['android_user_id']."&type=".$_GET['type']; ?>";
            var dataString1 = "check_dealer_code=" + dealer_code + "<?php if ($android_url != "") echo "&android_user_id=".$_GET['android_user_id']."&type=".$_GET['type']; ?>";
            $.ajax({
                type: "POST",
                url: "get_radio_value.php", // Name of the php files
                data: dataString,
                success: function (html) {
                    $(".get_amount").html(html);
                    /*return false*/
                }
            });
        }

    </script>
    <script>
        function user_coupon_code() {
            var get_radio_value = $('input[name=rd_sub_plan]:checked').val();
            /* if (get_radio_value == 'Free Trail (15 days)') {
             $(".validate_referral_code").css({"display": "none"});
             $('.validate_referral_code').hide();
             $('.horizontal_row').hide();
             $('.answer').hide();
             } else {
             $(".validate_referral_code").css({"display": "block"});
             $('.validate_referral_code').show();
             $('.coupon_question').show();
             $('.horizontal_row').show();
             }*/
            var coupon_code = $('input[name=coupon_code]').val();
            var dataString = "coupon_code=" + encodeURIComponent(coupon_code) + "&year=" + get_radio_value + "<?php if ($android_url != "") echo "&android_user_id=".$_GET['android_user_id']."&type=".$_GET['type']; ?>";
            var dataString1 = "check_coupon_code=" + encodeURIComponent(coupon_code) + "<?php if ($android_url != "") echo "&android_user_id=".$_GET['android_user_id']."&type=".$_GET['type']; ?>";
            $.ajax({
                type: "POST",
                url: "get_radio_value.php", // Name of the php files
                data: dataString,
                success: function (html) {
                    $(".get_amount").html(html);
                    /*return false*/
                }
            });
            $.ajax({
                type: "POST",
                url: "get_radio_value.php", // Name of the php files
                data: dataString1,
                success: function (html) {
                    $(".coupon_msg").html(html);
                    /*return false*/
                }
            });
        }
        function default_user_coupon_code() {
            var get_radio_value = $('input[name=rd_sub_plan]:checked').val();
            var coupon_code = '<?php echo $referer_code; ?>';
            var dataString = "coupon_code=" + coupon_code + "&year=" + get_radio_value + "<?php if ($android_url != "") echo "&android_user_id=".$_GET['android_user_id']."&type=".$_GET['type']; ?>";
            $.ajax({
                type: "POST",
                url: "get_radio_value.php", // Name of the php files
                data: dataString,
                success: function (html) {
                    $(".get_amount").html(html);
                    /*return false*/
                }
            });
        }
        function InvalidDealerCode() {
            var get_radio_value = $('input[name=rd_sub_plan]:checked').val();
            var coupon_code = 'jdhfkjghdskfhjg';
            var dataString = "coupon_code=" + coupon_code + "&year=" + get_radio_value + "<?php if ($android_url != "") echo "&android_user_id=".$_GET['android_user_id']."&type=".$_GET['type']; ?>";
            var dataString1 = "check_coupon_code=" + coupon_code + "<?php if ($android_url != "") echo "&android_user_id=".$_GET['android_user_id']."&type=".$_GET['type']; ?>";
            $.ajax({
                type: "POST",
                url: "get_radio_value.php", // Name of the php files
                data: dataString,
                success: function (html) {
                    $(".get_amount").html(html);
                    /*return false*/
                }
            });
        }

    </script>

    <!--<script>

function addTemporaryValue() {
var reference_code = <?php /*if (isset($_POST['grand_amount'])){ echo $_POST['grand_amount']; }elseif(isset($_POST['dealer_code'])){ echo $_POST['dealer_code']; }else{ echo ""; } */ ?>;
            var year = <?php /*if(isset($_POST['new_year'])) echo $_POST['new_year']; */ ?>;
            var dataString = "reference_code=" + reference_code + "&year=" + year + "<?php /*if ($android_url != "") echo "&android_user_id=".$_GET['android_user_id']."&type=".$_GET['type']; */ ?>";
            $.ajax({
                type: "POST",
                url: "get_radio_value.php",
                data: dataString,
                success: function (html) {

                }
            });
        }
    </script>-->

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