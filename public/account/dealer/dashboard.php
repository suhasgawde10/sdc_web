<?php
error_reporting(1);
ob_start();
include "../controller/ManageDealer.php";
$manage = new ManageDealer();
include '../sendMail/sendMail.php';
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
if (!isset($_SESSION['dealer_email'])) {
    header('location:../dealer-register.php');
}
/*unset($_SESSION['create_user_status']);*/

/*Sewrvice*/

$error = false;
$errorMessage = "";

/*if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    $updateUserStatus = $manage->updateUserStatus($user_id);
    if ($updateUserStatus) {
        header('location:dashboard.php');
    }
}*/


/*if(isset($_GET['today'])){
    $today = $_GET['today'];
    $newCommerse = $manage->displayDailyUser();
    if ($newCommerse != null) {
        $countNewCommerce = mysqli_num_rows($newCommerse);
    } else {
        $countNewCommerce = 0;
    }
}
if(isset($_GET['month'])){
    $month = $_GET['month'];
    $newCommerse = $manage->displayMonthlyUser();
    if ($newCommerse != null) {
        $countNewCommerce = mysqli_num_rows($newCommerse);
    } else {
        $countNewCommerce = 0;
    }
}*/


/*$dailyUser = $manage0>dispa*/

$display_message = $manage->displayDealerProfile();
if ($display_message != null) {
    $message_status = $display_message['message_status'];
    $dealer_status = $display_message['status'];
    $pay_status = $display_message['pay_status'];
    $deal_code = $display_message['dealer_code'];
    $id = $display_message['id'];
    $wallet_amount = $display_message['wallet_amount'];
    $dealerPercent = $display_message['dealer_percent'];
}

$checkPercent = $manage->getDealerPricingById($dealerPercent);
if ($checkPercent != "") {
    $percentValue = $checkPercent['percentage'];
}


$activeCommerse = $manage->displayAllActiveUser($_SESSION['dealer_code']);
if ($activeCommerse != null) {
    $countActiveCommerce = mysqli_num_rows($activeCommerse);
} else {
    $countActiveCommerce = 0;
}

$newCommerse = $manage->displayAllInActiveUser($_SESSION['dealer_code']);
if ($newCommerse != null) {
    $countInActiveCommerce = mysqli_num_rows($newCommerse);
} else {
    $countInActiveCommerce = 0;
}

$date1 = date("Y-m-d");
$date = date_create("$date1");
date_add($date, date_interval_create_from_date_string("30 days"));
$final_date = date_format($date, "Y-m-d");
/*echo $final_date;
die();*/
$get_data = $manage->displayDealerRelatedUser($date1, $final_date);


if ($get_data != null) {
    $countRenawalListing = mysqli_num_rows($get_data);
} else {
    $countRenawalListing = 0;
}

