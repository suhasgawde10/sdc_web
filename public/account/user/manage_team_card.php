<?php


include "../controller/ManageUser.php";

$manage = new ManageUser();

include "../controller/validator.php";
$validate = new Validator();
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
/*include '../sendMail/sendMail.php';*/
$error = false;
$errorMessage = "";
$error1 = false;
$errorMessage1 = "";
include("android-login.php");
$random = rand(100, 10000);
$random_password = rand(1000, 10000);
include("session_includes.php");
include("validate-page.php");

if(isset($_POST['upgrade-credit'])){

    if(isset($_POST['btn_user']) && $_POST['btn_user'] !=""){
        $user_id = $_POST['btn_user'];
    }else{
        $error = true;
        $errorMessage = "Please Select User";
    }

    if(isset($_POST['txt_year']) && $_POST['txt_year'] !=""){
        $txt_year = $_POST['txt_year'];
    }else{
        $error = true;
        $errorMessage = "Please Select Plan";
    }
    if(!$error){
        $total_credit = $manage->getUserCreditByYear($txt_year);

        if($total_credit !=null && $total_credit['credit_qty'] > 0) {
            if ($txt_year == "1 year") {
                $month = 12;
            } else if ($txt_year == "3 year") {
                $month = 36;
            } else if ($txt_year == "5 year") {
                $month = 60;
            } else {
                $month = "";
            }
            $getUserDetail = $manage->getUserProfile($user_id);
            if($getUserDetail !=null){
                $user_expiry_date = $getUserDetail['expiry_date'];
            }else{
                $user_expiry_date = null;
            }
            $date = date("Y-m-d");

            if (($user_expiry_date != null OR $user_expiry_date != "0000-00-00") && $user_expiry_date >= $date && $month !="") {
                $expiry_date_in_time = strtotime("+" . $month . " months", strtotime($user_expiry_date));
                $expiry_date = date("Y-m-d", $expiry_date_in_time);
            } elseif (($user_expiry_date != null OR $user_expiry_date != "0000-00-00") && $user_expiry_date <= $date && $month !="") {
                $expiry_date_in_time = strtotime("+" . $month . " months", strtotime($date));
                $expiry_date = date("Y-m-d", $expiry_date_in_time);
            } elseif(($user_expiry_date == null OR $user_expiry_date == "0000-00-00") && $month !="") {
                $expiry_date_in_time = strtotime("+" . $month . " months", strtotime($date));
                $expiry_date = date("Y-m-d", $expiry_date_in_time);
            }else{
                $expiry_date = "";
            }
            $updateUserExpiry = $manage->updateUserExpiryOfChild($user_id,$expiry_date);
            if($updateUserExpiry){
                $remain_credit = $total_credit['credit_qty'] - 1;
                $update_credit = $manage->updateUserCredit($remain_credit,$total_credit['id']);
                $error = false;
                $errorMessage = "User Plan Upgraded Successfully!.";
            }
        }
    }

}

$displayUser = $manage->displayParentUserDetails($id);
if ($displayUser != null) {
    $countUser = mysqli_num_rows($displayUser);
} else {
    $countUser = 0;
}
$maxsize = 10485760;

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

require_once("functions.php");
$customerId = 4444;
$orderDescription = "Plan"; //your script should substitute detailed description of your order here ( This field is not mandatory )
$country = "IN";//your script should substitute the customer's country code
$TMPL_CURRENCY = "INR";//your script should substitute the currency symbol in which you want to display amount
$currency = "INR";//your script should substitute the currency symbol in which you want to display amount
$state = "";//your script should substitute the customer's state
$postcode = "";//your script should substitute the customer's zip
$telnocc = "091";//your script should substitute the customer's contry code for tel no
$ip = "127.0.0.1"; // your script should replace it with your ip address
$reservedField1 = ""; //As of now this field is reserved and you need not put anything
$reservedField2 = ""; //As of now this field is reserved and you need not put anything
$terminalid = "";   //terminalid if provided
$paymentMode = ""; //payment type as applicable Credit Cards = CC, Vouchers = PV,  Ewallet = EW, NetBanking = NB
$paymentBrand = ""; //card type as applicable Visa = VISA; MasterCard=MC; Dinners= DINER; Amex= AMEX; Disc= DISC; CUP=CUP


