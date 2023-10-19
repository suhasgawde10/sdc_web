
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
if(isset($_POST['btn_service'])){
    $service_name = $_POST['service_name'];
    $subject = "Enquiry For service ".$service_name;
    if(isset($enquiry_email) && trim($enquiry_email) !=''){
        $enquiry_email= $enquiry_email;
    }else{
        $enquiry_email=$email;
    }
    $email_message = "You have a customer request for service ".$service_name."<br>Please contact with the customer Name: ".$_SESSION['client_name']." & Contact Number: ".$_SESSION['client_contact'];
if($verified_email_status == 1){
    $sendmail = $manage->sendMail(MAIL_FROM_NAME,$enquiry_email,$subject,$email_message);
}
    $date_time = date('Y-m-d h:i:a s');
    //$sms_message = $_SESSION['client_name']." " . $_SESSION['client_contact'] . " enquired for ".$service_name."\n(".date('d-M-Y').")\nsharedigitalcard";
    $sms_message = "Dear%20Sir%2C%20%0A%0A".$_SESSION['client_name'] . " " . $_SESSION['client_contact']."%20inquired%20for%20" . $service_name . "%20service%20from%20your%20Digital%20Card.%20Thank%20you%20for%20your%20prompt%20attention%20to%20this%20matter.%20%0A%0ABest%20regards%2C%20%0AShare%20Digital%20Card";
    //$sendsms = $manage->sendSMS($contact_no,$sms_message);
    $send_sms = $manage->sendSMSWithTemplateId($contact_no, $sms_message,TEMPLATE_LEAD);
    $update_count = $manage->updateUserLeadCount("Add",$user_id);
    $insert_data = array('user_id'=>$user_id,'client_name'=>$_SESSION['client_name'],'contact_no'=>$_SESSION['client_contact'],'service_name'=>$service_name,'created_date'=>date('Y-m-d'));
    $insert = $manage->insert($manage->serviceRequestTable,$insert_data);
    if($insert){
        $service_message = "Your request for " . $service_name. " has been sent successfully.";
    }
}

