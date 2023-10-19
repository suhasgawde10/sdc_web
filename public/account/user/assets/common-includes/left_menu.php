
<?php 

// Check if the user agent indicates a mobile device
function isMobileDevice() {
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $mobileKeywords = array(
      'Mobile', 'Android', 'iPhone', 'iPad', 'iPod', 'BlackBerry', 'Opera Mini'
    );
  
    foreach ($mobileKeywords as $keyword) {
      if (stripos($userAgent, $keyword) !== false) {
        return true;
      }
    }
  
    return false;
  }

?>
<section>
<!-- Left Sidebar -->
<aside id="leftsidebar" class="sidebar">
<!-- User Info -->
<div class="user-info">

    <div class="info-container">
        <div class="left_menu_profile">
            <img
                src="<?php if (!file_exists($profilePath) && $gender == "Male" or $get_user_details['img_name'] == "") {
                    echo "uploads/male_user.png";
                } elseif (!file_exists($profilePath) && $gender == "Female" or $get_user_details['img_name'] == "") {
                    echo "uploads/female_user.png";
                } else {
                    echo $profilePath;
                } ?>">
        </div>
        <div class="name" data-toggle="dropdown" aria-haspopup="true"
             aria-expanded="false">
            <span style="font-weight: bold;"
                  title="<?php echo $_SESSION['name']; ?>"><?php echo $_SESSION['name']; ?></span><br><?php
            if ($_SESSION['type'] == "Admin" OR $_SESSION['type'] == "Editor") {
                echo $_SESSION['email'];
            } else {
                if ($plan_name != "Life Time") {
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
                } else {
                    echo "Life Time";
                }
            }
            ?></div>
        <!--<div class="email">
            <a href="settings.php"><img src="assets/images/setting.png" height="30" width="30"></a>
        </div>-->
        <?php
        if ($_SESSION['type'] == "Admin" OR $_SESSION['type'] == "Editor") {
            ?>
            <div class="btn-group user-helper-dropdown">
                <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                <ul class="dropdown-menu pull-right">
                    <!--<li><a href="basic-user-info.php"><i class="material-icons">person</i>Profile</a></li>-->
                    <li><a href="reset-password.php"><i class="material-icons">group</i>Reset Password</a></li>
                    <li role="separator" class="divider"></li>
                    <!--
                                                 <li role="separator" class="divider"></li>-->
                    <li><a href="../sign-out.php"><i class="material-icons">input</i>Sign Out</a></li>
                </ul>
            </div>
        <?php
        }
        ?>
    </div>
