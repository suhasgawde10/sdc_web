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
include "controller/config data.php";


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

$fetAllData = $manage->FetchData($manage->dealerTable, $fetchId);
$password_status = $fetAllData['pass_status'];
if ($_SESSION['type'] != "admin") {
    if ($password_status == 0) {
        header("location:reset-password.php?edit_id=" . $id);
    }
}

$Specific_Link = $fetAllData['specific_link'];
$radioCheck = $fetAllData['card_show_status'];

$getCardData = $manage->getSpecificCardDataByDealer($fetchId);
if ($getCardData != "") {
    $countValue = mysqli_num_rows($getCardData);
}


if (isset($_POST['btn_add'])) {
    if (isset($_POST['demo_card_rdo']) && $_POST['demo_card_rdo'] != "") {
        $rdo_check = mysqli_real_escape_string($con, $_POST['demo_card_rdo']);
    } else {
        $error = true;
        $errorMessage .= "Please select radio button<br>";
    }
    if (!$error) {
        if ($rdo_check == 2) {
            if (isset($_POST['text_name']) && $_POST['text_name'] != "") {
                $txt_name = $_POST['text_name'];
            } else {
                $error = true;
                $errorMessage .= "Enter card name<br>";
            }
            if (isset($_POST['text_link']) && $_POST['text_link'] != "") {
                $txt_link = $_POST['text_link'];
            } else {
                $error = true;
                $errorMessage .= "Enter card link<br>";
            }
        } else {
            $txt_name = $_POST['text_name'];
            $txt_link = $_POST['text_link'];
        }
        if (!$error) {
            foreach ($txt_link as $key => $i) {
                $name = $txt_name[$key];
                $link = $txt_link[$key];
                $insert_data = array('dealer_id' => $fetchId, 'card_name' => $name, 'card_link' => $link);
                $insert_card = $manage->insert($manage->demoCardTable, $insert_data);
            }
            if ($insert_card) {
                $error = false;
                $errorMessage .= 'Demo card data added successfully';
            } else {
                $error = true;
                $errorMessage .= 'Issue while updating please try after some time';
            }
        }

    }


}

