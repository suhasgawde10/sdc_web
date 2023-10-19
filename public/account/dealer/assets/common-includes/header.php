<?php

/*if (isset($_SESSION['id'])) {
    if (isset($_SESSION['create_user_status']) && $_SESSION['create_user_status'] == true) {
        $id = $_SESSION["create_user_id"];
    } else {
        $id = $_SESSION["id"];
    }
}
$date = date("Y-m-d");
$getExpiryDate = $manage->getUserExpiryDate($id);

if ($getExpiryDate != null) {
    $expiry_date = $getExpiryDate['expiry_date'];

}
$earlier = new DateTime("$date");
$later = new DateTime("$expiry_date");
$diff = $later->diff($earlier)->format("%a");
if ($diff == 0) {
    $diff = "0";
}

*/


$get_user_details = $manage->selectTheme();
if ($get_user_details != null) {
    $img_name = $get_user_details['img_name'];
    $gender = $get_user_details['gender'];
    $profilePath = "uploads/" . $_SESSION['dealer_email'] . "/profile/" . $get_user_details['img_name'];
}

if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
    $host_url = "https://";
else
    $host_url = "http://";
$host_url .= $_SERVER['HTTP_HOST'];
$host_url .= $_SERVER['REQUEST_URI'];
?>

<nav class="navbar">
    <div class="container-fluid">
        <div class="navbar-header">
            <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse"
               data-target="#navbar-collapse" aria-expanded="false"></a>
            <a href="javascript:void(0);" class="bars"></a>
            <?php
            if ($main_site) {
                ?>
                <a class="navbar-brand-logo visible-sm visible-md visible-lg visible-xs navbar-brand"
                   href="basic-user-info.php"><img src="assets/images/logo.png"></a>
            <?php
            }
            ?>
            <?php
            if (isset($_SESSION['create_user_status']) && $_SESSION['create_user_status'] == true) {
                ?>
                <div class="col-md-3 col-md-offset-5" style="position: absolute">
                    <a class="navbar-brand" href="#"><b><label class="label label-danger">Running in user creation
                                mode</label></b></a>
                </div>
            <?php
            }
            ?>
        </div>


        <div class="collapse navbar-collapse" id="navbar-collapse">

            <ul class="nav navbar-nav navbar-right">
                <!-- Call Search -->
                <!--<li>
                    <a href="../index.php" target="_blank">Home</a>
                </li>
                   <li>
                       <a href="../index.php" target="_blank">Pricing</a>
                   </li>-->
                <!--<li>
                    <a href="#" target="_self">Expiry day : <?php echo $diff; ?> </a>
                </li>-->
                <!-- #END# Call Search -->
                <!-- Notifications -->
                <!-- #END# Notifications -->
                <!-- Tasks -->
                <?php
                if (strpos($host_url, 'sharedigitalcard.com') == false) {
                    ?>
                    <!--<li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="modal"
                           data-target="#supportModal" role="button">
                            Need Help?
                        </a>
                    </li>-->
                <?php
                }
                ?>
                <!-- #END# Tasks -->
                <!--<li class="pull-right"><a href="javascript:void(0);" class="js-right-sidebar" data-close="true"><i
                            class="material-icons">account_circle</i></a></li>-->
            </ul>
        </div>
    </div>
</nav>
<!-- Bootstrap Modal -->
<div class="modal fade" id="planModal" tabindex="-1" role="dialog" aria-labelledby="planModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="planModalLabel">Change in Plan and Pricing</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table">
          <thead>
            <tr>
              <th>Plan</th>
              <th>Features</th>
              <th>Price</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Basic Plan</td>
              <td>Standard features</td>
              <td>$9.99/month</td>
            </tr>
            <tr>
              <td>Premium Plan</td>
              <td>Enhanced features</td>
              <td>$19.99/month</td>
            </tr>
            <tr>
              <td>Enterprise Plan</td>
              <td>Advanced features</td>
              <td>Contact us for pricing</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
