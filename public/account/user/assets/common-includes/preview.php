<?php
//include '../../../whitelist.php';
$errorPreview = false;
$errorPreviewMessage = "";

if (isset($_POST['submit'])) {
    if ($_POST['custom_url_preview'] === $session_custom_url_is) {
        $url = $_SERVER['PHP_SELF'];
        header('location:' . $url);
    } else {
        $result = $manage->validateCustomUrl(trim($_POST['custom_url_preview']));
        if ($result) {
            $errorPreview = true;
            echo "<script>alert('custom url already exist')</script>";
            /* $errorPreviewMessage .="custom url already exist"; */
        }
        $removeCustomSpace = str_replace(' ', '-', $_POST['custom_url_preview']);
        if (!$errorPreview) {
            $update_custom_url = $manage->updateCustomUrl($removeCustomSpace);
            $addLogFile = $manage->addCustomUrlLog($removeCustomSpace);
            if ($addLogFile) {
                if (isset($_SESSION['create_user_status']) && $_SESSION['create_user_status'] == true) {
                    $_SESSION['create_user_custom_url'] = $removeCustomSpace;
                } else {
                    $_SESSION['custom_url'] = $removeCustomSpace;
                }
                $session_custom_url_is = $removeCustomSpace;
                /*header('location:basic-user-info.php');*/
                $toEmail = "" . $session_email . "";
                $subject = "Successfully changed the custom URL.";
                $sms_message = "Dear " . $session_name . ",\n";
                $sms_message .= "Your new digital card link is ready.\n";
                $sms_message .= SHARED_URL . $session_custom_url_is;
                $message = '<table style="width: 100%">
<tr>
<td colspan="2">
 <div style=" font-size: 18px;">
                    <p>Dear <span style="color:blue;">' . ucwords($session_name) . '</span>,<p>
                    <p> This mail is regarding successful updations of custom url link.</p>
                 <a href="' . SHARED_URL . $session_custom_url_is . '" style="' . $btn . ';background: #db5ea5 !important;width: 100%;color: #ffffff;border-radius: 4px;font-size: 16px;padding: 10px 0;">Open Your Digital Card</a>
                    <p>To do any changes in your "Share Digital Card " click on to below button to login to our web portal or you can change your details from mobile application.</p>
                </div>
</td>
</tr>
<tr><td colspan="2" style="text-align:center">
<a href="http://sharedigitalcard.com/login.php" style="' . $btn . ';color:white; border-radius: 4px;"><img src="http://sharedigitalcard.com/user/assets/images/laptop.png" style="width: 19%;display: inline-block;vertical-align: middle;padding-right: 5px;color: white;">Click To Login</a>
                   <a target="_blank" href="https://play.google.com/store/apps/details?id=sharedigitalcard.com.digitalcard" style="padding: 0px;display: inline-block;vertical-align: middle;"><img src="https://sharedigitalcard.com/assets/img/playstore.png"
                                                                                          style="width: 135px" alt="digital card app"></a>
</td></tr>
<tr>
<td colspan="2" style=" font-size: 18px;">
<p> For any query email us on <a href="mailto:support@sharedigitalcard.com" class="payment">support@sharedigitalcard.com</a></p>
</td>
</tr>
<tr><td colspan="2" style="padding: 10px;background: #e6e6e6;height: 115px;">
 <div style="width: 85%;margin: 0 auto;">
                <div style=" width: 100%;margin: 0 auto;">
                    <div style="text-align:center">
                        <a href="https://www.facebook.com/sharedigitalcard/"><img src="http://sharedigitalcard.com/user/assets/images/fb.png"></a>
<a href="https://www.instagram.com/sharedigitalcard/"><img src="http://sharedigitalcard.com/user/assets/images/insta.png"></a>
<a href="https://www.youtube.com/watch?v=6T9Ia_2rsig&list=PLg1QyEHQ9MYYBRDxWqLrWLCyvJlSheqTh"><img src="http://sharedigitalcard.com/user/assets/images/yt.png"></a>
                    </div>
                </div>

            </div>
</td></tr>
</table>';
               // $sendMail = $manage->sendMail(MAIL_FROM_NAME, $toEmail, $subject, $message);
               // $send_sms = $manage->sendSMS($session_contact_no, $sms_message);
                $url = $_SERVER['PHP_SELF'];
                header('location:' . $url);
            }
        }
    }


}
if (isset($_POST['cancel_button'])) {
    $url = $_SERVER['PHP_SELF'];
    header('location:' . $url);
}
?>
<div class="col-md-12 col-sm-12">
    <div class="row">
        <div class="col-md-10 col-sm-9 custom_input padding_zero padding_zero_both">
            <div class="form-group form-float">
                <div class="form-line preview_holder">
                    <form action="" method="post" id="custom_url">
                        <div class="info_circle help">
                            <div class="info-box-url" style="display: none;">
                                <a href="#" class="close-button">×</a>
                                <img src="assets/images/preview.png">
                            </div>
                            <a class="help-button" href="#" title="Click to know more"><i
                                    class="fas info_circle_color fa-info-circle"></i></a>
                        </div>
                        <input type="text" id="myInput" onkeypress="return RestrictSpace()"
                               name="custom_url_preview" class="form-control preview_padding"
                               placeholder="<?php echo SHARED_URL . $session_custom_url_is; ?>"
                               value="<?php if (isset($_GET['custom_url_id'])) {
                                   echo $session_custom_url_is;
                               } else {
                                   echo SHARED_URL . $session_custom_url_is;
                               } ?>" <?php if (!isset($_GET['custom_url_id'])) echo 'style="background: white"'; ?>>

                        <div class="edit_icon">
                            <?php if (isset($_GET['custom_url_id'])) { ?>
                                <!--<button class="right_button" name="cancel_button"></button>-->
                                <a href="<?php echo $_SERVER['PHP_SELF']; ?>"><i
                                        class="fas wrong_button1 fa-times"></i></a>
                                <button class="right_button" type="submit" name="submit"><i
                                        class="fas right_check1 fa-check"></i></button>
                            <?php
                            } else {
                                ?>
                                <a class="fas edit_color fa-pencil-alt"
                                   href="<?php echo $_SERVER['PHP_SELF']; ?>?custom_url_id=<?php echo $id; ?>"></a>
                            <?php
                            } ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-sm-3 preview_btn_margin">
        <!--
        onclick="setClipboard('https://sharedigitalcard.com/m/index.php?custom_url=<?php echo $session_custom_url_is; ?>')"
        -->
        <a title="copy URL" class="copy_button " data-toggle="modal" data-target="#copyUrlModal"><i
                class="fas fa-copy"></i> Copy URL</a>
        <!-- <a title="Preview" target="_blank" class="preview_button"
           href="<?php //echo SHARED_URL . $session_custom_url_is; ?>"><i class="fa fa-eye"></i>
            Preview</a> -->
            <a title="Preview" class="copy_button " data-toggle="modal" data-target="#myPreviewModal"><i
                class="fas fa-copy"></i> Preview</a>
            
    </div>