if (isset($_POST['send_email'])) {

    if (isset($_POST['check']) && $_POST['check'] != "") {
        $checkbox = $_POST['check'];
        foreach ($checkbox as $key) {
            $displayUser = $manage->getSpecificUserProfileById($key);
            $email = $displayUser['email'];
            $contact_no = $displayUser['contact_no'];
            $name = $displayUser['name'];
            $user_start_date = $displayUser['user_start_date'];
            $expiry_date = $displayUser['expiry_date'];
            $earlier = new DateTime(date("Y-m-d"));
            $later = new DateTime("$expiry_date");
            if ($expiry_date < date("Y-m-d")) {
                $days = 0;
            } else {
                $days = $later->diff($earlier)->format("%a");
            }
            $name = $displayUser['name'];
            $email_message = '
<table style="width: 100%">
<tr>
<td colspan="2" style=' . $back_image . '>
<div style="' . $overlay . '">
<div style=" margin: 0 auto">
 <div class="user-name-logo" style="padding-top: 10px;">
                        <div style="text-align: center;color: #c4a758;width: 80px;margin: 1px auto;background: white;border-radius: 50%;height: 80px;text-align: center;padding: 5px;">
                            <img src="https://sharedigitalcard.com/assets/img/logo/logo.png" style="padding-top: 15px;width:100%">
                        </div>
                    </div>
                    <div style="text-align: center;color: white;font-weight: 700;padding-bottom: 10px;">
                        <h1 style="font-size: 24px;margin: 0;">Share Digital Card</h1>
                    </div>
</div>
</div>
</td>
</tr>
<tr>
<td colspan="2">
 <div style=" font-size: 18px;">
                    <p> Dear <span class="cust-name">' . ucwords($name) . '</span>,</p>

                    <p>
                        This email regarding the expiration of <span class="payment"> 5 days </span> trail period of
                        share digital.
                    </p>

                    <p>
                        Staring Date From <span class="se-date">' . $user_start_date . '</span> To Ending date <span
                            class="se-date">' . $expiry_date . '</span>.</p>
                    <p>
                    ' . $days . ' days remaining.
                    </p>
                    </div>
</td>
</tr>
<tr><td colspan="2" style="text-align:center">
<a href="http://sharedigitalcard.com/login.php" style="' . $btn . ';color:white; border-radius: 4px;"><img src="http://sharedigitalcard.com/user/assets/images/laptop.png" style="width: 19%;display: inline-block;vertical-align: middle;padding-right: 5px;color: white;">Click To Login</a>
                   <a target="_blank" href="https://play.google.com/store/apps/details?id=sharedigitalcard.com.digitalcard" style="padding: 0px;display: inline-block;vertical-align: middle;"><img src="https://sharedigitalcard.com/assets/img/playstore.png"
                                                                                          style="width: 135px" alt="digital card app"></a>
</td></tr>
<tr>
<td colspan="2" style=" font-size: 18px;">
<p> For any query email us on <a href="mailto:support@sharedigitalcard.com" class="payment">support@sharedigitalcard.com</a></p>
</td>
</tr>
<tr><td colspan="2" style="padding: 10px;background: #e6e6e6;height: 115px;">
 <div style="width: 85%;margin: 0 auto;">
                <div style=" width: 100%;margin: 0 auto;">
                    <div style="text-align:center">
                        <a href="https://www.facebook.com/sharedigitalcard/"><img src="http://sharedigitalcard.com/user/assets/images/fb.png"></a>
<a href="https://www.instagram.com/sharedigitalcard/"><img src="http://sharedigitalcard.com/user/assets/images/insta.png"></a>
<a href="https://www.youtube.com/watch?v=6T9Ia_2rsig&list=PLg1QyEHQ9MYYBRDxWqLrWLCyvJlSheqTh"><img src="http://sharedigitalcard.com/user/assets/images/yt.png"></a>
                    </div>
                </div>

            </div>
</td></tr>
</table>';
            $subject = "Dear " . $name . " Expiration of digital card";
            $toEmail = $email;
            $toName = $name;

            $message = $days . " days remaining of expiry of your digital card please click on below link to renew it.\n https://sharedigitalcard.com/user/plan-selection.php";
            $sendMail = $manage->sendMail($toName, $toEmail, $subject, $email_message);
            if ($sendMail) {
                $send_sms = $manage->sendSMS($contact_no, $message);
                if (!$send_sms) {
                    $error = true;
                    $errorMessage = "Issue while sending sms";
                } else {
                    $error = false;
                    $errorMessage = "email and sms has been sent";
                }
            } else {
                $error = true;
                $errorMessage = "Issue while sending email";
            }
        }
    } else {
        $error = true;
        $errorMessage = "Please select atleast one checkbox";
    }


}

$dayAmount = $manage->displayAmountOfDay();
$weekAmount = $manage->displayAmountOfWeek();
$monthAmount = $manage->displayAmountOfMonth();
$yearAmount = $manage->displayAmountOfYear();
$lifeTimeAmount = $manage->displayAmountOfLifetime();

$totalWalletAmount = $manage->displayDealerTotalWalletAmount($_SESSION['dealer_code']);
$completedWalletAmount = $manage->displayDealerWalletAmount($_SESSION['dealer_code'], "completed");
$pendingWalletAmount = $manage->displayDealerWalletAmount($_SESSION['dealer_code'], "pending");

?>


<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">

<head runat="server">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Dashboard</title>
    <?php include "assets/common-includes/header_includes.php" ?>
    <style>
        /* .card {
  width: 400px;
  margin: 0 auto;
  border: 1px solid #ccc;
  border-radius: 8px;
} */

        .card-header {
            background-color: #f8f9fa;
            padding: 10px;
            border-bottom: 1px solid #ccc;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .card-title {
            margin: 0;
            font-size: 20px;
        }

        .card-body {
            padding: 20px;
        }

        .card-text {
            margin-bottom: 15px;
        }

        .color-blue {
            color: blue;
        }
    </style>
</head>

