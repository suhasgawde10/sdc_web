<?php
ob_start();
include "../controller/ManageUser.php";
$manage = new ManageUser();
include "../controller/validator.php";
$validate = new Validator();
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include '../sendMail/sendMail.php';
$error = false;
$errorMessage = "";
$error1 = false;
$errorMessage1 = "";
if (!isset($_SESSION['email'])) {
    header('location:../login.php');
} elseif (isset($_SESSION['email']) && isset($_SESSION['type']) && $_SESSION['type'] == 'User') {
    header('location:../login.php');
}

$random = rand(100, 10000);
$random_password = rand(1000, 10000);

if (isset($_GET['page_no']) && $_GET['page_no'] != "") {
    $page_no = $_GET['page_no'];
} else {
    $page_no = 1;
}

if (isset($_GET['delete_data']) && $_GET['email']) {
    $email = $_GET['email'];
    $delete_data = $security->decrypt($_GET['delete_data']);
    $dirPath = "uploads/$email";
    function deleteDirectory($dirPath)
    {
        if (is_dir($dirPath)) {
            $objects = scandir($dirPath);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dirPath . DIRECTORY_SEPARATOR . $object) == "dir") {
                        deleteDirectory($dirPath . DIRECTORY_SEPARATOR . $object);
                    } else {
                        unlink($dirPath . DIRECTORY_SEPARATOR . $object);
                    }
                }
            }
            reset($objects);
            rmdir($dirPath);
        }
    }

    deleteDirectory($dirPath);
    $status = $manage->deleteUser($delete_data);
    if ($status) {
        header('location:user-management.php');
    }
}

function GenerateAPIKey()
{
    $key = implode('-', str_split(substr(strtolower(md5(microtime() . rand(1000, 9999))), 0, 30), 6));
    return $key;
}

if(isset($_POST['btn_cancel'])){
    unset($_SESSION['drp_package']);
    unset($_SESSION['txt_search']);
    header('location:export-user.php');
}
$search_filter = false;

if (isset($_POST['search'])) {
    if (isset($_POST['txt_search']) && $_POST['txt_search'] != "") {
        $txt_search = $_POST['txt_search'];
    } else {
        $txt_search = "";
    }
    $_SESSION['txt_search'] = $txt_search;
    $drp_package = $_POST['drp_package'];
    $_SESSION['drp_package'] = $drp_package;

    $total_records_per_page = 9897879798789789797;
    $displayUser = $manage->displayAllUserByPackage($drp_package, $txt_search,"0", $total_records_per_page, $_SESSION['from_date'], $_SESSION['to_date']);
    if ($displayUser != null) {
        $countUser = mysqli_num_rows($displayUser);
    } else {
        $countUser = 0;
    }
    $search_filter = true;
}

