<?php
error_reporting(0);
ob_start();

include "../controller/ManageUser.php";
$manage = new ManageUser();
include "../controller/validator.php";
$validate = new Validator();
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include '../sendMail/sendMail.php';
$controller = new Controller();
$con = $controller->connect();


$alreadySaved = false;
$alreadyPayPal = false;
$alreadySavedBank = false;
$section_id = 7;
if (isset($_SESSION['dealer_login_type']) && $_SESSION['dealer_login_type'] != "") {
    $previous_page = basename($_SERVER['HTTP_REFERER']);
    if ($previous_page == "") {
        $previous_page = "basic-user-info.php";
    }
    header('location:' . $previous_page . '?show_payment_modal=true');
}
$by = "by user.";
$alreadySavedVideo = false;
$section_video_id = 8;


include("android-login.php");

$error = false;
$errorMessage = "";

$error1 = false;
$errorMessage1 = "";
$error2 = false;
$errorMessage2 = "";

$id = 0;
include("session_includes.php");

include "validate-page.php";

if ($id != 0) {
    $get_data = $manage->countGateway($id);
    if ($get_data) {
        $alreadySaved = true;
        $form_data = $manage->getGatewayPaymentDetails($id);
        $upi_id = $form_data['upi_id'];
        $upi_mobile_no = $form_data['upi_mobile_no'];

    }
}
if ($id != 0) {
$get_count_paypal = $manage->countPayPal($id);
if ($get_count_paypal !=null) {
    $alreadyPayPal = true;
        $paypal_email = $get_count_paypal['paypal_email'];
        $paypal_link = $get_count_paypal['paypal_link'];
}
}

$get_status = $manage->displayBankDetails();
if ($get_status != null) {
    $countForVideo = mysqli_num_rows($get_status);
} else {
    $countForVideo = 0;
}
if (isset($_POST['btn_add'])) {
    if (isset($_POST['txt_name']) && $_POST['txt_name'] != "") {
        $name = mysqli_real_escape_string($con, $_POST['txt_name']);
    } else {
        $error = true;
        $errorMessage .= "Please enter name.<br>";
    }
    if (isset($_POST['txt_bank_name']) && $_POST['txt_bank_name'] != "") {
        $bank_name = mysqli_real_escape_string($con, $_POST['txt_bank_name']);
    } else {
        $error = true;
        $errorMessage .= "Please bank name.<br>";
    }
    if (isset($_POST['txt_account']) && $_POST['txt_account'] != "") {
        $account = mysqli_real_escape_string($con, $_POST['txt_account']);
    } else {
        $error = true;
        $errorMessage .= "Please account number.<br>";
    }
    if (isset($_POST['txt_ifsc']) && $_POST['txt_ifsc'] != "") {
        $txt_ifsc = mysqli_real_escape_string($con, $_POST['txt_ifsc']);
    } else {
        $error = true;
        $errorMessage .= "Please IFSC code.<br>";
    }

    if (!$error) {
        $accountDetailsCount = $manage->getAccountDetailsCount();
        if ($accountDetailsCount == 0) {
            $defaultStatus = 1;
        } else {
            $defaultStatus = 0;
        }
        $status = $manage->addBankDetails($security->encrypt($name), $security->encrypt($bank_name), $security->encrypt($account), $security->encrypt($txt_ifsc), $defaultStatus);
        if ($status) {
            $page_name = $_SESSION['menu']['s_bank'];
            $action = "Add";

            $remark = $_POST['txt_bank_name'] . " has been added " . $by;
            $insertLog = $manage->insertUserLogData($page_name, $action, $remark);
            $_SESSION['red_dot']['bank_name'] = false;
            if ($countForVideo == 0) {
                $_SESSION['total_percent'] = $_SESSION['total_percent'] + 10;
            }
            $get_status = $manage->displayBankDetails();
            if ($get_status != null) {
                $countForVideo = mysqli_num_rows($get_status);
            } else {
                $countForVideo = 0;
            }
            $name = "";
            $bank_name = "";
            $account = "";
            $txt_ifsc = "";
            $error = false;
            $errorMessage .= $_SESSION['menu']['s_bank'] . " added successfully.";
        } else {
            echo "could not connect";
        }


    }

}

/*This is for edit*/
if (isset($_GET['edit_bank'])) {
    $edit_bank = $security->decrypt($_GET['edit_bank']);
    $form_data = $manage->getBankDetails($edit_bank);
    $name = $form_data['name'];
    $bank_name = $form_data['bank_name'];
    $account_number = $form_data['account_number'];
    $ifsc_code = $form_data['ifsc_code'];
}


