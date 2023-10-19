<?php
ob_start();
include "../controller/ManageUser.php";
$manage = new ManageUser();
include "../controller/validator.php";
$validate = new Validator();
$maxsize = 2097152;

$alreadySaved = false;


$alreadySavedVideo = false;
$section_video_id = 10;



if (!isset($_SESSION['email'])) {
    header('location:../login.php');
}
$error = false;
$errorMessage = "";
include("session_includes.php");

    $get_data = $manage->countAboutUs($id);
    if ($get_data) {
        $alreadySaved = true;
        $display_result = $manage->getContactUsDetails($id);
        $description = $display_result['description'];
        $fileUpload = "uploads/" . $session_email . "/about-us/" . $display_result['img_name'];
    }

if (isset($_POST['btn_save'])) {
    if (isset($_POST['txt_des']) && $_POST['txt_des'] != "") {
        $description = $_POST['txt_des'];
    } else {
        $error = true;
        $errorMessage .= "Please enter description.<br>";
    }
    if (isset($_FILES['upload']) && $_FILES['upload']['error'][0] != 4 /* 4 means there is no file selected*/) {
        $imgUploadStatus = true;
        $directory_name = "uploads/" . $session_email . "/about-us/";
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
        $status = $manage->addContactUs($description, $newFile);
        if ($status) {
            $error = false;
            $errorMessage = "details Added successfully";
            header('location:about-us.php');
        }  else {
            $error = true;
            $errorMessage = "Issue while adding details, Please try again.";
        }
    }
}

$imgUploadStatus = false;
/*This method used for update the Branch data*/
if (isset($_POST['btn_update'])) {
    if (isset($_POST['txt_des']) && $_POST['txt_des'] != "") {
        $description = $_POST['txt_des'];
    } else {
        $error = true;
        $errorMessage .= "Please enter description.<br>";
    }

    /*Start of pdf upload*/
    /*echo $_FILES['upload']['error'][0];
        die();*/
    if (isset($_FILES['upload']) && $_FILES['upload']['error'][0] != 4 /* 4 means there is no file selected*/) {
        $imgUploadStatus = true;
        $directory_name = "uploads/" . $session_email . "/about-us/";
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
                unlink('uploads/' . $session_email . '/about-us/' . $display_result['img_name'] . '');
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
        $status = $manage->updateContactUs($description, $newFile, $id);
        if ($status) {
            $error = false;
            $errorMessage = "details Updated successfully";
            header('location:about-us.php');
        }  else {
            $error = true;
            $errorMessage = "Issue while updating details, Please try again.";
        }
    }

}
/*This Method used for display the data in Manage table.*/
$get_result = $manage->displayImageSliderDetails();
if ($get_result != null) {
    $count = mysqli_num_rows($get_result);
} else {
    $count = 0;
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
    if($video_result){
        header('location:about-us.php');
       /* $url = "about-us.php";
        echo '<META HTTP-EQUIV="Refresh" Content="0; URL='.$url.'">';*/
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
                    <li class="tab-link breadcrumb-item breadcrumb_width animated infinite pulse">
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
        <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 padding_zero padding_zero_both">
            <div class="row">
                <div class="card">
                    <div class="header">
                        <div class="row cust-row">
                            <div class="col-lg-7"><h2>
                                    Add About us
                                </h2></div>
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
                                            <?php }?>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="form-label">Description</label>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <textarea name="txt_des" rows="4" cols="50" class="form-control"
                                                  placeholder="Please Enter Description"><?php if(isset($description)) echo $description; ?></textarea>
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
                        </form>
                    </div>
                    <div class="freelancer_search_box padding_zero padding_zero_both" style="width: 100%">
                        <div class="col-md-12">
                            <form action="" method="post">
                                <ul class="profile-ul">
                                    <h4>Visibility ON & OFF</h4>
                                    <li>
                                        <div class="cust-div">
                                            <input type="checkbox" name="video_type[]"
                                                   value="website" <?php if ($display_video_result['website'] == '1') {
                                                echo 'checked="checked"';
                                            } ?>>Website
                                        </div>
                                    </li>
                                    <li></li>


                                    <li class="li_event">
                                        <?php if (isset($alreadySavedVideo) && $alreadySavedVideo) {
                                            ?>
                                            <button class="btn btn-primary waves-effect" name="update_video_chk"
                                                    type="submit">
                                                Update
                                            </button>
                                        <?php
                                        } else {
                                            ?>
                                            <button class="btn btn-primary waves-effect" name="save_chk" type="submit">
                                                Add
                                            </button>
                                        <?php
                                        }
                                        ?>
                                    </li>
                                </ul>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
<?php include "assets/common-includes/footer_includes.php" ?>
</body>
</html>