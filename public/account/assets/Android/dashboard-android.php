<?php

include "../controller/ManageUser.php";
$manage = new ManageUser();
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();


if (isset($_GET['user_id'])) {
    $id = $security->decryptWebservice($_GET['user_id']);
    $displayLog = $manage->displayLogDetails($id);
    if ($displayLog != null) {
        $countLog = mysqli_num_rows($displayLog);
    } else {
        $countLog = 0;
    }

    $_SESSION["id"]= $security->encrypt($id);

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
            if($page_type =="index"){
                $page_type ="Home";
            }
            $count = $row['count'];
            $page_data .=",['" . pathinfo($page_type, PATHINFO_FILENAME) . "'," . $count ."]";
        }
    }
    $city_data = "";
    if($displayLog2 !=null){
        while($row1 = mysqli_fetch_array($displayLog2)){
            $city = $row1['city'];
            $count = $row1['count'];
            $city_data .=",['" . $city . "'," . $count ."]";
        }
    }

    $state_data = "";
    if($displayLog4 !=null){
        while($row2 = mysqli_fetch_array($displayLog4)){
            $state = $row2['state'];
            $count = $row2['count'];
            $state_data .=",['" . $state . "'," . $count ."]";
        }
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
    <style>
        #custom {
            display: none;
        }
    </style>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script>
        google.charts.load('current', {packages: ['corechart', 'bar']});
        google.charts.setOnLoadCallback(drawBarColors);

        function drawBarColors() {
            var data = google.visualization.arrayToDataTable([
                ['Page Type', 'Total Count']
                <?php
                echo $page_data;
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
    <script>
        google.charts.load('current', {packages: ['corechart', 'bar']});
        google.charts.setOnLoadCallback(drawBarColors);

        function drawBarColors() {
            var data = google.visualization.arrayToDataTable([
                ['City', 'Total Count']
                <?php
                echo $city_data;
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
            var chart = new google.visualization.BarChart(document.getElementById('chart_div1'));
            chart.draw(data, options);
        }
    </script>
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
<body style="background-color: white">
<section class="margin_dashboard">
    <div class="container">
        <div class="clearfix">

            <div class="col-lg-12 col-md-5 col-sm-12 col-xs-12">
                <div class="row margin_div1">
                    <div style="margin-bottom: 20px;">
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
                    </div>
                    <div class="body custom_card_padding">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs tab-nav-right" role="tablist">
                            <li role="presentation" class="active"><a class="custom_nav_tab" href="#state" data-toggle="tab"><i class="fa fa-flag"></i> State</a></li>
                            <li role="presentation"><a class="custom_nav_tab" href="#city" data-toggle="tab"><i class="fa fa-building"></i> City</a></li>
                            <li role="presentation"><a class="custom_nav_tab" href="#page" data-toggle="tab"><i class="fa fa-list"></i> Page</a></li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade in active" id="state">
                                <?php
                                if ($displayLog5 != null) {
                                ?>
                                    <div class="col-md-12 col-xs-12">
                                        <div id="visualization" style="overflow: auto;"> </div>
                                    </div>

                                    <?php
                                    }
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
                            <div role="tabpanel" class="tab-pane" id="city">
                                <?php
                                if ($displayLog3 != null) {
                                    ?>
                                    <div id="chart_div1"></div>
                                <?php
                                }
                                    $city_table = "<div class='body table-responsive table_scroll'>
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
                                    ?>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="page">
                                <?php

                                if ($displayLog != null) {
                                    ?>
                                    <div id="chart_div"></div>
                                <?php
                                }
                                ?>
                                <div>
                                    <?php
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
                                        $table .= "<tr><td colspan='2' class='text-center'>No data found!</td></tr>";
                                    }
                                    $table .= "</tbody></table></div>";


                                    echo $_SESSION['log_table'] = $table;
                                    ?>
                                </div>
                            </div>

                        </div>
                    </div>
                   <!-- <div class="width-prf">
                        <div class="form-group form-float drp_filter_border">
                            <div class="form-line">
                                <select name="gender" class="form-control" id="drp_filter"
                                        onchange="get_count(this.value)">
                                    <option value="today">Today</option>
                                    <option value="week">Last week</option>
                                    <option value="month">Last month</option>
                                    <option value="year">Last Year</option>
                                    <option value="life_time">Life time</option>
                                    <option value="custom">Custom</option>
                                </select>
                            </div>
                        </div>
                    </div>-->
                </div>
            </div>
        </div>


        <div id="custom">
            <div class="col-md-12">
                <div class="row">
                    <ul class='ul_search'>
                        <li class='ul_search_li'>
                            <div class='form-line'>
                                <label>From Date</label>
                                <input type='date' name='from_date' id="from_date" class='form-control'
                                       value=''>
                            </div>
                        </li>
                        <li class='ul_search_li'>
                            <div class='form-line'>
                                <label>To Date</label>
                                <input type='date' name='to_date' id="to_date" class='form-control'
                                       value=''>
                            </div>
                        </li>
                        <li class='ul_search_li'>
                            <div class='form-inline'>
                                <button type='button' name='search' onclick="get_search()" class='btn btn-primary'>
                                    Search
                                </button>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>


        <!--<div id="get_count"></div>-->
    </div>
</section>
<?php include "assets/common-includes/footer_includes.php" ?>
<script>
    var something = (function () {
        var executed = false;
        return function () {
            if (!executed) {
                executed = true;
                get_count("today");
            }
        };
    })();
    something();
    function get_count(val) { // Call to ajax function
        var dataString = "drp_filter=" + val + "&user_id=" +<?php if (isset($_GET['user_id'])) { $id = $security->decryptWebservice($_GET['user_id']); echo $id; }?>;
        if (val == "custom") {
            $("#custom").show();
            $("#custom").css("margin-bottom", "20px");
        } else {
            $("#custom").hide();
        }
        $.ajax({
            type: "POST",
            url: "get_counts.php", // Name of the php files
            data: dataString,
            success: function (html) {
                $("#get_count").html(html);
            }
        });
    }
    function get_search() {
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        var dataString = "from_date=" + from_date + "&to_date=" + to_date + "&user_id=" +<?php if (isset($_GET['user_id'])) { $id = $security->decryptWebservice($_GET['user_id']); echo $id; }?>;
        $.ajax({
            type: "POST",
            url: "get_counts.php", // Name of the php files
            data: dataString,
            success: function (html) {
                $("#get_count").html(html);
                return false
            }
        });
    }


</script>
</body>
</html>