<?php
ob_start();
ini_set('memory_limit', '-1');
include "../controller/ManageUser.php";
$manage = new ManageUser();
include "../controller/validator.php";
$validate = new Validator();
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include '../sendMail/sendMail.php';
$controller = new Controller();
$con = $controller->connect();

include("android-login.php");
$section_id = 5;
$alreadySaved = false;

$alreadySavedVideo = false;
$section_video_id = 4;

$maxsize = 4194304;
include_once('lib/ImgCompressor.class.php');


$error = false;
$errorMessage = "";
$time = date('Y-m-d H:i:s');
$errorClient = false;
$errorMessageClient = "";
include("session_includes.php");
include "validate-page.php";
$imgUploadStatus = false;
/*This Method used for display the data in Manage table.*/
$get_result = $manage->displayClientReviewDetails();
if ($get_result != null) {
    $count = mysqli_num_rows($get_result);
} else {
    $count = 0;
}

/*This method used for update the Branch data*/
if (isset($_POST['btn_save_client'])) {
    if (isset($_POST['rd_rating']) && $_POST['rd_rating'] != "") {
        $rd_rating = mysqli_real_escape_string($con, $_POST['rd_rating']);
    } else {
        $error = true;
        $errorMessage .= "Please give rating.<br>";
    }

    if (isset($_POST['txt_name']) && $_POST['txt_name'] != "") {
        $name = mysqli_real_escape_string($con, $_POST['txt_name']);
    } else {
        $error = true;
        $errorMessage .= "Please enter name.<br>";
    }
    if (isset($_POST['txt_des']) && $_POST['txt_des'] != "") {
        $description = mysqli_real_escape_string($con, $_POST['txt_des']);
    } else {
        $error = true;
        $errorMessage .= "Please enter description.<br>";
    }
    /*Start of pdf upload*/
    /*echo $_FILES['upload']['error'][0];
        die();*/
    if (isset($_FILES['upload']) && $_FILES['upload']['error'][0] != 4 /* 4 means there is no file selected*/) {
        $imgUploadStatus = true;
        $directory_name = "uploads/" . $session_email . "/testimonials/client_review/";
        $extension = array('.jpg', '.JPG', '.jpeg', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG');
        $total = count($_FILES['upload']['name']);
        for ($i = 0; $i < $total; $i++) {
            $filename = $_FILES['upload']['name'][$i];
            $extensionStatus = $validate->validateFileExtension($filename, $extension);
            if (!$extensionStatus) {
                $error = true;
                $errorMessage = "Please select valid file extension";
            }
        }
    }
    if (!$error) {
        $cover_name = "";
        $digits = 4;
        $randomNum = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        $newfilename = "";
        if ($imgUploadStatus) {
            for ($i = 0; $i < $total; $i++) {
                $filearray = array();
                /*   $setting = array(
                       'directory' => $directory_name, // directory file compressed output
                       'file_type' => array( // file format allowed
                           'image/jpeg',
                           'image/png',
                           'image/gif'
                       )
                   );
                   $ImgCompressor = new ImgCompressor($setting);
                   $result = $ImgCompressor->run($tmpFilePath1, 'jpg', 5);
               }

               $key = json_encode($result);
               $decode = json_decode($key);
               $value = 'status';
               $fileStatus = $decode->$value;
               if ($fileStatus == "success") {
                   $data = "data";
                   $compressed = "compressed";
                   $img_name = "name";
                   $cover_name = $decode->$data->$compressed->$img_name;
               } else {
                   $error = true;
                   $errorMessage = "Issue while uploading\nNote: File too large. File must be less than 4 megabytes";
               }*/
                $tmpFilePath = $_FILES['upload']['tmp_name'][$i];
                $file_original_name = substr($_FILES['upload']['name'][$i], 0, strrpos($_FILES['upload']['name'][$i], '.'));
                $file_extension = substr($_FILES['upload']['name'][$i], (strrpos($_FILES['upload']['name'][$i], '.') + 1));
                $newimgname = $file_original_name . "$" . $randomNum . '.' . $file_extension;
                $cover_name = str_replace([' ', '_'], '-', $newimgname);
                $newPath = $directory_name . $cover_name;
                // Compress Image
                $upload = compressImage($tmpFilePath, $newPath, 60);
                if (!$upload) {
                    $error = true;
                    $errorMessage = "Issue while uploading\nNote: File too large. File must be less than 4 megabytes";
                }
            }
        }
        if(!$error){

            $status = $manage->addClientsReview($id, $name, $description, $cover_name,$rd_rating,$time);
            if ($status) {
                $_SESSION['red_dot']['client_review'] = false;
                if ($count == 0) {
                    $_SESSION['total_percent'] = $_SESSION['total_percent'] + 10;
                }
                /*This Method used for display the data in Manage table.*/
                $get_result = $manage->displayClientReviewDetails();
                if ($get_result != null) {
                    $count = mysqli_num_rows($get_result);
                } else {
                    $count = 0;
                }
                $rd_rating = "";
                $name = "";
                $newFile = "";
                $description = "";
                $error = false;
                $errorMessage = $_SESSION['menu']['s_client_review_tab'] . " added successfully";
            } else {
                $error = true;
                $errorMessage = "Issue while adding details, Please try again.";
            }
        }
    }
}

$imgUpload = false;
/*This Method used for display the data in Manage table.*/

/*Edit Clients*/
$errorClient = false;
$errorMessageClient = "";


/*Edit Clients End*/


/*Edit Clients Review*/
if (isset($_GET['id'])) {
    $get_id = $security->decrypt($_GET['id']);
    $form_data_review = $manage->getClintReviewDetails($get_id);
    $name = $form_data_review['name'];
    $description = $form_data_review['description'];
    $img_name = $form_data_review['img_name'];
    $rd_rating = $form_data_review['rating_number'];
    $updateImage = 'uploads/' . $session_email . '/testimonials/client_review/' . $form_data_review['img_name'];
}

$imgUploadStatus = false;
/*This method used for update the Branch data*/
if (isset($_POST['btn_update_client'])) {

    if (isset($_POST['rd_rating']) && $_POST['rd_rating'] != "") {
        $rd_rating = mysqli_real_escape_string($con, $_POST['rd_rating']);
    } else {
        $error = true;
        $errorMessage .= "Please give rating.<br>";
    }

    if (isset($_POST['txt_name']) && $_POST['txt_name'] != "") {
        $name = mysqli_real_escape_string($con, $_POST['txt_name']);
    } else {
        $error = true;
        $errorMessage .= "Please enter name.<br>";
    }
    if (isset($_POST['txt_des']) && $_POST['txt_des'] != "") {
        $description = mysqli_real_escape_string($con, $_POST['txt_des']);
    } else {
        $error = true;
        $errorMessage .= "Please enter description.<br>";
    }
    /*Start of pdf upload*/
    /*echo $_FILES['upload']['error'][0];
        die();*/
    if (isset($_FILES['upload']) && $_FILES['upload']['error'][0] != 4 /* 4 means there is no file selected*/) {
        $imgUploadStatus = true;
        $directory_name = "uploads/" . $session_email . "/testimonials/client_review/";
        $extension = array('.jpg', '.JPG', '.jpeg', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG');
        $total = count($_FILES['upload']['name']);
        for ($i = 0; $i < $total; $i++) {
            $filename = $_FILES['upload']['name'][$i];
            $extensionStatus = $validate->validateFileExtension($filename, $extension);
            if (!$extensionStatus) {
                $error = true;
                $errorMessage = "Please select valid file extension";
            }
        }
    }
    if (!$error) {
        $digits = 4;
        $randomNum = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        $newfilename = "";
        if ($imgUploadStatus) {

            for ($i = 0; $i < $total; $i++) {
                $filearray = array();
                $tmpFilePath = $_FILES['upload']['tmp_name'][$i];
                $file_original_name = substr($_FILES['upload']['name'][$i], 0, strrpos($_FILES['upload']['name'][$i], '.'));
                $file_extension = substr($_FILES['upload']['name'][$i], (strrpos($_FILES['upload']['name'][$i], '.') + 1));
                /*echo $file_original_name;
                die();*/
                $newfilename = $file_original_name . "$" . $randomNum . '.' . $file_extension;
                $removeSpace = str_replace(array(' ', '_'), array('-', '-'), $newfilename);
                $newFile = strtolower($removeSpace);
                $newPath = $directory_name . $newFile;
                    $success = compressImage($tmpFilePath, $newPath, 60);
                    if ($success) {
                        if (file_exists($updateImage) && $form_data_review['img_name'] !="") {
                            unlink('uploads/' . $session_email . '/testimonials/client_review/' . $form_data_review['img_name'] . '');
                        }
                    }else{
                        $error = true;
                        $errorMessage = "Issue while uploading\nNote: File too large. File must be less than 4 megabytes";
                    }
            }
        }
        if(!$error){
            $status = $manage->updateClientReview($name, $description, $newFile, $security->decrypt($_GET['id']),$rd_rating);
            if ($status) {
                $error = false;
                $errorMessage = $_SESSION['menu']['s_client_review_tab'] ." updated successfully";
                if (isset($_GET['id'])) {
                    $get_id = $security->decrypt($_GET['id']);
                    $form_data_review = $manage->getClintReviewDetails($get_id);
                    $name = $form_data_review['name'];
                    $description = $form_data_review['description'];
                    $img_name = $form_data_review['img_name'];
                    $rd_rating = $form_data_review['rating_number'];
                    $updateImage = 'uploads/' . $session_email . '/testimonials/client_review/' . $form_data_review['img_name'];
                }

            } else {
                $error = true;
                $errorMessage = "Issue while updating details, Please try again.";
            }
        }
    }

}


if (isset($_GET['publishData']) && (isset($_GET['action']))) {
    $action = $_GET['action'];
    $publishData = $security->decrypt($_GET['publishData']);
    if ($action == "unpublish") {
        $result = $manage->publishUnpublish($publishData, 0, $manage->clientReviewTable);
    } else {
        $result = $manage->publishUnpublish($publishData, 1, $manage->clientReviewTable);
    }
    if ($android_url != "") {
        header('location:clients_review.php?' . $android_url);
    } else {
        header('location:clients_review.php');
    }
}

if (isset($_GET['delete_data'])) {
    $delete_data = $security->decrypt($_GET['delete_data']);
    $img_path = $_GET['img_path'];
    $updateImage = 'uploads/' . $session_email . '/testimonials/client_review/' . $img_path;
    if (file_exists($updateImage)) {
        unlink('uploads/' . $session_email . '/testimonials/client_review/' . $_GET['img_path'] . '');
        $status = $manage->deleteClientsReview($delete_data);
    } else {
        $status = $manage->deleteClientsReview($delete_data);
    }
    if ($status) {
        /*This Method used for display the data in Manage table.*/
        $get_result = $manage->displayClientReviewDetails();
        if ($get_result != null) {
            $count = mysqli_num_rows($get_result);
        } else {
            $count = 0;
        }
        if($count == 0) {
            $_SESSION['total_percent'] = $_SESSION['total_percent'] - 10;
        }
        if ($android_url != "") {
            header('location:clients_review.php?' . $android_url);
        } else {
            header('location:clients_review.php');
        }
    }
}


/*This is for image gallery*/

$get_data = $manage->countService($id, $section_id);
if ($get_data) {
    $alreadySaved = true;
    $display_result = $manage->getServiceStatus($id, $section_id);
    /*$array = explode(",",$statusOnOFF);*/
}
if($count ==0){
    $_SESSION['red_dot']['client_review'] = true;
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
            header('location:clients_review.php?' . $android_url);
        } else {
            header('location:clients_review.php');
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
            header('location:clients_review.php?' . $android_url);
        } else {
            header('location:clients_review.php');
        }
    } else {
        $error = true;
        $errorMessage = "Issue while adding details, Please try again.";
    }
}

