<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>-->


<!--<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.0.min.js"></script>-->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>
<script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<!--<script>
    $(document).ready(function () {
        $("#testimonial-slider").owlCarousel({
            items: 1,
            itemsDesktop: [1199, 2],
            itemsDesktopSmall: [979, 2],
            itemsTablet: [767, 1],
            pagination: true,
            autoPlay: true
        });
    });
</script>-->

<script>
    function setClipboard(value, text) {
        var tempInput = document.createElement("input");
        tempInput.style = "position: absolute; left: -1000px; top: -1000px";
        tempInput.value = value;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand("copy");
        document.body.removeChild(tempInput);
        if ("copy") {
            var x = document.getElementById("snackbar");
            x.innerHTML = text;
            x.className = "show";
            setTimeout(function () {
                x.className = x.className.replace("show", "");
            }, 3000);
        }
    }
</script>
<script type="text/javascript">
    $(document).bind("contextmenu", function (e) {
        e.preventDefault();
    });
    $(document).keydown(function (e) {
        if (e.which === 123) {
            return false;
        }
    });

</script>


<script>
    $('.load-mobile-redirect').click(function () {
        $(".back-color").css("display", "block");
        $(window).load(function () {
            setTimeout(function () {
                $('.spinner').fadeOut();
                $('.back-color').fadeOut();
                $('.path').fadeOut();
            }, 3000);
        });
    });
    /*$(window).load(function () {
     setTimeout(function () {
     $('.spinner').fadeOut();
     $('.back-color').fadeOut();
     $('.path').fadeOut();
     }, 20);
     });*/
</script>
<!--<script>
    function sendEmail(){
        var email = $("#email").val();
        var dataString =  "email=" + email + "&name="+ <?php /*echo $name; */ ?> + "message="+<?php /*echo $link; */ ?>;
        $.ajax({
            type: "POST",
            url: "theme-change.php",
            data: dataString,
            success: function (html) {
                $("#get_count").html(html);
                return false
            }
        });
    }
</script>-->

<script>
    jQuery(document).ready(function ($) {


        $('form').on('focus', 'input[type=number]', function (e) {
            $(this).on('wheel', function (e) {
                e.preventDefault();
            });
        });


        $('form').on('blur', 'input[type=number]', function (e) {
            $(this).off('wheel');
        });


        $('form').on('keydown', 'input[type=number]', function (e) {
            if (e.which == 38 || e.which == 40)
                e.preventDefault();
        });

    });
</script>


<script>

    var modal = document.getElementById('coverPicModal');
    // Get the image and insert it inside the modal - use its "alt" text as a caption
    var img = document.getElementById('coverImg');
    var modalImg = document.getElementById("img02");
    var captionText = document.getElementById("caption");
    if (img != null) {
        img.onclick = function () {
            modal.style.display = "block";
            modalImg.src = this.src;
            modalImg.alt = this.alt;
            // captionText.innerHTML = this.alt;
        }
    }
    // When the user clicks on <span> (x), close the modal
    modal.onclick = function () {
        img02.className += " out";
        setTimeout(function () {
            modal.style.display = "none";
            img02.className = "modal-content";
        }, 400);

    }

</script>
<script>

    // Get the modal
    var modal = document.getElementById('myModal');

    // Get the image and insert it inside the modal - use its "alt" text as a caption
    var img = document.getElementById('myImg');
    var modalImg = document.getElementById("img01");
    var captionText = document.getElementById("caption");
    if (img != null) {
        img.onclick = function () {
            modal.style.display = "block";
            modalImg.src = this.src;
            modalImg.alt = this.alt;
            //   captionText.innerHTML = this.alt;
        }
    }
    // When the user clicks on <span> (x), close the modal
    modal.onclick = function () {
        img01.className += " out";
        setTimeout(function () {
            modal.style.display = "none";
            img01.className = "modal-content";
        }, 400);

    }


</script>
<!--
<script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>-->