if($ServiceSectionStatus != 1){
    $redirect = FULL_DESKTOP_URL . "product" . get_full_param();
    header('Location: '.$redirect);
    die();
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>

    <title>Service - <?php echo $name; ?> - <?php echo $designation; ?> -<?php echo $_SERVER['HTTP_HOST']; ?></title>

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
                                <?php
                                /*                                if ($get_service_status != null) {
                                                                    if (isset($_GET['custom_url']) && $get_service_status['digital_card'] == 1) {
                                                                        $alreadyActiveSet = true;
                                                                        $alreadyActiveContent = true;*/

                                include "assets/common-includes/services.php";
                                /*   }
                               }
                               */ ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

</section>




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
                        data-dismiss="modal" style="margin-top: -4px;font-size: 30px;">&times;</button>
                <h4 class="modal-title cust-model-heading" style="font-size: 14px;"><span class="service_title"></span></h4>
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
                if(isset($country) && $country =="101") {
                    ?>
                    <div class="form-group text_box">
                        <label class="f_p text_c f_400">Contact Number</label>
                        <input type="number" placeholder="Contact Number" class="form-control" name="q_contact_no">
                    </div>
                    <?php
                }else {
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
                                <input class="send_textbox" type="number" id="digit-1" name="q_send_otp[]" data-next="digit-2"
                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                       maxlength="6" />
                                <input class="send_textbox" type="number" id="digit-2" name="q_send_otp[]" data-next="digit-3" data-previous="digit-1"
                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                       maxlength="6" />
                                <input class="send_textbox" type="number" id="digit-3" name="q_send_otp[]" data-next="digit-4" data-previous="digit-2"
                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                       maxlength="6" />
                                <span class="splitter">&ndash;</span>
                                <input class="send_textbox" type="number" id="digit-4" name="q_send_otp[]" data-next="digit-5" data-previous="digit-3"
                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                       maxlength="6" />
                                <input class="send_textbox" type="number" id="digit-5" name="q_send_otp[]" data-next="digit-6" data-previous="digit-4"
                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                       maxlength="6" />
                                <input class="send_textbox" type="number" id="digit-6" name="q_send_otp[]" data-previous="digit-5"
                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                       maxlength="6" />
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="service_name">
                    <div class="action_btn d-flex align-items-center mt_15 " >
                            <button class="btn_hover btn btn-info app_btn view_more_btn" <?php echo (isset($get_serv_reviews[0]) && $get_serv_reviews[0] == "0") ? 'id="submit_service"':'id="get_otp"'; ?> type="button">Send Enquiry</button>
                            <button class="btn_hover btn btn-success app_btn view_more_btn" id="verify_otp_btn" type="button">Verify OTP</button>
                        <a href="javascript:void(0);" id="resent_otp_btn">Try to resent OTP.</a>
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
                        data-dismiss="modal" style="margin-top: -4px;font-size: 30px;">&times;</button>
                <h4 class="modal-title cust-model-heading" style="font-size: 14px;color: #fff"><span class="service_title"></span></h4>
            </div>
            <div class="modal-body text-center">
                <h4><b>Scan & Pay ...</b></h4>
                <img id="upiQrCode" src=""  />
                <?php
                if($upi_id !='') {
                    echo '<h4 style="margin-top: 0"><b>'.$upi_id.'</b></h4>';
                }
                ?>
            </div>
        </div>

    </div>
</div>

<script>

    $('.digit-group').find('input').each(function() {
        $(this).attr('maxlength', 1);
        $(this).on('keyup', function(e) {
            var parent = $($(this).parent());

            if(e.keyCode === 8 || e.keyCode === 37) {
                var prev = parent.find('input#' + $(this).data('previous'));

                if(prev.length) {
                    $(prev).select();
                }
            } else if((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 65 && e.keyCode <= 90) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode === 39) {
                var next = parent.find('input#' + $(this).data('next'));

                if(next.length) {
                    $(next).select();
                } else {
                    if(parent.data('autosubmit')) {
                        parent.submit();
                    }
                }
            }
        });
    });
</script>
<script>
    $('.list-group-item').on('click',function(){
        return true;
    });
    function openServiceModal(service_name){
        $('input[name=service_name]').val(service_name);
        $('.service_title').text(service_name);
        $('#enquiryModal').modal('show');
    }
    function successMessage(text){
        Swal.fire({
            showConfirmButton: false,
            title: '<strong>Success!</strong>',
            icon: 'success',
            html:
                '<p>'+text+'</p>',
            showCloseButton: true,
            focusConfirm: false
        })
    }
    $(document).ready(function(){
        $('#msg_alert_danger').hide();
        $('#msg_alert_success').hide();
        $('#open_otp').hide();
        $('#verify_otp_btn,#resent_otp_btn').hide();
    });
    $('#get_otp').on('click',function(){
        var full_name = $('input[name=q_name]').val();
        var contact_no = $('input[name=q_contact_no]').val();
        if(full_name !='' && contact_no !='') {
            var dataString = "send_otp=" + encodeURIComponent(contact_no) + "&full_name=" + encodeURIComponent(full_name)+"&country="+'<?php echo $country; ?>';
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
                        $('#get_otp').css('display','none').removeAttr('disabled');
                        $('#verify_otp_btn,#resent_otp_btn,#open_otp').show();
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
    $('#resent_otp_btn').on('click',function(){
        var full_name = $('input[name=q_name]').val();
        var contact_no = $('input[name=q_contact_no]').val();
        if(full_name !='' && contact_no !='') {
            var dataString = "send_otp=" + encodeURIComponent(contact_no) + "&full_name=" + encodeURIComponent(full_name)+"&country="+'<?php echo $country; ?>';
           //  console.log(dataString);
            $.ajax({
                type: 'POST',
                url: '<?php echo FULL_DESKTOP_URL; ?>quick-demo-ajax.php',
                dataType: "json",
                data: dataString,
                beforeSend: function () {
                    $('#resent_otp_btn').text('Sending Otp...').attr("disabled", 'disabled');
                    $('input[name=q_name]').attr("disabled", 'disabled');
                    $('input[name=q_contact_no]').attr("disabled", 'disabled');
                },
                success: function (response) {
                    $('#resent_otp_btn').text('Try to resent OTP.').removeAttr('disabled');
                    if (response.status == 'ok') {
                        $('#resent_otp_btn').removeAttr('disabled');
                        $('#verify_otp_btn,#resent_otp_btn,#open_otp').show();
                        $('#msg_alert_success').show().text('OTP has been re-sent successfully!');
                        $('#msg_alert_danger').hide();
                    } else {
                        $('#msg_alert_success').hide();
                        $('#msg_alert_danger').show().text('Issue while sending OTP try after some time.');
                    }
                },
                error: function (err) {
                    console.log("AJAX error in request: " + JSON.stringify(err, null, 2));
                }
            });
        }
    });
    $('#verify_otp_btn').on('click',function(){
        var full_name = $('input[name=q_name]').val();
        var contact_no = $('input[name=q_contact_no]').val();
        // var otp_number = $('input[name=q_send_otp]').val();
        var otp_number = $("input[name='q_send_otp[]']")
            .map(function(){return $(this).val();}).get();
        var service_name = $('input[name=service_name]').val();

        <?php
        if(isset($enquiry_email) && trim($enquiry_email) !=''){
        echo "var enquiry_email='".urlencode($enquiry_email)."';";
        }else{
        echo "var enquiry_email='".urlencode($email)."';";
        }

        ?>


        if(full_name !='' && contact_no !='' && otp_number !='') {
            var dataString = "contact_no=" + encodeURIComponent(contact_no) + "&full_name=" + encodeURIComponent(full_name)+"&verify_otp="+otp_number+"&service_name="+ service_name +"&user_id="+<?php echo $user_id; ?> +"&admin_email="+enquiry_email+"&admin_contact="+'<?php echo urlencode($contact_no); ?>'+"&verified_email_status="+'<?php echo $verified_email_status; ?>';
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
                        $('.service_btn').attr("name","btn_service");
                        $('.service_btn').attr("type","submit");
                        $('input[name=q_name]').removeAttr("disabled").val('');
                        $('input[name=q_contact_no]').removeAttr("disabled").val('');
                        $('#get_otp').text('Send Enquiry');
                        $('#get_otp').css('display','block');
                        $('#msg_alert_danger').hide();
                        $('#msg_alert_success').hide();
                        $('#open_otp').hide();
                        $('#verify_otp_btn').hide();
                        $('#enquiryModal').modal('hide');
                        successMessage('Your request for '+service_name+' has been sent successfully.');
                    } else {
                        $('#verify_otp_btn').text('Verify Otp').attr('name','verify_otp_btn').removeAttr('disabled');
                        $('#msg_alert_success').hide();
                        $('#msg_alert_danger').show().text('OTP mismatch');
                    }
                },
                error: function (err) {
                    console.log("AJAX error in request: " + JSON.stringify(err, null, 2));
                }
            });
        }else{
            $('#msg_alert_success').hide();
            $('#msg_alert_danger').show().text('Please enter OTP');
        }
    });
    $('#submit_service').on('click',function(){
        var full_name = $('input[name=q_name]').val();
        var contact_no = $('input[name=q_contact_no]').val();
        // var otp_number = $('input[name=q_send_otp]').val();
        /* var otp_number = $("input[name='q_send_otp[]']")
            .map(function(){return $(this).val();}).get();*/
        var service_name = $('input[name=service_name]').val();

        <?php
        if(isset($enquiry_email) && trim($enquiry_email) !=''){
        echo "var enquiry_email='".urlencode($enquiry_email)."';";
        }else{
        echo "var enquiry_email='".urlencode($email)."';";
        }

        ?>


        if(full_name !='' && contact_no !='') {
            var dataString = "contact_no=" + encodeURIComponent(contact_no) + "&full_name=" + encodeURIComponent(full_name)+"&submit_service=true&service_name="+ service_name +"&user_id="+<?php echo $user_id; ?> +"&admin_email="+enquiry_email+"&admin_contact="+'<?php echo urlencode($contact_no); ?>'+"&verified_email_status="+'<?php echo $verified_email_status; ?>';
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
                        $('.service_btn').attr("name","btn_service");
                        $('.service_btn').attr("type","submit");
                        $('input[name=q_name]').removeAttr("disabled").val('');
                        $('input[name=q_contact_no]').removeAttr("disabled").val('');
                        $('#get_otp').text('Send Enquiry');
                        $('#get_otp').css('display','block');
                        $('#msg_alert_danger').hide();
                        $('#msg_alert_success').hide();
                        $('#open_otp').hide();
                        $('#verify_otp_btn').hide();
                        $('#enquiryModal').modal('hide');
                        successMessage('Your request for '+service_name+' has been sent successfully.');
                    } else {
                        $('#verify_otp_btn').text('Verify Otp').attr('name','verify_otp_btn').removeAttr('disabled');
                        $('#msg_alert_success').hide();
                        $('#msg_alert_danger').show().text('OTP mismatch');
                    }
                },
                error: function (err) {
                    console.log("AJAX error in request: " + JSON.stringify(err, null, 2));
                }
            });
        }else{
            $('#msg_alert_success').hide();
            $('#msg_alert_danger').show().text('Please enter OTP');
        }
    });

</script>
<?php
if($service_message !="") {
    ?>
    <script>
        successMessage('<?php echo $service_message; ?>');
    </script>
<?php
}
?>
<script>
    function setUpiLink(link,service_name){
        $('#upiQrCode').attr('src','https://chart.googleapis.com/chart?chs=240x240&cht=qr&chl='+encodeURIComponent(link)+'&choe=UTF-8');
        $('.service_title').text(service_name);
        $('#upiPayModal').modal('show');
    }
</script>
<?php include "assets/common-includes/footer.php" ?>
<?php include "assets/common-includes/footer_includes.php" ?>


<?php /*include "../assets/common-includes/mobile-desktop-url-changer.php" */ ?>
</body>
</html>
