<?php
if (isset($_SESSION["type"]) != 'dealer'){
?>
<nav class="main-header remove-header navbar navbar-expand navbar-white navbar-light">
    <?php
    }else{
    ?>
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <?php

        if ($_SESSION["type"] == 'admin') {
            if ($_GET['edit_id']) {
                $idss = $security->decrypt($_GET['edit_id']);
                $getDealername = $manage->getDealerByIdEdit($idss);
                if ($getDealername != "") {
                    ?>
                    <p style="font-weight: 700;font-size: 18px;letter-spacing: 0.5"><?php echo $getDealername['customer_name'] . " - " . $getDealername['company_name']; ?></p>
                <?php
                }
            }
        }
        }
        ?>
        <?php

        ?>

        <!-- Navbar -->

        <!-- Left navbar links -->

        <?php
        if (isset($_SESSION["type"]) != 'dealer') {
            ?>
            <ul class="navbar-nav width_60">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="index.php" class="nav-link">Home</a>
                </li>
            </ul>
        <?php
        }
        ?>
        <ul class="navbar-nav ml-auto pull-right">
            <!-- User Account: style can be found in dropdown.less -->
            <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <img src="assets/dist/img/avatar.png" class="user-image" alt="User Image">
                    <?php echo $_SESSION['name'] ?>
                </a>
                <ul class="dropdown-menu">
                    <!-- User image -->
                    <li class="user-header">
                        <img src="assets/dist/img/avatar.png" class="img-circle" alt="User Image">

                        <p>
                            <?php echo $_SESSION['type'] ?> - <?php echo $_SESSION['email'] ?>
                            <!--                        <small>Member since Nov. 2012</small>-->
                        </p>
                    </li>
                    <!-- Menu Footer-->
                    <li class="user-footer">
                        <div class="pull-left">
                            <a href="reset-password.php" class="btn btn-default btn-flat">Reset Password</a>
                            <a href="sign-out.php" class="btn btn-default btn-flat">Sign out</a>
                        </div>
                    </li>
                </ul>
            </li>
            <!-- Control Sidebar Toggle Button -->
            <!--
            <li>
                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
            </li>
            -->
        </ul>
        <!-- Right navbar links -->

    </nav>
    <!-- /.navbar -->