</div>

<div class="modal fade" id="myPreviewModal" role="dialog">
        <div class="modal-dialog modal-md">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header cust-upi-madal">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Preview For</h4>
                </div>
                <div class="modal-body">
                    <form id="upi_form_validation" method="POST" action="">
                        <div class="">
                            <ul class="preview-ul">
                                <li>
                                    <a href="<?php echo SHARED_URL . $session_custom_url_is; ?>" target="_blank"><img src="assets/images/card.png" class="img img-responsive preview-img"> Preview Digital Card</a>
                                </li>
                                <li>
                                <a href="https://miniiwebsite.com/index.php?custom_url=<?php echo $session_custom_url_is; ?>" target="_blank"><img src="assets/images/website.png"  class="img img-responsive preview-img"> Preview Website</a>
                                </li>
                            </ul>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<div class="modal zoomIn" id="copyUrlModal" role="dialog">
    <div class="modal-dialog modal-md">
        <!-- Modal content-->
        <div class="modal-content" style=" border-radius: 10px;">
            <!--<div class="modal-header cust-upi-madal">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <?php /*if (isset($alreadySaved) && $alreadySaved) {
                    */
            ?>
                    <h4 class="modal-title">Update UPI Details</h4>
                    <?php
            /*                } else {
                                */
            ?>
                    <h4 class="modal-title">Add UPI Details</h4>
                    <?php
            /*                }
                            */
            ?>
            </div>-->
            <div class="modal-body" style="overflow-y: auto;">
                <div class="body">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-9">
                                <h4><img src="assets/images/copy-link.png" style="width: 8%"> Share Link</h4>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="close" data-dismiss="modal"
                                        style="margin-top: 10px;">&times;</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 " style="margin: 10px 0 0 0;border-bottom: 1px solid #fff">
                        <div class="row sharelink">
                            <div class="col-md-10">
                                <h5>Public Link (Without Bank Info)</h5>

                                <p>Bank Details will not be visible in public link</p>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-default"
                                        onclick="setClipboard('<?php echo SHARED_URL . $session_custom_url_is; ?>')">
                                    Copy Link
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 ">
                        <div class="row sharelink">

                            <div class="col-md-10">
                                <h5>Private Link (Secure - With Bank Info)</h5>

                                <p>Bank Details will be visible anyone on the internet with this link can view</p>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-default" <?php
                                if (!isset($_SESSION['dealer_login_type'])) {
                                    ?> onclick="copyPrivateLink()" <?php } ?> >Copy Link
                                </button>
                            </div>
                            <?php
                            if (isset($_SESSION['dealer_login_type'])) {
                                ?>
                                <div class="accountUser text-center">
                                    <h5>Only Accessable To Account User</h5>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                    <!--<div class="col-md-12 col-sm-12 md-15 p-r-0">
                        <input type="text" id="myInput"
                               name="custom_url_preview" class="form-control preview_btn "
                               value="https://sharedigitalcard.com/m/index.php?custom_url=<?php /*echo $session_custom_url_is; */ ?>">

                        <a title="copy URL" class="copy_button_modal" onclick="setClipboard('https://sharedigitalcard.com/m/index.php?custom_url=<?php /*echo $session_custom_url_is; */ ?>')"><i class="fas fa-copy"></i> Copy URL</a>
                    </div>
                    <div class="col-md-12 col-sm-12 " style="margin: 12px 0">
                        <div class="row">
                            <div class="col-md-1">
                                <div class="copModalIcon">
                                    <i class="fa fa-globe" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <select class="form-control">
                                    <option>Anyone with the link</option>
                                    <option>Restricted</option>
                                </select>
                            </div>
                            <div class="col-md-4">

                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 text-right">
                        <button class="btn btn-primary" data-dismiss="modal">Done </button>
                    </div>-->

                </div>
            </div>
        </div>
    </div>
</div>