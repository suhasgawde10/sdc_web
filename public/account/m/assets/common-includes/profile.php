<?php

$paymentModel = false;
$section_bank_id = 7;

$get_bank_status = $manage->displayOnOffStatus($custom_url, $section_bank_id);

if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
    $link = "https";
else
    $link = "http";
$link .= "://";

$link .= $_SERVER['HTTP_HOST'];

$link .= $_SERVER['REQUEST_URI'];


$verify_user = $manage->displayVerifiedUser($user_id);
$get_country = $manage->mdm_getCountryCode($country);
if ($get_country != null) {
    $country_code = $get_country['phonecode'];
} else {
    $country_code = "91";
}
if (isset($_POST['add_contact'])) {
    $contactResult = $manage->mdm_getDigitalCardDetailsOFUser($custom_url);
    require_once "VcardExport.php";
    $vcardExport = new VcardExport();
    $vcardExport->contactVcardExportService($contactResult);
    exit();
}


$get_cover_data = $manage->getCoverImageOfUser($user_id);
if ($get_cover_data != null) {
    $coverCount = mysqli_num_rows($get_cover_data);
} else {
    $coverCount = 0;
}
function fetch_all_data($result)
{
    $all = array();
    while ($thing = mysqli_fetch_array($result)) {
        $all[] = $thing;
    }
    return $all;
}

function rep_escape($string)
{
    return str_replace(['\r\n', '\r', '\n', '\\'], '', $string);
}

if (basename($_SERVER['PHP_SELF']) != "index.php") {
    if ($get_data != null) {
        $user_status = $get_data['status'];
        $keyword = $get_data['user_keyword'];
        $parent_id = $get_data['parent_id'];
        $expiry_date = $get_data['expiry_date'];
        if ($parent_id != "") {
            $getParentData = $manage->getSpecificUserProfileById($parent_id);
            $email = $getParentData['email'];
            $user_id = $getParentData['user_id'];
            $contact_no = $getParentData['contact_no'];
            $default_user_id = $getParentData['user_id'];
            $about_company = $getParentData['about_company'];
            $company_name = $getParentData['company_name'];
            $our_mission = $getParentData['our_mission'];
            $company_profile = $getParentData['company_profile'];
            $cover_pic = $getParentData['cover_pic'];
            if ($getParentData['cover_pic'] != "") {
                $key_data = explode(',', $getParentData['cover_pic']);
            } else {
                $key_data = 0;
            }
        } else {
            $user_id = $get_data['user_id'];
            $contact_no = $get_data['contact_no'];
            $about_company = $get_data['about_company'];
            $company_name = $get_data['company_name'];
            $our_mission = $get_data['our_mission'];
            $email = $get_data['email'];
            $company_profile = $get_data['company_profile'];
            $cover_pic = $get_data['cover_pic'];
            if ($get_data['cover_pic'] != "") {
                $key_data = explode(',', $get_data['cover_pic']);
            } else {
                $key_data = 0;
            }
            $default_user_id = $get_data['user_id'];
        }

        $user_city = $get_data['city'];
        if ($keyword != "") {
            $keyword_array_data = explode(',', $keyword);
        } else {
            $keyword_array_data = "";
        }
    }
}

$getReviews = $manage->getTotalReviews($user_id);

if (!$user_expired_status) {
    $get_section_theme = $manage->mdm_displaySectionTheme($user_id, 0);
    if ($get_section_theme != null) {
        $profile_section_theme = $get_section_theme['theme_id'];
    } else {
        $profile_section_theme = 2;
    }
} else {
    $profile_section_theme = 1;
}

