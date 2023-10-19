<?php
ob_start();
include "../controller/ManageUser.php";
$manage = new ManageUser();
include "../controller/validator.php";
$validate = new Validator();
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
/*include '../sendMail/sendMail.php';*/

if (isset($_GET['id']) && (isset($_GET['name'])) && (isset($_GET['email'])) && (isset($_GET['contact'])) && (isset($_GET['custom_url']))) {

    $get_id = $_GET['id'];
    $email = $_GET['email'];
    $name = $_GET['name'];
    $contact = $_GET['contact'];
    $custom_url = $_GET['custom_url'];

    $_SESSION['create_user_id'] = $get_id;
    $_SESSION['create_user_email'] = $email;
    $_SESSION['create_user_name'] = $name;
    $_SESSION['create_user_contact'] = $contact;
    $_SESSION['create_user_custom_url'] = $custom_url;
    $_SESSION['create_user_status'] = true;
    header('location:manage-parent-child.php?user_id='.$_GET['id']);
}



$error = false;
$errorMessage = "";
$error1 = false;
$errorMessage1 = "";
include("android-login.php");
$random = rand(100, 10000);
$random_password = rand(1000, 10000);
include("session_includes.php");
include("validate-page.php");


if(isset($_GET['user_id'])){
    $user_id = $security->decrypt($_GET['user_id']);
}else{
    $user_id = 0;
}


if(isset($_POST['upgrade-credit'])){

    if(isset($_POST['btn_user']) && $_POST['btn_user'] !=""){
        $user_id = $_POST['btn_user'];
    }else{
        $error = true;
        $errorMessage = "Please Select User";
    }

    if(isset($_POST['txt_year']) && $_POST['txt_year'] !=""){
        $txt_year = $_POST['txt_year'];
    }else{
        $error = true;
        $errorMessage = "Please Select Plan";
    }
    if(!$error){
        $total_credit = $manage->getUserCreditByYear($txt_year);

        if($total_credit !=null && $total_credit['credit_qty'] > 0) {
            if ($txt_year == "1 year") {
                $month = 12;
            } else if ($txt_year == "3 year") {
                $month = 36;
            } else if ($txt_year == "5 year") {
                $month = 60;
            } else {
                $month = "";
            }
            $getUserDetail = $manage->getUserProfile($user_id);
            if($getUserDetail !=null){
                $user_expiry_date = $getUserDetail['expiry_date'];
            }else{
                $user_expiry_date = null;
            }
            $date = date("Y-m-d");

            if (($user_expiry_date != null OR $user_expiry_date != "0000-00-00") && $user_expiry_date >= $date && $month !="") {
                $expiry_date_in_time = strtotime("+" . $month . " months", strtotime($user_expiry_date));
                $expiry_date = date("Y-m-d", $expiry_date_in_time);
            } elseif (($user_expiry_date != null OR $user_expiry_date != "0000-00-00") && $user_expiry_date <= $date && $month !="") {
                $expiry_date_in_time = strtotime("+" . $month . " months", strtotime($date));
                $expiry_date = date("Y-m-d", $expiry_date_in_time);
            } elseif(($user_expiry_date == null OR $user_expiry_date == "0000-00-00") && $month !="") {
                $expiry_date_in_time = strtotime("+" . $month . " months", strtotime($date));
                $expiry_date = date("Y-m-d", $expiry_date_in_time);
            }else{
                $expiry_date = "";
            }
            $updateUserExpiry = $manage->updateUserExpiryOfChild($user_id,$expiry_date);
            if($updateUserExpiry){
                $remain_credit = $total_credit['credit_qty'] - 1;
                $update_credit = $manage->updateUserCredit($remain_credit,$total_credit['id']);
                $error = false;
                $errorMessage = "User Plan Upgraded Successfully!.";
            }
        }
    }

}
if(isset($_POST['btn_update_credit'])){
    $txt_redit = $_POST['txt_credit'];
    $plan_year = $_POST['plan_year'];
    $insertCredit = $manage->mu_updateUserCreditByAdmin($plan_year,$txt_redit,$user_id);
    if($insertCredit){
        $error = false;
        $errorMessage = "User Credit Upgraded Successfully!.";
    }else{
        $error = true;
        $errorMessage = "Issue while updating please try after some time!.";
    }
}
if (isset($_GET['user_expiry_id'])) {
    if (isset($_POST['update_expiry'])) {
        $user_expiry_id = $security->decrypt($_GET['user_expiry_id']);

        /*    die();*/
        $expiry_date = $_POST['expiry_date'];
/*        $update_expiry = $manage->updateUserExpiryDateAndSubscription($expiry_date, $user_expiry_id)*/;
        $update_expiry = $manage->updateUserExpiryDateProfile($expiry_date, $user_expiry_id);
        if ($update_expiry) {
            if (isset($_GET['page_no']) && $_GET['page_no'] != "") {
                header('location:manage-parent-child.php?page_no=' . $_GET['page_no']);
            } else {
                header('location:manage-parent-child.php?user_id='.$_GET['user_id']);
            }
        }
    }

}
$displayUser = $manage->displayParentUserDetails($user_id);
if ($displayUser != null) {
    $countUser = mysqli_num_rows($displayUser);
} else {
    $countUser = 0;
}



