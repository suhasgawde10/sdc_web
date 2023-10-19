<?php
require_once "controller/ManageApp.php";
$manage = new ManageApp();


$error = false;
$errorMessage = "";

if (isset($_POST['request_send'])) {
    if (isset($_POST['name']) && $_POST['name'] == "") {
        $error = true;
        $errorMessage = "Enter your name";
    } else {
        $name = $_POST['name'];
    }
    if (isset($_POST['contact']) && $_POST['contact'] == "") {
        $error = false;
        $errorMessage = "Enter your contact";
    } else {
        $contact = $_POST['contact'];
    }
    if (!$error) {
        //$message = "Name is :- " . $name . "\nContact is :- " . $contact . "";
        $message = "Name is :- " . $name . " Contact is :- " . $contact . "";
        /*$kubicContact = 8070139237;*/
        //$sendSms = $manage->sendSMS($global_contact, $message);
        $sendSms = $manage->sendSMSWithTemplateId($global_contact, $message, TEMPLATE_REQUEST_CALL);
        //        $sendSms = true;
        if ($sendSms) {
            $error = false;
            $errorMessage = "Message has been send";
        }
    }
}

?>


<div class="open_in_app" data-target="#sideModalTR" data-toggle="modal">
    <a href="#"><i class="fa fa-phone"></i> Request to call </a>
</div>
<!-- Side Modal Top Right -->

<!-- To change the direction of the modal animation change .right class -->
<div class="modal animated bounceInRight" id="sideModalTR" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <!-- Add class .modal-side and then add class .modal-top-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal_popup modal-side modal-top-right modal_width" role="document">
        <div class="modal-content modal_content_border">
            <div class="modal-header text-center modal_header_padding">
                <h4 class="modal-title w-100" id="myModalLabel">Request to call</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>
            </div>
            <div class="modal-body modal_body_padding">
                <div class="contact-form text-right">
                    <form action="" method="post" id="subForm">
                        <?php if ($error) {
                        ?>
                            <div class="alert_padding alert alert-danger">
                                <?php if (isset($errorMessage)) echo $errorMessage; ?>
                            </div>
                        <?php
                        } else if (!$error && $errorMessage != "") {
                        ?>
                            <div class="alert_padding alert alert-success">
                                <?php if (isset($errorMessage)) echo $errorMessage; ?>
                            </div>
                        <?php
                        }
                        ?>
                        <!--<input type="text" name="name" class="form_input_height" placeholder="Enter Your Name"
                               required="required">
                        <input type="number" class="form_input_height" name="contact" placeholder="Enter Your Number"
                               required="required">-->
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">person</i>
                            </span>

                            <div class="form-line">

                                <!--
                                                        <asp:TextBox ID="txt_username" CssClass="form-control" runat="server" placeholder="User name" required autofocus></asp:TextBox>-->
                                <input type="text" class="form-control" placeholder="Enter Your Name" name="name" autofocus required="required">
                            </div>
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">phone</i>
                            </span>

                            <div class="form-line">
                                <!--  <asp:TextBox ID="txt_password" CssClass="form-control" runat="server" placeholder="Password" required autofocus></asp:TextBox>-->
                                <input type="number" class="form-control" placeholder="Enter Your Number" name="contact" autofocus required="required">

                            </div>
                        </div>

                        <div class="input-group captchaData">
                            <div class="input-group-prepend captchaLable">
                                <span class="input-group-text ebcaptchatext" id="basic-addon3"></span>
                            </div>
                            <div class="form-line line-remove">
                                <input type="number" class="form-control" placeholder="Captcha Code" autofocus required="required" id="ebcaptchainput" aria-describedby="basic-addon3">
                            </div>
                            <!--                            <input type="text" class="form-control" id="basic-url" >-->
                        </div>

                        <!--<label id="ebcaptchatext"></label>
                        <input type="text" class="textbox" id="ebcaptchainput"/>-->
                        <!--<textarea name="msg" id="msg" placeholder="Your Message "></textarea>-->
                </div>
            </div>
            <div class="modal_footer_padding modal-footer">
                <button type="submit" name="request_send" class="btn btn-primary" id="request_send" disabled><i class="fa fa-phone"></i>&nbsp;Call
                    Now
                </button>
            </div>
            </form>
        </div>
    </div>
</div>
<!-- Side Modal Top Right -->