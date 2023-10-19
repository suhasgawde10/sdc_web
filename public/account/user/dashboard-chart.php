<?php
ob_start();
include_once "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include "../controller/ManageUser.php";
$manage = new ManageUser();

if (!isset($_SESSION['email'])) {
    header('location:../login.php');
}
/*unset($_SESSION['create_user_status']);*/

/*Sewrvice*/

include("session_includes.php");
include "validate-page.php";
$alert_status = false;
$failed_status = false;
$displayLog = $manage->displayLogDetailsOfUser();
$displayLog1 = $manage->displayLogDetailsOfUser();
if ($displayLog != null) {
    $countLog = mysqli_num_rows($displayLog);
} else {
    $countLog = 0;
}
$data = "";
$i = 1;
while($row = mysqli_fetch_array($displayLog1)){
    $state = $row['state'];
    $count = $row['count'];
    $data .=",['" . $state . "'," . $count ."]";
$i++;
}

?>


<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php include "assets/common-includes/total_count.php" ?>
    <title>Dashboard</title>
    <?php include "assets/common-includes/header_includes.php" ?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(drawBarColors);

    function drawBarColors() {
        var data = google.visualization.arrayToDataTable([
            ['State', 'Total Count']
            <?php
            echo $data;
            ?>
            ]);

        var options = {
            title: 'Statistics of logs result',
            chartArea: {width: '50%'},
            colors: ['#b0120a', '#ffab91'],
            hAxis: {
                title: 'Total view count',
                minValue: 0
            }
        };
        var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }
</script>
</head>
<body>
<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>
<section class="content">
    <div class="clearfix padding_bottom_46">
    <div class="container-fluid">
        <div class="block-header col-md-12">
            <h2>Statistic</h2>
        </div>
        <div class="row clearfix">

            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            Manage Log details  <span class="badge"><?php
                                if (isset($countLog)) echo $countLog;
                                ?></span>
                        </h2>
                    </div>
                    <div class="body">
                        <div id="chart_div"></div>
                    </div>
                    </div></div>


            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            Manage Log details  <span class="badge"><?php
                                if (isset($countLog)) echo $countLog;
                                ?></span>
                        </h2>
                    </div>
                    <div class="clearfix"></div>

                    <?php
                    $log_table = "<div style='padding: 10px;' class='col-md-12'>
                        <form method='post' action=''>";
                    if ($alert_status) {
                        $log_table .="<div class='alert alert-success'>
                            <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                <strong>Success!</strong> Message Sent Successfully!
                        </div>";
                    }
                    $log_table .="
                            <ul class='ul_search'>
                                <li class='ul_search_bar'>
                                    <div class='form-line'>
                                        <label>From Date</label>
                                        <input type='date' name='from_date' class='form-control'
                                               value=''>
            </div>
            </li>
            <li class='ul_search_bar'>
                <div class='form-line'>
                    <label>To Date</label>
                    <input type='date' name='to_date' class='form-control'
                           value=''>
                </div>
            </li>
            <li class='ul_search_bar'>
                <div class='form-inline'>
                    <button type='submit' name='search' class='btn btn-primary'>Search</button>
                </div>
            </li>
            </ul>
            </form>
    </div>
    <div class='clearfix'></div>";
                    $table = "<div class='body table-responsive table_scroll'>
                    <table class='table table-condensed table-bordered table-striped'>
                        <thead>
                        <tr class='back-color'>
                            <th>Page Type</th>
                            <th>Count</th>

                        </tr>
                        </thead>
                        <tbody>";
                    if ($displayLog != null) {
                        while ($result_data = mysqli_fetch_array($displayLog)) {
                            $page_type = $result_data['page_type'];
                            $user_count = $result_data['count'];
                            $table .= "<tr>
                                    <td>" . pathinfo($page_type, PATHINFO_FILENAME) . "</td>
                                    <td>" . $user_count . "</td>
                                </tr>";
                        }
                    } else {
                        $table .= "<tr><td colspan='10' class='text-center'>No data found!</td></tr>";
                    }
                    $table .= "</tbody></table></div>";

                    $log_table .= $table;
                    $_SESSION['log_table'] = $table;
                    echo $log_table;
                    ?>
                </div>
            </div>
        </div>
    </div>
        </div>
</section>
<?php include "assets/common-includes/footer_includes.php" ?>
</body>
</html>