if (isset($_POST['btn_update'])) {
    $message_data = "<br>";

    if (isset($_POST['txt_name']) && $_POST['txt_name'] != "") {
        if ($_POST['txt_name'] != $security->decrypt($name)) {
            $message_data .= $security->decrypt($name) . " TO " . $_POST['txt_name'] . ",<br>";
        }
        $name = mysqli_real_escape_string($con, $_POST['txt_name']);
    } else {
        $error = true;
        $errorMessage .= "Please enter name.<br>";
    }
    if (isset($_POST['txt_bank_name']) && $_POST['txt_bank_name'] != "") {
        if ($_POST['txt_bank_name'] != $security->decrypt($bank_name)) {
            $message_data .= $security->decrypt($bank_name) . " TO " . $_POST['txt_bank_name'] . ",<br>";
        }
        $bank_name = mysqli_real_escape_string($con, $_POST['txt_bank_name']);
    } else {
        $error = true;
        $errorMessage .= "Please bank name.<br>";
    }
    if (isset($_POST['txt_account']) && $_POST['txt_account'] != "") {
        if ($_POST['txt_account'] != $security->decrypt($account_number)) {
            $message_data .= $security->decrypt($account_number) . " TO " . $_POST['txt_account'] . ",<br>";
        }

        $account = mysqli_real_escape_string($con, $_POST['txt_account']);
    } else {
        $error = true;
        $errorMessage .= "Please account number.<br>";
    }
    if (isset($_POST['txt_ifsc']) && $_POST['txt_ifsc'] != "") {
        if ($_POST['txt_ifsc'] != $security->decrypt($ifsc_code)) {
            $message_data .= $security->decrypt($ifsc_code) . " TO " . $_POST['txt_ifsc'] . ",<br>";
        }
        $txt_ifsc = mysqli_real_escape_string($con, $_POST['txt_ifsc']);
    } else {
        $error = true;
        $errorMessage .= "Please IFSC code.<br>";
    }

    if (!$error) {
        $status = $manage->updateBankDetails($security->encrypt($name), $security->encrypt($bank_name), $security->encrypt($account), $security->encrypt($txt_ifsc), $security->decrypt($_GET['edit_bank']));
        if ($status) {
            $page_name = $_SESSION['menu']['s_bank'];
            $action = "Update";
            $remark = $_SESSION['menu']['s_bank'] . " has been updated " . $by;
            $insertLog = $manage->insertUserLogData($page_name, $action, $remark . $message_data);
            $error = false;
            $errorMessage .= "Detail Updated Successfully.";
            if ($android_url != "") {
                header('location:payment.php?' . $android_url);
            } else {
                header('location:payment.php');
            }
        } else {
            $error = true;
            $errorMessage = "Issue while updating details, Please try again.";
        }
    }

}


if (isset($_GET['publishData']) && (isset($_GET['action']))) {
    $action = $_GET['action'];
    $publishData = $security->decrypt($_GET['publishData']);
    $page_name = $_SESSION['menu']['s_bank'];
    if ($action == "unpublish") {
        $result = $manage->publishUnpublish($publishData, 0, $manage->bankDetailsTable);
        $action = "Update";

        $remark = "Bank Name : " . urldecode($_GET['bank_name']) . " has set to unpublish " . $by;
        $insertLog = $manage->insertUserLogData($page_name, $action, $remark);
    } else {
        $result = $manage->publishUnpublish($publishData, 1, $manage->bankDetailsTable);

        $action = "Update";

        $remark = "Bank Name : " . urldecode($_GET['bank_name']) . " has set to published " . $by;
        $insertLog = $manage->insertUserLogData($page_name, $action, $remark);
    }
    if ($android_url != "") {
        header('location:payment.php?' . $android_url);
    } else {
        header('location:payment.php');
    }
}

if (isset($_GET['delete_data'])) {
    $delete_data = $security->decrypt($_GET['delete_data']);
    $status = $manage->deleteBank($delete_data);
    if ($status) {
        $page_name = $_SESSION['menu']['s_bank'];
        $action = "Delete";
        $remark = "Bank Name : " . urldecode($_GET['bank_name']) . "  has been deleted " . $by;
        $insertLog = $manage->insertUserLogData($page_name, $action, $remark);
        $get_status = $manage->displayBankDetails();
        if ($get_status != null) {
            $countForVideo = mysqli_num_rows($get_status);
        } else {
            $countForVideo = 0;
        }
        if ($countForVideo == 0) {
            $_SESSION['total_percent'] = $_SESSION['total_percent'] - 10;
        }
        if ($android_url != "") {
            header('location:payment.php?' . $android_url);
        } else {
            header('location:payment.php');
        }
    }
}


/*End of Edit*/


if ($countForVideo == 0) {
    $_SESSION['red_dot']['bank_name'] = true;
}

