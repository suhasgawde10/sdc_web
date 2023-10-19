<?php

if (isset($_GET['custom_url'])) {
    if($parent_id !=""){
        $custom_url = $getParentData['custom_url'];
    }else{
        $custom_url = $_GET['custom_url'];
    }

    $get_result = $manage->mdm_getDigitalCardDetails("team",$custom_url);
}
$section_id = 6;
$get_section_theme = $manage->mdm_displaySectionTheme($default_user_id,$section_id);
if($get_section_theme !=null){
    $section_theme = $get_section_theme['theme_id'];
}else{
    $section_theme = 1;
}

?>

<div class="bhoechie-tab-content margin-padding-remover active">
    <section>

        <div class="content-main  background-theme-cust">
            <div class="service_header">
                <div class="all-main-heading">
                    <span class="text-color-p"><?php echo $our_team; ?></span>
                   <!-- <?php /*if (isset($_SESSION['email'])) { */?> <a title="Add Service" class="add-icon-color fas fa-pencil-alt" href=<?php /*echo FULL_WEBSITE_URL; */?>."user/our-team.php">&nbsp;&nbsp;Edit</a>
                    --><?php /*} */?>
                </div>
            </div>

            <div class="cust-coverlay overlay-height">


                <div class="container-fluid padding-remover">
                    <div class="bank-up-div">
                        <div class="row padd-bot scrollbar style-11">
                            <?php
                            if ($get_result != null) {
                            ?>
                            <ul class="our-team-ul">
                                <?php
                                while ($result_data = mysqli_fetch_array($get_result)) {
                                    $team_path = FULL_WEBSITE_URL."user/uploads/" . $result_data['email'] . "/our-team/" . $result_data['img_name'];
                                    if($section_theme == 1) {
                                        ?>
                                        <li>
                                            <div class="our-team">
                                                <div class="pic">
                                                    <img src="<?php if (check_url_exits($team_path) && $result_data['img_name'] != "") {
                                                        echo $team_path;
                                                    } else {
                                                        echo FULL_WEBSITE_URL."user/uploads/user.png";
                                                    } ?>">
                                                </div>
                                                <div class="team-content">
                                                    <h3 class="title"><?php echo $result_data['name']; ?></h3>
                                                    <span class="post"><?php echo $result_data['designation']; ?></span>
                                                </div>
                                                <?php
                                                if ($result_data['dg_link'] != '' OR $result_data['c_number'] != '' OR $result_data['w_number'] != '') {
                                                    ?>
                                                    <div class="btn-group" style="width: 100%">
                                                        <?php
                                                        if ($result_data['c_number'] != '') {
                                                            ?>
                                                            <a href="tel:<?php
                                                            echo $result_data['c_number'];
                                                            ?>" <?php if ($result_data['w_number'] == '') echo 'style=width:100%'; ?>
                                                               class="btn our_team_opt_bn btn-primary"><i
                                                                        class="fa fa-phone" aria-hidden="true"></i> Call
                                                                Now</a>
                                                            <?php
                                                        }
                                                        if ($result_data['w_number'] != '') {
                                                            ?>
                                                            <a target="_blank"
                                                               href="https://api.whatsapp.com/send?phone=<?php
                                                               echo $country_code . $result_data['w_number'];
                                                               ?>" <?php if ($result_data['c_number'] == '') echo 'style=width:100%'; ?>
                                                               class="btn our_team_opt_bn btn-primary"><i
                                                                        class="fab fa-whatsapp"></i> WhatsApp</a>

                                                            <?php
                                                        }
                                                        if ($result_data['dg_link'] != '') {
                                                            ?>
                                                            <a target="_blank"
                                                               href="<?php echo $result_data['dg_link']; ?>"
                                                               class="btn form-control read_more_btn btn-primary"><i
                                                                        class="fas fa-external-link-alt"></i> View
                                                                Digital Card</a>
                                                            <?php

                                                        }
                                                        ?>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                                <div>

                                                    <!--                                            <ul class="our-team-ul-optional">-->
                                                    <!--
                                                                                                                 src="../assets/img/logo/favicon.png"></a></li>-->
                                                    <!--                                                   -->
                                                    <!--                                                    <li><a ><img src="<?php echo FULL_DESKTOP_URL; ?>assets/images/phone.png"-->
                                                    <!--                                                                 style="width: 75%"></a></li>-->
                                                    <!--                                                    --><?php
                                                    //                                                }
                                                    //
                                                    //
                                                    ?>
                                                    <!--                                                    <li><a ><img-->
                                                    <!--                                                                src="data:image/png;base64,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"></a>-->
                                                    <!--                                                    </li>-->
                                                    <!--                                                    --><?php
                                                    //                                                }
                                                    //
                                                    ?>
                                                    <!--                                            </ul>-->

                                                </div>

                                            </div>
                                        </li>
                                        <?php
                                    }else{ ?>
                                        <li>
                                            <div class="team-area team-items">
                                                <div class="single-item">
                                                    <div class="item">
                                                        <div class="thumb">
                                                            <img class="img-fluid" src="<?php if (check_url_exits($team_path) && $result_data['img_name'] != "") {
                                                                echo $team_path;
                                                            } else {
                                                                echo FULL_WEBSITE_URL."user/uploads/user.png";
                                                            } ?>" alt="Thumb">
                                                           <!-- <div class="overlay">
                                                                <h4><?php /*echo $result_data['name']; */?></h4>
                                                                <p>
                                                                    <?php /*echo $result_data['designation']; */?>
                                                                </p>
                                                            </div>-->
                                                        </div>
                                                        <div class="theme2-info">
                                                            <?php
                                                            if ($result_data['dg_link'] != '' OR $result_data['c_number'] != '' OR $result_data['w_number'] != '') {
                                                                ?>
                                                                    <?php
                                                                    if ($result_data['c_number'] != '') {
                                                                        ?>
                                                                        <span class="theme2_call">
                                    <a href="tel:<?php
                                    echo $result_data['c_number'];
                                    ?>"><i class="fas fa-phone"></i></a>
                                </span>
                                                                        <?php
                                                                    }
                                                                    if ($result_data['w_number'] != '') {
                                                                        ?>
                                                                        <span class="message">
                                    <a  target="_blank"
                                        href="https://api.whatsapp.com/send?phone=<?php
                                        echo $country_code . $result_data['w_number'];
                                        ?>"><i class="fab whastapp_theme2_icon fa-whatsapp"></i></a>
                                </span>
                                                                        <?php
                                                                    }
                                                                    if ($result_data['dg_link'] != '') {
                                                                        ?>
                                                                        <span class="theme2_whatsapp">
                                    <a  target="_blank"
                                        href="<?php echo $result_data['dg_link']; ?>"><i class="fas fa-external-link-alt"></i></a>
                                </span>
                                                                        <?php

                                                                    }
                                                                    ?>
                                                                <?php
                                                            }
                                                            ?>
                                                            <h4><?php echo $result_data['name']; ?></h4>
                                                            <span><?php echo $result_data['designation']; ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </li>
                                <?php
                                }}
                                ?>
                            </ul>
                            <?php  }else{ ?>
                            <div class="col-lg-12">
                                <div class="col-lg-8 col-lg-offset-2">
                                    <div class="text-center no_data_found">
                                        <img src="<?php echo $team_not_found; ?>">
                                        <h5>Our Team Details will Appear Soon in this Section.</h5>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </section>
</div>