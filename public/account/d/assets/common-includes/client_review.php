<?php
if (isset($_GET['custom_url'])) {
    if($parent_id !=""){
        $custom_url = $getParentData['custom_url'];
    }else{
        $custom_url = $_GET['custom_url'];
    }

    $get_result = $manage->mdm_getDigitalCardDetails("client",$custom_url);

}

/* $section_service_id = 1;
 $get_service_status = $manage->displayOnOffStatus($custom_url, $section_service_id);
 $section_image_id = 2;
 $get_image_status = $manage->displayOnOffStatus($custom_url, $section_image_id);
 $section_video_id = 3;
 $get_video_status = $manage->displayOnOffStatus($custom_url, $section_video_id);*/
 $section_client_id = 4;
 $get_client_status = $manage->displayOnOffStatus($custom_url, $section_client_id);
 $section_client_review_id = 5;
 $get_client_review_status = $manage->displayOnOffStatus($custom_url, $section_client_review_id);
/* $section_our_team_id = 6;
 $get_our_team_status = $manage->displayOnOffStatus($custom_url, $section_our_team_id);
 $section_bank_id = 7;
 $get_bank_status = $manage->displayOnOffStatus($custom_url, $section_bank_id);*/


function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

?>
<div class="bhoechie-tab-content margin-padding-remover active">
    <section>
        <div class="content-main  background-theme-cust">
            <div class="all-main-heading">
                <span class="text-color-p"><?php echo $clients; ?></span>
                <?php /*if (isset($_SESSION['email'])) { */?><!-- <a title="Add Service"
                                                             class="add-icon-color fas fa-pencil-alt"
                                                             href=<?php /*echo FULL_WEBSITE_URL; */?>."user/testimonial.php">&nbsp;&nbsp;Edit</a>
                --><?php /*} */?>
            </div>
            <div class="cust-coverlay">
                <div class="bank-up-div">
                    <div class="card">
                        <ul class="nav nav-tabs" role="tablist">

                            <?php
/*                            if ($get_client_status != null) {
                                if (isset($_GET['custom_url']) && $get_client_status['digital_card'] == 1) { */?>
                                    <li role="presentation" class="active"><a href="#client-id"
                                                                              aria-controls="profile"
                                                                              role="tab" data-toggle="tab"><?php echo $client_name; ?></a>
                                    </li>
                                <?php /*}
                            } */?><!--
                            -->
                            <?php
                            /*                            if (isset($_GET['custom_url']) && $get_client_status['digital_card'] == 0) { */?><!--  --><?php /*} */?>
                            <?php

                            /*                            if ($get_client_review_status != null) {
                                                            if (isset($_GET['custom_url']) && $get_client_review_status['digital_card'] == 1) { */?>
                            <li role="presentation"  > <a
                                    href="#testimonial-id"
                                    aria-controls="home"
                                    role="tab"
                                    data-toggle="tab"><?php echo $client_review_tab; ?></a>
                            </li>
                            <!-- --><?php /*}
                            } */?>
                        </ul>

                        <!--
                        <?php
                        if (isset($_GET['custom_url']) && $get_client_status['digital_card'] == 0) { ?> active <?php } ?>
                        -->
                        <div class="bank-up-div">
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane "
                                     id="testimonial-id">

                                    <div class="container-fluid cust-container">
                                        <div class="row">
                                            <div class="col-md-12 margin_icon">
                                                <!-- <?php /*if (isset($_SESSION['email'])) { */ ?> <a title="Add Service" class="fas add-icon-color fa-plus-circle" href=FULL_WEBSITE_URL."user/testimonial.php"></a>
                                                --><?php /*} */ ?>
                                            </div>
                                            <div class="col-md-12 mt-10">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="flyout_modal_button_review">
<button class="btn btn-success"><i class="fa fa-edit"></i> Write a review</button>                                                        </div>

                                                    </div>
                                                    <div class="col-md-6 text-right">
                                                        <span style="vertical-align: -webkit-baseline-middle;padding-right: 5px">Sort by </span> <select onchange="changeFilterOfReview(this.value)" class="filter_review form-control">
                                                            <option value="high">Highest Rating</option>
                                                            <option value="low">Lowest Rating</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <!-- <?php /*if (isset($_SESSION['email'])) { */ ?> <a title="Add Service" class="fas add-icon-color fa-plus-circle" href=FULL_WEBSITE_URL."user/testimonial.php"></a>
                                                --><?php /*} */ ?>
                                            </div>
                                            <div class="col-md-12 owl-margin ">
                                                <ul class="ul_client_review">