if (isset($_POST['btn_save_upi'])) {
    if (isset($_POST['txt_upi_id']) && $_POST['txt_upi_id'] != "") {
        $txt_upi_id = $_POST['txt_upi_id'];
    } else {
        $error1 = true;
        $errorMessage1 .= "Please enter upi id.<br>";
    }
    if (isset($_POST['txt_upi_number']) && $_POST['txt_upi_number'] != "") {
        $txt_upi_number = $_POST['txt_upi_number'];
    } else {
        $error1 = true;
        $errorMessage1 .= "Please enter upi number.<br>";
    }
    if (!$error1) {
        $status = $manage->addGatewayDetails($_POST['txt_upi_id'], $txt_upi_number);
        if ($status) {
            $page_name = $_SESSION['menu']['s_bank'];
            $action = "Add";

            $remark = "UPI Id has been added " . $by;
            $insertLog = $manage->insertUserLogData($page_name, $action, $remark);
            $_SESSION['red_dot']['upi_id'] = false;
            $_SESSION['total_percent'] = $_SESSION['total_percent'] + 10;
            $error1 = false;
            $errorMessage1 .= "Detail Added Successfully.";
            if ($android_url != "") {
                header('location:payment.php?' . $android_url);
            } else {
                header('location:payment.php');
            }
        } else {
            $error = true;
            $errorMessage = "Issue while adding details, Please try again.";
        }


    }

}
if (isset($_POST['btn_update_upi'])) {
    if (isset($_POST['txt_upi_id']) && $_POST['txt_upi_id'] != "") {
        $txt_upi_id = $_POST['txt_upi_id'];
    } else {
        $error1 = true;
        $errorMessage1 .= "Please enter upi id.<br>";
    }
    if (isset($_POST['txt_upi_number']) && $_POST['txt_upi_number'] != "") {
        $txt_upi_number = $_POST['txt_upi_number'];
    } else {
        $error1 = true;
        $errorMessage1 .= "Please enter upi number.<br>";
    }
    if (!$error1) {
        $status = $manage->updatePaymentGateway($_POST['txt_upi_id'], $txt_upi_number, $id);
        if ($status) {
            $page_name = $_SESSION['menu']['s_bank'];
            $action = "Updated";
            $upi_message_data = "<br>";
            if ($txt_upi_id != $upi_id) {
                $upi_message_data .= $upi_id . " TO " . $txt_upi_id . ",<br>";
            }
            if ($txt_upi_number != $upi_mobile_no) {
                $upi_message_data .= $upi_mobile_no . " TO " . $txt_upi_number . "";
            }
            $remark = "UPI Id has been updated " . $by;
            $insertLog = $manage->insertUserLogData($page_name, $action, $remark . $upi_message_data);
            $error1 = false;
            $errorMessage1 .= "Detail updated Successfully.";
            if ($android_url != "") {
                header('location:payment.php?' . $android_url);
            } else {
                header('location:payment.php');
            }
        } else {
            $error1 = true;
            $errorMessage1 = "Issue while updating details, Please try again.";
        }
    }

}
if (isset($_POST['btn_save_paypal'])) {
    if (isset($_POST['txt_email']) && $_POST['txt_email'] != "") {
        $txt_email = $_POST['txt_email'];
    } else {
        $error2 = true;
        $errorMessage2 .= "Please enter paypal email id.<br>";
    }

    if (!$error2) {
        $status = $manage->addPayPalDetails($txt_email, $_POST['txt_link']);
        if ($status) {
            $page_name = $_SESSION['menu']['s_bank'];
            $action = "Add";
            $remark = "PayPal details has been added " . $by;
            $insertLog = $manage->insertUserLogData($page_name, $action, $remark);
            $_SESSION['red_dot']['upi_id'] = false;
            $_SESSION['total_percent'] = $_SESSION['total_percent'] + 10;
            $error1 = false;
            $errorMessage1 .= "Detail Added Successfully.";
            if ($android_url != "") {
                header('location:payment.php?' . $android_url);
            } else {
                header('location:payment.php');
            }
        } else {
            $error = true;
            $errorMessage = "Issue while adding details, Please try again.";
        }
    }

}


if (isset($_POST['btn_update_paypal'])) {
    if (isset($_POST['txt_email']) && $_POST['txt_email'] != "") {
        $txt_email = $_POST['txt_email'];
    } else {
        $error2 = true;
        $errorMessage2 .= "Please enter paypal email id.<br>";
    }
    if (!$error2) {
        $status = $manage->mu_updatePaymentPayPal($txt_email, $_POST['txt_link'], $id);
        if ($status) {
            $page_name = $_SESSION['menu']['s_bank'];
            $action = "Updated";
            $upi_message_data = "<br>";
            if ($txt_email != $paypal_email) {
                $upi_message_data .= $paypal_email . " TO " . $txt_email . ",<br>";
            }
            if (isset($_POST['txt_link']) && $_POST['txt_link'] != $paypal_link) {
                $upi_message_data .= $paypal_link . " TO " . $_POST['txt_link']  . "";
            }
            $remark = "PayPal has been updated " . $by;
            $insertLog = $manage->insertUserLogData($page_name, $action, $remark . $upi_message_data);
            $error1 = false;
            $errorMessage1 .= "Detail updated Successfully.";
            if ($android_url != "") {
                header('location:payment.php?' . $android_url);
            } else {
                header('location:payment.php');
            }
        } else {
            $error1 = true;
            $errorMessage1 = "Issue while updating details, Please try again.";
        }
    }

}