$form_data = $manage->getSpecificUserProfile();
if ($form_data != null) {
    $street = $form_data['address'];
    $company_name = $form_data['company_name'];
    $gst_no = $form_data['gst_no'];
    $pan_no = $form_data['pan_no'];
    $about_us = $form_data['about_company'];
    $our_mission = $form_data['our_mission'];
    $company_profile = $form_data['company_profile'];
    $city = $form_data['city'];
}

if (isset($_GET['publishData']) && (isset($_GET['action']))) {
    $action = $_GET['action'];
    $id = $security->decrypt($_GET['publishData']);
    if ($action == "Unblock") {
        $result = $manage->publishUnpublish($id, 1, $manage->profileTable);
    } else {
        $result = $manage->publishUnpublish($id, 0, $manage->profileTable);
    }
    header('location:manage_team_card.php');
}


function GenerateAPIKey()
{
    $key = implode('-', str_split(substr(strtolower(md5(microtime() . rand(1000, 9999))), 0, 30), 6));
    return $key;
}


$api_key = GenerateAPIKey();

if (isset($_POST['cancel_button'])) {
    if (isset($_GET['page_no']) && $_GET['page_no'] != "") {
        header('location:user-management.php?page_no=' . $_GET['page_no']);
    } else {
        header('location:user-management.php');
    }

}



$getCredit = $manage->getUserCreditById($user_id);
if ($getCredit != null) {
    $one_year_credit = $getCredit['one_year_credit'];
    if ($one_year_credit == null) {
        $one_year_credit = 0;
    }
    $three_year_credit = $getCredit['three_year_credit'];
    if ($three_year_credit == null) {
        $three_year_credit = 0;
    }
    $five_year_credit = $getCredit['five_year_credit'];
    if ($five_year_credit == null) {
        $five_year_credit = 0;
    }
    $life_time_credit = $getCredit['life_time_credit'];
    if ($life_time_credit == null) {
        $life_time_credit = 0;
    }
} else {
    $one_year_credit = 0;
    $three_year_credit = 0;
    $five_year_credit = 0;
    $life_time_credit = 0;
}


function addUrlParam($array)
{

    $url = $_SERVER['REQUEST_URI'];
    $val = "";
    if ($array != "") {
        foreach ($array as $name => $value) {
            if ($val != "") {
                $val .= "&" . $name . '=' . urlencode($value);
            } else {
                $val .= $name . '=' . urlencode($value);
            }
        }
    }
    if (strpos($url, '?') !== false) {
        $url .= '&' . $val;
    } else {
        $url .= '?' . $val;
    }
    return $url;
}


$today_date = date('Y-m-d');

function fetch_all_data($result)
{
    $all = array();
    while($thing = mysqli_fetch_array($result)) {
        $all[] = $thing;
    }
    return $all;
}


