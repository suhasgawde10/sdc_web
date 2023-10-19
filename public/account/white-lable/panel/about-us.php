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


$image = $fetAllData['about_img'];
$logo = $fetAllData['company_logo'];
$about = $fetAllData['about_desc'];
$boxf = $fetAllData['about_box_f'];
$boxs = $fetAllData['about_box_s'];
$featureImg = $fetAllData['feature_image'];
$copy_years = $fetAllData['copy_right'];
$logo_size = $fetAllData['logo_width'];

if (isset($_POST['btn_update'])) {
    if (isset($_POST['txt_desc']) && $_POST['txt_desc'] != "") {
        $desc = mysqli_real_escape_string($con, $_POST['txt_desc']);
    } else {
        $error = true;
        $errorMessage .= "Enter Description.<br>";
    }
    if (isset($_POST['txt_box_f']) && $_POST['txt_box_f'] != "") {
        $box_one = mysqli_real_escape_string($con, $_POST['txt_box_f']);
    } else {
        $error = true;
        $errorMessage .= "Enter box one content.<br>";
    }
    if (isset($_POST['txt_box_s']) && $_POST['txt_box_s'] != "") {
        $box_two = mysqli_real_escape_string($con, $_POST['txt_box_s']);
    } else {
        $error = true;
        $errorMessage .= "Enter box two content.<br>";
    }
    if (isset($_POST['img_width']) && $_POST['img_width'] != "") {
        $img_width= mysqli_real_escape_string($con, $_POST['img_width']);
    } else {
        $error = true;
        $errorMessage .= "Enter Image Width.<br>";
    }

    if (!$error) {
        $directory_name = "uploads/about-img/";
        $extension = array('.jpg', '.JPG', '.jpeg', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG');
        $digits = 8;
        $randomNum = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        $newimgname = "";

        if (isset($_FILES['upload']) && $_FILES['upload']['error'][0] != 4) {
            $total = count($_FILES['upload']['name']);
            for ($i = 0; $i < $total; $i++) {
                $filename = $_FILES['upload']['name'][$i];
                $extensionStatus = $validate->validateFileExtension($filename, $extension);
                if (!$extensionStatus) {
                    $error = true;
                    $errorMessage .= "Please select valid file extension";
                } else {
                    $tmpFilePath = $_FILES['upload']['tmp_name'][$i];
                    $file_original_name = substr($_FILES['upload']['name'][$i], 0, strrpos($_FILES['upload']['name'][$i], '.'));
                    $file_extension = substr($_FILES['upload']['name'][$i], (strrpos($_FILES['upload']['name'][$i], '.') + 1));
                    $newimgname = $randomNum . '.' . $file_extension;

                    $newPath = $directory_name . $newimgname;
                    if (($_FILES['upload']['size'][$i] <= $maxsize)) {
                        if (!move_uploaded_file($tmpFilePath, $newPath)) {
                            $error = true;
                            $errorMessage .= "Failed to upload file";
                        } else {
                            $image_path = "uploads/about-img/" . $fetAllData['about_img'];
                            if (file_exists($image_path) && $fetAllData['about_img'] != '') {
                                unlink($image_path);
                            }
                        }
                    } else {
                        $error = true;
                        $errorMessage .= "File Size should be less than 2mb.";
                    }
                }
            }
        }

        $directory_name_logo = "uploads/logo/";
        $extension = array('.jpg', '.JPG', '.jpeg', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG');
        $digits = 8;
        $randomNum = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        $newlogoname = "";


        if (isset($_FILES['logo']) && $_FILES['logo']['error'][0] != 4) {
            $total = count($_FILES['logo']['name']);
            for ($i = 0; $i < $total; $i++) {
                $filename = $_FILES['logo']['name'][$i];
                $extensionStatus = $validate->validateFileExtension($filename, $extension);
                if (!$extensionStatus) {
                    $error = true;
                    $errorMessage .= "Please select valid file extension";
                } else {
                    $tmpFilePath = $_FILES['logo']['tmp_name'][$i];
                    $file_original_name = substr($_FILES['logo']['name'][$i], 0, strrpos($_FILES['logo']['name'][$i], '.'));
                    $file_extension = substr($_FILES['logo']['name'][$i], (strrpos($_FILES['logo']['name'][$i], '.') + 1));
                    $newlogoname = $randomNum . '.' . $file_extension;

                    $newPath = $directory_name_logo . $newlogoname;
                    if (($_FILES['logo']['size'][$i] <= $maxsize)) {
                        if (!move_uploaded_file($tmpFilePath, $newPath)) {
                            $error = true;
                            $errorMessage .= "Failed to upload file";
                        } else {
                            $image_path = "uploads/logo/" . $fetAllData['company_logo'];
                            if (file_exists($image_path) && $fetAllData['company_logo'] != '') {
                                unlink($image_path);
                            }
                        }
                    } else {
                        $error = true;
                        $errorMessage .= "File Size should be less than 2mb.";
                    }
                }
            }
        }

        if (!$error) {
            if ($newimgname == "") {
                $newimgname = $image;
            }
            if ($newlogoname == "") {
                $newlogoname = $logo;
            }
            $condition = array('id' => $security->decrypt($_GET['edit_id']));
            $insert_data = array('company_logo' => $newlogoname,'logo_width'=>$img_width, 'about_img' => $newimgname, 'about_desc' => $desc, 'about_box_f' => $box_one, 'about_box_s' => $box_two, 'updated_at' => $today_date, 'updated_by' => $_SESSION['email']);
            $update_team = $manage->update($manage->dealerTable, $insert_data, $condition);
            if ($update_team) {

                $error = false;
                $errorMessage .= 'About has been updated successfully';
                header("location:about-us.php?edit_id=" . $id);

            } else {
                $error = true;
                $errorMessage .= 'Issue while updating please try after some time';
            }

        }
    }
}

if (isset($_POST['save-features'])) {

    if (isset($_POST['copy_right']) && $_POST['copy_right'] != "") {
        $copy_year = $_POST['copy_right'];
    } else {
        $error = true;
        $errorMessage .= "Enter Copy Right Year.<br>";
    }


    $directory_name = "uploads/feature_img/";
    $extension = array('.jpg', '.JPG', '.jpeg', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG');
    $digits = 8;
    $randomNum = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
    $featureimgname = "";

    if (isset($_FILES['features']) && $_FILES['features']['error'][0] != 4) {
        $total = count($_FILES['features']['name']);
        for ($i = 0; $i < $total; $i++) {
            $filename = $_FILES['features']['name'][$i];
            $extensionStatus = $validate->validateFileExtension($filename, $extension);
            if (!$extensionStatus) {
                $error = true;
                $errorMessage .= "Please select valid file extension";
            } else {
                $tmpFilePath = $_FILES['features']['tmp_name'][$i];
                $file_original_name = substr($_FILES['features']['name'][$i], 0, strrpos($_FILES['features']['name'][$i], '.'));
                $file_extension = substr($_FILES['features']['name'][$i], (strrpos($_FILES['features']['name'][$i], '.') + 1));
                $featureimgname = $randomNum . '.' . $file_extension;

                $newPath = $directory_name . $featureimgname;
                if (($_FILES['features']['size'][$i] <= $maxsize)) {
                    if (!move_uploaded_file($tmpFilePath, $newPath)) {
                        $error = true;
                        $errorMessage .= "Failed to upload file";
                    } else {
                        $image_path = "uploads/feature_img/" . $fetAllData['feature_image'];
                        if (file_exists($image_path) && $fetAllData['feature_image'] != '') {
                            unlink($image_path);
                        }
                    }
                } else {
                    $error = true;
                    $errorMessage .= "File Size should be less than 2mb.";
                }
            }
        }
    }
    if (!$error) {
        if ($featureimgname == "") {
            $featureimgname = $featureImg;
        }
        $condition = array('id' => $security->decrypt($_GET['edit_id']));
        $insert_data = array('feature_image' => $featureimgname, 'copy_right' => $copy_year);
        $update_team = $manage->update($manage->dealerTable, $insert_data, $condition);
        if ($update_team) {
            $error = false;
            $errorMessage .= 'Features image has been updated successfully';
            header("location:about-us.php?edit_id=" . $id);

        } else {
            $error = true;
            $errorMessage .= 'Issue while updating please try after some time';
        }

    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>About Us</title>

    <?php include 'assets/common-includes/header_includes.php' ?>

    <style>

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
                                    <form method="post" action="" enctype="multipart/form-data">
                                        <fieldset>
                                            <legend>About Us</legend>

                                            <div class="form-row">

                                                <div class="form-group col-md-4">
                                                    <label for="image_upload">Website Logo</label>
                                                    <input type="file" name="logo[]" class="form-control"
                                                           id="logo_upload" accept="image/*">
                                                    <?php
                                                    if (isset($_GET['edit_id'])) {
                                                        $image_path = "uploads/logo/" . $logo;
                                                        if (file_exists($image_path) && $logo != '') {
                                                            echo '<img src="' . $image_path . '" style="width:30%" >';
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="logo_width">Website Logo Width</label>
                                                    <div class="input-group">
                                                        <input type="number" name="img_width" class="form-control"
                                                               id="logo_width" value="<?php if(isset($logo_size)){ echo $logo_size; }else{ echo '18'; } ?>" required="">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">%</div>
                                                        </div>
                                                    </div>
                                                    <small id="emailHelp" class="form-text text-muted">Logo Width in
                                                        Percentage (%)
                                                    </small>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="image_upload">Image</label>
                                                    <input type="file" name="upload[]" class="form-control"
                                                           id="image_upload" accept="image/*">
                                                    <?php
                                                    if (isset($_GET['edit_id'])) {
                                                        $image_path = "uploads/about-img/" . $image;
                                                        if (file_exists($image_path) && $image != '') {
                                                            echo '<img src="' . $image_path . '" style="width:30%" >';
                                                        }
                                                    }
                                                    ?>
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <label for="comapany_name">Description</label>
                                                    <textarea id="default1"
                                                              name="txt_desc"><?php if (isset($about)) echo $about; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="domain">Box-1</label>
                                                    <input type="text" name="txt_box_f" class="form-control"
                                                           id="box_1" placeholder="box-1"
                                                           value="<?php if (isset($boxf)) echo $boxf; ?>">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="domain">Box-2</label>
                                                    <input type="text" name="txt_box_s" class="form-control"
                                                           id="box_2" placeholder="box-2"
                                                           value="<?php if (isset($boxs)) echo $boxs; ?>">
                                                </div>
                                            </div>
                                            <br>
                                            <button type="submit" name="btn_update" class="btn btn-primary">Update
                                            </button>
                                            <button type="reset" class="btn btn-danger">cancel</button>
                                        </fieldset>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="col-lg-12">
                                    <form method="post" action="" enctype="multipart/form-data">
                                        <fieldset>
                                            <legend>Features</legend>

                                            <div class="form-row">

                                                <div class="form-group col-md-6">
                                                    <label for="image_upload">Image</label>
                                                    <input type="file" name="features[]" class="form-control"
                                                           id="image_features">
                                                    <?php
                                                    if (isset($_GET['edit_id'])) {
                                                        $image_path = "uploads/feature_img/" . $featureImg;
                                                        if (file_exists($image_path) && $featureImg != '') {
                                                            echo '<img src="' . $image_path . '" style="width:30%" >';
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="Copy_right">Copy Right year</label>
                                                    <input type="text" name="copy_right" required=""
                                                           class="form-control" placeholder="eg. 2020"
                                                           value="<?php if (isset($copy_years)) echo $copy_years ?>">

                                                </div>
                                            </div>
                                            <br>
                                            <button type="submit" name="save-features" class="btn btn-primary">Save
                                            </button>
                                            <button type="reset" class="btn btn-danger">cancel</button>
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


    tinymce.init({
        selector: 'textarea#default,textarea#default1',
        plugins: 'print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
        imagetools_cors_hosts: ['picsum.photos'],
        menubar: 'file edit view insert format tools table help',
        toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
        toolbar_sticky: true,
        autosave_ask_before_unload: true,
        autosave_interval: '30s',
        autosave_prefix: '{path}{query}-{id}-',
        autosave_restore_when_empty: false,
        autosave_retention: '2m',
        image_advtab: true,
        link_list: [
            {title: 'My page 1', value: 'http://www.tinymce.com'},
            {title: 'My page 2', value: 'http://www.moxiecode.com'}
        ],
        image_list: [
            {title: 'My page 1', value: 'http://www.tinymce.com'},
            {title: 'My page 2', value: 'http://www.moxiecode.com'}
        ],
        image_class_list: [
            {title: 'None', value: ''},
            {title: 'Some class', value: 'class-name'}
        ],
        importcss_append: true,
        file_picker_callback: function (callback, value, meta) {
            /* Provide file and text for the link dialog */
            if (meta.filetype === 'file') {
                callback('https://www.google.com/logos/google.jpg', {text: 'My text'});
            }

            /* Provide image and alt text for the image dialog */
            if (meta.filetype === 'image') {
                callback('https://www.google.com/logos/google.jpg', {alt: 'My alt text'});
            }

            /* Provide alternative source and posted for the media dialog */
            if (meta.filetype === 'media') {
                callback('movie.mp4', {source2: 'alt.ogg', poster: 'https://www.google.com/logos/google.jpg'});
            }
        },
        templates: [
            {
                title: 'New Table',
                description: 'creates a new table',
                content: '<div class="mceTmpl"><table width="98%%"  border="0" cellspacing="0" cellpadding="0"><tr><th scope="col"> </th><th scope="col"> </th></tr><tr><td> </td><td> </td></tr></table></div>'
            },
            {title: 'Starting my story', description: 'A cure for writers block', content: 'Once upon a time...'},
            {
                title: 'New list with dates',
                description: 'New List with dates',
                content: '<div class="mceTmpl"><span class="cdate">cdate</span><br /><span class="mdate">mdate</span><h2>My List</h2><ul><li></li><li></li></ul></div>'
            }
        ],
        template_cdate_format: '[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]',
        template_mdate_format: '[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]',
        height: 200,
        image_caption: true,
        quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
        noneditable_noneditable_class: 'mceNonEditable',
        toolbar_mode: 'sliding',
        contextmenu: 'link image imagetools table',
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
    });
</script>


</body>
</html>
