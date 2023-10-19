<?php
ob_start();
error_reporting(1);
include_once "../controller/ManageUser.php";
$manage = new ManageUser();
include "../controller/validator.php";
$validate = new Validator();
include '../sendMail/sendMail.php';
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
$error = false;
$errorMessage = "";
if (!isset($_SESSION['email'])){
    header('location:../login.php');
}elseif(isset($_SESSION['email']) && isset($_SESSION['type']) && ($_SESSION['type'] != 'Admin' && $_SESSION['type'] != 'Editor')){
    header('location:../login.php');
}
if (isset($_POST['change'])) {
    $user_id = $_POST['user_id'];
    if (isset($_POST['set_status']))#for tempory validation
    {
        $set_status = $_POST['set_status'];
    }
    if($set_status !=""){
        $update = $manage->updateDealerPercent($set_status,$user_id);
        if($update){
            $error = false;
            $errorMessage = "Dealer Percent updated successfully!";
        }else{
            $error = true;
            $errorMessage = "Issue while updating Dealer Percent.";
        }
    }
}

$approveUser = $manage->displayApproveDealer();
if ($approveUser != null) {
    $countNewCommerce = mysqli_num_rows($approveUser);
} else {
    $countNewCommerce = 0;
}

/*if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $updateUserStatus = $manage->updateUserStatus($user_id);

    if ($updateUserStatus) {
        header('location:dealer-management.php');
    }
}*/
if (isset($_GET['publishData']) && (isset($_GET['action'])) && (isset($_GET['dealer_email'])) && (isset($_GET['contact_no']))) {
    $action = $_GET['action'];
    $id = $_GET['publishData'];
    $dealer_name = $_GET['dealer_name'];
    if ($action == "Unblock") {
        $result = $manage->blockUnblockDealer($id, 1, $manage->dealerProfileTable);
        if ($result) {
            $toName = "Kubic";
            $toEmail = $_GET['dealer_email'];
            $subject = "Unblock";
            $message = "You have been Unblock now you can log in";
            $email_message = '<table style="width: 100%">
<tr>
<td colspan="2" style=' .$back_image. '>
<div style="' . $overlay. '">
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
                    <p>Dear <span style="color:blue;">' . ucwords($dealer_name) . '</span>,<p>
                    <p> ' . $message .'</p>

                </div>
</td>
</tr>
<tr><td colspan="2" style="text-align:center">
<a href="http://sharedigitalcard.com/dealer-register.php?sign-in=true" style="' . $btn. ';color:white; border-radius: 4px;"><img src="http://sharedigitalcard.com/user/assets/images/laptop.png" style="width: 19%;display: inline-block;vertical-align: middle;padding-right: 5px;color: white;">Click To Login</a>
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
            $sendEmail = $manage->sendMail($toName, $toEmail, $message, $email_message);
            $adminContact = $_GET['contact_no'];
            $sms_message = $manage->sendSMS($adminContact, $message);
        }
    } else {
        $result = $manage->blockUnblockDealer($id, 0, $manage->dealerProfileTable);
        if ($result) {
            $toName = "Kubic";
            $toEmail = $_GET['dealer_email'];
            $subject = "Block";
            $message = "You have been block from sharedigitalcard.\n for any enquiry please contact our admin";
            $email_message = '<table style="width: 100%">
<tr>
<td colspan="2" style=' .$back_image. '>
<div style="' . $overlay. '">
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
                    <p>Dear <span style="color:blue;">' . ucwords($dealer_name) . '</span>,<p>
                    <p> ' . $message .'</p>

                </div>
</td>
</tr>
<tr><td colspan="2" style="text-align:center">
<a href="http://sharedigitalcard.com/dealer-register.php?sign-in=true" style="' . $btn. ';color:white; border-radius: 4px;"><img src="http://sharedigitalcard.com/user/assets/images/laptop.png" style="width: 19%;display: inline-block;vertical-align: middle;padding-right: 5px;color: white;">Click To Login</a>
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
            $sendEmail = $manage->sendMail($toName, $toEmail, $message, $email_message);
            $adminContact = $_GET['contact_no'];
            $sms_message = $manage->sendSMS($adminContact, $message);
        }
    }
    header('location:approve-dealer.php');
}

if (isset($_GET['delete_data']) && $_GET['email']) {
    $email = $_GET['email'];
    $delete_data = $security->decrypt($_GET['delete_data']);
    $dirPath = "../dealer/uploads/$email";
    function deleteDirectory($dirPath)
    {
        if (is_dir($dirPath)) {
            $objects = scandir($dirPath);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dirPath . DIRECTORY_SEPARATOR . $object) == "dir") {
                        deleteDirectory($dirPath . DIRECTORY_SEPARATOR . $object);
                    } else {
                        unlink($dirPath . DIRECTORY_SEPARATOR . $object);
                    }
                }
            }
            reset($objects);
            rmdir($dirPath);
        }
    }

    deleteDirectory($dirPath);
    $status = $manage->deleteDealer($delete_data);
    if ($status) {
        header('location:approve-dealer.php');
    }
}




