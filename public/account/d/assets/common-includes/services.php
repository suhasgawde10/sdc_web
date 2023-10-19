<?php

if (isset($_GET['custom_url'])) {
    if ($parent_id != "") {
        $custom_url = $getParentData['custom_url'];
    } else {
        $custom_url = $_GET['custom_url'];
    }
    $section_id = 1;
    $get_result = $manage->mdm_getDigitalCardDetails("service", $custom_url, "0");
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


?>

<div class="bhoechie-tab-content margin-padding-remover active">
    <section>
        <div class="content-main  background-theme-cust">
            <div class="service_header">
                <div class="all-main-heading">
                    <span class="text-color-p"><?php echo $our_service; ?></span>
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
                                                            <div class="service_theme_2_price">
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
                                                            <form method="post" action="">
                                                                <input type="hidden" name="service_name"
                                                                       value="<?php echo $result_data['service_name']; ?>">
                                                                <?php
                                                                if ($result_data['request_status'] == 1) {
                                                                    ?>
                                                                    <button class="btn service_btn" <?php
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
                                                                        <i class="fa fa-paper-plane"></i> Send Enquiry
                                                                    </button>
                                                                    <?php
                                                                }
                                                                $text = urlencode('I am interested in your ' . $result_data['service_name'] ." ". $services.' which is listed in your digital card please reply to my message.');
                                                                $number2 = $contact_no;
                                                                if ($whatsapp_status == 1) {
                                                                    if ($whatsapp_no == '') {
                                                                        $number = $contact_no;
                                                                    } else {
                                                                        $number = $whatsapp_no;
                                                                    }
                                                                    ?>
                                                                    <a href="https://api.whatsapp.com/send?phone=<?php echo $country_code . $number; ?>&text=<?php echo $text ?>"
                                                                       target="_blank" class="btn whatsapp_btn "><i
                                                                                class="fab fa-whatsapp"
                                                                                aria-hidden="true"></i>
                                                                        WhatsApp </a>
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
                                                                if ($result_data['call_status'] != 0) {
                                                                    ?>
                                                                    <a href="tel:<?php echo $country_code . $number2; ?>"
                                                                       class="btn call_btn"><i
                                                                                class="fa fa-phone fa-flip-horizontal"
                                                                                aria-hidden="true"></i>
                                                                        Call Now </a>
                                                                    <?php
                                                                }
                                                                if ($result_data['amount'] != '') {
                                                                    if ($result_data['pay_link'] != '') {
                                                                        $pay_link = $result_data['pay_link'];
                                                                        ?>
                                                                        <a href="<?php echo urlChecker(htmlspecialchars($pay_link)); ?>"
                                                                           class="btn service_price_btn <?php if ($result_data['request_status'] == 1 OR $whatsapp_status == 1) echo 'mar-top-7'; ?> "><i
                                                                                    class="fa fa-shopping-cart"
                                                                                    aria-hidden="true"></i> Buy Now </a>
                                                                        <?php
                                                                    } else {
                                                                        $pay_link = "upi://pay?pa=" . $upi_id . "&pn=98&mc=null&tid=null&tr=" . urlencode($result_data['service_name']) . "&tn=" . urlencode($result_data['service_name']) . "&am=" . trim($result_data['amount']) . "&mam=null&cu=INR&url=null";

                                                                        ?>
                                                                        <a href="javascript:void(0);"
                                                                           onclick="setUpiLink('<?php echo htmlspecialchars($pay_link); ?>','<?php echo htmlspecialchars($result_data['service_name']) ?>')"
                                                                           class="btn service_price_btn <?php if ($result_data['request_status'] == 1 OR $whatsapp_status == 1) echo 'mar-top-7'; ?> "><i
                                                                                    class="fa fa-shopping-cart"
                                                                                    aria-hidden="true"></i> Buy Now </a>
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
                                    ?>
                                </ul>
                                <?php
                            } else {
                                ?>
                                <div class="col-lg-12">
                                    <div class="col-lg-8 col-lg-offset-2">
                                        <div class="text-center no_data_found">
                                            <img src="<?php echo $service_not_found; ?>">
                                            <h5>We will be adding Our Service Details Shortly!!</h5>
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