<?php

ob_start();
include "../controller/ManageUser.php";
$manage = new ManageUser();
include "../controller/validator.php";
$validate = new Validator();
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
$controller = new Controller();
$con = $controller->connect();

$maxsize = 1048576;
include_once('lib/ImgCompressor.class.php');
$imgUploadStatus = false;

if (isset($_GET['token']) && isset($_GET['type']) && $_GET['type'] == "android") {
    $token = $security->decryptWebservice($_GET['token']);
    $seperate_token = explode('+', $token);
    $validateUserId = $manage->validAPIKEYId($seperate_token[0], $seperate_token[1]);
    if ($validateUserId) {
        $userSpecificResult = $manage->getUserProfile($seperate_token[0]);
        if ($userSpecificResult != null) {
            $android_name = $userSpecificResult["name"];
            $android_email = $userSpecificResult["email"];
            $android_custom_url = $userSpecificResult["custom_url"];
            $android_contact = $userSpecificResult['contact_no'];
            $android_type = $userSpecificResult['type'];
        }
        $_SESSION['type'] = $android_type;
        $_SESSION['email'] = $android_email;
        $_SESSION['name'] = $android_name;
        $_SESSION['contact'] = $android_contact;
        $_SESSION['custom_url'] = $android_custom_url;
        $_SESSION['id'] = $security->encrypt($seperate_token[0]);
    } else {
        header('location:404-not-found.php?' . $android_url);
    }
} elseif (!isset($_SESSION['email'])) {
    header('location:../login.php');
} else {
    $android_url = "";
}
if (!isset($_SESSION['email'])) {
    header('location:../login.php');
}

$error = false;
$errorMessage = "";
include("session_includes.php");
include "validate-page.php";

if (isset($_POST['btn_update'])) {
    if (isset($_POST['txt_name']) && $_POST['txt_name'] != "") {
        $name = mysqli_real_escape_string($con, $_POST['txt_name']);
    } else {
        $error = true;
        $errorMessage .= "Please enter service name.<br>";
    }
    if (isset($_POST['txt_des']) && $_POST['txt_des'] != "") {
        $description = mysqli_real_escape_string($con, $_POST['txt_des']);
    } else {
        $error = true;
        $errorMessage .= "Please enter description.<br>";
    }


    if (!$error) {

        // $status = $manage->updateService($name, $description, $cover_name, $security->decrypt($_GET['display_data']));
        /* if ($status) {
             $errorMessage = "details Updated successfully";
             header('location:manage-section.php');
         } else {
             $error = true;
             $errorMessage = "Issue while updating details, Please try again.";
         }*/


    }

}

$checkProfile = $manage->CheckUserId($manage->getUserSessionId(), 10);

$checkService = $manage->CheckUserId($manage->getUserSessionId(), 1);
$checkProduct = $manage->CheckUserId($manage->getUserSessionId(), 11);
$checkGallery = $manage->CheckUserId($manage->getUserSessionId(), 2);
$checkClient = $manage->CheckUserId($manage->getUserSessionId(), 4);
$checkTeam = $manage->CheckUserId($manage->getUserSessionId(), 6);
$checkBank = $manage->CheckUserId($manage->getUserSessionId(), 7);

$getServiceIconProfile = $manage->serviceIconUserId(10);
$getServiceIconService = $manage->serviceIconUserId(1);
$getProductIconService = $manage->serviceIconUserId(11);
$getServiceIconGallery = $manage->serviceIconUserId(2);
$getServiceIconClient = $manage->serviceIconUserId(4);
$getServiceIconTeam = $manage->serviceIconUserId(6);
$getServiceIconBank = $manage->serviceIconUserId(7);


$ProfileSectionStatus = $manage->getSectionStatus($manage->getUserSessionId(), 10);
$ServiceSectionStatus = $manage->getSectionStatus($manage->getUserSessionId(), 1);
$ProductSectionStatus = $manage->getSectionStatus($manage->getUserSessionId(), 11);
$gallerySectionStatus = $manage->getSectionStatus($manage->getUserSessionId(), 2);
$ClientSectionStatus = $manage->getSectionStatus($manage->getUserSessionId(), 4);
$TeamSectionStatus = $manage->getSectionStatus($manage->getUserSessionId(), 6);
$BankSectionStatus = $manage->getSectionStatus($manage->getUserSessionId(), 7);


$get_section = $manage->getSectionName();
if ($get_section != null) {
    $profile = $get_section['profile'];
    $services = $get_section['services'];
    $our_service = $get_section['our_service'];
    $product = $get_section['products'];
    $our_product = $get_section['our_product'];
    $gallery = $get_section['gallery'];
    $images = $get_section['images'];
    $videos = $get_section['videos'];
    $clients = $get_section['clients'];
    $client_name = $get_section['client_name'];
    $client_review_tab = $get_section['client_review'];
    $team = $get_section['team'];
    $our_team = $get_section['our_team'];
    $bank = $get_section['bank'];
    $payment = $get_section['payment'];
    $basic_info = $get_section['basic_info'];
    $company_info = $get_section['company_info'];
} else {
    $profile = "Profile";
    $services = "Services";
    $our_service = "Our Services";
    $product = "Products";
    $our_product = "Our Products";
    $gallery = "Gallery";
    $images = "Images";
    $videos = "Videos";
    $clients = "Clients";
    $client_name = "Clients";
    $client_review_tab = "Client's Reviews";
    $team = "Team";
    $our_team = "Our Team";
    $bank = "Bank";
    $payment = "Payment";
    $basic_info = "Basic Info";
    $company_info = "Company Info";
}


