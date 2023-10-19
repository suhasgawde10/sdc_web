<?php
error_reporting(1);

ob_start();
ini_set('memory_limit', '-1');
include "../controller/ManageUser.php";
$manage = new ManageUser();
include "../controller/validator.php";
$validate = new Validator();
include '../sendMail/sendMail.php';
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
$controller = new Controller();
$con = $controller->connect();


$alreadySaved = false;
$alreadySavedUpi = false;
$section_id = 1;


include("android-login.php");
$maxsize = 4194304;
// $maxsize = 1048576;
include_once('lib/ImgCompressor.class.php');
$error = false;
$errorMessage = "";
$error1 = false;
$errorMessage1 = "";
include("session_includes.php");
include "validate-page.php";
$imgUploadStatus = false;
/*This method used for update the Branch data*/
$active_tab = false;
if (isset($_GET['product']) && $_GET['product'] == "true") {
    $active_tab = true;
}

if (isset($_POST['btn_save_upi'])) {
    if (isset($_POST['txt_upi_id']) && $_POST['txt_upi_id'] != "") {
        $txt_upi_id = $_POST['txt_upi_id'];
    } else {
        $error = true;
        $errorMessage .= "Please enter upi id.<br>";
    }
    if (isset($_POST['txt_upi_number']) && $_POST['txt_upi_number'] != "") {
        $txt_upi_number = $_POST['txt_upi_number'];
    } else {
        $error = true;
        $errorMessage .= "Please enter upi number.<br>";
    }
    if (!$error) {
        $status = $manage->addGatewayDetails($_POST['txt_upi_id'], $txt_upi_number);
        if ($status) {
            $page_name = $_SESSION['menu']['s_bank'];
            $action = "Add";

            $remark = "UPI Id has been added " . $by;
            $insertLog = $manage->insertUserLogData($page_name, $action, $remark);
            $_SESSION['red_dot']['upi_id'] = false;
            $_SESSION['total_percent'] = $_SESSION['total_percent'] + 10;
            $error = false;
            $errorMessage .= "Upi Added Successfully.";
        } else {
            $error = true;
            $errorMessage = "Issue while adding details, Please try again.";
        }


    }

}


if ($id != 0) {
    $get_data = $manage->countGateway($id);
    if ($get_data) {
        $alreadySavedUpi = true;
        $form_data = $manage->getGatewayPaymentDetails($id);
        $upi_id = $form_data['upi_id'];
        $upi_mobile_no = $form_data['upi_mobile_no'];

    }
}
if (isset($_POST['btn_update_upi'])) {
    if (isset($_POST['txt_upi_id']) && $_POST['txt_upi_id'] != "") {
        $txt_upi_id = $_POST['txt_upi_id'];
    } else {
        $error = true;
        $errorMessage .= "Please enter upi id.<br>";
    }
    if (isset($_POST['txt_upi_number']) && $_POST['txt_upi_number'] != "") {
        $txt_upi_number = $_POST['txt_upi_number'];
    } else {
        $error = true;
        $errorMessage .= "Please enter upi number.<br>";
    }
    if (!$error) {
        $status = $manage->updatePaymentGateway($_POST['txt_upi_id'], $txt_upi_number, $id);
        if ($status) {
            $page_name = $_SESSION['menu']['s_bank'];
            $action = "Updated";
            $upi_message_data = "<br>";
            if ($txt_upi_id != $upi_id) {
                $upi_message_data .= $upi_id . " TO " . $txt_upi_id . ",<br>";
            }
            if ($txt_upi_number != $upi_mobile_no) {
                $upi_message_data .= $upi_mobile_no . " TO " . $txt_upi_number . "";
            }
            $remark = "UPI Id has been updated " . $by;
            $insertLog = $manage->insertUserLogData($page_name, $action, $remark . $upi_message_data);
            $error = false;
            $errorMessage .= "UPI updated Successfully.";
        } else {
            $error = true;
            $errorMessage = "Issue while updating details, Please try again.";
        }
    }

}


if (isset($_GET['display_data'])) {
    $display_data = $security->decrypt($_GET['display_data']);
    $form_data = $manage->getServiceDetails($display_data);
    $name = $form_data['service_name'];
    $description = $form_data['description'];
    $img_name = $form_data['img_name'];
    $read_more = $form_data['read_more'];
    $request_status = $form_data['request_status'];
    $whatsapp_status = $form_data['whatsapp_status'];
    $call_status = $form_data['call_status'];
    $payment_status = $form_data['pay_status'];
    $amount = $form_data['amount'];
    $pay_link = $form_data['pay_link'];
    $read_more_text = $form_data['read_more_txt'];
    $serv_type = $form_data['serv_type'];
    $uploadImage = "uploads/" . $session_email . "/service/" . $form_data['img_name'];
}

