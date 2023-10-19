<?php
ob_start();
include "../controller/ManageUser.php";
$manage = new ManageUser();
include "../controller/validator.php";
$validate = new Validator();
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();


$maxsize = 2097152;
$alreadySaved = false;
$section_id = 9;

if (!isset($_SESSION['email'])) {
    header('location:../login.php');
}
$error = false;
$errorMessage = "";
include("session_includes.php");

$imgUploadStatus = false;
/*This method used for update the Branch data*/
if (isset($_POST['btn_save'])) {
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
        $directory_name = "uploads/" . $session_email . "/image-slider/";
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
        $status = $manage->addImageSlider($description,$newFile);
        if ($status) {
            $error = false;
            $errorMessage = "details Added successfully";
        } else {
            $error = true;
            $errorMessage = "Issue while adding details, Please try again.";
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


if (isset($_GET['id'])) {
    $get_id = $security->decrypt($_GET['id']);
    $form_data = $manage->getImageSliderDetails($get_id);
    $description = $form_data['description'];
    $img_name = $form_data['img_name'];
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
        $directory_name = "uploads/" . $session_email . "/image-slider/";
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
            if (file_exists($form_data['img_name'])) {
                unlink('uploads/' . $session_email . '/service/' . $form_data['img_name'] . '');
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
        $status = $manage->updateImageSlider($description,$newFile,$security->decrypt($_GET['id']));
        if ($status) {
            $error = false;
            $errorMessage = "details Updated successfully";
            header('location:image-slider.php');
        } else {
            $error = true;
            $errorMessage = "Issue while updating details, Please try again.";
        }
    }

}

if (isset($_GET['publishData']) && (isset($_GET['action']))) {
    $action = $_GET['action'];
    $publishData = $security->decrypt($_GET['publishData']);
    if ($action == "unpublish") {
        $result = $manage->publishUnpublish($publishData,0,$manage->sliderTable);
    } else {
        $result = $manage->publishUnpublish($publishData,1,$manage->sliderTable);
    }
    header('location:image-slider.php');
}

if(isset($_GET['delete_data'])){
    $delete=$security->decrypt($_GET['delete_data']);
    $img_path = $_GET['img_path'];
    if(file_exists($img_path)){
        unlink('uploads/' . $session_email . '/image-slider/' . $_GET['img_path'] . '');
        $status = $manage->deleteImageSlider($delete);
    }else{
        $status = $manage->deleteImageSlider($delete);
    }

    if($status){
        header('location:image-slider.php');
    }
}


    $get_data = $manage->countService($id, $section_id);
    if ($get_data) {
        $alreadySaved = true;
        $display_result = $manage->getServiceStatus($id, $section_id);
        /*$array = explode(",",$statusOnOFF);*/
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
    if($result){
        header('location:image-slider.php');
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
                    <li class="tab-link breadcrumb-item breadcrumb_width animated infinite pulse">
                        <a href="about-us.php">
                            <span class="number"><i class="fas fa-images"></i></span>
                            <span class="label">About Us</span>
                        </a>
                    </li>
                    <li class="tab-link breadcrumb-item breadcrumb_width">
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
            <div class="row margin_div_web">
                <div class="card">
                    <div class="header">
                        <div class="row cust-row">
                            <?php if (isset($_GET['id'])) { ?>
                                <div class="col-lg-7"><h2>
                                        Update Slider Image
                                    </h2></div>
                            <?php } else { ?>
                                <div class="col-lg-7"><h2>
                                        Add Slider Image
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
                                <label class="form-label">Upload Image</label>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input class="form-control" type="file" id="upload" name="upload[]"
                                               multiple="multiple" accept=".png, .jpg, .jpeg,.JPG,.PNG,.JPEG"
                                               value="<?php if (isset($filename)) echo $filename; ?>">
                                        <?php if(isset($_GET['id'])) echo '<img src="uploads/' . $session_email . '/image-slider/' . $form_data['img_name'] . '" style="width: 20%;"/><br />'; ?>
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

                            <div>
                                <div class="form-group form_inline">
                                    <?php if(isset($_GET['id'])){ ?>
                                    <div>
                                        <input value="Update" type="submit" name="btn_update"
                                               class="btn btn-primary waves-effect">
                                    </div>
                                    <?php }else{ ?>
                                    <div>
                                        <input value="Add" type="submit" name="btn_save"
                                               class="btn btn-primary waves-effect">
                                    </div>
                                    <?php } ?>
                                    &nbsp;&nbsp;
                                    <div>
                                        <a class="btn btn-default" href="image-slider.php">cancel</a>
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
              <div class="freelancer_search_box padding_zero padding_zero_both" style="width: 100%">
                  <div class="col-md-12">
                      <form action="" method="post">
                          <ul class="profile-ul">
                              <h4>Visibility ON & OFF</h4>
                              <!--<li class="li_event">
                                        <div class="cust-div">
                                            <input type="checkbox" name="type[]"
                                                   value="digital_card"  <?php /*if ($display_result['digital_card'] == '1') {
                                                echo 'checked="checked"';
                                            } */?> >Digital Card
                                        </div>
                                    </li>-->

                              <li>
                                  <div class="cust-div">
                                      <input type="checkbox" name="type[]"
                                             value="website" <?php if ($display_result['website'] == '1') {
                                          echo 'checked="checked"';
                                      } ?>>Website
                                  </div>
                              </li>
                              <li></li>


                              <li class="li_event">
                                  <?php if (isset($alreadySaved) && $alreadySaved) {
                                      ?>
                                      <button class="btn btn-primary waves-effect" name="update_chk"
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
              <div class="card">
                  <div class="header">
                      <h2>
                          Manage Images <span class="badge"><?php
                              if (isset($count)) echo $count;
                              ?></span>
                      </h2>
                  </div>
                  <div class="body">
                      <table id="dtHorizontalVerticalExample" class="table table-striped table-bordered table-sm " cellspacing="0"
                             width="100%">
                          <thead>
                          <tr class="back-color">
                              <th style="width: 30%">IMAGE</th>
                              <th>NAME</th>
                              <!--   <th>DESCRIPTION</th>-->
                              <th>STATUS</th>
                              <th>ACTION</th>
                          </tr>
                          </thead>
                          <tbody>
                          <?php
                          if ($get_result != null) {
                              while ($result_data = mysqli_fetch_array($get_result)) {
                                  ?>
                                  <tr>
                                      <td><?php echo '<img src="uploads/' . $session_email . '/image-slider/' . $result_data['img_name'] . '" style="width: 100%;"/><br />'; ?></td>
                                      <td><?php echo $result_data['description']; ?></td>
                                      <!-- <td><?php /*echo $result_data['description']; */?></td>-->
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
                                                  <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"
                                                     role="button" aria-haspopup="true" aria-expanded="false">
                                                      <i class="material-icons">more_vert</i>
                                                  </a>
                                                  <ul class="dropdown-menu pull-right">
                                                      <li>
                                                          <a href="image-slider.php?id=<?php echo $security->encrypt($result_data['id']) ?>"
                                                          <i class="fas fa-edit"></i>&nbsp;&nbsp;Edit</a></li>
                                                      <li>
                                                          <a href="image-slider.php?delete_data=<?php echo $security->encrypt($result_data['id']) ?>&img_path=<?php echo $result_data['img_name']; ?>"
                                                             onclick="return confirm('Are You sure you want to delete?');" <i
                                                              class="fas fa-trash-alt"></i>&nbsp;&nbsp;Delete</a>
                                                      </li>
                                                      <li>
                                                          <a onclick="return confirm('Are You sure you want to <?php echo $result_data['status'] == 0 ? 'publish' : 'unpublish'; ?>?');"
                                                             href="image-slider.php?publishData=<?php echo $security->encrypt($result_data['id']) ?>&action=<?php echo $result_data['status'] == 0 ? "publish" : "unpublish"; ?> "><i
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
<?php include "assets/common-includes/footer_includes.php" ?>
</body>
</html>