if (isset($_POST['btn_save_image'])) {
    if (isset($_FILES['upload']) && $_FILES['upload']['error'][0] != 4) {
        $imgUploadStatus = true;
        $create_directory = "uploads/" . $session_email . "/section_icon/";
        if (!file_exists($create_directory)) {
            mkdir($create_directory, 0777, true);
        }
        $directory_name = "uploads/" . $session_email . "/section_icon/";
        $extension = array('.jpg', 'JPG', '.JPG', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG');
        $total = count($_FILES['upload']['name']);
        for ($i = 0; $i < $total; $i++) {
            $filename = $_FILES['upload']['name'][$i];
            $extensionStatus = $validate->validateFileExtension($filename, $extension);
            if ($extensionStatus == false) {
                $error = true;
                $errorMessage = "Please select valid file extension";
            }
            if (($_FILES['upload']['size'][$i] >= $maxsize)) {
                $error = true;
                $errorMessage = 'File too large. File must be less than 1 megabytes.';
            }
        }
    } else {
        $error = true;
        $errorMessage = 'Upload at least one image';
    }
    if (!$error) {
        $cover_name = "";
        $digits = 4;
        $randomNum = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        $newfilename = "";
        if ($imgUploadStatus) {
            for ($i = 0; $i < $total; $i++) {
                $filearray = array();
                $tmpFilePath = $_FILES['upload']['tmp_name'][$i];
                $file_original_name = substr($_FILES['upload']['name'][$i], 0, strrpos($_FILES['upload']['name'][$i], '.'));
                $file_extension = substr($_FILES['upload']['name'][$i], (strrpos($_FILES['upload']['name'][$i], '.') + 1));
                $newimgname = $file_original_name . "$" . $randomNum . '.' . $file_extension;
                $cover_name = str_replace([' ', '_'], '-', $newimgname);
                $newPath = $directory_name . $cover_name;
                if (!move_uploaded_file($tmpFilePath, $newPath)) {
                    $error = true;
                    $errorMessage .= "Failed to upload file";
                }
            }
        }
        if (!$error) {
            if ($checkProfile != "") {
                $DeleteFile = "uploads/" . $session_email . "/section_icon/" . $checkProfile['section_img'];
                unlink($DeleteFile);
                $condition = array('user_id' => $manage->getUserSessionId(), 'section_id' => 10);
                $updateData = array('section_img' => $cover_name);
                $status = $manage->update($manage->sectionIconTable, $updateData, $condition);
                if ($status) {
                    $error = false;
                    $errorMessage = "Profile Image Updated successfully";
                    header("location:manage-section.php");
                } else {
                    $error = true;
                    $errorMessage = "Issue while updating details, Please try again.";
                }
            } else {
                $status = $manage->addServiceIcon(10, $cover_name);
                if ($status) {
                    $error = false;
                    $errorMessage = "Profile Image Updated successfully";
                    header("location:manage-section.php");
                } else {
                    $error = true;
                    $errorMessage = "Issue while adding details, Please try again.";
                }
            }
        }
    }
}

if (isset($_POST['btn_save_image_service'])) {

    /* print_r($_FILES['fileUpload']);
     exit;*/
    if (isset($_FILES['fileUpload']) && $_FILES['fileUpload']['error'][0] != 4) {
        $imgUploadStatus = true;
        $create_directory = "uploads/" . $session_email . "/section_icon/";
        if (!file_exists($create_directory)) {
            mkdir($create_directory, 0777, true);
        }
        $directory_name = "uploads/" . $session_email . "/section_icon/";
        $extensions = array('.jpg', 'JPG', '.JPG', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG');
        $total = count($_FILES['fileUpload']['name']);
        for ($i = 0; $i < $total; $i++) {
            $serfilename = $_FILES['fileUpload']['name'][$i];
            $extensionStatus = $validate->validateFileExtension($serfilename, $extensions);
            if ($extensionStatus == false) {
                $error = true;
                $errorMessage = "Please select valid file extension";
            }
            if (($_FILES['fileUpload']['size'][$i] >= $maxsize)) {
                $error = true;
                $errorMessage = 'File too large. File must be less than 1 megabytes.';
            }
        }
    } else {
        $error = true;
        $errorMessage = 'Upload at least one image';
    }
    if (!$error) {
        $service_image = "";
        $digitss = 4;
        $randomNum = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        $servicenewfilename = "";
        if ($imgUploadStatus) {
            for ($i = 0; $i < $total; $i++) {
                $filearray = array();
                $sertmpFilePath = $_FILES['fileUpload']['tmp_name'][$i];
                $serfile_original_name = substr($_FILES['fileUpload']['name'][$i], 0, strrpos($_FILES['fileUpload']['name'][$i], '.'));
                $serfile_extension = substr($_FILES['fileUpload']['name'][$i], (strrpos($_FILES['fileUpload']['name'][$i], '.') + 1));
                $servicenewfilename = $serfile_original_name . "$" . $randomNum . '.' . $serfile_extension;
                $service_image = str_replace([' ', '_'], '-', $servicenewfilename);
                $SernewPath = $directory_name . $service_image;
                if (!move_uploaded_file($sertmpFilePath, $SernewPath)) {
                    $error = true;
                    $errorMessage .= "Failed to upload file";
                }

            }
        }

        if (!$error) {
            if ($checkService != "") {
                $DeleteFile = "uploads/" . $session_email . "/section_icon/" . $checkService['section_img'];
                unlink($DeleteFile);
                $condition = array('user_id' => $manage->getUserSessionId(), 'section_id' => 1);
                $updateData = array('section_img' => $service_image);
                $status = $manage->update($manage->sectionIconTable, $updateData, $condition);
                if ($status) {
                    $error = false;
                    $errorMessage = "Service Image Updated successfully";
                    header("location:manage-section.php");
                } else {
                    $error = true;
                    $errorMessage = "Issue while updating details, Please try again.";
                }
            } else {
                $status = $manage->addServiceIcon(1, $service_image);
                if ($status) {
                    $error = false;
                    $errorMessage = "Service Icon added successfully";
                    header("location:manage-section.php");
                } else {
                    $error = true;
                    $errorMessage = "Issue while adding details, Please try again.";
                }
            }

        }
    }
}

if (isset($_POST['btn_save_image_product'])) {

    /* print_r($_FILES['fileUpload']);
     exit;*/
    if (isset($_FILES['fileProductUpload']) && $_FILES['fileProductUpload']['error'][0] != 4) {
        $imgUploadStatus = true;
        $create_directory = "uploads/" . $session_email . "/section_icon/";
        if (!file_exists($create_directory)) {
            mkdir($create_directory, 0777, true);
        }
        $directory_name = "uploads/" . $session_email . "/section_icon/";
        $extensions = array('.jpg', 'JPG', '.JPG', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG');
        $total = count($_FILES['fileProductUpload']['name']);
        for ($i = 0; $i < $total; $i++) {
            $serfilename = $_FILES['fileProductUpload']['name'][$i];
            $extensionStatus = $validate->validateFileExtension($serfilename, $extensions);
            if ($extensionStatus == false) {
                $error = true;
                $errorMessage = "Please select valid file extension";
            }
            if (($_FILES['fileProductUpload']['size'][$i] >= $maxsize)) {
                $error = true;
                $errorMessage = 'File too large. File must be less than 1 megabytes.';
            }
        }
    } else {
        $error = true;
        $errorMessage = 'Upload at least one image';
    }
    if (!$error) {
        $service_image = "";
        $digitss = 4;
        $randomNum = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        $servicenewfilename = "";
        if ($imgUploadStatus) {
            for ($i = 0; $i < $total; $i++) {
                $filearray = array();
                $sertmpFilePath = $_FILES['fileProductUpload']['tmp_name'][$i];
                $serfile_original_name = substr($_FILES['fileProductUpload']['name'][$i], 0, strrpos($_FILES['fileProductUpload']['name'][$i], '.'));
                $serfile_extension = substr($_FILES['fileProductUpload']['name'][$i], (strrpos($_FILES['fileProductUpload']['name'][$i], '.') + 1));
                $servicenewfilename = $serfile_original_name . "$" . $randomNum . '.' . $serfile_extension;
                $service_image = str_replace([' ', '_'], '-', $servicenewfilename);
                $SernewPath = $directory_name . $service_image;
                if (!move_uploaded_file($sertmpFilePath, $SernewPath)) {
                    $error = true;
                    $errorMessage .= "Failed to upload file";
                }

            }
        }
        if (!$error) {
            if ($checkProduct != "") {
                $DeleteFile = "uploads/" . $session_email . "/section_icon/" . $checkProduct['section_img'];
                unlink($DeleteFile);
                $condition = array('user_id' => $manage->getUserSessionId(), 'section_id' => 11);
                $updateData = array('section_img' => $service_image);
                $status = $manage->update($manage->sectionIconTable, $updateData, $condition);
                if ($status) {
                    $error = false;
                    $errorMessage = "Product Icon added successfully";
                    header("location:manage-section.php");
                } else {
                    $error = true;
                    $errorMessage = "Issue while updating details, Please try again.";
                }
            } else {
                $status = $manage->addServiceIcon(11, $service_image);
                if ($status) {
                    $error = false;
                    $errorMessage = "Product Icon added successfully";
                    header("location:manage-section.php");
                } else {
                    $error = true;
                    $errorMessage = "Issue while adding details, Please try again.";
                }
            }
        }
    }
}

if (isset($_POST['btn_save_image_gellery'])) {
    if (isset($_FILES['upload_gellery']) && $_FILES['upload_gellery']['error'][0] != 4 /* 4 means there is no file selected*/) {
        $imgUploadStatus = true;
        $create_directory = "uploads/" . $session_email . "/section_icon/";
        if (!file_exists($create_directory)) {
            mkdir($create_directory, 0777, true);
        }
        $directory_name = "uploads/" . $session_email . "/section_icon/";
        $extension = array('.jpg', 'JPG', '.JPG', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG');
        $total = count($_FILES['upload_gellery']['name']);
        for ($i = 0; $i < $total; $i++) {
            $filename = $_FILES['upload_gellery']['name'][$i];

            $extensionStatus = $validate->validateFileExtension($filename, $extension);
            if ($extensionStatus == false) {
                $error = true;
                $errorMessage = "Please select valid file extension";
            }
            if (($_FILES['upload_gellery']['size'][$i] >= $maxsize)) {
                $error = true;
                $errorMessage = 'File too large. File must be less than 1 megabytes.';
            }
        }
    } else {
        $error = true;
        $errorMessage = 'Upload at least one image';
    }
    if (!$error) {
        $cover_name = "";
        $digits = 4;
        $randomNum = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        $newfilename = "";
        if ($imgUploadStatus) {
            for ($i = 0; $i < $total; $i++) {
                $filearray = array();
                $tmpFilePath = $_FILES['upload_gellery']['tmp_name'][$i];
                $file_original_name = substr($_FILES['upload_gellery']['name'][$i], 0, strrpos($_FILES['upload_gellery']['name'][$i], '.'));
                $file_extension = substr($_FILES['upload_gellery']['name'][$i], (strrpos($_FILES['upload_gellery']['name'][$i], '.') + 1));
                $newimgname = $file_original_name . "$" . $randomNum . '.' . $file_extension;
                $cover_name = str_replace([' ', '_'], '-', $newimgname);
                $newPath = $directory_name . $cover_name;
                if (!move_uploaded_file($tmpFilePath, $newPath)) {
                    $error = true;
                    $errorMessage .= "Failed to upload file";
                }

            }
        }
        if (!$error) {
            if ($checkGallery != "") {
                $DeleteFile = "uploads/" . $session_email . "/section_icon/" . $checkGallery['section_img'];
                unlink($DeleteFile);
                $condition = array('user_id' => $manage->getUserSessionId(), 'section_id' => 2);
                $updateData = array('section_img' => $cover_name);
                $status = $manage->update($manage->sectionIconTable, $updateData, $condition);
                if ($status) {
                    $error = false;
                    $errorMessage = "Gallery Icon added successfully";
                    header("location:manage-section.php");
                } else {
                    $error = true;
                    $errorMessage = "Issue while updating details, Please try again.";
                }
            } else {
                $status = $manage->addServiceIcon(2, $cover_name);
                if ($status) {
                    $error = false;
                    $errorMessage = "Gallery Icon added successfully";
                    header("location:manage-section.php");
                } else {
                    $error = true;
                    $errorMessage = "Issue while adding details, Please try again.";
                }
            }
        }
    }
}

if (isset($_POST['btn_save_image_client'])) {
    if (isset($_FILES['upload_client']) && $_FILES['upload_client']['error'][0] != 4 /* 4 means there is no file selected*/) {
        $imgUploadStatus = true;
        $create_directory = "uploads/" . $session_email . "/section_icon/";
        if (!file_exists($create_directory)) {
            mkdir($create_directory, 0777, true);
        }
        $directory_name = "uploads/" . $session_email . "/section_icon/";
        $extension = array('.jpg', 'JPG', '.JPG', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG');
        $total = count($_FILES['upload_client']['name']);
        for ($i = 0; $i < $total; $i++) {
            $filename = $_FILES['upload_client']['name'][$i];

            $extensionStatus = $validate->validateFileExtension($filename, $extension);
            /*print_r($extensionStatus);
            exit;*/
            if ($extensionStatus == false) {
                $error = true;
                $errorMessage = "Please select valid file extension";
            }
            if (($_FILES['upload_client']['size'][$i] >= $maxsize)) {
                $error = true;
                $errorMessage = 'File too large. File must be less than 1 megabytes.';
            }
        }
    } else {
        $error = true;
        $errorMessage = 'Upload at least one image';
    }
    if (!$error) {
        $cover_name = "";
        $digits = 4;
        $randomNum = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        $newfilename = "";
        if ($imgUploadStatus) {
            for ($i = 0; $i < $total; $i++) {
                $filearray = array();
                $tmpFilePath = $_FILES['upload_client']['tmp_name'][$i];
                $file_original_name = substr($_FILES['upload_client']['name'][$i], 0, strrpos($_FILES['upload_client']['name'][$i], '.'));
                $file_extension = substr($_FILES['upload_client']['name'][$i], (strrpos($_FILES['upload_client']['name'][$i], '.') + 1));
                $newimgname = $file_original_name . "$" . $randomNum . '.' . $file_extension;
                $cover_name = str_replace([' ', '_'], '-', $newimgname);
                $newPath = $directory_name . $cover_name;
                if (!move_uploaded_file($tmpFilePath, $newPath)) {
                    $error = true;
                    $errorMessage .= "Failed to upload file";
                }

            }
        }
        if (!$error) {
            if ($checkClient != "") {
                $DeleteFile = "uploads/" . $session_email . "/section_icon/" . $checkClient['section_img'];
                unlink($DeleteFile);
                $condition = array('user_id' => $manage->getUserSessionId(), 'section_id' => 4);
                $updateData = array('section_img' => $cover_name);
                $status = $manage->update($manage->sectionIconTable, $updateData, $condition);
                if ($status) {
                    $error = false;
                    $errorMessage = "Client Icon added successfully";
                    header("location:manage-section.php");
                } else {
                    $error = true;
                    $errorMessage = "Issue while update details, Please try again.";
                }
            } else {
                $status = $manage->addServiceIcon(4, $cover_name);
                if ($status) {
                    $error = false;
                    $errorMessage = "Client Icon added successfully";
                    header("location:manage-section.php");
                } else {
                    $error = true;
                    $errorMessage = "Issue while adding details, Please try again.";
                }
            }
        }
    }
}

if (isset($_POST['btn_save_image_team'])) {
    if (isset($_FILES['upload_team']) && $_FILES['upload_team']['error'][0] != 4 /* 4 means there is no file selected*/) {
        $imgUploadStatus = true;
        $create_directory = "uploads/" . $session_email . "/section_icon/";
        if (!file_exists($create_directory)) {
            mkdir($create_directory, 0777, true);
        }
        $directory_name = "uploads/" . $session_email . "/section_icon/";
        $extension = array('.jpg', 'JPG', '.JPG', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG');
        $total = count($_FILES['upload_team']['name']);
        for ($i = 0; $i < $total; $i++) {
            $filename = $_FILES['upload_team']['name'][$i];

            $extensionStatus = $validate->validateFileExtension($filename, $extension);
            /*print_r($extensionStatus);
            exit;*/
            if ($extensionStatus == false) {
                $error = true;
                $errorMessage = "Please select valid file extension";
            }
            if (($_FILES['upload_team']['size'][$i] >= $maxsize)) {
                $error = true;
                $errorMessage = 'File too large. File must be less than 1 megabytes.';
            }
        }
    } else {
        $error = true;
        $errorMessage = 'Upload at least one image';
    }
    if (!$error) {
        $cover_name = "";
        $digits = 4;
        $randomNum = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        $newfilename = "";
        if ($imgUploadStatus) {
            for ($i = 0; $i < $total; $i++) {
                $filearray = array();
                $tmpFilePath = $_FILES['upload_team']['tmp_name'][$i];
                $file_original_name = substr($_FILES['upload_team']['name'][$i], 0, strrpos($_FILES['upload_team']['name'][$i], '.'));
                $file_extension = substr($_FILES['upload_team']['name'][$i], (strrpos($_FILES['upload_team']['name'][$i], '.') + 1));
                $newimgname = $file_original_name . "$" . $randomNum . '.' . $file_extension;
                $cover_name = str_replace([' ', '_'], '-', $newimgname);
                $newPath = $directory_name . $cover_name;
                if (!move_uploaded_file($tmpFilePath, $newPath)) {
                    $error = true;
                    $errorMessage .= "Failed to upload file";
                }
            }
        }
        if (!$error) {
            if ($checkTeam != "") {
                $DeleteFile = "uploads/" . $session_email . "/section_icon/" . $checkTeam['section_img'];
                unlink($DeleteFile);
                $condition = array('user_id' => $manage->getUserSessionId(), 'section_id' => 6);
                $updateData = array('section_img' => $cover_name);
                $status = $manage->update($manage->sectionIconTable, $updateData, $condition);
                if ($status) {
                    $error = false;
                    $errorMessage = "Team Icon added successfully";
                    header("location:manage-section.php");
                } else {
                    $error = true;
                    $errorMessage = "Issue while updating details, Please try again.";
                }
            } else {
                $status = $manage->addServiceIcon(6, $cover_name);
                if ($status) {
                    $error = false;
                    $errorMessage = "Team Icon added successfully";
                    header("location:manage-section.php");
                } else {
                    $error = true;
                    $errorMessage = "Issue while adding details, Please try again.";
                }
            }
        }
    }
}

if (isset($_POST['btn_save_image_bank'])) {
    if (isset($_FILES['upload_bank']) && $_FILES['upload_bank']['error'][0] != 4 /* 4 means there is no file selected*/) {
        $imgUploadStatus = true;
        $create_directory = "uploads/" . $session_email . "/section_icon/";
        if (!file_exists($create_directory)) {
            mkdir($create_directory, 0777, true);
        }
        $directory_name = "uploads/" . $session_email . "/section_icon/";
        $extension = array('.jpg', 'JPG', '.JPG', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG');
        $total = count($_FILES['upload_bank']['name']);

        for ($i = 0; $i < $total; $i++) {
            $filename = $_FILES['upload_bank']['name'][$i];

            $extensionStatus = $validate->validateFileExtension($filename, $extension);

            if ($extensionStatus == false) {
                $error = true;
                $errorMessage = "Please select valid file extension";
            }
            if (($_FILES['upload_bank']['size'][$i] >= $maxsize)) {
                $error = true;
                $errorMessage = 'File too large. File must be less than 1 megabytes.';
            }
        }
    } else {
        $error = true;
        $errorMessage = 'Upload at least one image';
    }
    if (!$error) {
        $cover_name = "";
        $digits = 4;
        $randomNum = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        $newfilename = "";
        if ($imgUploadStatus) {
            for ($i = 0; $i < $total; $i++) {
                $filearray = array();
                $tmpFilePath = $_FILES['upload_bank']['tmp_name'][$i];
                $file_original_name = substr($_FILES['upload_bank']['name'][$i], 0, strrpos($_FILES['upload_bank']['name'][$i], '.'));
                $file_extension = substr($_FILES['upload_bank']['name'][$i], (strrpos($_FILES['upload_bank']['name'][$i], '.') + 1));
                $newimgname = $file_original_name . "$" . $randomNum . '.' . $file_extension;
                $cover_name = str_replace([' ', '_'], '-', $newimgname);
                $newPath = $directory_name . $cover_name;
                if (!move_uploaded_file($tmpFilePath, $newPath)) {
                    $error = true;
                    $errorMessage .= "Failed to upload file";
                }

            }
        }
        if (!$error) {
            if ($checkBank != "") {
                $DeleteFile = "uploads/" . $session_email . "/section_icon/" . $checkBank['section_img'];
                unlink($DeleteFile);
                $condition = array('user_id' => $manage->getUserSessionId(), 'section_id' => 7);
                $updateData = array('section_img' => $cover_name);
                $status = $manage->update($manage->sectionIconTable, $updateData, $condition);
                if ($status) {
                    $error = false;
                    $errorMessage = "Bank Icon added successfully";
                    header("location:manage-section.php");
                } else {
                    $error = true;
                    $errorMessage = "Issue while Updating details, Please try again.";
                }
            } else {
                $status = $manage->addServiceIcon(7, $cover_name);
                if ($status) {
                    $error = false;
                    $errorMessage = "Bank Icon added successfully";
                    header("location:manage-section.php");
                } else {
                    $error = true;
                    $errorMessage = "Issue while adding details, Please try again.";
                }
            }
        }
    }
}

?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/html">
<head runat="server">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php include "assets/common-includes/total_count.php" ?>
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <title>Manage Section</title>
    <style>
        input[type="file"] {
            margin-top: 5px;
            display: block !important;
        }

        .toggle {
            margin-left: 1px;
            width: 48px !important;
            height: 22px !important;
            padding: 0;
        }

        .toggle-on.btn {
            padding: 0;
            float: right;
        }

        .toggle-off.btn {
            padding: 0;
            float: right;
        }

        .btn:not(.btn-link):not(.btn-circle) span {
            position: relative;
            top: 0 !important;
            margin-left: 0 !important;
        }

        .toggle.btn {
            line-height: 10px;
            min-width: 48px !important;
            min-height: 22px !important;
            padding: 6px 10px;
        }

        .list-group-item {
            display: inline-flex !important;
            flex-direction: row;
            flex-wrap: nowrap;
            justify-content: space-between;
            align-items: center;
            align-content: center;
            width: 100%;
        }

        #profile-message {
            display: none;
        }

        #profile-message-validate {
            display: none;
        }

        #service-message {
            display: none;
        }

        #service-message-validate {
            display: none;
        }

        #product-message {
            display: none;
        }

        #product-message-validate {
            display: none;
        }

        #gallery-message {
            display: none;
        }

        #gallery-message-validate {
            display: none;
        }

        #client-message {
            display: none;
        }

        #client-message-validate {
            display: none;
        }

        #team-message {
            display: none;
        }

        #team-message-validate {
            display: none;
        }

        #bank-message {
            display: none;
        }

        #bank-message-validate {
            display: none;
        }

        #navbar-message {
            display: none;
        }

        #navbar-message-validate {
            display: none;
        }

        .form-label {
            color: #666;
        }

        .fieldset-class {
            padding: 8px 20px;
            width: 90%;
        }

        .fieldset-legend {
            font-size: 14px;
            color: #000
        }
    </style>
    <?php include "assets/common-includes/header_includes.php" ?>
    <style>
        .d-block-imp {
            display: block !important;
        }
    </style>
