<?php
ob_start();
include "../controller/ManageUser.php";
$manage = new ManageUser();
include "../controller/validator.php";
$validate = new Validator();
include '../sendMail/sendMail.php';
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();

if (!isset($_SESSION['email'])){
    header('location:../login.php');
}


$maxsize = 10485760;

$error = false;
$errorMessage = "";
$errorFile = false;
$errorMessageFile = "";
$emailError = false;
$emailErrorMessage = "";


$imgUploadStatus = false;
$fileUploadStatus = false;
$profileUploadStatus = false;

/*This method used for update the Branch data*/



if (isset($_GET['user_id'])) {
    $user_id = $security->decrypt($_GET['user_id']);
    $form_data = $manage->getSpecificDealerProfileByUserIdForUser($user_id);
    if ($form_data != null) {
        $name = $form_data['name'];
        $password = $form_data['password'];
        $email = $form_data['email'];
        $contact_no = $form_data['contact_no'];
        $gender = $form_data['gender'];
        $date_of_birth = $form_data['date_of_birth'];
        $alter_contact_no = $form_data['altr_contact_no'];
        $state = $form_data['state'];
        $city = $form_data['city'];
        $id_proof = $form_data['id_proof'];
        $light_bill = $form_data['light_bill'];
        $c_name = $form_data['c_name'];
        $c_registered = $form_data['c_registered'];
        $gstin_no = $form_data['gstin_no'];
        $pan_no = $form_data['pan_no'];
        $address = $form_data['address'];
        $landline_no = $form_data['landline_no'];
        $office_address = $form_data['office_address'];
        $website = $form_data['website'];
        $b_email_id = $form_data['b_email_id'];
        $img_name = $form_data['img_name'];
        $category = $form_data['category'];
        $dealer_code = $form_data['dealer_code'];
        $profilePath = "../dealer/uploads/" . $email . "/profile/" . $form_data['img_name'];
        if($form_data['light_bill']!=""){
            $lightPath ="../dealer/uploads/" . $email . "/light-bill/" . $form_data['light_bill'];
        }else{
            $lightPath = $form_data['light_bill'];
        }
        if($form_data['id_proof']!=""){
            $id_proofPath ='../dealer/uploads/' . $email . '/id-proof/' . $form_data['id_proof'];
        }else{
            $id_proofPath =$form_data['id_proof'];
        }
        if($form_data['cancel_check']!=""){
            $cancelCheckPath ='uploads/' . $email . '/id-proof/' . $form_data['cancel_check'];
        }else{
            $cancelCheckPath =$form_data['cancel_check'];
        }
        $user_type = $form_data['user_type'];
    }


    $displayCustomer = $manage->displayCustomer($dealer_code);
    if ($displayCustomer != null) {
        $customerCount = mysqli_num_rows($displayCustomer);
    } else {
        $customerCount = 0;
    }


    $totalWalletAmount = $manage->displayDealerTotalWalletAmount($dealer_code);
    $completedWalletAmount = $manage->displayDealerWalletAmount($dealer_code,"completed");
    $pendingWalletAmount = $manage->displayDealerWalletAmount($dealer_code,"pending");

    $dealerCommerse = $manage->displayAllUserOFDealer($dealer_code);
    if ($dealerCommerse != null) {
        $countDealerCommerce = mysqli_num_rows($dealerCommerse);
    } else {
        $countDealerCommerce = 0;
    }
}

