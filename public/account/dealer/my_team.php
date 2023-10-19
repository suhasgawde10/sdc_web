<?php
ob_start();
include "../controller/ManageDealer.php";
$manage = new ManageDealer();
include "../controller/validator.php";
$validate = new Validator();
include '../sendMail/sendMail.php';
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
if (!isset($_SESSION['dealer_email'])) {
    header('location:../dealer-register.php');
}
require_once("functions.php");
$maxsize = 10485760;
$error = false;
$errorMessage = "";
$errorFile = false;
$errorMessageFile = "";
$emailError = false;
$emailErrorMessage = "";
$contactError = false;
$contactErrorMessage = "";
/*$randomEmail = rand(100, 10000);*/
$randomEmail = 1234;
/*$randomSMS = rand(100, 10000);*/
$randomSMS = 1234;
/*@session_start() ;
session_destroy() ;*/

$id = 0;
$maxsize = 2097152;
include("session_includes.php");


$imgUploadStatus = false;
$fileUploadStatus = false;
$profileUploadStatus = false;
if (isset($_GET['publishData']) && (isset($_GET['action']))) {
    $action = $_GET['action'];
    $id = $security->decryptWebservice($_GET['publishData']);
    if ($action == "Unblock") {
        $result = $manage->publishUnpublish($id, 1, $manage->dealerProfileTable);
    } else {
        $result = $manage->publishUnpublish($id, 0, $manage->dealerProfileTable);
    }
    header('location:my_team.php');
}

if (isset($_GET['edit_id'])) {
    $form_data = $manage->getSpecificDealerProfileByUserId($security->decryptWebservice($_GET['edit_id']));
    if ($form_data != null) {
        $id_proof = $form_data['id_proof'];
        $light_bill = $form_data['light_bill'];
        $img_name = $form_data['img_name'];
        $email = $form_data['email'];
        $name = $form_data['name'];
        $contact_no = $form_data['contact_no'];
        $profilePath = "uploads/" . $email . "/profile/" . $form_data['img_name'];
        if ($form_data['light_bill'] != "") {
            $lightPath = "uploads/" . $email . "/light-bill/" . $form_data['light_bill'];
        } else {
            $lightPath = $form_data['light_bill'];
        }
        if ($form_data['id_proof'] != "") {
            $id_proofPath = 'uploads/' . $email . '/id-proof/' . $form_data['id_proof'];
        } else {
            $id_proofPath = $form_data['id_proof'];
        }
    }
}

/*This method used for update the Branch data*/

