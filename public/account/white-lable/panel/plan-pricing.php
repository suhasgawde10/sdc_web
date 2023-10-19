<?php
ini_set('memory_limit', '-1');
$error = false;
$errorMessage = "";
include "controller/ManageAdminApp.php";
$manage = new ManageAdminApp();
include "controller/validator.php";
$validate = new Validator();
include "controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
$controller = new Controller();
$con = $controller->connect();


$today_date = date("Y-m-d");

if (!isset($_SESSION['email'])) {
    header('location:index.php');
}

/*$maxsize = 4194304;*/
$maxsize = 4194304;
$imgUploadStatus = false;
$id = 0;
if (isset($_GET['edit_id'])) {
    $id = $_GET['edit_id'];
    $fetchId = $security->decrypt($id);
}
$getAllPlan = $manage->getPlan($fetchId);


if (isset($_POST['btn-add'])) {
    if (isset($_POST['txt_plan']) && $_POST['txt_plan'] != "") {
        $planname = mysqli_real_escape_string($con, $_POST['txt_plan']);
    } else {
        $error = true;
        $errorMessage .= "Enter plan name.<br>";
    }
    if (isset($_POST['txt_price']) && $_POST['txt_price'] != "") {
        $planprice = mysqli_real_escape_string($con, $_POST['txt_price']);
    } else {
        $error = true;
        $errorMessage .= "Enter plan Price.<br>";
    }
    $payment = $_POST['txt_payment'];

    if (!$error) {
        $insert_data = array('dealer_id' => $fetchId, 'plan_name' => $planname, 'price_price' => $planprice, 'payment_link' => $payment, 'created_at' => $today_date, 'created_by' => $_SESSION['email']);
        $insert_team = $manage->insert($manage->planTable, $insert_data);
        if ($insert_team) {
            $error = false;
            $errorMessage = "Plan Added successfully";
            header("location:plan-pricing.php?edit_id=" . $id);
        } else {
            $error = true;
            $errorMessage = "Issue while adding details, Please try again.";
        }
    }
}

if (isset($_GET['edit_data'])) {
    $edit_plan = $security->decrypt($_GET['edit_data']);
    $form_data = $manage->getPlanByIdEdit($edit_plan);
    if ($form_data != "") {
        $plan_name = $form_data['plan_name'];
        $plan_price = $form_data['price_price'];
        $plan_payment = $form_data['payment_link'];
    }
}
if (isset($_GET['edit_data'])) {
    if (isset($_POST['btn-update'])) {

        if (isset($_POST['txt_plan']) && $_POST['txt_plan'] != "") {
            $txt_plan = $_POST['txt_plan'];
        } else {
            $error = true;
            $errorMessage .= "Enter Plan Name<br>";
        }
        if (isset($_POST['txt_price']) && $_POST['txt_price'] != "") {
            $txt_price = $_POST['txt_price'];
        } else {
            $error = true;
            $errorMessage .= "Enter Plan Price<br>";
        }
        $uppayment = $_POST['txt_payment'];

        if (!$error) {
            $condition = array('id' => $security->decrypt($_GET['edit_data']));
            $insert_data = array('plan_name' => $txt_plan, 'price_price' => $txt_price, 'payment_link' => $uppayment, 'updated_at' => $today_date, 'updated_by' => $_SESSION['email']);

            $update_category = $manage->update($manage->planTable, $insert_data, $condition);
            if ($update_category) {
                $error = false;
                $errorMessage = "Plan Update Successfully";
                header("location:plan-pricing.php?edit_id=" . $id);
            } else {
                $error = true;
                $errorMessage = "Issue while updating details, Please try again.";
            }
        }
    }
}

if (isset($_GET['delete_data']) && ($_GET['action'] == "delete")) {
    $delete_data = $security->decrypt($_GET['delete_data']);
    $status = $manage->deleteData($manage->planTable, $delete_data);
    if ($status) {
        header("location:plan-pricing.php?edit_id=" . $id);
    }
}


