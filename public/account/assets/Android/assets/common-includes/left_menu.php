
<section>
    <!-- Left Sidebar -->
    <aside id="leftsidebar" class="sidebar">
        <!-- User Info -->
        <div class="user-info">

            <div class="info-container">
                <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['name']; ?></div>
                <div class="email"><?php echo $_SESSION['email']; ?></div>
                <div class="btn-group user-helper-dropdown">
                    <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                    <ul class="dropdown-menu pull-right">
                       <!-- <li><a href="profile.php"><i class="material-icons">person</i>Profile</a></li>
                        <li role="separator" class="divider"></li>-->
                        <li><a href="reset-password.php"><i class="material-icons">group</i>Reset Password</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="../sign-out.php"><i class="material-icons">input</i>Sign Out</a></li>
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
                if (isset($_SESSION["type"]) && ($_SESSION["type"] == "Admin")) {
                ?>
                <li class="active">
                    <a href="dashboard.php">
                        <!--<i class="material-icons">home</i>-->
                        <i class="fas fa-tachometer-alt icon-size"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <?php } ?>
                <?php
                if (isset($_SESSION["type"]) && ($_SESSION["type"] == "Admin")) {
                ?>
                <li>
                    <a href="user-management.php" class="menu-toggle">
                        <i class="fas fa-globe-americas icon-size"></i>
                        <span>User Management</span>
                    </a>
                   <!-- <ul class="ml-menu">
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
                    </ul>-->
                </li>
                <?php } ?>
                <li>
                    <a href="javascript:void(0);" class="menu-toggle">
                        <i class="far fa-credit-card icon-size"></i>
                        <span>Digital Card</span>
                    </a>
                    <ul class="ml-menu">
                        <li>
                            <a href="basic-user-info.php">Basic Information</a>
                        </li>
                        <li>
                            <a href="service.php">Service</a>
                        </li>
                        <li>
                            <a href="gallery.php">Gallery</a>
                        </li>
                        <li>
                            <a href="testimonial.php">Testimonial</a>
                        </li>
                        <li>
                            <a href="our-team.php">Our Team</a>
                        </li>
                        <li>
                            <a href="payment.php">Payment</a>
                        </li>
                       <!-- <li>
                            <a href="image-slider.php">Image Slider</a>
                        </li>
                        <li>
                            <a href="about-us.php">About us</a>
                        </li>-->

                    </ul>
                </li>
                <li>
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
                </li>
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
        <div class="legal">
            <div class="copyright">
                &copy; 2019 <a href="javascript:void(0);">Share Digital Card</a>.

            </div>
            <div class="version">
                <span class="kubic">Developed By <a href="http://kubictechnology.com/" target="_blank" title="kubic technology | software development company in malad mumbai" style="color:#EE4532 !important; font-weight:600">Kubic Technology</a></span>
            </div>
        </div>
        <!-- #Footer -->
    </aside>
    <!-- #END# Left Sidebar -->
    <!-- Right Sidebar -->
    <aside id="rightsidebar" class="right-sidebar">
        <ul class="nav nav-tabs tab-nav-right" role="tablist">
            <li role="presentation" class="active"><a href="#skins" data-toggle="tab">Profile</a></li>
          <!--  <li role="presentation"><a href="#settings" data-toggle="tab">SETTINGS</a></li>-->
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active in active" id="skins">
                <ul class="demo-choose-skin">
                    <li><div>
                    <i class="material-icons">account_circle</i></div>
                        <span><?php echo $_SESSION['name']; ?></span>
                    </li>
                    <li><div>
                            <i class="material-icons">call</i></div>
                        <span><?php echo $_SESSION['contact']; ?></span>
                    </li>
                    <li><div>
                            <i class="material-icons">email</i></div>
                        <span><?php echo $_SESSION['email']; ?></span>
                    </li>
                    <li>
                        <a class="btn btn-info waves-effect" href="reset-password.php">Change Password</a>&nbsp;&nbsp;&nbsp;&nbsp;
                        <a class="btn btn-primary waves-effect" href="../sign-out.php">Sign Out</a>

                    </li>
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
    <!-- #END# Right Sidebar -->
</section>