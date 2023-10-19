<?php
ob_start();
include "../controller/ManageUser.php";
$manage = new ManageUser();
include "../controller/validator.php";
$validate = new Validator();
$alreadySaved = false;
$alreadySavedEmail = false;
$maxsize = 2097152;


if (!isset($_SESSION['email'])) {
    header('location:../login.php');
}
$error = false;
$errorMessage = "";
$error1 = false;
$errorMessage1 = "";
include("session_includes.php");

$get_data = $manage->countLogoData($id);
if ($get_data) {
    $alreadySaved = true;
    $display_result = $manage->getLogoDetails($id);
    $company_name = $display_result['company_name'];
    $tag_line = $display_result['tag_line'];
    $fileUpload = "uploads/" . $session_email . "/logo/" . $display_result['img_name'];
}

if (isset($_POST['btn_save'])) {
    if (isset($_POST['txt_company_name']) && $_POST['txt_company_name'] != "") {
        $company_name = $_POST['txt_company_name'];
    } else {
        $error = true;
        $errorMessage .= "Please enter company name.<br>";
    }
    if (isset($_POST['txt_tag_line']) && $_POST['txt_tag_line'] != "") {
        $tag_line = $_POST['txt_tag_line'];
    } else {
        $error = true;
        $errorMessage .= "Please enter tag line.<br>";
    }
    /*tart of pdf upload*/
    /*echo $_FILES['upload']['error'][0];
        die();*/
    if (isset($_FILES['upload']) && $_FILES['upload']['error'][0] != 4 /* 4 means there is no file selected*/) {
        $imgUploadStatus = true;
        $directory_name = "uploads/" . $session_email . "/logo/";
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
            for ($i = 0; $i < $total; $i++) {
                $filearray = array();
                $tmpFilePath = $_FILES['upload']['tmp_name'][$i];
                $file_original_name = substr($_FILES['upload']['name'][$i], 0, strrpos($_FILES['upload']['name'][$i], '.'));
                $file_extension = substr($_FILES['upload']['name'][$i], (strrpos($_FILES['upload']['name'][$i], '.') + 1));
                /*echo $file_original_name;
                die();*/
                $newfilename = $file_original_name . "$" . $randomNum . '.' . $file_extension;
                $removeSpace = str_replace(array(' ','_'), array('-','-'), $newfilename);
                $newFile  = strtolower($removeSpace);
                $newPath = $directory_name . $newFile;
                if (!move_uploaded_file($tmpFilePath, $newPath)) {
                    $error = true;
                    $errorMessage = "Failed to upload file";
                }
            }
        }
        $status = $manage->addLogo($company_name, $tag_line, $newfilename);
        if ($status) {
            $error = false;
            $errorMessage = "details Added successfully";
            header('location:website_setting.php');
        } else {
            $error = true;
            $errorMessage = "Issue while adding details, Please try again.";
        }
    }

}
$imgUploadStatus = false;
if (isset($_POST['btn_update'])) {
    if (isset($_POST['txt_company_name']) && $_POST['txt_company_name'] != "") {
        $company_name = $_POST['txt_company_name'];
    } else {
        $error = true;
        $errorMessage .= "Please enter company name.<br>";
    }
    if (isset($_POST['txt_tag_line']) && $_POST['txt_tag_line'] != "") {
        $tag_line = $_POST['txt_tag_line'];
    } else {
        $error = true;
        $errorMessage .= "Please enter tag line.<br>";
    }
    /*Start of pdf upload*/
    /*echo $_FILES['upload']['error'][0];
        die();*/
    if (isset($_FILES['upload']) && $_FILES['upload']['error'][0] != 4 /* 4 means there is no file selected*/) {
        $imgUploadStatus = true;
        $directory_name = "uploads/" . $session_email . "/logo/";
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
            if(file_exists($display_result['img_name'])){
                unlink('uploads/' . $session_email . '/logo/' . $display_result['img_name'] . '');
                for ($i = 0; $i < $total; $i++) {
                    $filearray = array();
                    $tmpFilePath = $_FILES['upload']['tmp_name'][$i];
                    $file_original_name = substr($_FILES['upload']['name'][$i], 0, strrpos($_FILES['upload']['name'][$i], '.'));
                    $file_extension = substr($_FILES['upload']['name'][$i], (strrpos($_FILES['upload']['name'][$i], '.') + 1));
                    /*echo $file_original_name;
                    die();*/
                    $newfilename = $file_original_name . "$" . $randomNum . '.' . $file_extension;
                    $removeSpace = str_replace(array(' ','_'), array('-','-'), $newfilename);
                    $newFile  = strtolower($removeSpace);
                    $newPath = $directory_name . $newFile;
                    if (!move_uploaded_file($tmpFilePath, $newPath)) {
                        $error = true;
                        $errorMessage = "Failed to upload file";
                    }
                }
            }else{
                for ($i = 0; $i < $total; $i++) {
                    $filearray = array();
                    $tmpFilePath = $_FILES['upload']['tmp_name'][$i];
                    $file_original_name = substr($_FILES['upload']['name'][$i], 0, strrpos($_FILES['upload']['name'][$i], '.'));
                    $file_extension = substr($_FILES['upload']['name'][$i], (strrpos($_FILES['upload']['name'][$i], '.') + 1));
                    /*echo $file_original_name;
                    die();*/
                    $newfilename = $file_original_name . "$" . $randomNum . '.' . $file_extension;
                    $removeSpace = str_replace(array(' ','_'), array('-','-'), $newfilename);
                    $newFile  = strtolower($removeSpace);
                    $newPath = $directory_name . $newFile;
                    if (!move_uploaded_file($tmpFilePath, $newPath)) {
                        $error = true;
                        $errorMessage = "Failed to upload file";
                    }
                }
            }

        }
        $status = $manage->updateLogo($company_name, $tag_line, $newfilename, $id);
        if ($status) {
            $error = false;
            $errorMessage = "details Updated successfully";
            header('location:website_setting.php');
        } else {
            $error = true;
            $errorMessage = "Issue while updating details, Please try again.";
        }
    }

}


