<?php
ob_start();
include "../controller/ManageUser.php";
$manage = new ManageUser();
include "../controller/validator.php";
$validate = new Validator();
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include '../sendMail/sendMail.php';

$alreadySaved = false;
$alreadySavedBank = false;
$section_id = 7;


$alreadySavedVideo = false;
$section_video_id = 8;


if (!isset($_SESSION['email'])) {
    header('location:../login.php');
}elseif(isset($_SESSION['email']) && $_SESSION['email'] != "admin@sharedigitalcard.com" && (isset($_SESSION['type']) && $_SESSION['type'] != 'Admin')){
    header('location:../login.php');
}


$error = false;
$errorMessage = "";

$error1 = false;
$errorMessage1 = "";

$id = 0;
include("session_includes.php");
if (isset($_POST['btn_add'])) {
    if (isset($_POST['txt_year']) && $_POST['txt_year'] != "") {
        $year = $_POST['txt_year'];
    } else {
        $error = true;
        $errorMessage .= "Enter Year.<br>";
    }
    /*if (isset($_POST['txt_amount']) && $_POST['txt_amount'] != "") {
        $amount = $_POST['txt_amount'];
    } else {
        $error = true;
        $errorMessage .= "Enter Amount.<br>";
    }*/
    if (!$error) {
        $status = $manage->addSubscription($year,$_POST['txt_amount']);
        if ($status) {
            $error = false;
            $errorMessage .= "Detail Added Successfully.";
            header('location:subscription_module.php');
        } else {
            echo "could not connect";
        }
    }

}

/*This is for edit*/
if (isset($_GET['id'])) {
    $id = $security->decrypt($_GET['id']);
    $form_data = $manage->getSubscriptionDetails($id);
    if($form_data!=null){
        $year = $form_data['year'];
        $amount = $form_data['amt'];
    }

}


if (isset($_POST['btn_update'])){
    if(isset($_GET['id'])){
        $id = $security->decrypt($_GET['id']);
        if (isset($_POST['txt_year']) && $_POST['txt_year'] != "") {
            $year = $_POST['txt_year'];
        } else {
            $error = true;
            $errorMessage .= "Enter Year.<br>";
        }
        if (isset($_POST['txt_amount']) && $_POST['txt_amount'] != "") {
            $amount = $_POST['txt_amount'];
        } else {
            $error = true;
            $errorMessage .= "Enter Amount.<br>";
        }
        if (!$error) {
            $status = $manage->updateSubscription($year,$amount,$id);
            if ($status) {
                $error = false;
                $errorMessage .= "Detail Added Successfully.";
                header('location:subscription_module.php');
            } else {
                echo "could not connect";
            }
        }
    }
}


if (isset($_GET['publishData']) && (isset($_GET['action']))) {
    $action = $_GET['action'];
    $publishData = $security->decrypt($_GET['publishData']);
    if ($action == "unpublish") {
        $result = $manage->publishUnpublish($publishData, 0, $manage->planTable);
    } else {
        $result = $manage->publishUnpublish($publishData, 1, $manage->planTable);
    }
    header('location:subscription_module.php');
}

if (isset($_GET['delete_data'])) {
    $delete_data = $security->decrypt($_GET['delete_data']);
    $status = $manage->deleteSubscription($delete_data);
    if ($status) {
        header('location:subscription_module.php');
    }
}


/*End of Edit*/

$get_status = $manage->displaySubscription();
if ($get_status != null) {
    $countForVideo = mysqli_num_rows($get_status);
} else {
    $countForVideo = 0;
}










?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Subscription module</title>
    <?php include "assets/common-includes/header_includes.php" ?>
</head>
<body>
<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>
<section class="content">
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 ">
        <div class="row">
            <div class="card">
                <div class="header">
                    <div class="row cust-row">
                        <?php if (isset($_GET['id'])) { ?>
                            <div class="col-lg-7"><h2>
                                    Update Subscription
                                </h2></div>
                        <?php } else { ?>
                            <div class="col-lg-7"><h2>
                                    Add Subscription
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
                            <div class="width-prf">
                                <label>Year</label>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" name="txt_year" class="form-control"
                                               placeholder="Year"
                                               value="<?php if (isset($year)) echo $year; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Amount</label>

                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="number" name="txt_amount" class="form-control"
                                           placeholder="Amount"
                                           value="<?php if (isset($amount)) echo $amount; ?>">
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="form-group form_inline">
                                <?php if (isset($_GET['id'])) { ?>
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
                                    <a href="subscription_module.php" class="btn btn-default">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
        <div class="row margin_div_web">
            <div class="card">
                <div class="header">
                    <h2>
                        Manage Subscription <span class="badge"><?php
                            if (isset($countForVideo)) echo $countForVideo;
                            ?></span>
                    </h2>
                </div>
                <div class="body">
                    <table id="dtHorizontalVerticalExample" class="table table-striped table-bordered table-sm "
                           cellspacing="0"
                           width="100%">
                        <thead>
                        <tr class="back-color">
                            <th>Year</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>ACTION</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if ($get_status != null) {
                            while ($result_data = mysqli_fetch_array($get_status)) {
                                $default_user_id = $result_data['id'];
                                ?>
                                <tr>
                                    <td><?php echo $result_data['year']; ?></td>
                                    <td><?php echo $result_data['amt']; ?></td>
                                    <!-- <!--<td><?php /*echo $result_data['branch']; */ ?></td>-->
                                    <td><label class="label <?php if ($result_data['status'] == "0") {
                                            echo "label-danger";
                                        } else {
                                            echo "label-success";
                                        } ?>"><?php if ($result_data['status'] == 0) {
                                                echo "Unpublished";
                                            } else {
                                                echo "Published";
                                            } ?></label><br>
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
                                                        <a href="subscription_module.php?id=<?php echo $security->encrypt($result_data['id']) ?>"
                                                        <i class="fas fa-edit"></i>&nbsp;&nbsp;Edit</a></li>
                                                    <li>
                                                    <li>
                                                        <a href="subscription_module.php?delete_data=<?php echo $security->encrypt($result_data['id']) ?>"
                                                           onclick="return confirm('Are You sure you want to delete?');"
                                                        <i
                                                            class="fas fa-trash-alt"></i>&nbsp;&nbsp;Delete</a>
                                                    </li>
                                                    <li>
                                                        <a onclick="return confirm('Are You sure you want to <?php echo $result_data['status'] == 0 ? 'publish' : 'unpublish'; ?>?');"
                                                           href="subscription_module.php?publishData=<?php echo $security->encrypt($result_data['id']) ?>&action=<?php echo $result_data['status'] == 0 ? "publish" : "unpublish"; ?> "><i
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
</section>
<?php include "assets/common-includes/footer_includes.php" ?>
</body>
</html>