if (isset($_POST['btn_update'])) {
    if (isset($_POST['dob']) && $_POST['dob'] != "") {
        $validateDob = $validate->validDateChecker($_POST['dob'], "dd/mm/yyyy");
        if ($validateDob) {
            $dob = $_POST['dob'];
        }
    } else {
        $error = true;
        $errorMessage .= 'Please enter date of birth.<br>';
    }
    if (isset($_POST['txt_state']) && $_POST['txt_state'] != "") {
        $txt_state = $_POST['txt_state'];
    } else {
        $error = true;
        $errorMessage .= "Please enter state.<br>";
    }
    if (isset($_POST['txt_city']) && $_POST['txt_city'] != "") {
        $txt_city = $_POST['txt_city'];
    } else {
        $error = true;
        $errorMessage .= "Please enter city.<br>";
    }
    if (isset($_POST['txt_address']) && $_POST['txt_address'] != "") {
        $txt_address = $_POST['txt_address'];
    } else {
        $error = true;
        $errorMessage .= "Please enter address.<br>";
    }
    if (isset($_POST['txt_c_name']) && $_POST['txt_c_name'] != "") {
        $txt_c_name = $_POST['txt_c_name'];
    } else {
        $error = true;
        $errorMessage .= "Please enter company name.<br>";
    }
    if (isset($_POST['drp_type']) && $_POST['drp_type'] != "") {
        $drp_type = $_POST['drp_type'];
    } else {
        $error = true;
        $errorMessage .= "Please enter type.<br>";
    }
    if (isset($_POST['office_address']) && $_POST['office_address'] != "") {
        $office_address = $_POST['office_address'];
    } else {
        $error = true;
        $errorMessage .= "Please enter office address.<br>";
    }

    if (isset($_FILES['upload']) /* 4 means there is no file selected*/) {
        if($_FILES['upload']['error'][0] != 4){
            $imgUploadStatus = true;
            $directory_name = "../dealer/uploads/" . $email . "/light-bill/";
            $extension = array('.jpg', '.JPG', '.jpeg', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG','.pdf','.doc','.docx','.PDF','.DOC','.DOCX');
            $maxsize = 2097152;
            $total = count($_FILES['upload']['name']);
            for ($i = 0; $i < $total; $i++) {
                $filename = $_FILES['upload']['name'][$i];
                $extensionStatus = $validate->validateFileExtension($filename, $extension);
                if (!$extensionStatus) {
                    $error = true;
                    $errorMessage = "Please select valid file extension";
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
            $directory_file_name = "../dealer/uploads/" . $email . "/id-proof/";
            $extension = array('.jpg', '.JPG', '.jpeg', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG','.pdf','.doc','.docx','.PDF','.DOC','.DOCX');

            $total = count($_FILES['upload-file']['name']);
            for ($i = 0; $i < $total; $i++) {
                $filename = $_FILES['upload-file']['name'][$i];
                $extensionStatus = $validate->validateFileExtension($filename, $extension);
                if (!$extensionStatus) {
                    $error = true;
                    $errorMessage = "Please select valid file extension";
                }
            }
        }/*else{
            if ($form_data['id_proof']=="") {
                $error = true;
                $errorMessage = 'Please upload file';
            }
        }*/
    }
    if (isset($_FILES['upload-check']) /* 4 means there is no file selected*/) {
        if ($_FILES['upload-check']['error'][0] != 4) {

            $checkUploadStatus = true;
            $directory_file_name = "../dealer/uploads/" . $email . "/id-proof/";
            $extension = array('.jpg', '.JPG', '.jpeg', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG','.pdf','.doc','.docx','.PDF','.DOC','.DOCX');

            $total = count($_FILES['upload-check']['name']);
            for ($i = 0; $i < $total; $i++) {
                $filename = $_FILES['upload-check']['name'][$i];
                $extensionStatus = $validate->validateFileExtension($filename, $extension);
                if (!$extensionStatus) {
                    $error = true;
                    $errorMessage .= "Please select valid file extension";
                }
            }
        }/*else{
            if ($form_data['cancel_check']=="") {
                $error = true;
                $errorMessage .= 'Please upload cancel check<br>';
            }
        }*/
    }

    if (!$error) {
        $digits = 4;
        $randomNum = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        $newimgname = "";
        $newfilename = "";
        $checkname = "";
        if ($imgUploadStatus) {
            /*    $filename = '/path/to/foo.txt';
                if (file_exists($filename)) {
                    echo "The file $filename exists";
                } else {
                    echo "The file $filename does not exist";
                }*/
            for ($i = 0; $i < $total; $i++) {
                $filearray = array();
                $tmpFilePath = $_FILES['upload']['tmp_name'][$i];
                $file_extension = substr($_FILES['upload']['name'][$i], (strrpos($_FILES['upload']['name'][$i], '.') + 1));
                $newimgname = $randomNum . '.' . $file_extension;
                $newimgname = str_replace([' ', '_'], '-', $newimgname);
                $newPath = $directory_name . $newimgname;

                if (($_FILES['upload']['size'][$i] <= $maxsize)) {
                    $success =  compressImage($tmpFilePath, $newPath, 90);
                    if(!$success) {
                        if (!move_uploaded_file($tmpFilePath, $newPath)) {
                            $error = true;
                            $errorMessage .= "Failed to upload file";
                        }else{
                            if (file_exists($lightPath) && $form_data['light_bill'] !="") {
                                unlink($lightPath);
                            }
                        }
                    }
                }else{
                    $error = true;
                    $errorMessage .= "File Size should be less than 2mb.";

                }

            }
        }
        if ($fileUploadStatus) {

            for ($i = 0; $i < $total; $i++) {
                $filearray = array();
                $tmpFilePath1 = $_FILES['upload-file']['tmp_name'][$i];
                $file_extension1 = substr($_FILES['upload-file']['name'][$i], (strrpos($_FILES['upload-file']['name'][$i], '.') + 1));
                $newfilename = $randomNum . '.' . $file_extension1;
                $newfilename = str_replace([' ', '_'], '-', $newfilename);
                $newPath1 = $directory_file_name . $newfilename;
                if (($_FILES['upload-file']['size'][$i] <= $maxsize)) {
                    $success =  compressImage($tmpFilePath1, $newPath1, 90);
                    if(!$success) {
                        if (!move_uploaded_file($tmpFilePath1, $newPath1)) {
                            $error = true;
                            $errorMessage .= "Failed to upload file";
                        }else{
                            if (file_exists($id_proofPath) && $form_data['id_proof']!="") {
                                unlink($id_proofPath);
                            }
                        }
                    }
                }else{
                    $error = true;
                    $errorMessage .= "File Size should be less than 2mb.";

                }
            }
        }
        if ($checkUploadStatus) {

            for ($i = 0; $i < $total; $i++) {
                $filearray = array();
                $tmpFilePath2 = $_FILES['upload-check']['tmp_name'][$i];
                $file_extension2 = substr($_FILES['upload-check']['name'][$i], (strrpos($_FILES['upload-check']['name'][$i], '.') + 1));
                $checkname = $randomNum . '.' . $file_extension2;
                $checkname = str_replace([' ', '_'], '-', $checkname);
                $newPath2 = $directory_file_name . $checkname;
                if (($_FILES['upload-check']['size'][$i] <= $maxsize)) {
                    $success = compressImage($tmpFilePath2, $newPath2, 90);
                    if(!$success) {
                        if (!move_uploaded_file($tmpFilePath2, $newPath2)) {
                            $error = true;
                            $errorMessage .= "Failed to upload file";
                        }else{
                            if (file_exists($cancelCheckPath) && $form_data['cancel_check']!="") {
                                unlink($cancelCheckPath);
                            }
                        }
                    }
                }else{
                    $error = true;
                    $errorMessage .= "File Size should be less than 2mb.";
                }
            }
        }



           if(!$error){
               if(trim($newimgname) == ''){
                   $newimgname =  $form_data['light_bill'];
               }
               if(trim($newfilename)==''){
                   $newfilename = $form_data['id_proof'];
               }
               if (trim($checkname) =="") {
                   $checkname = $form_data['cancel_check'];
               }
               $status = $manage->updateDealerProfileById($_POST['txt_name'], $_POST["gender"], $_POST['dob'], $_POST['txt_alt_contact'],
                   $_POST['txt_state'], $_POST['txt_city'], $_POST['txt_address'], $newfilename, $newimgname, $_POST['txt_c_name'],
                   $_POST['drp_type'], $_POST['txt_gstin'], $_POST['pan_no'], $_POST['txt_landline'], $_POST['office_address'],
                   $_POST['txt_website'], $_POST['b_email_id'], $_POST['txt_category'],$_POST['drp_user_type'],$checkname, $security->decrypt($_GET['user_id']));
               if ($status) {
                   $update_password = $manage->update($manage->dealerLoginTable,array('password'=>$_POST['password']),array('user_id'=>$security->decrypt($_GET['user_id'])));


                   if (isset($_GET['user_id'])) {
                       $user_id = $security->decrypt($_GET['user_id']);
                       $form_data = $manage->getSpecificDealerProfileByUserIdForUser($user_id);
                       if ($form_data != null) {
                           $name = $form_data['name'];
                           $password = $form_data['password'];
                           $email = $form_data['email'];
                           $contact_no = $form_data['contact_no'];
                           $gender = $form_data['gender'];
                           $date_of_birth = $form_data['date_of_birth'];
                           $alter_contact_no = $form_data['altr_contact_no'];
                           $state = $form_data['state'];
                           $city = $form_data['city'];
                           $id_proof = $form_data['id_proof'];
                           $light_bill = $form_data['light_bill'];
                           $c_name = $form_data['c_name'];
                           $c_registered = $form_data['c_registered'];
                           $gstin_no = $form_data['gstin_no'];
                           $pan_no = $form_data['pan_no'];
                           $address = $form_data['address'];
                           $landline_no = $form_data['landline_no'];
                           $office_address = $form_data['office_address'];
                           $website = $form_data['website'];
                           $b_email_id = $form_data['b_email_id'];
                           $img_name = $form_data['img_name'];
                           $category = $form_data['category'];
                           $dealer_code = $form_data['dealer_code'];
                           $profilePath = "../dealer/uploads/" . $email . "/profile/" . $form_data['img_name'];
                           if($form_data['light_bill']!=""){
                               $lightPath ="../dealer/uploads/" . $email . "/light-bill/" . $form_data['light_bill'];
                           }else{
                               $lightPath = $form_data['light_bill'];
                           }
                           if($form_data['id_proof']!=""){
                               $id_proofPath ='../dealer/uploads/' . $email . '/id-proof/' . $form_data['id_proof'];
                           }else{
                               $id_proofPath =$form_data['id_proof'];
                           }
                           if($form_data['cancel_check']!=""){
                               $cancelCheckPath ='uploads/' . $email . '/id-proof/' . $form_data['cancel_check'];
                           }else{
                               $cancelCheckPath =$form_data['cancel_check'];
                           }
                           $user_type = $form_data['user_type'];
                       }


                       $displayCustomer = $manage->displayCustomer($dealer_code);
                       if ($displayCustomer != null) {
                           $customerCount = mysqli_num_rows($displayCustomer);
                       } else {
                           $customerCount = 0;
                       }


                       $totalWalletAmount = $manage->displayDealerTotalWalletAmount($dealer_code);
                       $completedWalletAmount = $manage->displayDealerWalletAmount($dealer_code,"completed");
                       $pendingWalletAmount = $manage->displayDealerWalletAmount($dealer_code,"pending");

                       $dealerCommerse = $manage->displayAllUserOFDealer($dealer_code);
                       if ($dealerCommerse != null) {
                           $countDealerCommerce = mysqli_num_rows($dealerCommerse);
                       } else {
                           $countDealerCommerce = 0;
                       }
                   }
                   $error = false;
                   $errorMessage = "Dealer Profile Updated SuccessFully";
               } else {
                   $error = true;
                   $errorMessage = "Issue while updating details please try again.";
               }
           }
    }
}



?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title><?php echo $name; ?> - Basic Information</title>
    <?php include "assets/common-includes/header_includes.php" ?>
    <link rel="stylesheet" href="assets/croppie/croppie.css">
    <style>
        .contact-icon-btm{
            position: absolute;
            top: 60%;
            right: 16%;
        }
    </style>
</head>
<body>
<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>

<section class="content">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a href="#">
                    <div class="info-box bg-pink hover-expand-effect">
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="content">
                            <div class="text">Total Customer</div>
                            <div
                                class="number"><?php if (isset($countDealerCommerce)) echo $countDealerCommerce; ?></div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a href="view-wallet-history.php?dealer_code=<?php echo $security->encryptWebservice($dealer_code) ?>">
                    <div class="info-box bg-grey hover-expand-effect">
                        <div class="icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div class="content">
                            <div class="text">Total Wallet Amount</div>
                            <div class="number"><?php if ($totalWalletAmount != null) {
                                    echo $totalWalletAmount;
                                } else {
                                    echo "0";
                                } ?></div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a href="view-wallet-history.php?dealer_code=<?php echo $security->encryptWebservice($dealer_code) ?>">
                    <div class="info-box bg-grey hover-expand-effect">
                        <div class="icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div class="content">
                            <div class="text">Total Pending Amount</div>
                            <div class="number"><?php if ($pendingWalletAmount != null) {
                                    echo $pendingWalletAmount;
                                } else {
                                    echo "0";
                                } ?></div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a href="view-wallet-history.php?dealer_code=<?php echo $security->encryptWebservice($dealer_code) ?>">
                    <div class="info-box bg-grey hover-expand-effect">
                        <div class="icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div class="content">
                            <div class="text">Total Completed Amount</div>
                            <div class="number"><?php if ($completedWalletAmount != null) {
                                    echo $completedWalletAmount;
                                } else {
                                    echo "0";
                                } ?></div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="clearfix">
        <!--<div class="col-lg-4 col-md-5 col-sm-12 col-xs-12">
            <div class="margin_div1">
                <div class="card">

                    <div class="body card_padding">
                        <form id="basic_user_profile" method="POST" action="" enctype="multipart/form-data">
                            <ul class="profile-left-ul">

                            </ul>
                        </form>
                    </div>
                </div>
            </div>
        </div>-->
        <div class="col-lg-12 col-md-5 col-sm-12 col-xs-12 padding_both">
            <div class="row margin_div_web">
                <div class="card">
                    <div class="header">
                        <h2>
                            Total Customer <span class="badge"><?php
                                if (isset($customerCount)) echo $customerCount;
                                ?></span>
                        </h2>
                    </div>
                    <div class="body">
                        <table id="dtHorizontalVerticalExample" class="table table-striped table-bordered table-sm "
                               cellspacing="0"
                               width="100%">
                            <thead>
                            <tr class="back-color">
                                <th>Name</th>
                                <th>Contact</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if ($displayCustomer != null) {
                                while ($row = mysqli_fetch_array($displayCustomer)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['name']; ?></td>
                                        <td><?php echo $row['contact_no']; ?></td>
                                        <td><?php echo $row['email']; ?></td>
                                        <td>
                                            <a href="user-management-view.php?user_id=<?php echo $security->encrypt($row['user_id']) ?>"><i
                                                    class="fas fa-eye"></i>&nbsp;&nbsp;View profile</a></td>

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
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding_both">
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

                        <form id="basic_user_info" method="POST" action="" enctype="multipart/form-data">
                            <fieldset>
                                <legend class="legend_font_size" align="left">Basic Information</legend>
                                <ul class="dealer-profile-ul">
                                    <li>
                                        <div class="form-group form-float text-align-profile"
                                             style="position: relative">
                                            <div id="uploaded_image">
                                            <!----><?php /*echo '<img src="" style="width: 15%;border-radius: 50%;" /><br />'; */ ?>
                                            <img
                                                src="<?php if (!file_exists($profilePath) && $gender == "Male" or $form_data['img_name'] == "") {
                                                    echo "uploads/male_user.png";
                                                } elseif (!file_exists($profilePath) && $gender == "Female" or $form_data['img_name'] == "") {
                                                    echo "uploads/female_user.png";
                                                } else {
                                                    echo $profilePath;
                                                } ?>" style="width: 50%;border-radius: 50%;">
                                                </div>
                                            <div class="contact-icon-btm">
                                                <input type="file" name="upload_image"  id="upload_image" style="display: none" accept="image/*"/>
                                                <a id="OpenImgUpload" >
                                                    <img src="assets/images/camera.png" style="width: 75%">
                                                </a>
                                            </div>
                                        </div>
                                    </li>


                                    <li>
                                        <div class="width-prf">
                                            <div class="form-group form-group-left form-float">
                                                <div class="">
                                                    <label class="form-label">Contact</label>
                                                    <lable name=label_txt_name"
                                                           class="form-control"> <?php echo $contact_no; ?>
                                                    </lable>
                                                </div>
                                            </div>
                                    </li>
                                    <li>
                                        <div class="width-prf">
                                            <div class="form-group form-group-left form-float">
                                                <div class="">
                                                    <label class="form-label">Email</label>
                                                    <lable name=label_txt_email"
                                                           class="form-control"><?php echo $email; ?>
                                                    </lable>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="width-prf">
                                            <label class="form-label">Name</label> <span>*</span>

                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input name="txt_name" class="form-control"
                                                           value="<?php if (isset($name)) echo $name; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-group">
                                            <label class="control-label">Password</label>

                                            <div class="form-line">
                                                <input id="password-field" type="text" class="form-control"
                                                       name="password"
                                                       value="<?php if (isset($password)) echo $password; ?>">
                                                    <span toggle="#password-field"
                                                          class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="width-prf">
                                            <label>Gender</label> <span>*</span>

                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <select name="gender" class="form-control gender_li">
                                                        <option name="">Select an option</option>
                                                        <option <?php if ($gender == 'Male') echo 'selected' ?>
                                                            name="male">Male
                                                        </option>
                                                        <option <?php if ($gender == 'Female') echo 'selected' ?>
                                                            name="female">Female
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-group">
                                            <label class="control-label">Date of birth</label> <span>*</span>

                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="date" class="form-control" name="dob"
                                                           value="<?php if (isset($date_of_birth)) echo $date_of_birth; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="width-prf">
                                            <label class="form-label">Alternate Contact</label>

                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                                    <input type="text" name="txt_alt_contact" class="form-control"
                                                           onkeypress="return isNumberKey(event)"
                                                           placeholder="Alternet Contact Number"
                                                           value="<?php if (isset($alter_contact_no)) echo $alter_contact_no; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-group">
                                            <label class="control-label">State</label> <span>*</span>

                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="txt_state"
                                                           placeholder="Enter State"
                                                           value="<?php if (isset($state)) echo $state; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-group">
                                            <label class="control-label">City</label> <span>*</span>

                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="txt_city"
                                                           value="<?php if (isset($city)) echo $city; ?>"
                                                           placeholder="Enter State">
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div>
                                            <label>Aadhar card or Pan card</label> <span class="required_field">*</span>

                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="file" id="upload-file" name="upload-file[]" style="display: block"
                                                           accept=".png, .jpg, .jpeg,.JPG,.PNG,.JPEG,.pdf,.doc,.docx">
                                                    <?php

                                                    $idProofPath = "../dealer/uploads/$email/id-proof/".$form_data['id_proof'];
                                                    if (file_exists($idProofPath) && $form_data['id_proof'] != "") echo '<a href="' . $idProofPath . '" target="_blank">Preview</a>'; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div>
                                            <label class="form-label">Address Proof</label> <span
                                                class="required_field">*</span>

                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="file" id="upload" name="upload[]" style="display: block"
                                                           accept=".png, .jpg, .jpeg,.JPG,.PNG,.JPEG,.pdf,.doc,.docx">
                                                    <?php


                                                    $lightBillPath = "../dealer/uploads/$email/light-bill/" . $form_data['light_bill'];

                                                    if (file_exists($lightBillPath) && $form_data['light_bill'] != "") echo '<a href="' . $lightBillPath . '" target="_blank">Preview</a>'; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="width-prf">
                                            <label>Cancelled Bank Cheque</label> <span class="required_field">*</span>
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="file" id="upload-check" name="upload-check[]"  style="display: block"
                                                           accept=".png, .jpg, .jpeg,.JPG,.PNG,.JPEG,.pdf,.doc,.docx">
                                                    <?php
                                                    $checkFilePath = "../dealer/uploads/$email/id-proof/" . $form_data['cancel_check'];
                                                    if (file_exists($checkFilePath) && $form_data['cancel_check'] != "") echo '<a href="' . $checkFilePath . '" target="_blank">Preview</a>'; ?>
                                                </div>
                                                <span style='color: red'>Note: Allow only images & pdf (Max 2 MB)</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="width-prf">
                                        <div class="width-prf">
                                            <label class="form-label">Address</label> <span>*</span>

                                            <div class="form-group form-float">
                                                <div class="form-line">
                                    <textarea name="txt_address" class="form-control"
                                              placeholder="Address"><?php if (isset($address)) echo $address; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </fieldset>
                            <a data-toggle="collapse" data-target="#demo">
                                <div class="about_company_icon">
                                    <span class="fas fa-chevron-down"></span>
                                </div>
                            </a>

                            <div id="demo" class="collapse">
                                <fieldset>
                                    <legend class="legend_font_size" align="left">Company Info</legend>
                                    <ul class="dealer-profile-ul">
                                        <li>
                                            <div class="width-prf">
                                                <label>User Type</label> <span class="required_field">*</span>

                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <select name="drp_user_type"
                                                                class="form-control gender_li" onchange="userTypeFun(this.value)">
                                                            <option <?php if ($user_type == 'Company') echo 'selected' ?>>Company
                                                            </option>
                                                            <option <?php if ($user_type == 'Individual') echo 'selected' ?>>Individual
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <span id="append_here"></span>
                                        <li>
                                            <div class="width-prf">
                                                <label class="form-label">Company Name</label> <span>*</span>

                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                                        <input type="text" name="txt_c_name" class="form-control"
                                                               placeholder="Enter Company Name"
                                                               value="<?php if (isset($c_name)) echo $c_name; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="width-prf">
                                                <label>Type</label> <span>*</span>

                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <select  name="drp_type"
                                                                class="form-control gender_li">
                                                            <option name="">Select Type</option>
                                                            <option <?php if ($c_registered == 'Registered') echo 'selected' ?>
                                                                name="registered">Registered
                                                            </option>
                                                            <option <?php if ($c_registered == 'Unregistered') echo 'selected' ?>
                                                                name="Unregistered">Unregistered
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="width-prf">
                                                <label class="form-label">Landline No</label>

                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input name="txt_landline" class="form-control"
                                                               placeholder="Landline Number"
                                                               value="<?php if (isset($landline_no)) echo $landline_no; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="width-prf">
                                                <label class="form-label">GSTIN No</label>

                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                                        <input type="text" name="txt_gstin" class="form-control"
                                                               placeholder="GSTIN number"
                                                               value="<?php if (isset($gstin_no)) echo $gstin_no; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="width-prf">
                                                <label class="form-label">PAN NO</label>

                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input name="pan_no" class="form-control"
                                                               placeholder="PAN number"
                                                               value="<?php if (isset($pan_no)) echo $pan_no; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="width-prf">
                                                <label class="form-label">Office Address</label> <span>*</span>

                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                                        <input name="office_address" class="form-control"
                                                               placeholder="Office address"
                                                               value="<?php if (isset($office_address)) echo $office_address; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="width-prf">
                                                <label class="form-label">Website</label>

                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input name="txt_website" type="url" class="form-control"
                                                               placeholder="Enter Website"
                                                               value="<?php if (isset($website)) echo $website; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="width-prf">
                                                <label class="form-label">Business Email Id</label>

                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                                        <input name="b_email_id" class="form-control"
                                                               placeholder="Business email id"
                                                               value="<?php if (isset($b_email_id)) echo $b_email_id; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="width-prf">
                                                <label class="form-label">Category</label>

                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                                        <input name="txt_category" class="form-control"
                                                               placeholder="Category"
                                                               value="<?php if (isset($category)) echo $category; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </fieldset>
                            </div>
                            <div class="btn-up-div " style="margin-top: 20px;">
                                <div class="form-group form_inline">
                                    <div class="example">

                                        <button name="btn_update" type="submit"
                                                class="btn btn-primary waves-effect">Update
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>




<script src="assets/plugins/jquery/jquery.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/bootstrap-tokenfield.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>

<!-- Bootstrap Core Js -->
<script src="assets/plugins/bootstrap/js/bootstrap.js"></script>

<!-- Select Plugin Js -->
<script src="assets/plugins/bootstrap-select/js/bootstrap-select.js"></script>

<!-- Slimscroll Plugin Js -->
<script src="assets/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

<!-- Waves Effect Plugin Js -->
<script src="assets/plugins/node-waves/waves.js"></script>

<!-- Jquery CountTo Plugin Js -->
<script src="assets/plugins/jquery-countto/jquery.countTo.js"></script>

<!-- Morris Plugin Js -->
<script src="assets/plugins/raphael/raphael.min.js"></script>
<script src="assets/plugins/morrisjs/morris.js"></script>

<!-- ChartJs -->
<script src="assets/plugins/chartjs/Chart.bundle.js"></script>

<!-- Sparkline Chart Plugin Js -->
<script src="assets/plugins/jquery-sparkline/jquery.sparkline.js"></script>

<!-- Custom Js -->
<script src="assets/js/admin.js"></script>
<!--<script src="https://cdn.rawgit.com/t4t5/sweetalert/v0.2.0/lib/sweet-alert.min.js" type="text/javascript"></script>-->
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.jqueryui.min.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.js"></script>
<!-- MDBootstrap Datatables  -->
<script type="text/javascript" src="assets/js/datatables.min.js"></script>
<script type="text/javascript" src="assets/js/important.js"></script>
<script src="assets/croppie/croppie.js"></script>
<!--<script type="text/javascript" src="upload.js"></script>-->

<div id="uploadimageModal" class="modal" role="dialog">
    <div class="modal-dialog dialog_width">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Upload & Crop Image</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div id="image_demo" style="width:350px; margin-top:30px"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success crop_image">Upload Image</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){

        $image_crop = $('#image_demo').croppie({
            enableExif: true,
            viewport: {
                width:250,
                height:250,
                type:'circle' //circle
            },
            boundary:{
                width:300,
                height:300
            }
        });

        $('#upload_image').on('change', function(){
            var reader = new FileReader();
            reader.onload = function (event) {
                $image_crop.croppie('bind', {
                    url: event.target.result
                }).then(function(){
                    console.log('jQuery bind complete');
                });
            }
            reader.readAsDataURL(this.files[0]);
            $('#uploadimageModal').modal('show');
        });

        $('.crop_image').click(function(event){
            $image_crop.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function(response){
                $.ajax({
                    url:"dealer-profile-upload.php",
                    type: "POST",
                    data:{"image": response,"email": '<?php echo $email; ?>',"user_id": '<?php echo $user_id; ?>'},
                    success:function(data)
                    {
                        $('#uploadimageModal').modal('hide');
                        $('#uploaded_image').html(data);
                    }
                });
            })
        });

    });
</script>
<script>


    function userTypeFun(value){
        if(value == 'Individual'){
            $('input[name=txt_c_name]').val('<?php if(isset($name)) echo $name ?>');
            $('input[name=b_email_id]').val('<?php echo $email; ?>');
            $('input[name=office_address]').val($('textarea[name=txt_address').val());
            $('ul.dealer-profile-ul').find('li.change_pos').prependTo('ul.dealer-profile-ul #append_here');
        }else{
            $('input[name=txt_c_name]').val('');
        }

    }
</script>
<script>
    $('#OpenImgUpload').click(function(){ $('#upload_image').trigger('click'); });
</script>

<script>

    $('.toggle_password').click(function(){

        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });

</script>

<!--<script type="text/javascript">
    document.getElementById("b3").onclick = function () {
        swal("Good job!", "You clicked the button!", "success");
    };
</script>-->


</body>
</html>