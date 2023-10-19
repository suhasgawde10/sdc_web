<?php


// include_once '../../../whitelist.php';
require_once "../controller/ManageDesktopCard.php";
$manage = new ManageDesktopCard();
include 'assets/common-includes/count-includes.php';

if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
    $link = "https";
else
    $link = "http";
$link .= "://";
$link = "";
$link .= $_SERVER['HTTP_HOST'];

$link .= $_SERVER['REQUEST_URI'];

$verify_user = $manage->displayVerifiedUser($user_id);

$get_country = $manage->mdm_getCountryCode($country);
if ($get_country != null) {
    $country_code = $get_country['phonecode'];
} else {
    $country_code = "91";
}
if ($parent_id != '') {
    $user_parent_id = $parent_id;
} else {
    $user_parent_id = $user_id;
}

$get_cover_data = $manage->getCoverImageOfUser($user_parent_id);
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


$getReviews = $manage->getTotalReviews($user_id);
//print_r($getReviews);

function rep_escape($string)
{
    return str_replace(['\r\n', '\r', '\n', '\\'], '', $string);
}

$domain_link = $get_data['domain_link'];
if (isset($domain_link) && $domain_link != '') {
    $final_link = $domain_link;
} else {
    $final_link = SHARED_URL . $_GET['custom_url'];
}
if (isset($_POST['send'])) {
    $error = false;
    $contact_no = $_POST['contact_no'];
    if (is_array($contact_no)) {
        for ($i = 0; $i < count($contact_no); $i++) {
            $message = "Hello ,\nPlease click on below link to check Digital Card! :)\n" . $final_link;
            $sendSMS = $manage->sendSMS($contact_no[$i], $message);
            if ($sendSMS) {
                $error = false;
                $errorMessage = "Digital card has been sent successfully.";
            } else {
                $error = true;
                $errorMessage .= "something when wrong while sending sms to " . $contact_no[$i];
            }
        }
    }

}
if (isset($_POST['sendEmail'])) {
    $txt_email = $_POST['txt_email'];
    $error1 = false;
    $toName = $name;
    $subject = $name . " has sent you a digital card";
    $message1 = "Hello ,<br> Please click on below link to check Digital Card!<br>";
    $message1 .= "<a href='$final_link'> $final_link </a>";
    if (is_array($txt_email)) {
        for ($i = 0; $i < count($txt_email); $i++) {
            $sendMail = $manage->sendMail($toName, $txt_email[$i], $subject, $message1);
            if ($sendMail) {
                $error1 = false;
                $errorMessage1 = "Digital card has been sent successfully.";
            } else {
                $error1 = true;
                $errorMessage1 = "something when wrong while sending email to " . $txt_email[$i];
            }
        }
    }
}

$get_section_theme = $manage->mdm_displaySectionTheme($user_id, 0);
if ($get_section_theme != null) {
    $profile_section_theme = $get_section_theme['theme_id'];
} else {
    $profile_section_theme = 1;
}

$total_page_count = $manage->mdm_totalPageCount($user_id);
if ($total_page_count['total_count'] != null) {
    $home_page_count = $total_page_count['total_count'];
} else {
    $home_page_count = 0;
}
function parse_url_all($url)
{
    $url = substr($url, 0, 4) == 'http' ? $url : 'http://' . $url;
    $d = parse_url($url);
    $tmp = explode('.', $d['host']);
    $n = count($tmp);
    if ($n >= 2) {
        if ($n == 4 || ($n == 3 && strlen($tmp[($n - 2)]) <= 3)) {
            $d['domain'] = $tmp[($n - 3)] . "." . $tmp[($n - 2)] . "." . $tmp[($n - 1)];
            $d['domainX'] = $tmp[($n - 3)];
        } else {
            $d['domain'] = $tmp[($n - 2)] . "." . $tmp[($n - 1)];
            $d['domainX'] = $tmp[($n - 2)];
        }
    }
    return $d;
}

$getDomain = $manage->getAllBusinessLinksById($user_id);

