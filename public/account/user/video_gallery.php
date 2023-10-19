<?php
ob_start();
include "../controller/ManageUser.php";
$manage = new ManageUser();
include "../controller/validator.php";
$validate = new Validator();
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include '../sendMail/sendMail.php';
$controller = new Controller();
$con = $controller->connect();
$alreadySaved = false;
$section_id = 2;


$alreadySavedVideo = false;
$section_video_id = 3;


include('android-login.php');

$error = false;
$errorMessage = "";
$error2 = false;
$errorMessage2 = "";

$videoError = false;
$errorMessageVideo = "";

$imgUploadStatus = false;
include("session_includes.php");
include "validate-page.php";

if (isset($_POST['btn_video'])) {
    if (isset($_POST['txt_video']) && $_POST['txt_video'] != "") {
        $video_name = mysqli_real_escape_string($con, $_POST['txt_video']);
    } else {
        $videoError = true;
        $errorMessageVideo = "Please enter video link.<br>";
    }
    if (!$videoError) {
        $addVideo = $manage->addVideo($video_name);
        if ($addVideo) {
            $_SESSION['red_dot']['video_link'] = false;
            if($countForVideo == 0) {
                $_SESSION['total_percent'] = $_SESSION['total_percent'] + 10;
            }
            $get_status = $manage->displayVideoDetails();
            if ($get_status != null) {
                $countForVideo = mysqli_num_rows($get_status);
            } else {
                $countForVideo = 0;
            }
            $video_name = "";
            $videoError = false;
            $errorMessageVideo =  $_SESSION['menu']['s_videos'] ." added successfully";
        } else {
            $videoError = true;
            $errorMessageVideo = "Issue while adding details, Please try again.";
        }
    }

}