?>
<div id="snackbar"></div>
<?php
if (!$user_expired_status) {
    if (basename($_SERVER['PHP_SELF']) == "index.php" OR basename($_SERVER['PHP_SELF']) == "profile-pdf.php") {
        ?>
        <div class="cover-image">
            <?php
            if ($get_cover_data != null) {
                $cover_first_img = fetch_all_data($get_cover_data);
                foreach ($cover_first_img as $k) {
                    $cover_first_img_path = FULL_WEBSITE_URL . "user/uploads/" . $email . "/profile/" . $k['cover_pic'];
                    break;
                }
            }
            if ($get_cover_data == null) {
                echo "<img src='" . FULL_WEBSITE_URL . "user/uploads/admin_background.jpg' id='coverImg'>";
            } elseif (check_url_exits($cover_first_img_path) && $coverCount == 1) {
                echo '<img src="' . $cover_first_img_path . '"  style="width:100%" id="coverImg">';
            } elseif ($coverCount > 1) {
                ?>
                <!--            <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">-->
                <div class="owl-slider">
                    <div id="carousel" class="owl-carousel">
                        <!-- Indicators -->
                        <!-- <ol class="carousel-indicators">
                            <?php
                        /*                            $i = 1;
                                                    foreach ($key_data as $key) {
                                                        */
                        ?>
                                <li data-target="#carousel-example-generic" data-slide-to="0" <?php /*if($i == 1) echo 'class="active"'; */
                        ?>></li>
                                <?php
                        /*                                $i++;
                                                    }
                                                    */
                        ?>
                        </ol>-->

                        <!-- Wrapper for slides -->

                        <?php
                        $i = 1;

                        foreach ($cover_first_img as $key) {
                            $cover_pic_path = FULL_WEBSITE_URL . "user/uploads/$email/profile/" . $key['cover_pic'];

                            if (check_url_exits($cover_pic_path) && $key['cover_pic'] != "") {
                                ?>
                                <div class="item <?php if ($i == 1) echo 'active'; ?>">
                                    <?php
                                    echo '<img class="owl-lazy" src="' . $cover_pic_path . '" />';
                                    ?>
                                </div>
                                <?php
                                $i++;
                            }
                        }
                        ?>
                    </div>

                </div>
            <?php
            } else {
                echo "<img src='" . FULL_WEBSITE_URL . "user/uploads/admin_background.jpg' id='coverImg'>";
            } ?>
            <!--<span><i class="fa fa-search-plus zoom-icon"></i></span>-->
            <!--<img src="<?php echo FULL_MOBILE_URL; ?>assets/images/zoom.png" class="zoom-icon">-->
            <?php
            if ($profile_section_theme == 2) {
                ?>
                <div class="theme2_share_icon flyout_modal_button">
                    <i class="fa fa-share-alt"></i>
                </div>
            <?php
            }
            ?>

        </div>
    <?php
    }
}
if ($user_expired_status) {
    ?>
    <!--  <div class="expire_user_cover">
        <h4><?php /*echo $company_name; */ ?></h4>
    </div>-->
<?php
}
if ($profile_section_theme == 1) {
    ?>
    <div class="profile-img <?php
    if (basename($_SERVER['PHP_SELF']) != "index.php") {
        ?> pad_top <?php } ?>">
        <img id="myImg" class="img-circle user-profile-img"
             src="<?php if (!check_url_exits($profilePath) && $gender == "Male" or $img_name == "") {
                 echo FULL_WEBSITE_URL . "user/uploads/male_user.png";
             } elseif (!check_url_exits($profilePath) && $gender == "Female" or $img_name == "") {
                 echo FULL_WEBSITE_URL . "user/uploads/female_user.png";
             } else {
                 echo $profilePath;
             } ?>">
        <!--  <div class="visitor_count">
                <i class="fa fa-eye"></i> visited <?php /*echo $home_page_count; */ ?>
            </div>-->
        <div class="whats-app">
            <!--              --><?php
            /*                if ($get_bank_status != null) {
                                if (isset($_GET['custom_url']) && $get_bank_status['digital_card'] == 1) { */ ?>
            <a href="tel:<?php if (isset($contact_no)) echo "+" . $country_code . $contact_no; ?>"><img
                    class="whats-app-logo  <?php
                    if ($user_expired_status) echo " top_div ";
                    if (basename($_SERVER['PHP_SELF']) != "index.php") {
                        ?> img_top <?php } ?>"
                    src="<?php echo FULL_MOBILE_URL; ?>assets/images/icon/call_now.png"></a><!-- --><?php /*echo $money_transfer_icon; */ ?>
            <?php /*} else { */ ?><!--
                        <a href="https://api.whatsapp.com/send?phone=<?php /*echo $country_code . $whatsapp_no; */ ?>"><img
                                    class="whats-app-logo" src="<?php /*echo $whatsapp_share_icon; */ ?>"></a>
                        --><?php
            /*                    }
                            }
                            */ ?>
            <?php
            if (basename($_SERVER['PHP_SELF']) == "index.php") {
                ?>
                <div class="flyout_modal_button"> <!-- data-target="#shareModal" data-toggle="modal" -->
                    <a><img class="share-logo <?php if ($user_expired_status) echo "top_div"; ?>"
                            src="<?php echo FULL_MOBILE_URL; ?>assets/images/icon/share-card.png"></a>
                </div><!-- <?php // echo $share_icon; ?> -->
            <?php
            } else {
                ?>
                <form method="post" action="">
                    <a href="<?php echo get_url_param_for_mobile('export-vcf.php'); ?>" name="add_contact"
                       class="btn_transparent <?php
                       if (basename($_SERVER['PHP_SELF']) != "index.php") {
                           ?> img_top <?php } ?>"
                       title="Export to vCard"> <img
                            src="<?php echo FULL_MOBILE_URL; ?>assets/images/icon/save-contact.png"></a>
                </form>
            <?php
            }
            ?>
        </div>
    </div>

    <div class="client-name <?php if ($user_expired_status) echo 'rxpitr' ?>">

        <h1 class="text-color-p"><?php if (isset($name)) echo $name; ?>
            <?php

            if ($verify_user == 1) {
                ?>
                <img class="blue-tick" src="<?php echo FULL_MOBILE_URL; ?>assets/images/icon/blue_tick.png">
            <?php
            }
            ?>
        </h1>
        <?php
        if ($user_expired_status) {
            ?>
            <h4 class="text-color-p"><?php if (isset($company_name)) echo $company_name; ?></h4>
            <h5 class="text-color-p"><?php if (isset($designation)) echo $designation; ?></h5>
        <?php
        } else {
            ?>
            <h3 class="text-color-p"><?php if (isset($designation)) echo $designation; ?></h3>
        <?php
        }
        if ($getReviews['rating_num'] !=0) {
            if (!$user_expired_status) {
                if ($getReviews != null) {
                    if ($getReviews['average_rating'] <= "2") {
                        $rating = "30";
                    } elseif ($getReviews['average_rating'] <= "3") {
                        $rating = "45";
                    } elseif ($getReviews['average_rating'] <= "4") {
                        $rating = "60";
                    } elseif ($getReviews['average_rating'] <= "5") {
                        $rating = "75";
                    }
                    ?>
                    <g id="G-REVIEW-STARS_21">
                        <a class="text-color-p" href="<?php echo get_url_param_for_mobile('testimonial.php'); ?>">
                            <?php echo $getReviews['average_rating']; ?> <span><i
                                    class="fa fa-star <?php if ($getReviews['average_rating'] >= "0.5") {
                                        echo "fill-star";
                                    } ?>"></i><i class="fa fa-star <?php if ($getReviews['average_rating'] >= "1.5") {
                                    echo "fill-star";
                                } ?>"></i><i class="fa fa-star <?php if ($getReviews['average_rating'] >= "2.5") {
                                    echo "fill-star";
                                } ?>"></i><i class="fa fa-star <?php if ($getReviews['average_rating'] >= "3.5") {
                                    echo "fill-star";
                                } ?>"></i><i class="fa fa-star <?php if ($getReviews['average_rating'] >= "4.5") {
                                    echo "fill-star";
                                } ?>"></i></span> <?php echo $getReviews['rating_num']; ?> Reviews</a>
                    </g>
                <?php
                }
            }
        }

        ?>
    </div>
    <div class="social-logo">
        <!--<ul class="social-ul">
                    <li><a <?php /*if ($youtube == "") {
                            echo "style='cursor: not-allowed;filter: grayscale(100%);'" . "target='_self'";
                        } else {
                            echo "target='_blank'";
                        } */ ?> href="<?php /*if (isset($youtube) && ($youtube) != "") {
                            echo $youtube;
                        } else {
                            echo "#";
                        } */ ?>" class="linkedin"><img src="<?php /*echo $youtube_icon; */ ?>"></a>
                    </li>
                    <li><a <?php /*if ($facebook == "") {
                            echo "style='cursor: not-allowed;filter: grayscale(100%);'" . "target='_self'";
                        } else {
                            echo "target='_blank'";
                        } */ ?> href="<?php /*if (isset($facebook) && ($facebook) != "") {
                            echo $facebook;
                        } else {
                            echo "#";
                        } */ ?>" class="facebook"> <img src="<?php /*echo $facebook_share_con; */ ?>"></a>
                    </li>
                    <li><a <?php /*if ($twitter == "") {
                            echo "style='cursor: not-allowed;filter: grayscale(100%);'" . "target='_self'";
                        } else {
                            echo "target='_blank'";
                        } */ ?> href="<?php /*if (isset($twitter) && ($twitter) != "") {
                            echo $twitter;
                        } else {
                            echo "#";
                        } */ ?>" class="twitter"><img src="<?php /*echo $twitter_icon; */ ?>"></a>
                    </li>
                    <li><a <?php /*if ($instagram == "") {
                            echo "style='cursor: not-allowed;filter: grayscale(100%);'" . "target='_self'";
                        } else {
                            echo "target='_blank'";
                        } */ ?> href="<?php /*if (isset($instagram) && ($instagram) != "") {
                            echo $instagram;
                        } else {
                            echo "#";
                        } */ ?>" class="instagram"><img src="<?php /*echo $instagram_icon; */ ?>"></a>
                    </li>
                    <li><a <?php /*if ($linked_in == "") {
                            echo "style='cursor: not-allowed;filter: grayscale(100%);'" . "target='_self'";
                        } else {
                            echo "target='_blank'";
                        } */ ?> href="<?php /*if (isset($linked_in) && ($linked_in) != "") {
                            echo $linked_in;
                        } else {
                            echo "#";
                        } */ ?>" class="linkedin"><img
                                src="<?php /*echo $linked_in_icon; */ ?>"></a></li>
                </ul>-->
        <?php
        if (!$user_expired_status) {
            ?>
            <ul class="contact-ul">
                <li>
                    <!--<a --><?php /*if($paymentModel){ echo "disabled"; }else{ echo 'data-target="#paymentModelProfile" data-toggle="modal"'; } */
                    ?>
                    <a target="_blank" href="https://api.whatsapp.com/send?phone=<?php if ($whatsapp_no != '') {
                        echo $country_code . $whatsapp_no;
                    } elseif ($contact_no != '') echo $country_code . $contact_no; ?>"
                       class="profile-img-a">
                        <div class="p-align">
                            <div class="contact-icon-btm"><i class="fa fa-whatsapp"></i> WhatsApp</div>
                            <!--<p></p>--></div>
                    </a></li>
                <li><a href="mailto:<?php if (isset($email)) echo $email; ?>"
                       class="profile-img-a">
                        <div class="p-align">
                            <div class="contact-icon-btm"><i class="fas fa-envelope"></i> Mail</div>
                            <!--<p></p>--></div>
                    </a></li>
                <?php /*if (isset($website) && ($website) != "") {*/ ?>
                <li><a target="_blank" href="<?php if (isset($website) && ($website) != "") {
                        echo urlChecker($website);
                    } else {
                        echo "#";
                    } ?>"
                       class="profile-img-a">
                        <div class="p-align">
                            <div class="contact-icon-btm
                                    <?php if (isset($website) && ($website) == "") {
                                echo "disabled-icon";
                            } ?>"><i class="fas fa-globe-europe"></i> Website
                            </div>
                            <!--<p></p>--></div>
                    </a></li><?php /*}if (isset($map_link) && ($map_link) != "") {*/ ?>
                <li><a href="<?php if (isset($map_link) && ($map_link) != "") {
                        echo urlChecker($map_link);
                    } else {
                        echo "#";
                    } ?>" class="profile-img-a">
                        <div class="p-align">
                            <div class="contact-icon-btm
                                    <?php if (isset($map_link) && ($map_link) == "") {
                                echo "disabled-icon";
                            } ?>
                                    "><i class="fas fa-map-marker-alt"></i> Direction
                            </div>
                            <!--<p></p>--></div>
                    </a></li>
                <?php /*}*/ ?>
            </ul>
        <?php
        }
        ?>
    </div>
