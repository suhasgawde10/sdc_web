<?php
$error = false;
$errorMessage = "";
include "controller/ManageAdminApp.php";
$manage = new ManageAdminApp();
include "controller/validator.php";
$validate = new Validator();
include "controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
$controller = new Controller();
$con = $controller->connect();

if(isset($_SESSION["email"])){
if (isset($_SESSION["type"]) && $_SESSION["type"]!="admin") {
    header('location:about-us.php?edit_id='.$security->encrypt($_SESSION["id"]));
}
}
else{
    header('location:index.php');
}




$getAllDealerCount = $manage->getDealer();

if($getAllDealerCount != ""){
    $getAllDealerCount = mysqli_num_rows($getAllDealerCount);
}else{
    $getAllDealerCount = 0;
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Dashboard </title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="assets/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bbootstrap 4 -->
    <link rel="stylesheet" href="assets/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="assets/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="assets/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="assets/dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="assets/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="assets/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="assets/summernote/summernote-bs4.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <link href="assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css"
          integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>
    <link rel="shortcut icon" href="../assets/img/logo/Logo-01.jpg" type="image/x-icon"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style type="text/css">
        #chart-container {
            width: 100%;
            height: auto;
        }
    </style>
    <style>
        #contain {
            height: 100px;
            overflow-y: scroll;
        }

        #table_scroll {
            width: 100%;
            margin-top: 10px;
            margin-bottom: 10px;
            border-collapse: collapse;
        }

        .demo1 {
            padding: 0px;
            margin: 0px;
            list-style: none;
        }

        .demo1 .news-item {
            padding: 4px 4px;
            margin: 0px;
            border-bottom: 1px dotted #555;
        }

        .comp-card:hover i {
            border-radius: 50%;
        }

        .comp-card i {
            color: #fff;
            width: 50px;
            height: 50px;
            border-radius: 5px;
            text-align: center;
            padding: 17px 0;
            font-size: 18px;
            text-shadow: 0 6px 8px rgba(62, 57, 107, .18);
            -webkit-transition: all .3s ease-in-out;
            transition: all .3s ease-in-out;
        }

        .bg-c-yellow {
            background: #ffb64d;
        }

        .more_info {
            color: #000000;
        }

        .more_info:hover {
            color: #000000;
        }

        .more_info i {
            color: #000000 !important;
        }

        .holiday_color {
            color: #212529;
        }

    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">

<div class="wrapper">
    <?php include 'assets/common-includes/header.php' ?>

    <?php include 'assets/common-includes/left_menu.php' ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <!--<div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Dashboard</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>-->
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="card">
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tabs-1" role="tabpanel">
                                <div class="row">
                                    <!-- ./col -->
                                    <div class="col-lg-3 col-3">
                                        <div class="small-box bg-danger">
                                            <div class="inner">
                                                <h3><?php echo $getAllDealerCount ?></h3>

                                                <p>Total Dealer</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-list-alt" aria-hidden="true"></i>
                                            </div>
                                            <a href="manage-dealer.php" class="small-box-footer">More info <i
                                                    class="fas fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                    <!--<div class="col-lg-3 col-3">
                                        <div class="small-box bg-primary">
                                            <div class="inner">
                                                <h3>13</h3>

                                                <p>Total </p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-list-alt" aria-hidden="true"></i>
                                            </div>
                                            <a href="manage-category.php" class="small-box-footer">More info <i
                                                    class="fas fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-3">
                                        <div class="small-box bg-success">
                                            <div class="inner">
                                                <h3>4</h3>

                                                <p>Total</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-list-alt" aria-hidden="true"></i>
                                            </div>
                                            <a href="manage-category.php" class="small-box-footer">More info <i
                                                    class="fas fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>-->
                                    <!-- ./col -->
                                    <!--<div class="col-lg-3 col-3">
                                        <div class="small-box bg-warning">
                                            <div class="inner">
                                                <h3>10</h3>

                                                <p>Total</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-list-alt" aria-hidden="true"></i>
                                            </div>
                                            <a href="manage-product.php" class="small-box-footer">More info <i
                                                    class="fas fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>-->
                                    <!-- ./col -->
                                </div>
                            </div>
                            <div class="tab-pane" id="tabs-2" role="tabpanel">
                                <div id="chart-container">
                                    <canvas id="graphCanvas"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    </div>


    <!-- /.row -->

</div>
<!-- /.container-fluid -->
</section>
<!-- /.content -->
</div>
<script>
    $(document).ready(function () {
        showGraph();
    });


    function showGraph() {
        {
            $.post("data.php",
                function (data) {
                    console.log(data);
                    var name = [];
                    var date = [];

                    for (var i in data) {
                        name.push(data[i].Grand);
                        date.push(data[i].Month_Name);
                    }
                    var chartdata = {
                        labels: date,
                        datasets: [
                            {
                                label: 'Income Statics',
                                backgroundColor: '#007bff',
                                borderColor: '#46d5f1',
                                hoverBackgroundColor: '#CCCCCC',
                                hoverBorderColor: '#666666',
                                data: name
                            }
                        ]
                    };

                    var graphTarget = $("#graphCanvas");

                    var barGraph = new Chart(graphTarget, {
                        type: 'bar',
                        data: chartdata
                    });
                });
        }
    }
</script>
<script>
    var my_time;
    $(document).ready(function () {
        pageScroll();
        $("#contain").mouseover(function () {
            clearTimeout(my_time);
        }).mouseout(function () {
            pageScroll();
        });
    });

    function pageScroll() {
        var objDiv = document.getElementById("contain");
        objDiv.scrollTop = objDiv.scrollTop + 1;
        if (objDiv.scrollTop == (objDiv.scrollHeight - 100)) {
            objDiv.scrollTop = 0;
        }
        my_time = setTimeout('pageScroll()', 25);
    }
</script>
<?php include 'assets/common-includes/footer.php'; ?>
</div>
<!-- ./wrapper -->
<?php include 'assets/common-includes/footer_includes.php'; ?>


</body>
</html>
