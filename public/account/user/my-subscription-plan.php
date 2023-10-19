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
if (isset($_GET['token']) && isset($_GET['type']) && $_GET['type'] == "android") {
    $android_url = "token=" . $_GET['token'] . "&type=" . $_GET['type'];
    $token = $security->decryptWebservice($_GET['token']);
    $seperate_token = explode('+', $token);
    $validateUserId = $manage->validAPIKEYId($seperate_token[0], $seperate_token[1]);
    if ($validateUserId) {
        if (!isset($_SESSION['id']) && !isset($_SESSION['email'])) {
            $userSpecificResult = $manage->getUserProfile($seperate_token[0]);
            if ($userSpecificResult != null) {
                $android_name = $userSpecificResult["name"];
                $android_email = $userSpecificResult["email"];
                $android_custom_url = $userSpecificResult["custom_url"];
                $android_contact = $userSpecificResult['contact_no'];
                $android_type = $userSpecificResult['type'];
            }
            $_SESSION['type'] = $android_type;
            $_SESSION['email'] = $android_email;
            $_SESSION['name'] = $android_name;
            $_SESSION['contact'] = $android_contact;
            $_SESSION['custom_url'] = $android_custom_url;
            $_SESSION['id'] = $security->encrypt($seperate_token[0]);
        }
    } else {
        header('location:404-not-found.php?' . $android_url);
    }
} elseif (!isset($_SESSION['email'])) {
    header('location:../login.php');
} else {
    $android_url = "";
}
include("session_includes.php");
include "validate-page.php";


/*$newCommerse = $manage->displayNewSubscriptionDetails();
if ($newCommerse != null) {
    $countNewCommerce = mysqli_num_rows($newCommerse);
} else {
    $countNewCommerce = 0;
}*/


/*$display_message = $manage->displayDealerProfile();
if($display_message!=null){
    $message_status = $display_message['message_status'];
    $status = $display_message['status'];
}*/

$displaySubscription = $manage->displaySubscriptionDetails();
if ($displaySubscription != null) {
    $countDescription = mysqli_num_rows($displaySubscription);
} else {
    $countDescription = 0;
}

?>


<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php include "assets/common-includes/total_count.php" ?>
    <title>My Subscription Plan</title>
    <?php include "assets/common-includes/header_includes.php" ?>
    <style>
        @media (max-width: 480px) {
            .footer1_div {
                margin: 6px 0 0px -16px;
            }
        }

    </style>