<?php
if (isset($_GET['custom_url'])) {
    if($parent_id !=""){
        $custom_url = $getParentData['custom_url'];
    }else{
        $custom_url = $_GET['custom_url'];
    }

}
if(isset($_GET['type']) && $_GET['type'] == 'low'){
    $get_data = $manage->mdm_getReviewDetailsByFilter($custom_url,"asc");
}elseif(isset($_GET['type']) && $_GET['type'] == 'high'){
    $get_data = $manage->mdm_getReviewDetailsByFilter($custom_url,"desc");
}else{
    $get_data = $manage->mdm_getDigitalCardDetails("client_review",$custom_url);
}


if ($get_data != null) {
    while ($client_review = mysqli_fetch_array($get_data)) {
        $review_img_path = FULL_WEBSITE_URL."user/uploads/" . $client_review['email'] . "/testimonials/client_review/" . $client_review['img_name'];
        if($client_review['rating_number'] !="") {
            if($client_review['rating_number'] == "5") {
                $rating = "75";
            }elseif ($client_review['rating_number'] == "4") {
                $rating = "60";
            }elseif ($client_review['rating_number'] == "3") {
                $rating = "45";
            }elseif ($client_review['rating_number'] == "2") {
                $rating = "30";
            }elseif ($client_review['rating_number'] == "1") {
                $rating = "15";
            }
        }
        ?>
<li>
                                                <div id="DIV_8">
                                                    <a href="#"><img alt="<?php echo $client_review['name']; ?>" src="<?php if($client_review['img_name']!="" && check_url_exits($review_img_path)){ echo $review_img_path; }else{ echo FULL_WEBSITE_URL."user/uploads/user.png"; } ?>" id="IMG_10" /></a>
                                                    <div id="DIV_11">
                                                        <div id="DIV_12">
                                                            <a href="#" id="A_13" class="text-color-p"><?php echo $client_review['name']; ?></a>
                                                        </div>
                                                        <div id="DIV_14">
                                                            <span id="SPAN_15" class="text-color-p"><?php echo time_elapsed_string($client_review['created_date']); ?></span>
                                                        </div>
                                                        <div id="DIV_20">
                                                            <?php
                                                            if($client_review['rating_number'] !="") {
                                                                ?>
                                                                <g id="G-REVIEW-STARS_21">
                                                                    <span id="SPAN_22"><span id="SPAN_23" style="width: <?php echo $rating."px"; ?>;"></span></span>
                                                                </g>
                                                                <?php
                                                            }
                                                                ?>
                                                            <div id="DIV_24">
                                                                <span id="SPAN_27" class="text-color-p"> <?php echo rep_escape($client_review['description']); ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
</li>

    <?php }
}else{?>

        <div class="col-lg-8 col-lg-offset-2">
            <div class="text-center no_data_found">
                <img src="<?php echo $client_not_found; ?>">
                <h5>Our Client's Reviews will Appear Soon in this Section.</h5>
            </div>
        </div>
<?php } ?>

                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane active" id="client-id">
                                    <div class="col-md-12 margin_icon">
                                        <?php /*if (isset($_SESSION['email'])) { */ ?><!-- <a title="Add Service" class="fas add-icon-color fa-plus-circle" href=FULL_WEBSITE_URL."user/clients_review.php"></a>
                                        --><?php /*} */ ?>
                                    </div>

                                        <div class="client-main scrollbar style-11">
                                            <?php
                                            if ($get_result != null) {
                                                ?>
                                                <ul class="client-main-ul">
                                                    <?php
                                                    while ($result_data = mysqli_fetch_array($get_result)) {
                                                        ?>
                                                        <li>
                                                            <div class="info"><img
                                                                    class=""
                                                                    src="<?php echo FULL_WEBSITE_URL."user/uploads/" . $result_data['email'] . "/testimonials/clients/" . $result_data['img_name']; ?>"
                                                                    alt="<?php echo $result_data['name']; ?>"></div>
                                                            <div class="client-name-heading">
                                                                <h5 class="text-color-p"><?php echo $result_data['name']; ?></h5>
                                                            </div>
                                                        </li>
                                                        <?php
                                                    }
                                                    ?>
                                                </ul>
                                            <?php }else{?>
                                                <div class="col-lg-12">
                                                    <div class="col-lg-8 col-lg-offset-2">
                                                        <div class="text-center no_data_found">
                                                            <img src="<?php echo $client_not_found; ?>">
                                                            <h5>Our Client's Details will Appear Soon in this Section </h5>
                                                        </div>
                                                    </div>
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


    </section>
</div>
<?php

require_once "../review-flyout.php";

?>
<script>
    function changeFilterOfReview(val) {
        var current_url = window.location.href;
        if(val == 'low'){
            $('.owl-margin').load(current_url+'&type=low ul.ul_client_review');
        }else {
            $('.owl-margin').load(current_url+'&type=high ul.ul_client_review');
        }
    }
</script>