/*$processUrl = "https://sandbox.paymentz.com/transaction/Checkout";*/
$processUrl = "https://secure.paymentz.in/transaction/Checkout";
$liveurl = "https://secure.live.com/transaction/PayProcessController";


if (isset($_GET['publishData']) && (isset($_GET['action']))) {
    $action = $_GET['action'];
    $id = $security->decrypt($_GET['publishData']);
    if ($action == "Unblock") {
        $result = $manage->publishUnpublish($id, 1, $manage->profileTable);
    } else {
        $result = $manage->publishUnpublish($id, 0, $manage->profileTable);
    }
    header('location:manage_team_card.php');
}


function GenerateAPIKey()
{
    $key = implode('-', str_split(substr(strtolower(md5(microtime() . rand(1000, 9999))), 0, 30), 6));
    return $key;
}

require('../controller/razorpay-php/Razorpay.php');

use Razorpay\Api\Api;

$api = new Api($keyId, $keySecret);

$api_key = GenerateAPIKey();

if (isset($_POST['cancel_button'])) {
    if (isset($_GET['page_no']) && $_GET['page_no'] != "") {
        header('location:user-management.php?page_no=' . $_GET['page_no']);
    } else {
        header('location:user-management.php');
    }

}



$getCredit = $manage->getUserCredit();
if ($getCredit != null) {
    $one_year_credit = $getCredit['one_year_credit'];
    if ($one_year_credit == null) {
        $one_year_credit = 0;
    }
    $three_year_credit = $getCredit['three_year_credit'];
    if ($three_year_credit == null) {
        $three_year_credit = 0;
    }
    $five_year_credit = $getCredit['five_year_credit'];
    if ($five_year_credit == null) {
        $five_year_credit = 0;
    }
    $life_time_credit = $getCredit['life_time_credit'];
    if ($life_time_credit == null) {
        $life_time_credit = 0;
    }
} else {
    $one_year_credit = 0;
    $three_year_credit = 0;
    $five_year_credit = 0;
    $life_time_credit = 0;
}


function addUrlParam($array)
{

    $url = $_SERVER['REQUEST_URI'];
    $val = "";
    if ($array != "") {
        foreach ($array as $name => $value) {
            if ($val != "") {
                $val .= "&" . $name . '=' . urlencode($value);
            } else {
                $val .= $name . '=' . urlencode($value);
            }
        }
    }
    if (strpos($url, '?') !== false) {
        $url .= '&' . $val;
    } else {
        $url .= '?' . $val;
    }
    return $url;
}


$today_date = date('Y-m-d');

function fetch_all_data($result)
{
    $all = array();
    while($thing = mysqli_fetch_array($result)) {
        $all[] = $thing;
    }
    return $all;
}
?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">

<head runat="server">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Manage Team Card</title>
    <?php include "assets/common-includes/header_includes.php" ?>
    <style>
        .header {
            padding: 10px;
        }

        .truncated_text {
            text-overflow: ellipsis;
            width: 150px;
            white-space: nowrap;
            overflow: hidden;
            padding: 0px;
            margin: 0px;
        }

        /* .truncated_text:hover {
             text-overflow: clip;
             width: auto;
             white-space: normal;
         }*/
        .info-box {
            height: auto;
            overflow: auto;
        }

        .info-box .content {
            display: inline-block;
            padding-bottom: 10px;
        }
    </style>

</head>