$whatsapp_share_model_icon = "data:image/png;base64,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";
$copy_model_icon = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAQAAAAAYLlVAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAAmJLR0QAAKqNIzIAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAAHdElNRQfjBh0OFzPB+nHkAAAB1UlEQVRo3u2ZzysEYRjHP8suLnJYEa4OisPu0Z+g5KIUJ5EcnOQvkOTgIslJS0nOpEhxEQnJRQ6iJIfdpaTN793XYV/b7JrsO++M3S3vdw4zvTPP+/3M8877vPUOqMnPCKe8ILSPJLfM06Tol6MKNl1YW48YIR2AKY/sBYIrKq1d+xTsK4lTDbyywr1eCoEAXTQD0Meqs9CwZB/TNk+rnk8EghlrY5lCYAIBwI5LgCgPANRYG/0KgZeM0sMaZy4BSkBRBIJFa5PKEPypDMDPj7CCfjppdNHnGxcscKgX3MiZJ/UuxaRN7zYfYbZ8HHhYdAdUALKHoIN2ABKc8Kk9BEHCAIyzRMpZ6LRcOFu1zdOalTloyZ+B7FlQC0Ccc5cA30W7Lv+jdtPQYdpsJORZYa0teh0wAAbAABgAA2AADIABMAAGwAAYAANgAAoLUKbU9GcKEQQgVgyAbrbYl/sFu9YbKnvF7lXPKgF5vcd24TPgp1xendKT2T8pYAbuGGKQOOss85HLVhhFiNjf+Gd1wACUPsATALUqW6y/qk2eH/M/mj0NjwAIcMA679r2DfTK17lwGlrFtYf/CyZ06MPEPLLfyFR/h2pijhuSLqyfOWY4U/3z6AtzfXHIAOuU4QAAACV0RVh0ZGF0ZTpjcmVhdGUAMjAxOS0wNi0yOVQxMjoyMzo1MSswMjowMEofE6EAAAAldEVYdGRhdGU6bW9kaWZ5ADIwMTktMDYtMjlUMTI6MjM6NTErMDI6MDA7QqsdAAAAGXRFWHRTb2Z0d2FyZQB3d3cuaW5rc2NhcGUub3Jnm+48GgAAAABJRU5ErkJggg==";
?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php include "assets/common-includes/total_count.php" ?>
    <title><?php echo $_SESSION['menu']['s_clients'] ?> - <?php echo $_SESSION['menu']['s_client_review_tab'] ?></title>
    <?php include "assets/common-includes/header_includes.php" ?>
    <link rel="stylesheet" type="text/css" href="assets/css/component.css"/>
    <!--[if IE]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!-- remove this if you use Modernizr -->
    <script>(function (e, t, n) {
            var r = e.querySelectorAll("html")[0];
            r.className = r.className.replace(/(^|\s)no-js(\s|$)/, "$1js$2")
        })(document, window, 0);</script>
    <style>