</head>
<body>
<?php
if (!isset($_GET['token']) && (!isset($_GET['type']))) {
?>
<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>

<section class="content">
    <?php
    if (isset($_SESSION['create_user_status']) && $_SESSION['create_user_status'] == true) {
        include "assets/common-includes/session_button_includes.php";
    }
    ?>
    <?php
    }elseif (isset($_GET['token']) && (isset($_GET['type']) && $_GET['type'] == "android")) {
    ?>
    <section class="androidSection">
        <?php
        function like_match($pattern, $subject)
        {
            $pattern = str_replace('%', '.*', preg_quote($pattern, '/'));
            return (bool)preg_match("/^{$pattern}$/i", $subject);
        }
        }
        ?>
        <div class="clearfix padding_bottom_46">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding_zero padding_zero_both">
                <div class="card">
                    <div class="header">
                        <h2>
                            My Subscription Plans  <span class="badge"><?php
                                if (isset($countDescription)) echo $countDescription;
                                ?></span>
                        </h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-condensed table-bordered table-striped">
                                <thead>
                                <tr class="back-color">
                                    <!--<th>Name</th>
                                    <th>Contact</th>-->
                                    <th>Package</th>
                                    <?php
                                    if (like_match('%dealer%', $referral_by) != 1) {
                                        ?>
                                        <th class="visible-lg visible-md hidden-sm hidden-xs">Amount</th>
                                    <?php
                                    }
                                    ?>
                                    <th class="visible-lg visible-md hidden-sm hidden-xs">Start Date</th>
                                    <th class="visible-lg visible-md hidden-sm hidden-xs">End Date</th>
                                    <th class="visible-lg visible-md hidden-sm hidden-xs">Payment Status</th>
                                    <th class="visible-lg visible-md hidden-sm hidden-xs">Status</th>
                                    <?php
                                    if (like_match('%dealer%', $referral_by) != 1) {
                                        if ($main_site) {
                                            ?>
                                            <th>Action</th>
                                        <?php
                                        }
                                    }
                                    ?>
                                    <!--<th>ACTION</th>-->
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if ($displaySubscription != null) {
                                    while ($result_data = mysqli_fetch_array($displaySubscription)) {
                                        ?>
                                        <tr <?php if ($result_data['status'] == "success") {
                                            echo 'style="background-color: #B2DFDB"';
                                        } elseif ($result_data['status'] == "failed") {
                                            echo 'style="background-color: #FFCDD2"';
                                        }; ?>>
                                            <!--<td><?php /*echo $result_data['name']; */ ?></td>
                                    <td><?php /*echo $result_data['contact']; */ ?></td>-->
                                            <!--<td><?php /*if ($result_data['type'] == 1) {
                                            echo "Digital Card";
                                        } else {
                                            echo "Digital Card + Website (combo)";
                                        } */
                                            ?></td>-->

                                            <td class="visible-lg visible-md hidden-sm hidden-xs"><?php echo $result_data['year']; ?></td>
                                            <?php
                                            if (like_match('%dealer%', $referral_by) != 1) {
                                                ?>
                                                <td class="visible-lg visible-md hidden-sm hidden-xs"><?php echo $result_data['total_amount']; ?></td>
                                            <?php
                                            }
                                            ?>
                                            <td class="visible-lg visible-md hidden-sm hidden-xs"><?php echo $result_data['start_date']; ?></td>
                                            <td class="visible-lg visible-md hidden-sm hidden-xs"><?php echo $result_data['end_date']; ?></td>
                                            <td class="visible-lg visible-md hidden-sm hidden-xs"><?php echo strtoupper($result_data['status']); ?></td>

                                            <td class="hidden-lg hidden-md visible-sm visible-xs">
                                                <b><?php echo $result_data['year']; ?></b><br>
                                                <?php echo $result_data['total_amount']; ?><br>
                                                <?php echo $result_data['start_date']; ?>
                                                to <?php echo $result_data['end_date']; ?><br>
                                                <?php echo strtoupper($result_data['status']); ?><br>
                                                <label class="label <?php if ($result_data['active_plan'] == "0") {
                                                    echo "label-danger";
                                                } else {
                                                    echo "label-success";
                                                } ?>"><?php if ($result_data['active_plan'] == 0) {
                                                        echo "Expired";
                                                    } else {
                                                        echo "Active";
                                                    } ?></label></td>
                                            <td class="visible-lg visible-md hidden-sm hidden-xs">
                                                <div>
                                                    <label class="label <?php if ($result_data['active_plan'] == "0") {
                                                        echo "label-danger";
                                                    } else {
                                                        echo "label-success";
                                                    } ?>"><?php if ($result_data['active_plan'] == 0) {
                                                            echo "Expired";
                                                        } else {
                                                            echo "Active";
                                                        } ?></label>
                                                </div>
                                            </td>
                                            <?php
                                            if (like_match('%dealer%', $referral_by) != 1) {
                                                if ($main_site) {
                                                    ?>
                                                    <td>
                                                        <a class="btn btn-default" <?php if ($result_data['invoice_no'] == "" or $result_data['invoice_no'] == 'NULL' or $result_data['dealer_by_pay'] == '1') {
                                                            echo 'disabled';
                                                            echo ' href="#"';
                                                        } else {
                                                            if ($android_url != "") {
                                                                echo 'href="user-invoice.php?user_invoice_id=' . $security->encrypt($result_data['id']) . '&' . $android_url . '"';
                                                            } else {
                                                                echo 'href="user-invoice.php?user_invoice_id=' . $security->encrypt($result_data['id']) . '"';
                                                            }
                                                        } ?>>View Invoice</a></td>
                                                <?php
                                                }
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
        </div>
    </section>
    <?php include "assets/common-includes/footer_includes.php" ?>
</body>
</html>