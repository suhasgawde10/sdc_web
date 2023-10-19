@extends('layouts.web-main')
@section('main-container')
    <div class="innerpage-banner" id="home"
     style="background: url({{url('/')}}/dist/assets/img/bread/breadcrumbs3.jpg) no-repeat center; background-size: cover;">
    <div class="inner-page-layer">
        <h5>Contact Us</h5>
        <h6><a href="index.php">Home</a>&nbsp;/&nbsp;<span>Contact Us</span></h6>
    </div>
</div>
<div class="container contact-area ptb--70">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="contact-form panel panel-success">
            <div class="custom_panel_heading">
                We will glad to hear from you!
            </div>
            <div class="panel-body">
                <form action="" method="post" enctype="multipart/form-data">
                 
                    <span id="body_lbl_name" class="form-control-names"><i class="fa fa-user"></i>&nbsp;Full Name</span>
                    <input type="text" name="name" placeholder="Full Name" required="required">
                    <span id="body_lbl_email_id" class="form-control-names"><i class="fa fa-envelope"></i>&nbsp;Email ID</span>
                    <input type="text" name="email" placeholder="Email Id" required="required">
                    <span id="body_lbl_mobile" class="form-control-names"><i class="fa fa-phone"></i>&nbsp;Contact Number</span>
                    <input type="text" name="contact_no" placeholder="Contact Number" required="required">
                    <span id="body_lbl_msg" class="form-control-names"><i class="fa fa-comment"></i>&nbsp;Message</span>
                    <textarea name="msg" id="msg" placeholder="Your Message" required="required"></textarea>
                    <!-- Google reCAPTCHA box -->
                    <div class="g-recaptcha" data-sitekey="6LeSbAEVAAAAAD7x5O1HkY9NtBkEThRTBK1lfHDI"></div>
                    <input type="submit" value="Send" name="send_detail" id="send">
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="contact_info">
            <div class="s-info">
                <i class="fa fa-map-marker"></i>

                <div class="meta-content">
                    <span>	709 / 7th floor, Lotus Business Park, Ram Bagh Road, Opp Dal Mill Compound, Near HP petrol pump, S. V. Road, Malad West, Mumbai, Maharashtra 400064.</span>
                </div>
            </div>

            <div class="s-info">
                <i class="fa fa-mobile"></i>

                <div class="meta-content">
                    <span>+91 99677 83583 / +91 97689 04980.</span>
                </div>
            </div>
         
            <div class="s-info">
                <i class="fa fa-clock-o" aria-hidden="true"></i>

                <div class="meta-content">
                    <span>Support Call Time: 11:00 to 6:00 (Monday to Friday)</span>

                </div>
            </div>

            <div class="s-info">
                <i class="fa fa-paper-plane"></i>

                <div class="meta-content">
                    <span>support@sharedigitalcard.com</span>

                </div>
            </div>
            <div class="c-social">
                <div class="social_icon">
                    <a href="https://www.facebook.com/sharedigitalcard/" target="_blank" class="fa fa-facebook"></a>
                    <a href="https://www.youtube.com/channel/UCQ4o_M5CqMUA9vnZZVfyvQw" target="_blank"
                       class="fa fa-youtube"></a>
                    <a href="https://www.instagram.com/sharedigitalcard/" target="_blank" class="fa fa-instagram"></a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