</div>
<!-- #User Info -->
<!-- Menu -->
<div class="menu">
<ul class="list">
    <li class="header">MAIN NAVIGATION</li>
    <?php
    /*                if (isset($_SESSION["type"]) && ($_SESSION["type"] == "Admin")) {
                    */
    ?>
    <li class="active">
        <a href="<?php
        if ($_SESSION['type'] == "Admin" or $_SESSION['type'] == "Editor") {
            echo 'admin_dashboard.php';
        } else {
            echo 'dashboard.php';
        }
        ?>">
            <!--<i class="material-icons">home</i>-->
            <i class="fas fa-tachometer-alt icon-size"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <?php /*} */ ?>
    <?php
    if (isset($_SESSION["type"]) && ($_SESSION["type"] == "Admin" or $_SESSION["type"] == "Editor")) {
        ?>
        <li>
            <a href="user-management.php">
                <i class="fa fa-users icon-size"></i>
                <span>Users</span>
            </a>
        </li>
    <?php
    }
    if (isset($_SESSION["type"]) && ($_SESSION["type"] == "Admin" OR $_SESSION['type'] == "Editor")) {
        ?>
        <li>
            <a href="dealer-management.php" class="dealer_link">
                <i class="fa fa-handshake-o icon-size"></i>
                <span>Dealers</span>
                <?php
                if ($countPendingDealer > 0) {
                    echo '<div><label class="label label-success">' . $countPendingDealer . '</label></div>';
                }
                ?>
            </a>
        </li>
    <?php
    }
    if (isset($_SESSION["type"]) && ($_SESSION["type"] == "Admin")) {
        ?>
        <li>
            <a href="coupon_module.php">
                <i class="fa fa-money icon-size"></i>
                <span>Coupons</span>
            </a>
        </li>
        <li>
            <a href="subscription_module.php">
                <i class="fa fa-money icon-size"></i>
                <span>Subscriptions</span>
            </a>
        </li>
        <?php
    }
    if (isset($_SESSION["type"]) && ($_SESSION["type"] == "Admin" OR $_SESSION['type'] == "Editor")) {
        ?>
        <li>
            <a href="blog.php">
                <i class="fab fa-blogger icon-size"></i>
                <span>Blog</span>
            </a>
        </li>
        <?php
    }
    if (isset($_SESSION["type"]) && ($_SESSION["type"] == "Admin")) {
        ?>
        <li>
            <a href="mobile-theme.php">
                <i class="fa fa-paint-brush icon-size"></i>
                <span>Themes</span>
            </a>
        </li>
        <li>
            <a href="my_team.php">
                <i class="fa fa-user icon-size"></i>
                <span>Editor</span>
            </a>
        </li>
        <li>
            <a href="notification_module.php">
                <i class="fa fa-user icon-size"></i>
                <span>Notification</span>
            </a>
        </li>
        <li>
            <a href="invoice-report.php">
                <i class="fa fa-user icon-size"></i>
                <span>Invoice Report</span>
            </a>
        </li>
        <li>
            <a href="admin-setting.php">
                <i class="fa fa-user icon-size"></i>
                <span>Setting</span>
            </a>
        </li>
    <?php
    }
    ?>
    <?php
    if ($_SESSION['type'] == "User") {
        ?>
        <li>
            <a href="basic-user-info.php">
                <i class="fas fa-user icon-size"></i>
                <span><?php echo $_SESSION['menu']['s_profile']; ?></span> <?php if ($_SESSION['red_dot']['company_name'] == true) echo '<div class="remaining_form_dot"></div>'; ?>
            </a>
        </li>
        <?php
        if(isMobileDevice()){
        ?>
         <li>
            <a href="service.php">
                <i class="far fa-list-alt icon-size"></i>
                <span><?php echo $_SESSION['menu']['s_services'] . " / " . $_SESSION['menu']['s_products']; ?></span>  <?php if ($_SESSION['red_dot']['service_name'] == true) echo '<div class="remaining_form_dot"></div>'; ?>
            </a>
        </li>
        <li>
            <a href="gallery.php">
                <i class="fas fa-images icon-size"></i>
                <span><?php echo $_SESSION['menu']['s_gallery']; ?></span><?php if ($_SESSION['red_dot']['image_name'] == true or $_SESSION['red_dot']['video_link'] == true) echo '<div class="remaining_form_dot"></div>'; ?>
            </a>
        </li>
        <li>
            <a href="testimonial.php">
                <i class="fas fa-poll icon-size"></i>
                <span><?php echo $_SESSION['menu']['s_clients']; ?></span><?php if ($_SESSION['red_dot']['client_name'] == true or $_SESSION['red_dot']['client_review'] == true) echo '<div class="remaining_form_dot"></div>'; ?>
            </a>
        </li>
        <li>
            <a href="our-team.php">
                <i class="fas fa-users icon-size"></i>
                <span><?php echo $_SESSION['menu']['s_team']; ?></span><?php if ($_SESSION['red_dot']['our_team'] == true) echo '<div class="remaining_form_dot"></div>'; ?>
            </a>
        </li>
        <li>
            <a href="payment.php">
                <i class="fas fa-money-bill-alt icon-size"></i>
                <span><?php echo $_SESSION['menu']['s_bank']; ?></span><?php if ($_SESSION['red_dot']['bank_name'] == true or $_SESSION['red_dot']['upi_id'] == true) echo '<div class="remaining_form_dot"></div>'; ?>
            </a>
        </li>
        <?php } if ($main_site) { ?>
            <li>
                <a href="manage_team_card.php">
                    <i class="fa fa-id-card-o icon-size" aria-hidden="true"></i>
                    <span>Create Team </span>
                    <label class="label label-success new_label">New</label>
                </a>
            </li>
        <?php
        }
        ?>
        <li>
            <a href="manage-section.php">
                <i class="fas fa-bars icon-size"></i>
                <span>Manage Menu Bar</span>
            </a>
        </li>

    <?php
    }
    ?>
    <!--        <li>
                <a href="javascript:void(0);" class="menu-toggle">
                    <i class="fas fa-globe-americas icon-size"></i>
                    <span>Website</span>
                </a>
                <ul class="ml-menu">
                    <li>
                        <a href="theme.php">Theme</a>
                    </li>
                    <li>
                        <a href="image-slider.php">Image slider</a>
                    </li>
                    <li>
                        <a href="about-us.php">About us</a>
                    </li>
                    <li>
                        <a href="website_setting.php">Website Setting</a>
                    </li>
                </ul>
            </li>-->
    <!--     <li class="header">LABELS</li>
          <li>
              <a href="javascript:void(0);">
                  <i class="material-icons col-red">donut_large</i>
                  <span>Important</span>
              </a>
          </li>
          <li>
              <a href="javascript:void(0);">
                  <i class="material-icons col-amber">donut_large</i>
                  <span>Warning</span>
              </a>
          </li>
          <li>
              <a href="javascript:void(0);">
                  <i class="material-icons col-light-blue">donut_large</i>
                  <span>Information</span>
              </a>
          </li>-->
