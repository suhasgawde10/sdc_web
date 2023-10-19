<?php
error_reporting(0);

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


$get_service_count = $manage->displayServiceDetails();
if ($get_service_count != null) {
    $service_count = mysqli_num_rows($get_service_count);
} else {
    $service_count = 0;
}

/*Image*/
$get_image_count = $manage->displayImageDetails();
if ($get_image_count != null) {
    $image_count = mysqli_num_rows($get_image_count);
} else {
    $image_count = 0;
}
/*Clients*/
$get_client_count = $manage->displayClientDetails();
if ($get_client_count != null) {
    $client_count = mysqli_num_rows($get_client_count);
} else {
    $client_count = 0;
}
/*Clients Review*/
$get_client_review_count = $manage->displayClientReviewDetails();
if ($get_client_review_count != null) {
    $client_review_count = mysqli_num_rows($get_client_review_count);
} else {
    $client_review_count = 0;
}
/*our Team*/
$get_our_team_count = $manage->displayTeamDetails();
if ($get_our_team_count != null) {
    $our_team_count = mysqli_num_rows($get_our_team_count);
} else {
    $our_team_count = 0;
}
/*Image Slider*/
/*$get_result = $manage->displayImageSliderDetails();
if ($get_result != null) {
    $sliderCount = mysqli_num_rows($get_result);
} else {
    $sliderCount = 0;
}*/
/*Video*/
$get_status = $manage->displayVideoDetails();
if ($get_status != null) {
    $countForVideo = mysqli_num_rows($get_status);
} else {
    $countForVideo = 0;
}


/*$display_message = $manage->displayDealerProfile();
if($display_message!=null){
    $message_status = $display_message['message_status'];
    $status = $display_message['status'];
}*/


$date1 = date("Y-m-d");
$date = date_create("$date1");
date_add($date, date_interval_create_from_date_string("30 days"));
$final_date = date_format($date, "Y-m-d");
/*echo $final_date;
die();*/
$get_data = $manage->displayRelatedUser($date1, $final_date);

if ($get_data != null) {
    $countRenawalListing = mysqli_num_rows($get_data);
} else {
    $countRenawalListing = 0;
}
$alert_status = false;
$failed_status = false;
if(isset($_POST['search'])){
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
}else{
    $from_date = "";
    $to_date = "";
}
$displayLog = $manage->displayLogDetailsOfUser($from_date,$to_date);
$displayLog1 = $manage->displayLogDetailsOfUser($from_date,$to_date);
$displayLog2 = $manage->displayLogDetailsOfUserByCity($from_date,$to_date);
$displayLog3 = $manage->displayLogDetailsOfUserByCity($from_date,$to_date);
$displayLog4 = $manage->displayLogDetailsOfUserByState($from_date,$to_date);
$displayLog5 = $manage->displayLogDetailsOfUserByState($from_date,$to_date);
if ($displayLog != null) {
    $countLog = mysqli_num_rows($displayLog);
} else {
    $countLog = 0;
}
$page_data = "";
if($displayLog1 !=null){

    while($row = mysqli_fetch_array($displayLog1)){
        $page_type = $row['page_type'];
        if($page_type =="index.php"){
            $page_type ="Home";
        }
        $count = $row['count'];
        if($page_data !=""){
            $page_data .=",'" . pathinfo($page_type, PATHINFO_FILENAME) . "'";
            $page_data_count .="," . $count;
        }else{
            $page_data .="'" . pathinfo($page_type, PATHINFO_FILENAME) . "'";
            $page_data_count .=$count;
        }

    }
}else{
    $page_data ="['Home',0]";
}

$city_data = "";
if($displayLog2 !=null){
    while($row1 = mysqli_fetch_array($displayLog2)){
        $city = $row1['city'];
        $count = $row1['count'];
        if($city_data !=""){
            $city_data .=",'" . $city . "'";
            $city_data_count .="," . $count;
        }else{
            $city_data .="'" . $city . "'";
            $city_data_count .=$count;
        }

    }
}else{
    $city_data ="['City',0]";
}