<!--<div class="modal modal_padding animated fadeInUpBig cust-model" id="shareModal" role="dialog">-->
<!--    <div class="modal-dialog modal_margin">-->
<!--        <div class="modal-content modal_width">-->
<!--            <div class="modal-header ">-->
<!--                <button type="button" class="close" data-dismiss="modal">&times;</button>-->
<!--                <h4 class="modal-title cust-model-heading">Share Digital Card</h4>-->
<!--            </div>-->
<!--            <div class="modal-body">-->
<!--                <div class="form-model">-->
<!--                    <ul class="ul-chat-option">-->
<!--                        <li>-->
<!--                            <a-->
<!--                                href="https://api.whatsapp.com/send?phone=&text=--><?php //if(isset($company_name) && $company_name !="") echo "*".trim($company_name)."*"; ?><!--%0A%0APlease%20click%20on%20below%20link%20to%20check%20Digital%20Card.%0A--><?php
//                                echo SHARED_URL.$_GET['custom_url'];
//                                ?><!--"><img-->
<!--                                    class="whats-app-logo"><img src="--><?php //echo $whatsapp_share_model_icon;?><!--"></a>-->
<!--                            <p>Saved</p>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a href="unsaved-whatsapp.php?custom_url=--><?php //echo $custom_url ?><!--"><img-->
<!--                                    class="whats-app-logo"><img src="--><?php //echo $whatsapp_share_model_icon;?><!--"></a>-->
<!--                            <p>Unsaved</p>-->
<!--                        </li>-->
<!--                        <li><a href="add-remove-row.php?custom_url=--><?php //echo $custom_url ?><!--"><img src="--><?php //echo $sms_model_icon;?><!--"></a>-->
<!--                            <p>SMS</p>-->
<!--                        </li>-->
<!--                        <li><a href="qr_code.php?custom_url=--><?php //echo $custom_url ?><!--"><img src="--><?php //echo $qr_code_icon; ?><!--"></a>-->
<!--                            <p>Qr Code</p>-->
<!--                        </li>-->
<!--                        <li><a href="share-email.php?custom_url=--><?php //echo $custom_url ?><!--"><img src="--><?php //echo $email_model_icon;?><!--"></a>-->
<!--                            <p>Email</p>-->
<!--                        </li>-->
<!---->
<!---->
<!--                        <li><a href="copy-link.php?custom_url=--><?php //echo  $_GET['custom_url']; ?><!--"><img-->
<!--                                    src="--><?php //echo $copy_model_icon;?><!--"></a>-->
<!--                            <p>Copy Link</p>-->
<!--                        </li>-->
<!---->
<!--                    </ul>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="modal-footer">-->
<!--                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->

<!--<div class="modal modal_padding animated fadeInUpBig cust-model" id="emailModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content modal_width">
            <div class="modal-header ">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title cust-model-heading">Send mail</h4>
            </div>
            <div class="modal-body">
                    <form id="myform" class="form-horizontal" method="post" action="">
                    <div class="form-group" style="display: flex;">
                        <div class="col-sm-10">
                            <input type="email" class="form-control" id="email" placeholder="Enter email" name="txt_email">
                        </div>
                        <div class="col-sm-2">
                            <button type="submit" class="btn btn-success" name="sendEmail">Send</button>
                        </div>
                    </div>
                    </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal modal_padding animated fadeInUpBig cust-model" id="smsModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content modal_width">
            <div class="modal-header ">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title cust-model-heading">Send SMS</h4>
            </div>
            <div class="modal-body">
                    <form class="form-horizontal" action="/action_page.php">
                    <div class="form-group" style="display: flex;">
                        <label class="control-label col-sm-2" for="email">Mobile no:</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" placeholder="Enter Number" name="number">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-success">Send</button>
                        </div>
                    </div>
                    </form>
            </div>
            <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>-->


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
                        <label>Amount</label>&nbsp;<label class="red">*</label>&nbsp;&nbsp;&nbsp;<label
                            class="amountReguired" id="amountReguired1"></label>
                        <input class="form-control amt1" type="number" name="amt" required="required">
                        <label>Remark (Optional)</label>
                        <textarea class="form-control remark1" name="remark" rows="3"></textarea>

                        <div class="form-group pay_now_btn">
                            <!--<button type="button" class="form-control btn btn-primary">Pay Now</button>-->
                            <a href="#" class="btn btn-primary pay_now_modal1" name="pay_now_modal">pay now
                            </a><label class="upiIdReguired text-center" id="upi_no1"></label>
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

<script type="text/javascript">
    $(".pay_now_modal1").click(function () {
        //alert("hi");
        var remark = $(".remark1").val();
        var amount = $(".amt1").val();
        var upi_no = '<?php echo $upi_mobile_no; ?>';
        if (amount == '') {
            $("#amountReguired1").text('Please enter amount');
        } else if (upi_no == 0) {
            $("#upi_no1").html('upi id is not configured');
        } else {
            /*console.log("upi://pay?pa=Q85477279@ybl&pn=9768904980&mc=null&tid=null&tr=" + remark + "&tn=This%20is%20test%20payment&am=" + amount + "&mam=null&cu=INR&url=null");*/
            location.href = "upi://pay?pa=<?php echo $upi_id; ?>&pn=<?php echo $upi_mobile_no;  ?>&mc=null&tid=null&tr=" + remark + "&tn=" + remark + "&am=" + amount + "&mam=null&cu=INR&url=null";
        }
        return false;
    });
</script>
<script>
    jQuery("#carousel").owlCarousel({
        autoplay: true,
        lazyLoad: true,
        loop: true,
        items: 1,
        margin: 20,
        /*
         animateOut: 'fadeOut',
         animateIn: 'fadeIn',
         */
        responsiveClass: true,
        autoHeight: true,
        autoplayTimeout: 7000,
        smartSpeed: 800,
        nav: true,
        responsive: {
            0: {
                items: 1
            },

            600: {
                items: 1
            },

            1024: {
                items: 1
            },

            1366: {
                items: 1
            }
        }
    });
</script>