</ul>
</div>
<!-- #Menu -->
<!-- Footer -->

<!-- #Footer -->
</aside>
<!-- #END# Left Sidebar -->
<!-- Right Sidebar -->
<aside id="rightsidebar" class="right-sidebar">

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade in active in active" id="skins">
            <ul class="demo-choose-skin">
                <li>
                    <div class="custom_div_size">
                        <i class="fa fa-bars" aria-hidden="true"></i></div>
                    <span><a href="my-subscription-plan.php"> Subscription Plan</a></span>
                </li>
                <li>
                    <div class="custom_div_size">
                        <i class="fa fa-paper-plane-o" aria-hidden="true"></i></div>

                    <span><a onclick="setClipboard('<?php echo $token_url; ?>')">Copy Feedback Url</a></span>
                </li>

                <li>
                    <div class="custom_div_size">
                        <i class="fa fa-id-card-o" aria-hidden="true"></i></div>
                    <span><a data-toggle="modal" data-target="#copyUrlModal">Copy Digital Card Url</a></span>
                </li>

                <?php
                if ($main_site) {
                    ?>
                    <li>
                        <div class="custom_div_size">
                            <i class="fa fa-link" aria-hidden="true"></i></div>
                    <span><a
                            onclick="setClipboard('https://sharedigitalcard.com/register.php?referral_by=<?php echo $referral_code; ?>')">Copy
                            Referral Url</a></span>
                    </li>
                <?php
                }
                ?>
                <?php
                if (!isset($_SESSION['dealer_login_type'])) {
                    ?>
                    <li>
                        <div class="custom_div_size">
                            <i class="fa fa-cog" aria-hidden="true"></i></div>
                        <span><a href="settings.php">Settings</a></span>
                    </li>
                <?php
                }
                ?>
                <?php
                if ($main_site) {
                    ?>
                    <li>
                        <div class="custom_div_size">
                            <i class="fa fa-id-card-o" aria-hidden="true"></i></div>
                        <span><a href="manage_team_card.php">Create Team Card </a></span>
                        <label class="label label-success new_label">New</label>
                    </li>
                <?php
                }
                ?>
                <?php
                if (!isset($_SESSION['dealer_login_type'])) {
                    ?>
                    <li>
                        <div class="custom_div_size">
                            <i class="fas fa-poll" aria-hidden="true"></i></div>
                        <span><a href="manage-log.php">Activity Logs </a></span>
                        <label class="label label-success new_label">New</label>
                    </li>

                    <li>
                        <div class="custom_div_size">
                            <i class="fa fa-lock" aria-hidden="true"></i></div>
                        <span><a href="reset-password.php">Change Password</a></span>
                    </li>
                <?php

                }
                ?>
                <li>
                    <div class="custom_div_size">
                        <i class="fa fa-sign-out" aria-hidden="true"></i></div>
                    <span><a class="" href="../sign-out.php">Sign Out</a></span>
                </li>
                <?php
                if (isset($_SESSION['type']) && $_SESSION['type'] == "User" && like_match('%dealer%', $referral_by) != 1) {
                    if ($plan_name != "Life Time" && $plan_name != "Free Trail (5 days)" && basename($_SERVER['PHP_SELF']) != "plan-selection.php") {

                        if ($diff != null) {

                            if ($sell_ref != "dealer_panel") {
                                ?>
                                <li>
                                    <a href="plan-selection.php" target="_self"
                                       class="btn btn-warning btn_premium form-control"
                                       style="width: 100%;color: white"><?php if ($getSubscription != null) {
                                            echo '<img
                                src="assets/images/crown.png">Upgrade Plan';
                                        } else {
                                            echo '<img
                                src="assets/images/crown.png">Plan selection';
                                        } ?></a>
                                </li>
                            <?php
                            }
                            ?>
                        <?php
                        }
                    }
                }?>

            </ul>
        </div>
        <!--<div role="tabpanel" class="tab-pane fade" id="settings">
            <div class="demo-settings">
                <p>GENERAL SETTINGS</p>
                <ul class="setting-list">
                    <li>
                        <span>Report Panel Usage</span>

                        <div class="switch">
                            <label>
                                <input type="checkbox" checked><span class="lever"></span></label>
                        </div>
                    </li>
                    <li>
                        <span>Email Redirect</span>

                        <div class="switch">
                            <label>
                                <input type="checkbox"><span class="lever"></span></label>
                        </div>
                    </li>
                </ul>
                <p>SYSTEM SETTINGS</p>
                <ul class="setting-list">
                    <li>
                        <span>Notifications</span>

                        <div class="switch">
                            <label>
                                <input type="checkbox" checked><span class="lever"></span></label>
                        </div>
                    </li>
                    <li>
                        <span>Auto Updates</span>

                        <div class="switch">
                            <label>
                                <input type="checkbox" checked><span class="lever"></span></label>
                        </div>
                    </li>
                </ul>
                <p>ACCOUNT SETTINGS</p>
                <ul class="setting-list">
                    <li>
                        <span>Offline</span>

                        <div class="switch">
                            <label>
                                <input type="checkbox"><span class="lever"></span></label>
                        </div>
                    </li>
                    <li>
                        <span>Location Permission</span>

                        <div class="switch">
                            <label>
                                <input type="checkbox" checked><span class="lever"></span></label>
                        </div>
                    </li>
                </ul>
            </div>
        </div>-->
    </div>