if (isset($_POST['btn_update'])) {
    if (!isset($_POST['request_status'])) {
        $request_status = 0;
    } else {
        $request_status = 1;
    }
    if (!isset($_POST['whatsapp_status'])) {
        $whatsapp_status = 0;
    } else {
        $whatsapp_status = 1;
    }

    if (!isset($_POST['call_status'])) {
        $call_status = 0;
    } else {
        $call_status = 1;
    }

    if (isset($_POST['txt_name']) && $_POST['txt_name'] != "") {
        $name = $_POST['txt_name'];
    } else {
        $error = true;
        $errorMessage .= "Please enter service name.<br>";
    }
    if (isset($_POST['txt_des']) && $_POST['txt_des'] != "") {
        $description = $_POST['txt_des'];
    } else {
        $error = true;
        $errorMessage .= "Please enter description.<br>";
    }
    $read_more_txt = $_POST['read_more_txt'];
    $serv_type = $_POST['serv_type'];
    if (isset($_POST['payment_status']) && $_POST['payment_status'] == 1) {
        $payment_status = $_POST['payment_status'];
        if (isset($_POST['txt_amount']) && $_POST['txt_amount'] != "") {
            $txt_amount = $_POST['txt_amount'];
        } else {
            $error = true;
            $errorMessage .= "Please enter amount.<br>";
        }
        if (isset($_POST['pay_option']) && $_POST['pay_option'] == "upi") {
            if (!$alreadySavedUpi) {
                $error = true;
                $errorMessage .= "Please enter upi id.<br>";
            }
            $pay_link = '';
        } elseif (isset($_POST['pay_option']) && $_POST['pay_option'] == "pay_link") {
            if (isset($_POST['pay_link']) && $_POST['pay_link'] != "") {
                $pay_link = $_POST['pay_link'];
            } else {
                $error = true;
                $errorMessage .= "Please enter payment link.<br>";
            }

        } else {
            $error = true;
            $errorMessage .= "Please select pay option.<br>";
        }


    } else {
        $payment_status = 0;
        $txt_amount = '';
        $pay_link = '';
    }

    if (isset($_FILES['upload']) && $_FILES['upload']['error'][0] != 4 /* 4 means there is no file selected*/) {
        $imgUploadStatus = true;
        $directory_name = "uploads/" . $session_email . "/service/";
        $extension = array('.jpg', 'JPG', '.JPG', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG');
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
        $digits = 4;
        $randomNum = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        $newfilename = "";
        $cover_name = "";
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
                } else {
                $manage->imageCompressor($newPath);
                
                    if (file_exists($uploadImage) && $form_data['img_name'] != "") {
                        unlink('uploads/' . $session_email . '/service/' . $form_data['img_name'] . '');
                    }
                }
            }
        }
        if (!$error) {
            $status = $manage->updateService($name, $description, $cover_name, $request_status, $_POST['read_more'], $security->decrypt($_GET['display_data']), $whatsapp_status, $call_status, $serv_type, $payment_status
                , $read_more_txt, $txt_amount, $pay_link);
            if ($status) {
                $errorMessage = "details Updated successfully";
                if ($android_url != "") {
                    header('location:service.php?' . $android_url);
                } else {
                    header('location:service.php');
                }
            } else {
                $error = true;
                $errorMessage = "Issue while updating details, Please try again.";
            }
        }


    }

}

if (isset($_POST['btn_update_theme'])) {
    if (isset($_POST['rd_theme'])) {
        $rd_theme = $_POST['rd_theme'];
    } else {
        $rd_theme = "2";
    }
    $update = $manage->mu_updateUserSectionTheme($rd_theme, $section_id, $id);
    if ($update) {
        $error1 = false;
        $errorMessage1 = "Theme has been updated successfully.";
    } else {
        $error1 = true;
        $errorMessage1 = "Issue while updating theme please try after some time.";
    }

}

if (isset($_POST['btn_update_product_theme'])) {
    $active_tab = true;
    if (isset($_POST['rd_theme'])) {
        $rd_theme = $_POST['rd_theme'];
    } else {
        $rd_theme = "2";
    }
    $update = $manage->mu_updateUserSectionTheme($rd_theme, "10", $id);
    if ($update) {
        $error1 = false;
        $errorMessage1 = "Theme has been updated successfully.";
    } else {
        $error1 = true;
        $errorMessage1 = "Issue while updating theme please try after some time.";
    }

}

/*This Method used for display the data in Manage table.*/
$get_result = $manage->displayServiceDetails();
if ($get_result != null) {
    $count = mysqli_num_rows($get_result);
} else {
    $count = 0;
}
$get_pro_result = $manage->displayServiceDetails(1);
if ($get_pro_result != null) {
    $pro_count = mysqli_num_rows($get_pro_result);
} else {
    $pro_count = 0;
}
if ($count == 0) {
    $_SESSION['red_dot']['service_name'] = true;
}