if (isset($_POST['btn_update'])) {
    if (isset($_POST['demo_card_rdo']) && $_POST['demo_card_rdo'] != "") {
        $up_rdo_check = mysqli_real_escape_string($con, $_POST['demo_card_rdo']);
    } else {
        $error = true;
        $errorMessage .= "Please select radio button<br>";
    }
    if (!$error) {
        if ($up_rdo_check == 2) {
            if (isset($_POST['text_name']) && $_POST['text_name'] != "") {
                $up_txt_name = $_POST['text_name'];
            } else {
                $error = true;
                $errorMessage .= "Enter card name<br>";
            }
            if (isset($_POST['text_link']) && $_POST['text_link'] != "") {
                $up_txt_link = $_POST['text_link'];
            } else {
                $error = true;
                $errorMessage .= "Enter card link<br>";
            }
        } else {
            $up_txt_name = $_POST['text_name'];
            $up_txt_link = $_POST['text_link'];
        }
        $up_id = $_POST['text_id'];
        if (!$error) {

            if ($up_rdo_check == 2) {
                foreach ($up_txt_link as $key => $i) {

                    $ups_id = $up_id[$key];
                    $up_name = $up_txt_name[$key];
                    $up_link = $up_txt_link[$key];
                    if ($up_name != "") {
                        $checkDataExits = $manage->getSpecificCardDataExitsByDealer($ups_id);
                        if ($checkDataExits) {

                            $condition = array('id' => $ups_id);
                            $update_data = array('dealer_id' => $fetchId, 'card_name' => $up_name, 'card_link' => $up_link);
                            $update_card = $manage->update($manage->demoCardTable, $update_data, $condition);
                        } else {
                            $insert_data = array('dealer_id' => $fetchId, 'card_name' => $up_name, 'card_link' => $up_link);
                            $insert_card = $manage->insert($manage->demoCardTable, $insert_data);
                        }
                    } else {
                        $condition = array('id' => $ups_id);
                        $update_data = array('dealer_id' => $fetchId, 'card_name' => $up_name, 'card_link' => $up_link);
                        $update_card = $manage->update($manage->demoCardTable, $update_data, $condition);
                    }
                }
                $condition = array('id' => $fetchId);
                $update_data = array('card_show_status' => $up_rdo_check);
                $update_card = $manage->update($manage->dealerTable, $update_data, $condition);
            } else {
                $condition = array('id' => $fetchId);
                $update_data = array('card_show_status' => $up_rdo_check);
                $update_card = $manage->update($manage->dealerTable, $update_data, $condition);
            }
            if ($update_card) {
                $error = false;
                $errorMessage .= 'Demo card data updated successfully';
            } else {
                $error = true;
                $errorMessage .= 'Issue while updating please try after some time';
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
    <title>Demo Card</title>

    <?php include 'assets/common-includes/header_includes.php' ?>

    <style>
        /*.active{
            background-color: #0000ff;
        }*/
        .input-group-btn {
            margin-left: -3px;
        }

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

        .multiple-card {
            margin-top: 10px;
            margin-bottom: 10px;

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
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <!-- Navbar -->
    <?php include 'assets/common-includes/header.php' ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->

    <?php
    if (isset($_SESSION["type"]) != 'dealer') {
        ?>

    <?php
    }
    ?>
    <?php include 'assets/common-includes/left_menu.php'; ?>


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
                                    <form method="post" action="" enctype="">
                                        <fieldset>
                                            <legend>Demo Card</legend>
                                            <div class="form-row">

                                                <div class="form-group col-md-12 col-lg-12">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio"
                                                               name="demo_card_rdo" id="inlineRadio1" value="1"
                                                               checked <?php if ($radioCheck == 1) {
                                                            echo "checked";
                                                        } ?> >
                                                        <label class="form-check-label" for="inlineRadio1">Show all my
                                                            Register Card</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio"
                                                               name="demo_card_rdo" id="inlineRadio2"
                                                               value="2" <?php if ($radioCheck == 2) {
                                                            echo "checked";
                                                        } ?> >
                                                        <label class="form-check-label" for="inlineRadio2">Show specific
                                                            register Card</label>
                                                    </div>
                                                </div>
                                                <!--<div class="form-group col-md-6 col-lg-6 link-txt">
                                                    <div class="mb-3">
                                                        <label for="link" class="form-label">Specific card link</label>
                                                        <input type="text" class="form-control"
                                                               id="link" name="txt_link"
                                                               placeholder="Digital card link"
                                                               value="<?php /*if (isset($Specific_Link)) echo $Specific_Link */ ?>">
                                                    </div>
                                                </div>-->
                                                <div class="form-group col-md-6 col-lg-6 link-txt">
                                                    <div class="mb-3">


                                                        <?php
                                                        if ($getCardData != "") {
                                                            while ($rowcard = mysqli_fetch_array($getCardData)) {

                                                                $crdid = $rowcard['id'];
                                                                $crdname = $rowcard['card_name'];
                                                                $crdlink = $rowcard['card_link'];
                                                                ?>
                                                                <div class="textbox-wrappers"
                                                                     style="margin-bottom: 10px">
                                                                    <div class="input-group">
                                                                        <input type="hidden"
                                                                               value="<?php echo $crdid; ?>"
                                                                               name="text_id[]" class="form-control"
                                                                               id="<?php echo $crdid; ?>">
                                                                        <input type="text" name="text_name[]"
                                                                               class="form-control"
                                                                               placeholder="Enter card name"
                                                                               value="<?php echo $crdname; ?>" id=""/>
                                                                        <input type="text" name="text_link[]"
                                                                               class="form-control"
                                                                               placeholder="Enter card link"
                                                                               value="<?php echo $crdlink; ?>"/>
                                                                <span class="input-group-btn">
                                                                    <button type="button"
                                                                            class="btn btn-danger remove-textbox"
                                                                            id="<?php echo $crdid; ?>">
                                                                        <i class="fa fa-minus"></i></button>
                                                                </span>
                                                                    </div>
                                                                </div>
                                                            <?php
                                                            }
                                                        }
                                                        ?>
                                                        <div class="textbox-wrapper" style="margin-bottom: 10px">
                                                            <div class="input-group">
                                                                <input type="text" name="text_name[]"
                                                                       class="form-control"
                                                                       placeholder="Enter card name"/>
                                                                <input type="text" name="text_link[]"
                                                                       class="form-control"
                                                                       placeholder="Enter card link"/>
                                                                <span class="input-group-btn">
                                                                    <button type="button"
                                                                            class="btn btn-success add-textbox"><i
                                                                            class="fa fa-plus"></i></button>
                                                                </span>
                                                            </div>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>

                                            <br>
                                            <?php if ($getCardData != "") {
                                                ?>
                                                <button type="submit" name="btn_update" class="btn btn-primary">Update
                                                </button>
                                            <?php
                                            } else {
                                                ?>
                                                <button type="submit" name="btn_add" class="btn btn-primary">Add
                                                </button>
                                            <?php
                                            } ?>
                                            <a href="about-us.php?edit_id=<?php echo $_GET['edit_id']; ?>"
                                               class="btn btn-danger">cancel</a>
                                        </fieldset>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
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
<script>

    /*if ($("#inlineRadio1").is(":checked")) {
     $("#link-txt").show();
     }
     if ($("#inlineRadio2").prop(":checked")) {
     $(".link-txt").show(300);
     }*/
</script>
<script>
    $(".link-txt").hide();

    if ($("#inlineRadio2").is(':checked')) {
        $(".link-txt").show(300);
    } else {
        $(".link-txt").hide(300);
    }

    $("#inlineRadio2").click(function () {
        if ($(this).is(":checked")) {
            $(".link-txt").show(300);
        }
    });

    $("#inlineRadio1").click(function () {
        if ($(this).is(":checked")) {
            $(".link-txt").hide(300);
        }
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        var max = 100;
        var cnt = 1;
        $(".add-textbox").on("click", function (e) {
            e.preventDefault();
            if (cnt < max) {
                cnt++;
                $(".textbox-wrapper").append('<div class="input-group multiple-card">' +
                '<input type="text" name="text_name[]" class="form-control" placeholder="Enter card name" />' +
                '<input type="text" name="text_link[]" class="form-control" placeholder="Enter card link" />' +
                '<span class="input-group-btn">' +
                '<button type="button" class="btn btn-danger remove-textbox">' +
                '<i class="fa fa-minus"></i></button></span></div>');
            }
        });

        $(".textbox-wrapper").on("click", ".remove-textbox", function (e) {
            e.preventDefault();
            $(this).parents(".input-group").remove();
            cnt--;
        });
        $(".textbox-wrappers").on("click", ".remove-textbox", function (e) {
            e.preventDefault();
            $(this).parents(".input-group").remove();
            $.ajax({
                type: "POST",
                url: "getCardData.php",
                data: {id: this.id},
                success: function (result) {
                    console.log(result);
                    location.reload()
                }
            });
            cnt--;
        });

    });
</script>
<script>
    /*$(".remove-textbox").click(function(e) {
     var id = ()
     e.preventDefault();

     });*/
</script>
</body>
</html>
