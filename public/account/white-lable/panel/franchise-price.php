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

$getFranchisePlan = $manage->getDealerFranchisePlan($fetchId);
$getDealerData = $manage->getDealerByIdEdit($fetchId);
if($getDealerData !=""){
    $franchise_status = $getDealerData['franchise_status'];
}
//var_dump($getFranchisePlan);


if (isset($_POST['btn-update'])) {

    $txt_name = $_POST['plan_name'];
    if (isset($_POST['plan_price']) && $_POST['plan_price'] != "") {
        $txt_price = $_POST['plan_price'];
    } else {
        $error = true;
        $errorMessage .= "Enter plan price<br>";
    }

    if (!$error) {
        foreach ($txt_price as $key => $i) {

            $price = $txt_price[$key];
            $name = $txt_name[$key];
            $update_category = $manage->updateFranchisePlan($fetchId, $name, $price);
            if ($update_category) {
                $error = false;
                $errorMessage = "Franchise Plan Update Successfully";
                header("location:franchise-price.php?edit_id=" . $id);
            } else {
                $error = true;
                $errorMessage = "Issue while updating details, Please try again.";
            }
        }
    }
}


?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Franchise Price</title>

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
                                            <label for="colFormLabel" class="col-sm-2 col-form-label">Franchise
                                                Status</label>
                                            <div class="col-sm-6">
                                                <input id="toggle-event" type="checkbox" data-toggle="toggle" <?php if($franchise_status == 1 ) echo 'checked'?>  >
                                            </div>
                                        </div>
                                        <fieldset>
                                            <legend>Franchise Plan Details</legend>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <br>

                                                    <div class="col-md-8">
                                                        <?php
                                                        if ($getFranchisePlan != "") {
                                                            while ($rowplan = mysqli_fetch_array($getFranchisePlan)) {
                                                                ?>
                                                                <div class="form-group row">
                                                                    <label for="colFormLabel"
                                                                           class="col-sm-2 col-form-label"><?php echo $rowplan['plan_name'] ?></label>
                                                                    <input type="hidden" class="form-control"
                                                                           id="colFormLabel" name="plan_name[]"
                                                                           placeholder="50 % Plan Price" required=""
                                                                           value="<?php echo $rowplan['plan_name'] ?>">

                                                                    <div class="col-sm-6">
                                                                        <input type="number" class="form-control"
                                                                               id="colFormLabel" name="plan_price[]"
                                                                               placeholder="price" required=""
                                                                               value="<?php echo $rowplan['plan_price'] ?>">
                                                                    </div>
                                                                </div>
                                                            <?php
                                                            }
                                                        }
                                                        ?>

                                                    </div>
                                                    <div class="col-md-12">
                                                        <button type="submit" class="btn btn-primary"
                                                                name="btn-update">Update
                                                        </button>
                                                        <button type="reset" class="btn btn-danger">cancel</button>
                                                    </div>
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
                data: "status=" + data + "&user_id=" + user_id,
                success: function (result) {
                    console.log(result);
                }
            });
        })
    });
</script>


</body>
</html>
