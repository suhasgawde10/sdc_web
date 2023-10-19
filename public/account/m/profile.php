<?php

$get_data = $manage->mdm_getDigitalCardDetailsOFUser($custom_url);
if ($get_data != null) {
    $name = $get_data['name'];
    $designation = $get_data['designation'];
    $contact_no = $get_data['contact_no'];
    $altr_contact_no = $get_data['altr_contact_no'];
    $email = $get_data['email'];
    $gender = $get_data['gender'];
    $img_name = $get_data['img_name'];
    $website = $get_data['website_url'];
    $linked_in = $get_data['linked_in'];
    $youtube = $get_data['youtube'];
    $facebook = $get_data['facebook'];
    $twitter = $get_data['twitter'];
    $instagram = $get_data['instagram'];
    $map_link = $get_data['map_link'];
    $address = $get_data['address'];
    $user_id = $get_data['user_id'];
    $profilePath = FULL_WEBSITE_URL."user/uploads/" . $email . "/profile/" . $img_name;
}



if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
    $link = "https";
else
    $link = "http";
$link .= "://";

$link .= $_SERVER['HTTP_HOST'];

$link .= $_SERVER['REQUEST_URI'];


/*
$getUserId = $manage->mdm_getDigitalCardDetailsOFUser($custom_url);
if ($getUserId != null) {

}*/

/*$getDetails = $manage->getGatewayPaymentDetails($user_id);
if ($getDetails != null) {
    $upi_id = $getDetails['upi_id'];
    $upi_mobile_no = $getDetails['upi_mobile_no'];

}*/

?>


<div class="profile-img ">
    <img class="img-circle" src="<?php if ($img_name == "" && $gender == "Male") {
        echo FULL_WEBSITE_URL."user/uploads/male_user.png";
    } elseif ($img_name == "" && $gender == "Female") {
        echo FULL_WEBSITE_URL."user/uploads/female_user.png";
    } else {
        echo $profilePath;
    } ?>">
</div>
<div class="whats-app">
    <a target="_blank"
       data-target="#paymentModelProfile" data-toggle="modal"><img
            class="whats-app-logo" src="<?php echo FULL_MOBILE_URL; ?>assets/images/payment-icon/digitalCardico.png"></a>
    <a data-target="#shareModal" data-toggle="modal"><img class="share-logo" src="<?php echo FULL_MOBILE_URL; ?>assets/images/icon/share.png"></a>
</div>
<div class="client-name">
    <h1><?php if (isset($name)) echo $name; ?><span><img class="blue-tick"
                                                         src="<?php echo FULL_MOBILE_URL; ?>assets/images/icon/blue_tick.png"></span></h1>

    <h3><?php if (isset($designation)) echo $designation; ?></h3>
</div>

<div class="modal share_modal_padding animated fadeInUpBig cust-model" id="paymentModelProfile" role="dialog">
    <div class="modal-dialog modal_margin">
        <div class="modal-content modal_width">
            <div class="modal-header ">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title cust-model-heading">Pay through UPI</h4>
            </div>
            <div class="modal-body">
                <div class="form-model">
                    <form method="post" action="">
                        <label>Amount</label>
                        <input class="form-control amt" name="amt">
                        <label>Remark</label>
                        <textarea class="form-control remark" name="remark" rows="3"></textarea>

                        <div class="form-group pay_now_btn">
                            <!--<button type="button" class="form-control btn btn-primary">Pay Now</button>-->
                            <a href="#" class="btn btn-primary pay_now_modal" name="pay_now_modal">pay now
                            </a>
                            <!--<button type="submit" class="btn btn-primary" name="pay_now_modal" onclick="location.href='upi://pay?pa=<?php /*echo $upi_id; */ ?>&pn=<?php /*echo $upi_mobile_no; */ ?>&mc=null&tid=null&tr=test101&tn=This%20is%20test%20payment&am=10&mam=null&cu=INR&url=null'">pay now</button>-->
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
            </div>
        </div>

    </div>
</div>


<div class="modal modal_padding animated fadeInUpBig cust-model" id="shareModal" role="dialog">
    <div class="modal-dialog modal_margin">
        <div class="modal-content modal_width">
            <div class="modal-header ">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title cust-model-heading">Share Digital Card</h4>
            </div>
            <div class="modal-body">
                <div class="form-model">
                    <ul class="ul-chat-option">
                        <li><a class="fb-share-button facebook"
                               data-href="<?php echo SHARED_URL.$_GET['custom_url']; ?>"
                               data-layout="button_count"> <img src="<?php echo FULL_MOBILE_URL; ?>assets/images/icon/iconfinder_Icon-1_3064839.png"></a>
                            <p>Facebook</p>

                        </li>
                        <li>
                            <a target="_blank"
                               href="https://api.whatsapp.com/send?phone=&text=Hello%20guys%2C%0APlease%20click%20on%20below%20link%20to%20check%20Digital%20Card%21%20%3A%29%0A%0A<?php
                               echo SHARED_URL.$_GET['custom_url'];
                               ?>"><img
                                    class="whats-app-logo"><img src="<?php echo FULL_MOBILE_URL; ?>assets/images/icon/whatsapp1.png"></a>
                            <p>Whatsapp</p>
                        </li>
                        <li><a onclick="setClipboard('<?php echo SHARED_URL.$_GET['custom_url']; ?>')"><img
                                    src="<?php echo FULL_MOBILE_URL; ?>assets/images/icon/copy.png"></a>
                            <p>Copy Link</p>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <!--  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
            </div>
        </div>

    </div>
</div>
<!--<div class="fb-share-button"
     data-href="http://sharedigitalcard.com/m/index.php?custom_url=kubictechnology"
     data-layout="button_count">
</div>-->
<div id="fb-root"></div>
<script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
<script type="text/javascript">
    $(".pay_now_modal").click(function () {
        var remark = $(".remark").val();
        var amount = $(".amt").val();
       location.href = "upi://pay?pa=<?php echo $upi_id; ?>&pn=<?php echo $upi_mobile_no;  ?>&mc=null&tid=null&tr=" + remark + "&tn="+ remark + "&am=" + amount + "&mam=null&cu=INR&url=null";
        return false;
    });
</script>