$state_data = "";
if($displayLog4 !=null){
    while($row2 = mysqli_fetch_array($displayLog4)){
        $state = $row2['state'];
        $count = $row2['count'];
        $state_data .=",['" . $state . "'," . $count ."]";
    }
}else{
    $state_data ="['state',0]";
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
    <style>
        @media (max-width: 480px){
            .footer1_div {
                margin: 6px 0 0px -16px;
            }
        }
        #myChart{
            height: 300px !important;
        }
        #change{
            display: none;
        }
       /* .apexcharts-canvas{
            width: 100% !important;
        }
        .apexcharts-canvas > svg{
            width: 100% !important;
        }*/
    </style>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script>
        /*google.charts.load('current', {packages: ['corechart', 'bar']});
        google.charts.setOnLoadCallback(drawBarColors);

        function drawBarColors() {
            var data = google.visualization.arrayToDataTable([
                ['Page Type', 'Total Count']
                <?php
              //  echo $page_data;
                ?>
            ]);

            var options = {
                title: 'Statistics of logs result',
                chartArea: {width: '100%'},
                colors: ['#b0120a', '#ffab91'],
                hAxis: {
                    title: 'Total view count',
                    minValue: 0
                }

            };
            var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }*/
    </script>
   <!-- <script>
        google.charts.load('current', {packages: ['corechart', 'bar']});
        google.charts.setOnLoadCallback(drawBarColors);

        function drawBarColors() {
            var data = google.visualization.arrayToDataTable([
                ['City', 'Total Count']
                <?php
/*                echo $city_data;
                */?>
            ]);

            var options = {
                title: 'Statistics of logs result',
                chartArea: {width: '100%'},
                colors: ['#b0120a', '#ffab91'],
                hAxis: {
                    title: 'Total view count',
                    minValue: 0
                }
            };
            var chart = new google.visualization.BarChart(document.getElementById('chart_div1'));
            chart.draw(data, options);
        }
    </script>-->
    <script>
        google.load('visualization', '1', {'packages': ['geochart']});
        google.setOnLoadCallback(drawVisualization);

        function drawVisualization() {
            var data = google.visualization.arrayToDataTable([
                ['State', 'Total Count']
                <?php
                echo $state_data;

                ?>
            ]);

            var opts = {
                region: 'IN',
                displayMode: 'regions',
                resolution: 'provinces',
                width: 640,
                height: 480
            };
            var geochart = new google.visualization.GeoChart(
                document.getElementById('visualization'));
            geochart.draw(data, opts);
        };

    </script>
</head>
<body>
<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>
<section class="content custom-m-t-90">
    <div class="clearfix padding_bottom_46">


        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding_zero padding_zero_both">
            <div class="row clearfix">

                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-6 custom_padding">
                    <a href="service.php">
                        <div class="info-box bg-blue ">
                            <div class="icon">
                                <i class="far fa-list-alt"></i>
                            </div>
                            <div class="content">
                                <div class="text">Service</div>
                                <div class="number"><?php if (isset($service_count)) echo $service_count; ?></div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-6 custom_padding">
                    <a href="gallery.php">
                        <div class="info-box bg-blue ">
                            <div class="icon">
                                <i class="fas fa-images"></i>
                            </div>
                            <div class="content">
                                <div class="text">Image</div>
                                <div class="number"><?php if (isset($image_count)) echo $image_count; ?></div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-6 custom_padding">
                    <a href="video_gallery.php">
                        <div class="info-box bg-blue ">
                            <div class="icon">
                                <i class="fas fa-images"></i>
                            </div>
                            <div class="content">
                                <div class="text">video</div>
                                <div class="number count-to"><?php if (isset($countForVideo)) echo $countForVideo; ?></div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-6 custom_padding">
                    <a href="testimonial.php">
                        <div class="info-box bg-blue ">
                            <div class="icon">
                                <i class="fas fa-poll"></i>
                            </div>
                            <div class="content">
                                <div class="text">Client</div>
                                <div class="number"><?php if (isset($client_count)) echo $client_count; ?></div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-6 custom_padding">
                    <a href="clients_review.php">
                        <div class="info-box bg-blue">
                            <div class="icon">
                                <i class="fas fa-poll"></i>
                            </div>
                            <div class="content">
                                <div class="text">Client Reviews</div>
                                <div
                                    class="number"><?php if (isset($client_review_count)) echo $client_review_count; ?></div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-6 custom_padding">
                    <a href="our-team.php">
                        <div class="info-box bg-blue ">
                            <div class="icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="content">
                                <div class="text">Our Team</div>
                                <div
                                    class="number count-to"><?php if (isset($our_team_count)) echo $our_team_count; ?></div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding_zero padding_zero_both">
            <div class="row clearfix">