<?php
} elseif ($profile_section_theme == 2) {
    ?>
    <div>
        <div class="profile-img-theme2">
            <img id="myImg"
                 src="<?php if (!check_url_exits($profilePath) && $gender == "Male" or $img_name == "") {
                     echo FULL_WEBSITE_URL . "user/uploads/male_user.png";
                 } elseif (!check_url_exits($profilePath) && $gender == "Female" or $img_name == "") {
                     echo FULL_WEBSITE_URL . "user/uploads/female_user.png";
                 } else {
                     echo $profilePath;
                 } ?>">
        </div>
        <div class="client-name client-name-theme2">

            <h1 class="text-color-p"><?php if (isset($name)) echo $name; ?>
                <?php

                if ($verify_user == 1) {
                    ?>
                    <img class="blue-tick" src="<?php echo FULL_MOBILE_URL; ?>assets/images/icon/blue_tick.png">
                <?php
                }
                ?>
            </h1>

            <h3 class="text-color-p"><?php if (isset($designation)) echo $designation; ?></h3>
            <?php
            if ($getReviews['rating_num'] !=0) {
                if (!$user_expired_status) {
                    if ($getReviews != null) {
                        if ($getReviews['average_rating'] <= "2") {
                            $rating = "30";
                        } elseif ($getReviews['average_rating'] <= "3") {
                            $rating = "45";
                        } elseif ($getReviews['average_rating'] <= "4") {
                            $rating = "60";
                        } elseif ($getReviews['average_rating'] <= "5") {
                            $rating = "75";
                        }
                        ?>
                        <g id="G-REVIEW-STARS_21 qw">
                            <a class="text-color-p" href="<?php echo get_url_param_for_mobile('testimonial.php'); ?>">
                                <?php echo $getReviews['average_rating']; ?> <span><i
                                        class="fa fa-star <?php if ($getReviews['average_rating'] >= "0.5") {
                                            echo "fill-star";
                                        } ?>"></i><i
                                        class="fa fa-star <?php if ($getReviews['average_rating'] >= "1.5") {
                                            echo "fill-star";
                                        } ?>"></i><i
                                        class="fa fa-star <?php if ($getReviews['average_rating'] >= "2.5") {
                                            echo "fill-star";
                                        } ?>"></i><i
                                        class="fa fa-star <?php if ($getReviews['average_rating'] >= "3.5") {
                                            echo "fill-star";
                                        } ?>"></i><i
                                        class="fa fa-star <?php if ($getReviews['average_rating'] >= "4.5") {
                                            echo "fill-star";
                                        } ?>"></i></span> <?php echo $getReviews['rating_num']; ?> Reviews</a>
                        </g>
                    <?php
                    }
                }
            }

            ?>
        </div>
    </div>
    <div class="social-logo">
        <!--<ul class="social-ul">
                    <li><a <?php /*if ($youtube == "") {
                            echo "style='cursor: not-allowed;filter: grayscale(100%);'" . "target='_self'";
                        } else {
                            echo "target='_blank'";
                        } */ ?> href="<?php /*if (isset($youtube) && ($youtube) != "") {
                            echo $youtube;
                        } else {
                            echo "#";
                        } */ ?>" class="linkedin"><img src="<?php /*echo $youtube_icon; */ ?>"></a>
                    </li>
                    <li><a <?php /*if ($facebook == "") {
                            echo "style='cursor: not-allowed;filter: grayscale(100%);'" . "target='_self'";
                        } else {
                            echo "target='_blank'";
                        } */ ?> href="<?php /*if (isset($facebook) && ($facebook) != "") {
                            echo $facebook;
                        } else {
                            echo "#";
                        } */ ?>" class="facebook"> <img src="<?php /*echo $facebook_share_con; */ ?>"></a>
                    </li>
                    <li><a <?php /*if ($twitter == "") {
                            echo "style='cursor: not-allowed;filter: grayscale(100%);'" . "target='_self'";
                        } else {
                            echo "target='_blank'";
                        } */ ?> href="<?php /*if (isset($twitter) && ($twitter) != "") {
                            echo $twitter;
                        } else {
                            echo "#";
                        } */ ?>" class="twitter"><img src="<?php /*echo $twitter_icon; */ ?>"></a>
                    </li>
                    <li><a <?php /*if ($instagram == "") {
                            echo "style='cursor: not-allowed;filter: grayscale(100%);'" . "target='_self'";
                        } else {
                            echo "target='_blank'";
                        } */ ?> href="<?php /*if (isset($instagram) && ($instagram) != "") {
                            echo $instagram;
                        } else {
                            echo "#";
                        } */ ?>" class="instagram"><img src="<?php /*echo $instagram_icon; */ ?>"></a>
                    </li>
                    <li><a <?php /*if ($linked_in == "") {
                            echo "style='cursor: not-allowed;filter: grayscale(100%);'" . "target='_self'";
                        } else {
                            echo "target='_blank'";
                        } */ ?> href="<?php /*if (isset($linked_in) && ($linked_in) != "") {
                            echo $linked_in;
                        } else {
                            echo "#";
                        } */ ?>" class="linkedin"><img
                                src="<?php /*echo $linked_in_icon; */ ?>"></a></li>
                </ul>-->
        <ul class="cust_theme2_contact">
            <li>
                <!--<a --><?php /*if($paymentModel){ echo "disabled"; }else{ echo 'data-target="#paymentModelProfile" data-toggle="modal"'; } */ ?>
                <a href="tel:<?php if (isset($contact_no)) echo "+" . $country_code . $contact_no; ?>"
                   class="profile-img-a">
                    <div class="p-align">
                        <div class=" cust_them2_icon"><i class="fa fa-phone"></i></div>
                        <!--<p></p>--></div>
                </a></li>
            <li>
                <!--<a --><?php /*if($paymentModel){ echo "disabled"; }else{ echo 'data-target="#paymentModelProfile" data-toggle="modal"'; } */ ?>
                <a target="_blank" href="https://api.whatsapp.com/send?phone=<?php if ($whatsapp_no != '') {
                    echo $country_code . $whatsapp_no;
                } elseif ($contact_no != '') echo $country_code . $contact_no; ?>"
                   class="profile-img-a">
                    <div class="p-align">
                        <div class=" cust_them2_icon"><i class="fa fa-whatsapp"></i></div>
                        <!--<p></p>--></div>
                </a></li>

            <li><a href="mailto:<?php if (isset($email)) echo $email; ?>"
                   class="profile-img-a">
                    <div class="p-align">
                        <div class=" cust_them2_icon"><i class="fas fa-envelope"></i></div>
                        <!--<p></p>--></div>
                </a></li>
            <?php /*if (isset($website) && ($website) != "") {*/ ?>
            <li><a target="_blank" href="

                        <?php if (isset($website) && ($website) != "") {
                    echo urlChecker($website);
                } else {
                    echo "#";
                } ?>"
                   class="profile-img-a">
                    <div class="p-align">
                        <div class=" cust_them2_icon
                                    <?php if (isset($website) && ($website) == "") {
                            echo "disabled-icon";
                        } ?>"><i class="fas fa-globe-europe"></i>
                        </div>
                        <!--<p></p>--></div>
                </a></li><?php /*}if (isset($map_link) && ($map_link) != "") {*/ ?>
            <?php /*}*/ ?>
            <li><a href="
                        <?php if (isset($map_link) && ($map_link) != "") {
                    echo urlChecker($map_link);
                } else {
                    echo "#";
                } ?>" class="profile-img-a">
                    <div class="p-align">
                        <div class=" cust_them2_icon
                                    <?php if (isset($map_link) && ($map_link) == "") {
                            echo "disabled-icon";
                        } ?>
                                    "><i class="fas fa-map-marker-alt"></i>
                        </div>
                        <!--<p></p>--></div>
                </a></li>
        </ul>
    </div>