if (isset($_POST['btn_save'])) {
    if (isset($_POST['txt_name']) && $_POST['txt_name'] != "") {
        $txt_name = $_POST['txt_name'];
    } else {
        $error = true;
        $errorMessage .= "Please enter name.<br>";
    }
    if (isset($_POST['txt_contact']) && $_POST['txt_contact'] != "") {
        $txt_contact = $_POST['txt_contact'];
        $result = $manage->validateContact($txt_contact);
        if ($result) {
            $error = true;
            $errorMessage .= "Contact number already exist.<br>";
        }
    } else {
        $error = true;
        $errorMessage .= "Please enter contact.<br>";
    }
    if (!$error) {
        if (isset($_POST['txt_email']) && $_POST['txt_email'] != "") {
            if (!filter_var($_POST['txt_email'], FILTER_VALIDATE_EMAIL)) {
                $error = true;
                $errorMessage .= "Invalid email format.<br>";
            }
            $result = $manage->validateRegisterEmail($_POST['txt_email']);
            if ($result) {
                $error = true;
                $errorMessage .= "Email ID Already Exists!!";
            }
            $txt_email = $_POST['txt_email'];
        } else {
            $error = true;
            $errorMessage .= "Please enter email.<br>";
        }
        if (!$error) {
            mkdir("uploads/" . $txt_email . "/profile/", 0777, true);
            mkdir("uploads/" . $txt_email . "/id-proof/", 0777, true);
            mkdir("uploads/" . $txt_email . "/light-bill/", 0777, true);
            if (isset($_FILES['upload']) /* 4 means there is no file selected*/) {
                if ($_FILES['upload']['error'][0] != 4) {
                    $imgUploadStatus = true;
                    $directory_name = "uploads/" . $txt_email . "/light-bill/";
                    $extension = array('.jpg', '.JPG', '.jpeg', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG', '.pdf', '.doc', '.docx', '.PDF', '.DOC', '.DOCX');
                    $maxsize = 2097152;
                    $total = count($_FILES['upload']['name']);
                    for ($i = 0; $i < $total; $i++) {
                        $filename = $_FILES['upload']['name'][$i];
                        $extensionStatus = $validate->validateFileExtension($filename, $extension);
                        if (!$extensionStatus) {
                            $error = true;
                            $errorMessage .= "Please select valid file extension";
                        }
                    }
                }/*else{
            if ($form_data['id_proof']=="") {
                $error = true;
                $errorMessage = 'Please upload file';
            }
        }*/

            }
            if (isset($_FILES['upload-file']) /* 4 means there is no file selected*/) {
                if ($_FILES['upload-file']['error'][0] != 4) {

                    $fileUploadStatus = true;
                    $directory_file_name = "uploads/" . $txt_email . "/id-proof/";
                    $extension = array('.jpg', '.JPG', '.jpeg', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG', '.pdf', '.doc', '.docx', '.PDF', '.DOC', '.DOCX');

                    $total = count($_FILES['upload-file']['name']);
                    for ($i = 0; $i < $total; $i++) {
                        $filename = $_FILES['upload-file']['name'][$i];
                        $extensionStatus = $validate->validateFileExtension($filename, $extension);
                        if (!$extensionStatus) {
                            $error = true;
                            $errorMessage .= "Please select valid file extension";
                        }
                    }
                }/*else{
            if ($form_data['id_proof']=="") {
                $error = true;
                $errorMessage = 'Please upload file';
            }
        }*/
            }
            if (!$error) {
                $digits = 4;
                $randomNum = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
                $removeImgSpace = "";
                $removeFileSpace = "";
                if ($imgUploadStatus) {
                    for ($i = 0; $i < $total; $i++) {
                        $filearray = array();
                        $tmpFilePath = $_FILES['upload']['tmp_name'][$i];
                        $file_original_name = substr($_FILES['upload']['name'][$i], 0, strrpos($_FILES['upload']['name'][$i], '.'));
                        $file_extension = substr($_FILES['upload']['name'][$i], (strrpos($_FILES['upload']['name'][$i], '.') + 1));
                        $newimgname = $file_original_name . "$" . $randomNum . '.' . $file_extension;
                        $removeImgSpace = str_replace([' ', '_'], '-', $newimgname);
                        $newPath = $directory_name . $removeImgSpace;
                        if (($_FILES['upload']['size'][$i] >= $maxsize)) {
                            $success = compress_image($tmpFilePath, $tmpFilePath, null, null, 90);
                            if ($success) {
                                if (!move_uploaded_file($tmpFilePath, $newPath)) {
                                    $error = true;
                                    $errorMessage .= "Failed to upload file";
                                }
                            }
                        } else {
                            if (!move_uploaded_file($tmpFilePath, $newPath)) {
                                $error = true;
                                $errorMessage .= "Failed to upload file";
                            }
                        }
                    }
                }
                if ($fileUploadStatus) {
                    for ($i = 0; $i < $total; $i++) {
                        $filearray = array();
                        $tmpFilePath1 = $_FILES['upload-file']['tmp_name'][$i];
                        $file_original_name1 = substr($_FILES['upload-file']['name'][$i], 0, strrpos($_FILES['upload-file']['name'][$i], '.'));
                        $file_extension1 = substr($_FILES['upload-file']['name'][$i], (strrpos($_FILES['upload-file']['name'][$i], '.') + 1));
                        $newfilename = $file_original_name1 . "$" . $randomNum . '.' . $file_extension1;
                        $removeFileSpace = str_replace([' ', '_'], '-', $newfilename);
                        $newPath1 = $directory_file_name . $removeFileSpace;
                        if (($_FILES['upload-file']['size'][$i] >= $maxsize)) {
                            $success = compress_image($tmpFilePath1, $tmpFilePath1, null, null, 90);
                            if ($success) {
                                if (!move_uploaded_file($tmpFilePath1, $newPath1)) {
                                    $error = true;
                                    $errorMessage .= "Failed to upload file";
                                }
                            }
                        } else {
                            if (!move_uploaded_file($tmpFilePath1, $newPath1)) {
                                $error = true;
                                $errorMessage .= "Failed to upload file";
                            }
                        }
                    }
                }
                $status = $manage->insertTeamProfile($txt_name, $removeFileSpace, $removeImgSpace, "Approved");
                if ($status) {
                    $password = rand(1000, 100000);
                    $type = "editor";
                    $insert_login = $manage->addTeamLogin($status, $type, $txt_email, $txt_contact, $password);
                    if ($insert_login) {
                        $sms_message1 = "Dear " . ucwords($txt_name) . ", \nYou have been registered as a team member of " . $_SESSION['dealer_name'] . "\nPlease login to your panel.\n\nUsername=" . $txt_contact . "\nPassword=" . $password;
                        $subject = "Registered as a team member of " . $_SESSION['dealer_name'];
                        $sendMail = $manage->sendMail(MAIL_FROM_NAME, $txt_email, $subject, $sms_message1);
                        $send_sms = $manage->sendSMS($txt_contact, $sms_message1);
                        $txt_email = $txt_name = $txt_contact = "";
                        $error = false;
                        $errorMessage .= "Successful! Login details has been sent to registered mobile number and email id.";
                    } else {
                        $error = true;
                        $errorMessage .= "Issue while updating details please try again.";
                    }
                } else {
                    $error = true;
                    $errorMessage .= "Issue while updating details please try again.";
                }
            }
        }
    }

}
if (isset($_GET['edit_id'])) {
    $edit_id = $security->decryptWebservice($_GET['edit_id']);
    if (isset($_POST['btn_update'])) {
        if (isset($_POST['txt_name']) && $_POST['txt_name'] != "") {
            $txt_name = $_POST['txt_name'];
        } else {
            $error = true;
            $errorMessage .= "Please enter name.<br>";
        }
        if (isset($_POST['txt_contact']) && $_POST['txt_contact'] != "") {
            $txt_contact = $_POST['txt_contact'];
            $result = $manage->validateContactByID($txt_contact, $edit_id);
            if ($result) {
                $error = true;
                $errorMessage .= "Contact number already exist.<br>";
            }
        } else {
            $error = true;
            $errorMessage .= "Please enter contact.<br>";
        }
        if (!$error) {
            if (isset($_POST['txt_email']) && $_POST['txt_email'] != "") {
                if (!filter_var($_POST['txt_email'], FILTER_VALIDATE_EMAIL)) {
                    $error = true;
                    $errorMessage .= "Invalid email format.<br>";
                }
                $result = $manage->validateRegisterEmailByID($_POST['txt_email'], $edit_id);
                if ($result) {
                    $error = true;
                    $errorMessage .= "Email ID Already Exists!!";
                }
                $txt_email = $_POST['txt_email'];
            } else {
                $error = true;
                $errorMessage .= "Please enter email.<br>";
            }
            if (!$error) {
                if ($email != $txt_email) {
                    $oldname = 'uploads/' . $email . '';
                    $newname = 'uploads/' . $txt_email;
                    rename($oldname, $newname);
                    $email == $txt_email;
                }

                if (isset($_FILES['upload']) /* 4 means there is no file selected*/) {
                    if ($_FILES['upload']['error'][0] != 4) {
                        $imgUploadStatus = true;
                        $directory_name = "uploads/" . $email . "/light-bill/";
                        $extension = array('.jpg', '.JPG', '.jpeg', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG', '.pdf', '.doc', '.docx', '.PDF', '.DOC', '.DOCX');
                        $maxsize = 2097152;
                        $total = count($_FILES['upload']['name']);
                        for ($i = 0; $i < $total; $i++) {
                            $filename = $_FILES['upload']['name'][$i];
                            $extensionStatus = $validate->validateFileExtension($filename, $extension);
                            if (!$extensionStatus) {
                                $error = true;
                                $errorMessage .= "Please select valid file extension";
                            }
                        }
                    }/*else{
            if ($form_data['id_proof']=="") {
                $error = true;
                $errorMessage = 'Please upload file';
            }
        }*/

                }
                if (isset($_FILES['upload-file']) /* 4 means there is no file selected*/) {
                    if ($_FILES['upload-file']['error'][0] != 4) {

                        $fileUploadStatus = true;
                        $directory_file_name = "uploads/" . $email . "/id-proof/";
                        $extension = array('.jpg', '.JPG', '.jpeg', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG', '.pdf', '.doc', '.docx', '.PDF', '.DOC', '.DOCX');

                        $total = count($_FILES['upload-file']['name']);
                        for ($i = 0; $i < $total; $i++) {
                            $filename = $_FILES['upload-file']['name'][$i];
                            $extensionStatus = $validate->validateFileExtension($filename, $extension);
                            if (!$extensionStatus) {
                                $error = true;
                                $errorMessage .= "Please select valid file extension";
                            }
                        }
                    }/*else{
            if ($form_data['id_proof']=="") {
                $error = true;
                $errorMessage = 'Please upload file';
            }
        }*/
                }
                if (!$error) {
                    $digits = 4;
                    $randomNum = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
                    $removeImgSpace = "";
                    $removeFileSpace = "";
                    if ($imgUploadStatus) {
                        /*    $filename = '/path/to/foo.txt';
                            if (file_exists($filename)) {
                                echo "The file $filename exists";
                            } else {
                                echo "The file $filename does not exist";
                            }*/
                        if (file_exists($lightPath) && $form_data['light_bill'] != "") {
                            unlink('uploads/' . $email . '/light-bill/' . $form_data['light_bill'] . '');
                        }
                        for ($i = 0; $i < $total; $i++) {
                            $filearray = array();
                            $tmpFilePath = $_FILES['upload']['tmp_name'][$i];
                            $file_original_name = substr($_FILES['upload']['name'][$i], 0, strrpos($_FILES['upload']['name'][$i], '.'));
                            $file_extension = substr($_FILES['upload']['name'][$i], (strrpos($_FILES['upload']['name'][$i], '.') + 1));
                            $newimgname = $file_original_name . "$" . $randomNum . '.' . $file_extension;
                            $removeImgSpace = str_replace([' ', '_'], '-', $newimgname);
                            $newPath = $directory_name . $removeImgSpace;
                            if (($_FILES['upload']['size'][$i] >= $maxsize)) {
                                $success = compress_image($tmpFilePath, $tmpFilePath, null, null, 90);
                                if ($success) {
                                    if (!move_uploaded_file($tmpFilePath, $newPath)) {
                                        $error = true;
                                        $errorMessage .= "Failed to upload file";
                                    }
                                }
                            } else {
                                if (!move_uploaded_file($tmpFilePath, $newPath)) {
                                    $error = true;
                                    $errorMessage .= "Failed to upload file";
                                }
                            }

                        }
                    }
                    if ($fileUploadStatus) {
                        if (file_exists($id_proofPath) && $form_data['id_proof'] != "") {
                            unlink('uploads/' . $email . '/id-proof/' . $form_data['id_proof'] . '');
                        }
                        for ($i = 0; $i < $total; $i++) {
                            $filearray = array();
                            $tmpFilePath1 = $_FILES['upload-file']['tmp_name'][$i];
                            $file_original_name1 = substr($_FILES['upload-file']['name'][$i], 0, strrpos($_FILES['upload-file']['name'][$i], '.'));
                            $file_extension1 = substr($_FILES['upload-file']['name'][$i], (strrpos($_FILES['upload-file']['name'][$i], '.') + 1));
                            $newfilename = $file_original_name1 . "$" . $randomNum . '.' . $file_extension1;
                            $removeFileSpace = str_replace([' ', '_'], '-', $newfilename);
                            $newPath1 = $directory_file_name . $removeFileSpace;
                            $success = compressImage($tmpFilePath1, $newPath1, 90);
                            if (($_FILES['upload']['size'][$i] >= $maxsize)) {
                                $success = compress_image($tmpFilePath, $tmpFilePath, null, null, 90);
                                if ($success) {
                                    if (!move_uploaded_file($tmpFilePath, $newPath)) {
                                        $error = true;
                                        $errorMessage .= "Failed to upload file";
                                    }
                                }
                            } else {
                                if (!move_uploaded_file($tmpFilePath, $newPath)) {
                                    $error = true;
                                    $errorMessage .= "Failed to upload file";
                                }
                            }
                        }
                    }

                    $status = $manage->updateTeamProfile($_POST['txt_name'], $removeFileSpace, $removeImgSpace, $edit_id);
                    if ($status) {
                        $insert_login = $manage->updateTeamLogin($txt_email, $txt_contact, $edit_id);
                        if ($insert_login) {
                            $error = false;
                            $errorMessage .= "Team member updated successfully";
                        } else {
                            $error = true;
                            $errorMessage .= "Issue while updating details please try again.";
                        }
                    } else {
                        $error = true;
                        $errorMessage .= "Issue while updating details please try again.";
                    }
                }
            }
        }
    }
}


$get_result = $manage->getTeamProfile();
if ($get_result != null) {
    $countTeam = mysqli_num_rows($get_result);
} else {
    $countTeam = 0;
}

function resize_image($file, $w, $h, $crop = false)
{
    list($width, $height) = getimagesize($file);
    $r = $width / $height;
    if ($crop) {
        if ($width > $height) {
            $width = ceil($width - ($width * abs($r - $w / $h)));
        } else {
            $height = ceil($height - ($height * abs($r - $w / $h)));
        }
        $newwidth = $w;
        $newheight = $h;
    } else {
        if ($w / $h > $r) {
            $newwidth = $h * $r;
            $newheight = $h;
        } else {
            $newheight = $w / $r;
            $newwidth = $w;
        }
    }

    //Get file extension
    $exploding = explode(".", $file);
    $ext = end($exploding);

    switch ($ext) {
        case "png":
            $src = imagecreatefrompng($file);
            break;
        case "jpg":
            $src = imagecreatefromjpeg($file);
            break;
        case "gif":
            $src = imagecreatefromgif($file);
            break;
        default:
            $src = imagecreatefromjpeg($file);
            break;
    }

    $dst = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

    return $dst;
}

$display_message = $manage->displayDealerProfile();
if ($display_message != null) {
    $message_status = $display_message['message_status'];
    $dealer_status = $display_message['status'];
    $pay_status = $display_message['pay_status'];
    $pay_status = $display_message['pay_status'];
    $deal_code = $display_message['dealer_code'];
}


?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>My Team</title>
    <?php include "assets/common-includes/header_includes.php" ?>

    <style>
        .profile-ul li {
            width: 100%;
        }

        .profile-ul li {
            padding: 12px 10px;
        }

        .form-group {
            margin: 0;
        }

        .dataTables_scrollBody {
            padding-bottom: 100px;
        }
    </style>
</head>
<body>
<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>
<section class="content">
    <div class="clearfix">
        <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 padding_both">
            <div class="row margin_div_web">
                <div class="card">
                    <div class="body">
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

                        <form method="POST" action="" enctype="multipart/form-data">
                            <fieldset>
                                <legend class="legend_font_size" align="left">Add Team</legend>
                                <ul class="profile-ul">
                                    <li>
                                        <div class="width-prf">
                                            <label class="form-label">Name</label> <span class="required_field">*</span>

                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input name="txt_name" class="form-control" placeholder="Enter Name"
                                                           value="<?php if (isset($_POST['txt_name'])) {
                                                               echo $_POST['txt_name'];
                                                           } elseif (isset($name)) echo $name; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="width-prf">
                                            <label class="form-label">Contact Number</label> <span
                                                    class="required_field">*</span>

                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                                    <input type="text" name="txt_contact" class="form-control"
                                                           onkeypress="return isNumberKey(event)"
                                                           oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                           maxlength="10"
                                                           placeholder="Contact Number"
                                                           value="<?php if (isset($_POST['txt_contact'])) {
                                                               echo $_POST['txt_contact'];
                                                           } elseif (isset($contact_no)) echo $contact_no; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="width-prf">
                                            <label class="form-label">Email</label> <span
                                                    class="required_field">*</span>

                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                                    <input type="email" name="txt_email" class="form-control"
                                                           placeholder="Enter Email"
                                                           value="<?php if (isset($_POST['txt_email'])) {
                                                               echo $_POST['txt_email'];
                                                           } elseif (isset($email)) echo $email; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="width-prf">
                                            <label>Aadhar card or Pan card</label>
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="file" id="upload-file" name="upload-file[]"
                                                           accept=".png, .jpg, .jpeg,.JPG,.PNG,.JPEG,.pdf,.doc,.docx">
                                                    <?php
                                                    if (isset($_GET['edit_id'])) {
                                                        $aadharFilePath = "uploads/$email/id-proof/" . $form_data['id_proof'];
                                                        if (file_exists($aadharFilePath) && $form_data['id_proof'] != "") echo '<a href="' . $aadharFilePath . '" target="_blank">Preview</a>';
                                                    }

                                                    ?>

                                                </div>
                                                Note : We accept image and pdf file only.& file size must be max 2mb.
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="width-prf">
                                            <label class="form-label">Last Month Light bill</label>
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="file" id="upload" name="upload[]"
                                                           accept=".png, .jpg, .jpeg,.JPG,.PNG,.JPEG,.pdf,.doc,.docx">
                                                    <?php
                                                    if (isset($_GET['edit_id'])) {
                                                        $lightFilePath = "uploads/$email/light-bill/" . $form_data['light_bill'];
                                                        if (file_exists($lightFilePath) && $form_data['light_bill'] != "") echo '<a href="' . $lightFilePath . '" target="_blank">Preview</a>';
                                                    }
                                                    ?>
                                                </div>
                                                Note : We accept image and pdf file only.& file size must be max 2mb
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </fieldset>

                            <div class="btn-up-div">
                                <div class="form-group form_inline">
                                    <div class="example">

                                        <?php
                                        if (isset($_GET['edit_id'])) {
                                            ?>
                                            <button name="btn_update" type="submit"
                                                    class="btn btn-primary waves-effect">Update
                                            </button>
                                            <?php
                                        } else {
                                            ?>
                                            <button name="btn_save" type="submit"
                                                    class="btn btn-primary waves-effect">Save
                                            </button>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    &nbsp;&nbsp;
                                    <div>
                                        <a class="btn btn-default" href="my_team.php">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">

            <div class="row">

                <div class="card">
                    <div class="header">
                        <h2>
                            Manage Team <span class="badge"><?php
                                if (isset($countTeam)) echo $countTeam;
                                ?></span>
                        </h2>
                    </div>
                    <div class="body" style="padding-top: 0">
                        <table id="dtHorizontalVerticalExample" class="table table-striped table-bordered table-sm "
                               cellspacing="0"
                               width="100%">
                            <thead>
                            <tr class="back-color">
                                <th>File</th>
                                <th>User</th>
                                <th>Login</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if ($get_result != null) {
                                while ($result_data = mysqli_fetch_assoc($get_result)) {
                                    ?>
                                    <tr>
                                        <td>
                                            <?php
                                            $aadharFilePath = 'uploads/' . $result_data['email'] . '/id-proof/' . $result_data['id_proof'];
                                            echo "Id Proof : ";
                                            if (file_exists($aadharFilePath) && $result_data['id_proof'] != "") {
                                                echo '<a href="' . $aadharFilePath . '" target="_blank">Preview</a>';
                                            } else {
                                                echo "-";
                                            }
                                            echo "<br>";
                                            echo "Light bill : ";
                                            $lightFilePath = 'uploads/' . $result_data['email'] . '/light-bill/' . $result_data['light_bill'];
                                            if (file_exists($lightFilePath) && $result_data['light_bill'] != "") {
                                                echo '<a href="' . $lightFilePath . '" target="_blank">Preview</a>';
                                            } else {
                                                echo "-";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            echo $result_data['name'] . "<br>" . $result_data['email'];
                                            ?>
                                        </td>
                                        <td><?php echo "username: " . $result_data['email'] . " Or " . $result_data['contact_no'] . "<br>"; ?><?php echo "password: " . $result_data['password'];
                                            ?></td>
                                        <td>
                                            <label
                                                    class="label <?php if ($result_data['block_status'] == "0") {
                                                        echo "label-danger";
                                                    } else {
                                                        echo "label-success";
                                                    } ?>"><?php if ($result_data['block_status'] == 0) {
                                                    echo "Block";
                                                } else {
                                                    echo "Unblock";
                                                } ?></label>
                                        </td>
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
                                                            <a href="my_team.php?edit_id=<?php echo $security->encryptWebservice($result_data['user_id']) ?>">
                                                                <i class="fas fa-edit"></i>&nbsp;&nbsp;Edit</a>
                                                        </li>
                                                        <li>
                                                            <a onclick="return confirm('Are You sure you want to <?php echo $result_data['block_status'] == 1 ? 'Block' : 'Unblock'; ?>?');"
                                                               href="my_team.php?publishData=<?php echo $security->encryptWebservice($result_data['user_id']) ?>&action=<?php echo $result_data['block_status'] == 1 ? "Block" : "Unblock"; ?>"
                                                               class="<?php echo $result_data['block_status'] == 0 ? "fa fa-unlock" : "fa fa-ban"; ?>">
                                                                &nbsp;&nbsp;<?php echo $result_data['block_status'] == 1 ? "Block" : "Unblock"; ?></a>
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

<?php include "assets/common-includes/footer_includes.php" ?>

<script>
    $('#OpenImgUpload').click(function () {
        $('#upload_image').trigger('click');
    });
</script>

<!--<script type="text/javascript">
    document.getElementById("b3").onclick = function () {
        swal("Good job!", "You clicked the button!", "success");
    };
</script>-->


</body>
</html>