/*rating start*/
[type="radio"]:checked + label:after, [type="radio"].with-gap:checked + label:before, [type="radio"].with-gap:checked + label:after{
    border: none;
}
[type="radio"]:not(:checked) + label:before, [type="radio"]:not(:checked) + label:after{
    border: none;
}
[type="radio"]:checked + label:after, [type="radio"].with-gap:checked + label:after{
    background: none;
}
.rate {
    float: left;
    text-align: left;
    height: 37px;
}
.rate > label {
    height: 46px;
}
.rate > input:checked ~ label, .rate input[checked="checked"] ~ label {
    color: #ffc700;
}

.rate:not(:checked) > label {
    height: 46px;
    float: right;
    width: 1em;
    overflow: hidden;
    white-space: nowrap;
    cursor: pointer;
    font-size: 40px;
    color: #ccc;
}
.rate:not(:checked) > label:before {
    /* content: 'â˜… '; */
    content: "\2605";
}




/*rating end*/
        .tooltip {
            position: relative;
            display: inline-block;
            opacity: 1;
        }

        .tooltip .tooltiptext {
            visibility: hidden;
            width: 140px;
            background-color: #555;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            bottom: 107%;
            left: 50%;
            margin-left: -75px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .tooltip .tooltiptext::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: #555 transparent transparent transparent;
        }

        .tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }

        .width_50{
            width: 50%;
        }
        #copy_review{
            opacity: 0;
            height: 0;
        }

        .div_width_50{
            width: 49%;
            display: inline-block;
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
        <div class="clearfix padding_bottom_46">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding_zero padding_zero_both">
            <div class="row">
                <div class="card <?php if($android_url !='') echo "no_back_card"; ?>">

                    <div class="body custom_card_padding">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs tab-nav-right <?php if($android_url !='') echo "d-card-none"; ?>" role="tablist" >
                            <li role="presentation"><a class="custom_nav_tab"
                                    href="testimonial.php<?php if ($android_url != "") echo "?" . $android_url; ?>"><?php echo $_SESSION['menu']['s_client_name'] ?><?php if($_SESSION['red_dot']['client_name'] == true) echo '<div class="remaining_sub_form_dot"></div>' ?></a>
                            </li>
                            <li role="presentation" class="active"><a class="custom_nav_tab"
                                    href="clients_review.php<?php if ($android_url != "") echo "?" . $android_url; ?>"
                                    data-toggle="tab"><?php echo $_SESSION['menu']['s_client_review_tab'] ?><?php if($_SESSION['red_dot']['client_review'] == true) echo '<div class="remaining_sub_form_dot"></div>' ?></a>
                            </li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade in active" id="testimonial">
                                <div class="clearfix">
                                    <div
                                        class="col-lg-5 col-md-5 col-sm-12 col-xs-12 padding_zero margin_div padding_zero_both">
                                        <div class="card">
                                            <div class="header client_padd">
                                                <div class="col-md-12 col-xs-12 padding_zero padding_zero_both" style="padding: 0" >
                                                    <div class="row">
                                                        <?php if (isset($_GET['id'])) { ?>
                                                            <div class="m-b-0 col-md-6 col-xs-6">
                                                                    <h2>
                                                                        Update <?php echo $_SESSION['menu']['s_client_review_tab'] ?>
                                                                    </h2>
                                                            </div>
                                                        <?php } else { ?>
                                                            <div class="m-b-0 col-md-6 col-xs-5">
                                                                    <h2>
                                                                        Add <?php echo $_SESSION['menu']['s_client_review_tab'] ?>
                                                                    </h2>
                                                            </div>
                                                        <?php } ?>
                                                        <div class="m-b-0 col-md-6 col-xs-7 text-right">
                                                            <button class="btn btn-success" data-toggle="modal"
                                                                    data-target="#modalOpen"><i class="fa fa-paper-plane" aria-hidden="true"></i> Share Feedback Link</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="body">
                                                <form id="form_validation" method="POST" action=""
                                                      enctype="multipart/form-data">
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
                                                        <label class="form-label">Select Rate</label> <span
                                                            class="required_field">*</span>

                                                        <div class="form-group form-float" style="overflow: hidden">

                                                                    <div class="rate">
                                                                        <input type="radio" value="5" name="rd_rating" id="rating-5" <?php if(isset($rd_rating) && $rd_rating =="5") echo "checked"; ?>>
                                                                        <label for="rating-5"></label>
                                                                        <input type="radio" value="4" name="rd_rating" id="rating-4" <?php if(isset($rd_rating) && $rd_rating =="4") echo "checked"; ?>>
                                                                        <label for="rating-4"></label>
                                                                        <input type="radio" value="3" name="rd_rating" id="rating-3" <?php if(isset($rd_rating) && $rd_rating =="3") echo "checked"; ?>>
                                                                        <label for="rating-3"></label>
                                                                        <input type="radio" value="2" name="rd_rating" id="rating-2" <?php if(isset($rd_rating) && $rd_rating =="2") echo "checked"; ?>>
                                                                        <label for="rating-2"></label>
                                                                        <input type="radio" value="1" name="rd_rating" id="rating-1" <?php if(isset($rd_rating) && $rd_rating =="1") echo "checked"; ?>>
                                                                        <label for="rating-1"></label>
                                                                    </div>


                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label class="form-label">Name</label> <span
                                                            class="required_field">*</span>

                                                        <div class="form-group form-float">
                                                            <div class="form-line">
                                                                <input name="txt_name" class="form-control"
                                                                       placeholder="Name Of Client"
                                                                       value="<?php if (isset($name)) echo htmlspecialchars($name); ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label class="form-label">Description</label> <span
                                                            class="required_field">*</span>

                                                        <div class="form-group form-float">
                                                            <div class="form-line">
                                        <textarea name="txt_des" rows="4" cols="50" class="form-control"
                                                  placeholder="Please Enter Service Description"><?php if (isset($description)) echo htmlspecialchars($description); ?></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div>

                                                        <div class="form-group form-float">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label class="form-label">Upload Image</label><br>
                                                                    <input type="file" name="upload[]" id="file-7"
                                                                           class="inputfile inputfile-6"
                                                                           data-multiple-caption="{count} files selected"
                                                                           multiple
                                                                           onchange="readURL(this);"
                                                                           accept=".png, .jpg, .jpeg,.JPG,.PNG,.JPEG"/>
                                                                    <label for="file-7"><span></span> <img id="blah"
                                                                                                           class="input_choose_file blah"
                                                                                                           src=""
                                                                                                           alt=""/><strong
                                                                            class="input_choose_file">
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                 width="20"
                                                                                 height="17" viewBox="0 0 20 17">
                                                                                <path
                                                                                    d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/>
                                                                            </svg>
                                                                            Choose a file&hellip;</strong></label>
                                                                </div>
                                                                <?php
                                                                if (!isset($_GET['id'])) {
                                                                    ?>
                                                                    <div class="col-md-6">
                                                                        <label>Default Image</label><br>
                                                                        <?php echo '<img src="uploads/user.png" style="width: 30%;"/>'; ?>
                                                                    </div>
                                                                <?php
                                                                }
                                                                ?>
                                                            </div>
                                                            <?php if (isset($_GET['id']) && $form_data_review['img_name'] != "") {
                                                                echo '<img src="uploads/' . $session_email . '/testimonials/client_review/' . $form_data_review['img_name'] . '" style="width: 20%;"/><br />';
                                                            } elseif (isset($_GET['id']) && $form_data_review['img_name'] == "") {
                                                                echo '<img src="uploads/user.png" style="width: 30%;"/>';
                                                            } ?>
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <div class="form-group form_inline">
                                                            <?php if (isset($_GET['id'])) { ?>
                                                                <div>
                                                                    <input value="Update" type="submit"
                                                                           name="btn_update_client"
                                                                           class="btn btn-primary waves-effect">
                                                                </div>
                                                            <?php } else { ?>
                                                                <div>
                                                                    <input value="Add" type="submit"
                                                                           name="btn_save_client"
                                                                           class="btn btn-primary waves-effect">
                                                                </div>
                                                            <?php } ?>
                                                            &nbsp;&nbsp;
                                                            <div>
                                                                <a href="clients_review.php<?php if ($android_url != "") echo "?" . $android_url; ?>"
                                                                   class="btn btn-default">Cancel</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="col-lg-7 col-md-7 col-sm-12 col-xs-12 padding_zero margin_div padding_zero_both">
                                        <!--<div class="freelancer_search_box padding_zero padding_zero_both" style="width: 100%">
                                        <div class="col-md-12">
                                            <form action="" method="post">
                                                <ul class="profile-ul">
                                                    <h4>Hide clients review from digital card.</h4>
                                                    <li class="li_event">
                                                        <div class="cust-div">
                                                            <input type="checkbox" name="type[]"
                                                                   value="digital_card"  <?php /*if ($display_result['digital_card'] == '1') {
                                                                echo 'checked="checked"';
                                                            } */ ?> > Clients review
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="cust-div">
                                                            <input type="checkbox" name="type[]"
                                                                   value="website" <?php /*if ($display_result['website'] == '1') {
                                                                echo 'checked="checked"';
                                                            } */ ?>>Website
                                                        </div>
                                                    </li>


                                                    <li class="li_event">
                                                        <?php /*if (isset($alreadySaved) && $alreadySaved) {
                                                            */ ?>
                                                            <button class="btn btn-primary waves-effect" name="update_chk"
                                                                    type="submit">
                                                                Save
                                                            </button>
                                                        <?php
                                        /*                                                        } else {
                                                                                                    */ ?>
                                                            <button class="btn btn-primary waves-effect" name="save_chk" type="submit">
                                                                Add
                                                            </button>
                                                        <?php
                                        /*                                                        }
                                                                                                */ ?>
                                                    </li>
                                                </ul>
                                            </form>
                                        </div>
                                    </div>-->
                                        <div class="card">
                                            <div class="header">
                                                <h2>
                                                    Manage <?php echo $_SESSION['menu']['s_client_review_tab'] ?> <span class="badge"><?php
                                                        if (isset($count)) echo $count;
                                                        ?></span>
                                                </h2>
                                            </div>
                                            <div class="body">
                                                <div style="overflow-x: auto">
                                                    <table id="dtHorizontalVerticalExample"
                                                           class="table table-striped table-bordered table-sm "
                                                           cellspacing="0"
                                                           width="100%">
                                                        <thead>
                                                        <tr class="back-color">
                                                            <th style="width: 30%">IMAGE</th>
                                                            <th class="visible-lg visible-md hidden-sm hidden-xs">NAME
                                                            </th>
                                                            <th class="visible-lg visible-md hidden-sm hidden-xs">
                                                                DESCRIPTION
                                                            </th>
                                                            <th class="visible-lg visible-md hidden-sm hidden-xs">
                                                                STATUS
                                                            </th>
                                                            <th>ACTION</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                        if ($get_result != null) {
                                                            while ($result_data = mysqli_fetch_array($get_result)) {
                                                                ?>
                                                                <tr>
                                                                    <td><?php if ($result_data['img_name'] != "") {
                                                                            echo '<img src="uploads/' . $session_email . '/testimonials/client_review/' . $result_data['img_name'] . '" style="width: 100%;"/><br />';
                                                                        } else {
                                                                            echo '<img src="uploads/user.png" style="width: 100%;"/>';
                                                                        } ?>
                                                                        <div
                                                                            class="hidden-lg hidden-md visible-sm visible-xs">
                                                                            <br>
                                                                            <b>NAME</b>
                                                                            : <?php echo wordwrap($result_data['name'], 20, "<br />\n"); ?>
                                                                            <br>
                                                                            <b>DESCRIPTION
                                                                                : </b><br><?php echo wordwrap($result_data['description'], 30, "<br />\n"); ?>
                                                                            <br>
                                                                            <b>STATUS</b> : <label
                                                                                class="label <?php if ($result_data['status'] == "0") {
                                                                                    echo "label-danger";
                                                                                } else {
                                                                                    echo "label-success";
                                                                                } ?>"><?php if ($result_data['status'] == 0) {
                                                                                    echo "Unpublished";
                                                                                } else {
                                                                                    echo "Published";
                                                                                } ?></label>
                                                                        </div>
                                                                    </td>
                                                                    <td class="visible-lg visible-md hidden-sm hidden-xs"><?php echo wordwrap($result_data['name'], 25, "<br />\n"); ?></td>
                                                                    <td class="visible-lg visible-md hidden-sm hidden-xs"><?php echo wordwrap($result_data['description'], 30, "<br />\n"); ?></td>
                                                                    <td class="visible-lg visible-md hidden-sm hidden-xs">
                                                                        <label
                                                                            class="label <?php if ($result_data['status'] == "0") {
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
                                                                                <a href="javascript:void(0);"
                                                                                   class="dropdown-toggle"
                                                                                   data-toggle="dropdown"
                                                                                   role="button" aria-haspopup="true"
                                                                                   aria-expanded="false">
                                                                                    <i class="material-icons">more_vert</i>
                                                                                </a>
                                                                                <ul class="dropdown-menu pull-right">
                                                                                    <li>
                                                                                        <a href="clients_review.php?id=<?php echo $security->encrypt($result_data['id']);
                                                                                        if ($android_url != "") echo "&" . $android_url; ?>"
                                                                                        <i class="fas fa-edit"></i>&nbsp;&nbsp;Edit</a>
                                                                                    </li>
                                                                                    <li>
                                                                                        <a href="clients_review.php?delete_data=<?php echo $security->encrypt($result_data['id']) ?>&img_path=<?php echo $result_data['img_name'];
                                                                                        if ($android_url != "") echo "&" . $android_url; ?>"
                                                                                           onclick="return confirm('Are You sure you want to delete?');"
                                                                                        <i
                                                                                            class="fas fa-trash-alt"></i>&nbsp;&nbsp;Delete</a>
                                                                                    </li>
                                                                                    <li>
                                                                                        <a onclick="return confirm('Are You sure you want to <?php echo $result_data['status'] == 0 ? 'publish' : 'unpublish'; ?>?');"
                                                                                           href="clients_review.php?publishData=<?php echo $security->encrypt($result_data['id']) ?>&action=<?php echo $result_data['status'] == 0 ? "publish" : "unpublish";
                                                                                           if ($android_url != "") echo "&" . $android_url; ?>"><i
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
                                                        <?php
                                                        }
                                                        ?>
                                                        </tbody>
                                                    </table>
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

        </div>