if (isset($_POST['btn_save'])) {
    if (!isset($_POST['request_status'])) {
        $request_status = 0;
    } else {
        $request_status = 1;
    }
    if (!isset($_POST['whatsapp_status'])) {
        $whatsapp_status = 0;
    } else {
        $whatsapp_status = 1;
    }

    if (!isset($_POST['call_status'])) {
        $call_status = 0;
    } else {
        $call_status = 1;
    }

    if (isset($_POST['txt_name']) && $_POST['txt_name'] != "") {
        $name = $_POST['txt_name'];
    } else {
        $error = true;
        $errorMessage .= "Please enter service name.<br>";
    }
    if (isset($_POST['txt_des']) && $_POST['txt_des'] != "") {
        $description = $_POST['txt_des'];
    } else {
        $error = true;
        $errorMessage .= "Please enter description.<br>";
    }

    $read_more_txt = $_POST['read_more_txt'];
    $serv_type = $_POST['serv_type'];
    if (isset($_POST['payment_status']) && $_POST['payment_status'] == 1) {
        $payment_status = $_POST['payment_status'];
        if (isset($_POST['txt_amount']) && $_POST['txt_amount'] != "") {
            $txt_amount = $_POST['txt_amount'];
        } else {
            $error = true;
            $errorMessage .= "Please enter amount.<br>";
        }
        if (isset($_POST['pay_option']) && $_POST['pay_option'] == "upi") {
            if (!$alreadySavedUpi) {
                $error = true;
                $errorMessage .= "Please enter upi id.<br>";
            }
        } elseif (isset($_POST['pay_option']) && $_POST['pay_option'] == "pay_link") {
            if (isset($_POST['pay_link']) && $_POST['pay_link'] != "") {
                $pay_link = $_POST['pay_link'];
            } else {
                $error = true;
                $errorMessage .= "Please enter payment link.<br>";
            }

        } else {
            $error = true;
            $errorMessage .= "Please select pay option.<br>";
        }


    } else {
        $payment_status = 0;
        $txt_amount = '';
        $pay_link = '';
    }


    if (isset($_FILES['upload']) && $_FILES['upload']['error'][0] != 4 /* 4 means there is no file selected*/) {
        $imgUploadStatus = true;
        $directory_name = "uploads/" . $session_email . "/service/";
        $extension = array('.jpg', 'JPG', '.JPG', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG');
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
                $manage->imageCompressor($newPath);
            }


            /* $key = json_encode($result);
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

        }


        if (!$error) {
            $status = $manage->addService($id, $name, $description, $cover_name, $request_status, $_POST['read_more'],
                $whatsapp_status, $call_status, $serv_type, $payment_status, $read_more_txt, $txt_amount, $pay_link);
            if ($status) {
                $_SESSION['red_dot']['service_name'] = false;
                $get_data = $manage->selectTheme();
                $user_keyword = $get_data['user_keyword'];
                if ($user_keyword != null) {
                    $keyword = $get_data['user_keyword'] . "," . $name;
                } else {
                    $keyword = $name;
                }
                if ($count == 0) {
                    $_SESSION['total_percent'] = $_SESSION['total_percent'] + 10;
                }
                $get_result = $manage->displayServiceDetails();
                if ($get_result != null) {
                    $count = mysqli_num_rows($get_result);
                } else {
                    $count = 0;
                }
                $update = $manage->updateUserKeyword($keyword);
                $name = "";
                $description = "";
                $_POST['read_more'] = "";
                $newFile = "";
                $error = false;
                $errorMessage = $_SESSION['menu']['s_services'] . " added successfully";
            } else {
                $error = true;
                $errorMessage = "Issue while adding details, Please try again.";

            }
        }
    }
}

if (isset($_GET['delete_data'])) {
    $delete_data = $security->decrypt($_GET['delete_data']);
    $img_path = $_GET['img_path'];
    $deleteImage = "uploads/" . $session_email . "/service/" . $img_path;
    if (file_exists($deleteImage)) {
        unlink('uploads/' . $session_email . '/service/' . $_GET['img_path'] . '');
        $status = $manage->deleteService($delete_data);
    } else {
        $status = $manage->deleteService($delete_data);
    }
    if ($status) {
        $get_result = $manage->displayServiceDetails();
        if ($get_result != null) {
            $count = mysqli_num_rows($get_result);
        } else {
            $count = 0;
        }
        if ($count == 0) {
            $_SESSION['total_percent'] = $_SESSION['total_percent'] - 10;
        }
        if ($android_url != "") {
            header('location:service.php?' . $android_url);
        } else {
            header('location:service.php');
        }
    }
}

if (isset($_GET['id']) && (isset($_GET['action']))) {
    $action = $_GET['action'];
    $get_id = $security->decrypt($_GET['id']);
    if ($action == "unpublish") {
        $result = $manage->publishUnpublish($get_id, 0, $manage->serviceTable);
    } else {
        $result = $manage->publishUnpublish($get_id, 1, $manage->serviceTable);
    }
    if ($android_url != "") {
        header('location:service.php?' . $android_url);
    } else {
        header('location:service.php');
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
    if ($android_url != "") {
        header('location:service.php?' . $android_url);
    } else {
        header('location:service.php');
    }
}
$get_section_theme = $manage->mdm_displaySectionTheme($id, $section_id);
if ($get_section_theme != null) {
    $section_theme = $get_section_theme['theme_id'];
} else {
    $section_theme = 2;
}

$get_pro_section_theme = $manage->mdm_displaySectionTheme($id, "10");
if ($get_pro_section_theme != null) {
    $pro_section_theme = $get_pro_section_theme['theme_id'];
} else {
    $pro_section_theme = 2;
}

?>
<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php include "assets/common-includes/total_count.php" ?>
    <title><?php echo $_SESSION['menu']['s_services'] ?></title>
    <style>
        #service-message {
            display: none;
        }

        img {
            max-width: 180px;
        }

        .table-bordered tbody tr td, .table-bordered tbody tr th {
            cursor: all-scroll;
        }
    </style> <?php include "assets/common-includes/header_includes.php" ?>

    <link rel="stylesheet" type="text/css" href="assets/css/component.css"/>
    <!--[if IE]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!-- remove this if you use Modernizr -->
    <script>
        (function (e, t, n) {
            var r = e.querySelectorAll("html")[0];
            r.className = r.className.replace(/(^|\s)no-js(\s|$)/, "$1js$2")
        })(document, window, 0);
    </script>
    <link rel="stylesheet" href="https://unpkg.com/lite-editor@1.6.39/css/lite-editor.css">


</head>
<body>
<?php
if (!isset($_GET['android_user_id']) && (!isset($_GET['type'])) && (!isset($_GET['api_key']))) {
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
    }elseif (isset($_GET['android_user_id']) && (isset($_GET['type'])) && (isset($_GET['api_key']))) {
    ?>
    <section class="androidSection">
        <?php
        }
        ?>
        <div class="clearfix padding_bottom_46">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding_zero padding_zero_both">
                <div class="row">
                    <div class="card">
                        <div class="header">
                            <div class="row cust-row">
                                <?php if (isset($_GET['display_data'])) { ?>
                                    <div class="col-lg-7"><h2>
                                            Update <?php echo $_SESSION['menu']['s_services'] ?>
                                            / <?php echo $_SESSION['menu']['s_products'] ?>
                                        </h2></div>
                                <?php } else { ?>
                                    <div class="col-lg-7"><h2>
                                            Add <?php echo $_SESSION['menu']['s_services'] ?>
                                            / <?php echo $_SESSION['menu']['s_products'] ?>
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
                                    <label class="form-label">Select Type</label> <span class="required_field">*</span>

                                    <div class="form-group form-float">
                                        <input type="radio" class="radio_prop" name="serv_type"
                                               value="0" <?php if (isset($serv_type) && $serv_type == "0") echo "checked"; elseif (!isset($serv_type)) echo "checked"; ?>>
                                        Service &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input class="radio_prop" type="radio" name="serv_type"
                                               value="1" <?php if (isset($serv_type) && $serv_type == '1') echo "checked"; ?>>
                                        Product

                                    </div>
                                </div>
                                <div>
                                    <label class="form-label">Name</label> <span class="required_field">*</span>

                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input name="txt_name" class="form-control"
                                                   placeholder="Name Of Service"
                                                   value="<?php if (isset($name)) echo htmlspecialchars($name); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="form-label">Description</label> <span class="required_field">*</span>


                                    <div class="form-group form-float">
                                        <textarea id="default" name="txt_des" rows="4" cols="50" class="form-control"
                                                  placeholder="Please Enter Service Description"><?php if (isset($description)) echo str_replace('\r\n', '', $description); ?></textarea>
                                    </div>
                                </div>
                                <div>
                                    <label class="form-label">Read More Link</label> (Optional)

                                    <div class="form-group form-float">
                                        <div class="form-line" style="display: flex">

                                            <select class="form-control" name="read_more_txt">
                                                <option <?php echo (isset($read_more_text) && $read_more_text == 'Read More') ? "selected" : ''; ?>>
                                                    Read More
                                                </option>
                                                <option <?php echo (isset($read_more_text) && $read_more_text == 'Apply Now') ? "selected" : ''; ?>>
                                                    Apply Now
                                                </option>
                                                <option <?php echo (isset($read_more_text) && $read_more_text == 'Book Now') ? "selected" : ''; ?>>
                                                    Book Now
                                                </option>
                                                <option <?php echo (isset($read_more_text) && $read_more_text == 'Donate Now') ? "selected" : ''; ?>>
                                                    Donate Now
                                                </option>
                                                <option <?php echo (isset($read_more_text) && $read_more_text == 'Contact Us') ? "selected" : ''; ?>>
                                                    Contact Us
                                                </option>
                                                <option <?php echo (isset($read_more_text) && $read_more_text == 'Download') ? "selected" : ''; ?>>
                                                    Download
                                                </option>
                                                <option <?php echo (isset($read_more_text) && $read_more_text == 'Learn More') ? "selected" : ''; ?>>
                                                    Learn More
                                                </option>
                                                <option <?php echo (isset($read_more_text) && $read_more_text == 'Request Time') ? "selected" : ''; ?>>
                                                    Request Time
                                                </option>
                                                <option <?php echo (isset($read_more_text) && $read_more_text == 'See Menu') ? "selected" : ''; ?>>
                                                    See Menu
                                                </option>
                                                <option <?php echo (isset($read_more_text) && $read_more_text == 'Shop Now') ? "selected" : ''; ?>>
                                                    Shop Now
                                                </option>
                                                <option <?php echo (isset($read_more_text) && $read_more_text == 'Sign Up') ? "selected" : ''; ?>>
                                                    Sign Up
                                                </option>
                                                <option <?php echo (isset($read_more_text) && $read_more_text == 'Watch More') ? "selected" : ''; ?>>
                                                    Watch More
                                                </option>
                                                <option <?php echo (isset($read_more_text) && $read_more_text == 'Enroll Now') ? "selected" : ''; ?>>
                                                    Enroll Now
                                                </option>
                                                <option <?php echo (isset($read_more_text) && $read_more_text == 'Admission Process') ? "selected" : ''; ?>>
                                                    Admission Process
                                                </option>
                                                <option <?php echo (isset($read_more_text) && $read_more_text == 'Buy Now') ? "selected" : ''; ?>>
                                                    Buy Now
                                                </option>
                                                <option <?php echo (isset($read_more_text) && $read_more_text == 'Registration') ? "selected" : ''; ?>>
                                                    Registration
                                                </option>
                                            </select>
                                            <input name="read_more" class="form-control"
                                                   placeholder="Enter link"
                                                   value="<?php if (isset($read_more)) echo htmlspecialchars($read_more); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div>

                                    <div class="form-group form-float">
                                        <div class="row">
                                            <div class="col-md-8 m_b_0">
                                                <label class="form-label">Upload Image</label><br>
                                                <!--<input type="file" id="upload" name="upload[]"
                                                       multiple="multiple" accept=".png, .jpg, .jpeg,.JPG,.PNG,.JPEG"
                                                       value="<?php /*if (isset($filename)) echo $filename; */ ?>">-->
                                                <input type="file" name="upload[]" id="file-7"
                                                       class="inputfile inputfile-6"
                                                       data-multiple-caption="{count} files selected" multiple
                                                       onchange="readURL(this);"
                                                       accept=".png, .jpg, .jpeg,.JPG,.PNG,.JPEG"/>
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
                                            if (!isset($_GET['display_data'])) {
                                                ?>
                                                <div class="col-md-4 m_b_0">
                                                    <label class="form-label">Default Image</label>
                                                    <br>
                                                    <?php echo '<img src="uploads/service.png" class="default_icon"/><br />'; ?>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                            <div class="col-md-12 m_b_0">
                                                <?php
                                                echo FILE_NOTE_4;
                                                ?>
                                            </div>

                                        </div>

                                        <?php if (isset($_GET['display_data']) && $form_data['img_name'] != "") {
                                            echo '<img src="uploads/' . $session_email . '/service/' . $form_data['img_name'] . '" style="width: 20%;" />';
                                        } elseif (isset($_GET['display_data']) && $form_data['img_name'] == "") {
                                            echo '<img src="uploads/service.png" style="width: 20%;"/>';
                                        } ?>
                                        <div>
                                            <br>
                                            <input type="checkbox"
                                                   name="request_status" <?php if (isset($request_status)) {
                                                if ($request_status == 1) echo "checked";
                                            } else {
                                                echo "checked";
                                            } ?> > Add Send Request button<br>
                                            <input type="checkbox"
                                                   name="whatsapp_status" <?php if (isset($whatsapp_status)) {
                                                if ($whatsapp_status == 1) echo "checked";
                                            } else {
                                                echo "checked";
                                            } ?> > Add WhatsApp Request button<br>
                                            <input type="checkbox" name="call_status" <?php if (isset($call_status)) {
                                                if ($call_status == 1) echo "checked";
                                            } else {
                                                echo "checked";
                                            } ?> > Add Call button<br>
                                            <input type="checkbox" name="payment_status" id="payment_status"
                                                   value="1" <?php if (isset($amount) && $amount != '') echo "checked"; ?> >
                                            Add Payment Option<br>
                                        </div>
                                        <div class="payment_setting">
                                            <div class="form-group form-float">
                                                <?php
                                                if ($user_country == 101) {
                                                    ?>
                                                    <input type="radio" class="radio_prop" name="pay_option"
                                                           value="upi" <?php if (isset($amount) && $amount != "" && $pay_link == '') echo "checked"; elseif (!isset($pay_link)) echo "checked"; ?>> UPI &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <?php
                                                }
                                                ?>
                                                <input class="radio_prop" type="radio" name="pay_option"
                                                       value="pay_link" <?php if (isset($pay_link) && $pay_link != '') echo "checked"; ?>>
                                                Payment Link
                                            </div>
                                            <?php
                                            if ($user_country == 101) {
                                                ?>
                                                <div class="form-group">
                                                    <button class="btn btn-primary upi_btn waves-effect" type="button"
                                                            name="upi-more" data-toggle="modal"
                                                            data-target="#myModalUpi">
                                                        <?php if (isset($alreadySavedUpi) && $alreadySavedUpi) {
                                                            ?>
                                                            MODIFY UPI ID
                                                            <?php
                                                        } else {
                                                            ?>
                                                            ADD UPI ID
                                                            <?php
                                                        }
                                                        ?>
                                                    </button>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                            <div>
                                                <label class="form-label">Amount</label>

                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input name="txt_amount" class="form-control"
                                                               placeholder="Enter Amount" type="number"
                                                               value="<?php if (isset($amount)) echo $amount; ?>">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="pay_link">
                                                <label class="form-label">Payment Link</label>

                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input name="pay_link" class="form-control"
                                                               placeholder="Enter Payment Link"
                                                               value="<?php if (isset($pay_link)) echo $pay_link; ?>">
                                                    </div>
                                                </div>
                                            </div>
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
                                                <button type="submit" name="btn_save" id="myBtn"
                                                        class="btn btn-primary waves-effect">Add
                                                </button>
                                            </div>
                                        <?php } ?>
                                        &nbsp;&nbsp;
                                        <div>
                                            <a href="service.php<?php if ($android_url != "") echo "?" . $android_url; ?>"
                                               class="btn btn-default">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding_zero padding_zero_both">
                <div class="row margin_div_web">
                    <!--  <div class="freelancer_search_box padding_zero padding_zero_both" style="width: 100%">
                    <div class="col-md-12 col-xs-12">
                        <div class="row">
                            <div id="service-message" class="alert alert-success">
                            </div>
                            <div class="col-md-10 col-xs-9">
                                <h4>Hide & Unhide</h4>
                            </div>
                            <div class="col-md-2 text-right col-xs-3">

                                    <label class="switch">
                                        <input type="checkbox" id="read" name="permission[]" value="<?php /*echo $display_result['digital_card']; */ ?>" <?php /*if ($display_result['digital_card'] == '1') {
                                            echo 'checked="checked"';
                                        } */ ?>/>
                                        <span class="slider round"></span>
                                    </label>
                            </div>
                        </div>-->
                    <!--<form action="" method="post">-->
                    <!--<ul class="profile-ul">
                                <li class="li_event">
                                    <div class="cust-div">
                                        <input type="checkbox" name="type[]"
                                               value="digital_card"  <?php /*if ($display_result['digital_card'] == '1') {
                                            echo 'checked="checked"';
                                        } */ ?> > Service
                                    </div>
                                </li>
                                <li>
                                    <input type="checkbox" id="read" name="permission[]" value="<?php /*echo $display_result['digital_card']; */ ?>" <?php /*if ($display_result['digital_card'] == '1') {
                                        echo 'checked="checked"';
                                    } */ ?>/>
                                </li>
                                <li>

                                </li>
                                   <li>
                                    <div class="cust-div">
                                        <input type="checkbox" name="type[]"
                                               value="website" <?php /*/*if ($display_result['website'] == '1') {
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
                        <div class="body custom_card_padding">
                            <ul class="nav nav-tabs tab-nav-right" role="tablist">
                                <li role="presentation" <?php if (!$active_tab) { ?> class="active" <?php } ?>>
                                    <a href="#profile" class="custom_nav_tab"
                                       data-toggle="tab"><?php echo $_SESSION['menu']['s_services'] ?></a>
                                </li>
                                <li role="presentation"
                                    onclick="openLogo_div(this)" <?php if ($active_tab) { ?> class="active" <?php } ?>>
                                    <a class="custom_nav_tab" href="#product"
                                       data-toggle="tab"><?php echo $_SESSION['menu']['s_products'] ?> <label
                                                class="label label-success company_new_label">New</label>
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div role="tabpanel"
                                     class="tab-pane <?php if (!$active_tab) echo "fade in active" ?>"
                                     id="profile">
                                    <div class="header">
                                        <div class="col-md-12 m-b-0">
                                            <div class="row">
                                                <div class="col-md-6 m-b-0">
                                                    <h2>
                                                        Manage <?php echo $_SESSION['menu']['s_services'] ?> <span
                                                                class="badge"><?php
                                                            if (isset($count)) echo $count;
                                                            ?></span>
                                                    </h2>
                                                </div>
                                                <div class="col-md-6 text-right m-b-0">
                                                    <button class="btn btn-warning shine " data-toggle="modal"
                                                            data-target="#myModal"> Service Theme
                                                    </button>
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
                                        <div class="double-scroll">
                                            <table id="dtHorizontalVerticalExample"
                                                   class="table table-striped table-bordered table-sm "
                                                   cellspacing="0"
                                                   width="100%">
                                                <thead>
                                                <tr class="back-color">
                                                    <th style="">ACTION</th>
                                                    <th class="visible-lg visible-md hidden-sm hidden-xs">Description
                                                    </th>
                                                    <th style="width: 30%">Service</th>
                                                </tr>
                                                </thead>
                                                <tbody class="row_position">
                                                <?php
                                                if ($get_result != null) {
                                                    while ($result_data = mysqli_fetch_array($get_result)) {
                                                        $service_path = 'uploads/' . $session_email . '/service/' . $result_data['img_name'];
                                                        ?>
                                                        <tr id="<?php echo $security->encryptWebservice($result_data['id']); ?>">
                                                            <td>
                                                                <ul class="header-dropdown">
                                                                    <li class="dropdown dropdown-inner-table">
                                                                        <a href="javascript:void();"
                                                                           class="dropdown-toggle"
                                                                           data-toggle="dropdown"
                                                                           role="button" aria-haspopup="true"
                                                                           aria-expanded="false">
                                                                            <i class="material-icons">more_vert</i>
                                                                        </a>
                                                                        <ul class="dropdown-menu pull-left">
                                                                            <li>
                                                                                <a href="service.php?display_data=<?php echo $security->encrypt($result_data['id']);
                                                                                if ($android_url != "") echo "&" . $android_url; ?>"
                                                                                <i class="fas fa-edit"></i>&nbsp;&nbsp;Edit</a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="service.php?delete_data=<?php echo $security->encrypt($result_data['id']); ?>&img_path=<?php echo $result_data['img_name'];
                                                                                if ($android_url != "") echo "&" . $android_url; ?>"
                                                                                   onclick="return confirm('Are You sure you want to delete?');">
                                                                                    <i class="fas fa-trash-alt"></i>&nbsp;&nbsp;Delete</a>
                                                                            </li>
                                                                            <li>
                                                                                <a onclick="return confirm('Are You sure you want to <?php echo $result_data['status'] == 0 ? 'publish' : 'unpublish'; ?>?');"
                                                                                   href="service.php?id=<?php echo $security->encrypt($result_data['id']); ?>&action=<?php echo $result_data['status'] == 0 ? "publish" : "unpublish";
                                                                                   if ($android_url != "") echo "&" . $android_url; ?> "><i
                                                                                        class="fas <?php echo $result_data['status'] == 0 ? "fa-upload" : "fa-download"; ?>"></i>&nbsp;&nbsp;<?php echo $result_data['status'] == 1 ? "Unpublish" : "Publish"; ?>
                                                                                </a>
                                                                            </li>
                                                                        </ul>
                                                                    </li>
                                                                </ul>
                                                            </td>
                                                            <td>
                                                                <div class="visible-lg visible-md hidden-sm hidden-xs">
                                                                    <div style="display: inline-block;vertical-align: top">
                                                                        <img src="assets/images/draggable-icon.png"
                                                                             style="width: 45px;">
                                                                        <div class="user_profile_image">
                                                                            <?php
                                                                            if ($result_data['img_name'] != "" && file_exists($service_path)) {
                                                                                echo '<img src=" ' . $service_path . ' " style="width: 100%"/><br />';
                                                                            } else {
                                                                                echo '<img src="uploads/service.png" style="width: 100%;padding-top: 20%;"/><br />';
                                                                            }
                                                                            ?>
                                                                        </div>
                                                                    </div>
                                                                    <div style="display: inline-block;">
                                                                        <?php echo wordwrap($result_data['service_name'], 20, "<br />\n") . "<br>";
                                                                        ?>

                                                                        <label
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
                                                                </div>

                                                                <div class="hidden-lg hidden-md visible-sm visible-xs">
                                                                    <?php
                                                                    if ($result_data['img_name'] != "" && file_exists($service_path)) {
                                                                        echo '<img src=" ' . $service_path . ' " class="user_profile_image"/><br />';
                                                                    } else {
                                                                        echo '<img src="uploads/service.png" class="user_profile_image"/><br />';
                                                                    }
                                                                    ?>
                                                                    <br>
                                                                    <b>NAME</b>
                                                                    : <?php echo wordwrap($result_data['service_name'], 20, "<br />\n"); ?>
                                                                    <br>
                                                                    <b>DESCRIPTION
                                                                        : </b><br><?php echo '<span class="more">' . str_replace('\r\n', '', wordwrap($manage->rep_escape($result_data['description']), 25, "<br />\n")) . "</span><br>"; ?>
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
                                                            <td class="visible-lg visible-md hidden-sm hidden-xs">
                                                                <?php
                                                                echo '<span class="more">' . str_replace('\r\n', '', wordwrap($manage->rep_escape($result_data['description']), 35, "<br />\n")) . "</span>";
                                                                ?>
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
                                <div role="tabpanel"
                                     class="tab-pane <?php if ($active_tab) echo "fade in active" ?>"
                                     id="product">
                                    <div class="header">
                                        <div class="col-md-12 m-b-0">
                                            <div class="row">
                                                <div class="col-md-6 m-b-0">
                                                    <h2> Manage <?php echo $_SESSION['menu']['s_products'] ?> <span
                                                                class="badge"><?php
                                                            if (isset($pro_count)) echo $pro_count;
                                                            ?></span></h2>
                                                </div>
                                                <div class="col-md-6 text-right m-b-0">
                                                    <button class="btn btn-warning shine " data-toggle="modal"
                                                            data-target="#myProductModal"> <?php echo $_SESSION['menu']['s_products'] ?>
                                                        Theme
                                                    </button>
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
                                        <div class="double-scroll">
                                            <table id="dtHorizontalVerticalExample"
                                                   class="table table-striped table-bordered table-sm "
                                                   cellspacing="0"
                                                   width="100%">
                                                <thead>
                                                <tr class="back-color">
                                                    <th style="width: 30%">Service</th>
                                                    <th class="visible-lg visible-md hidden-sm hidden-xs">Description
                                                    </th>
                                                    <th>ACTION</th>
                                                </tr>
                                                </thead>
                                                <tbody class="row_position">
                                                <?php
                                                if ($get_pro_result != null) {
                                                    while ($result_data = mysqli_fetch_array($get_pro_result)) {
                                                        $service_path = 'uploads/' . $session_email . '/service/' . $result_data['img_name'];
                                                        ?>
                                                        <tr id="<?php echo $security->encryptWebservice($result_data['id']); ?>">
                                                            <td>
                                                                <div class="visible-lg visible-md hidden-sm hidden-xs">
                                                                    <div style="display: inline-block;vertical-align: top">
                                                                        <img src="assets/images/draggable-icon.png"
                                                                             style="width: 45px;">
                                                                        <div class="user_profile_image">
                                                                            <?php
                                                                            if ($result_data['img_name'] != "" && file_exists($service_path)) {
                                                                                echo '<img src=" ' . $service_path . ' " style="width: 100%"/><br />';
                                                                            } else {
                                                                                echo '<img src="uploads/service.png" style="width: 100%;padding-top: 20%;"/><br />';
                                                                            }
                                                                            ?>
                                                                        </div>
                                                                    </div>
                                                                    <div style="display: inline-block;">
                                                                        <?php echo wordwrap($result_data['service_name'], 20, "<br />\n") . "<br>";
                                                                        ?>

                                                                        <label
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
                                                                </div>

                                                                <div class="hidden-lg hidden-md visible-sm visible-xs">
                                                                    <?php
                                                                    if ($result_data['img_name'] != "" && file_exists($service_path)) {
                                                                        echo '<img src=" ' . $service_path . ' " class="user_profile_image"/><br />';
                                                                    } else {
                                                                        echo '<img src="uploads/service.png" class="user_profile_image"/><br />';
                                                                    }
                                                                    ?>
                                                                    <br>
                                                                    <b>NAME</b>
                                                                    : <?php echo wordwrap($result_data['service_name'], 20, "<br />\n"); ?>
                                                                    <br>
                                                                    <b>DESCRIPTION
                                                                        : </b><br><?php echo '<span class="more">' . str_replace('\r\n', '', wordwrap($manage->rep_escape($result_data['description']), 25, "<br />\n")) . "</span><br>"; ?>
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
                                                            <td class="visible-lg visible-md hidden-sm hidden-xs">
                                                                <?php
                                                                echo '<span class="more">' . str_replace('\r\n', '', wordwrap($manage->rep_escape($result_data['description']), 35, "<br />\n")) . "</span>";
                                                                ?>
                                                            </td>

                                                            <td>
                                                                <ul class="header-dropdown">
                                                                    <li class="dropdown dropdown-inner-table">
                                                                        <a href="javascript:void();"
                                                                           class="dropdown-toggle"
                                                                           data-toggle="dropdown"
                                                                           role="button" aria-haspopup="true"
                                                                           aria-expanded="false">
                                                                            <i class="material-icons">more_vert</i>
                                                                        </a>
                                                                        <ul class="dropdown-menu pull-right">
                                                                            <li>
                                                                                <a href="service.php?display_data=<?php echo $security->encrypt($result_data['id']);
                                                                                if ($android_url != "") echo "&" . $android_url; ?>&product=true"
                                                                                <i class="fas fa-edit"></i>&nbsp;&nbsp;Edit</a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="service.php?delete_data=<?php echo $security->encrypt($result_data['id']); ?>&img_path=<?php echo $result_data['img_name'];
                                                                                if ($android_url != "") echo "&" . $android_url; ?>"
                                                                                   onclick="return confirm('Are You sure you want to delete?');">
                                                                                    <i class="fas fa-trash-alt"></i>&nbsp;&nbsp;Delete</a>
                                                                            </li>
                                                                            <li>
                                                                                <a onclick="return confirm('Are You sure you want to <?php echo $result_data['status'] == 0 ? 'publish' : 'unpublish'; ?>?');"
                                                                                   href="service.php?id=<?php echo $security->encrypt($result_data['id']); ?>&action=<?php echo $result_data['status'] == 0 ? "publish" : "unpublish";
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
                                                <?php } ?>
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


    </section>
    <!--<script>
        $(document).ready(function(){
            console.log('success');
            setInterval(function(){
                $.get("check-login.php", function(data){
                    console.log(data);
                    if(data==0) window.location.href="logout.php";
                });
            },1000);
        });
    </script>-->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-md">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header cust-upi-madal">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Select Theme</h4>
                </div>
                <div class="modal-body">
                    <div class="body">
                        <form id="upi_form_validation" method="POST" action="">
                            <div class="">
                                <ul class="company_ul">
                                    <li class="company_ul_li">
                                        <input type="radio" id="myCheckbox1" name="rd_theme"
                                               value="1" <?php if (isset($section_theme) && $section_theme == 1) echo "checked"; ?> />
                                        <label for="myCheckbox1"><img src="assets/images/theme/1.png"/></label>
                                    </li>
                                    <li class="company_ul_li">
                                        <input type="radio" id="myCheckbox2" name="rd_theme"
                                               value="2" <?php if (isset($section_theme) && $section_theme == 2) echo "checked"; ?> />
                                        <label for="myCheckbox2"><img src="assets/images/theme/2.png"/></label>
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
    </div>
    <div class="modal fade" id="myProductModal" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header cust-upi-madal">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Select Theme</h4>
                </div>
                <div class="modal-body">
                    <div class="body">
                        <form id="upi_form_validation" method="POST" action="">
                            <div class="">
                                <ul class="company_ul">
                                    <li class="company_ul_li width_33">
                                        <input type="radio" id="myCheckbox3" name="rd_theme"
                                               value="1" <?php if (isset($pro_section_theme) && $pro_section_theme == 1) echo "checked"; ?> />
                                        <label for="myCheckbox3"><img src="assets/images/theme/1.png"/></label>
                                    </li>
                                    <li class="company_ul_li width_33">
                                        <input type="radio" id="myCheckbox4" name="rd_theme"
                                               value="2" <?php if (isset($pro_section_theme) && $pro_section_theme == 2) echo "checked"; ?> />
                                        <label for="myCheckbox4"><img src="assets/images/theme/2.png"/></label>
                                    </li>
                                    <li class="company_ul_li width_33">
                                        <input type="radio" id="myCheckbox5" name="rd_theme"
                                               value="3" <?php if (isset($pro_section_theme) && $pro_section_theme == 3) echo "checked"; ?> />
                                        <label for="myCheckbox5"><img
                                                    src="assets/images/theme/pro-theme-3.png"/></label>
                                    </li>
                                    <li class="company_ul_li" style="width: 100%;margin-top: 15px;">
                                        <button class="btn btn-primary waves-effect form-control"
                                                name="btn_update_product_theme"
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
    </div>

    <div class="modal fade" id="myModalUpi" role="dialog">
        <div class="modal-dialog cust-model-width">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header cust-upi-madal">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <?php if (isset($alreadySavedUpi) && $alreadySavedUpi) {
                        ?>
                        <h4 class="modal-title">Update UPI Details</h4>
                        <?php
                    } else {
                        ?>
                        <h4 class="modal-title">Add UPI Details</h4>
                        <?php
                    }
                    ?>
                </div>
                <div class="modal-body">
                    <div class="body">
                        <form id="upi_form_validation" method="POST" action="">
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
                                <label class="form-label">UPI Id</label>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input name="txt_upi_id" class="form-control"
                                               placeholder="UPI Id"
                                               value="<?php if (isset($upi_id)) echo $upi_id; ?>">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="form-label">UPI Linked Mobile Number</label>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input name="txt_upi_number" class="form-control"
                                               placeholder="UPI Linked Mobile Number"
                                               onkeypress="return isNumberKey(event)"
                                               value="<?php if (isset($upi_mobile_no)) echo $upi_mobile_no; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group form_inline form-float">
                                <?php if (isset($alreadySavedUpi) && $alreadySavedUpi) {
                                    ?>
                                    <button class="btn btn-primary waves-effect form-control"
                                            name="btn_update_upi"
                                            type="submit">
                                        Update
                                    </button>
                                    <?php
                                } else {

                                    ?>
                                    <button class="btn btn-primary waves-effect form-control"
                                            name="btn_save_upi"
                                            type="submit">
                                        Add
                                    </button>
                                    <?php
                                }
                                ?>
                                &nbsp;&nbsp;
                                <!--<div>
                                    <button class="btn btn-default" type="reset">
                                        Reset
                                    </button>
                                </div>-->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include "assets/common-includes/footer_includes.php" ?>

    <script>

        $('#payment_status').click(function () {
            if ($(this).is(':checked'))
                $('.payment_setting').show();
            else
                $('.payment_setting').hide();
        });
        if (jQuery('#payment_status').is(':checked')) {
            $('.payment_setting').show();
        } else {
            $('.payment_setting').hide();
        }

        $('input[name=pay_option]').click(function () {
            if ($(this).val() == "upi") {
                $('.upi_btn').show();
                $('.pay_link').hide();
            } else {
                $('.pay_link').show();
                $('.upi_btn').hide();
            }
        });
        if (jQuery('input[name=pay_option]:checked').val() == "upi") {
            $('.pay_link').hide();
            $('.upi_btn').show();
        } else {
            $('.pay_link').show();
            $('.upi_btn').hide();
        }

    </script>
    <script src="https://unpkg.com/lite-editor@1.6.39/js/lite-editor.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript">
        $(".row_position").sortable({
            delay: 150,
            stop: function () {
                var selectedData = new Array();
                $('.row_position>tr').each(function () {
                    selectedData.push($(this).attr("id"));
                });
                updateOrder(selectedData);
            }
        });


        function updateOrder(data) {
            $.ajax({
                url: "changePosition.php",
                type: 'post',
                data: {service: data},
                success: function (data) {

                }
            })
        }
    </script>
    <script>
        $(document).ready(function () {
            $("#toggle-btn").click(function () {
                $("#toggle-example").collapse('toggle'); // toggle collapse
            });
        });
    </script>
    <script type="text/javascript">

        $("#read").click(function () {
            if ($("#read").val() == 0) {
                $("#read").val(1);
            } else {
                $("#read").val();
            }
            var dataString = "checkboxValue=" + $("#read").val();

            $.ajax({
                type: "POST",
                url: "practise.php", // Name of the php files
                data: dataString,
                success: function (html) {
                    if (html == 1) {
                        if ($("#read").val() == 0) {
                            $("#service-message").css({"display": "block"});
                            $("#service-message").addClass('alert-danger').removeClass('alert-success');
                            $("#service-message").html("Service section has been hide from digital card");
                        } else {
                            $("#service-message").css({"display": "block"});
                            $("#service-message").addClass('alert-success').removeClass('alert-danger');
                            $("#service-message").html("Service section has been Unhide from digital card");
                        }
                    } else {
                        alert("Please try again");
                    }
                },
                error: function (err) {
                    console.log("AJAX error in request: " + JSON.stringify(err, null, 2));
                }
            });

        });


        /*window.history.forward(1);
         document.addEventListener("onkeydown", my_onkeydown_handler);
         function my_onkeydown_handler() {
         switch (event.keyCode) {
         case 116 :
         event.returnValue = false;
         event.keyCode = 0;
         window.status = "We have disabled F5";
         break;
         }
         }*/
    </script>
    <script>
        window.addEventListener('DOMContentLoaded', function () {
            new LiteEditor('.js-lite-editor', {
                disableEditorMode: true
            });
        });
    </script>
    <script>
        function openLogo_div(val) {
            $('.nav-tabs > li').removeClass('active');
            $('#profile').removeClass('in active');
            $(val).addClass('active');
            $('#product').addClass('active');
            $('#product').addClass('in');
        }
    </script>
    <script>
        $(document).ready(function () {
            // Configure/customize these variables.
            var showChar = 75;  // How many characters are shown by default
            var ellipsestext = "...";
            var moretext = "Show more >";
            var lesstext = "Show less";


            $('.more').each(function () {
                var content = $(this).html();

                if (content.length > showChar) {

                    var c = content.substr(0, showChar);
                    var h = content.substr(showChar, content.length - showChar);

                    var html = c + '<span class="moreellipses">' + ellipsestext + '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';

                    $(this).html(html);
                }

            });

            $(".morelink").click(function () {
                if ($(this).hasClass("less")) {
                    $(this).removeClass("less");
                    $(this).html(moretext);
                } else {
                    $(this).addClass("less");
                    $(this).html(lesstext);
                }
                $(this).parent().prev().toggle();
                $(this).prev().toggle();
                return false;
            });
        });
    </script>
    <!--<script src="assets/js/custom-file-input.js"></script>-->
</body>
</html>