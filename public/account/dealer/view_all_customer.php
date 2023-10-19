<?php
ob_start();
error_reporting(1);
include "../controller/ManageDealer.php";
$manage = new ManageDealer();
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();

$error = false;
$errorMessage = "";

if (!isset($_SESSION['dealer_email'])) {
    header('location:../dealer-register.php');
}
/*unset($_SESSION['create_user_status']);*/

/*Sewrvice*/


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

if (isset($_GET['delete_user']) && $_GET['action'] == 'delete') {
    $delete_user_id = $security->decrypt($_GET['delete_user']);
    $insert = $manage->deactivateUserAccount($delete_user_id, $txt_reason, $further, "Deleted", 3);
    if ($insert) {
        $email = $_GET['email'];
        $oldname = '../user/uploads/' . $email . '';
        $newname = '../user/uploads/' . "deleted_" . $email;
        rename($oldname, $newname);
        $error = false;
        $errorMessage = "User has been deleted successfully!";
        header("refresh:1;url=view_all_customer.php?all_customer=" . $_GET['all_customer']);
    } else {
        $error = false;
        $errorMessage = "Issue while deleting please try after some time !";
    }
}

if (isset($_GET['update_user']) && $_GET['action'] == 'update') {
    $update_user_id = $security->decrypt($_GET['update_user']);
    $update_user_email = $_GET['email'];
    $update_password = $security->encrypt('12345678') . '8523';
    $update = $manage->UpdatePasswordUserAccount($update_user_id, $update_password, $update_user_email);
    if ($update) {
        $errorMessage = "User password has been Updated successfully! <br> New password is <b>12345678</b>";
        header("refresh:1;url=view_all_customer.php?all_customer=" . $_GET['all_customer']);
    } else {
        $error = false;
        $errorMessage = "Issue while deleting please try after some time !";
    }
}

if (isset($_GET['all_customer'])) {
    $id = $security->decrypt($_GET['all_customer']);
    $newCommerse = $manage->displayAllInActiveUser($id);
    if ($newCommerse != null) {
        $countNewCommerce = mysqli_num_rows($newCommerse);
    } else {
        $countNewCommerce = 0;
    }
    $activeCommerse = $manage->displayAllActiveUser($id);
    if ($activeCommerse != null) {
        $countActiveCommerce = mysqli_num_rows($activeCommerse);
    } else {
        $countActiveCommerce = 0;
    }
}

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

}

if (isset($_POST['btn_website_color'])) {
    $id = $_POST['d_user_id'];
    $txt_theme_color = $_POST['txt_theme_color'];
    $condition = array('id' => $id);
    $data = array('website_theme_color' => $txt_theme_color);
    $update = $manage->update($manage->profileTable, $data, $condition);
    if ($update) {
        $error = false;
        $errorMessage = "Website Theme Color updated successfully!";
    } else {
        $error = true;
        $errorMessage = "Issue while updating please try after some time!";
    }

}

if (isset($_POST['btn_website_theme'])) {
    $id = $_POST['d_user_id'];
    $drp_theme = $_POST['drp_website_theme'];
    $condition = array('id' => $id);
    $data = array('theme_id' => $drp_theme);
    $update = $manage->update($manage->profileTable, $data, $condition);
    if ($update) {
        $error = false;
        $errorMessage = "Website Theme updated successfully!";
    } else {
        $error = true;
        $errorMessage = "Issue while updating please try after some time!";
    }
}

if (isset($_POST['btn_website_logo_change'])) {
    $id = $_POST['d_user_id'];
    $logo_size = $_POST['txt_logo'];
    $condition = array('id' => $id);
    $data = array('company_logo_width' => $logo_size);
    /*print_r($data);
    exit;*/
    $update = $manage->update($manage->profileTable, $data, $condition);
    if ($update) {
        $error = false;
        $errorMessage = "Website Logo size updated successfully!";
    } else {
        $error = true;
        $errorMessage = "Issue while updating please try after some time!";
    }
}