<!--
                <div class="col-lg-12 col-md-8 col-sm-12 col-xs-12">
            <div class="card">
                <div class="body">
                    <fieldset>
                        <legend>Filter</legend>
                        <form method='post' action=''>
                            <ul class='ul_search'>
                                <li class='ul_search_li'>
                                    <div class='form-line'>
                                        <label>From Date</label>
                                        <input type='date' name='from_date' class='form-control'
                                               value='<?php /*if(isset($_POST['from_date'])) echo $_POST['from_date']; */?>' >
                                    </div>
                                </li>
                                <li class='ul_search_li'>
                                    <div class='form-line'>
                                        <label>To Date</label>
                                        <input type='date' name='to_date' class='form-control'
                                               value='<?php /*if(isset($_POST['to_date'])) echo $_POST['to_date']; */?>'>
                                    </div>
                                </li>
                                <li class='ul_search_li'>
                                    <div class='form-inline'>
                                        <button type='submit' name='search' class='btn btn-primary'>Search</button>
                                    </div>
                                </li>
                            </ul></form>
                    </fieldset>
                </div>
            </div>
                    </div>-->
                <div class="col-lg-12 col-md-8 col-sm-12 col-xs-12">
                    <div class="card">

                        <div class="body custom_filter_padding">
                            <div style="padding: 15px;">
                                <fieldset>
                                    <legend>Filter</legend>
                                    <form method='post' action=''>
                                        <ul class='ul_search'>
                                            <li class='ul_search_li'>
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
                                                </div>
                                            </li>
                                        </ul></form>
                                </fieldset>
                            </div>
                            <ul class="nav nav-tabs tab-nav-right marg-top-5" role="tablist">
                                <li role="presentation" <?php if(!isset($_GET['city_data'])) echo 'class="active"'; ?> ><a class="custom_nav_tab" href="#state" data-toggle="tab"><i class="fa fa-flag"></i> State</a></li>
                                <li role="presentation" <?php if(isset($_GET['city_data'])) echo 'class="active"'; ?> ><a class="custom_nav_tab" href="#city" data-toggle="tab" onclick="displayCityChart()"><i class="fa fa-building"></i> City</a></li>
                                <li role="presentation"><a class="custom_nav_tab" href="#page" data-toggle="tab"><i class="fa fa-list"></i> Page</a></li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content paddin-top-zero">
                                <div role="tabpanel" class="tab-pane <?php if(!isset($_GET['city_data'])) echo "fade in active"; ?> " id="state">
                                    <div class="body paddin-top-zero">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div id="visualization" style="overflow: auto;"> </div>
                                            </div>
                                            <div class="col-md-4">
                                                <?php
                                                $state_table = "<div class='body table-responsive table_scroll'>
                    <table class='table table-condensed table-bordered table-striped'>
                        <thead>
                        <tr class='back-color'>
                            <th>State Name</th>
                            <th>Count</th>

                        </tr>
                        </thead>
                        <tbody>";
                                                if ($displayLog5 != null) {
                                                    while ($result_data = mysqli_fetch_array($displayLog5)) {
                                                        $page_type = $result_data['state'];
                                                        $user_count = $result_data['count'];
                                                        $state_table .= "<tr>
                                    <td>" . pathinfo($page_type, PATHINFO_FILENAME) . "</td>
                                    <td>" . $user_count . "</td>
                                </tr>";
                                                    }
                                                } else {
                                                    $state_table .= "<tr><td colspan='2' class='text-center'>No data found!</td></tr>";
                                                }
                                                $state_table .= "</tbody></table></div>";


                                                echo $state_table;
                                                ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- page wise-->
                                </div>
                                <div role="tabpanel" class="tab-pane <?php if(isset($_GET['city_data'])) echo "fade in active"; ?> " id="city">
                                            <div class="container" style="
                                            margin-top: 15px;
  width: 100%;
  text-align: center;">
                                                <!--<div id="chart_div1"></div>-->

                                                    <div id="chart"></div>
                                                    <button id="change">Horizontal</button>


                                            </div>
                             <!--               <div class="col-md-3">
                                                <?php
