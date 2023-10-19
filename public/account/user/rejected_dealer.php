<?php
ob_start();
error_reporting(1);
include "../controller/ManageUser.php";
$manage = new ManageUser();
include "../controller/validator.php";
$validate = new Validator();
include '../sendMail/sendMail.php';
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
if (!isset($_SESSION['email'])){
    header('location:../login.php');
}elseif(isset($_SESSION['email']) && isset($_SESSION['type']) && ($_SESSION['type'] != 'Admin' && $_SESSION['type'] != 'Editor')){
    header('location:../login.php');
}

$newCommerse = $manage->displayRejectedDealer();
if ($newCommerse != null) {
    $countNewCommerce = mysqli_num_rows($newCommerse);
} else {
    $countNewCommerce = 0;
}

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
}


if (isset($_POST['change'])) {
    if (isset($_POST['set_status']))#for tempory validation
    {
        $set_status = $_POST['set_status'];
        if ($_POST['set_status'] == "") {
            header('location:dealer-management.php');
        }
    }


    if (isset($_POST['reject_remarks'])) {
        $rejection_remarks = $_POST['reject_remarks'];
    }
    if(isset($_POST['email'])){
        $email = $_POST['email'];
    }
    if(isset($_POST['name'])){
        $name = $_POST['name'];
    }
    if(isset($_POST['contact_no'])){
        $contact_no = $_POST['contact_no'];
    }

    if ($set_status == 'Approved') {

        if(isset($_POST['chk_payment'])){
            $chk_payment = 0;
        }else{
            $chk_payment = 1;
        }
        $updateUserStatus = $manage->updateUserApprovedStatus($set_status,$_POST['leave_details_id'],$chk_payment);
        if ($updateUserStatus) {
            $toName = "Kubic";
            $toEmail = $email;
            $subject = "Form Accepted";
            $message = "dear " . ucwords($name) . ",\n
We have verified your details. we are happy to see you as part of our portal.";
            // $sendEmail = $manage->sendMail($toName,$toEmail,$subject,$message);
            $userContact = $contact_no;
            $sms_message = $manage->sendSMS($userContact,$message);
            header('location:dealer-management.php');
        }
    } else {
        $updateUserStatus = $manage->updateUserRejectedStatus($set_status,$rejection_remarks,$_POST['leave_details_id']);
        if ($updateUserStatus) {
            $toName = "Kubic";
            $toEmail = $email;
            $subject = "Form Rejected";
            $message = "dear " . ucwords($name) . ",\n
please login and re-check the details. Update your details to get approval from the portal.";
            $sendEmail = $manage->sendMail($toName,$toEmail,$subject,$message);
            $userContact = $contact_no;
            $sms_message = $manage->sendSMS($userContact,$message);
            header('location:dealer-management.php');
        }
    }
}
?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Rejected Dealer</title>
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
                    <li class="tab-link breadcrumb-item" id="crumb5">
                        <a href="approve-dealer.php">
                            <span class="number"><i class="fas fa-money-bill-alt"></i></span>
                            <span class="label">Approve Dealer</span>
                        </a>
                    </li>
                    <li class="tab-link breadcrumb-item active visited">
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
                            Manage Rejected Dealer  <span class="badge"><?php
                                if (isset($countNewCommerce)) echo $countNewCommerce;
                                ?></span>
                        </h2>
                    </div>
                    <div class="body">
                        <table id="dtHorizontalVerticalExample" class="table table-striped table-bordered table-sm "
                               cellspacing="0"
                               width="100%">
                            <thead>
                            <tr class="back-color">
                                <th>Name</th>
                                <th>Contact</th>
                                <th>Email</th>
                                <th>Password</th>
                                <th>Dealer code</th>
                                <th>Message</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if ($newCommerse != null) {
                                while ($row = mysqli_fetch_array($newCommerse)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['name']; ?></td>
                                        <td><?php echo $row['contact_no']; ?></td>
                                        <td><?php echo $row['email']; ?></td>
                                        <td><?php echo $row['password']; ?></td>
                                        <td><?php echo $row['dealer_code']; ?></td>
                                        <td><?php echo $row['rejected_message']; ?></td>
                                        <td><a href="view-dealer-profile.php?user_id=<?php echo $security->encrypt($row['user_id']) ?>"><i class="fas fa-eye"></i>&nbsp;&nbsp;View profile</a></td>
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

<?php include "assets/common-includes/footer_includes.php" ?>


</body>
</html>