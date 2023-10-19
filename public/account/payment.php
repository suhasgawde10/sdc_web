<?php

include "controller/ManageUser.php";
$manage = new ManageUser();
include "controller/validator.php";
$validate = new Validator();
include "controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
$error = false;
$errorMessage = "";

$adminOTPMessage = "";
$adminErrorMessage = "";

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
$ip_query = @unserialize(file_get_contents('http://ip-api.com/php/' . $ip));

if ($ip_query && $ip_query['status'] == 'success') {
    $countryName = $ip_query['country'];
} else {
    $countryName = '';
}


if (isset($_GET['token']) && $_GET['token'] != "") {
    $user_id = $security->decryptWebservice($_GET['token']);
    $_SESSION['id'] = $security->encrypt($user_id);
}

$date = date("Y-m-d");


require('controller/razorpay-php/Razorpay.php');

use Razorpay\Api\Api;

$api = new Api($keyId, $keySecret);

require_once("functions.php");
unset($_SESSION['referral_code']);
unset($_SESSION['coupon_code']);
unset($_SESSION['user_dealer_code']);

$maxsize = 10485760;

$error = false;
$errorMessage = "";


$form_data = $manage->getSpecificUserProfileById($user_id);
if ($form_data != null) {
    $name = $form_data['name'];
    $street = $form_data['address'];
    $user_email = $form_data['email'];
    $contact_no = $form_data['contact_no'];
    $profilePath = "user/uploads/" . $user_email . "/profile/" . $form_data['img_name'];
    $expiry_date = $form_data['expiry_date'];
    $city = $form_data['city'];

    $company_name = $form_data['company_name'];
    $gst_no = $form_data['gst_no'];
    $user_country = $form_data['country'];
    $address = $form_data['address'];

}


$sub_plan = $manage->subscriptionPlan();


function fetch_all_data($result)
{
    $all = array();
    while ($thing = mysqli_fetch_array($result)) {
        $all[] = $thing;
    }
    return $all;
}

