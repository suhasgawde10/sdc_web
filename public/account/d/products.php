<?php

require_once "../controller/ManageDesktopCard.php";
$manage = new ManageDesktopCard();
include_once '../sendMail/sendMail.php';
require_once "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include "../data-uri-image.php";
include "assets/common-includes/all-query.php";
$error = false;
$errorMessage = "";
$error1 = false;
$errorMessage1 = "";
$Themerror = false;
$ThemerrorMessage = "";


$service_message = "";
if (isset($_POST['btn_service'])) {
    $service_name = $_POST['service_name'];
    $subject = "Enquiry For service " . $service_name;
    if (isset($enquiry_email) && trim($enquiry_email) != '') {
        $enquiry_email = $enquiry_email;
    } else {
        $enquiry_email = $email;
    }
    $email_message = "You have a customer request for service " . $service_name . "<br>Please contact with the customer Name: " . $_SESSION['client_name'] . " & Contact Number: " . $_SESSION['client_contact'];
    if ($verified_email_status == 1) {
        $sendmail = $manage->sendMail(MAIL_FROM_NAME, $enquiry_email, $subject, $email_message);
    }
    $date_time = date('Y-m-d h:i:a s');
    $sms_message = $_SESSION['client_name'] . " " . $_SESSION['client_contact'] . " enquired for " . $service_name . "\n(" . date('d-M-Y') . ")\nsharedigitalcard";
    $sendsms = $manage->sendSMS($contact_no, $sms_message);
    $update_count = $manage->updateUserLeadCount("Add", $user_id);
    $insert_data = array('user_id' => $user_id, 'client_name' => $_SESSION['client_name'], 'contact_no' => $_SESSION['client_contact'], 'service_name' => $service_name, 'created_date' => date('Y-m-d'));
    $insert = $manage->insert($manage->serviceRequestTable, $insert_data);
    if ($insert) {
        $service_message = "Your request for " . $service_name . " has been sent successfully.";
    }
}
if (isset($_GET['custom_url'])) {
    if ($parent_id != "") {
        $custom_url = $getParentData['custom_url'];
    } else {
        $custom_url = $_GET['custom_url'];
    }
    $section_id = 10;
    $get_result = $manage->mdm_getDigitalCardDetails("service", $custom_url, "1");
    $get_section_theme = $manage->mdm_displaySectionTheme($default_user_id, $section_id);
    if ($get_section_theme != null) {
        $section_theme = $get_section_theme['theme_id'];
    } else {
        $section_theme = 2;
    }
    if ($country != "101") {
        $currency_symbol = "$";
    } else {
        $currency_symbol = "&#8377;";
    }
}

$getDetails = $manage->getGatewayPaymentDetails($user_id);
if ($getDetails != null) {
    $upi_id = $getDetails['upi_id'];
    $upi_mobile_no = $getDetails['upi_mobile_no'];
} else {
    $upi_id = "";
    $upi_mobile_no = "";
}

