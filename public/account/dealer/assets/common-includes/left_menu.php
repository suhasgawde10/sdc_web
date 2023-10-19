<?php
$all_customer_count = $manage->countAllCustomerByCode($_SESSION['dealer_code']);



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
                    <?php echo $_SESSION['dealer_name']; ?><br><?php echo $_SESSION['dealer_email']; ?></div>
                <div class="btn-group user-helper-dropdown">
                    <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                    <ul class="dropdown-menu pull-right">
                        <li><a href="profile.php"><i class="material-icons">person</i>Profile</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="reset-password.php"><i class="material-icons">group</i>Reset Password</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="../sign-out-dealer.php"><i class="material-icons">input</i>Sign Out</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- #User Info -->
        <!-- Menu -->
        <div class="menu">
            <ul class="list">
                <li class="header">MAIN NAVIGATION</li>
                <?php
                if ($dealer_status == 1 && $pay_status == 1) {
                    ?>
                    <li class="active">
                        <a href="dashboard.php">
                            <!--<i class="material-icons">home</i>-->
                            <i class="fas fa-tachometer-alt icon-size"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                <?php }
                if ($dealer_status == 1 && $pay_status == 1) {
                    ?>
                    <li>
                        <a href="create_digital_card.php">
                            <i class="fas fa-globe-americas icon-size"></i>
                            <span>Create Digital Card</span>
                        </a>
                    </li>
                    <li>
                        <a href="view_all_customer.php?all_customer=<?php echo $security->encrypt($deal_code); ?>">
                            <i class="fas fa-users icon-size"></i>
                            <span>View All Customer</span> <label
                                class="label view_custo label-success"><?php echo $all_customer_count ?></label>
                        </a>
                    </li>
                    <?php
                    if (isset($_SESSION['dealer_type']) && $_SESSION['dealer_type'] == "dealer") {
                        ?>
                        <li>
                            <a href="my_team.php">
                                <i class="fas fa-users icon-size"></i>
                                <span>My Team </span>
                            </a>
                        </li>
                        <li>
                            <a href="invoice-report.php">
                                <i class="fas fa-file icon-size"></i>
                                <span>Invoice Report </span>
                            </a>
                        </li>
                        <li>
                            <a href="https://my.bugasura.io/kubic-technology/share-digital-card" target="_blank">
                                <i class="fas fa-file icon-size"></i>
                                <span>Raise a Issue</span>
                            </a>
                        </li>
                    <?php
                    }
                }
                ?>
                <?php
                if ($dealer_status == 0) {
                    ?>
                    <li>
                        <a href="basic-user-info.php">
                            <i class="fas fa-user icon-size"></i>
                            <span>Basic Information</span>
                        </a>

                    </li>
                <?php
                } elseif ($dealer_status == 1 && $pay_status == 0) {
                    ?>
                    <li>
                        <a href="payment_deposit.php">
                            <i class="fas fa-user icon-size"></i>
                            <span>Payment</span>
                        </a>
                    </li>
                <?php
                }
                if ($dealer_status == 1 && $pay_status == 1) {
                    if (strpos($host_url, 'sharedigitalcard.com') == false) {
                        ?>
                        <!--<li>
                            <a href="assets/pdf/Earning%20methods%20of%20dealers.pdf" target="_blank">
                                <i class="fa fa-money icon-size"></i>
                                <span>Earning Methods</span>
                            </a>
                        </li>-->
                    <?php
                    }
                    ?>
                    <div style="position: relative">
                        <img src="assets/images/dealer-code.png" style="width: 100%">

                        <div class="dealer_code">
                            <h5><?php echo $deal_code; ?></h5>
                        </div>
                    </div>
                <?php
                }
                ?>
            </ul>
        </div>
        <!-- #Menu -->
        <!-- Footer -->
        <?php
        if ($main_site) {
            ?>

            <div class="legal">
                <div class="copyright">
                    &copy; <?php echo date('Y'); ?> <a href="javascript:void(0);">Share Digital Card</a>.
                </div>
                <div class="version">
                    <span class="kubic">Powered By <a href="http://kubictechnology.com/" target="_blank"
                                                      title="kubic technology | software development company in malad mumbai"
                                                      style="color:#EE4532 !important; font-weight:600">Kubic
                            Technology</a></span>
                </div>
            </div>
        <?php
        }
        ?>
        <!-- #Footer -->
    </aside>
    <!-- #END# Left Sidebar -->
    <!-- Right Sidebar -->
    <!-- <aside id="rightsidebar" class="right-sidebar">
        <ul class="nav nav-tabs tab-nav-right" role="tablist">
            <li role="presentation" class="active"><a href="#skins" data-toggle="tab">Profile</a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active in active" id="skins">
                <ul class="demo-choose-skin">
                    <li><div>
                    <i class="material-icons">account_circle</i></div>
                        <span><?php /*echo $_SESSION['dealer_name']; */ ?></span>
                    </li>
                    <li><div>
                            <i class="material-icons">call</i></div>
                        <span><?php /*echo $_SESSION['dealer_contact']; */ ?></span>
                    </li>
                    <li><div>
                            <i class="material-icons">email</i></div>
                        <span><?php /*echo $_SESSION['dealer_email']; */ ?></span>
                    </li>
                    <li>
                        <a class="btn btn-info waves-effect" href="#">Change Password</a>&nbsp;&nbsp;&nbsp;&nbsp;
                        <a class="btn btn-primary waves-effect" href="../sign-out-dealer.php">Sign Out</a>

                    </li>
                </ul>
            </div>
        </div>
    </aside>-->
    <!-- #END# Right Sidebar -->
</section>