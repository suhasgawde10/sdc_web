<?php
ob_start();
include "../controller/ManageUser.php";
$manage = new ManageUser();
include "../controller/validator.php";
$validate = new Validator();
include "../controller/EncryptDecrypt.php";
include '../sendMail/sendMail.php';
$security = new EncryptDecrypt();
$maxsize = 10485760;


if (!isset($_SESSION['email'])) {
    header('location:../login.php');
}

$error = false;
$errorMessage = "";
include("session_includes.php");

$imgUploadStatus = false;
/*This method used for update the Branch data*/
if (isset($_POST['btn_save'])) {
    if (isset($_POST['txt_title']) && $_POST['txt_title'] != "") {
        $title = $_POST['txt_title'];
    } else {
        $error = true;
        $errorMessage .= "Please enter title.<br>";
    }
    if (isset($_POST['txt_des']) && $_POST['txt_des'] != "") {
        $description = $_POST['txt_des'];
    } else {
        $error = true;
        $errorMessage .= "Please enter description.<br>";
    }
    if (isset($_FILES['upload']) && $_FILES['upload']['error'][0] != 4 /* 4 means there is no file selected*/) {
        $imgUploadStatus = true;
        $directory_name = "uploads/blog/";
        $extension = array('.jpg', '.JPG', '.jpeg', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG');

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
    }else {
        $error = true;
        $errorMessage = "Please select file";
    }
    /*echo "here";
    die();*/

    /*End of pdf*/
    if (!$error) {
        $digits = 4;
        $randomNum = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        $newfilename = "";
        if ($imgUploadStatus) {
            /*echo "here";
            die();*/
            /*unlink('uploads/' . $_SESSION['email'] . '/profile/' . $form_data['img_name'] . '');*/
            for ($i = 0; $i < $total; $i++) {
                $filearray = array();
                $tmpFilePath = $_FILES['upload']['tmp_name'][$i];
                $file_original_name = substr($_FILES['upload']['name'][$i], 0, strrpos($_FILES['upload']['name'][$i], '.'));
                $file_extension = substr($_FILES['upload']['name'][$i], (strrpos($_FILES['upload']['name'][$i], '.') + 1));
                $newfilename = $file_original_name . "$" . $randomNum . '.' . $file_extension;
                $removeSpace = str_replace([' ','_'], '-', $newfilename);
                $newPath = $directory_name . $removeSpace;
                if (!move_uploaded_file($tmpFilePath, $newPath)) {
                    $error = true;
                    $errorMessage = "Failed to upload file";
                }
            }
        }
        /*echo "here";
        die();*/
        $status = $manage->addBlog($_POST['txt_title'], $_POST['txt_des'],$_POST['txt_facebook'],$_POST['txt_insta'],$_POST['txt_keyword'],$_POST['txt_video'], $removeSpace);
        if ($status) {
            $error = false;
            $errorMessage = "details Added successfully";
            /*$url = "service.php";*/
            /*  header("Refresh:2; url=" . $url);*/
            header('location:blog.php');
        } else {
            $error = true;
            $errorMessage = "Issue while adding details, Please try again.";

        }
    }
}

/*This Method used for display the data in Manage table.*/
$get_result = $manage->displayBlogDetails();
if ($get_result != null) {
    $count = mysqli_num_rows($get_result);
} else {
    $count = 0;
}

if (isset($_GET['display_data'])) {
    $display_data = $security->decrypt($_GET['display_data']);
    $form_data = $manage->getBlogDetails($display_data);
    if($form_data!=null){
        $title = $form_data['title'];
        $description = $form_data['description'];
        $img_name = $form_data['img_file'];
        $facebook = $form_data['facebook'];
        $instagram = $form_data['instagram'];
        $keyword = $form_data['keyword'];
        $video = $form_data['video_file'];
    }

}
if(isset($_GET['delete_data'])){
    $delete_data = $security->decrypt($_GET['delete_data']);
    $img_path = $_GET['img_path'];
    unlink('uploads/blog/' . $_GET['img_path'] . '');
    $status = $manage->deleteBlog($delete_data);
    if($status){
        header('location:blog.php');
    }
}
if (isset($_GET['id']) && (isset($_GET['action']))) {
    $action = $_GET['action'];
    $get_id = $security->decrypt($_GET['id']);
    if ($action == "unpublish") {
        $result = $manage->publishUnpublish($get_id,0,$manage->blogTable);
    } else {
        $result = $manage->publishUnpublish($get_id,1,$manage->blogTable);
    }
    header('location:blog.php');
}

if (isset($_POST['btn_update'])) {
    if (isset($_POST['txt_title']) && $_POST['txt_title'] != "") {
        $title = $_POST['txt_title'];
    } else {
        $error = true;
        $errorMessage .= "Please enter title.<br>";
    }
    if (isset($_POST['txt_des']) && $_POST['txt_des'] != "") {
        $description = $_POST['txt_des'];
    } else {
        $error = true;
        $errorMessage .= "Please enter description.<br>";
    }
    /*Start of pdf upload*/
    /*echo $_FILES['upload']['error'][0];
      die();*/
    if (isset($_FILES['upload']) && $_FILES['upload']['error'][0] != 4 /*4 means there is no file selected*/) {
        $imgUploadStatus = true;
        $directory_name = "uploads/blog/";
        $extension = array('.jpg', '.JPG', '.jpeg', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG','.3gp','.flv');
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
    if (!$error) {
        $digits = 4;
        $randomNum = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        $removeSpace = "";
        if ($imgUploadStatus) {
            /*unlink('uploads/blog/' . $form_data['img_file'] . '');*/
            for ($i = 0; $i < $total; $i++) {
                $filearray = array();
                $tmpFilePath = $_FILES['upload']['tmp_name'][$i];
                $file_original_name = substr($_FILES['upload']['name'][$i], 0, strrpos($_FILES['upload']['name'][$i], '.'));
                $file_extension = substr($_FILES['upload']['name'][$i], (strrpos($_FILES['upload']['name'][$i], '.') + 1));
                $newfilename = $file_original_name . "$" . $randomNum . '.' . $file_extension;
                $removeSpace = str_replace([' ','_'], '-', $newfilename);
                $newPath = $directory_name . $removeSpace;
                if (!move_uploaded_file($tmpFilePath, $newPath)) {
                    $error = true;
                    $errorMessage = "Failed to upload file";
                }
            }
        }


        $status = $manage->updateBlog($title, $description, $_POST['txt_facebook'],$_POST['txt_insta'],$_POST['txt_keyword'],$_POST['txt_video'], $removeSpace, $security->decrypt($_GET['display_data']));
        /*echo "here";
        die();*/
        if ($status) {
            $error = false;
            $errorMessage = "details Updated successfully";
            /*$url = "service.php?id=" . $_GET['id'];
            header("Refresh:2; url=" . $url);*/
            header('location:blog.php');
        } else {
            $error = true;
            $errorMessage = "Issue while updating details, Please try again.";
        }
    }

}


?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Blog</title>
    <?php include "assets/common-includes/header_includes.php" ?>

    <style>
        input[type="file"] {
            display: block;
        }
    </style>
    <!--<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js"></script>
    <script>tinymce.init({selector:'textarea'});</script>-->
    <script src="https://unpkg.com/lite-editor@1.6.39/js/lite-editor.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/lite-editor@1.6.39/css/lite-editor.css">
    <script>
        import LiteEditor from 'lite-editor';

        const editor = new LiteEditor('.js-editor');
    </script>

</head>
<body>
<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>
<section class="content" id="editor">
    <div class="clearfix">
        <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
            <div class="row margin_div_web">
                <div class="card">
                    <div class="header">
                        <div class="row cust-row">
                            <?php if (isset($_GET['display_data'])) { ?>
                                <div class="col-lg-7"><h2>
                                        Update Blog
                                    </h2></div>
                            <?php } else { ?>
                                <div class="col-lg-7"><h2>
                                        Add Blog
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
                                <label class="form-label">Title</label>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input name="txt_title" class="form-control"
                                               placeholder="Title"
                                               value="<?php if (isset($title)) echo $title; ?>">
                                    </div>
                                </div>
                            </div>


                            <div>
                                <label class="form-label">Upload Image</label>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input class="form-control" type="file" id="upload" name="upload[]"
                                               multiple="multiple" accept=".png, .jpg, .jpeg,.JPG,.PNG,.JPEG"
                                               value="<?php if (isset($filename)) echo $filename; ?>">
                                        <?php if (isset($_GET['display_data'])) echo '<img src="uploads/blog/' . $form_data['img_file'] . '" style="width: 20%;"/><br />'; ?>
                                    </div>
                                </div>
                            </div>
                            <div id="edit">
                                <label class="form-label">Description</label>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <textarea name="txt_des" rows="4" class="form-control js-lite-editor" cols="50"
                                                  placeholder="Please Enter Service Description"><?php if (isset($description)) echo $description; ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="form-label">Keywords</label>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <textarea name="txt_keyword" class="form-control" placeholder="Keywords"><?php if (isset($keyword)) echo $keyword; ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="form-label">Facebook Url</label>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input name="txt_facebook" class="form-control"
                                               placeholder="Title"
                                               value="<?php if (isset($facebook)) echo $facebook; ?>">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="form-label">Instagram Url</label>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input name="txt_insta" class="form-control"
                                               placeholder="Instagram url"
                                               value="<?php if (isset($instagram)) echo $instagram; ?>">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="form-label">Video Url(optional)</label>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input name="txt_video" class="form-control"
                                               placeholder="youtube url"
                                               value="<?php if (isset($video)) echo $video; ?>">
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
                                        <a href="blog.php" class="btn btn-default">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
            <div class="row margin_div_web">
                <div class="card">
                    <div class="header">
                        <h2>
                            Manage Blog <span class="badge"><?php
                                if (isset($count)) echo $count;
                                ?></span>
                        </h2>
                    </div>
                    <div class="body">
                        <table id="dtHorizontalVerticalExample" class="table table-striped table-bordered table-sm " cellspacing="0"
                               width="100%">
                            <thead>
                            <tr class="back-color">
                                <th style="width: 30%">Image</th>
                                <th>Title</th>
                                <!--<th>Description</th>
                                <th>Facebook</th>
                                <th>Instagram</th>
                                <th>Keywords</th>-->
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            if ($get_result != null) {
                                while ($result_data = mysqli_fetch_array($get_result)) {
                                    ?>

                                    <tr>
                                        <td><?php /*echo '<img src=" style="width: 100%;"/><br />';*/
                                        echo '<a href="uploads/blog/' . $result_data['img_file'] . '" target="_blank">view image</a>';
                                        ?></td>
                                        <td><?php echo $result_data['title']; ?></td>
                                        <!--<td><?php /*echo $result_data['description']; */?></td>
                                        <td><?php /*echo $result_data['facebook']; */?></td>
                                        <td><?php /*echo $result_data['instagram']; */?></td>
                                        <td><?php /*echo $result_data['keyword']; */?></td>-->
                                        <td><label class="label <?php if ($result_data['status'] == "0") {
                                                echo "label-danger";
                                            } else {
                                                echo "label-success";
                                            } ?>"><?php if ($result_data['status'] == 0) {
                                                    echo "Unpublished";
                                                } else {
                                                    echo "Published";
                                                } ?></label></td>
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
                                                            <a href="blog.php?display_data=<?php echo $security->encrypt($result_data['id']); ?>"
                                                            <i class="fas fa-edit"></i>&nbsp;&nbsp;Edit</a></li>
                                                        <li>
                                                            <a href="blog.php?delete_data=<?php echo $security->encrypt($result_data['id']); ?>&img_path=<?php echo $result_data['img_file']; ?>"
                                                               onclick="return confirm('Are You sure you want to delete?');"
                                                            <i class="fas fa-trash-alt"></i>&nbsp;&nbsp;Delete</a>
                                                        </li>
                                                        <li>
                                                            <a onclick="return confirm('Are You sure you want to <?php echo $result_data['status'] == 0 ? 'publish' : 'unpublish'; ?>?');"
                                                               href="blog.php?id=<?php echo $security->encrypt($result_data['id']); ?>&action=<?php echo $result_data['status'] == 0 ? "publish" : "unpublish"; ?> "><i
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
                            <?php } ?>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include "assets/common-includes/footer_includes.php" ?>
<script>
    window.addEventListener('DOMContentLoaded', function () {
        new LiteEditor('.js-lite-editor', {
            disableEditorMode: true
        });
    });
</script>

<script type="text/javascript">
    window.history.forward(1);
    document.addEventListener("onkeydown", my_onkeydown_handler);
    function my_onkeydown_handler() {
        switch (event.keyCode) {
            case 116 : // 'F5'
                event.returnValue = false;
                event.keyCode = 0;
                window.status = "We have disabled F5";
                break;
        }
    }
</script>
</body>
</html>