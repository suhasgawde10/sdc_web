<?php

ob_start();
include "../controller/ManageUser.php";
$manage = new ManageUser();
include "../controller/validator.php";
$validate = new Validator();
include '../sendMail/sendMail.php';
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();

if (!isset($_SESSION['email'])) {
    header('location:../login.php');
}elseif(isset($_SESSION['email']) && $_SESSION['email'] != "admin@sharedigitalcard.com" && (isset($_SESSION['type']) && $_SESSION['type'] != 'Admin')){
    header('location:../login.php');
}

include_once('lib/ImgCompressor.class.php');
$error = false;
$errorMessage = "";
include("session_includes.php");
$maxsize = 4194304;
$imgUploadStatus = false;
$fileUploadStatus = false;

if (isset($_POST['btn_save'])) {
    if (isset($_POST['txt_title']) && $_POST['txt_title'] != "") {
        $title = $_POST['txt_title'];
    } else {
        $error = true;
        $errorMessage .= "Please enter title.<br>";
    }
    if (isset($_FILES['upload']) && $_FILES['upload']['error'][0] != 4 /* 4 means there is no file selected*/) {
        $imgUploadStatus = true;
        $directory_name = "../digital-card/theme/";
        $extension = array('.jpg', '.JPG', '.jpeg', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG');
        $maxsize = 2097152;
        $total = count($_FILES['upload']['name']);
        for ($i = 0; $i < $total; $i++) {
            $filename = $_FILES['upload']['name'][$i];
            $extensionStatus = $validate->validateFileExtension($filename, $extension);
            if (!$extensionStatus) {
                $error = true;
                $errorMessage = "Please select valid file extension";
            }
            if (($_FILES['upload']['size'][$i] >= $maxsize)) {
                $error = true;
                $errorMessage = 'File too large. File must be less than 2 megabytes.';
            }
        }
    }
    if (isset($_FILES['upload-file']) && $_FILES['upload-file']['error'][0] != 4 /* 4 means there is no file selected*/) {
        $fileUploadStatus = true;
        $directory_file_name = "../digital-card/theme/";
        $extension = array('.jpg', '.JPG', '.jpeg', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG');
        $maxsize = 2097152;
        $total = count($_FILES['upload-file']['name']);
        for ($i = 0; $i < $total; $i++) {
            $filename = $_FILES['upload-file']['name'][$i];
            $extensionStatus = $validate->validateFileExtension($filename, $extension);
            if (!$extensionStatus) {
                $error = true;
                $errorMessage = "Please select valid file extension";
            }
            if (($_FILES['upload-file']['size'][$i] >= $maxsize)) {
                $error = true;
                $errorMessage = 'File too large. File must be less than 2 megabytes.';
            }
        }
    }

    if (!$error) {
        if ($imgUploadStatus) {
            $digits = 4;
            $randomNum = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
            $newimgname = "";

            for ($i = 0; $i < $total; $i++) {
                $filearray = array();
                $tmpFilePath = $_FILES['upload']['tmp_name'][$i];
                $file_original_name = substr($_FILES['upload']['name'][$i], 0, strrpos($_FILES['upload']['name'][$i], '.'));
                $file_extension = substr($_FILES['upload']['name'][$i], (strrpos($_FILES['upload']['name'][$i], '.') + 1));
                $newimgname = $file_original_name . "$" . $randomNum . '.' . $file_extension;
                $cover_name = str_replace([' ', '_'], '-', $newimgname);
                $newPath = $directory_name . $cover_name;
                // Compress Image
                $upload = compressImage($tmpFilePath,$newPath,60);
                if(!$upload){
                    $error = true;
                    $errorMessage = "Issue while uploading\nNote: File too large. File must be less than 4 megabytes";
                }

            }
            }
        if ($fileUploadStatus) {
            $digits = 4;
            $randomNum = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
            $newfilename = "";

            for ($i = 0; $i < $total; $i++) {
                $filearray = array();
                $tmpFilePath1 = $_FILES['upload-file']['tmp_name'][$i];
                $file_original_name = substr($_FILES['upload-file']['name'][$i], 0, strrpos($_FILES['upload-file']['name'][$i], '.'));
                $file_extension = substr($_FILES['upload-file']['name'][$i], (strrpos($_FILES['upload-file']['name'][$i], '.') + 1));
                $newimgname = $file_original_name . "$" . $randomNum . '.' . $file_extension;
                $cover_name1 = str_replace([' ', '_'], '-', $newimgname);
                $newPath1 = $directory_file_name . $cover_name1;
                // Compress Image
                $upload = compressImage($tmpFilePath1,$newPath1,60);
                if(!$upload){
                    $error = true;
                    $errorMessage = "Issue while uploading\nNote: File too large. File must be less than 4 megabytes";
                }

            }
        }
        if(!$error){
           /* $data = "data";
            $compressed = "compressed";
            $img_name = "name";
            $cover_name = $decode->$data->$compressed->$img_name;
            $cover_name1 = $decode1->$data->$compressed->$img_name;*/
            $update_photo = $manage->addTheme($title, $cover_name,$cover_name1);
            if ($update_photo) {
                header('location:mobile-theme.php');
            }
        }
        else{
            $error = true;
            $errorMessage = "Issue while uploading\nNote: File too large. File must be less than 4 megabytes";
        }

    }
}

$get_result = $manage->displayThemeDetails();
if ($get_result != null) {
    $count = mysqli_num_rows($get_result);
} else {
    $count = 0;
}

if (isset($_GET['delete_data'])) {
    $delete_data = $security->decrypt($_GET['delete_data']);
    $img_path = '../digital-card/theme/' . $_GET['img_path'];
    $thumb_img = '../digital-card/theme/' . $_GET['thumb_img'];
    if (file_exists($img_path)) {
        unlink('../digital-card/theme/' . $_GET['img_path'] . '');
        $status = $manage->deleteThemeImage($delete_data);
    } else {
        $status = $manage->deleteThemeImage($delete_data);
    }
    if (file_exists($thumb_img)) {
        unlink('../digital-card/theme/' . $_GET['thumb_img'] . '');
        $status = $manage->deleteThemeImage($delete_data);
    } else {
        $status = $manage->deleteThemeImage($delete_data);
    }
    if ($status) {
        header('location:mobile-theme.php');
    }
}

?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">

<head runat="server">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Mobile theme</title>
    <?php include "assets/common-includes/header_includes.php" ?>
    <style>
        input[type="file"] {
            display: block;
        }
    </style>
</head>

<body>
    <?php include "assets/common-includes/header.php" ?>
    <?php include "assets/common-includes/left_menu.php" ?>
    <section class="content">
        <div class="clearfix">
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                    <div class="row margin_div_web">
                        <div class="card">
                            <div class="header">
                                <div class="row cust-row">
                                    <?php if (isset($_GET['display_data'])) { ?>
                                    <div class="col-lg-7">
                                        <h2>
                                            Update theme
                                        </h2>
                                    </div>
                                    <?php } else { ?>
                                    <div class="col-lg-7">
                                        <h2>
                                            Add Theme
                                        </h2>
                                    </div>
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
                                        <label>Upload Theme Image</label> <span class="required_field">*</span>

                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="file" id="upload" name="upload[]"
                                                    accept=".png, .jpg, .jpeg,.JPG,.PNG,.JPEG" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="form-label">Upload Thumbnail</label> <span
                                            class="required_field">*</span>

                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="file" id="upload-file" name="upload-file[]"
                                                    accept=".png, .jpg, .jpeg,.JPG,.PNG,.JPEG" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="form-label">Title</label>

                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input name="txt_title" class="form-control" placeholder="Title"
                                                    value="<?php if (isset($title)) echo $title; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="form-group form_inline">
                                            <?php if (isset($_GET['display_data'])) { ?>
                                            <div>
                                                <input value="Update" type="submit" name="btn_update"
                                                    class="btn btn-primary waves-effect">
                                            </div>
                                            <?php } else { ?>
                                            <div>
                                                <button type="submit" name="btn_save"
                                                    class="btn btn-primary waves-effect">Add
                                                </button>
                                            </div>
                                            <?php } ?>
                                            &nbsp;&nbsp;
                                            <div>
                                                <a href="service.php" class="btn btn-default">Cancel</a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row margin_div_web">
                        <div class="card">
                            <div class="header">
                                <h2>
                                    Manage Theme <span class="badge"><?php
                                if (isset($count)) echo $count;
                                ?></span>
                                </h2>
                            </div>
                            <div class="body">
                                <div style="overflow-x: auto">
                                    <table id="dtHorizontalVerticalExample"
                                        class="table table-striped table-bordered table-sm " cellspacing="0"
                                        width="100%">
                                        <thead>
                                            <tr class="back-color">
                                                <th>Title</th>
                                                <th>Uploaded By</th>
                                                <th>Background Image</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                if ($get_result != null) {
                                    while ($result_data = mysqli_fetch_array($get_result)) {
                                        ?>
                                            <tr>
                                                <td><?php echo $result_data['title']; ?></td>
                                                <td><?php echo $result_data['name']; ?></td>
                                                <td><a target="_blank"
                                                        href=<?php echo '../theme/' . $result_data['img_name'] . ''; ?>>View
                                                        Background Image</a>
                                                    <br>
                                                    <a target="_blank"
                                                        href=<?php echo '../theme/' . $result_data['thumb_img'] . ''; ?>>View
                                                        Thumbnail Image</a></td>
                                                <td>
                                                    <a title="Delete"
                                                        href="mobile-theme.php?delete_data=<?php echo $security->encrypt($result_data['id']); ?>&img_path=<?php echo $result_data['img_name']; ?>&thumb_img=<?php echo $result_data['thumb_img']; ?>"
                                                        onclick="return confirm('Are You sure you want to delete?');" <i
                                                        class="fas fa-trash-alt"></i></a>
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
                                            <?php } ?>
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
    <?php include "assets/common-includes/footer_includes.php" ?>
    <script type="text/javascript">
        window.history.forward(1);
        document.addEventListener("onkeydown", my_onkeydown_handler);

        function my_onkeydown_handler() {
            switch (event.keyCode) {
                case 116: // 'F5'
                    event.returnValue = false;
                    event.keyCode = 0;
                    window.status = "We have disabled F5";
                    break;
            }
        }
    </script>
</body>

</html>