</head>
<body>
<?php
if (!isset($_GET['token']) && (!isset($_GET['type']))) {
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
    <?php
    }elseif (isset($_GET['token']) && (isset($_GET['type']) && $_GET['type'] == "android")) {
    ?>
    <section class="androidSection">
        <?php
        }
        ?>

        <?php
        if (!isset($_GET['token']) && !isset($_GET['type'])) {
        ?>
        <div class="clearfix">

            <div class="update_menu_heading">
                <h2>UPDATE MENU BAR</h2>
            </div>
            <?php
            } else {
                echo '   <div class="row">';
            }
            ?>
            <div class="col-lg-7 col-md-7 col-sm-8 col-xs-12 bhoechie-tab-container">
                <div class="col-lg-4 col-md-3 col-sm-4 col-xs-3 bhoechie-tab-menu">
                    <div class="list-group menu_bar_img">
                        <a href="#" class="list-group-item active">
                            <?php
                            if ($getServiceIconProfile != "") {
                                $pathImg = "uploads/" . $session_email . "/section_icon/" . $getServiceIconProfile["section_img"] . " ";
                                ?>
                                <img src="<?php echo $pathImg; ?>" onError="this.onerror=null;this.src='assets/images/user1.png';" />
<!--                                <img src="--><?php //echo $pathImg; ?><!--">-->
                            <?php
                            } else {
                                ?>
                                <img src="assets/images/user1.png">
                            <?php
                            }
                            ?>
                            <span class="list_group_title">
                        <?php echo $profile; ?></span>
                            <!--<input type="checkbox" data-toggle="toggle" id="profile_toggle" value="0" >-->
                            <!--<input id="VistaToggle" type="checkbox" checked data-toggle="toggle" data-size=="small" data-on="ON" data-off="OFF"  data-onstyle="success" data-offstyle="danger" value="1">-->
                            <input id="VistaToggle" type="checkbox" data-toggle="toggle"
                                   value="1" <?php if ($ProfileSectionStatus) echo 'checked'; ?> >

                        </a>
                        <a href="#" class="list-group-item">
                            <?php
                            if ($getServiceIconService != "") {
                                $pathImg = "uploads/" . $session_email . "/section_icon/" . $getServiceIconService["section_img"] . " ";
                                ?>
                                <img src="<?php echo $pathImg; ?>" onError="this.onerror=null;this.src='assets/images/clipboard.png';" />
                            <?php
                            } else {
                                ?>
                                <img src="assets/images/clipboard.png">
                            <?php
                            }
                            ?>

                            <span class="list_group_title"><?php echo $services; ?></span>
                            <input type="checkbox" id="service_toggle" data-toggle="toggle" value="1"
                                <?php if ($ServiceSectionStatus) echo 'checked'; ?> >
                        </a>
                        <a href="#" class="list-group-item">
                            <?php
                            if ($getProductIconService != "") {
                                $pathImg = "uploads/" . $session_email . "/section_icon/" . $getProductIconService["section_img"] . " ";
                                ?>
                                <img src="<?php echo $pathImg; ?>" onError="this.onerror=null;this.src='assets/images/clipboard.png';" />
                            <?php
                            } else {
                                ?>
                                <img src="assets/images/clipboard.png">
                            <?php
                            }
                            ?>

                            <span class="list_group_title"><?php echo $our_product; ?></span>
                            <input type="checkbox" id="product_toggle"
                                   data-toggle="toggle" <?php if ($ProductSectionStatus) echo 'checked'; ?>
                                   value="1">
                        </a>
                        <a href="#" class="list-group-item">
                            <?php
                            if ($getServiceIconGallery != "") {
                                $pathImg = "uploads/" . $session_email . "/section_icon/" . $getServiceIconGallery["section_img"] . " ";
                                ?>
                                <img src="<?php echo $pathImg; ?>" onError="this.onerror=null;this.src='assets/images/gallery.png';" />
                            <?php
                            } else {
                                ?>
                                <img src="assets/images/gallery.png">
                            <?php
                            }
                            ?>
                            <span class="list_group_title">

                        <?php echo $gallery; ?></span>
                            <input type="checkbox" id="gellery_toggle"
                                   data-toggle="toggle" <?php if ($gallerySectionStatus) echo 'checked'; ?>
                                   value="1">
                        </a>
                        <a href="#" class="list-group-item">
                            <?php
                            if ($getServiceIconClient != "") {
                                $pathImg = "uploads/" . $session_email . "/section_icon/" . $getServiceIconClient["section_img"] . " ";
                                ?>
                                <img src="<?php echo $pathImg; ?>" onError="this.onerror=null;this.src='assets/images/review.png';" />
                            <?php
                            } else {
                                ?>
                                <img src="assets/images/review.png">
                            <?php
                            }
                            ?>
                            <span class="list_group_title"><?php echo $clients ?></span>

                            <input type="checkbox" id="client_toggle"
                                   data-toggle="toggle" <?php if ($ClientSectionStatus) echo 'checked'; ?>
                                   value="1">
                        </a>
                        <a href="#" class="list-group-item">
                            <?php
                            if ($getServiceIconTeam != "") {
                                $pathImg = "uploads/" . $session_email . "/section_icon/" . $getServiceIconTeam["section_img"] . " ";
                                ?>
                                <img src="<?php echo $pathImg; ?>" onError="this.onerror=null;this.src='assets/images/teamwork.png';" />
                            <?php
                            } else {
                                ?>
                                <img src="assets/images/teamwork.png">
                            <?php
                            }
                            ?>
                            <span class="list_group_title">
                        <?php echo $team; ?></span>
                            <input type="checkbox" id="team_toggle"
                                   data-toggle="toggle" <?php if ($TeamSectionStatus) echo 'checked'; ?>
                                   value="1">
                        </a>
                        <a href="#" class="list-group-item">


                            <?php
                            if ($getServiceIconBank != "") {
                                $pathImg = "uploads/" . $session_email . "/section_icon/" . $getServiceIconBank["section_img"] . " ";
                                ?>
                                <img src="<?php echo $pathImg; ?>" onError="this.onerror=null;this.src='assets/images/point-of-service.png';" />
                            <?php

                            } else {
                                ?>
                                <img src="assets/images/point-of-service.png">
                            <?php
                            }
                            ?>

                            <span class="list_group_title">
                        <?php echo $bank; ?></span>
                            <input type="checkbox" id="bank_toggle"
                                   data-toggle="toggle" <?php if ($BankSectionStatus) echo 'checked'; ?>
                                   value="1">
                        </a>
                    </div>
                </div>
                <form id="section_form" method="post" action="" enctype="multipart/form-data">

                    <div class="col-lg-8 col-md-8 col-sm-9 col-xs-9 bhoechie-tab">
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
                        <!-- flight section -->
                        <div class="bhoechie-tab-content active">
                            <div id="profile-message" class="alert alert-success">
                            </div>
                            <div id="profile-message-validate" class="alert alert-danger">
                            </div>
                            <div class="col-md-12 mb-20">
                                <div class="row">
                                    <fieldset class="fieldset-class">
                                        <legend class="fieldset-legend">Menu Name</legend>
                                        <div class="">
                                            <input name="txt_name" id="txt_profile" class="form-control"
                                                   value="<?php if (isset($profile)) echo $profile; ?>">
                                        </div>
                                        <div class="" style="margin-top: 10px">
                                            <button class="btn btn-primary d-block-imp" type="button"
                                                    onclick="updateProfile()"> Save
                                            </button>
                                        </div>
                                    </fieldset>
                                    <br>
                                    <fieldset class="fieldset-class">
                                        <legend class="fieldset-legend">Upload Icon</legend>
                                        <div class="">
                                            <input class="form-control" type="file" name="upload[]" id="fileupload"
                                                   accept=".png, .jpg, .jpeg,.JPG,.PNG,.JPEG">
                                        </div>
                                        <div class="" style="margin-top: 10px">
                                            <button type="submit" name="btn_save_image"
                                                    class="btn btn-primary d-block-imp"> Save
                                            </button>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="col-md-12 mb-10">
                                <div class="row">
                                    <div id="navbar-message" class="alert alert-success">
                                    </div>
                                    <div id="navbar-message-validate" class="alert alert-danger">
                                    </div>

                                    <fieldset class="fieldset-class">
                                        <legend class="fieldset-legend">Nav Bar Name</legend>
                                        <div class="mb-10">
                                            <input name="txt_name" id="txt_basic" class="form-control"
                                                   placeholder="basic info"
                                                   value="<?php if (isset($basic_info)) echo $basic_info; ?>">
                                        </div>
                                        <div class="">
                                            <input name="txt_name" id="txt_company" class="form-control"
                                                   placeholder="company name"
                                                   value="<?php if (isset($company_info)) echo $company_info; ?>">
                                        </div>
                                        <div class="mt-10">
                                            <button class="btn btn-primary " type="button" onclick="updateNavBar()">
                                                Save
                                            </button>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            <!--  <div class="col-md-6">
                                  <img src="assets/images/menu_profile.JPG">
                              </div>-->
                        </div>
                        <!-- train section -->
                        <div class="bhoechie-tab-content">
                            <div id="service-message" class="alert alert-success">
                            </div>
                            <div id="service-message-validate" class="alert alert-danger">
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <fieldset class="fieldset-class">
                                        <legend class="fieldset-legend">Menu Name</legend>

                                        <div class="menu_input_margin">
                                            <input name="txt_name" id="service_name"
                                                   class="form-control mb-10"
                                                   value="<?php if (isset($services)) echo $services; ?>">
                                            <label class="form-label">Header Name (Service)</label>
                                            <input name="txt_name" id="service_header"
                                                   class="form-control"
                                                   value="<?php if (isset($our_service)) echo $our_service; ?>">

                                            <div class="mt-10   ">
                                                <button class="btn btn-primary" type="button" onclick="updateService()">
                                                    Save
                                                </button>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <br>
                                    <fieldset class="fieldset-class">
                                        <legend class="fieldset-legend">Upload icon</legend>
                                        <input class="form-control" type="file" name="fileUpload[]" id="fileupload"
                                               accept=".png, .jpg, .jpeg,.JPG,.PNG,.JPEG">

                                        <div class="mt-10" style="">
                                            <button name="btn_save_image_service" class="btn btn-primary"
                                                    type="submit"> Save
                                            </button>
                                        </div>
                                    </fieldset>
                                    <!--<div class=""><br>
                                        <label class="form-label">Upload Icon</label><br>
                                        <input type="file" name="fileUpload[]" class="custom-file-input" id="chooseFile" accept=".png, .jpg, .jpeg,.JPG,.PNG,.JPEG">

                                        <label for="file-7">
                                            <span></span>
                                           <img class="input_choose_file blah" src="" alt="">
                                            <strong class="input_choose_file">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="17"
                                                     viewBox="0 0 20 17">
                                                    <path
                                                        d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path>
                                                </svg>
                                                Choose a file…
                                            </strong>
                                        </label>
                                    </div>-->
                                </div>

                            </div>
                            <!--<div class="col-md-6">

                            </div>-->


                        </div>


                        <div class="bhoechie-tab-content">
                            <div id="product-message" class="alert alert-success">
                            </div>
                            <div id="product-message-validate" class="alert alert-danger">
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <fieldset class="fieldset-class">
                                        <legend class="fieldset-legend">Menu Name</legend>
                                        <div class="menu_input_margin">
                                            <input name="txt_name" id="product_name"
                                                   class="form-control"
                                                   value="<?php if (isset($product)) echo $product; ?>">
                                        </div>
                                        <label class="form-label">Header Name (Product)</label>

                                        <input name="txt_name" id="product_header"
                                               class="form-control"
                                               value="<?php if (isset($our_product)) echo $our_product; ?>">

                                        <div class="mt-10">
                                            <button class="btn btn-primary" type="button" onclick="updateProduct()">Save
                                            </button>
                                        </div>
                                    </fieldset>
                                    <br>
                                    <fieldset class="fieldset-class">
                                        <legend class="fieldset-legend">Upload icon</legend>
                                        <input class="form-control" type="file" name="fileProductUpload[]"
                                               id="fileupload"
                                               accept=".png, .jpg, .jpeg,.JPG,.PNG,.JPEG">

                                        <div class="mt-10">
                                            <button name="btn_save_image_product" class="btn btn-primary"
                                                    type="submit">
                                                Save
                                            </button>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                        <!-- hotel search -->
                        <div class="bhoechie-tab-content">
                            <div id="gallery-message" class="alert alert-success">
                            </div>
                            <div id="gallery-message-validate" class="alert alert-danger">
                            </div>
                            <div class="col-md-12">
                                <div class="row">

                                    <fieldset class="fieldset-class">
                                        <legend class="fieldset-legend">Menu Name</legend>
                                        <div class="menu_input_margin">
                                            <input name="txt_name" id="txt_gallery" class="form-control"
                                                   value="<?php if (isset($gallery)) echo $gallery; ?>">
                                        </div>
                                        <label class="form-label">Header Name (images)</label>

                                        <div class="menu_input_margin">
                                            <input name="txt_name" id="txt_images" class="form-control"
                                                   value="<?php if (isset($images)) echo $images; ?>">
                                        </div>

                                        <label class="form-label">Header Name (videos)</label>
                                        <input name="txt_name" id="videos" class="form-control"
                                               value="<?php if (isset($videos)) echo $videos; ?>">

                                        <div class="mt-10">
                                            <button class="btn btn-primary" type="button" onclick="updateGallery()">Save
                                            </button>
                                        </div>
                                    </fieldset>
                                    <br>
                                    <fieldset class="fieldset-class mb-20">
                                        <legend class="fieldset-legend">Upload icon</legend>
                                        <input class="form-control" type="file" name="upload_gellery[]"
                                               id="fileupload"
                                               accept=".png, .jpg, .jpeg,.JPG,.PNG,.JPEG">

                                        <div class="mt-10">
                                            <button name="btn_save_image_gellery" class="btn btn-primary"
                                                    type="submit">
                                                Save
                                            </button>
                                        </div>
                                    </fieldset>
                                </div>

                            </div>
                        </div>
                        <div class="bhoechie-tab-content">
                            <div id="client-message" class="alert alert-success">
                            </div>
                            <div id="client-message-validate" class="alert alert-danger">
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <fieldset class="fieldset-class mb-20">
                                        <legend class="fieldset-legend">Menu Name</legend>
                                        <div class="menu_input_margin">
                                            <input name="txt_name" id="txt_clients" class="form-control"
                                                   value="<?php if (isset($clients)) echo $clients; ?>">
                                        </div>

                                        <label class="form-label">Header Name (Client)</label>

                                        <div class="menu_input_margin">
                                            <input name="txt_name" id="client_name" class="form-control"
                                                   value="<?php if (isset($client_name)) echo $client_name; ?>">
                                        </div>
                                        <label class="form-label">Header Name (Client's review)</label>
                                        <input name="txt_name" id="client_review"
                                               class="form-control"
                                               value="<?php if (isset($client_review_tab)) echo $client_review_tab; ?>">

                                        <div class="mt-10">
                                            <button class="btn btn-primary" onclick="updateClients()" type="button">Save
                                            </button>
                                        </div>
                                    </fieldset>
                                    <fieldset class="fieldset-class mb-20">
                                        <legend class="fieldset-legend">Upload icon</legend>
                                        <input class="form-control" type="file" name="upload_client[]"
                                               id="fileupload"
                                               accept=".png, .jpg, .jpeg,.JPG,.PNG,.JPEG">

                                        <div class="mt-10">
                                            <button name="btn_save_image_client" class="btn btn-primary"
                                                    type="submit">
                                                Save
                                            </button>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                        <div class="bhoechie-tab-content">
                            <div id="team-message" class="alert alert-success">
                            </div>
                            <div id="team-message-validate" class="alert alert-danger">
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <fieldset class="fieldset-class mb-20">
                                        <legend class="fieldset-legend">Menu Name</legend>
                                        <div class="menu_input_margin">
                                            <input name="txt_name" id="team" class="form-control"
                                                   value="<?php if (isset($team)) echo $team; ?>">
                                        </div>
                                        <label class="form-label">Header Name (Our Team)</label>
                                        <input name="txt_name" id="our_team" class="form-control"
                                               value="<?php if (isset($our_team)) echo $our_team; ?>">

                                        <div class="mt-10">
                                            <button class="btn btn-primary" onclick="updateTeam()" type="button">Save
                                            </button>
                                        </div>
                                    </fieldset>

                                    <fieldset class="fieldset-class mb-20">
                                        <legend class="fieldset-legend">Upload icon</legend>
                                        <label class="form-label" style="margin-top: 5px">Upload icon</label>
                                        <input class="form-control" type="file" name="upload_team[]"
                                               id="fileupload"
                                               accept=".png, .jpg, .jpeg,.JPG,.PNG,.JPEG">

                                        <div class="mt-10">
                                            <button name="btn_save_image_team" class="btn btn-primary"
                                                    type="submit"> Save
                                            </button>
                                    </fieldset
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bhoechie-tab-content">
                        <div id="bank-message" class="alert alert-success">
                        </div>
                        <div id="bank-message-validate" class="alert alert-danger">
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <fieldset class="fieldset-class mb-20">
                                    <legend class="fieldset-legend">Menu Name</legend>

                                    <div class="menu_input_margin">
                                        <input name="txt_name" id="bank" class="form-control"
                                               value="<?php if (isset($bank)) echo $bank; ?>">
                                    </div>
                                    <label class="form-label">Header Name (Payment)</label>
                                    <input name="txt_name" id="payment" class="form-control"
                                           value="<?php if (isset($payment)) echo $payment; ?>">

                                    <div class="mt-10">
                                        <button class="btn btn-primary" onclick="updateBank()" type="button">Save
                                        </button>
                                    </div>
                                </fieldset>
                                <fieldset class="fieldset-class mb-20">
                                    <legend class="fieldset-legend">Upload icon</legend>
                                    <label class="form-label" style="margin-top: 5px">Upload icon</label>
                                    <input class="form-control" type="file" name="upload_bank[]"
                                           id="fileupload"
                                           accept=".png, .jpg, .jpeg,.JPG,.PNG,.JPEG">

                                    <div class="mt-10" style="">
                                        <button name="btn_save_image_bank" class="btn btn-primary"
                                                type="submit">
                                            Save
                                        </button>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        <!--<div class="col-md-6">

                        </div>-->
                    </div>

            </div>
            </form>
        </div>
        <div class="col-md-5 hidden-sm hidden-xs">
            <img src="assets/images/menu.png" style="width: 100%">
        </div>
        </div>
    </section>
    <?php include "assets/common-includes/footer_includes.php" ?>
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script>
        $(document).ready(function () {
            $("div.bhoechie-tab-menu>div.list-group>a").click(function (e) {
                e.preventDefault();
                $(this).siblings('a.active').removeClass("active");
                $(this).addClass("active");
                var index = $(this).index();
                $("div.bhoechie-tab>div.bhoechie-tab-content").removeClass("active");
                $("div.bhoechie-tab>div.bhoechie-tab-content").eq(index).addClass("active");
            });
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function () {
            $(".list-group,.bhoechie-tab").sortable({
                placeholder: "ui-state-highlight",
                update: function (event, ui) {
                    var page_id_array = new Array();
                    $('.list-group a.list-group-item').each(function () {
                        page_id_array.push($(this).attr("id"));
                    });
                }
            });

        });
    </script>

    <script>
        function updateProfile() {
            var profile = $('#txt_profile').val();
            if (profile.trim() == '') {
                $("#profile-message-validate").css({"display": "block"});
                $("#profile-message-validate").html('please enter some value');
            }
            else if (profile.length >= 15) {
                $("#profile-message-validate").css({"display": "block"});
                $("#profile-message-validate").html('length should be less than 10');
            }
            else {
                var dataString = "change_profile=" + encodeURIComponent(profile);
                $.ajax({
                    type: "POST",
                    url: "manage_section_ajax.php",
                    data: dataString,
                    success: function (html) {
                        $("#profile-message-validate").css({"display": "none"});
                        $("#profile-message").css({"display": "block"});
                        $("#profile-message").html(html);
                        $(".list-group a.active .list_group_title").html(profile);

                    }
                });
            }

        }
    </script>
    <script>
        function updateService() {
            var service_name = $('#service_name').val();
            var service_header = $('#service_header').val();
            if (service_name.trim() == '' || service_header.trim() == '') {
                $("#service-message-validate").css({"display": "block"});
                $("#service-message-validate").html('please enter some value');
            } else if (service_name.length >= 15) {
                $("#service-message-validate").css({"display": "block"});
                $("#service-message-validate").html('service name length should be 10 or less than');
            } else if (service_header.length >= 21) {
                $("#service-message-validate").css({"display": "block"});
                $("#service-message-validate").html('header length should be less than 20 or less than');
            } else {
                var dataString = "change_service=" + encodeURIComponent(service_name) + "&service_header=" + encodeURIComponent(service_header);
                $.ajax({
                    type: "POST",
                    url: "manage_section_ajax.php",
                    data: dataString,
                    success: function (html) {
                        $("#service-message-validate").css({"display": "none"});
                        $("#service-message").css({"display": "block"});
                        $("#service-message").html(html);
                        $(".list-group a.active .list_group_title").html(service_name);
                    }
                });
            }

        }


        function updateProduct() {
            var product_name = $('#product_name').val();
            var product_header = $('#product_header').val();
            if (product_name.trim() == '' || product_header.trim() == '') {
                $("#product-message-validate").css({"display": "block"});
                $("#product-message-validate").html('please enter some value');
            } else if (product_name.length >= 15) {
                $("#product-message-validate").css({"display": "block"});
                $("#product-message-validate").html('product name length should be 10 or less than');
            } else if (product_header.length >= 21) {
                $("#product-message-validate").css({"display": "block"});
                $("#product-message-validate").html('header length should be less than 20 or less than');
            } else {
                var dataString = "change_product=" + encodeURIComponent(product_name) + "&product_header=" + encodeURIComponent(product_header);
                $.ajax({
                    type: "POST",
                    url: "manage_section_ajax.php",
                    data: dataString,
                    success: function (html) {
                        $("#product-message-validate").css({"display": "none"});
                        $("#product-message").css({"display": "block"});
                        $("#product-message").html(html);
                        $(".list-group a.active .list_group_title").html(product_name);
                    }
                });
            }

        }
    </script>
    <script>
        function updateGallery() {
            var gallery = $('#txt_gallery').val();
            var images = $('#txt_images').val();
            var videos = $('#videos').val();
            if (gallery.trim() == '' || images.trim() == '' || videos.trim() == '') {
                $("#gallery-message-validate").css({"display": "block"});
                $("#gallery-message-validate").html('please enter some value');
            } else if (gallery.length >= 15) {
                $("#gallery-message-validate").css({"display": "block"});
                $("#gallery-message-validate").html('gallery length should be less than 10');
            } else if (images.length >= 21 || videos.length >= 21) {
                $("#gallery-message-validate").css({"display": "block"});
                $("#gallery-message-validate").html('Image and Video Length should be less than 20');
            } else {
                var dataString = "change_gallery=" + encodeURIComponent(gallery) + "&images=" + encodeURIComponent(images) + "&videos=" + encodeURIComponent(videos);
                $.ajax({
                    type: "POST",
                    url: "manage_section_ajax.php",
                    data: dataString,
                    success: function (html) {
                        $("#gallery-message-validate").css({"display": "none"});
                        $("#gallery-message").css({"display": "block"});
                        $("#gallery-message").html(html);
                        $(".list-group a.active .list_group_title").html(gallery);
                    }
                });
            }
        }
    </script>
    <script>
        function updateClients() {
            var clients = $('#txt_clients').val();
            var client_name = $('#client_name').val();
            var client_review = $('#client_review').val();

            if (clients.trim() == '' || client_name.trim() == '' || client_review.trim() == '') {
                $("#client-message-validate").css({"display": "block"});
                $("#client-message-validate").html('please enter some value');
            } else if (clients.length >= 15) {
                $("#client-message-validate").css({"display": "block"});
                $("#client-message-validate").html('Client length should be 10 or less than');
            } else if (client_name.length >= 21 || client_review.length >= 21) {
                $("#client-message-validate").css({"display": "block"});
                $("#client-message-validate").html('client name and client review length should be 20 or less than');
            } else {
                var dataString = "change_clients=" + encodeURIComponent(clients) + "&client_name=" + encodeURIComponent(client_name) + "&client_review=" + encodeURIComponent(client_review);
                $.ajax({
                    type: "POST",
                    url: "manage_section_ajax.php",
                    data: dataString,
                    success: function (html) {
                        $("#client-message-validate").css({"display": "none"});
                        $("#client-message").css({"display": "block"});
                        $("#client-message").html(html);
                        $(".list-group a.active .list_group_title").html(clients);
                    }
                });
            }


        }
    </script>
    <script>
        function updateTeam() {
            var team = $('#team').val();
            var our_team = $('#our_team').val();

            if (team.trim() == '' || our_team.trim() == '') {
                $("#team-message-validate").css({"display": "block"});
                $("#team-message-validate").html('Enter some value');
            } else if (team.length >= 15) {
                $("#team-message-validate").css({"display": "block"});
                $("#team-message-validate").html('team length should be 10 or less than 10');
            } else if (our_team.length >= 21) {
                $("#team-message-validate").css({"display": "block"});
                $("#team-message-validate").html('our team length should be less than 20 ');
            } else {
                var dataString = "change_team=" + encodeURIComponent(team) + "&our_team=" + encodeURIComponent(our_team);
                $.ajax({
                    type: "POST",
                    url: "manage_section_ajax.php",
                    data: dataString,
                    success: function (html) {
                        $("#team-message-validate").css({"display": "none"});
                        $("#team-message").css({"display": "block"});
                        $("#team-message").html(html);
                        $(".list-group a.active .list_group_title").html(team);

                    }
                });
            }


        }
    </script>
    <script>
        function updateBank() {
            var bank = $('#bank').val();
            var payment = $('#payment').val();

            if (bank.trim() == '' || payment.trim() == '') {
                $("#bank-message-validate").css({"display": "block"});
                $("#bank-message-validate").html('Enter some value');
            } else if (bank.length >= 15) {
                $("#bank-message-validate").css({"display": "block"});
                $("#bank-message-validate").html('bank length should be less than 10');
            } else if (payment.length >= 21) {
                $("#bank-message-validate").css({"display": "block"});
                $("#bank-message-validate").html('payment length should be or less than 20');
            } else {
                var dataString = "change_bank=" + encodeURIComponent(bank) + "&payment=" + encodeURIComponent(payment);
                $.ajax({
                    type: "POST",
                    url: "manage_section_ajax.php",
                    data: dataString,
                    success: function (html) {
                        $("#bank-message-validate").css({"display": "none"});
                        $("#bank-message").css({"display": "block"});
                        $("#bank-message").html(html);
                        $(".list-group a.active .list_group_title").html(bank);

                    }
                });
            }
        }
    </script>
    <script>
        function updateNavBar() {
            var basic_info = $('#txt_basic').val();
            var company_info = $('#txt_company').val();

            if (basic_info.trim() == '' || company_info.trim() == '') {
                $("#navbar-message-validate").css({"display": "block"});
                $("#navbar-message-validate").html('Enter some value ');
            } else if (basic_info.length >= 15) {
                $("#navbar-message-validate").css({"display": "block"});
                $("#navbar-message-validate").html('bank length should be less than 10');
            } else if (company_info.length >= 20) {
                $("#navbar-message-validate").css({"display": "block"});
                $("#navbar-message-validate").html('payment length should be or less than 20');
            } else {
                var dataString = "change_basic_info=" + encodeURIComponent(basic_info) + "&company_info=" + encodeURIComponent(company_info);
                $.ajax({
                    type: "POST",
                    url: "manage_section_ajax.php",
                    data: dataString,
                    success: function (html) {
                        $("#navbar-message-validate").css({"display": "none"});
                        $("#navbar-message").css({"display": "block"});
                        $("#navbar-message").html(html);

                    }
                });
            }
        }
    </script>
    <script>
        $("document").ready(function () {
            $('#VistaToggle').change(function () {
                var mode = $(this).prop('checked');
                var id = $(this).val();
                if (mode == false) {
                    var profile_toggle = 0;
                } else {
                    var profile_toggle = 1;
                }
                $.ajax({
                    type: 'POST',
                    url: 'manage_section_ajax.php',
                    data: {profile_toggle: profile_toggle, mode: mode},
                    success: function (data) {
                        $("#profile-message-validate").css({"display": "none"});
                        $("#profile-message").css({"display": "block"});
//                        $("#profile-message").html('profile status updated successfully');
                        $("#profile-message").html(data);
                    }
                });
            });

            $('#service_toggle').change(function () {
                var mode = $(this).prop('checked');
                var id = $(this).val();
                if (mode != true) {
                    var service_toggle = 0;
                } else {
                    var service_toggle = 1;
                }
                $.ajax({
                    type: 'POST',
                    url: 'manage_section_ajax.php',
                    data: {service_toggle: service_toggle, mode: mode},
                    success: function (data) {
                        $("#service-message-validate").css({"display": "none"});
                        $("#service-message").css({"display": "block"});
                        $("#service-message").html(data);
                    }
                });
            });

            $('#product_toggle').change(function () {
                var mode = $(this).prop('checked');
                var id = $(this).val();
                if (mode != true) {
                    var product_toggle = 0;
                } else {
                    var product_toggle = 1;
                }
                $.ajax({
                    type: 'POST',
                    url: 'manage_section_ajax.php',
                    data: {product_toggle: product_toggle, mode: mode},
                    success: function (data) {
                        $("#product-message-validate").css({"display": "none"});
                        $("#product-message").css({"display": "block"});
                        $("#product-message").html(data);
                    }
                });
            });

            $('#gellery_toggle').change(function () {
                var mode = $(this).prop('checked');
                var id = $(this).val();
                if (mode != true) {
                    var gellery_toggle = 0;
                } else {
                    var gellery_toggle = 1;
                }
                $.ajax({
                    type: 'POST',
                    url: 'manage_section_ajax.php',
                    data: {gellery_toggle: gellery_toggle, mode: mode},
                    success: function (data) {
                        $("#gallery-message-validate").css({"display": "none"});
                        $("#gallery-message").css({"display": "block"});
                        $("#gallery-message").html(data);
                    }
                });
            });

            $('#client_toggle').change(function () {
                var mode = $(this).prop('checked');
                var id = $(this).val();
                if (mode != true) {
                    var client_toggle = 0;
                } else {
                    var client_toggle = 1;
                }
                $.ajax({
                    type: 'POST',
                    url: 'manage_section_ajax.php',
                    data: {client_toggle: client_toggle, mode: mode},
                    success: function (data) {
                        $("#client-message-validate").css({"display": "none"});
                        $("#client-message").css({"display": "block"});
                        $("#client-message").html(data);
                    }
                });
            });

            $('#team_toggle').change(function () {
                var mode = $(this).prop('checked');
                var id = $(this).val();
                if (mode != true) {
                    var team_toggle = 0;
                } else {
                    var team_toggle = 1;
                }
                $.ajax({
                    type: 'POST',
                    url: 'manage_section_ajax.php',
                    data: {team_toggle: team_toggle, mode: mode},
                    success: function (data) {
                        $("#team-message-validate").css({"display": "none"});
                        $("#team-message").css({"display": "block"});
                        $("#team-message").html(data);
                    }
                });
            });

            $('#bank_toggle').change(function () {
                var mode = $(this).prop('checked');
                var id = $(this).val();
                if (mode != true) {
                    var bank_toggle = 0;
                } else {
                    var bank_toggle = 1;
                }
                $.ajax({
                    type: 'POST',
                    url: 'manage_section_ajax.php',
                    data: {bank_toggle: bank_toggle, mode: mode},
                    success: function (data) {
                        $("#bank-message-validate").css({"display": "none"});
                        $("#bank-message").css({"display": "block"});
                        $("#bank-message").html(data);
                    }
                });
            });
        });

    </script>
</body>
</html>