?>


<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>All Customers</title>
    <?php include "assets/common-includes/header_includes.php" ?>

    <style>
        .dataTables_scrollBody {
            padding-bottom: 150px;
        }

        .morecontent span {
            display: none;
        }

        .morelink {
            display: block;
        }

        .tooltip {
            position: relative;
            display: inline-block;
            opacity: 1;
        }

        .tooltip .tooltiptext {
            visibility: hidden;
            width: 140px;
            background-color: #555;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            bottom: 107%;
            left: 50%;
            margin-left: -75px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .tooltip .tooltiptext::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: #555 transparent transparent transparent;
        }

        .tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }

        .width_50 {
            width: 50%;
        }

        #copy_review {
            opacity: 0;
            height: 0;
        }

        .div_width_50 {
            width: 49%;
            display: inline-block;
        }

        .right-side-top {
            float: right;
            margin-top: 5px;
        }
    </style>
</head>
<body>

<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>

<section class="content">
    <div class="container-fluid">

        <!-- <div class="row clearfix">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a href="view_all_customer.php?all_customer=<?php /*echo $id; */ ?>">
                    <div class="info-box bg-pink hover-expand-effect">
                        <div class="icon">
                            <i class="fas fa-camera-retro"></i>
                        </div>
                        <div class="content">
                            <div class="text">Total Customer</div>
                            <div class="number"><?php /*if (isset($countNewCommerce)) echo $countNewCommerce; */ ?></div>
                        </div>
                    </div>
                </a>
            </div>
        </div>-->
    </div>
    <div class="clearfix">
        <div class="row">
            <?php include 'assets/common-includes/shareUrl.php' ?>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <div class="row">

                <div class="card">
                    <!-- <div class="header">
                        <h2>
                            All Customers  <span class="badge"><?php
                    /*                                if (isset($all_customer_count)) echo $all_customer_count;
                                                    */ ?></span>
                        </h2>
                    </div>-->

                    <div class="body" style="padding-top: 0">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs tab-nav-right" role="tablist">
                            <li role="presentation" class="active"><a data-toggle="tab" href="#inactive-cutomer"
                                                                      class="customer_tab">Inactive Customer <label
                                        class="badge badge-success"><?php echo $countNewCommerce; ?></label></a>
                            </li>
                            <li role="presentation"><a
                                    href="active-customer.php?all_customer=<?php echo $_GET['all_customer']; ?>">Active
                                    Customer <label
                                        class="badge badge-danger"><?php echo $countActiveCommerce; ?></label></a>
                            </li>
                            <div class="col-md-2 right-side-top">
                                <button class="btn btn-success" type="button" id="btn-export">
                                    Export To Excel
                                </button>
                            </div>
                        </ul>

                        <div class="tab-content">

                            <div role="tabpanel" class="tab-pane fade in active" id="inactive-cutomer">
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

                                <table id="dtHorizontalVerticalExample"
                                       class="table table-striped table-bordered table-sm " cellspacing="0"
                                       width="100%">
                                    <thead>
                                    <tr class="back-color">
                                        <th>User</th>
                                        <th>Login</th>
                                        <th>Plan</th>
                                        <th>Keywords</th>
                                        <!--<th>Amount</th>-->
                                        <!-- <th>Start Date</th>
                                         <th>End Date</th>-->
                                        <th class="noExl">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if ($newCommerse != null) {
                                        while ($result_data = mysqli_fetch_assoc($newCommerse)) {
                                            $link = SHARED_URL . $result_data['custom_url'];
                                            $user_id2 = $security->encrypt($result_data['user_id']);
                                            $token_url = "http://sharedigitalcard.com/user/share-your-feedback.php?token=" . $security->encryptWebservice($result_data['user_id']);
                                            $custom_url = $result_data['custom_url'];
                                            $name = urlencode($result_data['name']);
                                            $email = $result_data['email'];
                                            $contact_no = $result_data['contact_no'];
                                            $gender = $result_data['gender'];
                                            $password = rtrim($result_data['password'], "8523");
                                            $profilePath = "../user/uploads/" . $result_data['email'] . "/profile/" . $result_data['img_name'];
                                            $end_date = $result_data['expiry_date'];
                                            $date = date("Y-m-d");
                                            $earlier = new DateTime("$date");
                                            $later = new DateTime("$end_date");
                                            $website_theme_color = $result_data['website_theme_color'];

                                            $website_theme = $result_data["theme_id"];
                                            $logo_width = $result_data["company_logo_width"];

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

                                                <td>
                                                    <a href="view_customer.php?user_id=<?php echo $user_id2; ?>"
                                                       target="_blank">
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
                                                <td><?php echo $result_data['email'] . "<br>" . $result_data['contact_no']; ?>
                                                    <!--<br>--><?php /*echo $security->decrypt($password);*/
                                                    ?></td>
                                                <td><?php if ($result_data['user_start_date'] != "") {
                                                        echo "Reg. : " . $result_data['user_start_date'] . "<br>";
                                                    }
                                                    echo "Plan : " . $result_data['year'] . '<br>days : <label class="label label-success">' . $diff . '</label>'; ?></td>
                                                <td><?php if ($result_data['user_keyword'] != "") {
                                                        echo "<span class='more'>";
                                                        echo wordwrap($result_data['user_keyword'], 20, "<br>");
                                                        echo "</span>";
                                                    } else {
                                                        echo "Not Available";
                                                    } ?></td>
                                                <td class="noExl">
                                                    <ul class="header-dropdown">
                                                        <li class="dropdown dropdown-inner-table">
                                                            <a href="javascript:void(0);" class="dropdown-toggle"
                                                               data-toggle="dropdown"
                                                               role="button" aria-haspopup="true"
                                                               aria-expanded="false">
                                                                <i class="material-icons">more_vert</i>
                                                            </a>
                                                            <ul class="dropdown-menu pull-right">
                                                                <?php
                                                                if (isset($_SESSION['dealer_type']) && $_SESSION['dealer_type'] == "dealer") {
                                                                    ?>
                                                                    <li>
                                                                        <a href="view_customer.php?user_id=<?php echo $user_id2; ?>"><i
                                                                                class="fas fa-eye"></i>&nbsp;&nbsp;View
                                                                            more</a>
                                                                    </li>
                                                                <?php
                                                                }
                                                                ?>
                                                                <li>
                                                                    <a target="_blank"
                                                                       href="<?php echo $link; ?>"><i
                                                                            class="fas fa-globe"></i>&nbsp;&nbsp;Live
                                                                        url</a>
                                                                </li>
                                                                <li>
                                                                    <a onclick="copyClipboard('<?php echo $link; ?>')"><i
                                                                            class="fas fa-copy"></i>&nbsp;&nbsp;Copy
                                                                        Digital Card URL</a>
                                                                </li>
                                                                <li>
                                                                    <a onclick="copyClipboard('<?php echo $token_url; ?>')"><i
                                                                            class="fas fa-copy"></i>&nbsp;&nbsp;Copy
                                                                        Feedback URL</a>
                                                                </li>
                                                                <li>
                                                                    <a href="#"
                                                                       onclick="openThemeChangeLogoModal('<?php echo $result_data['id']; ?>','<?php echo $logo_width ?>')">
                                                                        <i class="fas fa-envelope"></i>&nbsp;&nbsp;Logo
                                                                        adjust</a>
                                                                </li>
                                                                <li>
                                                                    <a href="#"
                                                                       onclick="openColorModal('<?php echo $result_data['user_id']; ?>','<?php echo $website_theme_color; ?>')">
                                                                        <i class="fas fa-palette"></i>&nbsp;&nbsp;Update
                                                                        Website Color</a>
                                                                </li>
                                                                <li>
                                                                    <a href="#"
                                                                       onclick="openThemeChangeModal('<?php echo $result_data['id']; ?>','<?php echo $website_theme; ?>')">
                                                                        <i class="fas fa-envelope"></i>&nbsp;&nbsp;Update
                                                                        Website Theme</a>
                                                                </li>
                                                                <li>
                                                                    <a  <?php
                                                                    if ($result_data['dealer_access'] == 1) {
                                                                        ?> target="_blank"
                                                                        href="../user/dealer-user-session.php?id=<?php echo $user_id2 ?>&name=<?php echo $name; ?>
                                                            &contact=<?php echo $contact_no ?>&email=<?php echo urlencode($email); ?>&custom_url=<?php echo $custom_url ?>&dealer_id=<?php echo $security->encrypt($_SESSION['dealer_id']); ?>&dealer_login_type=dealer" <?php } else {
                                                                        echo 'data-target="#myModal" data-toggle="modal"';
                                                                    } ?>>
                                                                        <i class="fas fa-edit"></i>&nbsp;&nbsp;Login</a>
                                                                </li>
                                                                <?php
                                                                if (isset($_SESSION['dealer_type']) && $_SESSION['dealer_type'] == "dealer") {

                                                                    if ($percentValue == '100') {
                                                                        ?>
                                                                        <li>
                                                                            <a href="plan-selection_last.php?user_id=<?php echo $user_id2 ?>">
                                                                                <i class="fa fa-money"
                                                                                   aria-hidden="true"></i>&nbsp;&nbsp;Upgrade
                                                                                Plan</a>
                                                                        </li>
                                                                    <?php

                                                                    } else {
                                                                        ?>
                                                                        <li>
                                                                            <a href="plan-selection.php?user_id=<?php echo $user_id2 ?>">
                                                                                <i class="fa fa-money"
                                                                                   aria-hidden="true"></i>&nbsp;&nbsp;Upgrade
                                                                                Plan</a>
                                                                        </li>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                    <li>
                                                                        <a href="view_all_customer.php?delete_user=<?php echo $user_id2 ?>&action=delete&all_customer=<?php echo $_GET['all_customer']; ?>&email=<?php echo $email; ?>">
                                                                            <i class="fa fa-trash"
                                                                               aria-hidden="true"></i>&nbsp;&nbsp;Delete</a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="view_all_customer.php?update_user=<?php echo $user_id2 ?>&action=update&all_customer=<?php echo $_GET['all_customer']; ?>&email=<?php echo $email; ?>">
                                                                            <i class="fa fa-trash"
                                                                               aria-hidden="true"></i>&nbsp;&nbsp;Update
                                                                            Password</a>
                                                                    </li>
                                                                <?php } ?>
                                                            </ul>
                                                        </li>
                                                    </ul>
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
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <?php /*if (isset($alreadySaved) && $alreadySaved) {
                    */ ?><!--
                    <h4 class="modal-title">Update UPI Details</h4>
                    <?php
                /*                } else {
                                    */ ?>
                    <h4 class="modal-title">Add UPI Details</h4>
                    --><?php
                /*                }
                                */ ?>
            </div>
            <div class="modal-body">
                <div class="body">

                    <div class="text-center">
                        <img src="assets/images/access-denied.png" style="padding-bottom: 15px;">

                        <p><b>User of this digital card have revoke your login access, please contact user</b></p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myWebsiteColorModal" role="dialog">
    <div class="modal-dialog cust-model-width">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header cust-upi-madal">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Update Website Color</h4>
            </div>
            <div class="modal-body">
                <div class="body">
                    <form id="upi_form_validation" method="POST" action="">
                        <input type="hidden" name="d_user_id" class="d_user_id">

                        <div>
                            <label class="form-label">Enter Theme Color</label>

                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input name="txt_theme_color" class="form-control txt_theme_color"
                                           placeholder="Enter Theme Color">
                                </div>
                            </div>
                        </div>
                        <div class="form-group form_inline form-float">
                            <button class="btn btn-primary waves-effect form-control"
                                    name="btn_website_color" type="submit">
                                Update Website Color
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myWebsiteThemeModal" role="dialog">
    <div class="modal-dialog cust-model-width">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header cust-upi-madal">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Update Website Theme</h4>
            </div>
            <div class="modal-body">
                <div class="body">
                    <form id="upi_form_validation" method="POST" action="">
                        <input type="hidden" name="d_user_id" class="d_user_id">

                        <div>
                            <label class="form-label">Select Website Theme</label>

                            <div class="form-group form-float">
                                <select class="form-control show-tick" name="drp_website_theme" id="drp_website_theme">
                                    <option disabled>Select Theme Name</option>
                                    <option value="theme1" <?php if ($website_theme == "theme1") echo "selected"; ?> >
                                        Theme-1
                                    </option>
                                    <option value="theme2" <?php if ($website_theme == "theme2") echo "selected"; ?> >
                                        Theme-2
                                    </option>
                                    <option value="theme3" <?php if ($website_theme == "theme3") echo "selected"; ?> >
                                        Theme-3
                                    </option>
                                    <option value="theme4" <?php if ($website_theme == "theme4") echo "selected"; ?> >
                                        Theme-4
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group form_inline form-float">
                            <button class="btn btn-primary waves-effect form-control"
                                    name="btn_website_theme" type="submit">
                                Update Website Theme
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myWebsiteLogoModal" role="dialog">
    <div class="modal-dialog cust-model-width">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header cust-upi-madal">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Adjust Website Logo width</h4>
            </div>
            <div class="modal-body">
                <div class="body">
                    <form id="upi_form_validation" method="POST" action="">
                        <input type="hidden" name="d_user_id" class="d_user_id">

                        <div>
                            <label class="form-label">Enter logo width</label>

                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input name="txt_logo" class="form-control txt_logo"
                                           placeholder="Enter Website Logo Width">
                                </div>
                                <small id="emailHelp" class="form-text text-muted">Logo width always in percentage (%)
                                </small>
                            </div>
                        </div>
                        <div class="form-group form_inline form-float">
                            <button class="btn btn-primary waves-effect form-control"
                                    name="btn_website_logo_change" type="submit">
                                Update Website logo change
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function copyClipboard(value) {
        var tempInput = document.createElement("input");
        tempInput.style = "position: absolute; left: -1000px; top: -1000px";
        tempInput.value = value;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand("copy");
        document.body.removeChild(tempInput);
        if ("copy") {
            var x = document.getElementById("snackbar");
            x.className = "show";
            setTimeout(function () {
                x.className = x.className.replace("show", "");
            }, 3000);
        }
    }
</script>
<?php include "assets/common-includes/footer_includes.php" ?>
<script src="https://rawcdn.githack.com/FuriosoJack/TableHTMLExport/v2.0.0/src/tableHTMLExport.js"></script>

<!--<script src="https://rawgit.com/unconditional/jquery-table2excel/master/src/jquery.table2excel.js"></script>-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/TableExport/5.2.0/js/tableexport.min.js" integrity="sha512-XmZS54be9JGMZjf+zk61JZaLZyjTRgs41JLSmx5QlIP5F+sSGIyzD2eJyxD4K6kGGr7AsVhaitzZ2WTfzpsQzg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>-->
<!--<script>-->
<!--    function myFunction1(count, text) {-->
<!--        console.log(count);-->
<!--        var copyText = document.getElementById("myInput01" + count);-->
<!--        copyText.select();-->
<!--        copyText.setSelectionRange(0, 99999);-->
<!--        document.execCommand("copy");-->
<!---->
<!--        var tooltip = document.getElementById("myTooltip01" + count);-->
<!--        tooltip.innerHTML = text + " Copied";-->
<!--    }-->
<!---->
<!--    function outFunc1(count) {-->
<!--        var tooltip = document.getElementById("myTooltip01" + count);-->
<!--        tooltip.innerHTML = "Copy Custom Url";-->
<!--    }-->
<!--    function myFunction2(count, text) {-->
<!--        console.log(count);-->
<!--        var copyText = document.getElementById("myInput02" + count);-->
<!--        copyText.select();-->
<!--        copyText.setSelectionRange(0, 99999);-->
<!--        document.execCommand("copy");-->
<!---->
<!--        var tooltip = document.getElementById("myTooltip02" + count);-->
<!--        tooltip.innerHTML = text + " Copied";-->
<!--    }-->
<!---->
<!--    function outFunc2(count) {-->
<!--        var tooltip = document.getElementById("myTooltip02" + count);-->
<!--        tooltip.innerHTML = "Copy Feedback";-->
<!--    }-->
<!--    function myFunction3(count, text) {-->
<!--        console.log(count);-->
<!--        var copyText = document.getElementById("myInput03" + count);-->
<!--        copyText.select();-->
<!--        copyText.setSelectionRange(0, 99999);-->
<!--        document.execCommand("copy");-->
<!---->
<!--        var tooltip = document.getElementById("myTooltip03" + count);-->
<!--        tooltip.innerHTML = text + " Copied";-->
<!--    }-->
<!---->
<!--    function outFunc3(count) {-->
<!--        var tooltip = document.getElementById("myTooltip03" + count);-->
<!--        tooltip.innerHTML = "Copy Custom Url";-->
<!--    }-->
<!--    function myFunction4(count, text) {-->
<!--        console.log(count);-->
<!--        var copyText = document.getElementById("myInput04" + count);-->
<!--        copyText.select();-->
<!--        copyText.setSelectionRange(0, 99999);-->
<!--        document.execCommand("copy");-->
<!---->
<!--        var tooltip = document.getElementById("myTooltip04" + count);-->
<!--        tooltip.innerHTML = text + " Copied";-->
<!--    }-->
<!---->
<!--    function outFunc4(count) {-->
<!--        var tooltip = document.getElementById("myTooltip04" + count);-->
<!--        tooltip.innerHTML = "Copy Feedback";-->
<!--    }-->
<!---->
<!---->
<!--</script>-->
<script>
    $(document).ready(function () {
        // Configure/customize these variables.
        var showChar = 100;  // How many characters are shown by default
        var ellipsestext = "...";
        var moretext = "Show more >";
        var lesstext = "Show less";


        $('.more').each(function () {
            var content = $(this).html();

            if (content.length > showChar) {

                var c = content.substr(0, showChar);
                var h = content.substr(showChar, content.length - showChar);

                var html = c + '<span class="moreellipses">' + ellipsestext + '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';

                $(this).html(html);
            }

        });

        $(".morelink").click(function () {
            if ($(this).hasClass("less")) {
                $(this).removeClass("less");
                $(this).html(moretext);
            } else {
                $(this).addClass("less");
                $(this).html(lesstext);
            }
            $(this).parent().prev().toggle();
            $(this).prev().toggle();
            return false;
        });
    });

    function openColorModal(id, color) {
        console.log(color);
        $('.d_user_id').val(id);
        $('.txt_theme_color').val(color);
        $('#myWebsiteColorModal').modal('show');
    }

    function openThemeChangeModal(id, themename) {
        $('.d_user_id').val(id);
        $('#drp_website_theme').val(themename);
        $('#myWebsiteThemeModal').modal('show');
    }
    function openThemeChangeLogoModal(id, logowidth) {
        $('.d_user_id').val(id);
        $('.txt_logo').val(logowidth);
        $('#myWebsiteLogoModal').modal('show');
    }

    /*$("#btn-export").click(function () {
     $("#dtHorizontalVerticalExample").table2excel({
     exclude: ".noExl",
     name: "Merchandising Report",
     filename: "merchReportTable"
     });
     });*/

    $("#btn-export").click(function () {
        $("#dtHorizontalVerticalExample").tableHTMLExport({
            type: 'csv',
            filename: 'Inactive_customer.csv',
            ignoreColumns: '.noExl',
            ignoreRows: '#ultimo'
        });
    });


</script>
</body>
</html>
