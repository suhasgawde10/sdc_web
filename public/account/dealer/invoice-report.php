<?php
ob_start();
include "../controller/ManageDealer.php";
$manage = new ManageDealer();
include '../sendMail/sendMail.php';
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
if (!isset($_SESSION['dealer_email'])) {
    header('location:../dealer-register.php');
}
/*unset($_SESSION['create_user_status']);*/

/*Sewrvice*/

$error = false;
$errorMessage = "";



/*$dailyUser = $manage0>dispa*/

$display_message = $manage->displayDealerProfile();
if ($display_message != null) {
    $message_status = $display_message['message_status'];
      $dealer_status = $display_message['status'];     $pay_status = $display_message['pay_status'];
    $deal_code = $display_message['dealer_code'];
    $id = $display_message['id'];
    $wallet_amount = $display_message['wallet_amount'];

}

$displaySubscription = $manage->md_displayInvoiceDetailsForDealer();
if ($displaySubscription != null) {
    $countDescription = mysqli_num_rows($displaySubscription);
} else {
    $countDescription = 0;
}

if (isset($_POST['search'])) {
    if(isset($_POST['from_date'])){
        $from_date = $_POST['from_date'];
    }else{
        $from_date = "";
    }
    if(isset($_POST['to_date'])){
        $to_date = $_POST['to_date'];
    }else{
        $to_date = "";
    }
    $displaySubscription = $manage->md_displayInvoiceDetailsForDealerForFilter($from_date, $to_date);
    if ($displaySubscription != null) {
        $countDescription = mysqli_num_rows($displaySubscription);
    } else {
        $countDescription = 0;
    }

}



if (isset($_POST['print'])) {
    if (isset($_POST['check']) && $_POST['check'] != "") {
        $checkbox = $_POST['check'];
    } else {
        $error = true;
        $errorMessage = 'Please select atleast one checkbox';
    }
    if (!$error) {
        $inner_array = array();
        $id = "";
        for ($i = 0; $i < count($checkbox); $i++) {
            if ($id != "") {
                $id .= "," . $security->encrypt($checkbox[$i]);
            } else {
                $id .= $security->encrypt($checkbox[$i]);
            }
        }
        header('location:user-invoice.php?user_invoice_id=' . $id);
    }
}




?>


<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Dashboard</title>
    <?php include "assets/common-includes/header_includes.php" ?>
</head>
<body>
<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>
<section class="content">
    <div class="container-fluid">

        <div class="row clearfix">
            <form method="post" action="">
                <div class="col-lg-12 col-md-4 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-10">
                                        <h2>
                                            Manage Invoice <span class="badge"><?php
                                                if (isset($countDescription)) echo $countDescription;
                                                ?></span>
                                        </h2>
                                    </div>

                                    <div class="col-md-2 text-right">
                                        <button type='submit' name="print" class='btn btn-default'>Print</button>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                        <div class="col-md-12 invoice_margin " style="overflow: hidden;z-index: 999">

                            <ul class="ul_search">
                                <li class="ul_search_li">
                                    <div class='form-line'>
                                        <label>From Date</label>
                                        <input type='date' name='from_date' class='form-control'
                                               value="<?php if (isset($from_date)) echo $from_date; ?>">
                                    </div>
                                </li>
                                <li class="ul_search_li">
                                    <div class='form-line'>
                                        <label>To Date</label>
                                        <input type='date' name='to_date' class='form-control'
                                               value="<?php if (isset($to_date)) echo $to_date; ?>">
                                    </div>
                                </li>
                                <li class="ul_search_li">
                                    <div class='form-inline'>
                                        <button type='submit' name='search' class='btn btn-primary'>Search</button>
                                        <a type='button' href="" class='btn btn-danger'>Cancel</a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="body table-responsive table_scroll">
                            <table id="dtHorizontalExample" class="table table-striped table-bordered table-sm "
                                   cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr class="back-color">
                                    <th><input type="checkbox" id="checkAl"></th>
                                    <th>Invoice No/Date</th>
                                    <th>Profile</th>
                                    <th>Package</th>
                                    <th>Amount</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Payment Status</th>
                                    <!--<th>Status</th>-->
                                    <th>Action</th>
                                    <!--<th>ACTION</th>-->
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if ($displaySubscription != null) {
                                    while ($result_data = mysqli_fetch_array($displaySubscription)) {
                                        ?>
                                        <tr <?php if ($result_data['status'] == "success") {
                                            echo 'style="background-color: #B2DFDB"';
                                        } elseif ($result_data['status'] == "failed") {
                                            echo 'style="background-color: #FFCDD2"';
                                        }; ?>>
                                            <!--<td><?php /*echo $result_data['name']; */ ?></td>
                                    <td><?php /*echo $result_data['contact']; */ ?></td>-->
                                            <!--<td><?php /*if ($result_data['type'] == 1) {
                                            echo "Digital Card";
                                        } else {
                                            echo "Digital Card + Website (combo)";
                                        } */ ?></td>-->
                                            <td><input type="checkbox" id="checkItem" name="check[]"
                                                       value="<?php echo $result_data["id"]; ?>"></td>
                                            <td><?php echo $result_data['invoice_no'] . " / " . $result_data['timestamp']; ?></td>
                                            <td><?php echo $result_data['name']; ?>
                                                <br><?php echo $result_data['email']; ?></td>
                                            <td><?php echo $result_data['year']; ?></td>
                                            <td><?php echo $result_data['total_amount']; ?></td>
                                            <td><?php echo $result_data['start_date']; ?></td>
                                            <td><?php echo $result_data['end_date']; ?></td>
                                            <td><?php echo strtoupper($result_data['status']); ?><br><label
                                                        class="label <?php if ($result_data['active_plan'] == "0") {
                                                            echo "label-danger";
                                                        } else {
                                                            echo "label-success";
                                                        } ?>"><?php if ($result_data['active_plan'] == 0) {
                                                        echo "Expired";
                                                    } else {
                                                        echo "Active";
                                                    } ?></label></td>
                                            <td>
                                                <a class="btn btn-default" <?php if ($result_data['invoice_no'] == "" or $result_data['invoice_no'] == 'NULL') {
                                                    echo 'disabled';
                                                    echo ' href="#"';
                                                } else {
                                                    echo 'href="user-invoice.php?user_invoice_id=' . $security->encrypt($result_data['id']) . '"';
                                                } ?>>View Invoice</a></td>

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
                        </div>

                    </div>
                </div>
            </form>
            <!-- <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a href="image-slider.php">
                    <div class="info-box bg-pink hover-expand-effect">
                        <div class="icon">
                            <i class="far fa-image"></i>
                        </div>
                        <div class="content">
                            <div class="text">Image Slider</div>
                            <div class="number count-to"><?php /*if (isset($sliderCount)) echo $sliderCount; */ ?></div>
                        </div>
                    </div>
                </a>
            </div>-->
        </div>
    </div>


</section>

<?php include "assets/common-includes/footer_includes.php" ?>
<script>
    $("#checkAl").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
</script>
</body>
</html>