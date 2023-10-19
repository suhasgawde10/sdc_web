<?php
error_reporting(0);
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
require_once("functions.php");

$maxsize = 4194304;
include_once('lib/ImgCompressor.class.php');


$alreadySaved = false;
$section_id = 4;
$alreadySavedVideo = false;
$section_video_id = 4;

include("android-login.php");

$error = false;
$errorMessage = "";

$errorClient = false;
$errorMessageClient = "";

$imgUploadStatus = false;
include("session_includes.php");
include "validate-page.php";

$imgUpload = false;
/*This Method used for display the data in Manage table.*/
$get_staus = $manage->displayClientDetails();
if ($get_staus != null) {
    $countClient = mysqli_num_rows($get_staus);
} else {
    $countClient = 0;
}

if (isset($_POST['btn_save'])) {

    if (isset($_POST['txt_name_client']) && $_POST['txt_name_client'] != "") {
        $txt_name_client = mysqli_real_escape_string($con, $_POST['txt_name_client']);
    } else {
        $error = true;
        $errorMessage .= "Please enter client name.<br>";
    }
    if (isset($_FILES['upload_img']) && $_FILES['upload_img']['error'][0] != 4 /* 4 means there is no file selected*/) {
        $imgUpload = true;
        $directory_name = "uploads/" . $session_email . "/testimonials/clients/";
        $extension = array('.jpg', '.JPG', '.jpeg', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG');
        $total = count($_FILES['upload_img']['name']);
        for ($i = 0; $i < $total; $i++) {
            $filename = $_FILES['upload_img']['name'][$i];
            $extensionStatus = $validate->validateFileExtension($filename, $extension);
            if (!$extensionStatus) {
                $error = true;
                $errorMessage = "Please select valid file extension";
            }
            if (($_FILES['upload_img']['size'][$i] >= $maxsize)) {
                $error = true;
                $errorMessage = 'File too large. File must be less than 4 megabytes.';
            }

        }
    } else {
        $error = true;
        $errorMessage = "Please select file";
    }
    if (!$error) {
        $digits = 4;
        $randomNum1 = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        $newfilename1 = "";
        if ($imgUpload) {
            for ($i = 0; $i < $total; $i++) {
                $filearray = array();
                $tmpFilePath = $_FILES['upload_img']['tmp_name'][$i];
                /*        $setting = array(
                            'directory' => $directory_name1, // directory file compressed output
                            'file_type' => array( // file format allowed
                                'image/jpeg',
                                'image/png',
                                'image/gif'
                            )
                        );
                        $ImgCompressor = new ImgCompressor($setting);
                        $result = $ImgCompressor->run($tmpFilePath1, 'jpg', 5);
                    }
                }

                $key = json_encode($result);
                $decode = json_decode($key);
                $value = 'status';
                $fileStatus = $decode->$value;
                if ($fileStatus == "success") {
                    $data = "data";
                    $compressed = "compressed";
                    $img_name = "name";
                    $cover_name = $decode->$data->$compressed->$img_name;*/
                $tmpFilePath = $_FILES['upload_img']['tmp_name'][$i];
                $file_original_name = substr($_FILES['upload_img']['name'][$i], 0, strrpos($_FILES['upload_img']['name'][$i], '.'));
                $file_extension = substr($_FILES['upload_img']['name'][$i], (strrpos($_FILES['upload_img']['name'][$i], '.') + 1));
                $newimgname = $file_original_name . "$" . $randomNum1 . '.' . $file_extension;
                $cover_name = str_replace([' ', '_'], '-', $newimgname);
                $newPath = $directory_name . $cover_name;
                // Compress Image
                // $upload = compressImage($tmpFilePath, $newPath, 60);
                $upload = move_uploaded_file($tmpFilePath,$newPath);
                if (!$upload) {
                    $error = true;
                    $errorMessage = "Issue while uploading\nNote: File too large. File must be less than 4 megabytes";
                } else {
                    if (($_FILES['upload_img']['size'][$i] >= 100)) {
                        $manage->imageCompressor($newPath);
                    }
                    $status = $manage->addClient($txt_name_client, $cover_name);
                    if ($status) {
                        $_SESSION['red_dot']['client_name'] = false;
                        if ($countClient == 0) {
                            $_SESSION['total_percent'] = $_SESSION['total_percent'] + 10;
                        }
                        $get_staus = $manage->displayClientDetails();
                        if ($get_staus != null) {
                            $countClient = mysqli_num_rows($get_staus);
                        } else {
                            $countClient = 0;
                        }
                        $txt_name_client = "";
                        $newFile = "";
                        $error = false;
                        $errorMessage = $_SESSION['menu']['s_client_name'] . " added successfully";
                    } else {
                        $error = false;
                        $errorMessage = "Issue while adding details, Please try again.";
                    }
                }
            }
        }
    }
}



if (isset($_GET['id'])) {
    $get_id = $security->decrypt($_GET['id']);
    $form_data = $manage->getClientDetails($get_id);
    $name = $form_data['name'];
    /*  $description = $form_data['description'];*/
    $img_name = $form_data['img_name'];
    $uploadImage = 'uploads/' . $session_email . '/testimonials/clients/' . $form_data['img_name'];
}

$imgUpload = false;
/*This method used for update the Branch data*/
if (isset($_POST['btn_update'])) {
    if (isset($_POST['txt_name_client']) && $_POST['txt_name_client'] != "") {
        $txt_name_client = mysqli_real_escape_string($con, $_POST['txt_name_client']);
    } else {
        $error = true;
        $errorMessage .= "Please enter name.<br>";
    }

    if (isset($_FILES['upload_img']) && $_FILES['upload_img']['error'][0] != 4 /* 4 means there is no file selected*/) {
        $imgUpload = true;
        $directory_name1 = "uploads/" . $session_email . "/testimonials/clients/";
        $extension = array('.jpg', '.JPG', '.jpeg', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG');
        $total = count($_FILES['upload_img']['name']);
        for ($i = 0; $i < $total; $i++) {
            $filename = $_FILES['upload_img']['name'][$i];
            $extensionStatus = $validate->validateFileExtension($filename, $extension);
            if (!$extensionStatus) {
                $error = true;
                $errorMessage = "Please select valid file extension";
            }
            if (($_FILES['upload_img']['size'][$i] >= $maxsize)) {
                $error = true;
                $errorMessage = 'File too large. File must be less than 4 megabytes.';
            }

        }
    }
    /*End of pdf*/
    if (!$error) {
        $digits = 4;
        $randomNum1 = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        $cover_name = "";
        if ($imgUpload) {
            for ($i = 0; $i < $total; $i++) {
                $filearray = array();
                $tmpFilePath = $_FILES['upload_img']['tmp_name'][$i];
             /*   $setting = array(
                    'directory' => $directory_name1, // directory file compressed output
                    'file_type' => array( // file format allowed
                        'image/jpeg',
                        'image/png',
                        'image/gif'
                    )
                );
                $ImgCompressor = new ImgCompressor($setting);
                $result = $ImgCompressor->run($tmpFilePath1, 'jpg', 5);*/
                $file_original_name = substr($_FILES['upload_img']['name'][$i], 0, strrpos($_FILES['upload_img']['name'][$i], '.'));
                $file_extension = substr($_FILES['upload_img']['name'][$i], (strrpos($_FILES['upload_img']['name'][$i], '.') + 1));
                $newimgname = $file_original_name . "$" . $randomNum1 . '.' . $file_extension;
                $cover_name = str_replace([' ', '_'], '-', $newimgname);
                $newPath = $directory_name1 . $cover_name;
                //  $upload = compressImage($tmpFilePath,$newPath,60);
                $upload = move_uploaded_file($tmpFilePath,$newPath);
                if(!$upload){
                    $error = true;
                    $errorMessage = "Issue while uploading\nNote: File too large. File must be less than 4 megabytes";
                }else{
                    // Compress Image
                    if (($_FILES['upload_img']['size'][$i] >= 100)) {
                        $manage->imageCompressor($newPath);
                    }
                    if (file_exists($uploadImage) && $form_data['img_name'] !="") {
                        unlink('uploads/' . $session_email . '/testimonials/clients/' . $form_data['img_name'] . '');
                    }
                }
            }
           /* $key = json_encode($result);
            $decode = json_decode($key);
            $value = 'status';
            $fileStatus = $decode->$value;
            if ($fileStatus == "success") {
                if (file_exists($uploadImage) && $form_data['img_name'] !="") {
                    unlink('uploads/' . $session_email . '/testimonials/clients/' . $form_data['img_name'] . '');
                }
                $data = "data";
                $compressed = "compressed";
                $img_name = "name";
                $cover_name = $decode->$data->$compressed->$img_name;
            } else {
                $error = true;
                $errorMessage = "Issue while uploading\nNote: File too large. File must be less than 4 megabytes";
            }*/

        }

       if(!$error){
           $status = $manage->updateClient($txt_name_client, $cover_name, $security->decrypt($_GET['id']));
           if ($status) {
               $error = false;
               $errorMessage = "details Updated successfully";
               if ($android_url != "") {
                   header('location:testimonial.php?' . $android_url);
               } else {
                   header('location:testimonial.php');
               }
           } else {
               $error = false;
               $errorMessage = "Issue while updating please try again.";
           }
       }
    }

}

/*Edit Clients End*/
if($countClient == 0){
    $_SESSION['red_dot']['client_name'] = true;
}
$imgUploadStatus = false;


/*End Clients Review*/
if (isset($_GET['publishData']) && (isset($_GET['action']))) {
    $action = $_GET['action'];
    $publishData = $security->decrypt($_GET['publishData']);
    if ($action == "unpublish") {
        $result = $manage->publishUnpublish($publishData, 0, $manage->clientTable);
    } else {
        $result = $manage->publishUnpublish($publishData, 1, $manage->clientTable);
    }
    if ($android_url != "") {
        header('location:testimonial.php?' . $android_url);
    } else {
        header('location:testimonial.php');
    }
}


if (isset($_GET['delete_data'])) {
    $delete_data = $security->decrypt($_GET['delete_data']);
    $img_path = $_GET['img_path'];
    $uploadImage = 'uploads/' . $session_email . '/testimonials/clients/' . $img_path;
    if (file_exists($uploadImage)) {
        unlink('uploads/' . $session_email . '/testimonials/clients/' . $_GET['img_path'] . '');
        $status = $manage->deleteClient($delete_data);
    } else {
        $status = $manage->deleteClient($delete_data);
    }
    if ($status) {
        $get_staus = $manage->displayClientDetails();
        if ($get_staus != null) {
            $countClient = mysqli_num_rows($get_staus);
        } else {
            $countClient = 0;
        }
        if($countClient == 0) {
            $_SESSION['total_percent'] = $_SESSION['total_percent'] - 10;
        }
        if ($android_url != "") {
            header('location:testimonial.php?' . $android_url);
        } else {
            header('location:testimonial.php');
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
            header('location:testimonial.php?' . $android_url);
        } else {
            header('location:testimonial.php');
        }
    }
}



?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php include "assets/common-includes/total_count.php" ?>
    <title><?php echo $_SESSION['menu']['s_clients'] ?> - <?php echo $_SESSION['menu']['s_client_name'] ?></title>
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
        .table-bordered tbody tr td, .table-bordered tbody tr th {
            cursor: all-scroll;
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
                           <ul class="nav nav-tabs tab-nav-right <?php if($android_url !='') echo "d-card-none"; ?>" role="tablist">
                               <li role="presentation" class="active"><a class="custom_nav_tab" href="#clients" data-toggle="tab"><?php echo $_SESSION['menu']['s_client_name'] ?><?php if($_SESSION['red_dot']['client_name'] == true) echo '<div class="remaining_sub_form_dot"></div>' ?></a></li>
                               <li role="presentation"><a class="custom_nav_tab"
                                                          href="clients_review.php<?php if ($android_url != "") echo "?" . $android_url; ?>"><?php echo $_SESSION['menu']['s_client_name'] ?><?php if($_SESSION['red_dot']['client_review'] == true) echo '<div class="remaining_sub_form_dot"></div>' ?></a>
                               </li>

                           </ul>
                           <!-- Tab panes -->
                           <div class="tab-content">
                               <div role="tabpanel" class="tab-pane fade in active" id="clients">
                                   <div class="clearfix">
                                       <div
                                           class="col-lg-5 col-md-5 col-sm-12 col-xs-12 padding_zero margin_div padding_zero_both">
                                           <div class="card">
                                               <div class="header">
                                                   <div class="row cust-row">
                                                       <?php if (isset($_GET['id'])) { ?>
                                                           <div class="col-lg-7 m_b_0"><h2>
                                                                   Update <?php echo $_SESSION['menu']['s_client_name'] ?>
                                                               </h2></div>
                                                       <?php } else { ?>
                                                           <div class="col-lg-7 m_b_0"><h2>
                                                                   Add <?php echo $_SESSION['menu']['s_client_name'] ?>
                                                               </h2></div>
                                                       <?php } ?>
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
                                                           <label class="form-label">Upload Image</label>

                                                           <div class="form-group form-float">
                                                               <div class="form-line">
                                                                   <!--<asp:FileUpload ID="fileupload_cat_img" CssClass="form-control" runat="server" />-->
                                                                   <input type="file" name="upload_img[]" id="file-7"
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
                                                                   <?php if (isset($_GET['id'])) echo '<img src="uploads/' . $session_email . '/testimonials/clients/' . $form_data['img_name'] . '" style="width: 20%;"/><br />'; ?>
                                                               </div>
                                                               <?php
                                                               echo FILE_NOTE_4;
                                                               ?>
                                                           </div>

                                                       </div>

                                                       <div>
                                                           <label class="form-label">Name</label> <span
                                                               class="required_field">*</span>

                                                           <div class="form-group form-float">
                                                               <div class="form-line">
                                                                   <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                                                   <input name="txt_name_client" class="form-control"
                                                                          placeholder="Name Of Client"
                                                                          value="<?php if (isset($form_data['name'])) echo htmlspecialchars($form_data['name']); ?>">

                                                               </div>
                                                           </div>
                                                       </div>


                                                       <div>
                                                           <div class="form-group form_inline">
                                                               <?php if (isset($_GET['id'])) { ?>
                                                                   <div>
                                                                       <input value="Update" type="submit"
                                                                              name="btn_update"
                                                                              class="btn btn-primary waves-effect">
                                                                   </div>
                                                               <?php } else { ?>
                                                                   <div>
                                                                       <input value="Add" type="submit" name="btn_save"
                                                                              class="btn btn-primary waves-effect">
                                                                   </div>
                                                               <?php } ?>
                                                               &nbsp;&nbsp;
                                                               <div>
                                                                   <a href="testimonial.php<?php if ($android_url != "") echo "?" . $android_url; ?>"
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
                                           <!--<div class="freelancer_search_box padding_zero padding_zero_both"
                                         style="width: 100%">
                                        <div class="col-md-12">
                                            <form action="" method="post">
                                                <ul class="profile-ul">
                                                    <h4>Hide clients details from digital card</h4>
                                                    <li class="li_event">
                                                        <div class="cust-div">
                                                            <input type="checkbox" name="video_type[]"
                                                                   value="digital_card"  <?php /*if ($display_video_result['digital_card'] == '1') {
                                                                echo 'checked="checked"';
                                                            } */ ?> > Clients
                                                        </div>
                                                    </li>
                                                       <li>
                                                        <div class="cust-div">
                                                            <input type="checkbox" name="video_type[]"
                                                                   value="website" <?php /*if ($display_video_result['website'] == '1') {
                                                                echo 'checked="checked"';
                                                            } */ ?>>Website
                                                        </div>
                                                    </li>


                                                    <li class="li_event">
                                                        <?php /*if (isset($alreadySavedVideo) && $alreadySavedVideo) {
                                                            */ ?>
                                                            <button class="btn btn-primary waves-effect"
                                                                    name="update_video_chk"
                                                                    type="submit">
                                                                Save
                                                            </button>
                                                        <?php
                                           /*                                                        } else {
                                                                                                       */ ?>
                                                            <button class="btn btn-primary waves-effect" name="save_chk"
                                                                    type="submit">
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
                                                       Manage <?php echo $_SESSION['menu']['s_client_name'] ?> <span class="badge"><?php
                                                           if (isset($countClient)) echo $countClient;
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
                                                               <th style="width: 85%">Clients</th>
                                                              <!-- <th class="visible-lg visible-md hidden-sm hidden-xs">NAME
                                                               </th>
                                                               <th class="visible-lg visible-md hidden-sm hidden-xs">
                                                                   STATUS
                                                               </th>-->
                                                               <th class="text-center">ACTION</th>
                                                           </tr>
                                                           </thead>
                                                           <tbody class="row_position">
                                                           <?php
                                                           if ($get_staus != null) {
                                                               while ($result_data = mysqli_fetch_array($get_staus)) {
                                                                   ?>
                                                                   <tr id="<?php echo $security->encryptWebservice($result_data['id']); ?>">
                                                                       <td>
                                                                           <div  style="display: inline-block;vertical-align: top">
                                                                           <img src="assets/images/draggable-icon.png" style="width: 45px;">
                                                                           <div class="user_our_team_image">
                                                                               <?php echo '<img src="uploads/' . $session_email . '/testimonials/clients/' . $result_data['img_name'] . '" /><br />'; ?>
                                                                           </div>
                                                                         </div>
                                                                           <div class="our_team_desc">
                                                                               <span><?php echo wordwrap($result_data['name'], 100, "<br />\n"); ?></span><br>
                                                                               <label class="label <?php if ($result_data['status'] == "0") {
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
                                                                     <!--  <td class="visible-lg visible-md hidden-sm hidden-xs"><?php /*echo wordwrap($result_data['name'], 20, "<br />\n");; */?></td>
                                                                       <td class="visible-lg visible-md hidden-sm hidden-xs">
                                                                           <label
                                                                               class="label <?php /*if ($result_data['status'] == "0") {
                                                                                   echo "label-danger";
                                                                               } else {
                                                                                   echo "label-success";
                                                                               } */?>"><?php /*if ($result_data['status'] == 0) {
                                                                                   echo "Unpublished";
                                                                               } else {
                                                                                   echo "Published";
                                                                               } */?></label></td>-->
                                                                       <td class="text-center">
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
                                                                                           <a href="testimonial.php?id=<?php echo $security->encrypt($result_data['id']);
                                                                                           if ($android_url != "") echo "&" . $android_url; ?>"
                                                                                           <i class="fas fa-edit"></i>&nbsp;&nbsp;Edit</a>
                                                                                       </li>
                                                                                       <li>
                                                                                           <a href="testimonial.php?delete_data=<?php echo $security->encrypt($result_data['id']) ?>&img_path=<?php echo $result_data['img_name'];
                                                                                           if ($android_url != "") echo "&" . $android_url; ?>"
                                                                                              onclick="return confirm('Are You sure you want to delete?');"
                                                                                           <i
                                                                                               class="fas fa-trash-alt"></i>&nbsp;&nbsp;Delete</a>
                                                                                       </li>
                                                                                       <li>
                                                                                           <a onclick="return confirm('Are You sure you want to <?php echo $result_data['status'] == 0 ? 'publish' : 'unpublish'; ?>?');"
                                                                                              href="testimonial.php?publishData=<?php echo $security->encrypt($result_data['id']) ?>&action=<?php echo $result_data['status'] == 0 ? "publish" : "unpublish";
                                                                                              if ($android_url != "") echo "&" . $android_url; ?> "><i
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
    <script src="assets/js/custom-file-input.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript">
        $( ".row_position" ).sortable({
            delay: 150,
            stop: function() {
                var selectedData = new Array();
                $('.row_position>tr').each(function() {
                    selectedData.push($(this).attr("id"));
                });
                updateOrder(selectedData);
            }
        });


        function updateOrder(data) {
            $.ajax({
                url:"changePosition.php",
                type:'post',
                data:{client:data},
                success:function(data){
                    console.log(data);
                }
            })
        }
    </script>
</body>
</html>