?>


<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Approve Dealers</title>
    <?php include "assets/common-includes/header_includes.php" ?>
</head>
<body>

<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>
<section class="content">
    <div class="up-nav visible-lg visible-md visible-sm hidden-xs">
        <main>
            <div class="page-content" id="applyPage">
                <ul class="breadcrumbs">
                    <li class="tab-link breadcrumb-item">
                        <a href="dealer-management.php">
                            <span class="number"><i class="fas fa-user"></i></span>
                            <span class="label">Pending dealer</span>
                        </a>
                    </li>
                    <li class="tab-link breadcrumb-item  active visited" id="crumb5">
                        <a href="approve-dealer.php">
                            <span class="number"><i class="fas fa-money-bill-alt"></i></span>
                            <span class="label">Approve Dealer</span>
                        </a>
                    </li>
                    <li class="tab-link breadcrumb-item">
                        <a href="rejected_dealer.php">
                            <span class="number"><i class="fas fa-user"></i></span>
                            <span class="label">Rejected dealer</span>
                        </a>
                    </li>
                </ul>

            </div>

        </main>
    </div>
    <div class="clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <div class="card">
                <div class="header">
                    <h2>
                        Manage Approve Dealer  <span class="badge"><?php
                            if (isset($countNewCommerce)) echo $countNewCommerce;
                            ?></span>
                    </h2>
                </div>
                <div class="body">
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
                    <table id="dtHorizontalVerticalExample" class="table table-striped table-bordered table-sm "
                           cellspacing="0"
                           width="100%">
                        <thead>
                        <tr class="back-color">
                            <th>Reseller name</th>
                            <th>Login</th>
                            <th>Customer</th>
                            <th>Wallet</th>
                            <th>Kubic Amount</th>
                            <th>Percent(%)</th>
                            <?php
                            if(isset($_SESSION['type']) && ($_SESSION['type'] == "Admin")) {
                                ?>
                                <th>Action</th>
                            <?php
                            }
                            ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if ($approveUser != null) {

                            $get_price = $manage->getDealerPriceDetails();
                            $result_list = array();
                            while($form_data = mysqli_fetch_array($get_price)) {
                                $result_list[] = $form_data;
                            }
                            while ($result_data = mysqli_fetch_array($approveUser)) {
                                $dealer_code = $result_data['dealer_code'];
                                $getKubicAmount = $manage->kubicAmountByDealer($dealer_code);
                                if ($getKubicAmount['kubicAmount'] != null) {
                                    $kubicTotalAmount = $getKubicAmount['kubicAmount'];
                                } else {
                                    $kubicTotalAmount = 0;
                                }
                                $newCommerse = $manage->countAllUserByDealerCode($dealer_code);

                                $activeCommerse = $manage->countAllActiveUserOfdealer($dealer_code);

                                $inactive_user = $newCommerse - $activeCommerse;
                                $totalWalletAmount = $manage->displayDealerWalletAmount($dealer_code,"completed','pending");
                                $pendingWalletAmount = $manage->displayDealerWalletAmount($dealer_code,"pending");

                                $gender = $result_data['gender'];
                                $profilePath = "../dealer/uploads/" . $result_data['email'] . "/profile/" . $result_data['img_name'];
                                ?>
                                <tr>
                                    <td> <a target="_blank" href="view-dealer-profile.php?user_id=<?php echo $security->encrypt($result_data['user_id']); ?>">
                                            <div style="display: inline-block;vertical-align: top">
                                                <img
                                                    src="<?php if (!file_exists($profilePath) && $gender == "Male" or $result_data['img_name'] == "") {
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
                                                if($result_data['pay_status'] == "1"){
                                                  echo  " - <label class='label label-info'>" .$result_data['membership']. "</label><br>";
                                                }else{
                                                    echo   " - <label class='label label-danger'>Unpaid</label><br>";
                                                }
                                                echo $result_data['c_name']."<br>";
                                                if($result_data['created_date'] !=""){
                                                $date1=date_create($result_data['created_date']);
                                                $date2=date_create(date('Y-m-d'));
                                                    $diff=date_diff($date1,$date2);
                                                    echo "Date: ".date('d-M-Y',strtotime($result_data['created_date']));
                                                    echo '<br><label class="label label-success">'.$diff->format("%R%a days").'</label>';
                                                }else{
                                                    echo "-";
                                                }
                                                ?>
                                            </div>
                                        </a></td>
                                    <td><?php echo $result_data['email']."<br>".$result_data['contact_no']."<br>".$result_data['password']."<br>"; ?></td>
                                    <td><?php echo "Total : ". $newCommerse."<br>Converted : ".$activeCommerse."<br>Followup : ".$inactive_user; ?></td>
                                    <td><?php echo '<a href="view-wallet-history.php?dealer_code=' . $security->encryptWebservice($dealer_code).'">Total : ' . $totalWalletAmount.'<br>Payable : '.$pendingWalletAmount.' </a>'; ?></td>
                                    <td><?php echo $kubicTotalAmount; ?></td>

                                    <td>
                                        <form method="post" action="">
                                            <select id="set_status" name="set_status"
                                                    class="form-control"    <?php
                                if(isset($_SESSION['type']) && ($_SESSION['type'] == "Editor")) echo 'disabled';
                                    ?>>
                                                <option value="">Select an Option</option>
                                                <?php
                                                foreach ($result_list as $row) {
                                                    ?>
                                                    <option <?php if (isset($result_data['dealer_percent']) && $result_data['dealer_percent'] == $row['id']) echo 'selected' ?>
                                                        value="<?php echo $row['id']; ?>"><?php echo $row['percentage']; ?>
                                                    </option>
                                                <?php
                                                }
                                                ?>
                                            </select><br>
                                            <input type="hidden" value="<?php echo $result_data['user_id'] ?>"
                                                   name="user_id">
                                <?php
                                if(isset($_SESSION['type']) && ($_SESSION['type'] == "Admin")) {
                                    ?>
                                    <button type="submit" class="btn btn-primary" name="change">Change
                                    </button>
                                <?php
                                }
                                    ?>
                                        </form>

                                    </td>
                                    <?php
                                    if(isset($_SESSION['type']) && ($_SESSION['type'] == "Admin")) {
                                        ?>
                                    <td>
                                        <ul class="header-dropdown">
                                            <li class="dropdown dropdown-inner-table">
                                                <a href="javascript:void(0);" class="dropdown-toggle"
                                                   data-toggle="dropdown"
                                                   role="button" aria-haspopup="true" aria-expanded="false">
                                                    <i class="material-icons">more_vert</i>
                                                </a>
                                                <ul class="dropdown-menu pull-right">
                                                    <li>
                                                        <a onclick="return confirm('Are You sure you want to <?php echo $result_data['block_status'] == 1 ? 'Block' : 'Unblock'; ?>?');"
                                                           href="approve-dealer.php?publishData=<?php echo $result_data['user_id'] ?>&action=<?php echo $result_data['block_status'] == 1 ? "Block" : "Unblock"; ?>&contact_no=<?php echo $result_data['contact_no'] ?>&dealer_email=<?php echo $result_data['email']; ?>&dealer_name=<?php echo $result_data['name']; ?>"
                                                           class="<?php echo $result_data['block_status'] == 0 ? "fa fa-unlock" : "fa fa-ban"; ?>">
                                                            &nbsp;&nbsp;<?php echo $result_data['block_status'] == 1 ? "Block" : "Unblock"; ?></a>
                                                        <!-- <input type="hidden" value="<?php /*echo $result_data['contact_no']; */ ?>" name="dealer_contact_no">-->
                                                    </li>
                                                    <li>
                                                    <li>
                                                        <a href="approve-dealer.php?delete_data=<?php echo $security->encrypt($result_data['user_id']) ?>&email=<?php echo $result_data['email'] ?>"
                                                           onclick="return confirm('Are You sure you want to delete?');">
                                                        <i class="fas fa-trash-alt"></i>&nbsp;&nbsp;Delete</a>
                                                    </li>
                                                    <!--<li>
                                                            <a onclick="return confirm('Are You sure you want to <?php /*echo $result_data['status'] == 0 ? 'publish' : 'unpublish'; */ ?>?');"
                                                               href="user-management.php?publishData=<?php /*echo $security->encrypt($result_data['id']) */ ?>&action=<?php /*echo $result_data['status'] == 0 ? "publish" : "unpublish"; */ ?> "><i
                                                                    class="fas <?php /*echo $result_data['status'] == 0 ? "fa-upload" : "fa-download"; */ ?>"></i>&nbsp;&nbsp;<?php /*echo $result_data['status'] == 1 ? "Unpublish" : "Publish"; */ ?>
                                                            </a>
                                                        </li>-->
                                                </ul>
                                            </li>
                                        </ul>
                                    </td>
                                <?php
                                }
                                ?>
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
        </div>
    </div>
</section>


<?php include "assets/common-includes/footer_includes.php" ?>

<script type="text/javascript">
    function SetStatus(value, i) {

        var val = "reject_remarks_" + i;
        if (value == 'Rejected') {
            $('#' + val).removeClass('hide');
            $('#' + val).addClass('show');
        } else {
            $('#' + val).removeClass('show');
            $('#' + val).addClass('hide');
        }
    }
</script>
</body>
</html>