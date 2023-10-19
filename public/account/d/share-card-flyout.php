<div class="flyout" xmlns="http://www.w3.org/1999/html">
    <div class="flyout_modal_open">
        <span class="flyout_modal_close_btn"></span>
        <div class="flyout_modal_open_boom">
            <div class="col-lg-12">
                <div class="body">
                    <!-- Nav tabs -->
                        <div class="card">
                                <div class="body" id="review_data">
                                    <div class="col-md-12 text-center m-t-5 m-b-0">
                                        <div class="row">
                                            <h3>Share Digital Card</h3>
                                            <hr>
                                        </div>
                                    </div>
                                    <div class="form-model">
                                        <ul class="ul-chat-option">
                                            <li>
                                                <a target="_blank" href="https://api.whatsapp.com/send?phone=&text=<?php if(isset($company_name) && $company_name !="") echo "*".urlencode(trim($company_name))."*"; ?>%0A%0APlease%20click%20on%20below%20link%20to%20check%20Digital%20Card.%0A<?php echo $final_link; ?>"><img class="whats-app-logo"><img src="<?php echo $whatsapp_share_model_icon;?>"></a>
                                                <p>Saved</p>
                                            </li>
                                            <li>
                                                <a href="<?php echo get_url_param_full_url('unsaved-whatsapp.php'); ?>"><img
                                                        class="whats-app-logo"><img src="<?php echo $whatsapp_share_model_icon;?>"></a>
                                                <p>Unsaved</p>
                                            </li>
                                            <!-- <li><a href="<?php echo get_url_param_full_url('add-remove-row.php'); ?>"><img src="<?php echo $sms_model_icon;?>"></a>
                                                <p>SMS</p>
                                            </li> -->
                                            <li><a href="<?php echo get_url_param_full_url('qr_code.php'); ?>"><img src="<?php echo $qr_code_icon; ?>"></a>
                                                <p>Qr Code</p>
                                            </li>
                                            <!-- <li><a href="<?php echo get_url_param_full_url('share-email.php') ?>"><img src="<?php echo $email_model_icon;?>"></a>
                                                <p>Email</p>
                                            </li> -->
                                           <!-- <li class="other_apps"><a href="javascript:void(0);" class="share-btn"><img
                                                        src="<?php /*echo FULL_MOBILE_URL */?>assets/images/icon/digital-marketing.png"></a>
                                                <p>Other apps</p>
                                            </li>-->

                                        </ul>
                                    </div>
                                    <?php
                                    if(!$user_expired_status && !$validToken) {
                                        ?>
                                        <hr>
                                        <div class="card">
                                            <div class="bank-up-div">

                                                <div class=" sms-body">
                                                    <form method="post" action="">

                                                        <!--   <div class="col-md-12 col-sm-12 col-xs-12 " style="margin: 10px 0 10px 0;">
                                                        <div class="row sharelink" >
                                                            <div class="col-md-9 col-xs-12">
                                                                <h5>Public Link (Without Bank Info)</h5>
                                                                <p>Bank Details will not be visible in public.</p>
                                                            </div>
                                                            <div class="col-md-3 text-right">
                                                                <button class="btn btn-default" type="button" onclick="setClipboard('<?php /*echo $final_link; */
                                                        ?>','URL is on the Clipboard try to paste it!')">Copy Link</button>
                                                            </div>
                                                        </div>
                                                    </div>-->
                                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                                            <div class="row sharelink ">
                                                                <div class="col-md-8 col-xs-12">
                                                                    <h5>Share With Bank Details</h5>
                                                                    <p>The link will be sent to Register contact number,
                                                                        Once Link Generated, it will be valid for 7
                                                                        days.</p>
                                                                </div>
                                                                <div class="col-md-4 text-right col-xs-12">
                                                                    <!-- <div class="custom_flyout_div">
                                                                         <img src="assets/images/banking.png">
                                                                     </div>-->
                                                                    <button class="btn btn-default get_link"
                                                                            type="button" name="get_link"><i
                                                                            class="fa fa-link" aria-hidden="true"></i>
                                                                        Get Link
                                                                    </button>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="alert alert-danger" id="msg_alert_danger_flyout">
                                                        </div>
                                                        <div class="alert alert-success" id="msg_alert_success_flyout">
                                                        </div>

                                                    </form>
                                                </div>

                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>


                        </div>



                </div>
            </div>
        </div>

    </div>
</div>
<script>
    const flyoutModalButton = document.querySelector('.flyout_modal_button');
    const flyoutModalOpen = document.querySelector('.flyout_modal_open');
    const flyoutModalClose = document.querySelector('.flyout_modal_close_btn');

    flyoutModalButton.addEventListener('click', function() {
        flyoutModalOpen.classList.add('active');
    });

    flyoutModalClose.addEventListener('click', function() {
        flyoutModalOpen.classList.remove('active');
    })
</script>

<script>
    $(document).ready(function(){
        $('#msg_alert_danger_flyout').hide();
        $('#msg_alert_success_flyout').hide();
    });
    $('.get_link').on('click',function(){
        var dataString = "get_link=true"+"&custom_url="+'<?php echo urlencode($custom_url); ?>';
        $.ajax({
            type: 'POST',
            url: '<?php echo FULL_WEBSITE_URL ?>review-flyout-ajax.php',
            dataType: "json",
            data: dataString,
            beforeSend: function () {
                $('.get_link').text('Getting...').attr("disabled", 'disabled');
            },
            success: function (response) {
                console.log(response.msg);
                if (response.status == 'ok') {
                    $('.get_link').text('Get link').removeAttr("disabled");
                    $('#msg_alert_danger_flyout').hide();
                    $('#msg_alert_success_flyout').show().text(response.msg);
                } else {
                    $('.get_link').text('Get link').removeAttr('disabled');
                    $('#msg_alert_success_flyout').hide();
                    $('#msg_alert_danger_flyout').show().text('Issue while getting private link please try after some time .');
                }
            },
            error: function (err) {
                $('.get_link').text('Get link').removeAttr('disabled');
                console.log("AJAX error in request: " + JSON.stringify(err, null, 2));
            }
        });
    });

</script>
<script>
/*
    const shareBtn = document.querySelector('.share-btn');
    //const ogBtnContent = shareBtn.textContent;
const ogBtnContent = shareBtn ? shareBtn.textContent : null;
//    const title = document.querySelector('h1').textContent;
    const url = document.querySelector('link[rel=canonical]') &&
        document.querySelector('link[rel=canonical]').href ||
        window.location.href;

    if (navigator.share) {

    } else {
       $('.other_apps').hide();
    }
    shareBtn.addEventListener('click', () => {
        if (navigator.share) {
            navigator.share({
                title: 'Hi, Please click on below link to check digital card',
                text: '',
                url: window.location.href
            }).then(() => {
            })
                .catch(err => {
                });
        } else {
            $('.other_apps').hide();
        }
    });
*/

    function showMessage(element, msg) {
        element.textContent = msg;
        setTimeout(() => {
            element.textContent = ogBtnContent;
        }, 2000);
    }
</script>
