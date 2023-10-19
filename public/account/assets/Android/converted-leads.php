<?php
ob_start();
include_once "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include "../controller/ManageUser.php";
$manage = new ManageUser();
$error = false;
$errorMessage = "";


if (isset($_GET['user_id'])) {
    $id = $security->decryptWebservice($_GET['user_id']);
    $validateId = $manage->validateUserId($id);
}
if (isset($_GET['page_no']) && $_GET['page_no'] != "") {
    $page_no = $_GET['page_no'];
} else {
    $page_no = 1;
}


if(isset($_POST['change'])){
    if(isset($_POST['row_id']) && $_POST['row_id'] !=""){
        $row_id = $security->decryptWebservice($_POST['row_id']);
        $set_status = $_POST['set_status'];
        $condition = array('id'=>$row_id);
        $data = array('approve_status'=>$set_status);
        $update = $manage->update($manage->serviceRequestTable,$data,$condition);
        if($update){
            $error = false;
            $errorMessage = "Status has been changed";
        }else{
            $error = true;
            $errorMessage = "Issue while updating status please try after some time.";
        }
    }
}

$approve_status = "Converted";
$get_total_sub_leads_count = $manage->total_sub_leads_count($id);
$followup_total_count = $get_total_sub_leads_count['total_followup'];
$converted_total_count = $get_total_sub_leads_count['total_converted'];
$not_interest_total_count = $get_total_sub_leads_count['total_not_interest'];
if($converted_total_count == null){
    $converted_total_count = 0;
}
if($followup_total_count == null){
    $followup_total_count = 0;
}
if($not_interest_total_count ==null){
    $not_interest_total_count = 0;
}

if(isset($_POST['search'])){
    $txt_name = $_POST['txt_name'];
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    $total_customer = $manage->displayLeadResultWithLimitForFilterCount($id,$approve_status,$offset, $total_records_per_page,$txt_name,$from_date,$to_date);
    $total_records_per_page = 25;
    $offset = ($page_no - 1) * $total_records_per_page;
    $previous_page = $page_no - 1;
    $next_page = $page_no + 1;
    $adjacents = "2";
    $total_records = $total_customer;
    $total_no_of_pages = ceil($total_records / $total_records_per_page);
    $second_last = $total_no_of_pages - 1; // total page minus 1
    $displaydata = $manage->displayLeadResultWithLimitForFilter($id,$approve_status,$offset, $total_records_per_page,$txt_name,$from_date,$to_date);
}else{
    $get_total_customer = $manage->displayLeadResult($id,$approve_status);
    if($get_total_customer !=null){
        $total_customer = mysqli_num_rows($get_total_customer);
    }else{
        $total_customer = 0;
    }
    $total_records_per_page = 25;
    $offset = ($page_no - 1) * $total_records_per_page;
    $previous_page = $page_no - 1;
    $next_page = $page_no + 1;
    $adjacents = "2";
    $total_records = $total_customer;
    $total_no_of_pages = ceil($total_records / $total_records_per_page);
    $second_last = $total_no_of_pages - 1; // total page minus 1
    $displaydata = $manage->displayLeadResultWithLimit($id,$approve_status,$offset, $total_records_per_page);
}

?>


<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php include "assets/common-includes/total_count.php" ?>
    <title>Converted Leads</title>
    <?php include "assets/common-includes/header_includes.php" ?>
    <style>
        @media (max-width: 480px){
            .footer1_div {
                margin: 6px 0 0px -16px;
            }
        }
        .nav-tabs > li {
            width: 49%
        }
        .nav > li > a {
            padding: 10px 3px;
        }

    </style>

</head>
<body>

<section class="user_contact">
    <div class="clearfix padding_bottom_46">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding_zero padding_zero_both">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-8 col-sm-12 col-xs-12">
                    <div class="card">

                        <div class="body custom_filter_padding">
                            <div >
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
                                                <div class='form-inline' style="display: inline-flex">
                                                    <button type='submit' name='search' class='btn btn-primary'>Search</button>
                                                    <a  href="" class='btn btn-danger'>Cancel</a>
                                                </div>
                                            </li>
                                        </ul></form>
                                </fieldset>
                            </div>
                            <ul class="nav nav-tabs tab-nav-right marg-top-5" role="tablist">
                                <li role="presentation" ><a class="custom_nav_tab" href="my-leads.php?user_id=<?php echo $_GET['user_id']; ?>"><i class="fa fa-flag"></i> Follow UP <span class="badge badge-success"><?php echo $followup_total_count; ?></span></a></li>
                                <li role="presentation" class="active"><a class="custom_nav_tab" href="#converted" data-toggle="tab" ><i class="fa fa-check-square-o" aria-hidden="true"></i> Converted <span class="badge badge-success"><?php echo $converted_total_count ?></span></a></li>
                                <li role="presentation"><a class="custom_nav_tab" href="not-interested.php?user_id=<?php echo $_GET['user_id']; ?>" ><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Not Interested <span class="badge badge-success"><?php echo $not_interest_total_count ?></span></a></li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content paddin-top-zero">
                                <div role="tabpanel" class="tab-pane fade in active " id="converted">
                                   <!-- <div class="header">
                                        <h2>
                                            Total Converted Leads <span class="badge"><?php
/*                                                if (isset($total_customer)) echo $total_customer;
                                                */?></span>
                                        </h2>
                                    </div>-->
                                    <div class="lead-body">
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