$get_email_data = $manage->countEmailData($id);
if ($get_email_data) {
    $alreadySavedEmail = true;
    $display_result_email = $manage->getEmailDetails($id);
    $email = $display_result_email['email'];
    $password = $display_result_email['password'];
}

if (isset($_POST['btn_save_email'])) {
    if (isset($_POST['txt_email']) && $_POST['txt_email'] != "") {
        $email = $_POST['txt_email'];
    } else {
        $error1 = true;
        $errorMessage1 .= "Please enter Email.<br>";
    }
    if (isset($_POST['txt_password']) && $_POST['txt_password'] != "") {
        $password = $_POST['txt_password'];
    } else {
        $error1 = true;
        $errorMessage1 .= "Please enter password.<br>";
    }
    /*tart of pdf upload*/
    /*End of pdf*/
    if (!$error1) {
        $status = $manage->addMailSetting($email, $password);
        if ($status) {
            $error1 = false;
            $errorMessage1 = "details Added successfully";
            header('location:website_setting.php');
        } else {
            echo "Could not connect";
        }
    }

}
if (isset($_POST['btn_update_email'])) {
    if (isset($_POST['txt_email']) && $_POST['txt_email'] != "") {
        $email = $_POST['txt_email'];
    } else {
        $error1 = true;
        $errorMessage1 .= "Please enter Email.<br>";
    }
    if (isset($_POST['txt_password']) && $_POST['txt_password'] != "") {
        $password = $_POST['txt_password'];
    } else {
        $error1 = true;
        $errorMessage1 .= "Please enter password.<br>";
    }
    /*End of pdf*/
    if (!$error1) {
        $status = $manage->updateMailSetting($email, $password, $id);
        if ($status) {
            $error1 = false;
            $errorMessage1 = "details Updated successfully";
            header('location:website_setting.php');
        } else {
            echo "Could not connect";
        }
    }
}





?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Image Slider</title>
    <?php include "assets/common-includes/header_includes.php" ?>
