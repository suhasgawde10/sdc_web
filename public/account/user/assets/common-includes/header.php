<?php
include_once('whitelist.php');
include_once('session_includes.php');
// $main_site = false;

// if (strpos($_SERVER['HTTP_HOST'], 'sharedigitalcard.com') !== false) {
//     $main_site = true;
// }
// else if(strpos($_SERVER['HTTP_HOST'], 'localhost') !== false){
//     $main_site = true;
// }
$diff = null;
date_default_timezone_set("Asia/Kolkata");

function like_match($pattern, $subject)
{
    $pattern = str_replace('%', '.*', preg_quote($pattern, '/'));
    return (bool)preg_match("/^{$pattern}$/i", $subject);
}
if (isset($_SESSION['id'])) {
    if (isset($_SESSION['create_user_status']) && $_SESSION['create_user_status'] == true) {
        $id = $_SESSION["create_user_id"];
    } else {
        $id = $_SESSION["id"];
    }
}

if(isset($_SESSION["dealer_id"]) && $_SESSION["dealer_id"] !=""){

}else{
    $_SESSION["dealer_id"] = "";
}

$date = date("Y-m-d");

$getExpiryDate = $manage->getUserExpiryDate();

if ($getExpiryDate != null) {
    $expiry_date = $getExpiryDate['expiry_date'];
    $earlier = new DateTime("$date");
    $later = new DateTime("$expiry_date");
    $diff = $later->diff($earlier)->format("%a");
}


if($_SESSION['type']=="User"){
    $getSubscription = $manage->displaySubscriptionDetails();
}

if(basename($_SERVER['PHP_SELF']) !="view-dealer-profile.php"){
    $get_user_details = $manage->selectTheme();
    if ($get_user_details != null) {
        $update_user_count = $get_user_details['update_user_count'];
        $get_email_count = $get_user_details['email_count'];
        $referral_code = $get_user_details['user_referer_code'];
        $gender = $get_user_details['gender'];
        $profilePath = "uploads/" . $session_email . "/profile/" . $get_user_details['img_name'];

    }
}

$user_id_decrypt = $security->decrypt($id);
$token_url = FULL_WEBSITE_URL."testimonial/" . $session_custom_url_is;

if(isset($_SESSION['type']) && $_SESSION['type'] == "Admin"){
    $pendingDealer = $manage->displayPendingDealer();
    if ($pendingDealer != null) {
        $countPendingDealer = mysqli_num_rows($pendingDealer);
    } else {
        $countPendingDealer = 0;
    }
}
if(isset($get_user_details)){
    $user_date = $get_user_details['created_date'];

    if($user_date == ""){
        $user_date = $get_user_details['user_start_date'];
        if($user_date == ""){
            $user_date = date('Y-m-d');
        }
    }
}
else{
    $user_date = date('Y-m-d');
}
$notification_data = $manage->displayNotification($user_date);