<body>
    <?php include "assets/common-includes/header.php" ?>
    <?php include "assets/common-includes/left_menu.php" ?>
    <section class="content">
        <?php
    if (isset($_SESSION['create_user_status']) && $_SESSION['create_user_status'] == true) {
        include "assets/common-includes/session_button_includes.php";
    }
    ?>
        <div class="clearfix">
            <div id="projectFacts" class="sectionClass col-lg-12">
                <div class="fullWidth eight columns">

                    <div class="projectFactsWrap">

                        <div class="item " data-number="">
                            <!-- <div class="counter-icon customr_count_img">
                            <img src="../assets/img/website-counter/customer.png" alt="Online visiting card maker">
                        </div>-->
                            <div class="customer_count">
                                <p id="number1" class="number">
                                    <?php if (isset($one_year_credit)) echo $one_year_credit; ?></p>
                                <span></span>

                                <p>1 Year Credit</p>
                                <?php
                            if ($one_year_credit > 0) {
                            if($countUser < 0){
                                echo  '<a class="btn btn-success mt-10" href="add_team_card.php?add='.$security->encryptWebservice('1 year').'">Redeem Now</a>';
                            }else {
                                ?>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-info"
                                        onclick="location.href='add_team_card.php?add=<?php echo $security->encryptWebservice('1 year') ?>'"><i
                                            class="fa fa-address-card-o" aria-hidden="true"></i> Create New</button>
                                    <button type="button" class="btn btn-warning" data-toggle="modal"
                                        data-target="#myModalUpgrade" onclick="upgradeCreditOfUser('1 year')"><i
                                            class="fa fa-user-plus" aria-hidden="true"></i> Upgrade Existing</button>
                                </div>
                                <?php
                                 if (like_match('%dealer%', $referral_by) != 1) {
                                ?>
                                <button class="btn btn-success mt-10" data-toggle="modal" data-target="#myModal"
                                    onclick="upgradeCredit('1 year')"><i class="fa fa-plus-circle"
                                        aria-hidden="true"></i> Add More Credit
                                </button>
                                <?php
                                }
                                ?>
                                <?php
                            }
                            } else {

                                if (like_match('%dealer%', $referral_by) != 1) {
                                    ?>
                                <button class="btn btn-success mt-10" data-toggle="modal" data-target="#myModal"
                                    onclick="upgradeCredit('1 year')"><i class="fa fa-credit-card"
                                        aria-hidden="true"></i> Purchase Credit
                                </button>
                                <?php
                                }
                            }
                            ?>

                            </div>
                        </div>

                        <div class="item " data-number="55">
                            <!--   <div class="counter-icon customr_count_img">
                            <img src="../assets/img/website-counter/city.png" alt="smart digital business card">
                        </div>-->
                            <div class="customer_count">
                                <p id="number2" class="number">
                                    <?php if (isset($three_year_credit)) echo $three_year_credit; ?></p>
                                <span></span>

                                <p>3 Years Credit</p>
                                <?php
                            if ($three_year_credit > 0) {
                                if($countUser < 0){
                                    echo  '<a class="btn btn-success mt-10" href="add_team_card.php?add='.$security->encryptWebservice('3 year').'">Redeem Now</a>';
                                }else {
                                    ?>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-info"
                                        onclick="location.href='add_team_card.php?add=<?php echo $security->encryptWebservice('3 year') ?>'"><i
                                            class="fa fa-address-card-o" aria-hidden="true"></i> Create New</button>
                                    <button type="button" class="btn btn-warning" data-toggle="modal"
                                        data-target="#myModalUpgrade" onclick="upgradeCreditOfUser('3 year')"><i
                                            class="fa fa-user-plus" aria-hidden="true"></i> Upgrade Existing</button>
                                </div>
                                <?php
                                 if (like_match('%dealer%', $referral_by) != 1) {
                                ?>
                                <button class="btn btn-success mt-10" data-toggle="modal" data-target="#myModal"
                                    onclick="upgradeCredit('3 year')"><i class="fa fa-plus-circle"
                                        aria-hidden="true"></i> Add More Credit
                                </button>
                                <?php
                                 }
                                 ?>
                                <?php
                                }
                            } else {
                                if (like_match('%dealer%', $referral_by) != 1) {
                                    ?>
                                <button class="btn btn-success mt-10" data-toggle="modal" data-target="#myModal"
                                    onclick="upgradeCredit('3 year')"><i class="fa fa-credit-card"
                                        aria-hidden="true"></i> Purchase Credit
                                </button>
                                <?php
                                }
                            }
                            ?>
                            </div>
                        </div>
                        <div class="item " data-number="359">
                            <!-- <div class="counter-icon customr_count_img">
                            <img src="../assets/img/website-counter/artist.svg" alt="verified and secure business card">
                        </div>-->

                            <div class="customer_count">
                                <p id="number3" class="number"><?php echo $five_year_credit; ?></p>
                                <span></span>

                                <p>5 Years Credit</p>
                                <?php
                            if ($five_year_credit > 0) {
                                if($countUser < 0){
                                    echo  '<a class="btn btn-success mt-10" href="add_team_card.php?add='.$security->encryptWebservice('5 year').'">Redeem Now</a>';
                                }else {
                                    ?>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-info"
                                        onclick="location.href='add_team_card.php?add=<?php echo $security->encryptWebservice('5 year') ?>'"><i
                                            class="fa fa-address-card-o" aria-hidden="true"></i> Create New</button>
                                    <button type="button" class="btn btn-warning" data-toggle="modal"
                                        data-target="#myModalUpgrade" onclick="upgradeCreditOfUser('5 year')"><i
                                            class="fa fa-user-plus" aria-hidden="true"></i> Upgrade Existing</button>
                                </div>
                                <?php
                                 if (like_match('%dealer%', $referral_by) != 1) {
                                ?>
                                <button class="btn btn-success mt-10" data-toggle="modal" data-target="#myModal"
                                    onclick="upgradeCredit('5 year')"><i class="fa fa-plus-circle"
                                        aria-hidden="true"></i> Add More Credit
                                </button>
                                <?php
                                } ?>
                                <?php
                                }
                            } else {

                                if (like_match('%dealer%', $referral_by) != 1) {
                                    ?>
                                <button class="btn btn-success mt-10" data-toggle="modal" data-target="#myModal"
                                    onclick="upgradeCredit('5 year')"><i class="fa fa-credit-card"
                                        aria-hidden="true"></i> Purchase Credit
                                </button>
                                <?php
                                }
                            }
                            ?>
                            </div>
                        </div>
                        <div class="item " data-number="<?php echo $total_dealer_count ?>">
                            <!--   <div class="counter-icon customr_count_img">
                            <img src="../assets/img/website-counter/handshake.svg" alt="digital card free demo">
                        </div>-->
                            <div class="customer_count">

                                <p id="number4" class="number"><?php echo $life_time_credit; ?></p>
                                <span></span>

                                <p>Life Time Credit</p>
                                <?php
                            if ($life_time_credit > 0) {
                                if($countUser < 0){
                                    echo  '<a class="btn btn-success mt-10" href="add_team_card.php?add='.$security->encryptWebservice('Life Time').'">Redeem Now</a>';
                                }else {
                                    ?>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-info"
                                        onclick="location.href='add_team_card.php?add=<?php echo $security->encryptWebservice('Life Time') ?>'"><i
                                            class="fa fa-address-card-o" aria-hidden="true"></i> Create New</button>
                                    <button type="button" class="btn btn-warning" data-toggle="modal"
                                        data-target="#myModalUpgrade" onclick="upgradeCreditOfUser('Life Time')"><i
                                            class="fa fa-user-plus" aria-hidden="true"></i> Upgrade Existing</button>
                                </div>
                                <?php
                                 if (like_match('%dealer%', $referral_by) != 1) {
                                ?>
                                <button class="btn btn-success mt-10" data-toggle="modal" data-target="#myModal"
                                    onclick="upgradeCredit('Life Time')"><i class="fa fa-plus-circle"
                                        aria-hidden="true"></i> Add More Credit
                                </button>
                                <?php
                                }
                            ?>
                                <?php
                                }
                            } else {
                                if (like_match('%dealer%', $referral_by) != 1) {
                                    ?>
                                <button class="btn btn-success mt-10" data-toggle="modal" data-target="#myModal"
                                    onclick="upgradeCredit('Life Time')"><i class="fa fa-credit-card"
                                        aria-hidden="true"></i> Purchase Credit
                                </button>
                                <?php
                                }
                            }
                            ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div class="col-md-12">
                            <div class="row">
                                <h4>
                                    Manage Team Card <span class="badge"><?php
                                    if (isset($countUser)) echo $countUser;
                                    ?></span>
                                </h4>
                            </div>
                            <div id="snackbar">URL is on the clipboard, try to paste it!</div>
                        </div>

                        <!-- <div class="col-md-3 text-right">
                        <a class="btn btn-primary open_digi" href="add_team_card.php">Add New Card
                        </a>
                    </div>-->

                    </div>
                    <div class="body">
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

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-sm" cellspacing="0"
                                id="dtHorizontalVerticalExample" width="100%">
                                <thead>
                                    <tr class="back-color">
                                        <th>User</th>
                                        <th>Email/Contact</th>
                                        <th>Start/End Date</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                            if ($displayUser != null) {
                                $i = 10;
                                $result_status = fetch_all_data($displayUser);
                                foreach ($result_status as $result_data){
                                    $link = SHARED_URL.$result_data['custom_url'];
                                    $end_date = $result_data['expiry_date'];
                                    $password = rtrim($result_data['password'], "8523");
                                    $date = date("Y-m-d");
                                    $earlier = new DateTime("$date");
                                    $later = new DateTime("$end_date");

                                    $five_day = date('Y-m-d', strtotime(date_create("Y-m-d") . ' + 5 days'));
                                    if ($result_data['year'] != 'Life Time') {
                                        if ($end_date === $date) {
                                            $diff = "1";
                                        } elseif ($end_date < $date) {
                                            $diff = "Expired";
                                        } else {
                                            $diff = $later->diff($earlier)->format("%a");
                                        }
                                    } else {
                                        $diff = "Life Time";
                                    }

                                    $custom_url = $result_data['custom_url'];
                                    $gender = $result_data['gender'];
                                    $profilePath = "uploads/" . $session_email. "/" . $result_data['email'] . "/profile/" . $result_data['img_name'];
                                    ?>
                                    <!--                                    --><?php //if ($result_data['status'] == '0') {
//                                        echo 'background-color: #f96b6b';
//                                    } elseif ($diff == 0) {
//                                        echo 'background-color: #fdc8ce';
//                                    } elseif ($end_date <= $five_day) {
//                                        echo 'background-color: #f9eabf;';
//                                    } ?>
                                    <tr style="">

                                        <td>
                                            <a href="<?php echo SHARED_URL.$result_data['custom_url'] ?>"
                                                target="_blank">
                                                <div style="display: inline-block;vertical-align: top">
                                                    <img src="<?php if (!file_exists($profilePath) && $gender == "Male" or $result_data['img_name'] == "") {
                                                                echo "uploads/male_user.png";
                                                            } elseif (!file_exists($profilePath) && $gender == "Female" or $result_data['img_name'] == "") {
                                                                echo "uploads/female_user.png";
                                                            } else {
                                                                echo $profilePath;
                                                            } ?>" class="user_profile_image">
                                                </div>
                                                <div style="display: inline-block;">
                                                    <?php
                                                    echo $result_data['name'];
                                                    if ($result_data['designation'] != "") {
                                                        echo "<p class='truncated_text' title='" . $result_data['designation'] . "'>" . $result_data['designation'] . "</p>";
                                                    }
                                                    echo $result_data['created_date'];
                                                    ?>
                                                </div>
                                            </a>
                                        </td>

                                        <td><?php echo $result_data['email'] . "<br>" . $result_data['contact_no']; ?>
                                        </td>
                                        <td><?php

                                            echo  date('d-M-Y',strtotime($result_data['created_date'])) ." / " . date('d-M-Y',strtotime($end_date));
                                            ?></td>


                                        <td>
                                            <ul class="header-dropdown">
                                                <li class="dropdown dropdown-inner-table">
                                                    <a href="javascript:void(0);" class="dropdown-toggle"
                                                        data-toggle="dropdown" role="button" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        <i class="material-icons">more_vert</i>
                                                    </a>
                                                    <ul class="dropdown-menu pull-right">
                                                        <li>
                                                            <a
                                                                href="add_team_card.php?display_data=<?php echo $security->encryptWebservice($result_data['user_id']); ?>&action=edit"><i
                                                                    class="fas fa-edit"></i>&nbsp;&nbspEdit</a>
                                                        </li>
                                                        <li>
                                                            <a onclick="setClipboard('<?php echo $link; ?>')"><i
                                                                    class="fas fa-copy"></i>&nbsp;&nbsp;Copy URL</a>
                                                        </li>
                                                        <li>
                                                            <a onclick="return confirm('Are You sure you want to <?php echo $result_data['status'] == 1 ? 'Block' : 'Unblock'; ?>?');"
                                                                href="manage_team_card.php?publishData=<?php echo $security->encrypt($result_data['user_id']) ?>&action=<?php echo $result_data['status'] == 1 ? "Block" : "Unblock"; ?>"
                                                                class="<?php echo $result_data['status'] == 0 ? "fa fa-unlock" : "fa fa-ban"; ?>">
                                                                &nbsp;&nbsp;<?php echo $result_data['status'] == 1 ? "Block" : "Unblock"; ?></a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                    <?php
                                    $i++;
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
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog cust-model-width">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header cust-upi-madal">
                    <button type="button" class="close" data-dismiss="modal" id="closeTheModal">&times;</button>
                    <h4 class="modal-title">Get <span id="select_year"></span> credit</h4>
                </div>
                <div class="modal-body padding_zero_model">


                    <div class="body">
                        <form method="post" name='razorpayform'
                            action="credit-success-page.php<?php if(isset($_GET['type']) && $_GET['type']=='android'){ echo '?type=android'; } ?>"
                            id="form">
                            <div class="get_amount">

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
                            <input type="hidden" name="street" value="<?php echo substr($street,0,99); ?>">
                            <input type="hidden" name="postcode" value="<?php echo $postcode; ?>">
                            <input type="hidden" name="phone" value="<?php echo $_SESSION['contact']; ?>">
                            <input type="hidden" name="telnocc" value="<?php echo $telnocc; ?>">
                            <input type="hidden" name="email" value="<?php echo $_SESSION['email']; ?>">
                            <input type="hidden" name="terminalid" value="<?php echo $terminalid; ?>">
                            <input type="hidden" name="paymentMode" value="<?php echo $paymentMode; ?>">
                            <input type="hidden" name="paymentBrand" value="<?php echo $paymentBrand; ?>">
                            <input type="hidden" name="customerId" value="<?php echo $customerId; ?>">
                        </form>
                        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModalUpgrade" role="dialog">
        <div class="modal-dialog cust-model-width">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header cust-upi-madal">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Upgrade <span class="select_year_exist"></span> credit</h4>
                </div>
                <div class="modal-body padding_zero_model">


                    <div class="body">
                        <form method="post" action="" enctype="multipart/form-data">
                            <table class="table table-borderless">
                                <tr>
                                    <td colspan="2">
                                        <div class="width-prf">
                                            <label>Select User </label> <span class="required_field">*</span>

                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <select class="form-control" name="btn_user" required>
                                                        <option value="">Select User</option>
                                                        <?php
                                                foreach ($result_status as $result_data) {
                                                    ?>
                                                        <option value="<?php echo $result_data['user_id']; ?>">
                                                            <?php echo $result_data['name'] . "<br>" . $result_data['contact_no']; ?>
                                                        </option>
                                                        <?php
                                                }
                                                ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="width-prf">
                                            <label>Redeem Credit </label>

                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    One.
                                                </div>
                                            </div>

                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">

                                        <div class="width-prf">
                                            <input type="hidden" name="txt_year">
                                            <button class="btn btn-primary form-control" name="upgrade-credit"
                                                type="submit">Upgrade Plan</button>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include "assets/common-includes/footer_includes.php" ?>

    <script>
        function upgradeCreditOfUser(text) {
            $('.select_year_exist').text(text);
            $('input[name=txt_year]').val(text);
        }

        function upgradeCredit(text) {
            $('#select_year').text(text);

            var dataString = "radio_value=" + text + "&quantity=1" + "<?php if ($android_url != "
            ") echo " & android_user_id = " . $_GET['android_user_id'] . " & type = " . $_GET['type']; ?>";
            $.ajax({
                type: "POST",
                url: "credit-ajax.php", // Name of the php files
                data: dataString,
                dataType: "json",
                success: function (result) {
                    /* $('.payzor_row').load('.payzor_section');
                     $('.payzor_section').load('.payzor_row');*/

                    //$('.payzor_row').load('');

                    $(".get_amount").html(result.data);

                    $('#razor_script').attr("data-amount", result.pay_amount);
                    //$('.payzor_section').load('plan-selection.php?amount='++' .payzor_section');
                    //    console.log(result.data2);
                    //$(".pamentz_row").html(result.data2);


                    <
                    ?
                    php
                    if (like_match('%ref%', $referer_code) == 1) {
                        ?
                        >
                        default_user_referral_code(); <
                        ?
                        php
                    } ? >
                }
            });

        }

        function upgradeCreditByQuantity(text, val) {
            if (text.trim() != '' && val > 0) {
                var dataString = "radio_value=" + text + "&quantity=" + val + "<?php if ($android_url != "
                ") echo " & android_user_id = " . $_GET['android_user_id'] . " & type = " . $_GET['type']; ?>";

                console.log(dataString);
                $.ajax({
                    type: "POST",
                    url: "credit-ajax.php", // Name of the php files
                    data: dataString,
                    dataType: "json",
                    success: function (result) {
                        /* $('.payzor_row').load('.payzor_section');
                         $('.payzor_section').load('.payzor_row');*/

                        //$('.payzor_row').load('');

                        $(".get_amount").html(result.data);

                        $('#razor_script').attr("data-amount", result.pay_amount);
                        //$('.payzor_section').load('plan-selection.php?amount='++' .payzor_section');
                        //    console.log(result.data2);
                        //$(".pamentz_row").html(result.data2);


                        <
                        ?
                        php
                        if (like_match('%ref%', $referer_code) == 1) {
                            ?
                            >
                            default_user_referral_code(); <
                            ?
                            php
                        } ? >
                    }
                });
            }


        }
    </script>
    <?php
if ($error && $errorMessage != "") {
    ?>
    <script>
        $('.open_digi').click();
    </script>
    <?php
}
?>
    <script>
        $("#checkAl").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
        $(".user_amount").click(function () {
            var $row = $(this).closest("tr"); // Find the row
            var amt = $row.find(".nr").text(); // Find the text

        });
        $(document).ready(function () {

            $(".checkbox1").change(function () {
                //Create an Array.
                var selected = new Array();
                $('input[type="checkbox"]:checked').each(function () {
                    selected.push(this.value);
                });
                if (selected.length > 0) {
                    $('.txt_id').val(selected.join(","));
                    $('.extra_day').val(selected.join(","));
                }

            });

        });
    </script>
</body>

</html>