<?php
}

if ($user_expired_status) {
    ?>
    <div class="expire_user_contact">
        <h4>Contact Info</h4>
    </div>
    <div class="expire_div_ul">
        <ul class="contact-ul">

            <li>
                <!--<a --><?php /*if($paymentModel){ echo "disabled"; }else{ echo 'data-target="#paymentModelProfile" data-toggle="modal"'; } */ ?>
                <a target="_blank" href="https://api.whatsapp.com/send?phone=<?php if ($whatsapp_no != '') {
                    echo $country_code . $whatsapp_no;
                } elseif ($contact_no != '') echo $country_code . $contact_no; ?>"
                   class="profile-img-a">
                    <div class="p-align">
                        <div class="contact-icon-btm expire_user_icon"><i class="fa fa-whatsapp"></i></div>
                        <p>WhatsApp</p></div>
                </a></li>
            <li><a href="mailto:<?php if (isset($email)) echo $email; ?>"
                   class="profile-img-a">
                    <div class="p-align">
                        <div class="contact-icon-btm expire_user_icon"><i class="fas fa-envelope"></i></div>
                        <p>Email</p></div>
                </a></li>
            <?php /*if (isset($website) && ($website) != "") {*/ ?>
            <li><a target="_blank" href="

                        <?php if (isset($website) && ($website) != "") {
                    echo $website;
                } else {
                    echo "#";
                } ?>"
                   class="profile-img-a">
                    <div class="p-align">
                        <div class="contact-icon-btm expire_user_icon
                                    <?php if (isset($website) && ($website) == "") {
                            echo "disabled-icon";
                        } ?>"><i class="fas fa-globe-europe"></i>
                        </div>
                        <p>Website</p></div>
                </a></li><?php /*}if (isset($map_link) && ($map_link) != "") {*/ ?>
            <?php /*}*/ ?>
            <li><a href="
                        <?php if (isset($map_link) && ($map_link) != "") {
                    echo urlChecker($map_link);
                } else {
                    echo "#";
                } ?>" class="profile-img-a">
                    <div class="p-align">
                        <div class="contact-icon-btm expire_user_icon
                                    <?php if (isset($map_link) && ($map_link) == "") {
                            echo "disabled-icon";
                        } ?>
                                    "><i class="fas fa-map-marker-alt"></i>
                        </div>
                        <p>Map</p></div>
                </a></li>
            <li><a <?php if ($youtube == "") {
                    echo "style='cursor: not-allowed;filter: grayscale(100%);'" . "target='_self'";
                } else {
                    echo "target='_blank'";
                } ?> href="<?php if (isset($youtube) && ($youtube) != "") {
                    echo urlChecker($youtube);
                } else {
                    echo "#";
                } ?>">
                    <div class="contact-icon-btm expire_user_icon"><i class="fa fa-youtube-play" aria-hidden="true"></i>
                    </div>
                    <p>YouTube</p></a>
            </li>

            <li><a <?php if ($facebook == "") {
                    echo "style='cursor: not-allowed;filter: grayscale(100%);'" . "target='_self'";
                } else {
                    echo "target='_blank'";
                } ?> href="<?php if (isset($facebook) && ($facebook) != "") {
                    echo urlChecker($facebook);
                } else {
                    echo "#";
                } ?>">
                    <div class="contact-icon-btm expire_user_icon"><i class="fa fa-facebook-square"
                                                                      aria-hidden="true"></i></div>
                    <p>Facebook</p></a>
            </li>

            <li><a <?php if ($twitter == "") {
                    echo "style='cursor: not-allowed;filter: grayscale(100%);'" . "target='_self'";
                } else {
                    echo "target='_blank'";
                } ?> href="<?php if (isset($twitter) && ($twitter) != "") {
                    echo urlChecker($twitter);
                } else {
                    echo "#";
                } ?>">
                    <div class="contact-icon-btm expire_user_icon"><i class="fa fa-twitter" aria-hidden="true"></i>
                    </div>
                    <p>Twitter</p></a>
            </li>
            <li><a <?php if ($instagram == "") {
                    echo "style='cursor: not-allowed;filter: grayscale(100%);'" . "target='_self'";
                } else {
                    echo "target='_blank'";
                } ?> href="<?php if (isset($instagram) && ($instagram) != "") {
                    echo urlChecker($instagram);
                } else {
                    echo "#";
                } ?>">
                    <div class="contact-icon-btm expire_user_icon"><i class="fab fa-instagram"></i></div>
                    <p>Instagram</p></a>
            </li>
            <li><a <?php if ($linked_in == "") {
                    echo "style='cursor: not-allowed;filter: grayscale(100%);'" . "target='_self'";
                } else {
                    echo "target='_blank'";
                } ?> href="<?php if (isset($linked_in) && ($linked_in) != "") {
                    echo urlChecker($linked_in);
                } else {
                    echo "#";
                } ?>">
                    <div class="contact-icon-btm expire_user_icon"><i class="fab fa-linkedin-in"></i></div>
                    <p>Linked In</p></a></li>
            <?php if ($playstore != "") {
                ?>
                <li><a <?php if ($playstore == "") {
                        echo "style='cursor: not-allowed;filter: grayscale(100%);'" . "target='_self'";
                    } else {
                        echo "target='_blank'";
                    } ?> href="<?php if (isset($playstore) && ($playstore) != "") {
                        echo urlChecker($playstore);
                    } else {
                        echo "#";
                    } ?>">
                        <div class="contact-icon-btm expire_user_icon"><i class="fab fa-google-play"></i></div>
                        <p>Play Store</p></a></li>
            <?php
            }
            ?>

        </ul>

    </div>

<?php
}

?>


<!--<div class="fb-share-button"
     data-href="http://sharedigitalcard.com/m/index.php?custom_url=kubictechnology"
     data-layout="button_count">
</div>-->

<div id="fb-root"></div>
<div id="myModal" class="profile-modal">
    <img class="modal-content" id="img01">
</div>
<div id="coverPicModal" class="profile-modal">
    <img class="modal-content" id="img02">
</div>

<!-- The Modal -->