if (isset($_SESSION['drp_package']) && !$search_filter) {
        $total_records_per_page = 9897879798789789797;

    $displayUser = $manage->displayAllUserByPackage($_SESSION['drp_package'], $_SESSION['txt_search'], "0", $total_records_per_page, $_SESSION['from_date'], $_SESSION['to_date']);
        if ($displayUser != null) {
            $countUser = mysqli_num_rows($displayUser);
        } else {
            $countUser = 0;
        }
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
$displayPlan = $manage->displaySubscription();
if ($displayPlan != null) {
    $countForPlan = mysqli_num_rows($displayPlan);
} else {
    $countForPlan = 0;
}

?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>User Mangement</title>
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

    </style>

</head>
<body>
<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>
<section class="content">
    <?php
    if (isset($_SESSION['create_user_status']) && $_SESSION['create_user_status'] == true) {
        include "assets/common-includes/session_button_includes.php";
        echo "<br>";
    }
    ?>
    <div class="clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <div class="col-md-9">
                        <div class="row">
                            <h4>
                                Export All User <span class="badge"><?php
                                    if (isset($countUser)) echo $countUser;
                                    ?></span>
                            </h4>
                        </div>
                        <div id="snackbar">URL is on the clipboard, try to paste it!</div>
                    </div>
                    <div class="col-md-3 text-right">
                        <button class="btn btn-success" type="button" id="btnExport">
                            Export To Excel
                        </button>
                    </div>



                </div>
                <div class="body">
                    <?php if ($error1) {
                        ?>
                        <div class="alert alert-danger">
                            <?php if (isset($errorMessage1)) echo $errorMessage1; ?>
                        </div>
                        <?php
                    } else if (!$error1 && $errorMessage1 != "") {
                        ?>
                        <div class="alert alert-success">
                            <?php if (isset($errorMessage1)) echo $errorMessage1; ?>
                        </div>
                        <?php
                    }
                    ?>

                    <?php
                    if (isset($_SESSION['type']) && $_SESSION['type'] == "Admin") {
                        ?>
                        <fieldset class="filter_search">
                            <legend>Filter</legend>
                            <div class="col-md-12">
                                <div class="row">
                                    <form method="post" action="">
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" name="txt_search"
                                                   value="<?php if (isset($txt_search)) echo $txt_search; ?>"
                                                   placeholder="Enter name,email,contact number,keywords,designation,company_name">
                                        </div>
                                        <div class="col-md-3">
                                            <select class="form-control show-tick" name="drp_package">
                                                <option value="">Select Package</option>
                                                <?php
                                                if ($displayPlan != null) {
                                                    while ($row = mysqli_fetch_array($displayPlan)) {
                                                        $year = $row['year'];
                                                        ?>
                                                        <option
                                                                value="<?php echo $year; ?>" <?php if (isset($_SESSION['drp_package']) && $_SESSION['drp_package'] == $year) echo 'selected="selected"'; ?>><?php echo $year; ?></option>

                                                    <?php }
                                                } ?>
                                                <option
                                                        value="expired" <?php if (isset($_SESSION['drp_package']) && $_SESSION['drp_package'] == 'expired') echo 'selected="selected"'; ?>>
                                                    Expired
                                                </option>
                                                <option
                                                        value="purchased" <?php if (isset($_SESSION['drp_package']) && $_SESSION['drp_package'] == 'purchased') echo 'selected="selected"'; ?>>
                                                    Purchased
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-2 text-left">
                                            <div class="row">
                                                <button class="btn btn-primary" type="submit" name="search">Search
                                                </button>

                                                <button type="submit" name="btn_cancel" class="btn btn-danger">
                                                    Clear filter</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </fieldset>
                        <?php
                    }
                    ?>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-sm"
                               cellspacing="0" id="dtHorizontalVerticalExample"
                               width="100%"> <!-- id="dtHorizontalVerticalExample" -->
                            <thead>
                            <tr class="back-color">
                                <th><input type="checkbox" id="checkAl"></th>
                                <th>Name</th>
                                <th>Contact</th>
                                <th>State City</th>
                                <th>User(self-dealer)</th>
                                <th>Plan(purchased free)</th>

                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if ($displayUser != null) {
                                $i = 10;
                                while ($result_data = mysqli_fetch_array($displayUser)) {

                                    ?>
                                    <tr>
                                        <td><input type="checkbox" id="checkItem" name="check[]" class="checkbox1"
                                                   value="<?php echo $result_data["id"]; ?>"></td>
                                        <td>
                                            <a href="<?php echo SHARED_URL.$result_data['custom_url'] ?>"
                                               target="_blank">
                                                <div style="display: inline-block;">
                                                    <?php
                                                    echo $result_data['name'];

                                                    ?>
                                                </div>
                                            </a>
                                        </td>
                                        <td><?php echo $result_data['email'] . "<br>" . $result_data['contact_no']; ?></td>
                                        <td>
                                            <?php
                                            if($result_data['state'] !=''){
                                                $getState = $manage->getStateCategoryById($result_data['state']);
                                                echo  $getState['name'];
                                            }else{
                                                echo "-";
                                            }
                                            if($result_data['city'] !=''){
                                                echo " - ". $getState['city'];
                                            }/*else{
                                                echo " -";
                                            }*/

                                            ?>
                                        </td>
                                        <td>
                                            <?php

                                                $created_status = false;
                                                if (like_match('%dealer%', $result_data['referer_code']) == 1) {
                                        $getDealer = $manage->getDealerProfile($result_data['referer_code']);
                                        $dealer_id = $getDealer['user_id'];
                                        echo ' <a href="view-dealer-profile.php?user_id=' . $security->encrypt($dealer_id) . '"><label class="label label-danger">By ' . $getDealer['name'] . '</label> </a>';
                                        $created_status = true;
                                        } else if (isset($result_data['created_by']) && $result_data['created_by'] != "") {
                                        echo ' <label class="label label-danger">' . $result_data['created_by'] . '</label>';
                                        } else {
                                        echo ' <label class="label label-info">Self Register</label>';
                                        }
                                                ?>
                                        </td>

                                        <!--<td><?php /*echo $result_data['email']; */ ?></td>-->
                                        <!--<td><?php /*echo $result_data['year'] . '<br>days : <label class="label label-success">' . $diff . '</label><br>Date : <label class="label label-info">' . $result_data['user_start_date'] . '</label>'; */?></td>-->
                                        <td>
                                            <?php
                                            $year_arary = array('1 year','3 year','5 year','Life Time');
                                            if($result_data['year'] != "" && in_array($result_data['year'],$year_arary)){
                                                echo "Purchased";
                                            }else{
                                                echo "Free";
                                            }

                                            ?>

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


<?php include "assets/common-includes/footer_includes.php" ?>

<script src="assets/js/table2excel.js" type="text/javascript"></script>
<script>
    function addDomainLink(id,domain_link) {
        $('.d_user_id').val(id);
        $('.txt_link').val(domain_link);
        $('#myDomainModal').modal('show');
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
<script>
    function getStateDataByCountry(value) {
        var dataString = 'country_id=' + value;
        if (value != '') {
            $.ajax({
                url: "get_city_ajax.php",
                type: "POST",
                data: dataString,
                success: function (html) {
                    $('#state_select').html(html);
                }
            });
        } else {
            $('#state_select').html(' <select name="txt_city" class="form-control"><option value="">select an option</option></select>');
        }
    }
    function getCityByStateId(value) {

        var dataString = 'state_id=' + value;
        if (value != '') {
            $.ajax({
                url: "get_city_ajax.php",
                type: "POST",
                data: dataString,
                success: function (html) {
                    $('#city_select').html(html);
                }
            });
        } else {
            $('#city_select').html(' <select name="txt_city" class="form-control"><option value="">select an option</option></select>');
        }
    }
</script>
<script type="text/javascript">
    $(function () {
        $("#btnExport").click(function () {
            $("#dtHorizontalVerticalExample").table2excel({
                filename: "user-management.xls"
            });
        });
    });
</script>
</body>
</html>