</aside>
<aside id="rightsidebarforlead" class="right-sidebar custom_right_side_bar">

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade in active in active" id="skins">
            <?php
            if ($notification_data != null) {
                ?>
                <ul class="demo-choose-skin">
                    <?php
                    $noty_i = 1;
                    while ($noty_data = mysqli_fetch_array($notification_data)) {
                        ?>
                        <li <?php if ($noty_i <= $notification_count) echo 'class="noty_li_back"'; ?>>
                            <a href="<?php if ($noty_data['link'] != "") {
                                echo $noty_data['link'];
                            } else {
                                echo "#";
                            } ?>" target="_blank">
                                <div class="title_div">
                                    <div>
                                        <h5><?php echo $noty_data['title']; ?></h5>
                                    </div>
                                    <div>
                                        <b style="color: #666;"><?php echo date('d-M-Y', strtotime($noty_data['created_date'])) ?></b>
                                    </div>
                                </div>
                                <div class="desc_div">
                                    <p style="color: #333;"><?php echo $noty_data['description'] ?></p>
                                </div>
                            </a>
                        </li>
                        <?php
                        $noty_i++;
                    }
                    ?>
                </ul>
            <?php
            } else {
                ?>
                <div class="notification_section">
                    <img src="assets/images/notification.png">
                    <h4>No notification found</h4>
                </div>
            <?php
            }
            ?>
        </div>
        <!--<div role="tabpanel" class="tab-pane fade" id="settings">
            <div class="demo-settings">
                <p>GENERAL SETTINGS</p>
                <ul class="setting-list">
                    <li>
                        <span>Report Panel Usage</span>

                        <div class="switch">
                            <label>
                                <input type="checkbox" checked><span class="lever"></span></label>
                        </div>
                    </li>
                    <li>
                        <span>Email Redirect</span>

                        <div class="switch">
                            <label>
                                <input type="checkbox"><span class="lever"></span></label>
                        </div>
                    </li>
                </ul>
                <p>SYSTEM SETTINGS</p>
                <ul class="setting-list">
                    <li>
                        <span>Notifications</span>

                        <div class="switch">
                            <label>
                                <input type="checkbox" checked><span class="lever"></span></label>
                        </div>
                    </li>
                    <li>
                        <span>Auto Updates</span>

                        <div class="switch">
                            <label>
                                <input type="checkbox" checked><span class="lever"></span></label>
                        </div>
                    </li>
                </ul>
                <p>ACCOUNT SETTINGS</p>
                <ul class="setting-list">
                    <li>
                        <span>Offline</span>

                        <div class="switch">
                            <label>
                                <input type="checkbox"><span class="lever"></span></label>
                        </div>
                    </li>
                    <li>
                        <span>Location Permission</span>

                        <div class="switch">
                            <label>
                                <input type="checkbox" checked><span class="lever"></span></label>
                        </div>
                    </li>
                </ul>
            </div>
        </div>-->
    </div>
</aside>
<!-- #END# Right Sidebar -->


</section>