</head>
<body>
<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>
<section class="content">
    <?php
    if(isset($_SESSION['create_user_status']) && $_SESSION['create_user_status']==true){
        include "assets/common-includes/session_button_includes.php" ;
    }
    ?>
    <?php include "assets/common-includes/website-preview.php" ?>
    <div class="up-nav visible-lg visible-md visible-sm hidden-xs">
        <main>
            <div class="page-content" id="applyPage">
                <ul class="breadcrumbs">
                    <li class="tab-link breadcrumb-item breadcrumb_width active visited">
                        <a href="theme.php">
                            <span class="number"><i class="far fa-list-alt"></i></span>
                            <span class="label">Theme</span>
                        </a>
                    </li>
                    <li class="tab-link breadcrumb-item breadcrumb_width active visited">
                        <a href="image-slider.php">
                            <span class="number"><i class="fas fa-user"></i></span>
                            <span class="label">Image Slider</span>
                        </a>
                    </li>
                    <li class="tab-link breadcrumb-item breadcrumb_width active visited">
                        <a href="about-us.php">
                            <span class="number"><i class="fas fa-images"></i></span>
                            <span class="label">About Us</span>
                        </a>
                    </li>
                    <li class="tab-link breadcrumb-item breadcrumb_width active visited">
                        <a href="website_setting.php">
                            <span class="number"><i class="fas fa-user"></i></span>
                            <span class="label">Website Setting</span>
                        </a>
                    </li>
                </ul>
            </div>

        </main>
    </div>

    <div class="clearfix">
        <div class="col-lg-6 col-md-5 col-sm-12 col-xs-12 padding_zero padding_zero_both">
            <div class="row margin_div_web">
                <div class="card">
                    <div class="header">
                        <div class="row cust-row">
                            <div class="col-lg-7"><h2>Upload Logo</h2></div>
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
                                <label class="form-label">Upload Image</label>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input class="form-control" type="file" id="upload" name="upload[]"
                                               multiple="multiple" accept=".png, .jpg, .jpeg,.JPG,.PNG,.JPEG"
                                               value="<?php if (isset($filename)) echo $filename; ?>">
                                        <?php if (isset($alreadySaved) && $alreadySaved) {
                                            ?><img src="<?php echo $fileUpload; ?>" style="width: 20%;"/>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="form-label">Company Name </label>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input name="txt_company_name" class="form-control"
                                               placeholder="Company Name"
                                               value="<?php if (isset($company_name)) echo $company_name; ?>">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="form-label">Tag line</label>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input name="txt_tag_line" class="form-control"
                                               placeholder="Tag Line"
                                               value="<?php if (isset($tag_line)) echo $tag_line; ?>">
                                    </div>
                                </div>
                            </div>


                            <div class="form-group form_inline form-float">
                                <?php if (isset($alreadySaved) && $alreadySaved) {
                                    ?>
                                    <button class="btn btn-primary waves-effect" name="btn_update"
                                            type="submit">
                                        Update
                                    </button>
                                <?php
                                } else {
                                    ?>
                                    <button class="btn btn-primary waves-effect" name="btn_save" type="submit">
                                        Add
                                    </button>
                                <?php
                                }
                                ?>
                                &nbsp;&nbsp;
                                <div>
                                    <button class="btn btn-default" type="reset">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-5 col-sm-12 col-xs-12 padding_zero padding_zero_both">
            <div class="row margin_div_web">
                <div class="card">
                    <div class="header">
                        <div class="row cust-row">
                            <div class="col-lg-7"><h2>
                                    Update Email Setting
                                </h2></div>

                        </div>
                    </div>
                    <div class="body">
                        <form id="form_validation" method="POST" action="" enctype="multipart/form-data">
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
                            <div>
                                <label class="form-label">Email</label>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input name="txt_email" class="form-control"
                                               placeholder="Email" value="<?php if (isset($email)) echo $email; ?>">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="form-label">Password</label>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input name="txt_password" class="form-control"
                                               placeholder="Password"
                                               value="<?php if (isset($password)) echo $password; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group form_inline form-float">
                                <?php if (isset($alreadySavedEmail) && $alreadySavedEmail) {
                                    ?>
                                    <button class="btn btn-primary waves-effect" name="btn_update_email"
                                            type="submit">
                                        Update
                                    </button>
                                <?php
                                } else {
                                    ?>
                                    <button class="btn btn-primary waves-effect" name="btn_save_email" type="submit">
                                        Add
                                    </button>
                                <?php
                                }
                                ?>
                                &nbsp;&nbsp;
                                <div>
                                    <button class="btn btn-default" type="reset">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php include "assets/common-includes/footer_includes.php" ?>
</body>
</html>