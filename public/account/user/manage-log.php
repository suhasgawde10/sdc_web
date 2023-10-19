<?php
ob_start();
include_once "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include "../controller/ManageUser.php";
$manage = new ManageUser();

if (!isset($_SESSION['email'])) {
    header('location:../login.php');
}
/*unset($_SESSION['create_user_status']);*/

/*Sewrvice*/

include("session_includes.php");
include "validate-page.php";

$displayLog = $manage->displayLogDetailsOfUserDetails();
if ($displayLog != null) {
    $countLog = mysqli_num_rows($displayLog);
} else {
    $countLog = 0;
}

?>


<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php include "assets/common-includes/total_count.php" ?>
    <title>Manage Activity Log</title>
    <?php include "assets/common-includes/header_includes.php" ?>
    <style>
        @media (max-width: 480px){
            .footer1_div {
                margin: 6px 0 0px -16px;
            }
        }

    </style>

</head>
<body>
<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>
<section class="content">
    <div class="clearfix padding_bottom_46">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding_zero padding_zero_both">
            <div class="card">
                <div class="header">
                    <h2>
                        My Log Details  <span class="badge"><?php
                            if (isset($countLog)) echo $countLog;
                            ?></span>
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table id="dtHorizontalVerticalExample"
                               class="table table-striped table-bordered table-sm "
                               cellspacing="0"
                               width="100%">
                            <thead>
                            <tr class="back-color">
                                <th>Page</th>
                                <th>Action</th>
                                <th>Remark</th>
                                <th>Time</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if ($displayLog != null) {
                                while ($result_data = mysqli_fetch_array($displayLog)) {
                                    ?>
                                    <tr>
                                        <td><?php echo str_replace('.php','',$result_data['page_name']); ?></td>
                                        <td><?php echo $result_data['action']; ?></td>
                                        <td><div class="log_img">
                                                <?php
                                                if($result_data['img_name'] !=''){
                                                    $key_data = explode(',',$result_data['img_name']);
                                                    if(count($key_data) > 1){
                                                        echo '<img src="uploads/' . $session_email. '/profile/' . $key_data[0] .'"  > TO <img src="uploads/' . $session_email. '/profile/' . $key_data[1] .'"  >';
                                                    }else{
                                                        echo '<img src="uploads/male_user.png"  > TO <img src="uploads/' . $session_email. '/profile/' . $key_data[0] .'"  >';
                                                    }
                                                }else{
                                                    echo $result_data['remark'];
                                                } ?>
                                            </div>
                                            </td>
                                        <td><?php echo $result_data['date_time']; ?></td>
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
        </div>
</section>
<?php include "assets/common-includes/footer_includes.php" ?>
</body>
</html>