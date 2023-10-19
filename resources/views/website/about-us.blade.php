@extends('layouts.web-main')
@section('main-container')
    <div class="innerpage-banner" id="home" style="background: url({{url('/')}}/dist/assets/img/bread/breadcrumbs2.jpg) no-repeat center; background-size: cover;">
        <div class="inner-page-layer">
            <h5>About us</h5>
            <h6><a href="index.php">Home</a>&nbsp;/&nbsp;<span>About us</span></h6>
        </div>
    </div>
    <div class="about-area ptb--60">
        <div class="container">
            <div class="row display_about_img d-flex">
                <div class="col-xs-12 col-lg-6 col-md-6 col-sm-6">
                    <div class="about-left-img">
                        <img src="{{ url('/') }}/dist/assets/img/about/Screenshot%20(1).png" alt="free digital business card maker" title="free digital business card maker">
                    </div>
                </div>

                <div class="col-xs-12 col-lg-6 col-md-6 col-sm-6">
                    <div class="about-heading">
                        <h3><strong>What is digital card ?</strong></h3>
                    </div>
                    <div class="about-content ptb--50">
                        <p>
                            A <b>digital card is a virtual digital representation</b> of your profile with details
                            including your personal information, bank details, contact details and many more. Digital Card
                            <b>allows the world to get in touch with you in a more simpler and convinient way</b>. It gives you the luxury
                            of flexibility, customization and privacy controls for your profile
                            to <b>keep your information more secure and safe</b>. Enabling everyone to share their information under <b>one link</b>.
                        </p>
                        <p>The benefit of digital card is the process of <b>sharing the card through social platforms</b> hence
                            even other person if interested can interact.</p>

                    </div>
                    <div class="about-heading">
                        <h3><strong>About Company</strong></h3>
                    </div>
                    <div class="about-content ptb--20">
                        <p><b>Kubic Technology</b> is a registered Mumbai-based software and website development company and IT consulting services provider. We work on diverse projects ranging from simple information systems and websites to complex enterprise type architectures, desktop or web-enabled applications, traditional n-tier and service-oriented architectures. we also provide end-products to customers. this is one of our best product <b>"Share Digital Card". Our mission is to make digital India</b>.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