/*

*/
if(isset($countForVideo) == 0){
    $_SESSION['red_dot']['video_link'] = true;
}
if (isset($_GET['delete_data'])) {
    $delete_data = $security->decrypt($_GET['delete_data']);
    $status = $manage->deleteVideo($delete_data);
    if ($status) {
        $get_status = $manage->displayVideoDetails();
        if ($get_status != null) {
            $countForVideo = mysqli_num_rows($get_status);
        } else {
            $countForVideo = 0;
        }
        if($countForVideo == 0) {
            $_SESSION['total_percent'] = $_SESSION['total_percent'] - 10;
        }
        if ($android_url != "") {
            header('location:video_gallery.php?' . $android_url);
        } else {
            header('location:video_gallery.php');
        }
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
function parse_channel_id($url) {
    $parsed = parse_url(rtrim($url, '/'));
    if (isset($parsed['path']) && preg_match('/^\/channel\/(([^\/])+?)$/', $parsed['path'], $matches)) {
        return $matches[1];
    }else{
        return false;
    }
}
if (isset($_GET['id']) && (isset($_GET['action']))) {
    $action = $_GET['action'];
    $get_id = $security->decrypt($_GET['id']);
    if ($action == "unpublish") {
        $result = $manage->publishUnpublish($get_id, 0, $manage->videoTable);
    } else {
        $result = $manage->publishUnpublish($get_id, 1, $manage->videoTable);
    }
    if ($android_url != "") {
        header('location:video_gallery.php?' . $android_url);
    } else {
        header('location:video_gallery.php');
    }
}
/*Import and Sync start*/
if (isset($_POST['btn_import_url'])){
    if(isset($_POST['txt_youtube_url']) && $_POST['txt_youtube_url'] !=''){
        $youtube_url = $_POST['txt_youtube_url'];
        $pattern = "/^(https?:\/\/)?(www\.)?youtu((\.be)|(be\..{2,5}))\/((user)|(channel))$/";
        $valid = preg_match("/^(https?\:\/\/)?(www\.)?(youtube\.com|youtu\.be)\/((user)|(channel))\/\w+$/", $youtube_url);
        if($valid !=1){
            $error2 = true;
           $errorMessage2 = "Please enter valid channel url.";
        }else{

            $channelID = parse_channel_id($youtube_url);

            if($channelID) {
                $YOUTUBE_API_key = 'AIzaSyD2Pd7U2YDUXrgiHLOk8tpOaHNkYvdfot0';
                $maxResults = 200;
                $videoList = json_decode(file_get_contents('https://www.googleapis.com/youtube/v3/search?order=date&part=snippet&channelId=' . $channelID . '&maxResults=' . $maxResults . '&key=' . $YOUTUBE_API_key . ''));

                $current_video_url_array = array();

                foreach ($videoList->items as $item) {
                        //Embed video

                        if (isset($item->id->videoId)) {
                            $current_url = "https://www.youtube.com/watch?v=".  $item->id->videoId;

                            $current_video_url_array[] =$current_url;
                            $check_duplicate_video = $manage->mu_checkduplicateVideo($item->id->videoId);
                            if(!$check_duplicate_video){
                                $add = $manage->addVideo($current_url,$channelID);
                            }elseif ($check_duplicate_video['channel_id'] == ''){
                                $condition = array('id'=>$check_duplicate_video['id']);
                                $data = array('channel_id'=>$channelID);
                                $update  = $manage->update($manage->videoTable,$data,$condition);
                            }
                             }
                }
                $sync_video = $manage->mu_listAllVideoUsingChannel($channelID);
                if($sync_video !=null) {
                    $video_array = array();
                    while ($thing = mysqli_fetch_array($sync_video)) {
                        $video_array[$thing['id']] = $thing['video_link'];
                    }

                    $result=array_diff($video_array,$current_video_url_array);
//https://www.youtube.com/channel/UCBG_S40CtHCUZhIvxbd26ag = anand singh
                    if($result !=null){
                        foreach ($result as $key=>$value){
                            $delete_video = $manage->deleteVideo($key);
                        }
                    }
                }
            }else{
                $error2 = true;
                $errorMessage2 = "Please enter valid channel url.";
            }
        }
    }else{
        $error2 = true;
       $errorMessage2 = "Please enter channel url.";
    }


}
/*Import and Sync end*/
/*$get_status = $manage->displayVideoDetails();
if ($get_status != null) {
    $countForVideo = mysqli_num_rows($get_status);
} else {
    $countForVideo = 0;
}*/
/*This is for video gallery*/

$get_video_data = $manage->countService($id, $section_video_id);
if ($get_video_data) {
    $alreadySavedVideo = true;
    $display_video_result = $manage->getServiceStatus($id, $section_video_id);
}
if (isset($_POST['update_video_chk'])) {

    $digital_card_video_status = 0;
    $website_video_status = 0;

    if (isset($_POST['video_type'])) {
        $video_type = $_POST['video_type'];

        if (isset($video_type[0]) && $video_type[0] == "digital_card" || isset($video_type[0]) && $video_type[0] == "digital_card") {
            $digital_card_video_status = 1;
        } else {
            $digital_card_video_status = 0;
        }

        if (isset($video_type[0]) && $video_type[0] == "website" || isset($video_type[1]) && $video_type[1] == "website") {
            $website_video_status = 1;
        } else {
            $website_video_status = 0;
        }
    }

    $video_result = $manage->updateSectionStatus($id, $section_video_id, $website_video_status, $digital_card_video_status);
    if ($video_result) {
        if ($android_url != "") {
            header('location:video_gallery.php?' . $android_url);
        } else {
            header('location:video_gallery.php');
        }
    }
}
if (isset($_GET['page_no']) && $_GET['page_no'] != "") {
    $page_no = $_GET['page_no'];
} else {
    $page_no = 1;
}
$total_records_per_page = 10;
$offset = ($page_no - 1) * $total_records_per_page;
$previous_page = $page_no - 1;
$next_page = $page_no + 1;
$adjacents = "2";
$get_count = $manage->mdm_displayVideoCount();
$total_records = $get_count;

$total_no_of_pages = ceil($total_records / $total_records_per_page);
$second_last = $total_no_of_pages - 1; // total page minus 1

$get_status = $manage->mu_displayVideoDetailsByLimit( $offset, $total_records_per_page);
if ($get_status != null) {
    $countForVideo = mysqli_num_rows($get_status);
} else {
    $countForVideo = 0;
}


?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php include "assets/common-includes/total_count.php" ?>
    <title><?php echo $_SESSION['menu']['s_gallery']; ?> - <?php echo $_SESSION['menu']['s_videos']; ?></title>
    <?php include "assets/common-includes/header_includes.php" ?>
</head>
<body>
<?php
if (!isset($_GET['android_user_id']) && (!isset($_GET['type'])) && (!isset($_GET['api_key']))) {
?>
<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>
<section class="content">
    <?php
    if (isset($_SESSION['create_user_status']) && $_SESSION['create_user_status'] == true) {
        include "assets/common-includes/session_button_includes.php";
    }
    ?>
    <?php include "assets/common-includes/preview.php" ?>
    <div class="up-nav visible-lg visible-md visible-sm hidden-xs">
        <?php include 'assets/common-includes/menu_bar_include.php' ?>
    </div>
    <?php
    }elseif (isset($_GET['android_user_id']) && (isset($_GET['type']) && $_GET['type'] == "android") && (isset($_GET['api_key']))) {
    ?>
    <section class="androidSection">
        <?php
        }
        ?>
        <div class="clearfix padding_bottom_46">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding_zero padding_zero_both">
            <div class="row">
                <div class="card <?php if($android_url !='') echo "no_back_card"; ?>">
                    <div class="body custom_card_padding">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs tab-nav-right <?php if($android_url !='') echo "d-card-none"; ?>" role="tablist">
                            <li role="presentation"><a class="custom_nav_tab"
                                    href="gallery.php<?php if ($android_url != "") echo "?" . $android_url; ?>"><?php echo $_SESSION['menu']['s_images']; ?><?php if($_SESSION['red_dot']['image_name'] == true) echo '<div class="remaining_sub_form_dot"></div>' ?></a>
                            </li>
                            <li role="presentation" class="active"><a class="custom_nav_tab" href="#video" data-toggle="tab"><?php echo $_SESSION['menu']['s_videos']; ?><?php if($_SESSION['red_dot']['video_link'] == true) echo '<div class="remaining_sub_form_dot"></div>' ?></a></li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <!--Video-->
                            <div role="tabpanel" class="tab-pane fade in active" id="video">
                                <div class="clearfix">
                                    <div
                                        class="col-lg-5 col-md-5 col-sm-12 col-xs-12 padding_zero margin_div padding_zero_both">
                                        <div class="card">
                                            <div class="header">
                                                <div class="row cust-row">
                                                    <div class="col-lg-7 m_b_0"><h2>
                                                            Add <?php echo $_SESSION['menu']['s_videos']; ?>

                                                        </h2></div>

                                                </div>

                                            </div>
                                            <div class="body">
                                                <form id="video_gallery" method="POST" action=""
                                                      enctype="multipart/form-data">
                                                    <?php if ($videoError) {
                                                        ?>
                                                        <div class="alert alert-danger">
                                                            <?php if (isset($errorMessageVideo)) echo $errorMessageVideo; ?>
                                                        </div>
                                                    <?php
                                                    } else if (!$videoError && $errorMessageVideo != "") {
                                                        ?>
                                                        <div class="alert alert-success">
                                                            <?php if (isset($errorMessageVideo)) echo $errorMessageVideo; ?>
                                                        </div>
                                                    <?php
                                                    }
                                                    ?>
                                                    <div>
                                                        <label class="form-label">YouTube Video link</label> <span
                                                            class="required_field">*</span>

                                                        <div class="form-group form-float">
                                                            <div class="form-line">
                                                                <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                                                <input name="txt_video"
                                                                       class="form-control"
                                                                       placeholder="Enter YouTube video Link" autocomplete="off">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="form-group form_inline">
                                                            <div>
                                                                <!--   <asp:Button ID="btn_save" runat="server" Text="Save" CssClass="btn btn-primary waves-effect" />-->
                                                                <input value="Add" type="submit" name="btn_video"
                                                                       class="btn btn-primary waves-effect">
                                                            </div>
                                                            &nbsp;&nbsp;
                                                            <div>
                                                                <!--<asp:Button ID="btn_add_reset" runat="server" Text="Reset" CssClass="btn btn-success waves-effect" />-->
                                                                <input type="reset" name="btn_add-reset"
                                                                       class="btn btn-default">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="col-lg-7 col-md-7 col-sm-12 col-xs-12 padding_zero margin_div padding_zero_both">
                                        <!--<div class="freelancer_search_box padding_zero padding_zero_both" style="width: 100%">
                                            <div class="col-md-12">
                                            <form action="" method="post">
                                                <ul class="profile-ul">
                                                    <h4>Hide video gallery from digital card</h4>
                                                    <li class="li_event">
                                                        <div class="cust-div">
                                                            <input type="checkbox" name="video_type[]"
                                                                   value="digital_card"  <?php /*if ($display_video_result['digital_card'] == '1') {
                                                                echo 'checked="checked"';
                                                            } */ ?> > Video
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="cust-div">
                                                            <input type="checkbox" name="video_type[]"
                                                                   value="website" <?php /*if ($display_video_result['website'] == '1') {
                                                                echo 'checked="checked"';
                                                            } */ ?>>Website
                                                        </div>
                                                    </li>


                                                    <li class="li_event">
                                                        <?php /*if (isset($alreadySavedVideo) && $alreadySavedVideo) {
                                                            */ ?>
                                                            <button class="btn btn-primary waves-effect" name="update_video_chk"
                                                                    type="submit">
                                                                Save
                                                            </button>
                                                        <?php
                                        /*                                                        } else {
                                                                                                    */ ?>
                                                            <button class="btn btn-primary waves-effect" name="save_chk" type="submit">
                                                                Add
                                                            </button>
                                                        <?php
                                        /*                                                        }
                                                                                                */ ?>
                                                    </li>
                                                </ul>
                                            </form>
                                            </div>
                                        </div>-->
                                        <div class="card">
                                            <div class="header">
                                                <div class="col-md-12 col-xs-12 m-b-0">

                                                    <div class="row">
                                                        <div class="col-md-6 col-xs-7 m-b-0">
                                                            <h2>
                                                                Manage <?php echo $_SESSION['menu']['s_videos']; ?> <span class="badge"><?php
                                                                    if (isset($countForVideo)) echo $countForVideo;
                                                                    ?></span>
                                                            </h2>
                                                        </div>
                                                        <div class="col-md-6 col-xs-5 text-right m-b-0">
                                                            <button class="btn btn-danger" data-toggle="modal" data-target="#myModal"><i class="fa fa-youtube"></i> Import & Sync</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="body">
                                                <div style="overflow-x: auto">
                                                    <table
                                                           class="table table-striped table-bordered table-sm "
                                                           cellspacing="0"
                                                           width="100%"><!-- id="dtHorizontalVerticalExample" -->
                                                        <thead>
                                                        <tr class="back-color">
                                                            <th>Video</th>
                                                            <th class="visible-lg visible-md hidden-sm hidden-xs">
                                                                Status
                                                            </th>
                                                            <th>ACTION</th>

                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php

                                                        if ($get_status != null) {
                                                            while ($result_data = mysqli_fetch_array($get_status)) {
                                                                $video_link = $result_data['video_link'];
                                                                ?>
                                                                <tr>
                                                                    <td>
                                                                        <iframe
                                                                            src="<?php echo str_replace("watch?v=", "embed/", "$video_link"); ?>"
                                                                            frameborder="0" style="width: 100%"
                                                                            allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                                                            allowfullscreen></iframe>
                                                                        <div
                                                                            class="hidden-lg hidden-md visible-sm visible-xs">
                                                                            <br>
                                                                            <b>STATUS</b> : <label
                                                                                class="label <?php if ($result_data['status'] == "0") {
                                                                                    echo "label-danger";
                                                                                } else {
                                                                                    echo "label-success";
                                                                                } ?>"><?php if ($result_data['status'] == 0) {
                                                                                    echo "Unpublished";
                                                                                } else {
                                                                                    echo "Published";
                                                                                } ?></label>
                                                                        </div>
                                                                    </td>
                                                                    <td class="visible-lg visible-md hidden-sm hidden-xs">
                                                                        <label
                                                                            class="label <?php if ($result_data['status'] == "0") {
                                                                                echo "label-danger";
                                                                            } else {
                                                                                echo "label-success";
                                                                            } ?>"><?php if ($result_data['status'] == 0) {
                                                                                echo "Unpublished";
                                                                            } else {
                                                                                echo "Published";
                                                                            } ?></label></td>
                                                                    <td>
                                                                        <ul class="header-dropdown">
                                                                            <li class="dropdown dropdown-inner-table">
                                                                                <a href="javascript:void(0);"
                                                                                   class="dropdown-toggle"
                                                                                   data-toggle="dropdown"
                                                                                   role="button" aria-haspopup="true"
                                                                                   aria-expanded="false">
                                                                                    <i class="material-icons">more_vert</i>
                                                                                </a>
                                                                                <ul class="dropdown-menu pull-right">
                                                                                    <li>
                                                                                        <a href="video_gallery.php?delete_data=<?php echo $security->encrypt($result_data['id']);
                                                                                        if ($android_url != "") echo "&" . $android_url; ?>"
                                                                                           onclick="return confirm('Are You sure you want to delete?');"
                                                                                        <i
                                                                                            class="fas fa-trash-alt"></i>&nbsp;&nbsp;Delete</a>
                                                                                    </li>
                                                                                    <li>
                                                                                        <a onclick="return confirm('Are You sure you want to <?php echo $result_data['status'] == 0 ? 'publish' : 'unpublish'; ?>?');"
                                                                                           href="video_gallery.php?id=<?php echo $security->encrypt($result_data['id']); ?>&action=<?php echo $result_data['status'] == 0 ? "publish" : "unpublish";
                                                                                           if ($android_url != "") echo "&" . $android_url; ?> "><i
                                                                                                class="fas <?php echo $result_data['status'] == 0 ? "fa-upload" : "fa-download"; ?>"></i>&nbsp;&nbsp;<?php echo $result_data['status'] == 1 ? "Unpublish" : "Publish"; ?>
                                                                                        </a>
                                                                                    </li>
                                                                                </ul>
                                                                            </li>
                                                                        </ul>
                                                                    </td>
                                                                </tr>

                                                            <?php
                                                            }
                                                            ?>
                                                        <?php
                                                        } else {
                                                            ?>
                                                            <tr>
                                                                <td colspan="10" class="text-center">No data found!</td>
                                                            </tr>
                                                        <?php
                                                        }
                                                        ?>
                                                        </tbody>
                                                    </table>
                                                    <div style='padding: 10px 20px 0px; border-top: dotted 1px #CCC;float: right'>

                                                        <ul class="pagination">
                                                            <?php // if($page_no > 1){ echo "<li><a href='?page_no=1'>First Page</a></li>"; } ?>

                                                            <li <?php if ($page_no <= 1) {
                                                                echo "class='disabled'";
                                                            } ?>>
                                                                <a <?php if ($page_no > 1) {
                                                                    echo "href='?page_no=$previous_page'";
                                                                } ?>>Previous</a>
                                                            </li>

                                                            <?php
                                                            if ($total_no_of_pages <= 10) {
                                                                for ($counter = 1; $counter <= $total_no_of_pages; $counter++) {
                                                                    if ($counter == $page_no) {
                                                                        echo "<li class='active'><a>$counter</a></li>";
                                                                    } else {
                                                                        echo "<li><a href='?page_no=$counter'>$counter</a></li>";
                                                                    }
                                                                }
                                                            } elseif ($total_no_of_pages > 10) {

                                                                if ($page_no <= 4) {
                                                                    for ($counter = 1; $counter < 8; $counter++) {
                                                                        if ($counter == $page_no) {
                                                                            echo "<li class='active'><a>$counter</a></li>";
                                                                        } else {
                                                                            echo "<li><a href='?page_no=$counter'>$counter</a></li>";
                                                                        }
                                                                    }
                                                                    echo "<li><a>...</a></li>";
                                                                    echo "<li><a href='?page_no=$second_last'>$second_last</a></li>";
                                                                    echo "<li><a href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
                                                                } elseif ($page_no > 4 && $page_no < $total_no_of_pages - 4) {
                                                                    echo "<li><a href='?page_no=1'>1</a></li>";
                                                                    echo "<li><a href='?page_no=2'>2</a></li>";
                                                                    echo "<li><a>...</a></li>";
                                                                    for ($counter = $page_no - $adjacents; $counter <= $page_no + $adjacents; $counter++) {
                                                                        if ($counter == $page_no) {
                                                                            echo "<li class='active'><a>$counter</a></li>";
                                                                        } else {
                                                                            echo "<li><a href='?page_no=$counter'>$counter</a></li>";
                                                                        }
                                                                    }
                                                                    echo "<li><a>...</a></li>";
                                                                    echo "<li><a href='?page_no=$second_last'>$second_last</a></li>";
                                                                    echo "<li><a href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
                                                                } else {
                                                                    echo "<li><a href='?page_no=1'>1</a></li>";
                                                                    echo "<li><a href='?page_no=2'>2</a></li>";
                                                                    echo "<li><a>...</a></li>";

                                                                    for ($counter = $total_no_of_pages - 6; $counter <= $total_no_of_pages; $counter++) {
                                                                        if ($counter == $page_no) {
                                                                            echo "<li class='active'><a>$counter</a></li>";
                                                                        } else {
                                                                            echo "<li><a href='?page_no=$counter'>$counter</a></li>";
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                            ?>

                                                            <li <?php if ($page_no >= $total_no_of_pages) {
                                                                echo "class='disabled'";
                                                            } ?>>
                                                                <a <?php if ($page_no < $total_no_of_pages) {
                                                                    echo "href='?page_no=$next_page'";
                                                                } ?>>Next</a>
                                                            </li>
                                                            <?php if ($page_no < $total_no_of_pages) {
                                                                echo "<li><a href='?page_no=$total_no_of_pages'>Last &rsaquo;&rsaquo;</a></li>";
                                                            } ?>
                                                        </ul>
                                                        <!--<strong>Page <?php /*echo $page_no . " of " . $total_no_of_pages; */?></strong>-->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--Video-->
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
</div>
    </section>
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-sm padding-right-21">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header cust-upi-madal">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Import And Sync Video</h4>
                </div>
                <div class="modal-body">
                    <div class="body">
                        <form id="upi_form_validation" method="POST" action="">
                            <?php if ($error2) {
                                ?>
                                <div class="alert alert-danger">
                                    <?php if (isset($errorMessage2)) echo $errorMessage2; ?>
                                </div>
                                <?php
                            } else if (!$error2 && $errorMessage2 != "") {
                                ?>
                                <div class="alert alert-success">
                                    <?php if (isset($errorMessage2)) echo $errorMessage2; ?>
                                </div>
                                <?php
                            }
                            ?>
                            <div>
                                <label class="form-label">Enter Channel URL</label>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input name="txt_youtube_url" class="form-control"
                                               placeholder="Ex:-https://www.youtube.com/channel/YOUR_CHANNEL_ID_HERE"
                                               value="<?php if (isset($paypal_email)) echo $paypal_email; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group form_inline form-float">
                                <button class="btn btn-primary waves-effect form-control"
                                        name="btn_import_url"
                                        type="submit">
                                    Import & Sync
                                </button>
                                &nbsp;&nbsp;
                                <!--<div>
                                    <button class="btn btn-default" type="reset">
                                        Reset
                                    </button>
                                </div>-->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include "assets/common-includes/footer_includes.php" ?>
</body>
</html>