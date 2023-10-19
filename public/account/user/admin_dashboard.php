<?php
ob_start();

error_reporting(0);

include_once "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include "../controller/ManageUser.php";
$manage = new ManageUser();
include '../sendMail/sendMail.php';

$error = false;
$errorMessage = "";

if (!isset($_SESSION['email'])) {
    header('location:../login.php');
} elseif (isset($_SESSION['email']) && isset($_SESSION['type']) && $_SESSION['type'] == 'User') {
    header('location:../login.php');
}
/*unset($_SESSION['create_user_status']);*/

if (isset($_GET['delete_data'])) {
    $delete_data = $security->decrypt($_GET['delete_data']);
    $status = $manage->masterDelete($delete_data,"tb_new_user_log");
    header('location:admin_dashboard.php');
}
$newCommerse = $manage->displayNewSubscriptionDetails();
if ($newCommerse != null) {
    $countNewCommerce = mysqli_num_rows($newCommerse);
} else {
    $countNewCommerce = 0;
}


$remainingUser = $manage->displayDefaultUser();
if ($remainingUser != null) {
    $newCustomer = mysqli_num_rows($remainingUser);
} else {
    $newCustomer = 0;
}


if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    $updateUserStatus = $manage->updateUserStatus($user_id);
    /*echo "here";
    die();*/
    if ($updateUserStatus) {
        header('location:dashboard.php');
    }
}
$active_customer = $manage->countAllActiveUserWithoutTrial();

/*$display_message = $manage->displayDealerProfile();
if($display_message!=null){
    $message_status = $display_message['message_status'];
    $status = $display_message['status'];
}*/


$date1 = date("Y-m-d");
$date = date_create("$date1");
date_add($date, date_interval_create_from_date_string("30 days"));
$final_date = date_format($date, "Y-m-d");

/*echo $final_date;
die();*/
$get_data = $manage->displayRelatedUser($date1, $final_date);

if ($get_data != null) {
    $countRenawalListing = mysqli_num_rows($get_data);
} else {
    $countRenawalListing = 0;
}
$displayUser = $manage->displayAllUser();
if ($displayUser != null) {
    $countUser = mysqli_num_rows($displayUser);
} else {
    $countUser = 0;
}
if (isset($_SESSION['type']) && $_SESSION['type'] == "Admin") {
    $approveUser = $manage->displayApproveDealer();
    if ($approveUser != null) {
        $countDealer = mysqli_num_rows($approveUser);
    } else {
        $countDealer = 0;
    }

    $dayAmount = $manage->displayAmountOfDay();
    $weekAmount = $manage->displayAmountOfWeek();
    $monthAmount = $manage->displayAmountOfMonth();
    $yearAmount = $manage->displayAmountOfYear();
    $lifeTimeAmount = $manage->displayAmountOfLifetime();

    $pendingDealer = $manage->displayPendingDealer();
}
if ($pendingDealer != null) {
    $pendingDealerCount = mysqli_num_rows($pendingDealer);
} else {
    $pendingDealerCount = 0;
}