?>
<div id="snackbar">URL is on the clipboard, try to paste it!</div>
<nav class="navbar">
    <div class="container-fluid">
        <div class="navbar-header nav_bar">
            <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse"
                data-target="#navbar-collapse" aria-expanded="false"></a>
            <a href="javascript:void(0);" class="bars"></a>
            <?php
            if($main_site) {
                ?>
            <a class="navbar-brand-logo visible-sm visible-md visible-lg visible-xs navbar-brand"
                href="basic-user-info.php"><img src="assets/images/logo.png"></a>
            <?php
            }
            ?>
            <?php
            if (isset($_SESSION['create_user_status']) && $_SESSION['create_user_status'] == true) {
                ?>
            <div class="col-md-3 col-md-offset-5" style="position: absolute">
                <a class="navbar-brand" href="#"><b><label class="label label-danger">Running in user creation
                            mode</label></b></a>
            </div>
            <?php
            }
            ?>
        </div>
        <div class="collapse navbar-collapse" id="navbar-collapse">
            <?php
            if (isset($_SESSION['type']) && $_SESSION['type'] == "User") {
                if (like_match('%dealer%', $referral_by) != 1 && $plan_name !="Life Time") {
                    $five_day = date('Y-m-d', strtotime(date_create("Y-m-d") . ' + 5 days'));
                if ($plan_name == "Free Trail (5 days)" OR $expiry_date <= $five_day) {
                    ?>
            <div class="col-md-5 col-md-offset-2 trial_div"><span>
                    <?php if($plan_name == "Free Trail (5 days)"){ echo 'You are using trial version of digital card'; }elseif($expiry_date < date('Y-m-d')){ echo "Your digital card has been expired."; }elseif($expiry_date <= $five_day){ echo "Your digital card will expire on ".date('d-M-Y',strtotime($expiry_date)); } ?>
                    &nbsp;&nbsp;<a type="button" href="plan-selection.php" class="btn btn-warning btn_premium"><img
                            src="assets/images/crown.png">Upgrade to Premium</a></span>
            </div>
            <?php

                }
            }
            }
            ?>
            <ul class="nav navbar-nav navbar-right">
                <!-- Call Search -->
                <!--<li>
                    <a href="../index.php" target="_blank">Home</a>
                </li>
                   <li>
                       <a href="../index.php" target="_blank">Pricing</a>
                   </li>-->
                <?php
                if(isset($_SESSION['type']) && $_SESSION['type'] == "User") {
                    ?>
                <li>
                    <a href="javascript:void(0);" title="Notifications" class="user_account  pad_zero"
                        data-close="true">
                        <div class="header_top_icon header_top_noty_icon" onclick="updateNotification()"><i
                                class="fa fa-bell"></i><?php if(isset($notification_count) && $notification_count > 0) echo '<span class="noty-icon" id="notifiy_count">'.$notification_count.'</span>';  ?>
                        </div>
                    </a>
                </li>
                <?php
                    if (basename($_SERVER['PHP_SELF']) != "dealer-management.php" && basename($_SERVER['PHP_SELF']) != "rejected_dealer.php" && basename($_SERVER['PHP_SELF']) != "view-dealer-profile.php") {
                        ?>
                <li>
                    <a href="my-leads.php" title="My Leads" class=" pad_zero" data-close="true" style="z-index: 999;">
                        <div class="header_top_icon"><i class="fas fa-poll"></i><?php
                                        if (isset($pending_lead_count) && $pending_lead_count > 0) echo '<span class="noty-icon" id="pending_lead_count">'.$pending_lead_count.'</span>';
                                        ?></div>
                    </a>
                </li>
                <?php
                    }
                }
                if(isset($_SESSION['type']) && $_SESSION['type'] == "Admin") {
                   //$get_sms = $manage->smsCreditChecker();
                    ?>
                <li><a href="#" target="_self">Remaining sms : <?php// echo $get_sms; ?> </a>
                </li>
                <?php
                }
                ?>
                <!-- #END# Call Search -->
                <!-- Notifications -->
                <!--<li class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle dropdown_color" data-toggle="dropdown" role="button">
                        <i class="material-icons">notifications</i>
                        <span class="label-count">7</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">NOTIFICATIONS</li>
                        <li class="body">
                            <ul class="menu">
                                <li>
                                    <a href="javascript:void(0);">
                                        <div class="icon-circle bg-light-green">
                                            <i class="material-icons">person_add</i>
                                        </div>
                                        <div class="menu-info">
                                            <h4>12 new members joined</h4>

                                            <p>
                                                <i class="material-icons">access_time</i> 14 mins ago

                                            </p>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">
                                        <div class="icon-circle bg-cyan">
                                            <i class="material-icons">add_shopping_cart</i>
                                        </div>
                                        <div class="menu-info">
                                            <h4>4 sales made</h4>

                                            <p>
                                                <i class="material-icons">access_time</i> 22 mins ago

                                            </p>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">
                                        <div class="icon-circle bg-red">
                                            <i class="material-icons">delete_forever</i>
                                        </div>
                                        <div class="menu-info">
                                            <h4><b>Nancy Doe</b> deleted account</h4>

                                            <p>
                                                <i class="material-icons">access_time</i> 3 hours ago

                                            </p>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">
                                        <div class="icon-circle bg-orange">
                                            <i class="material-icons">mode_edit</i>
                                        </div>
                                        <div class="menu-info">
                                            <h4><b>Nancy</b> changed name</h4>

                                            <p>
                                                <i class="material-icons">access_time</i> 2 hours ago

                                            </p>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">
                                        <div class="icon-circle bg-blue-grey">
                                            <i class="material-icons">comment</i>
                                        </div>
                                        <div class="menu-info">
                                            <h4><b>John</b> commented your post</h4>

                                            <p>
                                                <i class="material-icons">access_time</i> 4 hours ago

                                            </p>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">
                                        <div class="icon-circle bg-light-green">
                                            <i class="material-icons">cached</i>
                                        </div>
                                        <div class="menu-info">
                                            <h4><b>John</b> updated status</h4>

                                            <p>
                                                <i class="material-icons">access_time</i> 3 hours ago

                                            </p>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">
                                        <div class="icon-circle bg-purple">
                                            <i class="material-icons">settings</i>
                                        </div>
                                        <div class="menu-info">
                                            <h4>Settings updated</h4>

                                            <p>
                                                <i class="material-icons">access_time</i> Yesterday

                                            </p>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="footer">
                            <a href="javascript:void(0);">View All Notifications</a>
                        </li>
                    </ul>
                </li>-->
                <!-- #END# Notifications -->
                <!-- Tasks -->
                <!--  <li class="dropdown">
                      <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                          <i class="material-icons">flag</i>
                          <span class="label-count">9</span>
                      </a>
                      <ul class="dropdown-menu">
                          <li class="header">TASKS</li>
                          <li class="body">
                              <ul class="menu tasks">
                                  <li>
                                      <a href="javascript:void(0);">
                                          <h4>Footer display issue

                                              <small>32%</small>
                                          </h4>
                                          <div class="progress">
                                              <div class="progress-bar bg-pink" role="progressbar" aria-valuenow="85"
                                                   aria-valuemin="0" aria-valuemax="100" style="width: 32%">
                                              </div>
                                          </div>
                                      </a>
                                  </li>
                                  <li>
                                      <a href="javascript:void(0);">
                                          <h4>Make new buttons

                                              <small>45%</small>
                                          </h4>
                                          <div class="progress">
                                              <div class="progress-bar bg-cyan" role="progressbar" aria-valuenow="85"
                                                   aria-valuemin="0" aria-valuemax="100" style="width: 45%">
                                              </div>
                                          </div>
                                      </a>
                                  </li>
                                  <li>
                                      <a href="javascript:void(0);">
                                          <h4>Create new dashboard

                                              <small>54%</small>
                                          </h4>
                                          <div class="progress">
                                              <div class="progress-bar bg-teal" role="progressbar" aria-valuenow="85"
                                                   aria-valuemin="0" aria-valuemax="100" style="width: 54%">
                                              </div>
                                          </div>
                                      </a>
                                  </li>
                                  <li>
                                      <a href="javascript:void(0);">
                                          <h4>Solve transition issue

                                              <small>65%</small>
                                          </h4>
                                          <div class="progress">
                                              <div class="progress-bar bg-orange" role="progressbar" aria-valuenow="85"
                                                   aria-valuemin="0" aria-valuemax="100" style="width: 65%">
                                              </div>
                                          </div>
                                      </a>
                                  </li>
                                  <li>
                                      <a href="javascript:void(0);">
                                          <h4>Answer GitHub questions

                                              <small>92%</small>
                                          </h4>
                                          <div class="progress">
                                              <div class="progress-bar bg-purple" role="progressbar" aria-valuenow="85"
                                                   aria-valuemin="0" aria-valuemax="100" style="width: 92%">
                                              </div>
                                          </div>
                                      </a>
                                  </li>
                              </ul>
                          </li>
                          <li class="footer">
                              <a href="javascript:void(0);">View All Tasks</a>
                          </li>
                      </ul>
                  </li>-->
                <!-- #END# Tasks -->
                <?php
                if (isset($_SESSION['type']) && $_SESSION['type'] == "User") {
                    ?>
                <li class="pull-right ">

                    <a href="javascript:void(0);" class="user_account js-right-sidebar header_img " data-close="true">
                        <img class="js-right-sidebar" src="<?php if (!file_exists($profilePath) && $gender == "Male" or $get_user_details['img_name'] == "") {
                                      echo "uploads/male_user.png";
                                  } elseif (!file_exists($profilePath) && $gender == "Female" or $get_user_details['img_name'] == "") {
                                      echo "uploads/female_user.png";
                                  } else {
                                      echo $profilePath;
                                  } ?>"><span class="js-right-sidebar"><span
                                style="font-weight: bold;margin-bottom: 3px;"
                                class="js-right-sidebar"><?php echo $_SESSION['name']; ?></span> <br><?php
                                      if($plan_name !="Life Time") {

                                          if ($diff != null) {
                                              ?>
                            Remain <?php if ($diff == 0) {
                                                  echo "1 days";
                                              } elseif ($expiry_date < $date) {
                                                  echo "0 days";
                                              } elseif ($expiry_date > $date) {
                                                  echo $diff . " days";
                                              } ?>
                            <?php
                                          }
                                      }else{
                                          echo "Life Time";
                                      }
                                ?></span><i class="fas js-right-sidebar fa-chevron-down down_arrow_icon"></i></a>
                    <div>

                    </div>
                    </a>
                </li>
                <?php
                }
                ?>
            </ul>
        </div>
    </div>
</nav>