?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Plan & Pricing</title>

    <?php include 'assets/common-includes/header_includes.php' ?>

    <style>
        /*.active{
            background-color: #0000ff;
        }*/

        legend {
            font-size: 1.2em !important;
            font-weight: bold !important;
            text-align: left !important;
            width: auto;
            padding: 0 10px;
            border-bottom: none;
        }

        .img-input {
            width: 0.1px;
            height: 0.1px;
            opacity: 0;
            overflow: hidden;
            position: absolute;
            z-index: -1;
        }

        img {
            max-width: 180px;
        }

        #more {
            display: none;
        }
    </style>

    <!--[if IE]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- remove this if you use Modernizr -->
    <script>(function (e, t, n) {
            var r = e.querySelectorAll("html")[0];
            r.className = r.className.replace(/(^|\s)no-js(\s|$)/, "$1js$2")
        })(document, window, 0);</script>
    <link rel="stylesheet" type="text/css" href="css/image-preview.css"/>
    <link rel="stylesheet" href="assets/summernote/summernote-bs4.css">
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css"
          rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <!-- Navbar -->
    <?php include 'assets/common-includes/header.php' ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php include 'assets/common-includes/left_menu.php' ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header table-card-header">
                                <?php include "commom-menu.php" ?>
                            </div>
                            <div class="card-body">
                                <br>

                                <div class="col-lg-12">
                                    <?php if ($error && $errorMessage != "") {
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
                                    <form method="post" action="">

                                    <div class="form-group row">
                                            <label for="colFormLabel" class="col-sm-2 col-form-label">Plan
                                                Status</label>

                                            <div class="col-sm-6">
                                                <input id="toggle-event" type="checkbox"
                                                       data-toggle="toggle" <?php if ($plan_status == 1) echo 'checked' ?>  >
                                            </div>
                                        </div>

                                        <fieldset>
                                            <legend>Plan Details</legend>
                                            <div class="row">
                                                <div class="col-lg-5">

                                                    <div class="col-md-12">
                                                        <label for="name">Plan</label>
                                                        <input type="text" name="txt_plan" class="form-control"
                                                               id="name" placeholder="Plan Name"
                                                               value="<?php if (isset($plan_name)) echo $plan_name ?>">
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="image_upload">Price</label>
                                                        <input type="text" name="txt_price"
                                                               class="form-control"
                                                               id="price" placeholder="Price"
                                                               value="<?php if (isset($plan_price)) echo $plan_price ?>">
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="image_upload">Payment Link</label>
                                                        <input type="text" name="txt_payment"
                                                               class="form-control"
                                                               id="payment" placeholder="Payment Link"
                                                               value="<?php if (isset($plan_payment)) echo $plan_payment ?>">
                                                    </div>
                                                    <br>

                                                    <div class="col-md-12">
                                                        <?php
                                                        if (isset($_GET['edit_data'])) {
                                                            ?>
                                                            <button type="submit" class="btn btn-primary"
                                                                    name="btn-update">Update
                                                            </button>
                                                        <?php
                                                        } else {
                                                            ?>
                                                            <button type="submit" class="btn btn-primary"
                                                                    name="btn-add">Save
                                                            </button>
                                                        <?php
                                                        }
                                                        ?>
                                                        <button type="reset" class="btn btn-danger">cancel</button>
                                                    </div>
                                                </div>
                                                <div class="col-sm-7">
                                                    <table id="example1"
                                                           class="table table-bordered table-striped">
                                                        <thead>
                                                        <tr>
                                                            <th>Plan</th>
                                                            <th>Price</th>
                                                            <th>Payment link</th>
                                                            <th>Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                        if ($getAllPlan != "") {
                                                            while ($rowData = mysqli_fetch_array($getAllPlan)) {
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $rowData['plan_name'] ?></td>
                                                                    <td><?php echo $rowData['price_price'] ?></td>
                                                                    <td><?php echo $rowData['payment_link'] ?></td>
                                                                    <td>
                                                                        <div class="dropdown">
                                                                            <button class="btn btn-default"
                                                                                    type="button"
                                                                                    data-toggle="dropdown"
                                                                                    aria-expanded="false"><i
                                                                                    class="fa fa-ellipsis-v"
                                                                                    aria-hidden="true"></i>
                                                                            </button>
                                                                            <?php if ($_SESSION["type"] == "admin") {
                                                                                ?>
                                                                                <div
                                                                                    class="dropdown-menu dropdown-menu-right"
                                                                                    aria-labelledby="dropdownMenuButton">
                                                                                    <a class="dropdown-item waves-light waves-effect"
                                                                                       href="plan-pricing.php?edit_id=<?php echo $id; ?>&edit_data=<?php echo $security->encrypt($rowData['id']); ?>">
                                                                                        <i class="fas fa-edit"></i>&nbsp;&nbsp;Edit</a>
                                                                                    <a class="dropdown-item waves-light waves-effect"
                                                                                       href="plan-pricing.php?delete_data=<?php echo $security->encrypt($rowData['id']); ?>&action=delete&edit_id=<?php echo $id; ?>"
                                                                                       onclick="return confirm('Are You sure you want to delete?');">
                                                                                        <i class="fas fa-trash-alt"></i>&nbsp;&nbsp;Delete</a>
                                                                                </div>
                                                                            <?php

                                                                            } else {
                                                                                ?>
                                                                                <div
                                                                                    class="dropdown-menu dropdown-menu-right"
                                                                                    aria-labelledby="dropdownMenuButton">
                                                                                    <a class="dropdown-item waves-light waves-effect"
                                                                                       href="plan-pricing.php?edit_id=<?php echo $security->encrypt($_SESSION['id']); ?>&edit_data=<?php echo $security->encrypt($rowData['id']); ?>">
                                                                                        <i class="fas fa-edit"></i>&nbsp;&nbsp;Edit</a>
                                                                                    <a class="dropdown-item waves-light waves-effect"
                                                                                       href="plan-pricing.php?delete_data=<?php echo $security->encrypt($rowData['id']); ?>&action=delete&edit_id=<?php echo $security->encrypt($_SESSION['id']); ?>"
                                                                                       onclick="return confirm('Are You sure you want to delete?');">
                                                                                        <i class="fas fa-trash-alt"></i>&nbsp;&nbsp;Delete</a>
                                                                                </div>
                                                                            <?php
                                                                            } ?>

                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            <?php
                                                            }
                                                        }
                                                        ?>
                                                        </tbody>
                                                    </table>

                                                </div>
                                            </div>
                                        </fieldset>
                                    </form>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <?php include 'assets/common-includes/footer.php' ?>
</div>
<!-- ./wrapper -->
<?php include 'assets/common-includes/footer_includes.php' ?>

<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('.blah')
                    .attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
<script type="text/javascript">
    $("document").ready(function () {
        $('#toggle-event').change(function () {
            var data = $(this).prop('checked');
            var user_id = <?php echo $fetchId ?>;
            $.ajax({
                type: 'POST',
                url: "update_franchise_status.php",
                data: "planstatus=" + data + "&user_id=" + user_id,
                success: function (result) {
                    console.log(result);
                }
            });
        })
    });
</script>

</body>
</html>