$get_data = fetch_all_data($sub_plan);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!--- Basic Page Needs  -->
    <meta charset="utf-8">
    <title>Payment | Online business and visiting card maker in India, Maharashtra, Mumbai.</title>
    <meta name="description"
          content="Sign in to digital card,log in to digital business card,Digital card is online digital representation of your profile, includes your personal information, bank details, and many more">
    <meta name="keywords"
          content="digital business card, digital visiting card, online visiting card, online business card, visiting card design, maker in, india, maharashtra, mumbai, business card design, customized design, attractive visiting card, share digital card, business card application, visiting card application, app, login, sign in">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!-- Mobile Specific Meta  -->

    <!-- Bootstrap Core Css -->
    <!-- <link href="assets/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">-->
    <!-- Waves Effect Css -->
    <!-- Custom Css -->
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'UA-144581468-1');
    </script>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <!--<link rel="stylesheet" href="assets/css/jquery-ui.css">-->

    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <!--<link rel="stylesheet" href="assets/css/magnificpopup.css">-->
    <!--<link rel="stylesheet" href="assets/css/jquery.mb.YTPlayer.min.css">-->

    <link rel="stylesheet" href="assets/css/typography.css">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/png" href="assets/img/logo/favicon.png">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    <!-- Bootstrap Core Css -->
    <!-- <link href="assets/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">-->
    <!-- Waves Effect Css -->
    <link href="user/assets/plugins/node-waves/waves.css" rel="stylesheet"/>
    <link href="assets/css/form.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>-->

    <style>
        .container {
            padding-left: 10px;
            padding-right: 10px;
        }

        .modal-header .close {
            margin-top: 0;
        }

        table {
            margin: 20px 0;
            width: 100%;
        }

        table tbody tr td:nth-child(1) {
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
            box-shadow: 0 8px 10px 0 rgb(0 0 0 / 13%);
            font-family: 'Lato', sans-serif;
            height: auto;
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

        .dropdown__filter-selected {
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

        .single-price button.pricing_btn {
            display: none;
            width: 100%;
            padding: 10px;
            color: white;
        }

        .single-price button.pricing_btn i {
            font-size: 16px;
            margin-left: 6px;
            font-weight: 600;
        }

        .single-price {
            padding-bottom: 0;
        }

        .single-price {
            height: auto;
            border-radius: 5px;
            text-align: center;
            -webkit-transition: all .3s ease 0s;
            -o-transition: all .3s ease 0s;
            transition: all .3s ease 0s;
            background-color: #fff;
            box-shadow: none;
            overflow: unset;
        }

        .single-price:hover {
            box-shadow: none
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
            color: unset;
            background-color: unset
        }

        [type="checkbox"] + label {
            padding-left: 0;
        }

        [type="checkbox"] + label:before, [type="checkbox"]:not(.filled-in) + label:after {
            opacity: 0;
        }

        s {
            font-size: 15px;
        }

        fieldset {
            min-width: 0;
            padding: 0;
            margin: 0;
            border: 0;

            border-radius: 10px;
        }

        legend {
            display: block;
            width: auto;
            font-size: 18px;
            border: 0;
            margin: 0 0px;
            font-weight: 600;
            padding-left: 5px;
            padding-right: 5px;
            color: #777;
        }

        .pricing_include_ul li.price {
            width: 100%;
        }
    </style>
</head>

<body onload="get_value('1 year')">
<?php
if (!isset($_GET['type'])) {
    ?>
    <header id="header">
        <div class="header-area" style="padding: 10px 0;">
            <div class="container">
                <div class="row">
                    <div class="menu-area">
                        <div class="col-md-2 col-sm-12 col-xs-12 text-center playstore_logo">
                            <div class="logo">
                                <a href="index.php"><img src="assets/img/logo/logo.png" alt="Digital Card logo"></a>
                            </div>
                            <a class="xyz hidden-lg hidden-md hidden-sm hidden-xs" href="#"><img
                                    class="playstore_logo_img" src="assets/img/google-play-badge.png"
                                    alt="digital card app"></a>
                        </div>
                        <div class="col-md-10 hidden-xs hidden-sm">
                            <div class="main-menu">
                                <nav class="nav-menu">
                                    <ul>
                                        <li class=" abc">
                                            <!-- class="xyz" --> <a href="index.php">Suhas Gawde : 9773884631<br>Ajay
                                                Chorge
                                                : 9768904980</a></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="col-sm-12 col-xs-12 visible-sm visible-xs">

                            <div class="row" style="background: #eee">
                                <div class="mobile_menu"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
<?php
}
?>
<!-- header area end -->
<section class="feature-area bg-gray padding_section margin_top_div bg-gray-resp" id="feature">
    <div class="container">
        <div class="col-md-12 hidden-sm col-xs-12 padd_zero">
            <div class="custom_card card">
                <div class="main_div">
                    <div class="first_right_div">
                        <div>
                            <img src="assets/img/schedule.png">
                        </div>
                        <div class="title-h3">
                            <h3>Your <span>trial period </span>has <span>expired</span></h3>
                        </div>
                        <div class="pricing_ul">
                            <ul>
                                <li><i class="fa fa-check-circle" aria-hidden="true"></i> Control panel</li>
                                <li><i class="fa fa-check-circle" aria-hidden="true"></i> All payment options</li>
                                <li><i class="fa fa-check-circle" aria-hidden="true"></i> Share your digital card</li>
                                <li><i class="fa fa-check-circle" aria-hidden="true"></i> Easy interface</li>
                                <li><i class="fa fa-check-circle" aria-hidden="true"></i> Secure fund transfer</li>
                            </ul>
                        </div>
                    </div>
                    <div class="second_right_div">
                        <div class="clearfix">
                            <div class="col-md-12 user-profile">
                                <div class="user_profile_1">
                                    <img
                                        src="<?php if (!file_exists($profilePath) && $gender == "Male" or $form_data['img_name'] == "") {
                                            echo "user/uploads/male_user.png";
                                        } elseif (!file_exists($profilePath) && $gender == "Female" or $form_data['img_name'] == "") {
                                            echo "user/uploads/female_user.png";
                                        } else {
                                            echo $profilePath;
                                        } ?>" class="profile_image">
                                </div>
                                <div class="user_profile_2">
                                    <h4><?php echo $name ?></h4>

                                    <p><?php if ($expiry_date < $date) {
                                            echo 'Your Digital Card Has Been Expired';
                                        } else {
                                            echo 'Your Digital Card Expired Soon';
                                        } ?></p>
                                </div>
                            </div>
                            <div class="col-md-12 payment_heading">
                                <h3>Select Plan to Renew Your Digital Card</h3>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 padding_zero padding_zero_both">
                                <div class="row">
                                    <div class="card">
                                        <div class="body">
                                            <fieldset>

                                                <legend class="legend_font_size" align="left">Billing Address</legend>

                                                <form method="post" action="">
                                                    <div class="width-prf">
                                                        <label class="form-label">Full Name</label> <span
                                                            class="required_field">*</span>

                                                        <div class="form-group form-float">
                                                            <div class="form-line">
                                                                <input name="txt_name" class="form-control"
                                                                       placeholder="Full name"
                                                                       value="<?php if (isset($_SESSION['invoice_name']) && $_SESSION['invoice_name'] != '') echo $_SESSION['invoice_name']; elseif (isset($name)) echo $name; ?>">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="width-prf">
                                                        <label class="form-label">Company Name</label>
                                                        <span>(Optional)</span>

                                                        <div class="form-group form-float">
                                                            <div class="form-line">
                                                                <input name="company_name" class="form-control"
                                                                       placeholder="Company Name"
                                                                       value="<?php if (isset($_SESSION['invoice_company_name']) && $_SESSION['invoice_company_name'] != '') echo $_SESSION['invoice_company_name']; elseif (isset($company_name)) echo $company_name; ?>">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <?php
                                                    if ($user_country == '101') {
                                                        ?>
                                                        <div class="width-prf">
                                                            <label class="form-label">GST No</label>

                                                            <div class="form-group form-float">
                                                                <div class="form-line">
                                                                    <input name="txt_gst_no" class="form-control"
                                                                           placeholder="GST NO"
                                                                           value="<?php if (isset($_SESSION['invoice_gst_no']) && $_SESSION['invoice_gst_no'] != '') echo $_SESSION['invoice_gst_no']; elseif (isset($gst_no)) echo $gst_no; ?>">
                                                                </div>
                                                            </div>
                                                        </div>

                                                    <?php
                                                    }
                                                    ?>
                                                    <div class="width-prf">
                                                        <label class="form-label">Address</label>

                                                        <div class="form-group form-float">
                                                            <div class="form-line">
                                    <textarea name="txt_address" class="form-control"
                                              placeholder="Address"><?php if (isset($_SESSION['invoice_address']) && $_SESSION['invoice_address'] != '') echo $_SESSION['invoice_address']; elseif (isset($address)) echo $address; ?></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-10 col-xs-12 padding_zero">
                                <div class="card">
                                    <div class="body" style="padding: 10px;">
                                        <ul class="pricing_include_ul">
                                            <li class="price">
                                                <div class="single-price">

                                                    <input type="hidden" name="drp_new_year" value="1 year"/>

                                                    <div class="dropdown_custom">
                                                        <input type="checkbox" class="dropdown__switch"
                                                               id="filter-switch1" hidden/>

                                                        <label for="filter-switch1" class="dropdown__options-filter">
                                                            <ul class="dropdown__filter" role="listbox" tabindex="-1">
                                                                <li class="dropdown__filter-selected"
                                                                    aria-selected="true">
                                                                    <div class="prc-head-drp">
                                                                        <?php
                                                                        foreach ($get_data as $key) {
                                                                            if ($countryName == 'India' OR $countryName == '') {
                                                                                $original_amount = "&#8377;999";
                                                                                $amount = "&#8377;" . $key['amt'];
                                                                            } else {
                                                                                $original_amount = "$15";
                                                                                $amount = "$" . $key['usd_amt'];
                                                                            }
                                                                            ?>
                                                                            <h6><?php echo $key['year']; ?></h6>
                                                                            <h5><?php echo $amount; ?> <s
                                                                                    class="original_price"><?php echo $original_amount; ?></s>
                                                                            </h5>
                                                                            <?php
                                                                            break;
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </li>
                                                                <li>

                                                                    <?php
                                                                    if ($sub_plan != null) {
                                                                        ?>
                                                                        <ul class="dropdown__select">
                                                                            <?php
                                                                            foreach ($get_data as $row_data) {
                                                                                $year = $row_data['year'];
                                                                                if ($year == '3 year') {
                                                                                    $year = '2 year + 1 year (Free)';
                                                                                } elseif ($year == '5 year') {
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
                                                                                } else {
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
                                                                                <li class="dropdown__select-option"
                                                                                    role="option"
                                                                                    data-year="<?php echo $row_data['year']; ?>">
                                                                                    <div class="prc-head-drp">

                                                                                        <h6><?php echo $year; ?></h6>
                                                                                        <h5><?php echo $currency_symbol . $new_amount; ?>
                                                                                            <s class="original_price"><?php echo $currency_symbol . $original_amount; ?></s>
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
                                                    <form method="post" name='razorpayform'
                                                          action="success-page-razor.php<?php if (isset($_GET['type']) && $_GET['type'] == 'android') {
                                                              echo '?type=android';
                                                          } ?>" id="form">
                                                        <table class="table table-borderless get_amount">
                                                            <tbody></tbody>
                                                        </table>
                                                        <div class="pt-10">
                                                            <button name="btn_submit" type="submit"
                                                                    class="btn btn-success pricing_btn form"></button>
                                                        </div>
                                                    </form>
                                                </div>
                                                <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>

    <?php
    if(isset($_SESSION['new_year'])){
    echo "var get_radio_value=".$_SESSION['new_year'].";";
}
else{
    echo "var get_radio_value='';";
}
 ?>


    // Change option selected
    const label = document.querySelector('.dropdown__filter-selected');
    const drp_options = Array.from(document.querySelectorAll('.dropdown__select-option'));
    const plan_amount = document.querySelector('input[name=drp_new_year]');
    drp_options.forEach(option=>{
        option.addEventListener('click', ()=> {
        label.innerHTML = option.innerHTML;
    plan_amount.value = option.getAttribute('data-year');
    get_value(option.getAttribute('data-year'));
    get_radio_value = option.getAttribute('data-year');
    });});

    // Close dropdown onclick outside
    document.addEventListener('click', e=> {
        const toggle = document.querySelector('.dropdown__switch');
    const element = e.target;

    if (element == toggle) return;

    const isDropdownChild = element.closest('.dropdown__filter');

    if (!isDropdownChild) {
        toggle.checked = false;
    }});
</script>
<!-- Scripts -->
<script src="assets/js/jquery-3.2.0.min.js"></script>
<script src="assets/js/jquery-ui.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<div class="modal fade" id="user_referral_code" role="dialog">
    <div class="modal-dialog cust-model-width">
        <div class="modal-content">
            <div class="modal-header cust-upi-madal">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Have a referral code?</h5>
            </div>
            <div class="modal-body">
                <div class="body">
                    <form method="POST" action="">
                        <input name="referal_code" placeholder="Enter referral code"
                               class="form-control referral_code">&nbsp;&nbsp;&nbsp;
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

<div class="modal fade" id="user_coupon_code" role="dialog">
    <div class="modal-dialog cust-model-width">
        <div class="modal-content">
            <div class="modal-header cust-upi-madal">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Have a coupon code?</h4>
            </div>
            <div class="modal-body">
                <div class="body">
                    <input name="coupon_code" placeholder="Enter coupon code" class="form-control">&nbsp;&nbsp;&nbsp;
                    <p class="coupon_msg"></p>

                    <div class="form-group">
                        <button class="btn btn-primary" type="button" onclick="user_coupon_code()">Apply Now
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>

    function getFormData() {
        <?php
        if ($user_country == '101') {
        echo "var gst_no = $('input[name=txt_gst_no]').val();";
        }else{
          echo "var gst_no = '';";
        }?>
        var address = $('textarea[name=txt_address]').val();
        var name = $('input[name=txt_name]').val();
        var company_name = $('input[name=company_name]').val();
        var dataString = "company_name=" + encodeURIComponent(company_name) + "&txt_name=" + encodeURIComponent(name) + "&txt_address=" + encodeURIComponent(address) + "&txt_gst_no=" + encodeURIComponent(gst_no);
        console.log(dataString);
        $.ajax({
            type: "POST",
            url: '<?php echo FULL_WEBSITE_URL; ?>get_radio_value.php', // Name of the php files
            data: dataString,
            success: function (html) {

            }
        });
    }

    <?php
    if (isset($_GET['type']) && $_GET['type'] =='android'){
        echo 'var mod_type = "&type=android";';
    }else{
        echo 'var mod_type = "&nothing=nothing";';
    } ?>
    function get_value(val) {
        var dataString = "radio_value=" + val + <?php echo '"&user_id=" + ' . $user_id; ?> +"<?php if (isset($android_url) && $android_url != "") echo "&android_user_id=".$_GET['android_user_id']."&type=".$_GET['type']; ?>";
        $.ajax({
            type: "POST",
            url: '<?php echo FULL_WEBSITE_URL; ?>get_radio_value.php', // Name of the php files
            data: dataString,
            dataType: "json",
            success: function (result) {
                $(".get_amount tbody").html(result.data);
            }
        });
    }

</script>
<script>
    function user_referral_code() {
        var refereal_code = $('.referral_code').val();
        var dataString = "refereal_code=" + refereal_code + <?php echo '"&user_id=" + ' . $user_id; ?>;
        var dataString1 = "check_code=" + refereal_code + <?php echo '"&user_id=" + ' . $user_id; ?>;
        var dataString2 = "check_refereal_code=" + refereal_code + <?php echo '"&user_id=" + ' . $user_id; ?>;
        $.ajax({
            type: "POST",
            url: '<?php echo FULL_WEBSITE_URL; ?>get_radio_value.php', // Name of the php files
            data: dataString + mod_type,
            success: function (html) {
                $(".code_msg").html(html);
                /*return false*/
                $(".hide_default").css("display", "none");

            }
        });
        $.ajax({
            type: "POST",
            url: '<?php echo FULL_WEBSITE_URL; ?>get_radio_value.php', // Name of the php files
            data: dataString1 + mod_type,
            success: function (html) {
                $(".extra_month").html(html);
                /*return false*/
            }
        });
        $.ajax({
            type: "POST",
            url: '<?php echo FULL_WEBSITE_URL; ?>get_radio_value.php', // Name of the php files
            data: dataString2 + mod_type,
            success: function (html) {
                $(".code_msg2").html(html);
                /*return false*/
            }
        });
    }
    function InvalidReferralCode() {
        var refereal_code = 'referetfr';
        var dataString = "refereal_code=" + refereal_code + <?php echo '"&user_id=" + ' . $user_id; ?>;
        var dataString1 = "check_code=" + refereal_code + <?php echo '"&user_id=" + ' . $user_id; ?>;
        $.ajax({
            type: "POST",
            url: '<?php echo FULL_WEBSITE_URL; ?>get_radio_value.php', // Name of the php files
            data: dataString + mod_type,
            success: function (html) {
                $(".code_msg").html(html);
                return false
            }
        });
        $.ajax({
            type: "POST",
            url: '<?php echo FULL_WEBSITE_URL; ?>get_radio_value.php', // Name of the php files
            data: dataString1 + mod_type,
            success: function (html) {
                $(".extra_month").html(html);
                return false
            }
        });
    }

</script>
<script>
    function user_dealer_code() {

        var dealer_code = $('.dealer_code').val();
        var dataString = "dealer_code=" + encodeURIComponent(dealer_code) + "&year=" + get_radio_value;
        var dataString1 = "check_dealer_code=" + encodeURIComponent(dealer_code);
        $.ajax({
            type: "POST",
            url: '<?php echo FULL_WEBSITE_URL; ?>get_radio_value.php', // Name of the php files
            data: dataString + mod_type,
            success: function (html) {
                $(".get_amount").html(html);
                /*return false*/
            }
        });
        $.ajax({
            type: "POST",
            url: '<?php echo FULL_WEBSITE_URL; ?>get_radio_value.php', // Name of the php files
            data: dataString1 + mod_type,
            success: function (html) {
                $(".code_msg1").html(html);
                /*return false*/
            }
        });
    }
    function InvalidDealerCode() {
        var dealer_code = 'jdhfkjghdskfhjg';
        var dataString = "dealer_code=" + dealer_code + "&year=" + get_radio_value;
        var dataString1 = "check_dealer_code=" + dealer_code;
        $.ajax({
            type: "POST",
            url: '<?php echo FULL_WEBSITE_URL; ?>get_radio_value.php', // Name of the php files
            data: dataString + mod_type,
            success: function (html) {
                $(".get_amount").html(html);
                /*return false*/
            }
        });
    }

</script>
<script>
    function user_coupon_code() {
        var coupon_code = $('input[name=coupon_code]').val();
        var dataString = "coupon_code=" + encodeURIComponent(coupon_code) + "&year=" + get_radio_value;
        var dataString1 = "check_coupon_code=" + encodeURIComponent(coupon_code);
        $.ajax({
            type: "POST",
            url: '<?php echo FULL_WEBSITE_URL; ?>get_radio_value.php', // Name of the php files
            data: dataString + mod_type,
            success: function (html) {
                $(".get_amount").html(html);
                /*return false*/
            }
        });
        $.ajax({
            type: "POST",
            url: '<?php echo FULL_WEBSITE_URL; ?>get_radio_value.php', // Name of the php files
            data: dataString1 + mod_type,
            success: function (html) {
                $(".coupon_msg").html(html);
                /*return false*/
            }
        });
    }

    function InvalidDealerCode() {
        var coupon_code = 'jdhfkjghdskfhjg';
        var dataString = "coupon_code=" + coupon_code + "&year=" + get_radio_value;
        var dataString1 = "check_coupon_code=" + coupon_code;
        $.ajax({
            type: "POST",
            url: '<?php echo FULL_WEBSITE_URL; ?>get_radio_value.php', // Name of the php files
            data: dataString + mod_type,
            success: function (html) {
                $(".get_amount").html(html);
                /*return false*/
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


</body>

</html>