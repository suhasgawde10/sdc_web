<?php
$errorPreview = false;
$errorPreviewMessage = "";

if (isset($_SESSION['id'])) {
    $session_id = $_SESSION['id'];
/*
    $get_id = $manage->getSpecificUserProfile($session_id);
    $id = $get_id['id'];*/
}
if (isset($_POST['submit'])) {
    $result = $manage->validateCustomUrl(trim($_POST['custom_url_preview']));
    if ($result) {
        $errorPreview = true;
        echo "<script>alert('custom url already exist')</script>";
      /*  $errorPreviewMessage .="custom url already exist";*/
    }
    if (!$errorPreview) {
        $update_custom_url = $manage->updateCustomUrl($_POST['custom_url_preview'], $_GET['custom_url_id']);
        if ($update_custom_url) {
            $_SESSION['custom_url'] = $_POST['custom_url_preview'];
            /*header('location:basic-user-info.php');*/
            echo '<META HTTP-EQUIV="Refresh" Content="0; URL=' . $_SERVER['PHP_SELF'] . '">';
            $toName = "";
            $toEmail = "" . $_SESSION['email'] . "";
            $subject = "Upadted Custom Url";
            $message = "Dear " . $_SESSION['name'] . ".\n";
            $message .= "You have Successfully updated url.\n";
            $message .= "Please Click on below link to Open Digital Card..\n";
            $message .= SHARED_URL. $_SESSION['custom_url'];
            $sendMail = $manage->sendMail($toName, $toEmail, $subject, $message);
            $send_sms = $manage->sendSMS($_SESSION['contact'], $message);
        }
    }
}
if(isset($_POST['cancel_button'])){
    echo '<META HTTP-EQUIV="Refresh" Content="0; URL=' . $_SERVER['PHP_SELF'] . '">';
}
?>
<div class="col-md-12">
    <div class="row">
        <div class="col-md-10 padding_zero padding_zero_both">
            <div class="form-group form-float">
                <div class="form-line preview_holder">
                    <form action="" method="post">
                        <?php /*if ($errorPreview) {
                            */?><!--
                            <div class="alert alert-danger">
                                <?php /*if (isset($errorPreviewMessage)) echo $errorPreviewMessage; */?>
                            </div>
                        <?php
/*                        } else if (!$errorPreview && $errorPreviewMessage != "") {
                            */?>
                            <div class="alert alert-success">
                                <?php /*if (isset($errorPreviewMessage)) echo $errorPreviewMessage; */?>
                            </div>
                        --><?php
/*                        }
                        */?>
                        <div class="info_circle help">
                            <div class="info-box-url" style="display: none;">
                                <a href="#" class="close-button">×</a>
                                <img src="assets/images/preview.png">
                            </div>
                            <a class="help-button" href="#" title="Click to know more"><i class="fas info_circle_color fa-info-circle"></i></a>
                        </div>
                        <input id="myInput" name="custom_url_preview" class="form-control preview_padding"
                               placeholder="<?php echo SHARED_URL. $_SESSION['custom_url']; ?>"
                               value="<?php if (isset($_GET['custom_url_id'])){ echo $_SESSION['custom_url']; }else{ echo SHARED_URL.$_SESSION['custom_url'];} ?>">
                        <div class="edit_icon">
                            <?php if (isset($_GET['custom_url_id'])) { ?>
                                <button class="right_button" name="cancel_button"><i class="fas wrong_button fa-times"></i></button>
                                <button class="right_button" type="submit" name="submit"><i class="fas right_check fa-check"></i></button>
                            <?php
                            } else { ?>
                                <a class="fas edit_color fa-pencil-alt" href="<?php echo $_SERVER['PHP_SELF']; ?>?custom_url_id=<?php echo $session_id; ?>"></a>
                            <?php
                            } ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2 preview_btn_margin">
        <!--<input type="button" <img src='assets/images/copy.png'>-->
        <!--<input type="image" onclick="myFunction()" onmouseout="outFunc()" src="assets/images/copy.png" alt="Tool Tip">-->
        <a title="copy URL" class="fas copy_button fa-copy" onclick="myFunction()" onmouseout="outFunc()" > copy URL</a>
        <a title="Preview" target="_blank" class="preview_button"
           href="../m/index.php?custom_url=<?php echo $_SESSION['custom_url']; ?>">Live Preview</a>
    </div>
</div>