/*                                                $city_table = "<div class='table-responsive table_scroll'>
                    <table class='table table-condensed table-bordered table-striped'>
                        <thead>
                        <tr class='back-color'>
                            <th>City Name</th>
                            <th>Count</th>

                        </tr>
                        </thead>
                        <tbody>";
                                                if ($displayLog3 != null) {
                                                    while ($result_data = mysqli_fetch_array($displayLog3)) {
                                                        $page_type = $result_data['city'];
                                                        $user_count = $result_data['count'];
                                                        $city_table .= "<tr>
                                    <td>" . pathinfo($page_type, PATHINFO_FILENAME) . "</td>
                                    <td>" . $user_count . "</td>
                                </tr>";
                                                    }
                                                } else {
                                                    $city_table .= "<tr><td colspan='2' class='text-center'>No data found!</td></tr>";
                                                }
                                                $city_table .= "</tbody></table></div>";


                                                echo $city_table;
                                                */?>
                                            </div>-->
                                    <!-- page wise-->
                                </div>
                                <div role="tabpanel" class="tab-pane" id="page">
                                    <div class="body paddin-top-zero">


                                        <?php
                                        if ($alert_status) {
                                            $log_table .="<div class='alert alert-success'>
                            <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                <strong>Success!</strong> Message Sent Successfully!
                        </div>";
                                        }
                                        ?>

                                        <div class="row">

                                            <div class="col-md-12">
                                                <div class="row">
                                                    <!--<div id="chart_div"></div>-->
                                                    <canvas id="myChart"></canvas>
                                                </div>
                                            </div>
                                            <!--<div class="col-md-3">
                                                <?php
/*                                                $table = "<div class='body table-responsive table_scroll'>
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
                                                        if($page_type == "index.php"){
                                                            $page_type = "Home";
                                                        }
                                                        $table .= "<tr>
                                    <td>" . pathinfo($page_type, PATHINFO_FILENAME) . "</td>
                                    <td>" . $user_count . "</td>
                                </tr>";
                                                    }
                                                } else {
                                                    $table .= "<tr><td colspan='2' class='text-center'>No data found!</td></tr>";
                                                }
                                                $table .= "</tbody></table></div>";


                                                echo $_SESSION['log_table'] = $table;
                                                */?>
                                            </div>-->
                                        </div>
                                    </div>
                                    <!-- page wise-->
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.js"></script>
<script>
    var ctx = document.getElementById("myChart").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [<?php echo $page_data; ?>],
            datasets: [{
                label: 'Count', // Name the series
                data: [<?php echo $page_data_count; ?>], // Specify the data values array
                fill: false,
                borderColor: '#2196f3', // Add custom color border (Line)
                backgroundColor: '#2196f3', // Add custom color background (Points and Fill)
                borderWidth: 1 // Specify bar border width
            }]},
        options: {
            responsive: true, // Instruct chart js to respond nicely.
            maintainAspectRatio: false, // Add to prevent default behaviour of full-width/height
        }
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/1.4.6/apexcharts.min.js"></script>
<script>
    // Chart options
    const options = {
        chart: {
            height: "auto",
            width: "100%",
            type: "bar",
            background: "#f4f4f4",
            foreColor: "#333"
        },
        plotOptions: {
            bar: {
                horizontal: true
            }
        },
        series: [
            {
                name: "Total Count",
                data: [
                    <?php echo $city_data_count; ?>
                ]
            }
        ],
        xaxis: {
            categories: [
               <?php echo $city_data; ?>
            ]
        },
        fill: {
            colors: ["#F44336"]
        },
        dataLabels: {
            enabled: false
        },

        title: {
            text: "Total Page Count By City",
            align: "center",
            margin: 20,
            offsetY: 20,
            style: {
                fontSize: "25px"
            }
        }
    };

    // Init chart
    const chart = new ApexCharts(document.querySelector("#chart"), options);

    // Render chart
    chart.render();

    // Event example
    document.getElementById("change").addEventListener("click", () =>
        chart.updateOptions({
            plotOptions: {
                bar: {
                    horizontal: true
                }
            }
        })
    );

</script>
<script>
    //var rect_tag = document.getElementsByTagName('rect');
   /* var x = document.getElementById("SvgjsRect1012");
    console.log(x);
    if(x < 0){
        document.getElementsByTagName("rect")[0].setAttribute("width","0");
    }*/
          function displayCityChart(){
          window.location.href = "dashboard.php?city_data=true";
      }
</script>
</body>
</html>