?>
<div id="snackbar"></div>
<!--<div id="snackbar"></div>-->
<div class="col-md-4 <?php
if ($user_expired_status) {
    echo "col-md-offset-4";
} ?>">
    <div class="row">
        <div class="content-main content-main-height">
            <?php
            if (!$user_expired_status) {
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
                        echo "<img src='" . FULL_WEBSITE_URL . "user/uploads/admin_background.jpg' style='width: 100%' id='myImg2'>";
                    } elseif (check_url_exits($cover_first_img_path) && $coverCount == 1) {
                        echo '<img src="' . $cover_first_img_path . '"  style="width:100%" id="myImg2">';
                    } elseif ($coverCount > 1) {
                        ?>
                        <div class="owl-slider">
                            <div id="carousel" class="owl-carousel">
                                <!-- Indicators -->
                                <!-- <ol class="carousel-indicators">
                            <?php
                                /*                            $i = 1;
                                                            foreach ($key_data as $key) {
                                                                */
                                ?>
                                <li data-target="#carousel-example-generic" data-slide-to="0" <?php /*if($i == 1) echo 'class="active"'; */ ?>></li>
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

                            <!-- Controls -->
                            <!--<a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>-->
                        </div>
                    <?php
                    } else {
                        echo "<img src='" . FULL_WEBSITE_URL . "user/uploads/admin_background.jpg' style='width: 100%' id='myImg2'>";
                    } ?>
                    <?php
                    if ($profile_section_theme == 2) {
                        ?>

                        <div class="flyout_modal_button theme2_share_icon">
                            <i class="fa fa-share-alt"></i>
                        </div>
                    <?php
                    }
                    ?>

                    <!-- <span><i class="fa fa-search-plus zoom-icon"></i></span>-->
                </div>
            <?php
            }
            ?>
            <div class="overlay overlay-height">
                <?php
                if ($profile_section_theme == 1) {

                    /*  if (strpos($_SERVER['HTTP_HOST'], 'sharedigitalcard.com') !== false) {

                      } else {
                          echo $profilePath;
                          die();
                      }*/

                    ?>
                    <div class="profile-img " <?php if ($user_expired_status) echo 'style="padding-top:35px;"'; ?>>

                        <img id="myImg" class="img-circle"
                             src="<?php if (!check_url_exits($profilePath) && $gender == "Male" or $img_name == "") {
                                 echo FULL_WEBSITE_URL . "user/uploads/male_user.png";
                             } elseif (!check_url_exits($profilePath) && $gender == "Female" or $img_name == "") {
                                 echo FULL_WEBSITE_URL . "user/uploads/female_user.png";
                             } else {
                                 echo $profilePath;
                             } ?>">

                        <!--<img id="myImg" class="img-circle"
             src="<?php
                        /*             echo $profilePath;
                                     */ ?>" onerror="this.src='<?php /*echo FULL_WEBSITE_URL; */ ?>user/uploads/male_user.png'">-->
                        <!--<div class="visitor_count">
                            <i class="fa fa-eye"></i> visited <?php /*echo $home_page_count; */ ?>
                        </div>-->
                    </div>
                    <div class="whats-app">
                        <a target="_blank"
                           href="https://api.whatsapp.com/send?phone=<?php echo $country_code . $whatsapp_no; ?>"><img
                                class="whats-app-logo" src="<?php echo FULL_DESKTOP_URL ?>/assets/images/icon/whatsapp.png"></a>
                        <!--<a data-target="#myModal" data-toggle="modal"><img class="share-logo"
                                                                           src=""></a>-->
                        <!-- <?php echo $share_icon; ?> -->
                        <div class="flyout_modal_button"> <!-- data-target="#myModal" data-toggle="modal" -->
                            <a><img class="share-logo" href="javascript:void(0);"
                                    src="<?php echo FULL_DESKTOP_URL; ?>assets/images/icon/share-card.png"></a>
                        </div>
                    </div>
                    <div class="client-name">
                        <h1 class="text-color-p"><?php if (isset($name)) echo $name; ?><?php

                            if ($verify_user == 1) {
                                ?>
                                <img class="blue-tick"
                                     src="<?php echo FULL_DESKTOP_URL; ?>assets/images/icon/blue_tick.png">
                            <?php
                            }
                            ?>
                        </h1>

                        <h3 class="text-color-p"><?php if (isset($designation)) echo $designation; ?></h3>

                        <?php
                        if($getReviews['rating_num'] !=0){
                            if (!$user_expired_status) {
                                if ($getReviews != null) {
                                    if ($getReviews['average_rating'] <= "1") {
                                        $rating = "15";
                                    } elseif ($getReviews['average_rating'] <= "2") {
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
                                        <a class="text-color-p"
                                           href="<?php echo FULL_DESKTOP_URL . "testimonial" . get_full_param(); ?>"><?php echo $getReviews['average_rating']; ?>
                                            <span id="SPAN_22"><span id="SPAN_23" style="width: <?php echo $rating . "px"; ?>;"></span></span> <?php echo $getReviews['rating_num']; ?>Reviews</a>
                                    </g>
                                <?php
                                }
                            }
                        }

                        ?>
                    </div>
                <?php
                } elseif ($profile_section_theme == 2) {

                    /*    if (strpos($_SERVER['HTTP_HOST'], 'sharedigitalcard.com') !== false) {

                        } else {
                            echo $profilePath;
                            die();
                        }*/

                    ?>

                    <?php
                    if (strpos($_SERVER['HTTP_HOST'], 'sharedigitalcard.com') !== false) {
                        ?>

                        <div class="profile-img-theme2 ">
                            <img id="myImg"
                                 src="<?php if (!check_url_exits($profilePath) && $gender == "Male" or $img_name == "") {
                                     echo FULL_WEBSITE_URL . "user/uploads/male_user.png";
                                 } elseif (!check_url_exits($profilePath) && $gender == "Female" or $img_name == "") {
                                     echo FULL_WEBSITE_URL . "user/uploads/female_user.png";
                                 } else {
                                     echo $profilePath;
                                 } ?>">

                        </div>
                    <?php
                    } else {
                        ?>
                        <div class="profile-img-theme2 ">
                            <img id="myImg"
                                 src="<?php
                                 echo $profilePath;
                                 ?>">

                        </div>
                    <?php
                    }
                    ?>
                    <div class="client-name client-name-theme2">
                        <h1 class="text-color-p"><?php if (isset($name)) echo $name; ?><?php
                            if ($verify_user == 1) {
                                ?>
                                <img class="blue-tick"
                                     src="<?php echo FULL_DESKTOP_URL; ?>assets/images/icon/blue_tick.png">
                            <?php
                            }
                            ?>
                        </h1>

                        <h3 class="text-color-p"><?php if (isset($designation)) echo $designation; ?></h3>
                        <?php

                        if ($getReviews['rating_num'] !=0) {
                            if ($getReviews['average_rating'] <= "1") {
                                $rating = "15";
                            } elseif ($getReviews['average_rating'] <= "2") {
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
                                <a class="text-color-p"
                                   href="<?php echo FULL_DESKTOP_URL . "testimonial" . get_full_param(); ?>"><?php echo $getReviews['average_rating']; ?>
                                    <span id="SPAN_22"><span id="SPAN_23"
                                                             style="width: <?php echo $rating . "px"; ?>;"></span></span> <?php echo $getReviews['rating_num']; ?>
                                    Reviews</a>
                            </g>
                        <?php
                        }
                        ?>
                    </div>
                <?php
                }
                ?>
                <div class="social-logo">
                    <ul class="social-ul">
                        <?php
                        if ($profile_section_theme == 2) {
                            ?>
                            <li>

                                <a target="_blank"
                                   href="https://api.whatsapp.com/send?phone=<?php echo $country_code . $whatsapp_no; ?>"><img
                                        src="<?php echo FULL_DESKTOP_URL ?>/assets/images/icon/whatsapp.png"></a>
                            </li>
                        <?php
                        }
                        ?>

                        <?php
                        if ($youtube != "" || $soacil_media_status != 1) {
                            ?>
                            <li><a <?php if ($youtube == "") {
                                    echo "style='cursor: not-allowed;filter: grayscale(100%);'" . "target='_self'";
                                } else {
                                    echo "target='_blank'";
                                } ?> href="<?php if (isset($youtube) && ($youtube) != "") {
                                    echo urlChecker($youtube);
                                } else {
                                    echo "#";
                                } ?>" class="linkedin"><img src="<?php echo FULL_DESKTOP_URL ?>/assets/images/icon/youtube2.png"></a>
                            </li>
                        <?php
                        } ?>


                        <?php
                        if ($facebook != "" || $soacil_media_status != 1) {
                            ?>
                            <li>
                                <a <?php if ($facebook == "") {
                                    echo "style='cursor: not-allowed;filter: grayscale(100%);'" . "target='_self'";
                                } else {
                                    echo "target='_blank'";
                                } ?> href="<?php if (isset($facebook) && ($facebook) != "") {
                                    echo $facebook;
                                } else {
                                    echo "#";
                                } ?>" class="facebook"> <img src="<?php echo FULL_DESKTOP_URL ?>/assets/images/icon/facebook2.png">
                                </a>
                            </li>
                        <?php
                        }
                        ?>

                        <?php
                        if ($twitter != "" || $soacil_media_status != 1) {
                            ?>
                            <li><a <?php if ($twitter == "") {
                                    echo "style='cursor: not-allowed;filter: grayscale(100%);'" . "target='_self'";
                                } else {
                                    echo "target='_blank'";
                                } ?> href="<?php if (isset($twitter) && ($twitter) != "") {
                                    echo $twitter;
                                } else {
                                    echo "#";
                                } ?>" class="twitter"><img src="<?php echo FULL_DESKTOP_URL ?>/assets/images/icon/twitter.png"></a>
                            </li>
                        <?php
                        }
                        ?>
                        <?php
                        if ($instagram != "" || $soacil_media_status != 1) {
                            ?>
                            <li><a <?php if ($instagram == "") {
                                    echo "style='cursor: not-allowed;filter: grayscale(100%);'" . "target='_self'";
                                } else {
                                    echo "target='_blank'";
                                } ?> href="<?php if (isset($instagram) && ($instagram) != "") {
                                    echo $instagram;
                                } else {
                                    echo "#";
                                } ?>" class="instagram"><img src="<?php echo FULL_DESKTOP_URL ?>/assets/images/icon/instagram.png"></a>
                            </li>
                        <?php
                        }
                        ?>
                        <?php
                        if ($linked_in != "" || $soacil_media_status != 1) {
                            ?>
                            <li><a <?php if ($linked_in == "") {
                                    echo "style='cursor: not-allowed;filter: grayscale(100%);'" . "target='_self'";
                                } else {
                                    echo "target='_blank'";
                                } ?> href="<?php if (isset($linked_in) && ($linked_in) != "") {
                                    echo $linked_in;
                                } else {
                                    echo "#";
                                } ?>" class="linkedin"><img
                                        src="<?php echo FULL_DESKTOP_URL ?>/assets/images/icon/linkedin.png"></a>
                            </li>
                        <?php
                        }
                        ?>
                        <?php
                        if ($playstore != "") {
                            ?>
                            <li><a <?php if ($playstore == "") {
                                    echo "style='cursor: not-allowed;filter: grayscale(100%);'" . "target='_self'";
                                } else {
                                    echo "target='_blank'";
                                } ?> href="<?php if (isset($playstore) && ($playstore) != "") {
                                    echo $playstore;
                                } else {
                                    echo "#";
                                } ?>" class="playstore"><img
                                        src="<?php echo FULL_DESKTOP_URL; ?>assets/images/icon/playstore.png"></a></li>
                        <?php
                        }
                        if ($getDomain != null) {
                            while ($rowDomain = mysqli_fetch_array($getDomain)) {
                                $domain_full_url = parse_url_all($rowDomain['link']);
                                if ($domain_full_url['domain'] == "t.me") {
                                    $domain_image_url = FULL_WEBSITE_URL . "assets/img/business-icon/" . $domain_full_url['domain'] . ".png";
                                } else {
                                    $domain_image_url = FULL_WEBSITE_URL . "assets/img/business-icon/" . $domain_full_url['domainX'] . ".png";
                                }

                                if (!check_url_exits($domain_image_url)) {
                                    $domain_image_url = FULL_WEBSITE_URL . "assets/img/business-icon/browser.png";
                                }
                                ?>
                                <li><a target='_blank' href="<?php echo urlChecker($rowDomain['link']); ?>"
                                       class="playstore"><img
                                            src="<?php echo $domain_image_url; ?>"
                                            onerror="this.src='<?php echo FULL_WEBSITE_URL; ?>assets/img/business-icon/browser.png'"></a>
                                </li>
                            <?php
                            }
                        }
                        ?>

                    </ul>
                </div>

                <div class="contact_info_scroll">
                    <div class="contact-heading">
                        <div class="row cust-margin">
                            <div class="col-xs-12">
                                <h4 class="contact-h4 text-color-p">Contact Information <a
                                        href="<?php echo get_url_param_for_mobile('export-vcf.php'); ?>"
                                        class="cust_them2_icon cust_save_contact"
                                        title="Export to vCard"><i class="fa fa-download"></i> &nbsp;Save Contact</a>
                                </h4>
                            </div>

                        </div>
                    </div>
                    <div class="deatil-table">
                        <table class="info-table">
                            <?php
                            if ($get_data['display_country_code'] == 1) {
                                ?>
                                <tr>
                                    <td><i class="fa fa-mobile font-size-25"></i></td>
                                    <td><span><a class="text-color-p"
                                                 href="tel:<?php if (isset($contact_no)) echo "+".$country_code . $contact_no; ?>"><?php if (isset($contact_no)) echo $country_code . $contact_no; ?></a><a
                                                class="text-color-p"
                                                href="tel:<?php if (isset($altr_contact_no) && $altr_contact_no != "") echo "+".$country_code . $altr_contact_no; ?>"><?php if (isset($altr_contact_no) && $altr_contact_no != "") echo "&nbsp;/&nbsp;" . $country_code . $altr_contact_no; ?></a></span>
                                    </td>
                                </tr>
                            <?php
                            } else {
                                ?>
                                <tr>
                                    <td><i class="fa fa-mobile font-size-25"></i></td>
                                    <td><span><a class="text-color-p"
                                                 href="tel:<?php if (isset($contact_no)) echo $contact_no; ?>"><?php if (isset($contact_no)) echo $contact_no; ?></a><a
                                                class="text-color-p"
                                                href="tel:<?php if (isset($altr_contact_no) && $altr_contact_no != "") echo $altr_contact_no; ?>"><?php if (isset($altr_contact_no) && $altr_contact_no != "") echo "&nbsp;/&nbsp;" . $altr_contact_no; ?></a></span>
                                    </td>
                                </tr>
                            <?php
                            }
                            if (isset($landline_number) && $landline_number != '') {
                                ?>
                                <tr>
                                    <td><i class="fa fa-phone fa-flip-horizontal"></i></td>
                                    <td><span><a class="text-color-p"
                                                 href="tel:<?php echo $landline_number; ?>"><?php echo $landline_number; ?></a></span>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                            <tr>
                                <td><i class="fas fa-envelope"></i></td>
                                <td>
                                    <?php
                                    if (isset($saved_email) && $saved_email != "") {
                                        ?>
                                        <?php
                                        $display_email = explode(',', $saved_email);
                                        $i = 1;
                                        $len = count($display_email);
                                        foreach ($display_email as $key) {
                                            ?>
                                            <a target="_blank" class="text-color-p"
                                               href="mailto:<?php if (isset($key)) echo $key; ?>"><?php if (isset($key)) echo $key; ?></a>
                                            <?php if ($i != $len) echo " / ";
                                            $i++;
                                        } ?>
                                    <?php
                                    } else {
                                        ?>
                                        <a class="text-color-p"
                                           href="mailto:<?php if (isset($email)) echo $email; ?>"><?php if (isset($email)) echo $email; ?></a>
                                    <?php
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-globe-europe"></i></td>
                                <td><a class="text-color-p"
                                       href="<?php if (isset($website)) echo urlChecker($website); ?>"
                                       target="_blank"><?php if ($website != null) {
                                            echo $website;
                                        } else {
                                            echo "Website not available";
                                        } ?></a></td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-map-marker-alt"></i></td>
                                <td><p><a class="text-color-p"
                                          href="<?php if (isset($map_link)) echo urlChecker($map_link); ?>"
                                          target="_blank"><?php if ($address != null) {
                                                echo $address;
                                            } else {
                                                echo "Address not available";
                                            } ?></a></p></td>
                            </tr>
                        </table>

                        <!-- <div class="icon-bar">
                        <a href="mailto:<?php /*if (isset($email)) echo $email; */ ?>"
                           class="facebook"><i class="fas fa-envelope"></i></a>
                        <a href="<?php /*if (isset($website)) echo $website; */ ?>" target="_blank" class="facebook"><i
                                class="fas fa-globe-europe"></i></a>
                        <a href="<?php /*echo $map_link; */ ?>" target="_blank" class="facebook"><i
                                class="fas fa-map-marker-alt"></i></a>
                    </div>-->
                    </div>
                    <?php
                    if ($user_expired_status) {
                        if (strpos($_SERVER['HTTP_HOST'], 'sharedigitalcard.com') !== false) {
                            ?>
                            <div class="col-xs-12">
                                <div class="expire_div_dg">
                                    <div>
                                        <h3><img src="<?php echo FULL_WEBSITE_URL; ?>assets/img/logo/logo.png"> Share
                                            Digital Card</h3>
                                    </div>
                                    <div>
                                        <?php
                                        if (like_match('%dealer%', $referral_by) == 1) {
                                            $getDealer = $manage->getDealerProfile($referral_by);
                                            ?>
                                            <div class="text-center">
                                                <a class="" href="tel:<?php echo $getDealer['contact_no']; ?>"><i
                                                        class="fa fa-volume-control-phone" aria-hidden="true"></i> Call
                                                    Now</a>
                                            </div>
                                        <?php
                                        } else {
                                            ?>
                                            <div class="text-center">
                                                <a class=""
                                                   href="<?php echo "https://sharedigitalcard.com/" ?>demo-cards.php"
                                                   target="_blank"><i class="fa fa-external-link"
                                                                      aria-hidden="true"></i> Get Your
                                                    Free Digital Card</a>
                                            </div>

                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                    }

                    if (!$user_expired_status) {
                        if ($company_name != "") {
                            ?>
                            <div class="margin-top-company">
                                <div class="col-xs-12">
                                    <h4 class="contact-h4 text-color-p">Company Information</h4>

                                    <div class="deatil-table">

                                        <table class="company_table">
                                            <tbody>
                                            <tr>
                                                <td class="text-color-p">Name :</td>
                                                <td><span class="text-color-p"><?php echo $company_name; ?></span></td>
                                            </tr>
                                            <?php
                                            if ($country == "101") {
                                                ?>
                                                <tr>
                                                    <td class="text-color-p">GSTN No :</td>
                                                    <td class="text-color-p"><?php if ($gst_no != "") {
                                                            echo $gst_no;
                                                        } else {
                                                            echo "Gst not found";
                                                        } ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-color-p">PAN No :</td>
                                                    <td class="text-color-p"><?php if ($pan_no != "") {
                                                            echo $pan_no;
                                                        } else {
                                                            echo "Pan no not found";
                                                        } ?></td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                            </tbody>
                                        </table>

                                    </div>


                                </div>
                            </div>
                        <?php
                        }
                    }
                    ?>
                </div>

            </div>

        </div>
    </div>
</div>


<!--Modal-->
<div id="myModal1" class="profile-modal">
    <img class="modal-content" id="img01">
</div>
<div id="myModal2" class="profile-modal">
    <img class="modal-content" id="img02">
</div>

<div class="modal fade " id="myModal"
     role="dialog" style="z-index: 9999">
    <div class="modal-dialog cust-model-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bank-model-header">
                <button type="button" class="close cust-close"
                        data-dismiss="modal">&times;</button>
                <h4 class="modal-title cust-model-heading">Select an option</h4>
            </div>
            <div class="modal-body" style="overflow-y: auto;overflow-x: hidden;">


                <ul class="ul-chat-option">
                    <li><a onclick="emailDiv()" href="#"><img src="<?php echo FULL_DESKTOP_URL ?>/assets/images/icon/email.png"></a>

                        <p>Email</p>
                    </li>
                    <li><a onclick="smsDiv()" href="#"><img src="<?php echo FULL_DESKTOP_URL ?>/assets/images/icon/sms.png"></a>

                        <p>SMS</p>
                    </li>
                    <li><a target="_blank"
                           href="https://api.whatsapp.com/send?phone=&text=<?php if (isset($company_name) && $company_name != "") echo "*" . trim(urlencode($company_name)) . "*"; ?>%0A%0APlease%20click%20on%20below%20link%20to%20check%20Digital%20Card.%0A<?php
                           echo $final_link;
                           ?>"><img
                                class="whats-app-logo"><img src="<?php echo FULL_DESKTOP_URL ?>/assets/images/icon/whatsapp1.png"></a>

                        <p>Whatsapp</p>
                    </li>
                    <li>
                        <a style="cursor: pointer;"
                           onclick="setClipboard('<?php echo $final_link; ?>','URL is on the clipboard try to paste it!')"><img
                                src="<?php echo FULL_DESKTOP_URL ?>/assets/images/icon/copy.png"></a>

                        <p>Copy Link</p>
                    </li>
                </ul>

                <div id="smsDiv" class="col-sm-offset-1 col-sm-10 sms-body">
                    <h4 style="margin-top: 0px">Send SMS</h4>

                    <form method="post" action="">
                        <div class="text-center">
                            <div style="width: 100%; text-align: -webkit-center ">
                                <?php if ($error) {
                                    ?>
                                    <div class="alert alert-danger">
                                        <?php if (isset($errorMessage)) echo $errorMessage; ?>
                                    </div>
                                <?php
                                } else if (!$error && $errorMessage != "") {
                                    ?>
                                    <div class="alert alert-success" style="padding: 8px; font-size: 13px;">
                                        <?php if (isset($errorMessage)) echo $errorMessage; ?>
                                    </div>
                                <?php
                                }
                                ?>
                                <table style="width: 100%; text-align: -webkit-center;" rules="all">
                                    <tr id="rowId">
                                        <td>
                                            <div class="input-group" style="display: flex; margin-bottom: 10px">
                                                <span class="input-group-addon" style="width: 48.95px;">+91</span>
                                                <input name="contact_no[]" type="number"
                                                       class="form-control" placeholder="Enter Number"
                                                       required="required" autofocus
                                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                       maxlength="10"/>
                                                &nbsp;&nbsp;<span class="plus_icon" onclick="addMoreRows(this.form);"><i
                                                        class="fa fa-plus" aria-hidden="true"></i></span>
                                            </div>
                                        </td>

                                    </tr>

                                </table>
                            </div>
                        </div>

                        <div id="addedRows"></div>
                        <br>
                        <button class="btn btn-success" name="send" type="submit">Send SMS&nbsp;&nbsp;&nbsp;<i
                                class="fas fa-paper-plane"></i></button>
                    </form>
                </div>
                <div id="emailDiv" class="col-sm-offset-1 col-sm-10 sms-body">
                    <h4 style="margin-top: 0px">Send Email</h4>

                    <form method="post" action="">
                        <div class="text-center">
                            <div style="width: 100%; text-align: -webkit-center ">
                                <?php if ($error1) {
                                    ?>
                                    <div class="alert alert-danger">
                                        <?php if (isset($errorMessage1)) echo $errorMessage1; ?>
                                    </div>
                                <?php
                                } else if (!$error1 && $errorMessage1 != "") {
                                    ?>
                                    <div class="alert alert-success" style="padding: 8px; font-size: 13px;">
                                        <?php if (isset($errorMessage1)) echo $errorMessage1; ?>
                                    </div>
                                <?php
                                }
                                ?>
                                <table style="width: 100%; text-align: -webkit-center;" rules="all">

                                    <tr id="rowId">
                                        <td>

                                            <div class="input-group" style="display: flex; margin-bottom: 10px">
                                                        <span class="input-group-addon" style="width: 48.95px;"><i
                                                                class="glyphicon glyphicon-envelope"></i></span>
                                                <input name="txt_email[]" type="email" class="form-control"
                                                       autofocus placeholder="Enter Email" required="required"/>&nbsp;&nbsp;<span
                                                    class="plus_icon" onclick="addMoreRows1(this.form);">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    </span>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div id="addedRows1"></div>
                        <br>
                        <button class="btn btn-success" name="sendEmail" type="submit">Send Email&nbsp;&nbsp;&nbsp;<i
                                class="fas fa-paper-plane"></i></button>
                    </form>
                </div>
            </div>

        </div>

    </div>
</div>


<div id="fb-root"></div>
<script>(function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