if($ProductSectionStatus != 1){
    $redirect = FULL_DESKTOP_URL . "gallery" . get_full_param();
    header('Location: '.$redirect);
    die();
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>

    <title>Products - <?php echo $name; ?> - <?php echo $designation; ?> -<?php echo $_SERVER['HTTP_HOST']; ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "assets/common-includes/header_includes.php" ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
</head>

<body class="background_body_image">
<?php
/*
echo $name;
die();*/

?>
<div class="end_sub_overlay">
    <div style="margin-top: 10%;text-align: center;"><!--class="bg-text"-->
        <img src="<?php echo FULL_DESKTOP_URL; ?>assets/images/sub.png" style="width: 40%">
    </div>
</div>

<section>
    <div class="digi-heading"></div>
    <div class="container">
        <div class="digi-web-main">
            <div>
                <?php include "assets/common-includes/left_menu.php" ?><!--Left Menu-->
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-12 bhoechie-tab-container">
                            <div class=" col-md-2  bhoechie-tab-menu-custom">
                                <?php include "assets/common-includes/nav_tab.php" ?>
                            </div>
                            <div class=" col-md-10 bhoechie-tab margin-padding-remover">
                                <div class="bhoechie-tab-content margin-padding-remover"></div>

                                <div class="bhoechie-tab-content margin-padding-remover active">
                                    <section>
                                        <div class="content-main  background-theme-cust">
                                            <div class="service_header">
                                                <div class="all-main-heading">
                                                    <span class="text-color-p"><?php echo $our_product; ?></span>
                                                    <?php /*if (isset($_SESSION['email'])) { */?><!-- <a title="Add Service"
                                                                                                 class="add-icon-color fas fa-pencil-alt"
                                                                                                 href=<?php /*echo FULL_WEBSITE_URL; */?>"user/service.php">
                                                        &nbsp;&nbsp;Edit</a>
                                                    --><?php /*} */?>
                                                </div>
                                            </div>

                                            <div class="cust-coverlay">
                                                <div class="container-fluid padding-remover">
                                                    <div class="bank-up-div">
                                                        <div class="main-board scrollbar style-11">
                                                            <?php
                                                            if ($get_result != null) {
                                                                ?>
                                                                <ul class="borad-of-dirct-ul">
                                                                    <?php
                                                                    while ($result_data = mysqli_fetch_array($get_result)) {
                                                                        $whatsapp_status = $result_data['whatsapp_status'];
                                                                        if ($section_theme == 3) {
                                                                            ?>
                                                                            <li>
                                                                                <div class="board-li-div">

                                                                                    <div style="display: flex">
                                                                                        <div class="service_theme_3_img_div">
                                                                                            <img src="<?php
                                                                                            $service_path = FULL_WEBSITE_URL . "user/uploads/" . $result_data['email'] . "/service/" . $result_data['img_name'];
                                                                                            if (check_url_exits($service_path) && $result_data['img_name'] != "") {
                                                                                                echo $service_path;
                                                                                            } else {
                                                                                                echo FULL_WEBSITE_URL . "user/uploads/service.png";
                                                                                            } ?>" style="width: 100%">
                                                                                        </div>


                                                                                        <div class="service_theme_3_content_div">
                                                                                            <h3 class="service_theme_3_title"><?php echo rep_escape($result_data['service_name']); ?></h3>
                                                                                            <?php
                                                                                            if ($result_data['amount'] != '') {
                                                                                                ?>
                                                                                                <h3 class="service_theme_3_sub_title"><?php echo $currency_symbol . $result_data['amount']; ?></h3>
                                                                                                <?php
                                                                                            }
                                                                                            if ($result_data['amount'] != '') {
                                                                                                if ($result_data['pay_link'] != '') {
                                                                                                    $pay_link = $result_data['pay_link'];
                                                                                                    ?>
                                                                                                    <a href="<?php echo urlChecker(htmlspecialchars($pay_link)); ?>"
                                                                                                       class="btn service_price_btn mar-top-7 "><i
                                                                                                                class="fa fa-shopping-cart"
                                                                                                                aria-hidden="true"></i>
                                                                                                        Buy Now
                                                                                                    </a>
                                                                                                    <?php
                                                                                                } else {
                                                                                                    $pay_link = "upi://pay?pa=" . $upi_id . "&pn=98&mc=null&tid=null&tr=" . urlencode($result_data['service_name']) . "&tn=" . urlencode($result_data['service_name']) . "&am=" . trim($result_data['amount']) . "&mam=null&cu=INR&url=null";

                                                                                                    ?>
                                                                                                    <a href="javascript:void(0);"
                                                                                                       onclick="setUpiLink('<?php echo htmlspecialchars($pay_link); ?>','<?php echo htmlspecialchars($result_data['service_name']) ?>')"
                                                                                                       class="btn service_price_btn mar-top-7 "><i
                                                                                                                class="fa fa-shopping-cart"
                                                                                                                aria-hidden="true"></i>
                                                                                                        Buy Now
                                                                                                    </a>
                                                                                                    <?php
                                                                                                }
                                                                                            }
                                                                                            ?>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="info-board padd_top_15">

                                                                                        <!--  <h5>Manging Director</h5>-->
                                                                                        <p><?php echo rep_escape($result_data['description']); ?></p>
                                                                                        <?php

                                                                                        if ($result_data['request_status'] == 1 OR $result_data['read_more'] != '' OR $whatsapp_status == 1) {
                                                                                            ?>
                                                                                            <div style="padding: 10px;">
                                                                                                <form method="post"
                                                                                                      action="">
                                                                                                    <input type="hidden"
                                                                                                           name="service_name"
                                                                                                           value="<?php echo $result_data['service_name']; ?>">
                                                                                                    <?php
                                                                                                    if ($result_data['request_status'] == 1) {
                                                                                                        ?>
                                                                                                        <button
                                                                                                                class="btn service_btn" <?php
                                                                                                        if (isset($_SESSION['client_name'])) {
                                                                                                            ?>
                                                                                                            type="submit" name="btn_service"
                                                                                                            <?php
                                                                                                        } else {
                                                                                                            ?>
                                                                                                            onclick="openServiceModal('<?php echo $result_data['service_name']; ?>')" type="button"
                                                                                                            <?php
                                                                                                        }
                                                                                                        ?>>
                                                                                                            <i class="fa fa-paper-plane"></i>
                                                                                                            Send Enquiry
                                                                                                        </button>
                                                                                                        <?php
                                                                                                    }
                                                                                                    $text = urlencode('I am interested in your ' . $result_data['service_name'] ." ". $product.' which is listed in your digital card please reply to my message.');
                                                                                                    $number2 = $contact_no;
                                                                                                    if ($whatsapp_status == 1) {
                                                                                                        if ($whatsapp_no == '') {
                                                                                                            $number = $contact_no;
                                                                                                        } else {
                                                                                                            $number = $whatsapp_no;
                                                                                                        }


                                                                                                        ?>
                                                                                                        <a href="https://api.whatsapp.com/send?phone=<?php echo $country_code . $number; ?>&text=<?php echo $text ?>"
                                                                                                           target="_blank"
                                                                                                           class="btn whatsapp_btn "><i
                                                                                                                    class="fab fa-whatsapp"
                                                                                                                    aria-hidden="true"></i>
                                                                                                            WhatsApp
                                                                                                        </a>
                                                                                                        <?php
                                                                                                    }
                                                                                                    if ($result_data['call_status'] != 0) {
                                                                                                        ?>
                                                                                                        <a href="tel:<?php echo $country_code . $number2; ?>"
                                                                                                           class="btn call_btn"><i
                                                                                                                    class="fa fa-phone fa-flip-horizontal"
                                                                                                                    aria-hidden="true"></i>
                                                                                                            Call Now
                                                                                                        </a>
                                                                                                        <?php
                                                                                                    }
                                                                                                    if ($result_data['read_more'] != '') {
                                                                                                        ?>
                                                                                                        <a href="<?php echo urlChecker($result_data['read_more']); ?>"
                                                                                                           target="_blank"
                                                                                                           class="btn read_more_btn <?php if ($result_data['request_status'] == 1 && $whatsapp_status == 1) echo 'mar-top-7'; ?> "><i
                                                                                                                    class="fa fa-info-circle"
                                                                                                                    aria-hidden="true"></i> <?php echo $result_data['read_more_txt']; ?>
                                                                                                        </a>
                                                                                                        <?php
                                                                                                    }

                                                                                                    ?>


                                                                                                </form>

                                                                                            </div>
                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                    </div>
                                                                                </div>
                                                                            </li>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <li>
                                                                                <div class="board-li-div">

                                                                                    <div class=" <?php
                                                                                    if ($section_theme == 1) {
                                                                                        echo "board-img";
                                                                                    } else {
                                                                                        echo "service_theme2";
                                                                                    }
                                                                                    ?>">
                                                                                        <img src="<?php
                                                                                        $service_path = FULL_WEBSITE_URL . "user/uploads/" . $result_data['email'] . "/service/" . $result_data['img_name'];
                                                                                        if (check_url_exits($service_path) && $result_data['img_name'] != "") {
                                                                                            echo $service_path;
                                                                                        } else {
                                                                                            echo FULL_WEBSITE_URL . "user/uploads/service.png";
                                                                                        } ?>">
                                                                                        <?php
                                                                                        if ($section_theme == 2) {
                                                                                            if ($result_data['amount'] != '') {
                                                                                                ?>
                                                                                                <div
                                                                                                        class="service_theme_2_price">
                                                                                                    <?php
                                                                                                    echo $currency_symbol . $result_data['amount'];
                                                                                                    ?>
                                                                                                </div>
                                                                                                <?php
                                                                                            }
                                                                                        }
                                                                                        ?>
                                                                                    </div>
                                                                                    <div class="info-board <?php
                                                                                    if ($section_theme == 2) {
                                                                                        echo "padding_zero_imp";
                                                                                    }
                                                                                    ?>">
                                                                                        <h3 <?php if ($section_theme == 2) echo 'class="info-board-theme2-h3"'; ?>><?php echo rep_escape($result_data['service_name']); ?></h3>
                                                                                        <?php
                                                                                        if ($result_data['amount'] != '' && $section_theme == 1) {
                                                                                            ?>
                                                                                            <h3 <?php if ($section_theme == 1) echo 'class="info-board-theme2-h3"'; ?>><?php echo $currency_symbol . $result_data['amount']; ?></h3>
                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                        <!--  <h5>Manging Director</h5>-->
                                                                                        <p><?php echo rep_escape($result_data['description']); ?></p>
                                                                                        <?php

                                                                                        if ($result_data['request_status'] == 1 OR $result_data['read_more'] != '' OR $whatsapp_status == 1) {
                                                                                            ?>
                                                                                            <div style="padding: 10px;">
                                                                                                <form method="post"
                                                                                                      action="">
                                                                                                    <input type="hidden"
                                                                                                           name="service_name"
                                                                                                           value="<?php echo $result_data['service_name']; ?>">
                                                                                                    <?php
                                                                                                    if ($result_data['request_status'] == 1) {
                                                                                                        ?>
                                                                                                        <button
                                                                                                                class="btn service_btn" <?php
                                                                                                        if (isset($_SESSION['client_name'])) {
                                                                                                            ?>
                                                                                                            type="submit" name="btn_service"
                                                                                                            <?php
                                                                                                        } else {
                                                                                                            ?>
                                                                                                            onclick="openServiceModal('<?php echo $result_data['service_name']; ?>')" type="button"
                                                                                                            <?php
                                                                                                        }
                                                                                                        ?>>
                                                                                                            <i class="fa fa-paper-plane"></i>
                                                                                                            Send Enquiry
                                                                                                        </button>
                                                                                                        <?php
                                                                                                    }
                                                                                                    $text = urlencode('I am interested in your ' . $result_data['service_name'] ." ". $product.' which is listed in your digital card please reply to my message.');
                                                                                                    $number2 = $contact_no;
                                                                                                    if ($whatsapp_status == 1) {
                                                                                                        if ($whatsapp_no == '') {
                                                                                                            $number = $contact_no;
                                                                                                        } else {
                                                                                                            $number = $whatsapp_no;
                                                                                                        }


                                                                                                        ?>
                                                                                                        <a href="https://api.whatsapp.com/send?phone=<?php echo $country_code . $number; ?>&text=<?php echo $text ?>"
                                                                                                           target="_blank"
                                                                                                           class="btn whatsapp_btn "><i
                                                                                                                    class="fab fa-whatsapp"
                                                                                                                    aria-hidden="true"></i>
                                                                                                            WhatsApp
                                                                                                        </a>
                                                                                                        <?php
                                                                                                    }
                                                                                                    if ($result_data['call_status'] != 0) {
                                                                                                        ?>
                                                                                                        <a href="tel:<?php echo $country_code . $number2; ?>"
                                                                                                           class="btn call_btn"><i
                                                                                                                    class="fa fa-phone fa-flip-horizontal"
                                                                                                                    aria-hidden="true"></i>
                                                                                                            Call Now
                                                                                                        </a>
                                                                                                        <?php
                                                                                                    }
                                                                                                    if ($result_data['read_more'] != '') {
                                                                                                        ?>
                                                                                                        <a href="<?php echo urlChecker($result_data['read_more']); ?>"
                                                                                                           target="_blank"
                                                                                                           class="btn read_more_btn <?php if ($result_data['request_status'] == 1 && $whatsapp_status == 1) echo 'mar-top-7'; ?> "><i
                                                                                                                    class="fa fa-info-circle"
                                                                                                                    aria-hidden="true"></i> <?php echo $result_data['read_more_txt']; ?>
                                                                                                        </a>
                                                                                                        <?php
                                                                                                    }
                                                                                                    if ($result_data['amount'] != '') {
                                                                                                        if ($result_data['pay_link'] != '') {
                                                                                                            $pay_link = $result_data['pay_link'];
                                                                                                            ?>
                                                                                                            <a href="<?php echo urlChecker(htmlspecialchars($pay_link)); ?>"
                                                                                                               class="btn service_price_btn <?php if ($result_data['request_status'] == 1 OR $whatsapp_status == 1) echo 'mar-top-7'; ?> "><i
                                                                                                                        class="fa fa-shopping-cart"
                                                                                                                        aria-hidden="true"></i>
                                                                                                                Buy Now
                                                                                                            </a>
                                                                                                            <?php
                                                                                                        } else {
                                                                                                            $pay_link = "upi://pay?pa=" . $upi_id . "&pn=98&mc=null&tid=null&tr=" . urlencode($result_data['service_name']) . "&tn=" . urlencode($result_data['service_name']) . "&am=" . trim($result_data['amount']) . "&mam=null&cu=INR&url=null";

                                                                                                            ?>
                                                                                                            <a href="javascript:void(0);"
                                                                                                               onclick="setUpiLink('<?php echo htmlspecialchars($pay_link); ?>','<?php echo htmlspecialchars($result_data['service_name']) ?>')"
                                                                                                               class="btn service_price_btn <?php if ($result_data['request_status'] == 1 OR $whatsapp_status == 1) echo 'mar-top-7'; ?> "><i
                                                                                                                        class="fa fa-shopping-cart"
                                                                                                                        aria-hidden="true"></i>
                                                                                                                Buy Now
                                                                                                            </a>
                                                                                                            <?php
                                                                                                        }
                                                                                                    }
                                                                                                    ?>


                                                                                                </form>

                                                                                            </div>
                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                    </div>
                                                                                </div>
                                                                            </li>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </ul>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <div class="col-lg-12">
                                                                    <div class="col-lg-8 col-lg-offset-2">
                                                                        <div class="text-center no_data_found">
                                                                            <img src="<?php echo $service_not_found; ?>">
                                                                            <h5>We will be adding Our Products Details
                                                                                Shortly!!</h5>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </section>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

</section>


<script type="text/javascript">
    var rowCount = 1;

    function addMoreRows(frm) {
        rowCount++;
        /*var recRow = '<p id="rowCount'+rowCount+'" ><tr><td><div class="input-group" style="display: flex"><span class="input-group-addon" style="width: 49px;">+91</span> <input name="contact_no" type="number" class="form-control" placeholder="Enter Number" required="required"/> &nbsp;&nbsp;</div></td></tr><a href="javascript:void(0);" onclick="removeRow('+rowCount+');"><i class="fa fa-times plus_icon" aria-hidden="true"></i></a></p>';*/
        var recRow = '<p style="display:flex" id="rowCount' + rowCount + '"><tr><td style="display: table; position: relative;border-collapse: separate"><span class="input-group-addon" style="width: 48.95px;">+91</span> <input name="contact_no[]" type="number" class="form-control" placeholder="Enter Number" autofocus required="required" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength = "10"/></td></tr>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="removeRow(' + rowCount + ');"><i class="fa fa-minus plus_icon" aria-hidden="true"></i></a></p>';
        jQuery('#addedRows').append(recRow);
    }

    function removeRow(removeNum) {
        jQuery('#rowCount' + removeNum).remove();
    }
</script>
<script type="text/javascript">
    var rowCount1 = 1;

    function addMoreRows1(frm) {
        rowCount1++;
        /*var recRow = '<p id="rowCount1'+rowCount1+'" ><tr><td><div class="input-group" style="display: flex"><span class="input-group-addon" style="width: 49px;">+91</span> <input name="contact_no" type="number" class="form-control" placeholder="Enter Number" required="required"/> &nbsp;&nbsp;</div></td></tr><a href="javascript:void(0);" onclick="removeRow('+rowCount1+');"><i class="fa fa-times plus_icon" aria-hidden="true"></i></a></p>';*/
        var recRow = '<p style="display:flex" id="rowCount1' + rowCount1 + '"><tr><td style="display: table; position: relative;border-collapse: separate"><span class="input-group-addon" style="width: 49px;"><i class="glyphicon glyphicon-envelope"></i></span> <input name="txt_email[]" type="email" class="form-control" autofocus placeholder="Enter Email" required="required"/></td></tr>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="removeRow1(' + rowCount1 + ');"><i class="fa fa-minus plus_icon" aria-hidden="true"></i></a></p>';
        jQuery('#addedRows1').append(recRow);
    }

    function removeRow1(removeNum) {
        jQuery('#rowCount1' + removeNum).remove();
    }
</script>

<script>
    function emailDiv() {
        document.getElementById('emailDiv').style.display = "block";
        document.getElementById('smsDiv').style.display = "none";
    }

    function smsDiv() {
        document.getElementById('emailDiv').style.display = "none";
        document.getElementById('smsDiv').style.display = "block";
    }
</script>


<!--<script type="text/javascript">
    if (screen.width <= 768 || screen.height == 480) //if 1024x768
        window.location.replace("../<?php /*if(isset($_GET['custom_url'])) echo $_GET['custom_url'];*/ ?>")
</script>-->

<div class="modal fade " id="enquiryModal"
     role="dialog" style="z-index: 9999">
    <div class="modal-dialog modal-md">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header service_modal_title">
                <button type="button" class="close cust-close custom_modal_close"
                        data-dismiss="modal" style="margin-top: -4px;font-size: 30px;">&times;
                </button>
                <h4 class="modal-title cust-model-heading" style="font-size: 14px;"><span class="service_title"></span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" id="msg_alert_danger">
                </div>
                <div class="alert alert-success" id="msg_alert_success">
                </div>
                <div class="form-group text_box">
                    <label class="f_p text_c f_400">Full Name</label>
                    <input type="text" placeholder="Enter Name" class="form-control" name="q_name">
                </div>
                <?php
                if (isset($country) && $country == "101") {
                    ?>
                    <div class="form-group text_box">
                        <label class="f_p text_c f_400">Contact Number</label>
                        <input type="number" placeholder="Contact Number" class="form-control" name="q_contact_no">
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="form-group text_box">
                        <label class="f_p text_c f_400">Email Id</label>
                        <input type="email" placeholder="Enter Email Id" class="form-control" name="q_contact_no">
                    </div>
                    <?php
                }
                ?>
                <div class="form-group text_box" id="open_otp">
                    <label class="f_p text_c f_400">Enter OTP</label>
                    <!-- <input type="number" class="form-control"
                            name="q_send_otp"  placeholder="Enter OTP" autofocus
                            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                            maxlength="4" value="">-->
                    <div class="otp_section">
                        <div class="digit-group">
                            <input class="send_textbox" type="number" id="digit-1" name="q_send_otp[]"
                                   data-next="digit-2"
                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                   maxlength="6"/>
                            <input class="send_textbox" type="number" id="digit-2" name="q_send_otp[]"
                                   data-next="digit-3" data-previous="digit-1"
                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                   maxlength="6"/>
                            <input class="send_textbox" type="number" id="digit-3" name="q_send_otp[]"
                                   data-next="digit-4" data-previous="digit-2"
                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                   maxlength="6"/>
                            <span class="splitter">&ndash;</span>
                            <input class="send_textbox" type="number" id="digit-4" name="q_send_otp[]"
                                   data-next="digit-5" data-previous="digit-3"
                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                   maxlength="6"/>
                            <input class="send_textbox" type="number" id="digit-5" name="q_send_otp[]"
                                   data-next="digit-6" data-previous="digit-4"
                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                   maxlength="6"/>
                            <input class="send_textbox" type="number" id="digit-6" name="q_send_otp[]"
                                   data-previous="digit-5"
                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                   maxlength="6"/>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="service_name">
                <div class="action_btn d-flex align-items-center mt_15 ">
                    <button class="btn_hover btn btn-info app_btn view_more_btn" id="submit_service" type="button">Send
                        Enquiry
                    </button>
                    <button class="btn_hover btn btn-success app_btn view_more_btn" id="verify_otp_btn" type="button">
                        Verify OTP
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>
<div class="modal fade " id="upiPayModal"
     role="dialog" style="z-index: 9999">
    <div class="modal-dialog modal-md">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header service_modal_title">
                <button type="button" class="close cust-close custom_modal_close"
                        data-dismiss="modal" style="margin-top: -4px;font-size: 30px;">&times;
                </button>
                <h4 class="modal-title cust-model-heading" style="font-size: 14px;color: #fff"><span
                            class="service_title"></span></h4>
            </div>
            <div class="modal-body text-center">
                <h4><b>Scan & Pay ...</b></h4>
                <img id="upiQrCode" src=""/>
                <?php
                if ($upi_id != '') {
                    echo '<h4 style="margin-top: 0"><b>' . $upi_id . '</b></h4>';
                }
                ?>
            </div>
        </div>

    </div>
</div>
<script>

    $('.digit-group').find('input').each(function () {
        $(this).attr('maxlength', 1);
        $(this).on('keyup', function (e) {
            var parent = $($(this).parent());

            if (e.keyCode === 8 || e.keyCode === 37) {
                var prev = parent.find('input#' + $(this).data('previous'));

                if (prev.length) {
                    $(prev).select();
                }
            } else if ((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 65 && e.keyCode <= 90) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode === 39) {
                var next = parent.find('input#' + $(this).data('next'));

                if (next.length) {
                    $(next).select();
                } else {
                    if (parent.data('autosubmit')) {
                        parent.submit();
                    }
                }
            }
        });
    });
</script>
<script>
    $('.list-group-item').on('click', function () {
        return true;
    });

    function openServiceModal(service_name) {
        $('input[name=service_name]').val(service_name);
        $('.service_title').text(service_name);
        $('#enquiryModal').modal('show');
    }

    function successMessage(text) {
        Swal.fire({
            showConfirmButton: false,
            title: '<strong>Success!</strong>',
            icon: 'success',
            html:
                '<p>' + text + '</p>',
            showCloseButton: true,
            focusConfirm: false
        })
    }

    $(document).ready(function () {
        $('#msg_alert_danger').hide();
        $('#msg_alert_success').hide();
        $('#open_otp').hide();
        $('#verify_otp_btn').hide();
    });
    $('#get_otp').on('click', function () {
        var full_name = $('input[name=q_name]').val();
        var contact_no = $('input[name=q_contact_no]').val();
        if (full_name != '' && contact_no != '') {
            var dataString = "send_otp=" + encodeURIComponent(contact_no) + "&full_name=" + encodeURIComponent(full_name) + "&country=" + '<?php echo $country; ?>';
            console.log(dataString);
            $.ajax({
                type: 'POST',
                url: '<?php echo FULL_DESKTOP_URL; ?>quick-demo-ajax.php',
                dataType: "json",
                data: dataString,
                beforeSend: function () {
                    $('#get_otp').text('Sending Otp...').attr("disabled", 'disabled');
                    $('input[name=q_name]').attr("disabled", 'disabled');
                    $('input[name=q_contact_no]').attr("disabled", 'disabled');
                },
                success: function (response) {
                    if (response.status == 'ok') {
                        $('#get_otp').css('display', 'none').removeAttr('disabled');
                        $('#verify_otp_btn').show();
                        $('#open_otp').show();
                        $('#msg_alert_success').show().text('OTP has been sent successfully!');
                        $('#msg_alert_danger').hide();
                    } else {
                        $('#get_otp').text('Send Enquiry').removeAttr('disabled');
                        $('#msg_alert_success').hide();
                        $('input[name=q_contact_no]').removeAttr("disabled");
                        $('#msg_alert_danger').show().text('Issue while sending OTP try after some time.');
                    }
                },
                error: function (err) {
                    console.log("AJAX error in request: " + JSON.stringify(err, null, 2));
                }
            });
        }
    });
    $('#verify_otp_btn').on('click', function () {
        var full_name = $('input[name=q_name]').val();
        var contact_no = $('input[name=q_contact_no]').val();
        // var otp_number = $('input[name=q_send_otp]').val();
        var otp_number = $("input[name='q_send_otp[]']")
            .map(function () {
                return $(this).val();
            }).get();
        var service_name = $('input[name=service_name]').val();

        <?php
        if (isset($enquiry_email) && trim($enquiry_email) != '') {
            echo "var enquiry_email='" . urlencode($enquiry_email) . "';";
        } else {
            echo "var enquiry_email='" . urlencode($email) . "';";
        }

        ?>


        if (full_name != '' && contact_no != '' && otp_number != '') {
            var dataString = "contact_no=" + encodeURIComponent(contact_no) + "&full_name=" + encodeURIComponent(full_name) + "&verify_otp=" + otp_number + "&service_name=" + service_name + "&user_id=" +<?php echo $user_id; ?> +"&admin_email=" + enquiry_email + "&admin_contact=" + '<?php echo urlencode($contact_no); ?>' + "&verified_email_status=" + '<?php echo $verified_email_status; ?>';
            $.ajax({
                type: 'POST',
                url: '<?php echo FULL_DESKTOP_URL; ?>quick-demo-ajax.php',
                dataType: "json",
                data: dataString,
                beforeSend: function () {
                    $('#verify_otp_btn').text('Verifying Otp...').attr("disabled", 'disabled');
                    $('input[name=q_name]').attr("disabled", 'disabled');
                    $('input[name=q_contact_no]').attr("disabled", 'disabled');
                },
                success: function (response) {
                    if (response.status == 'ok') {
                        $('.service_btn').removeAttr('onClick');
                        $('.service_btn').attr("name", "btn_service");
                        $('.service_btn').attr("type", "submit");
                        $('input[name=q_name]').removeAttr("disabled").val('');
                        $('input[name=q_contact_no]').removeAttr("disabled").val('');
                        $('#get_otp').text('Send Enquiry');
                        $('#get_otp').css('display', 'block');
                        $('#msg_alert_danger').hide();
                        $('#msg_alert_success').hide();
                        $('#open_otp').hide();
                        $('#verify_otp_btn').hide();
                        $('#enquiryModal').modal('hide');
                        successMessage('Your request for ' + service_name + ' has been sent successfully.');
                    } else {
                        $('#verify_otp_btn').text('Verify Otp').attr('name', 'verify_otp_btn').removeAttr('disabled');
                        $('#msg_alert_success').hide();
                        $('#msg_alert_danger').show().text('OTP mismatch');
                    }
                },
                error: function (err) {
                    console.log("AJAX error in request: " + JSON.stringify(err, null, 2));
                }
            });
        } else {
            $('#msg_alert_success').hide();
            $('#msg_alert_danger').show().text('Please enter OTP');
        }
    });
    $('#submit_service').on('click', function () {
        var full_name = $('input[name=q_name]').val();
        var contact_no = $('input[name=q_contact_no]').val();
        // var otp_number = $('input[name=q_send_otp]').val();
        /*var otp_number = $("input[name='q_send_otp[]']")
            .map(function(){return $(this).val();}).get();*/
        var service_name = $('input[name=service_name]').val();

        <?php
        if (isset($enquiry_email) && trim($enquiry_email) != '') {
            echo "var enquiry_email='" . urlencode($enquiry_email) . "';";
        } else {
            echo "var enquiry_email='" . urlencode($email) . "';";
        }

        ?>


        if (full_name != '' && contact_no != '') {
            var dataString = "contact_no=" + encodeURIComponent(contact_no) + "&full_name=" + encodeURIComponent(full_name) + "&submit_service=true&service_name=" + service_name + "&user_id=" +<?php echo $user_id; ?> +"&admin_email=" + enquiry_email + "&admin_contact=" + '<?php echo urlencode($contact_no); ?>' + "&verified_email_status=" + '<?php echo $verified_email_status; ?>';
            $.ajax({
                type: 'POST',
                url: '<?php echo FULL_DESKTOP_URL; ?>quick-demo-ajax.php',
                dataType: "json",
                data: dataString,
                beforeSend: function () {
                    $('#verify_otp_btn').text('Verifying Otp...').attr("disabled", 'disabled');
                    $('input[name=q_name]').attr("disabled", 'disabled');
                    $('input[name=q_contact_no]').attr("disabled", 'disabled');
                },
                success: function (response) {
                    if (response.status == 'ok') {
                        $('.service_btn').removeAttr('onClick');
                        $('.service_btn').attr("name", "btn_service");
                        $('.service_btn').attr("type", "submit");
                        $('input[name=q_name]').removeAttr("disabled").val('');
                        $('input[name=q_contact_no]').removeAttr("disabled").val('');
                        $('#get_otp').text('Send Enquiry');
                        $('#get_otp').css('display', 'block');
                        $('#msg_alert_danger').hide();
                        $('#msg_alert_success').hide();
                        $('#open_otp').hide();
                        $('#verify_otp_btn').hide();
                        $('#enquiryModal').modal('hide');
                        successMessage('Your request for ' + service_name + ' has been sent successfully.');
                    } else {
                        $('#verify_otp_btn').text('Verify Otp').attr('name', 'verify_otp_btn').removeAttr('disabled');
                        $('#msg_alert_success').hide();
                        $('#msg_alert_danger').show().text('OTP mismatch');
                    }
                },
                error: function (err) {
                    console.log("AJAX error in request: " + JSON.stringify(err, null, 2));
                }
            });
        } else {
            $('#msg_alert_success').hide();
            $('#msg_alert_danger').show().text('Please enter OTP');
        }
    });

</script>
<?php
if ($service_message != "") {
    ?>
    <script>
        successMessage('<?php echo $service_message; ?>');
    </script>
    <?php
}
?>
<?php include "assets/common-includes/footer.php" ?>
<?php include "assets/common-includes/footer_includes.php" ?>

<script>
    function setUpiLink(link, service_name) {
        $('#upiQrCode').attr('src', 'https://chart.googleapis.com/chart?chs=240x240&cht=qr&chl=' + encodeURIComponent(link) + '&choe=UTF-8');
        $('.service_title').text(service_name);
        $('#upiPayModal').modal('show');
    }
</script>
<?php /*include "../assets/common-includes/mobile-desktop-url-changer.php" */ ?>
</body>
</html>