?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Manage Team Card</title>
    <?php include "assets/common-includes/header_includes.php" ?>
    <style>
        .header {
            padding: 10px;
        }

        .truncated_text {
            text-overflow: ellipsis;
            width: 150px;
            white-space: nowrap;
            overflow: hidden;
            padding: 0px;
            margin: 0px;
        }

        /* .truncated_text:hover {
             text-overflow: clip;
             width: auto;
             white-space: normal;
         }*/
        .info-box {
            height: auto;
            overflow: auto;
        }

        .info-box .content {
            display: inline-block;
            padding-bottom: 10px;
        }


    </style>

</head>
<body>
<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>
<section class="content">
    <?php
    if (isset($_SESSION['create_user_status']) && $_SESSION['create_user_status'] == true) {
        include "assets/common-includes/session_button_includes.php";
    }
    ?>
    <div class="clearfix">
        <div id="projectFacts" class="sectionClass col-lg-12">
            <div class="fullWidth eight columns">

                <div class="projectFactsWrap">

                    <div class="item " data-number="">
                       <!-- <div class="counter-icon customr_count_img">
                            <img src="../assets/img/website-counter/customer.png" alt="Online visiting card maker">
                        </div>-->
                        <div class="customer_count">
                            <p id="number1" class="number"><?php if (isset($one_year_credit)) echo $one_year_credit; ?></p>
                            <span></span>

                            <p>1 Year Credit</p>
                            <button class="btn btn-success mt-10" data-toggle="modal" data-target="#myModal"
                                    onclick="upgradeCredit('1 year','<?php if (isset($one_year_credit)) echo $one_year_credit; ?>')"><i class="fa fa-edit" aria-hidden="true"></i> Edit
                            </button>

                        </div>
                    </div>

                    <div class="item " data-number="55">
                     <!--   <div class="counter-icon customr_count_img">
                            <img src="../assets/img/website-counter/city.png" alt="smart digital business card">
                        </div>-->
                        <div class="customer_count">
                            <p id="number2" class="number"><?php if (isset($three_year_credit)) echo $three_year_credit; ?></p>
                            <span></span>

                            <p>3 Years Credit</p>
                            <button class="btn btn-success mt-10" data-toggle="modal" data-target="#myModal"
                                    onclick="upgradeCredit('3 year','<?php if (isset($three_year_credit)) echo $three_year_credit; ?>')"><i class="fa fa-edit" aria-hidden="true"></i> Edit
                            </button>
                        </div>
                    </div>
                    <div class="item " data-number="359">
                       <!-- <div class="counter-icon customr_count_img">
                            <img src="../assets/img/website-counter/artist.svg" alt="verified and secure business card">
                        </div>-->

                        <div class="customer_count">
                            <p id="number3" class="number"><?php echo $five_year_credit; ?></p>
                            <span></span>

                            <p>5 Years Credit</p>
                            <button class="btn btn-success mt-10" data-toggle="modal" data-target="#myModal"
                                    onclick="upgradeCredit('5 year','<?php if (isset($five_year_credit)) echo $five_year_credit; ?>')"><i class="fa fa-edit" aria-hidden="true"></i> Edit
                            </button>
                        </div>
                    </div>
                    <div class="item " data-number="<?php echo $total_dealer_count ?>">
                     <!--   <div class="counter-icon customr_count_img">
                            <img src="../assets/img/website-counter/handshake.svg" alt="digital card free demo">
                        </div>-->
                        <div class="customer_count">

                            <p id="number4" class="number"><?php echo $life_time_credit; ?></p>
                            <span></span>

                            <p>Life Time Credit</p>
                            <button class="btn btn-success mt-10" data-toggle="modal" data-target="#myModal"
                                    onclick="upgradeCredit('Life Time','<?php if (isset($life_time_credit)) echo $life_time_credit; ?>')"><i class="fa fa-edit" aria-hidden="true"></i> Edit
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <div class="col-md-12">
                        <div class="row">
                            <h4>
                                Manage Team Card <span class="badge"><?php
                                    if (isset($countUser)) echo $countUser;
                                    ?></span>
                            </h4>
                        </div>
                        <div id="snackbar">URL is on the clipboard, try to paste it!</div>
                    </div>

                   <!-- <div class="col-md-3 text-right">
                        <a class="btn btn-primary open_digi" href="add_team_card.php">Add New Card
                        </a>
                    </div>-->

                </div>
                <div class="body">
                    <?php if ($error) {
                        ?>
                        <div class="alert alert-danger">
                            <?php if (isset($errorMessage)) echo $errorMessage; ?>
                        </div>
                        <?php
                    } else if (!$error && $errorMessage != "") {
                        ?>
                        <div class="alert alert-success">
                            <?php if (isset($errorMessage)) echo $errorMessage; ?>
                        </div>
                        <?php
                    }
                    ?>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-sm"
                               cellspacing="0"
                               id="dtHorizontalVerticalExample" width="100%">
                            <thead>
                            <tr class="back-color">
                                <th>User</th>
                                <th>Email/Contact</th>
                                <th>Start/End Date</th>
                                <th>ACTION</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if ($displayUser != null) {
                                $i = 10;
                                $result_status = fetch_all_data($displayUser);
                                foreach ($result_status as $result_data){
                                    $link = SHARED_URL . $result_data['custom_url'];
                                    $end_date = $result_data['expiry_date'];
                                    $password = rtrim($result_data['password'], "8523");
                                    $date = date("Y-m-d");
                                    $earlier = new DateTime("$date");
                                    $later = new DateTime("$end_date");

                                    $five_day = date('Y-m-d', strtotime(date_create("Y-m-d") . ' + 5 days'));
                                    if ($result_data['year'] != 'Life Time') {
                                        if ($end_date === $date) {
                                            $diff = "1";
                                        } elseif ($end_date < $date) {
                                            $diff = "Expired";
                                        } else {
                                            $diff = $later->diff($earlier)->format("%a");
                                        }
                                    } else {
                                        $diff = "Life Time";
                                    }

                                    $custom_url = $result_data['custom_url'];
                                    $gender = $result_data['gender'];
                                    $profilePath = "uploads/" . $session_email. "/" . $result_data['email'] . "/profile/" . $result_data['img_name'];
                                    ?>
                                    <!--                                    --><?php //if ($result_data['status'] == '0') {
//                                        echo 'background-color: #f96b6b';
//                                    } elseif ($diff == 0) {
//                                        echo 'background-color: #fdc8ce';
//                                    } elseif ($end_date <= $five_day) {
//                                        echo 'background-color: #f9eabf;';
//                                    } ?>
                                    <tr style="">

                                        <td>
                                            <a href="<?php echo SHARED_URL.$result_data['custom_url'] ?>"
                                               target="_blank">
                                                <div style="display: inline-block;vertical-align: top">
                                                    <img
                                                            src="<?php if (!file_exists($profilePath) && $gender == "Male" or $result_data['img_name'] == "") {
                                                                echo "uploads/male_user.png";
                                                            } elseif (!file_exists($profilePath) && $gender == "Female" or $result_data['img_name'] == "") {
                                                                echo "uploads/female_user.png";
                                                            } else {
                                                                echo $profilePath;
                                                            } ?>" class="user_profile_image">
                                                </div>
                                                <div style="display: inline-block;">
                                                    <?php
                                                    echo $result_data['name'];
                                                    if ($result_data['designation'] != "") {
                                                        echo "<p class='truncated_text' title='" . $result_data['designation'] . "'>" . $result_data['designation'] . "</p>";
                                                    }
                                                    echo $result_data['created_date'];
                                                    ?>
                                                </div>
                                            </a>
                                        </td>

                                        <td><?php echo $result_data['email'] . "<br>" . $result_data['contact_no']; ?></td>
                                        <!--<td><?php
/*
                                            echo  date('d-M-Y',strtotime($result_data['created_date'])) ." / " . date('d-M-Y',strtotime($end_date));
                                            */?></td>-->
                                        <td style="position: relative">Start Date
                                            :</br><?php echo $result_data['created_date'] . " </br> "; ?>

                                            <form method="post" action="">
                                                Expiry Date :</br>
                                                <input type="date" name="expiry_date"
                                                       value="<?php echo $result_data['expiry_date']; ?>" <?php if (!isset($_GET['user_expiry_id'])) echo 'disabled' ?>>
                                                <?php
                                                if (isset($_SESSION['type']) && $_SESSION['type'] == "Admin") {
                                                    ?>
                                                    <div id="edit_icon_user" class="edit_icon">
                                                        <?php if (isset($_GET['user_expiry_id']) && $result_data['user_id'] == $security->decrypt($_GET['user_expiry_id'])) {
                                                            ?>
                                                            <button class="right_button1" name="cancel_button"><i
                                                                        class="fas wrong_button1 fa-times"></i></button>
                                                            <button class="right_button1" type="submit"
                                                                    name="update_expiry"><i
                                                                        class="fas right_check fa-check"></i></button>
                                                            <?php
                                                        } else { ?>
                                                            <a class="fas edit_color fa-pencil-alt"
                                                               href="<?php echo addUrlParam(array('user_expiry_id' => $security->encrypt($result_data['user_id']))); ?>"></a>
                                                            <?php
                                                        } ?>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </form>


                                        </td>


                                        <td>
                                            <ul class="header-dropdown">
                                                <li class="dropdown dropdown-inner-table">
                                                    <a href="javascript:void(0);" class="dropdown-toggle"
                                                       data-toggle="dropdown"
                                                       role="button" aria-haspopup="true" aria-expanded="false">
                                                        <i class="material-icons">more_vert</i>
                                                    </a>
                                                    <ul class="dropdown-menu pull-right">
                                                        <li>
                                                            <a target="_blank" href="add_team_card.php?display_data=<?php echo $security->encryptWebservice($result_data['user_id']); ?>&action=edit"><i
                                                                        class="fas fa-edit"></i>&nbsp;&nbspEdit</a>
                                                        </li>
                                                        <!--<li>
                                                            <a onclick="setClipboard('<?php /*echo $link; */?>')"><i
                                                                        class="fas fa-copy"></i>&nbsp;&nbsp;Copy URL</a>
                                                        </li>-->
                                                        <li>
                                                            <a onclick="return confirm('Are You sure you want to <?php echo $result_data['status'] == 1 ? 'Block' : 'Unblock'; ?>?');"
                                                               href="manage_team_card.php?publishData=<?php echo $security->encrypt($result_data['user_id']) ?>&action=<?php echo $result_data['status'] == 1 ? "Block" : "Unblock"; ?>"
                                                               class="<?php echo $result_data['status'] == 0 ? "fa fa-unlock" : "fa fa-ban"; ?>">
                                                                &nbsp;&nbsp;<?php echo $result_data['status'] == 1 ? "Block" : "Unblock"; ?></a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                    <?php
                                    $i++;
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

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog cust-model-width">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header cust-upi-madal">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Update <span class="plan_year"></span> credit</h4>
            </div>
            <div class="modal-body">
                <div class="body">
                    <form method="post" action="">
                        <div >
                            <input type="hidden" class="plan_year" name="plan_year">
                            <div>
                                <label class="form-label">Update Credit</label>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input name="txt_credit" type="number" class="form-control"
                                               placeholder="Enter Credit">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="form-group form-float">
                                        <button class="btn btn-primary" type="submit" name="btn_update_credit">Update Credit</button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include "assets/common-includes/footer_includes.php" ?>

<script>
    function upgradeCreditOfUser(text) {
        $('.select_year_exist').text(text);
        $('input[name=txt_year]').val(text);
    }
    function upgradeCredit(text,count) {
        $('.plan_year').text(text);
        $('.plan_year').val(text);
        $('input[name=txt_credit]').val(count);
    }

</script>
<?php
if ($error && $errorMessage != "") {
    ?>
    <script>
        $('.open_digi').click();
    </script>
    <?php
}
?>
<script>
    $("#checkAl").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
    $(".user_amount").click(function () {
        var $row = $(this).closest("tr");    // Find the row
        var amt = $row.find(".nr").text(); // Find the text

    });
    $(document).ready(function () {

        $(".checkbox1").change(function () {
            //Create an Array.
            var selected = new Array();
            $('input[type="checkbox"]:checked').each(function () {
                selected.push(this.value);
            });
            if (selected.length > 0) {
                $('.txt_id').val(selected.join(","));
                $('.extra_day').val(selected.join(","));
            }

        });

    });
</script>
</body>
</html>