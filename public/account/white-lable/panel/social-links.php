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

$fetAllData = $manage->FetchData($manage->dealerTable, $fetchId);
$facebook = $fetAllData['facebook'];
$linkdin = $fetAllData['linkdin'];
$twitter = $fetAllData['twitter'];
$instagram = $fetAllData['instagram'];
$whatsapp = $fetAllData['whatsapp'];
$email = $fetAllData['email'];
$youtube = $fetAllData['youtube'];

if (isset($_POST['add-btn'])) {

   /* $fb = preg_match('/(?:(?:http|https):\/\/)?(?:www.)?facebook.com\/(?:(?:\w)*#!\/)?(?:pages\/)?(?:[?\w\-]*\/)?(?:profile.php\?id=(?=\d.*))?([\w\-]*)?/', $_POST['txt_fb']);
    if ($fb) {
        $insert_fb = mysqli_real_escape_string($con, $_POST['txt_fb']);
    } else {
        $error = true;
        $errorMessage .= "Enter Valid Facebook profile link.<br>";
    }
    $linkd = preg_match('/(https?)?:?(\/\/)?(([w]{3}||\w\w)\.)?linkedin.com(\w+:{0,1}\w*@)?(\S+)(:([0-9])+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/', $_POST['txt_linkdin']);
    if ($linkd) {
        $insert_linkdin = mysqli_real_escape_string($con, $_POST['txt_linkdin']);
    } else {
        $error = true;
        $errorMessage .= "Enter Valid linkedin profile link.<br>";
    }

    $twitr = preg_match('/http(?:s)?:\/\/(?:www\.)?twitter\.com\/([a-zA-Z0-9_]+)/', $_POST['txt_twitter']);
    if ($twitr) {
        $insert_twitter = $_POST['txt_twitter'];
    } else {
        $error = true;
        $errorMessage .= "Enter Valid twitter profile link.<br>";
    }*/

    $insert_fb = $_POST['txt_fb'];
    $insert_linkdin = $_POST['txt_linkdin'];
    $insert_twitter = $_POST['txt_twitter'];
    $insert_insta = $_POST['txt_insta'];
    $insert_youtube = $_POST['txt_youtube'];
    if (!$error) {
        $condition = array('id' => $security->decrypt($_GET['edit_id']));
        $insert_data = array('facebook' => $insert_fb, 'linkdin' => $insert_linkdin, 'twitter' => $insert_twitter, 'instagram' => $insert_insta, 'youtube' => $insert_youtube);
        $update_cont = $manage->update($manage->dealerTable, $insert_data, $condition);
        if ($update_cont) {
            $error = false;
            $errorMessage = "Social link updated successfully";
        } else {
            $error = true;
            $errorMessage = "Issue while adding details, Please try again.";
        }
    }

}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Social Media links</title>

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
                                        <fieldset>
                                            <legend>Social Link</legend>
                                            <div class="col-lg-12">
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <label for="image_upload">facebook</label>
                                                        <input type="text" class="form-control" name="txt_fb"
                                                               placeholder="facebook"
                                                               value="<?php if (isset($facebook)) echo $facebook ?>">
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label for="image_upload">LinkdIn</label>
                                                        <input type="text" class="form-control" name="txt_linkdin"
                                                               placeholder="LinkdIn"
                                                               value="<?php if (isset($linkdin)) echo $linkdin ?>">
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label for="image_upload">Twitter</label>
                                                        <input type="text" class="form-control" name="txt_twitter"
                                                               placeholder="Twitter"
                                                               value="<?php if (isset($twitter)) echo $twitter ?>">
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label for="image_upload">Instagram</label>
                                                        <input type="text" class="form-control" name="txt_insta"
                                                               placeholder="Instagram"
                                                               value="<?php if (isset($instagram)) echo $instagram ?>">
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label for="image_upload">Youtube</label>
                                                        <input type="text" class="form-control" name="txt_youtube"
                                                               placeholder="Youtube"
                                                               value="<?php if (isset($youtube)) echo $youtube ?>">
                                                    </div>
                                                </div>
                                                <br>

                                                <div class="mt-20">
                                                    <div class="col-lg-4">
                                                        <button type="submit" name="add-btn" class="btn btn-primary">
                                                            Update
                                                        </button>
                                                        <button type="reset" class="btn btn-danger">cancel</button>
                                                    </div>
                                                </div>

                                            </div>

                                            <br><br><br>

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

<script>

</script>

</body>
</html>