/*This is for image gallery*/

$get_bank_data = $manage->countService($id, $section_id);
if ($get_bank_data) {
    $alreadySavedBank = true;
    $display_bank_result = $manage->getServiceStatus($id, $section_id);
    /*$array = explode(",",$statusOnOFF);*/
}

if (isset($_POST['update_chk'])) {

    $digital_card_status = 0;
    $website_status = 0;

    if (isset($_POST['type'])) {
        $type = $_POST['type'];

        if (isset($type[0]) && $type[0] == "digital_card" || isset($type[0]) && $type[0] == "digital_card") {
            $digital_card_status = 1;
        } else {
            $digital_card_status = 0;
        }

        if (isset($type[0]) && $type[0] == "website" || isset($type[1]) && $type[1] == "website") {
            $website_status = 1;
        } else {
            $website_status = 0;
        }
    }

    $result = $manage->updateSectionStatus($id, $section_id, $website_status, $digital_card_status);
    if ($result) {
        if ($android_url != "") {
            header('location:payment.php?' . $android_url);
        } else {
            header('location:payment.php');
        }
    }
}


/*This is for video gallery*/

$get_video_data = $manage->countService($id, $section_video_id);
if ($get_video_data) {
    $alreadySavedVideo = true;
    $display_video_result = $manage->getServiceStatus($id, $section_video_id);
}

if (isset($_POST['update_video_chk'])) {

    $digital_card_video_status = 0;
    $website_video_status = 0;

    if (isset($_POST['video_type'])) {
        $video_type = $_POST['video_type'];

        if (isset($video_type[0]) && $video_type[0] == "digital_card" || isset($video_type[0]) && $video_type[0] == "digital_card") {
            $digital_card_video_status = 1;
        } else {
            $digital_card_video_status = 0;
        }

        if (isset($video_type[0]) && $video_type[0] == "website" || isset($video_type[1]) && $video_type[1] == "website") {
            $website_video_status = 1;
        } else {
            $website_video_status = 0;
        }
    }

    $video_result = $manage->updateSectionStatus($id, $section_video_id, $website_video_status, $digital_card_video_status);
    if ($video_result) {
        if ($android_url != "") {
            header('location:payment.php?' . $android_url);
        } else {
            header('location:payment.php');
        }
    }
}


if (isset($_GET['default_user_id'])) {
    $default_id = $_GET['default_user_id'];
    $get_count_default = $manage->getAccountDetailsCount();
    if ($get_count_default == 1) {
        $get_default = $manage->updateDefaultBank($default_id);
        if ($get_default) {
            $updateDefault = $manage->updateDefaultBankStatus($default_id);
            if ($updateDefault) {
                if ($android_url != "") {
                    header('location:payment.php?' . $android_url);
                } else {
                    header('location:payment.php');
                }
            }
        }
    }
}
if (isset($alreadySaved) && $alreadySaved) {
    $_SESSION['red_dot']['upi_id'] = false;
}
?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php include "assets/common-includes/total_count.php" ?>
    <title><?php echo $_SESSION['menu']['s_bank'] ?></title>
    <?php include "assets/common-includes/header_includes.php" ?>
    <style>
    .error-message {
      color: red;
    }
  </style>
