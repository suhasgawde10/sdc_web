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
    if (!$error) {
        $data = array('coupan_name'=>$_POST['name'],'discount'=>$_POST['discount'],'from_date'=>$_POST['from_date'],'to_date'=>$_POST['to_date'],'created_date'=>date('Y-m-d'),'created_by'=>$id);
        $status = $manage->insert($manage->couponTable,$data);
        if ($status) {
            $error = false;
            $errorMessage .= "Detail Added Successfully.";
        } else {
            echo "could not connect";
        }
    }

}



if (isset($_POST['btn_update'])){
    if(isset($_GET['id'])){
        $c_id = $security->decrypt($_GET['id']);
        $data = array('coupan_name'=>$_POST['name'],'discount'=>$_POST['discount'],'from_date'=>$_POST['from_date'],'to_date'=>$_POST['to_date']);
        if (!$error) {
            $status = $manage->update($manage->couponTable,$data,array('id'=>$c_id));
            if ($status) {
                $error = false;
                $errorMessage .= "Detail Updated Successfully.";
            } else {
                echo "could not connect";
            }
        }
    }
}


/*This is for edit*/
if (isset($_GET['id'])) {
    $id = $security->decrypt($_GET['id']);
    $form_data = $manage->getCouponCodeDetails($id);
    if($form_data!=null){
        $name = $form_data['coupan_name'];
        $discount= $form_data['discount'];
        $from_date= $form_data['from_date'];
        $to_date= $form_data['to_date'];

    }

}

if (isset($_GET['delete_data'])) {
    $delete_data = $security->decrypt($_GET['delete_data']);
    $status = $manage->deleteCouponCode($delete_data);
    if ($status) {
        header('location:coupon_module.php');
    }
}

if (isset($_GET['delete_data'])) {
    $delete_data = $security->decrypt($_GET['delete_data']);
    $status = $manage->deleteCoupon($delete_data);
    if ($status) {
        header('location:coupon_module.php');
    }
}


/*End of Edit*/

$get_status = $manage->displayCouponModule();
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
    <title>Coupon module</title>
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
                                    Update Coupon
                                </h2></div>
                        <?php } else { ?>
                            <div class="col-lg-7"><h2>
                                    Add Coupon
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
                                <label>Coupon Name</label>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" name="name" class="form-control"
                                               placeholder="Enter Coupan Name" style="text-transform: uppercase;"
                                               value="<?php if (isset($name)) echo $name; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="width-prf">
                                <label>Discount</label>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" name="discount" class="form-control"
                                               placeholder="Enter Discount"
                                               value="<?php if (isset($discount)) echo $discount; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="form-label">From date</label>

                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="date" name="from_date" class="form-control"
                                           value="<?php if (isset($from_date)) echo $from_date; ?>">
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="form-label">To date</label>

                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="date" name="to_date" class="form-control"
                                           value="<?php if (isset($to_date)) echo $to_date; ?>">
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
                                    <a href="coupon_module.php" class="btn btn-default">Cancel</a>
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
                        Manage Coupon <span class="badge"><?php
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
                            <th>Name</th>
                            <th>Discount</th>
                            <th>From Date</th>
                            <th>TO Date</th>
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
                                    <td><a onclick="setClipboard('https://sharedigitalcard.com/register.php?coupon_code=<?php echo $result_data['coupan_name']; ?>')"><?php echo $result_data['coupan_name']; ?></a></td>
                                    <td><?php echo $result_data['discount']; ?></td>
                                    <td><?php echo $result_data['from_date']; ?></td>
                                    <td><?php echo $result_data['to_date']; ?></td>

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
                                                        <a href="coupon_module.php?id=<?php echo $security->encrypt($result_data['id']) ?>"
                                                        <i class="fas fa-edit"></i>&nbsp;&nbsp;Edit</a></li>
                                                    <li>
                                                    <li>
                                                        <a href="coupon_module.php?delete_data=<?php echo $security->encrypt($result_data['id']); ?>"
                                                           onclick="return confirm('Are You sure you want to delete?');"
                                                        <i class="fas fa-trash-alt"></i>&nbsp;&nbsp;Delete</a>
                                                    </li>
                                                    <li>
                                                        <a onclick="setClipboard('https://sharedigitalcard.com/register.php?coupon_code=<?php echo $result_data['coupan_name']; ?>')">
                                                        <i class="fa fa-link"></i>&nbsp;&nbsp;Share Url</a>
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