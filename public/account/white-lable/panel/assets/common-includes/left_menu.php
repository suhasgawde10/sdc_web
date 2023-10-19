<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <?php
        if($_SESSION["type"] === "admin"){
            ?>
            <span class="">Admin</span>
        <?php
        }else{
            echo $_SESSION['name'];
        }
        ?>

    </a>

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <?php
                if($_SESSION["type"] === "admin"){
                ?>
                    <li class="nav-item">
                        <a href="dashboard.php"
                           class="<?php if (basename($_SERVER['PHP_SELF']) == "dashboard.php") {
                               echo "nav-link active";
                           } else {
                               echo "nav-link";
                           } ?>" >
                            <i class="nav-icon fas fa-tachometer-alt"></i>

                            <p>
                                Dashboard
                            </p>
                        </a>
                    </li>
                    <li class="nav-item has-treeview ">
                        <a href="#" class="<?php if (basename($_SERVER['PHP_SELF']) == "add-dealer.php" || basename($_SERVER['PHP_SELF']) == "manage-dealer.php") {
                            echo "nav-link active";
                        } else {
                            echo "nav-link";
                        } ?>">
                            <i class="nav-icon fa fa-product-hunt"></i>

                            <p>
                                Manage Dealer Website
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item ">
                                <a href="add-dealer.php" class="nav-link">
                                    <i class="fas fa-chevron-circle-right nav-icon"></i>

                                    <p>Add Dealer</p>
                                </a>
                            </li>
                            <li class="nav-item ">
                                <a href="manage-dealer.php" class="nav-link">
                                    <i class="fas fa-chevron-circle-right nav-icon"></i>

                                    <p>Manage Dealer</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php
                }else{
                ?>
                    <li class="nav-item">
                        <a href="about-us.php?edit_id=<?php echo $security->encrypt($_SESSION["id"]); ?>"
                           class="<?php if (basename($_SERVER['PHP_SELF']) == "about-us.php") {
                               echo "nav-link active";
                           } else {
                               echo "nav-link";
                           } ?>" >
                            <i class="fas fa-chevron-circle-right nav-icon"></i>
                            <p>Manage Dealer</p>
                        </a>
                    </li>
                <?php
                }
                ?>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>