</head>
<body>
<?php
if (!isset($_GET['android_user_id']) && (!isset($_GET['type']) && $_GET['type'] != "android") && (!isset($_GET['api_key']))) {
?>
<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>
<section class="content">
    <?php
    if (isset($_SESSION['create_user_status']) && $_SESSION['create_user_status'] == true) {
        include "assets/common-includes/session_button_includes.php";
    }
    ?>
    <?php include "assets/common-includes/preview.php" ?>
    <div class="up-nav visible-lg visible-md visible-sm hidden-xs">
        <?php include 'assets/common-includes/menu_bar_include.php' ?>
    </div>
    <?php
    }elseif (isset($_GET['android_user_id']) && (isset($_GET['type']) && $_GET['type'] == "android") && (isset($_GET['api_key']))) {
    ?>
    <section class="androidSection">
        <?php
        }
        ?>
        <div class="clearfix">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding_zero padding_zero_both"
                 style="position: relative">
                <div class="row">
                    <div class="card cardupi">
                        <div class="upi-head">
                            <h2>
                                Configure Your Wallet
                                <label class="label label-success find_upi_font" style="cursor: pointer"
                                       data-toggle="modal"
                                       data-target="#findUPI">How to find your UPI Id</label>
                            </h2>

                            <p>Please Check For Your UPI Details And Fill This form</p>
                        </div>


                        <ul class="upi-pay">
                            <li>
                                <div class="upi-img-div"><img class="img-circle" src="assets/images/gpay.png"></div>
                            </li>
                            <li>
                                <div class="upi-img-div"><img class="img-circle" src="assets/images/paytm-512.png">
                                </div>
                            </li>
                            <li>
                                <div class="upi-img-div"><img class="img-circle"
                                                              src="assets/images/PhonePe-off-campus-drive.png"></div>
                            </li>


                        </ul>
                        <div class="upi-btn-div">
                            <button class="btn btn-primary upi-btn waves-effect" name="upi-more"
                                    type="submit" data-toggle="modal" data-target="#myModal">
                                <?php if (isset($alreadySaved) && $alreadySaved) {
                                    ?>
                                    MODIFY UPI ID
                                    <?php
                                } else {
                                    ?>
                                    ADD UPI ID
                                    <?php
                                }
                                ?>
                            </button>
                            <div class="modal fade" id="myModal" role="dialog">
                                <div class="modal-dialog cust-model-width">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header cust-upi-madal">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <?php if (isset($alreadySaved) && $alreadySaved) {
                                                ?>
                                                <h4 class="modal-title">Update UPI Details</h4>
                                                <?php
                                            } else {
                                                ?>
                                                <h4 class="modal-title">Add UPI Details</h4>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                        <div class="modal-body padding_zero_model">
                                            <div class="body">
                                                <form id="upi_form_validation" method="POST" action="" onsubmit="return validateForm()">
                                                    <?php if ($error1) {
                                                        ?>
                                                        <div class="alert alert-danger">
                                                            <?php if (isset($errorMessage1)) echo $errorMessage1; ?>
                                                        </div>
                                                        <?php
                                                    } else if (!$error1 && $errorMessage1 != "") {
                                                        ?>
                                                        <div class="alert alert-success">
                                                            <?php if (isset($errorMessage1)) echo $errorMessage1; ?>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                    <div>
                                                        <label class="form-label">UPI Id</label>
                                                        <div class="form-group form-float">
                                                            <div class="form-line">
                                                                <input name="txt_upi_id" class="form-control" id="upiId" placeholder="UPI Id" value="<?php if (isset($upi_id)) echo $upi_id; ?>">
                                                                <p id="message1" class="error-message"></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label class="form-label">UPI Linked Mobile Number</label>
                                                        <div class="form-group form-float">
                                                            <div class="form-line">
                                                                <input name="txt_upi_number" type="number" id="txt_upi_number" class="form-control" min='10' placeholder="UPI Linked Mobile Number" onkeypress="return isNumberKey(event)" value="<?php if (isset($upi_mobile_no)) echo $upi_mobile_no; ?>">
                                                                <p id="message2" class="error-message"></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form_inline form-float">
                                                        <?php if (isset($alreadySaved) && $alreadySaved) {
                                                            ?>
                                                            <button class="btn btn-primary waves-effect form-control"
                                                                    name="btn_update_upi"
                                                                    type="submit">
                                                                Update
                                                            </button>
                                                            <?php
                                                        } else {

                                                            ?>
                                                            <button class="btn btn-primary waves-effect form-control"
                                                                    name="btn_save_upi"
                                                                    type="submit">
                                                                Add
                                                            </button>
                                                            <?php
                                                        }
                                                        ?>
                                                        &nbsp;&nbsp;
                                                        <!--<div>
                                                            <button class="btn btn-default" type="reset">
                                                                Reset
                                                            </button>
                                                        </div>-->
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding_zero padding_zero_both"
                 style="position: relative">
                <div class="">
                    <div class="card cardupi">
                        <div class="upi-head">
                            <h2>
                                Configure Your PayPal Account
                                <!-- <label class="label label-success find_upi_font" style="cursor: pointer"
                                        data-toggle="modal"
                                        data-target="#findUPI">How to find your UPI Id</label>-->
                            </h2>

                            <p>Please Check For Your PayPal Details And Fill This form</p>
                        </div>


                        <ul class="upi-pay">
                            <li>
                                <div class="upi-img-div"><img class="img-circle" src="assets/images/paypal.png"></div>
                            </li>
                        </ul>
                        <div class="upi-btn-div">
                            <button class="btn btn-primary upi-btn waves-effect" name="pay-more"
                                    type="submit" data-toggle="modal" data-target="#myPayPalModal">
                                <?php if (isset($alreadyPayPal) && $alreadyPayPal) {
                                    ?>
                                    Modify PayPal
                                    <?php
                                } else {
                                    ?>
                                    Add PayPal
                                    <?php
                                }
                                ?>
                            </button>
                            <div class="modal fade" id="myPayPalModal" role="dialog">
                                <div class="modal-dialog cust-model-width">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header cust-upi-madal">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <?php if (isset($alreadyPayPal) && $alreadyPayPal) {
                                                ?>
                                                <h4 class="modal-title">Update PayPal Details</h4>
                                                <?php
                                            } else {
                                                ?>
                                                <h4 class="modal-title">Add PayPal Details</h4>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                        <div class="modal-body padding_zero_model">
                                            <div class="body">
                                                <form id="upi_form_validation" method="POST" action="">
                                                    <?php if ($error2) {
                                                        ?>
                                                        <div class="alert alert-danger">
                                                            <?php if (isset($errorMessage2)) echo $errorMessage2; ?>
                                                        </div>
                                                        <?php
                                                    } else if (!$error2 && $errorMessage2 != "") {
                                                        ?>
                                                        <div class="alert alert-success">
                                                            <?php if (isset($errorMessage2)) echo $errorMessage2; ?>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                    <div>
                                                        <label class="form-label">PayPal Registered Email</label>

                                                        <div class="form-group form-float">
                                                            <div class="form-line">
                                                                <input name="txt_email" class="form-control" type="email"
                                                                       placeholder="Enter PayPal Registered Email"
                                                                       value="<?php if (isset($paypal_email)) echo $paypal_email; ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label class="form-label">Payme link</label> (Optional)

                                                        <div class="form-group form-float">
                                                            <div class="form-line">
                                                                <input name="txt_link" class="form-control" placeholder="Enter Payme link" value="<?php if (isset($paypal_link)) echo $paypal_link; ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form_inline form-float">
                                                        <?php if (isset($alreadyPayPal) && $alreadyPayPal) {
                                                            ?>
                                                            <button class="btn btn-primary waves-effect form-control"
                                                                    name="btn_update_paypal"
                                                                    type="submit">
                                                                Update
                                                            </button>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <button class="btn btn-primary waves-effect form-control"
                                                                    name="btn_save_paypal"
                                                                    type="submit">
                                                                Add
                                                            </button>
                                                            <?php
                                                        }
                                                        ?>
                                                        &nbsp;&nbsp;
                                                        <!--<div>
                                                            <button class="btn btn-default" type="reset">
                                                                Reset
                                                            </button>
                                                        </div>-->
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix ">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding_zero padding_zero_both">
                <div class="row">
                    <div class="card">
                        <div class="header">
                            <div class="row cust-row">
                                <?php if (isset($_GET['edit_bank'])) { ?>
                                    <div class="col-lg-7"><h2>
                                            Update <?php echo $_SESSION['menu']['s_bank'] ?>
                                        </h2></div>
                                <?php } else { ?>
                                    <div class="col-lg-7"><h2>
                                            Adds <?php echo $_SESSION['menu']['s_bank'] ?>
                                        </h2></div>
                                <?php } ?>

                            </div>
                        </div>
                        <div class="body">
                            <form id="form_validation" method="POST" action="" enctype="multipart/form-data">
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
                                <div>
                                    <label class="form-label">IFSC Code</label>

                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="txt_ifsc" class="form-control"
                                                   placeholder="IFSC Code"
                                                   value="<?php if (isset($ifsc_code)) echo htmlspecialchars($security->decrypt($ifsc_code)); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="form-label">Account Number</label>

                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="number" name="txt_account" class="form-control"
                                                   placeholder="Account Number"
                                                   value="<?php if (isset($account_number)) echo $security->decrypt($account_number); ?>">
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="form-label">Bank Name</label>

                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input name="txt_bank_name" class="form-control"
                                                   placeholder="Bank Name"
                                                   value="<?php if (isset($bank_name)) echo $security->decrypt($bank_name); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="form-label">Name</label>

                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input name="txt_name" class="form-control"
                                                   placeholder="Name"
                                                   value="<?php if (isset($name)) echo $security->decrypt($name); ?>">
                                        </div>
                                    </div>
                                </div>
                                <!--      <div>
                                <label class="form-label">Branch </label>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input name="txt_branch" class="form-control"
                                               placeholder="Branch Location" value="<?php /*if(isset($branch)) echo $branch; */ ?>">
                                    </div>
                                </div>
                            </div>-->
                                <div>
                                    <div class="form-group form_inline">
                                        <?php if (isset($_GET['edit_bank'])) { ?>
                                            <div>
                                                <input value="Update" type="submit" name="btn_update"
                                                       class="btn btn-primary waves-effect">
                                            </div>
                                        <?php } else { ?>
                                            <div>
                                                <input value="Save" type="submit" name="btn_add"
                                                       class="btn btn-primary waves-effect">
                                            </div>
                                        <?php } ?>
                                        &nbsp;&nbsp;
                                        <div>
                                            <a href="payment.php<?php if ($android_url != "") echo "?" . $android_url; ?>"
                                               class="btn btn-default">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding_zero padding_zero_both">
                <div class="row margin_div_web">
                    <!--<div class="freelancer_search_box" style="width: 100%">
                <div class="col-md-12">
                    <form action="" method="post">
                        <ul class="profile-ul">
                            <h4>Hide bank details from digital card</h4>
                            <li class="li_event">
                                <div class="cust-div">
                                    <input type="checkbox" name="type[]"
                                           value="digital_card"  <?php /*if ($display_bank_result['digital_card'] == '1') {
                                        echo 'checked="checked"';
                                    } */ ?> > Bank details
                                </div>
                            </li>
                            <li>
                                <div class="cust-div">
                                    <input type="checkbox" name="type[]"
                                           value="website" <?php /*if ($display_bank_result['website'] == '1') {
                                        echo 'checked="checked"';
                                    } */ ?>>Website
                                </div>
                            </li>
                            <li class="li_event">
                                <?php /*if (isset($alreadySavedBank) && $alreadySavedBank) {
                                    */ ?>
                                    <button class="btn btn-primary waves-effect" name="update_chk"
                                            type="submit">
                                        Save
                                    </button>
                                <?php
                    /*                                } else {
                                                        */ ?>
                                    <button class="btn btn-primary waves-effect" name="save_chk" type="submit">
                                        Add
                                    </button>
                                <?php
                    /*                                }
                                                    */ ?>
                            </li>
                        </ul>
                    </form>
                </div>
            </div>-->
                    <div class="card">
                        <div class="header">
                            <h2>
                                Manage <?php echo $_SESSION['menu']['s_bank'] ?> <span class="badge"><?php
                                    if (isset($countForVideo)) echo $countForVideo;
                                    ?></span>
                            </h2>
                        </div>
                        <div class="body">
                            <div style="overflow-x: auto">
                                <div id="snackbar1">Bank details on the clipboard, try to paste it!</div>
                                <table id="dtHorizontalVerticalExample"
                                       class="table table-striped table-bordered table-sm "
                                       cellspacing="0"
                                       width="100%">
                                    <thead>
                                    <tr class="back-color">
                                        <th class="hidden-lg hidden-md visible-sm visible-xs">Bank Details</th>
                                        <th class="visible-lg visible-md hidden-sm hidden-xs">IFSC Code</th>
                                        <th class="visible-lg visible-md hidden-sm hidden-xs">Account Number</th>
                                        <th class="visible-lg visible-md hidden-sm hidden-xs">Bank Name</th>
                                        <th class="visible-lg visible-md hidden-sm hidden-xs">Name</th>
                                        <th class="visible-lg visible-md hidden-sm hidden-xs">Status</th>
                                        <th>ACTION</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if ($get_status != null) {
                                        while ($result_data = mysqli_fetch_array($get_status)) {
                                            $default_user_id = $result_data['id'];
                                            $bank_details_content = "IFSC Code: " . $security->decrypt($result_data['ifsc_code']) . " | Account Number: " . $security->decrypt($result_data['account_number']) . " | Bank Name: " . $security->decrypt($result_data['bank_name']) . " | Name: " . $security->decrypt($result_data['name']);
                                            ?>
                                            <tr>
                                                <td class="hidden-lg hidden-md visible-sm visible-xs">
                                                    <b>IFSC Code</b>
                                                    : <?php echo $security->decrypt($result_data['ifsc_code']); ?>
                                                    <br>
                                                    <b>Account Number</b>
                                                    : <?php echo $security->decrypt($result_data['account_number']); ?>
                                                    <br>
                                                    <b>Bank Name</b>
                                                    : <?php echo $security->decrypt($result_data['bank_name']); ?>
                                                    <br>
                                                    <b>Name</b>
                                                    : <?php echo $security->decrypt($result_data['name']); ?>
                                                    <br>
                                                    <b>Status</b> : <label
                                                            class="label <?php if ($result_data['status'] == "0") {
                                                                echo "label-danger";
                                                            } else {
                                                                echo "label-success";
                                                            } ?>"><?php if ($result_data['status'] == 0) {
                                                            echo "Unpublished";
                                                        } else {
                                                            echo "Published";
                                                        } ?></label>
                                                </td>
                                                <td class="visible-lg visible-md hidden-sm hidden-xs"><?php echo $security->decrypt($result_data['ifsc_code']); ?></td>
                                                <td class="visible-lg visible-md hidden-sm hidden-xs"><?php echo $security->decrypt($result_data['account_number']); ?></td>
                                                <td class="visible-lg visible-md hidden-sm hidden-xs"><?php echo $security->decrypt($result_data['bank_name']); ?></td>
                                                <td class="visible-lg visible-md hidden-sm hidden-xs"><?php echo $security->decrypt($result_data['name']); ?>

                                                </td>
                                                <!-- <!--<td><?php /*echo $result_data['branch']; */ ?></td>-->
                                                <td class="visible-lg visible-md hidden-sm hidden-xs"><label
                                                            class="label <?php if ($result_data['status'] == "0") {
                                                                echo "label-danger";
                                                            } else {
                                                                echo "label-success";
                                                            } ?>"><?php if ($result_data['status'] == 0) {
                                                            echo "Unpublished";
                                                        } else {
                                                            echo "Published";
                                                        } ?></label><!--<br>&nbsp;&nbsp;--><?php
                                                    /*                                            if ($result_data['default_bank'] == "0") {
                                                                                                    echo " <form method='post' action=''>
                                                                                                    <a href='payment.php?default_user_id=" . $default_user_id . "'
                                                                                                            class='btn btn primary'>set as default</a>
                                                                                                </form>";
                                                                                                } else {
                                                                                                    echo "<label class='label label-primary'>default selected</label>";
                                                                                                } */ ?>
                                                </td>
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
                                                                    <a href="payment.php?edit_bank=<?php echo $security->encrypt($result_data['id']);
                                                                    if ($android_url != "") echo "&" . $android_url; ?>"
                                                                    <i class="fas fa-edit"></i>&nbsp;&nbsp;Edit</a></li>
                                                                <li>
                                                                <li>
                                                                    <a href="javascript:void(0);"
                                                                       onclick="copyClipbaord('<?php echo $bank_details_content; ?>')"
                                                                    <i class="fas fa-edit"></i>&nbsp;&nbsp;Copy Bank
                                                                    Details</a></li>
                                                                <li>
                                                                <li>
                                                                    <a href="payment.php?delete_data=<?php echo $security->encrypt($result_data['id']) ?>&bank_name=<?php echo urlencode($security->decrypt($result_data['bank_name'])); ?>"
                                                                       onclick="return confirm('Are You sure you want to delete?');"
                                                                    <i
                                                                            class="fas fa-trash-alt"></i>&nbsp;&nbsp;Delete</a>
                                                                </li>
                                                                <li>
                                                                    <a onclick="return confirm('Are You sure you want to <?php echo $result_data['status'] == 0 ? 'publish' : 'unpublish'; ?>?');"
                                                                       href="payment.php?publishData=<?php echo $security->encrypt($result_data['id']) ?>&bank_name=<?php echo urlencode($security->decrypt($result_data['bank_name'])); ?>&action=<?php echo $result_data['status'] == 0 ? "publish" : "unpublish"; ?> "><i
                                                                                class="fas <?php echo $result_data['status'] == 0 ? "fa-upload" : "fa-download"; ?>"></i>&nbsp;&nbsp;<?php echo $result_data['status'] == 1 ? "Unpublish" : "Publish"; ?>
                                                                    </a>
                                                                </li>
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
                                    </tbody>
                                    <?php
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </section>
    <div class="modal fade" id="findUPI" role="dialog">
        <div class="modal-dialog upi_modal_width">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header cust-upi-madal">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">How to find upi id</h4>
                </div>
                <div class="modal-body padding_zero_model">
                    <div class="body">
                        <img src="assets/images/bank-info.jpg" style="width: 100%">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include "assets/common-includes/footer_includes.php" ?>
    <?php
    if($error2) {
        ?>
        <script>
            $('button[name=pay-more]')[0].click();
        </script>
        <?php
    }
    ?>
    <?php
    if($error1) {
        ?>
        <script>
            $('button[name=upi-more]')[0].click();
        </script>
        <?php
    }
    ?>
    <script>

        function validateForm() {
            var isValid = true;

            // Call individual validation functions
            isValid = isValid && validateUPI();
            isValid = isValid && validateMobileNumber();

            // Return combined result
            return isValid;
        }



         function validateUPI() {
            var upiId = document.getElementById('upiId').value;
            // let regex = new RegExp(/^[a-zA-Z0-9.-]{2, 256}@[a-zA-Z][a-zA-Z]{2, 64}$/);
            let regex = new RegExp(/^[\w.-]+@[\w.-]+$/);
            var isValid = regex.test(upiId);

            var messageElement = document.getElementById('message1');
            if (isValid) {
                messageElement.innerHTML = "";
                return true;
            } else {
                messageElement.innerHTML = "Please enter a valid UPI ID";
                return false;
            }
        }
        function validateMobileNumber() {
            // Get the mobile number input value
            var mobileNumber = document.getElementById("txt_upi_number").value;
            var messageElement2 = document.getElementById('message2');
            // Regular expression to match a valid mobile number
            var regex = /^[1-9]\d{9}$/;

            // Check if the mobile number matches the regex pattern
            if (!regex.test(mobileNumber)) {
                // Display an error message or perform any other necessary action
                messageElement2.innerHTML = "Invalid mobile number";
                // Prevent form submission
                return false;
            }
                messageElement2.innerHTML = "";
                // Valid mobile number
                return true;
            }
    </script>
</body>
</html>