</div>

    </section>

    <?php include "assets/common-includes/footer_includes.php" ?>
    <div class="modal fade" id="modalOpen" role="dialog">
        <div class="modal-dialog modal-sm">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header cust-upi-madal">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Share FeedBack Link</h4>
                </div>
                <div class="modal-body">
                    <div class="body">
                        <div id="team-message-success" class="alert alert-success">
                        </div>
                        <div id="team-message-validate" class="alert alert-danger">
                        </div>
                           <!-- <div class="form-group">
                                <label class="form-label">Customer Review</label> <span
                                    class="required_field">*</span>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <textarea name="customer_review" rows="6" cols="50" class="form-control">Hello Sir,

It Was nice working for you and fulfill your requirement, our team is always available there for you. Please give us your valuable feedback in order to improve our service even further,  your point of view matters to us.

Please click on the following link in order to share your valuable feedback</textarea>
                                    </div>
                                </div>
                            </div>
                        <div class="form-group">
                            <button type="button" onclick="changeReview()"
                                   class="btn btn-primary waves-effect">Save Review & Share</button>
                        </div>-->

                            <div class="form-group" >
                                <label class="form-label">Share Link</label>
                                <div class="row">
                                    <div class="col-md-4 col-xs-6">
                                        <a onclick="shareWhatsapp()" href="#"><img class="width_50 " src="<?php echo $whatsapp_share_model_icon;?>"></a>
                                        <p>Whatsapp</p>
                                    </div>
                                    <div class="col-md-4 tooltip col-xs-6">
                                        <img class="width_50 width_resp" onclick="myFunction()" onmouseout="outFunc()"
                                             src="<?php echo $copy_model_icon;?>">
                                        <span class="tooltiptext" id="myTooltip">Copy to clipboard</span>
                                        <p>Copy Link</p>

                                    </div>
                                    <div id="team-message">
                                        <?php

                                        echo '<input type="text" id="copy_review" value="' . $token_url.'">';
                                        echo '<input type="hidden" name="whatsapp_review" value="' . $token_url.'">';
                                        ?>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function myFunction() {
            var copyText = document.getElementById("copy_review");
            copyText.select();
            copyText.setSelectionRange(0, 99999999);
            document.execCommand("copy");

            var tooltip = document.getElementById("myTooltip");
            tooltip.innerHTML = "Copied";
        }

        function outFunc() {
            var tooltip = document.getElementById("myTooltip");
            tooltip.innerHTML = "Copy to clipboard";
        }

    </script>
    <script>
        function changeReview() {
            var review = $('textarea[name=customer_review]').val();
            if (review.length < 1) {
                $("#team-message-validate").css({"display": "block"});
                $("#team-message-validate").html('Enter some value');
            }  else {
                $("#team-message-validate").css({"display": "none"});
                var dataString = "our_review=" + encodeURIComponent(review);
                $.ajax({
                    type: "POST",
                    url: "change_review_ajax.php",
                    data: dataString,
                    success: function (html) {
                        $("#team-message-success").css('display','block');
                        $("#share-div").css('display','block');
                        $("#team-message-success").html('Feedback saved successfully!');
                        $("#team-message").html(html);
                    }
                });
            }
        }
        $("#share-div").hide();
        $("#team-message-validate").hide();
        $("#team-message-success").hide();
        function shareWhatsapp(){
            var token = $('input[name=whatsapp_review]').val();
            window.location.href = "https://api.whatsapp.com/send?phone=&text="+encodeURIComponent(token);
        }
    </script>
</body>
</html>