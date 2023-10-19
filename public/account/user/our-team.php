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
$alreadySaved = false;
$section_id = 6;
$error = false;
$errorMessage = "";
$error1 = false;
$errorMessage1 = "";

$maxsize = 4194304;
include_once('lib/ImgCompressor.class.php');
include("android-login.php");
include("session_includes.php");
include "validate-page.php";
$imgUploadStatus = false;

$get_result = $manage->displayTeamDetails();
if ($get_result != null) {
    $count = mysqli_num_rows($get_result);
} else {
    $count = 0;
}
/*This method used for update the Branch data*/
if (isset($_POST['btn_save'])) {
    if (isset($_POST['txt_name']) && $_POST['txt_name'] != "") {
        $name = mysqli_real_escape_string($con, $_POST['txt_name']);
    } else {
        $error = true;
        $errorMessage .= "Please enter name.<br>";
    }
    if (isset($_POST['txt_des']) && $_POST['txt_des'] != "") {
        $designation = mysqli_real_escape_string($con, $_POST['txt_des']);
    } else {
        $error = true;
        $errorMessage .= "Please enter designation.<br>";
    }
    $w_number = $_POST['w_number'];
    $c_number = $_POST['c_number'];
    $dg_link = $_POST['dg_link'];
    if (isset($_FILES['upload']) && $_FILES['upload']['error'][0] != 4 /* 4 means there is no file selected*/) {
        $imgUploadStatus = true;
        $directory_name = "uploads/" . $session_email . "/our-team/";
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
                $errorMessage = 'File too large. File must be less than 4 megabytes.';
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
                /*      $setting = array(
                          'directory' => $directory_name, // directory file compressed output
                          'file_type' => array( // file format allowed
                              'image/jpeg',
                              'image/png',
                              'image/gif'
                          )
                      );
                      $ImgCompressor = new ImgCompressor($setting);
                      $result = $ImgCompressor->run($tmpFilePath, 'jpg', 5);
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
                // $upload = compressImage($tmpFilePath,$newPath,60);
                $upload = move_uploaded_file($tmpFilePath,$newPath);
                if(!$upload){
                    $error = true;
                    $errorMessage = "Issue while uploading\nNote: File too large. File must be less than 4 megabytes";
                }else{
                    if (($_FILES['upload_img']['size'][$i] >= 100)) {
                        $manage->imageCompressor($newPath);
                    }
                }
            }
        }
        if(!$error){
            $status = $manage->addTeam($id, $name, $designation, $cover_name,$dg_link,$c_number,$w_number);
            if ($status) {
                $_SESSION['red_dot']['our_team']= false;
                if($count == 0) {
                    $_SESSION['total_percent'] = $_SESSION['total_percent'] + 10;
                }
                $get_result = $manage->displayTeamDetails();
                if ($get_result != null) {
                    $count = mysqli_num_rows($get_result);
                } else {
                    $count = 0;
                }
                $name = "";
                $cover_name = "";
                $designation = "";
                $error = false;
                $errorMessage = $_SESSION['menu']['s_team'] ." added successfully";
            } else {
                $error = true;
                $errorMessage = "Issue while adding details, Please try again.";
            }
        }



    }

}

/*This Method used for display the data in Manage table.*/

if($count == 0){
    $_SESSION['red_dot']['our_team']= true;
}
/*This is For Edit*/
if (isset($_GET['id'])) {
    $get_id = $security->decrypt($_GET['id']);
    $form_data = $manage->getTeamDetails($get_id);
    $name = $form_data['name'];
    $designation = $form_data['designation'];
    $img_name = $form_data['img_name'];
    $w_number = $form_data['w_number'];
    $c_number = $form_data['c_number'];
    $dg_link = $form_data['dg_link'];
    $uploadImage = 'uploads/' . $session_email . '/our-team/' . $form_data['img_name'];
}

$imgUploadStatus = false;
/*This method used for update the Branch data*/
if (isset($_POST['btn_update'])) {
    if (isset($_POST['txt_name']) && $_POST['txt_name'] != "") {
        $name = mysqli_real_escape_string($con, $_POST['txt_name']);
    } else {
        $error = true;
        $errorMessage .= "Please enter name.<br>";
    }
    if (isset($_POST['txt_des']) && $_POST['txt_des'] != "") {
        $designation = mysqli_real_escape_string($con, $_POST['txt_des']);
    } else {
        $error = true;
        $errorMessage .= "Please enter designation.<br>";
    }
    $w_number = $_POST['w_number'];
    $c_number = $_POST['c_number'];
    $dg_link = $_POST['dg_link'];
    /*Start of pdf upload*/
    /*echo $_FILES['upload']['error'][0];
        die();*/
    if (isset($_FILES['upload']) && $_FILES['upload']['error'][0] != 4 /* 4 means there is no file selected*/) {
        $imgUploadStatus = true;
        $directory_name = "uploads/" . $session_email . "/our-team/";
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
                $errorMessage = 'File too large. File must be less than 4 megabytes.';
            }
        }
    }

    /*End of pdf*/
    if (!$error) {
        $digits = 4;
        $randomNum = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        $newfilename = "";
        if ($imgUploadStatus) {

            for ($i = 0; $i < $total; $i++) {
                $tmpFilePath1 = $_FILES['upload']['tmp_name'][$i];
                /*       $setting = array(
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
                       if (file_exists($uploadImage)) {
                           unlink('uploads/' . $session_email . '/our-team/' . $form_data['img_name'] . '');
                       }
                       $data = "data";
                       $compressed = "compressed";
                       $img_name = "name";
                       $cover_name = $decode->$data->$compressed->$img_name;
                   } else {
                       $error = true;
                       $errorMessage = "Issue while uploading\nNote: File too large. File must be less than 4 megabytes";
                   }*/
                $file_original_name = substr($_FILES['upload']['name'][$i], 0, strrpos($_FILES['upload']['name'][$i], '.'));
                $file_extension = substr($_FILES['upload']['name'][$i], (strrpos($_FILES['upload']['name'][$i], '.') + 1));
                $newimgname = $file_original_name . "$" . $randomNum . '.' . $file_extension;
                $cover_name = str_replace([' ', '_'], '-', $newimgname);
                $newPath = $directory_name . $cover_name;
                // Compress Image
                // $upload = compressImage($tmpFilePath1,$newPath,60);
                $upload = move_uploaded_file($$tmpFilePath1,$newPath);
                if(!$upload){
                    $error = true;
                    $errorMessage = "Issue while uploading\nNote: File too large. File must be less than 4 megabytes";
                }else{
                    if (($_FILES['upload_img']['size'][$i] >= 100)) {
                        $manage->imageCompressor($newPath);
                    }
                    if (file_exists($uploadImage)) {
                        unlink('uploads/' . $session_email . '/our-team/' . $form_data['img_name'] . '');
                    }
                }
            }

        }

        if(!$error){
            $status = $manage->updateTeam($name, $designation, $cover_name,$dg_link,$c_number,$w_number, $security->decrypt($_GET['id']));
            if ($status) {
                $error = false;
                $errorMessage = "details Updated successfully";
                if ($android_url != "") {
                    header('location:our-team.php?' . $android_url);
                } else {
                    header('location:our-team.php');
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
        $result = $manage->publishUnpublish($publishData, 0, $manage->ourTeamTable);
    } else {
        $result = $manage->publishUnpublish($publishData, 1, $manage->ourTeamTable);
    }
    if ($android_url != "") {
        header('location:our-team.php?' . $android_url);
    } else {
        header('location:our-team.php');
    }
}
if (isset($_GET['delete_data'])) {
    $delete_data = $security->decrypt($_GET['delete_data']);
    $img_path = $_GET['img_path'];
    $uploadImage = 'uploads/' . $session_email . '/our-team/' . $img_path;
    if (file_exists($uploadImage)) {
        unlink('uploads/' . $session_email . '/our-team/' . $_GET['img_path'] . '');
        $status = $manage->deleteTeam($delete_data);
    } else {
        $status = $manage->deleteTeam($delete_data);
    }
    if ($status) {
        $get_result = $manage->displayTeamDetails();
        if ($get_result != null) {
            $count = mysqli_num_rows($get_result);
        } else {
            $count = 0;
        }
        if($count == 0) {
            $_SESSION['total_percent'] = $_SESSION['total_percent'] - 10;
        }
        if ($android_url != "") {
            header('location:our-team.php?' . $android_url);
        } else {
            header('location:our-team.php');
        }
    }
}


/*This Method used for display the data in Manage table.*/
$get_result = $manage->displayTeamDetails();
if ($get_result != null) {
    $count = mysqli_num_rows($get_result);
} else {
    $count = 0;
}

/*End of Edit*/


if (isset($_SESSION['id'])) {
    $get_data = $manage->countService($id, $section_id);
    if ($get_data) {
        $alreadySaved = true;
        $display_result = $manage->getServiceStatus($id, $section_id);
        /*$array = explode(",",$statusOnOFF);*/
    }
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
            header('location:our-team.php?' . $android_url);
        } else {
            header('location:our-team.php');
        }
    }
}

if(isset($_POST['btn_update_theme'])){
    if(isset($_POST['rd_theme'])){
        $rd_theme = $_POST['rd_theme'];
    }else{
        $rd_theme = "1";
    }
    $update = $manage->mu_updateUserSectionTheme($rd_theme,$section_id,$id);
    if($update){
        $error1 = false;
        $errorMessage1 = "Theme has been updated successfully.";
    }else{
        $error1 = true;
        $errorMessage1 = "Issue while updating theme please try after some time.";
    }

}

$get_section_theme = $manage->mdm_displaySectionTheme($id,$section_id);
if($get_section_theme !=null){
    $section_theme = $get_section_theme['theme_id'];
}else{
    $section_theme = 1;
}

?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php include "assets/common-includes/total_count.php" ?>
    <title><?php echo $_SESSION['menu']['s_team'] ?></title>
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
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 padding_zero padding_zero_both">
                <div class="row">
                    <div class="card">
                        <div class="header">
                            <div class="row cust-row">
                                <?php if (isset($_GET['id'])) { ?>
                                    <div class="col-lg-7"><h2>
                                            Update <?php echo $_SESSION['menu']['s_team'] ?>
                                        </h2></div>
                                <?php } else { ?>
                                    <div class="col-lg-7"><h2>
                                            Add <?php echo $_SESSION['menu']['s_team'] ?>
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
                                    <label class="form-label">Name</label> <span class="required_field">*</span>

                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                            <input name="txt_name" class="form-control"
                                                   placeholder="Name"
                                                   value="<?php if (isset($name)) echo htmlspecialchars($name); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="form-label">Designation</label> <span class="required_field">*</span>

                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                            <input name="txt_des" class="form-control" placeholder="Desgination"
                                                   value="<?php if (isset($designation)) echo htmlspecialchars($designation); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div>


                                    <div class="form-group form-float">
                                        <div class="row">
                                            <div class="col-md-6 m_b_0">
                                                <label class="form-label">Upload Image</label>
                                                <input type="file" name="upload[]" id="file-7"
                                                       class="inputfile inputfile-6"
                                                       data-multiple-caption="{count} files selected" multiple
                                                       onchange="readURL(this);" accept=".png, .jpg, .jpeg,.JPG,.PNG,.JPEG"/>
                                                <label for="file-7"><span></span> <img
                                                        class="input_choose_file blah"
                                                        src=""
                                                        alt=""/><strong
                                                        class="input_choose_file">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                             height="17" viewBox="0 0 20 17">
                                                            <path
                                                                d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/>
                                                        </svg>
                                                        Choose a file&hellip;</strong></label>
                                            </div>
                                            <?php
                                            if (!isset($_GET['id'])) {
                                                ?>
                                                <div class="col-md-6 m_b_0">
                                                    <label>Default Image</label>
                                                    <br>
                                                    <?php echo '<img src="uploads/user.png" style="width: 30%;"/>'; ?>
                                                </div>
                                            <?php
                                            }
                                            ?>
                                            <div class="col-md-12 m_b_0">
                                                <?php
                                                echo FILE_NOTE_4;
                                                ?>
                                            </div>
                                            <?php if (isset($_GET['id']) && $form_data['img_name'] != "") {
                                                echo '<img src="uploads/' . $session_email . '/our-team/' . $form_data['img_name'] . '" style="width: 20%;"/><br />';
                                            } elseif (isset($_GET['id']) && $form_data['img_name'] == "") {
                                                echo '<img src="uploads/user.png" style="width: 30%;"/>';
                                            } ?>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="form-label">Digital Card Link</label> (Optional)

                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                            <input name="dg_link" class="form-control" placeholder="Enter Digital Card Link"
                                                   value="<?php if (isset($dg_link)) echo htmlspecialchars($dg_link); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="form-label">Contact Number</label> (Optional)

                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                            <input name="c_number" class="form-control" placeholder="Enter Contact Number"
                                                   value="<?php if (isset($c_number)) echo htmlspecialchars($c_number); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="form-label">WhatsApp number</label> (Optional)

                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                            <input name="w_number" class="form-control" placeholder="Enter WhatsApp number"
                                                   value="<?php if (isset($w_number)) echo htmlspecialchars($w_number); ?>">
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
                                                <input value="Add" type="submit" name="btn_save"
                                                       class="btn btn-primary waves-effect">
                                            </div>
                                        <?php } ?>
                                        &nbsp;&nbsp;
                                        <div>
                                            <a href="our-team.php<?php if ($android_url != "") echo "?" . $android_url; ?>" class="btn btn-default">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 padding_zero padding_zero_both">
                <div class="row margin_div_web">
                    <!--<div class="freelancer_search_box padding_zero padding_zero_both" style="width: 100%">
                    <div class="col-md-12">
                        <form action="" method="post">

                            <ul class="profile-ul">
                                <h4>Hide our team details from digital card</h4>
                                <li class="li_event">
                                    <div class="cust-div">
                                        <input type="checkbox" name="type[]"
                                               value="digital_card"  <?php /*if ($display_result['digital_card'] == '1') {
                                            echo 'checked="checked"';
                                        } */ ?> > Our team
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
                    /*                                    } else {
                                                            */ ?>
                                        <button class="btn btn-primary waves-effect" name="save_chk" type="submit">
                                            Add
                                        </button>
                                    <?php
                    /*                                    }
                                                        */ ?>
                                </li>
                            </ul>
                        </form>
                    </div>
                </div>-->
                    <div class="card">
                        <div class="header">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h2>
                                            Manage <?php echo $_SESSION['menu']['s_team'] ?> <span class="badge"><?php
                                                if (isset($count)) echo $count;
                                                ?></span>
                                        </h2>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <button class="btn btn-warning shine " data-toggle="modal" data-target="#myModal"> Team Theme</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="body">
                            <?php if ($error1) {
                                ?>
                                <div class="alert alert-danger">
                                    <?php if (isset($errorMessage1)) echo $errorMessage1; ?>
                                </div>
                                <?php
                            } else if (!$error1 && $errorMessage1 != "") {
                                ?>
                                <div class="alert alert-success">
                                    <?php if (isset($errorMessage1)) echo $errorMessage1; ?>
                                </div>
                                <?php
                            }
                            ?>
                            <div style="overflow-x: auto">
                                <table id="dtHorizontalVerticalExample"
                                       class="table table-striped table-bordered table-sm "
                                       cellspacing="0"
                                       width="100%">
                                    <thead>
                                    <tr class="back-color">
                                        <th style="width: 85%">Team Member</th>
                                    <!--    <th class="visible-lg visible-md hidden-sm hidden-xs">NAME</th>
                                        <th class="visible-lg visible-md hidden-sm hidden-xs">DESIGNATION</th>
                                        <th class="visible-lg visible-md hidden-sm hidden-xs">STATUS</th>-->
                                        <th style="text-align: center">ACTION</th>
                                    </tr>
                                    </thead>
                                    <tbody  class="row_position">
                                    <?php
                                    if ($get_result != null) {
                                        while ($result_data = mysqli_fetch_array($get_result)) {
                                            ?>
                                            <tr id="<?php echo $security->encryptWebservice($result_data['id']); ?>">
                                                <td>
                                                    <div  style="display: inline-block;vertical-align: top">
                                                    <img src="assets/images/draggable-icon.png" style="width: 45px;">
                                                        <div class="user_our_team_image">
                                                            <?php if ($result_data['img_name'] != "") {
                                                                echo '<img src="uploads/' . $session_email . '/our-team/' . $result_data['img_name'] . '"/><br />';
                                                            } else {
                                                                echo '<img src="uploads/user.png"  /><br />';
                                                            } ?>
                                                        </div>
                                                        </div>
                                                    <div class="our_team_desc">
                                                        <span><?php echo wordwrap($result_data['name'], 100, "<br />\n"); ?></span><br>
                                                        <span ><?php echo wordwrap($result_data['designation'], 100, "<br />\n"); ?></span><br>
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
                                               <!-- <td class="visible-lg visible-md hidden-sm hidden-xs"><?php /*echo wordwrap($result_data['name'], 20, "<br />\n"); */?></td>
                                                <td class="visible-lg visible-md hidden-sm hidden-xs"><?php /*echo wordwrap($result_data['designation'], 30, "<br />\n"); */?></td>
                                                <td class="visible-lg visible-md hidden-sm hidden-xs"><label class="label <?php /*if ($result_data['status'] == "0") {
                                                        echo "label-danger";
                                                    } else {
                                                        echo "label-success";
                                                    } */?>"><?php /*if ($result_data['status'] == 0) {
                                                            echo "Unpublished";
                                                        } else {
                                                            echo "Published";
                                                        } */?></label></td>-->
                                                <td style="text-align: center">
                                                    <ul class="header-dropdown">
                                                        <li class="dropdown dropdown-inner-table">
                                                            <a href="javascript:void(0);" class="dropdown-toggle"
                                                               data-toggle="dropdown"
                                                               role="button" aria-haspopup="true" aria-expanded="false">
                                                                <i class="material-icons">more_vert</i>
                                                            </a>
                                                            <ul class="dropdown-menu pull-right">
                                                                <li>
                                                                    <a href="our-team.php?id=<?php echo $security->encrypt($result_data['id']);
                                                                    if ($android_url != "") echo "&" . $android_url; ?>"
                                                                    <i class="fas fa-edit"></i>&nbsp;&nbsp;Edit</a></li>
                                                                <li>
                                                                    <a href="our-team.php?delete_data=<?php echo $security->encrypt($result_data['id']) ?>&img_path=<?php echo $result_data['img_name'];
                                                                    if ($android_url != "") echo "&" . $android_url; ?>"
                                                                       onclick="return confirm('Are You sure you want to delete?');"
                                                                    <i
                                                                        class="fas fa-trash-alt"></i>&nbsp;&nbsp;Delete</a>
                                                                </li>
                                                                <li>
                                                                    <a onclick="return confirm('Are You sure you want to <?php echo $result_data['status'] == 0 ? 'publish' : 'unpublish'; ?>?');"
                                                                       href="our-team.php?publishData=<?php echo $security->encrypt($result_data['id']) ?>&action=<?php echo $result_data['status'] == 0 ? "publish" : "unpublish";
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
    </section>
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-md">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header cust-upi-madal">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Select Theme</h4>
                </div>
                <div class="modal-body">
                        <form id="upi_form_validation" method="POST" action="">
                            <div class="">
                                <ul class="company_ul">
                                    <li class="company_ul_li">
                                        <input type="radio" id="myCheckbox1" name="rd_theme"
                                               value="1" <?php if(isset($section_theme) && $section_theme == 1) echo "checked"; ?>  />
                                        <label for="myCheckbox1"><img src="assets/images/theme/team1.PNG"  style="width: 90%"/></label>
                                    </li>
                                    <li class="company_ul_li">
                                        <input type="radio" id="myCheckbox2" name="rd_theme"
                                               value="2" <?php if(isset($section_theme) && $section_theme == 2) echo "checked"; ?> />
                                        <label for="myCheckbox2"><img src="assets/images/theme/team2.PNG"/></label>
                                    </li>
                                    <li class="company_ul_li" style="width: 100%;margin-top: 15px;">
                                        <button class="btn btn-primary waves-effect form-control"
                                                name="btn_update_theme"
                                                type="submit">
                                            Update Theme
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
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
                data:{team:data},
                success:function(data){
                    console.log(data);
                }
            })
        }
    </script>
</body>
</html>