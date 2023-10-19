<?php
if (isset($_GET['custom_url'])) {
    if($parent_id !=""){
        $custom_url = $getParentData['custom_url'];
    }else{
        $custom_url = $_GET['custom_url'];
    }

  //  $get_result = $manage->mdm_getDigitalCardDetails("client",$custom_url);
  //  $get_data = $manage->mdm_getDigitalCardDetails("client_review",$custom_url);
}
?>
<div class="bhoechie-tab-content margin-padding-remover <?php if (!$alreadyActiveContent and $ProfileSectionStatus ) {
    $alreadyActiveContent = true;
    echo "active";
} ?>">
    <section>
        <div class="content-main  background-theme-cust active">
            <div class="all-main-heading">
                <span class="text-color-p">Company Info</span>
                <?php /*if (isset($_SESSION['email'])) { */?><!-- <a title="Add Service"
                                                             class="add-icon-color fas fa-pencil-alt"
                                                             href=<?php /*echo FULL_WEBSITE_URL */?>."user/testimonial.php">&nbsp;&nbsp;Edit</a>
                --><?php /*} */?>
            </div>
            <div class="cust-coverlay">
                <div class="bank-up-div">
                    <div class="card">
                        <ul class="nav nav-tabs" role="tablist">
                            <?php
                            /*                            if ($get_client_status != null) {
                                                            if (isset($_GET['custom_url']) && $get_client_status['digital_card'] == 1) { */ ?>
                            <li role="presentation" class="active"><a href="#about" aria-controls="profile" role="tab" data-toggle="tab">About</a></li>
                            <?php /*}
                            } */ ?><!--
                            --><?php
                            /*                            if ($get_client_review_status != null) {
                                                            if (isset($_GET['custom_url']) && $get_client_review_status['digital_card'] == 1) { */ ?>
                            <li role="presentation"><a href="#our-mission" aria-controls="home" role="tab"data-toggle="tab">Our Mission</a>
                            </li>
                            <li role="presentation"><a
                                    href="#our-profile"
                                    aria-controls="home"
                                    role="tab"
                                    data-toggle="tab">Profile</a>
                            </li>
                            <!-- --><?php /*}
                            } */ ?>
                        </ul>

                        <div class="bank-up-div">
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="about">
                                    <div class="client-main scrollbar style-11">
                                        <div class="container-fluid">
                                            <?php
                                            if ($about_company != "") {
                                                ?>
                                                <div class="company_content text-color-p">
                                                    <?php echo rep_escape($about_company); ?>
                                                </div>
                                            <?php } else { ?>
                                                <div class="text-center no_data_found1">
                                                    <img src="<?php echo $about_us_data_uri; ?>">
                                                    <h5>About company will Appear Soon in this Section.</h5>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="our-mission">
                                    <div class="client-main scrollbar style-11">
                                        <div class="container-fluid">
                                            <?php
                                            if ($our_mission != ""){
                                            ?>
                                            <div class="company_content text-color-p">
                                                <?php
                                                echo rep_escape($our_mission); ?>
                                            </div>
                                            <?php } else { ?>
                                                <div class="text-center no_data_found1">
                                                    <img src="<?php echo $our_mission_data_uri; ?>">
                                                    <h5>Our Mission will Appear Soon in this Section.</h5>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="our-profile">
                                    <div class="client-main scrollbar style-11">
                                        <div class="container-fluid">
                                            <?php
                                            if ($company_profile != "") {
                                                $profilePath = FULL_WEBSITE_URL."user/uploads/$email/profile/$company_profile";
                                            } else {
                                                $profilePath = $company_profile;
                                            }
                                            if (check_url_exits($profilePath)) {
                                                ?>
                                                <iframe style="width: 100%; height: 100vh; border: unset;"
                                                        src="<?php echo $profilePath; ?>"></iframe>
                                            <?php } else { ?>
                                                <div class="text-center no_data_found1">
                                                    <img src="<?php echo $company_pdf_data_uri ?>">
                                                    <h5>Company Profile will Appear Soon in this Section.</h5>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>


    </section>
</div>