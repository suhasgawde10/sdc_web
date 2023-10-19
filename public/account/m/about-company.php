<?php

include "../controller/ManageMobileCard.php";
$manage = new ManageMobileCard();
include_once '../sendMail/sendMail.php';

$error = false;
$errorMessage = "";

if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
    $link = "https";
else
    $link = "http";
$link .= "://";

$link .= $_SERVER['HTTP_HOST'];

$link .= $_SERVER['REQUEST_URI'];

if (isset($_GET['custom_url'])) {

    $get_data = $manage->mdm_getDigitalCardDetailsOFUser($_GET['custom_url']);
    $parent_id = $get_data['parent_id'];
    if($parent_id !=""){
        $getParentData = $manage->getSpecificUserProfileById($parent_id);
        $custom_url = $getParentData['custom_url'];
    }else{
        $custom_url = $_GET['custom_url'];
    }

 /*   if ($get_data != null) {
        $email = $get_data['email'];
        $about_company = $get_data['about_company'];
        $our_mission = $get_data['our_mission'];
        $company_profile = $get_data['company_profile'];
    }*/


    if($parent_id !=""){
        $default_user_id = $getParentData['user_id'];
        $about_company = $getParentData['about_company'];
        $company_name = $getParentData['company_name'];
        $our_mission = $getParentData['our_mission'];
        $email = $getParentData['email'];
        $company_profile = $getParentData['company_profile'];
    }else{
       $user_id = $get_data['user_id'];
        $contact_no = $get_data['contact_no'];
        $about_company = $get_data['about_company'];
      $company_name = $get_data['company_name'];
        $our_mission = $get_data['our_mission'];
        $email = $get_data['email'];
        $company_profile = $get_data['company_profile'];
    }
}


function fetch_all_data($result)
{
    $all = array();
    while($thing = mysqli_fetch_array($result)) {
        $all[] = $thing;
    }
    return $all;
}


function rep_escape($string){
    return str_replace(['\r\n','\r','\n','\\'],'',$string);
}

$get_section = $manage->getSectionName($user_id);
if ($get_section != null) {
    $profile = $get_section['profile'];
    $services = $get_section['services'];
    $our_service = $get_section['our_service'];
    $gallery = $get_section['gallery'];
    $images = $get_section['images'];
    $videos = $get_section['videos'];
    $clients = $get_section['clients'];
    $client_name = $get_section['client_name'];
    $client_review_tab = $get_section['client_review'];
    $team = $get_section['team'];
    $our_team = $get_section['our_team'];
    $bank = $get_section['bank'];
    $payment = $get_section['payment'];
    $basic_info = $get_section['basic_info'];
    $company_info = $get_section['company_info'];
} else {
    $profile = "Profile";
    $services = "Services";
    $our_service = "Our Services";
    $gallery = "Gallery";
    $images = "Images";
    $videos = "Videos";
    $clients = "Clients";
    $client_name = "Clients";
    $client_review_tab = "Client's Reviews";
    $team = "Team";
    $our_team = "Our Team";
    $bank = "Bank";
    $payment = "Payment";
    $basic_info = "Basic Info";
    $company_info = "Company Info";
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "assets/common-includes/header_includes.php" ?>
    <style>
        .nav-tabs>li{
            width: 33.3%;
        }
        .nav>li>a{
            padding: 10px;
        }
    </style>
</head>
<body style="background-image: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);">

<div>
    <div class="container height-100">
        <div class="row sms-header">
            <div class="col-sm-12 text-center">
                <a style="float: left" href="<?php echo get_url_param_for_mobile('index.php') ?>"><i
                        class="fas fa-chevron-left"></i></a>
                <span class="text-center">Company Info</span>
            </div>
        </div>


        <div class="row">
            <div class="card">
                <ul class="nav nav-tabs sms-tab" role="tablist">
                    <li role="presentation" <?php if(!isset($_GET['tab']) or $_GET['tab']=="about"){?>class="active"<?php } ?>><a href="#about"
                                                              aria-controls="home"
                                                              role="tab"
                                                              data-toggle="tab">About</a>
                    </li>
                    <li role="presentation" <?php if(isset($_GET['tab']) && $_GET['tab']=="misssion"){?>class="active"<?php } ?>><a href="#mission"
                                               aria-controls="home"
                                               role="tab"
                                               data-toggle="tab">Our Mission</a>
                    </li>
                    <li role="presentation" <?php if(isset($_GET['tab']) && $_GET['tab']=="profile"){?>class="active"<?php } ?>><a href="#profile"
                                               aria-controls="home"
                                               role="tab"
                                               data-toggle="tab">Profile</a>
                    </li>
                </ul>
                <div class="bank-up-div">
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane <?php if(!isset($_GET['tab']) or $_GET['tab']=="about"){?>active<?php } ?>" id="about">
                            <div class="col-md-12">
                                <?php if($about_company!=""){ echo rep_escape($about_company); ?>
                                <?php }else{ ?>
                                    <div class="text-center no_data_found">
                                        <img src="<?php echo $about_us_data_uri ?>">
                                        <h5>About company will Appear Soon in this Section.</h5>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane <?php if(isset($_GET['tab']) && $_GET['tab']=="misssion"){?>active<?php } ?>" id="mission">
                            <div class="col-md-12">
                                <?php if($our_mission!=""){ echo rep_escape($our_mission); ?>
                                <?php }else{ ?>
                                    <div class="text-center no_data_found">
                                        <img src="<?php echo $our_mission_data_uri; ?>">
                                        <h5>Our Mission will Appear Soon in this Section.</h5>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane <?php if(isset($_GET['tab']) && $_GET['tab']=="profile"){?>active<?php } ?>" id="profile">
                            <div class="col-md-12">
                                <?php
                                if($company_profile!=""){
                                    $profilePath = FULL_WEBSITE_URL."user/uploads/$email/profile/$company_profile";
                                }else{
                                    $profilePath = $company_profile;
                                }
                                if(check_url_exits($profilePath)) {
                                    ?>
                                    <iframe style="width: 100%; height: 100vh; border: unset;"
                                            src="<?php echo $profilePath ?> "></iframe>
                                <?php }else{ ?>
                                    <div class="text-center no_data_found">
                                        <img src="<?php echo $company_pdf_data_uri; ?>">
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
    <?php include "assets/common-includes/footer.php" ?>
</div>

<?php include "assets/common-includes/footer_includes.php" ?>

</body>
</html>