<body>
    <?php include "assets/common-includes/header.php" ?>
    <?php include "assets/common-includes/left_menu.php" ?>
    <section class="content">
        <div class="container-fluid">

            <div class="block-header">
                <h2>DASHBOARD</h2>
            </div>
            <?php
            if(isset($_SESSION['dealer_type']) && $_SESSION['dealer_type']=="dealer"){
            ?>
            <div class="row clearfix">
                <div class="col-lg-9 col-xs-12">
                    <div class="row">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title"> Change in Plan and Pricing</h5>
                            </div>
                            <div class="card-body">
                                <h4 class="card-text color-blue">We would like to inform you that we have made changes
                                    to our plans
                                    and pricing</h4>
                                <h4 class="text-danger">Effective from <b>29th May 2023</b>. Please take note of the
                                    updated details:</h4>

                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Plan</th>
                                            <th>Price (Excluding GST)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Digital Card Plan (Yearly plan)</td>
                                            <td>Rs. 999</td>
                                        </tr>
                                        <tr>
                                            <td>80% Discount Plan</td>
                                            <td>Each card: Rs. 199.8 </td>
                                        </tr>
                                        <tr>
                                            <td>50% Discount Plan</td>
                                            <td>Each card: Rs. 499.5 </td>
                                        </tr>
                                        <tr>
                                            <td>Combo Offer (Digital Card + Website)</td>
                                            <td>Rs. 4,999 </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <br>
                                <h4 class="text-danger">Note: &nbsp;Please be informed that other digital card plans will no longer be available after
                                    29th May 2023. (for example: 3 years, 5 years and lifetime plan)</h4>
                                <a href="https://sharedigitalcard.com/pricing" target="_blank" class="btn btn-primary">Learn More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            }
            ?>



            <div class="row clearfix">
                <div class="col-lg-12 col-xs-12">
                    <div class="row">
                        <?php include 'assets/common-includes/shareUrl.php' ?>
                        <div class="col-lg-5 col-md-7 col-sm-6 col-xs-12">
                            <a href="view_all_customer.php?all_customer=<?php echo $security->encrypt($deal_code); ?>">
                                <div class="info-box bg-pink hover-expand-effect">
                                    <div class="icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="content">
                                        <div class="text">Active Customer</div>
                                        <div class="number">
                                            <?php if (isset($countActiveCommerce)) echo $countActiveCommerce; ?></div>
                                    </div>
                                    <div class="content">
                                        <div class="text">Inactive Customer</div>
                                        <div class="number">
                                            <?php if (isset($countInActiveCommerce)) echo $countInActiveCommerce; ?>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php
                    if (isset($_SESSION['dealer_type']) && $_SESSION['dealer_type'] == "dealer") {
                        ?>
                        <div class="col-lg-7 col-md-7 col-sm-6 col-xs-12">
                            <a
                                href="view-wallet-history.php?dealer_code=<?php echo $security->encryptWebservice($_SESSION['dealer_code']) ?>">
                                <div class="info-box bg-grey hover-expand-effect">
                                    <div class="icon">
                                        <i class="fas fa-credit-card"></i>
                                    </div>
                                    <div class="content">
                                        <div class="text">Total</div>
                                        <div class="number"><?php if ($totalWalletAmount != "") {
                                                echo number_format($totalWalletAmount, 2);
                                            } else {
                                                echo "0";
                                            } ?></div>
                                    </div>
                                    <div class="content">
                                        <div class="text">Pending</div>
                                        <div class="number"><?php if ($pendingWalletAmount != "") {
                                                echo number_format($pendingWalletAmount, 2);
                                            } else {
                                                echo "0";
                                            } ?></div>
                                    </div>
                                    <div class="content">
                                        <div class="text">Paid</div>
                                        <div class="number"><?php if ($completedWalletAmount != "") {
                                                echo number_format($completedWalletAmount, 2);
                                            } else {
                                                echo "0";
                                            } ?></div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php } ?>

                        <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
                            <div class="card">
                                <form method="post" action="">
                                    <div class="header">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <h2>
                                                    Renewal Listing <span class="badge"><?php
                                                    if (isset($countRenawalListing)) echo $countRenawalListing;
                                                    ?></span>
                                                </h2>
                                            </div>
                                        </div>
                                        <!--<div class="col-md-4 text-right">
                                        <div class="row">
                                            <button class="btn btn-success" type="submit" name="send_email">SEND EMAIL & SMS
                                            </button>
                                        </div>
                                    </div>-->
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
                                    <div class="body">
                                        <table id="dtHorizontalExample"
                                            class="table table-striped table-bordered table-sm " cellspacing="0"
                                            width="100%">
                                            <thead>
                                                <tr class="back-color">
                                                    <th><input type="checkbox" id="checkAll"></th>
                                                    <th>Name</th>
                                                    <th>Contact no</th>
                                                    <th>Plan</th>
                                                    <?php
                                            if (isset($_SESSION['dealer_type']) && $_SESSION['dealer_type'] == "dealer") {
                                                ?>
                                                    <th>Action</th>
                                                    <?php } ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                        if ($get_data != null) {
                                            while ($result_data = mysqli_fetch_array($get_data)) {
                                                $expiry_date = $result_data['expiry_date'];
                                                $user_id = $security->encrypt($result_data['user_id']);
                                                $email = $result_data['email'];
                                                $contact_no = $result_data['contact_no'];
                                                $gender = $result_data['gender'];
                                                $password = rtrim($result_data['password'], "8523");
                                                $profilePath = "../user/uploads/" . $result_data['email'] . "/profile/" . $result_data['img_name'];
                                                $end_date = $result_data['expiry_date'];
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
                                                ?>
                                                <tr>
                                                    <td><input type="checkbox" id="checkItem1" name="check[]"
                                                            value="<?php echo $result_data["user_id"]; ?>"></td>
                                                    <td>
                                                        <a href="view_customer.php?user_id=<?php echo $user_id; ?>"
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
                                                                    echo "<br>" . $result_data['designation'];
                                                                }
                                                                if ($result_data['company_name'] != "") {
                                                                    echo "<br>" . $result_data['company_name'];
                                                                }

                                                                echo "<br><label class='label label-success'>" . $manage->getProfilePercent($result_data['id']) . "%</label>";

                                                                ?>
                                                            </div>
                                                        </a>
                                                    </td>
                                                    <td><?php echo $result_data['contact_no']; ?></td>
                                                    <td><?php if ($result_data['user_start_date'] != "") {
                                                            echo "Reg. : " . $result_data['user_start_date'] . "<br>";
                                                        }
                                                        echo 'Plan : ' . $result_data['year'] . '<br>days : <label class="label label-success">' . $diff . '</label>'; ?>
                                                    </td>
                                                    <?php
                                                    if (isset($_SESSION['dealer_type']) && $_SESSION['dealer_type'] == "dealer") {
                                                        if ($percentValue == '100') {
                                                            ?>
                                                    <td>
                                                        <a class="btn btn-success"
                                                            href="plan-selection_last.php?user_id=<?php echo $user_id ?>">
                                                            <i class="fa fa-money"
                                                                aria-hidden="true"></i>&nbsp;&nbsp;Upgrade
                                                            Plan</a>
                                                    </td>
                                                    <?php
                                                        } else {
                                                            ?>
                                                    <td>
                                                        <a class="btn btn-success"
                                                            href="plan-selection.php?user_id=<?php echo $user_id ?>">
                                                            <i class="fa fa-money"
                                                                aria-hidden="true"></i>&nbsp;&nbsp;Upgrade
                                                            Plan</a>
                                                    </td>
                                                    <?php
                                                        }
                                                    } ?>
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
                                        </table>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <?php
                    if (isset($_SESSION['dealer_type']) && $_SESSION['dealer_type'] == "dealer") {
                        ?>
                        <div class="col-lg-3 col-xs-12">
                            <div class="row margin_div_web">
                                <div class="card">
                                    <div class="body">
                                        <form method="post" action="">

                                            <div>
                                                <h5 class="text-center">Income Statistics</h5>
                                            </div>
                                            <table class="table table-borderless get_amount">
                                                <tr>
                                                    <td>Today</td>
                                                    <td class="text-right"><label class="label label-success"><?php if ($dayAmount['total_amount'] == null) {
                                                                echo "0";
                                                            } else {
                                                                echo number_format($dayAmount['total_amount'], 2);
                                                            } ?></label></td>
                                                </tr>
                                                <tr>
                                                    <td>Week</td>
                                                    <td class="text-right"><label
                                                            class="label label-success"><?php if ($weekAmount != null) echo number_format($weekAmount['total_amount'], 2); ?></label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Month</td>
                                                    <td class="text-right"><label class="label label-success"><?php
                                                            if ($monthAmount != null) echo number_format($monthAmount['total_amount'], 2);
                                                            ?></label></td>
                                                </tr>
                                                <tr>
                                                    <td>Year</td>
                                                    <td class="text-right"><label class="label label-success"><?php
                                                            if ($yearAmount != null) echo number_format($yearAmount['total_amount'], 2);
                                                            ?></label></td>
                                                </tr>
                                                <tr>
                                                    <td>Life-Time</td>
                                                    <td class="text-right"><label class="label label-success"><?php
                                                            if ($lifeTimeAmount != null) echo number_format($lifeTimeAmount['total_amount'], 2);
                                                            ?></label></td>
                                                </tr>

                                            </table>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>


            </div>
        </div>


    </section>

    <?php include "assets/common-includes/footer_includes.php" ?>
    <script>
        $("#checkAll").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
    </script>
</body>

</html>