if (isset($_POST['send_email'])) {
    $checkbox = $_POST['check'];
    if ($checkbox != "") {
        foreach ($checkbox as $key) {
            $displayUser = $manage->displayAllUserByID($key);
            $email = $displayUser['email'];
            $contact_no = $displayUser['contact_no'];
            $name = $displayUser['name'];
            $user_start_date = $displayUser['user_start_date'];
            $expiry_date = $displayUser['expiry_date'];
            $earlier = new DateTime(date("Y-m-d"));
            $later = new DateTime("$expiry_date");
            $days = $later->diff($earlier)->format("%a");
            $name = $displayUser['name'];
            $email_message = ' <table style="width: 100%">
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
$totalWalletAmount = $manage->displayUserTotalWalletAmount($security->decrypt($_SESSION['id']));
$completedWalletAmount = $manage->displayUserWalletAmount($security->decrypt($_SESSION['id']),"completed");
$pendingWalletAmount = $manage->displayUserWalletAmount($security->decrypt($_SESSION['id']),"pending");

?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Admin Dashboard</title>
    <?php include "assets/common-includes/header_includes.php" ?>
</head>
<body>
<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>DASHBOARD</h2>
        </div>
        <div class="clearfix">
            <div class="col-lg-8 col-xs-12">
                <div class="row">
                    <?php
                    if (isset($_SESSION['type']) && $_SESSION['type'] != "Editor") {
                    ?>
                    <div class="col-lg-6 padding_zero_both col-md-4 col-sm-6 col-xs-12">
                        <?php
                        } else {
                        ?>
                        <div class="col-lg-3 padding_zero_both col-md-4 col-sm-6 col-xs-12">
                            <?php
                            }
                            ?>
                            <a href="user-management.php">
                                <div class="info-box bg-pink hover-expand-effect">
                                    <div class="icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="content">
                                        <div class="text">Total Customer</div>
                                        <div class="number"><?php if (isset($countUser)) echo $countUser; ?></div>
                                    </div>
                                    <?php
                                    if (isset($_SESSION['type']) && $_SESSION['type'] != "Editor") {
                                        ?>
                                        <div class="content">
                                            <div class="text">Active</div>
                                            <div class="number"><?php if (isset($active_customer)) echo $active_customer; ?></div>
                                        </div>
                                        <div class="content">
                                            <div class="text">Follow Up</div>
                                            <div class="number"><?php
                                                $pending_user = $countUser - $active_customer;
                                                if (isset($pending_user)) echo $pending_user; ?></div>
                                        </div>
                                        <?php
                                    }else{
                                        ?>

                                    <?php
                                    }
                                    ?>
                                </div>
                            </a>
                        </div>
                        <?php
                        if (isset($_SESSION['type']) && $_SESSION['type'] == "Admin") {
                            ?>
                            <div class="col-lg-6 padding_zero_both col-md-4 col-sm-6 col-xs-12">
                                <a href="approve-dealer.php">
                                    <div class="info-box bg-cyan hover-expand-effect">
                                        <div class="icon">
                                            <i class="fas fa-user-tie"></i>
                                        </div>
                                        <div class="content">
                                            <div class="text">Total Dealer</div>
                                            <div class="number"><?php if (isset($countDealer)) echo $countDealer; ?></div>
                                        </div>
                                        <div class="content">
                                            <div class="text">Total New Dealer</div>
                                            <div
                                                    class="number"><?php if (isset($pendingDealerCount)) echo $pendingDealerCount; ?></div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <?php
                        }else {
                            ?>
                            <div class="col-lg-6 padding_zero_both col-md-4 col-sm-6 col-xs-12">
                                <a href="view-wallet-history.php">
                                    <div class="info-box bg-grey hover-expand-effect">
                                        <div class="icon">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <div class="content">
                                            <div class="text">Total</div>
                                            <div class="number"><?php if ($totalWalletAmount != "") {
                                                    echo number_format($totalWalletAmount,2);
                                                } else {
                                                    echo "0";
                                                } ?></div>
                                        </div>
                                        <div class="content">
                                            <div class="text">Pending</div>
                                            <div class="number"><?php if ($pendingWalletAmount != "") {
                                                    echo number_format($pendingWalletAmount,2);
                                                } else {
                                                    echo "0";
                                                } ?></div>
                                        </div>
                                        <div class="content">
                                            <div class="text">Paid</div>
                                            <div class="number"><?php if ($completedWalletAmount != "") {
                                                    echo number_format($completedWalletAmount,2);
                                                } else {
                                                    echo "0";
                                                } ?></div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <?php
                        }
                        if (isset($_SESSION['type']) && $_SESSION['type'] != "Editor") {
                            ?>
                            <div class="col-lg-6 padding_zero_both col-md-4 col-sm-6 col-xs-12">
                                <a href="new-user.php">
                                    <div class="info-box bg-grey hover-expand-effect">
                                        <div class="icon">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <div class="content">
                                            <div class="text">Total New Customer</div>
                                            <div class="number"><?php if (isset($newCustomer)) echo $newCustomer; ?></div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <?php
                        }
                        ?>
                        <?php
                        if (isset($_SESSION['type']) && $_SESSION['type'] != "Editor") {
                            ?>
                            <div class="col-lg-12 padding_zero_both col-md-12 col-sm-12 col-xs-12">
                                <form method="post" action="">
                                    <div class="card">
                                        <div class="header">
                                            <div class="col-md-8">
                                                <h2>
                                                    Manage Renewal Listing <span class="badge"><?php
                                                        if (isset($countRenawalListing)) echo $countRenawalListing;
                                                        ?></span>
                                                </h2>
                                            </div>
                                            <div class="col-md-4 text-right">
                                                <button class="btn btn-success" type="submit" name="send_email">SEND
                                                    EMAIL &
                                                    SMS
                                                </button>
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
                                        <div class="body">
                                            <table id="dtHorizontalExample"
                                                   class="table table-striped table-bordered table-sm "
                                                   cellspacing="0"
                                                   width="100%">
                                                <thead>
                                                <tr class="back-color">
                                                    <th><input type="checkbox" id="checkAll"></th>
                                                    <th>Name</th>
                                                    <th>Email ID</th>
                                                    <th>Remaining Days</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                if ($get_data != null) {
                                                    while ($result_data = mysqli_fetch_array($get_data)) {
                                                        $expiry_date = $result_data['expiry_date'];
                                                        ?>
                                                        <tr>
                                                            <td><input type="checkbox" id="checkItem1" name="check[]"
                                                                       value="<?php echo $result_data["user_id"]; ?>">
                                                            </td>
                                                            <td><?php echo $result_data['name']; ?></td>
                                                            <td><?php echo $result_data['email']; ?></td>
                                                            <!--  <td><?php /*echo $result_data['contact_no']; */ ?></td>-->
                                                            <td><?php
                                                                $date = date("Y-m-d");
                                                                $earlier = new DateTime("$date");
                                                                $later = new DateTime("$expiry_date");
                                                                $diff = $later->diff($earlier)->format("%a");
                                                                echo $diff;
                                                                ?>
                                                            </td>
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
                                    </div>
                                </form>

                            </div>
                        <?php } ?>
                    </div>
                </div>
                <?php
                if (isset($_SESSION['type']) && $_SESSION['type'] == "Admin") {
                    ?>
                    <div class="col-lg-4 col-xs-12">
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
                                                <td class="text-right"><label
                                                            class="label label-success"><?php if ($dayAmount['total_amount'] == null) {
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


                    <?php
                }
                ?>
                <div class="col-lg-4 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <div class="col-md-8">
                                <h2>
                                    Manage Not Registered User
                                </h2>
                            </div>
                        </div>

                        <div class="body">
                            <table id="dtHorizontalVerticalExample"
                                   class="table table-striped table-bordered table-sm "
                                   cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr class="back-color">
                                    <th>Contact</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $get_row  = $manage->mu_displayInsertLog();
                                if ($get_row != null) {
                                    while ($rows_data = mysqli_fetch_array($get_row)) {
                                        ?>
                                        <tr>
                                            <td><?php echo $rows_data['contact_no']; ?></td>
                                            <td><?php echo $rows_data['created_date']; ?></td>
                                            <td>      <a href="admin_dashboard.php?delete_data=<?php echo $security->encrypt($rows_data['id']); ?>"
                                                         onclick="return confirm('Are You sure you want to delete?');" class="btn btn-primary">
                                                    <i class="fas fa-trash-alt"></i>&nbsp;&nbsp;Delete</a></td>

                                        </tr>
                                    <?php
                                    }
                                    ?>
                                <?php
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="3" class="text-center">No data found!</td>
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

<?php include "assets/common-includes/footer_includes.php" ?>
<script>
    $("#checkAll").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
</script>
</body>
</html>