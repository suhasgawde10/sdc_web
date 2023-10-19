<?php
ob_start();
include_once "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include "../controller/ManageUser.php";
$manage = new ManageUser();
$error = false;
$errorMessage = "";
if (!isset($_SESSION['email'])) {
    header('location:../login.php');
}
/*unset($_SESSION['create_user_status']);*/

/*Sewrvice*/

include("session_includes.php");
include "validate-page.php";
if (isset($_GET['page_no']) && $_GET['page_no'] != "") {
    $page_no = $_GET['page_no'];
} else {
    $page_no = 1;
}


$approve_status = "Not Interested";

include "assets/common-includes/lead-app-include.php";

?>


<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php include "assets/common-includes/total_count.php" ?>
    <title>Not Interested Leads</title>
    <?php include "assets/common-includes/header_includes.php" ?>
    <style>
        @media (max-width: 480px){
            .footer1_div {
                margin: 6px 0 0px -16px;
            }
        }

    </style>

</head>
<body>
<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>
<section class="content">
    <div class="clearfix padding_bottom_46">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding_zero padding_zero_both">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-8 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header text-right">
                            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post"
                                  id="export-form">
                                <button class="btn btn-success" name='ExportType' value="export-to-excel" id="btnExport"><i
                                        class="fa fa-download"></i> Export To Excel
                                </button>
                            </form>
                        </div>
                        <div class="body custom_filter_padding">
                            <div style="padding: 15px;">
                                <fieldset>
                                    <legend>Filter</legend>
                                    <form method='post' action=''>
                                        <ul class='ul_search'>
                                            <li class='ul_search_li'>
                                                <div class='form-line'>
                                                    <label>Enter Text</label>
                                                    <input type='text' name='txt_name' class='form-control' placeholder="Enter some text"
                                                           value='<?php if(isset($_POST['txt_name'])) echo $_POST['txt_name']; ?>' >
                                                </div>
                                            </li>  <li class='ul_search_li'>
                                                <div class='form-line'>
                                                    <label>From Date</label>
                                                    <input type='date' name='from_date' class='form-control'
                                                           value='<?php if(isset($_POST['from_date'])) echo $_POST['from_date']; ?>' >
                                                </div>
                                            </li>
                                            <li class='ul_search_li'>
                                                <div class='form-line'>
                                                    <label>To Date</label>
                                                    <input type='date' name='to_date' class='form-control'
                                                           value='<?php if(isset($_POST['to_date'])) echo $_POST['to_date']; ?>'>
                                                </div>
                                            </li>
                                            <li class='ul_search_li'>
                                                <div class='form-inline'>
                                                    <button type='submit' name='search' class='btn btn-primary'>Search</button>
                                                    <a  href="" class='btn btn-danger'>Cancel</a>
                                                </div>
                                            </li>
                                        </ul></form>
                                </fieldset>
                            </div>
                            <ul class="nav nav-tabs tab-nav-right marg-top-5" role="tablist">
                                <li role="presentation"  ><a class="custom_nav_tab" href="my-leads.php" ><i class="fa fa-flag"></i> Pending <span class="badge badge-success"><?php echo $pending_total_count; ?></span></a></li>
                                <li role="presentation" ><a class="custom_nav_tab" href="follow_up.php"><i class="fa fa-flag"></i> Follow UP <span class="badge badge-success"><?php echo $followup_total_count; ?></span></a></li>
                                <li role="presentation" ><a class="custom_nav_tab" href="converted-leads.php"  ><i class="fa fa-check-square-o" aria-hidden="true"></i> Converted <span class="badge badge-success"><?php echo $converted_total_count ?></span></a></li>
                                <li role="presentation" class="active"><a class="custom_nav_tab" href="#not_interest" data-toggle="tab" ><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Not Interested <span class="badge badge-success"><?php echo $not_interest_total_count ?></span></a></li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content paddin-top-zero">
                                <div role="tabpanel" class="tab-pane fade in active " id="not_interest">
                                    <!--<div class="header">
                                        <h2>
                                            Total Not Interested Leads <span class="badge"><?php
/*                                                if (isset($total_customer)) echo $total_customer;
                                                */?></span>
                                        </h2>
                                    </div>-->
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
                                        <div style="overflow-x: auto">
                                            <table class="table table-striped table-bordered table-sm "
                                                   cellspacing="0"
                                                   width="100%"><!-- id="dtHorizontalVerticalExample" -->
                                                <thead>
                                                <tr class="back-color">
                                                    <th>Date</th>
                                                    <th>Name</th>
                                                    <th>Contact</th>
                                                    <th>Service</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody  class="row_position">
                                                <?php
                                                if ($displaydata != null) {
                                                    $i=1;
                                                    while ($result_lead_data = mysqli_fetch_array($displaydata)) {
                                                        ?>
                                                        <tr>
                                                            <td><?php echo date('d-M-Y',strtotime($result_lead_data['created_date'])); ?></td>
                                                            <td><?php echo $result_lead_data['client_name']; ?></td>
                                                            <td><?php echo $result_lead_data['contact_no']; ?></td>
                                                            <td><?php echo $result_lead_data['service_name']; ?></td>
                                                            <td> <form method="post" action="">
                                                                    <select id="set_status" name="set_status"
                                                                            onchange="SetStatus('<?php echo $i ?>')"
                                                                            class="form-control">
                                                                        <option <?php if (isset($result_lead_data['approve_status']) && $result_lead_data['approve_status'] == 'Follow Up') echo 'selected' ?>><i class="fa fa-flag" aria-hidden="true"></i> Follow Up</option>
                                                                        <option <?php if (isset($result_lead_data['approve_status']) && $result_lead_data['approve_status'] == 'Converted') echo 'selected' ?>><i class="fa fa-thumbs-o-up" aria-hidden="true"></i> Converted
                                                                        </option>
                                                                        <option <?php if (isset($result_lead_data['approve_status']) && $result_lead_data['approve_status'] == 'Not Interested') echo 'selected' ?>><i class="fa fa-thumbs-o-down" aria-hidden="true"></i> Not Interested
                                                                        </option>
                                                                    </select>
                                                                    <input type="hidden" value="<?php echo $security->encryptWebservice($result_lead_data['id']); ?>"
                                                                           name="row_id">
                                                                    <input type="submit" style="display: none" name="change" id="submit_form<?php echo $i; ?>" />
                                                                </form></td>
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
                                            <div>

                                                <ul class="pagination m-0">
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
                                                <div style="padding-left: 10px;">
                                                    <strong>Page <?php echo $page_no . " of " . $total_no_of_pages; ?></strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</section>
<?php include "assets/common-includes/footer_includes.php" ?>
<script type="text/javascript">

    function SetStatus(i) {
        $('#submit_form'+i)[0].click();
    }
</script>
</body>
</html>