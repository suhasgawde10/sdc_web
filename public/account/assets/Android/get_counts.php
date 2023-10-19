<?php

include "../controller/ManageUser.php";
$manage = new ManageUser();



if(isset($_POST['from_date']) && (isset($_POST['to_date']) &&(isset($_POST['user_id'])))){
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    $user_id = $_POST['user_id'];


    $displayLog = $manage->getSpecificCount($from_date, $to_date, $user_id);

    $getTotalCount = $manage->countTotalNumber($from_date, $to_date, $user_id);



    echo "<div class='clearfix'>";
    echo "<div class='col-lg-12 col-md-5 col-sm-12 col-xs-12'>";

    echo "<div class='row clearfix'><div class='col-lg-3 col-md-3 col-sm-6 col-xs-12 padding_count_zero'><a href='#'>";
    echo "<div class='info-box bg-cyan hover-expand-effect'><div class='icon'><i class='fas fa-users'></i></div>";
    echo "<div class='content'><div class='text'>Total Count:-</div>";
    echo "<div class='number'> $getTotalCount</div></div></div></a></div></div>";


    /* echo "<div class='col-lg-8 col-md-8 col-sm-8 col-xs-8'>";
     echo "<h4><strong>Total Count:-</strong></h4>";
     echo "</div>";
     echo "<div class='col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right' >";
     echo "<label class='label label-primary'>";
     echo $getTotalCount;
     echo "</label>";*/
    echo "</div></div></div></div>";
    /*      $custom_search = "
   <form method='post' action=''>
      <ul class='ul_search'>
                                  <li class='ul_search_li'>
                                      <div class='form-line'>
                                          <label>From Date</label>
                                          <input type='date' name='from_date' class='form-control'
                                                 value=''>
              </div>
              </li>
              <li class='ul_search_li'>
                  <div class='form-line'>
                      <label>To Date</label>
                      <input type='date' name='to_date' class='form-control'
                             value=''>
                  </div>
              </li>
              <li class='ul_search_li'>
                  <div class='form-inline'>
                      <button type='submit' name='search' class='btn btn-primary'>Search</button>
                  </div>
              </li>
              </ul></form>";*/
    echo "<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>";
    echo "<div class='row'>";
    echo "<div class='card count_card'>";
    echo "<div class='body table-responsive table_scroll'>";
    echo "<table class='table table-condensed table-bordered table-striped'>";
    echo "<thead>";
    echo "<tr class='back-color'><th>Page Type</th><th>Count</th></tr>";
    echo "</thead><tbody>";
    if ($displayLog != null) {
        while ($result_data = mysqli_fetch_array($displayLog)) {
            $page_type = $result_data['page_type'];
            $removeExtension = pathinfo($page_type)['filename'];
            $newPageType = str_replace('index', 'profile', $removeExtension);
            echo "<tr>
                                        <td>$newPageType</td>
                                        <td>";
            echo $result_data['count'];
            echo "</td>
                                    </tr>";


        }
    } else {
        echo "
                                <tr>
                                    <td colspan='10' class='text-center'>No data found!</td>
                                </tr>";

    }
    echo "</tbody></table></div></div></div></div></div>";


}
$today_date = date("Y-m-d");
if (isset($_POST['drp_filter'])) {
    $drp_filter = $_POST['drp_filter'];
    if ($drp_filter != '') {

        /* if (isset($_GET['user_id'])) {*/
        $user_id = $_POST['user_id'];

        switch ($drp_filter) {
            case "today";
                $final_date = date("Y-m-d");
                break;
            case "week";
                $date = date_create("$today_date");
                date_add($date, date_interval_create_from_date_string("-7days"));
                $final_date = date_format($date, "Y-m-d");
                break;
            case "month";
                $date = date_create("$today_date");
                date_add($date, date_interval_create_from_date_string("-30days"));
                $final_date = date_format($date, "Y-m-d");
                break;
            case "year";
                $date = date_create("$today_date");
                date_add($date, date_interval_create_from_date_string("-365days"));
                $final_date = date_format($date, "Y-m-d");
                break;
            case "life_time";
                $final_date = "";
                $today_date = "";
                break;
            /*case "custom";
                if(isset($_POST['from_date']) && (isset($_POST['to_date']))) {
                    $today_date = $_POST['from_date'];
                    $final_date = $_POST['to_date'];
                }
                break;*/
            default:
                $final_date = date("Y-m-d");

        }
        $displayLog = $manage->getSpecificCount($final_date,$today_date, $user_id);

        $getTotalCount = $manage->countTotalNumber($final_date,$today_date, $user_id);



        echo "<div class='clearfix'>";
        echo "<div class='col-lg-12 col-md-5 col-sm-12 col-xs-12'>";

        echo "<div class='row clearfix'><div class='col-lg-3 col-md-3 col-sm-6 col-xs-12 padding_count_zero'><a href='#'>";
        echo "<div class='info-box bg-cyan hover-expand-effect'><div class='icon'><i class='fas fa-users'></i></div>";
        echo "<div class='content'><div class='text'>Total Count:-</div>";
        echo "<div class='number'> $getTotalCount</div></div></div></a></div></div>";


        /* echo "<div class='col-lg-8 col-md-8 col-sm-8 col-xs-8'>";
         echo "<h4><strong>Total Count:-</strong></h4>";
         echo "</div>";
         echo "<div class='col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right' >";
         echo "<label class='label label-primary'>";
         echo $getTotalCount;
         echo "</label>";*/
        echo "</div></div></div></div>";
        /*      $custom_search = "
       <form method='post' action=''>
          <ul class='ul_search'>
                                      <li class='ul_search_li'>
                                          <div class='form-line'>
                                              <label>From Date</label>
                                              <input type='date' name='from_date' class='form-control'
                                                     value=''>
                  </div>
                  </li>
                  <li class='ul_search_li'>
                      <div class='form-line'>
                          <label>To Date</label>
                          <input type='date' name='to_date' class='form-control'
                                 value=''>
                      </div>
                  </li>
                  <li class='ul_search_li'>
                      <div class='form-inline'>
                          <button type='submit' name='search' class='btn btn-primary'>Search</button>
                      </div>
                  </li>
                  </ul></form>";*/
        echo "<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>";
        echo "<div class='row'>";
        echo "<div class='card count_card'>";
        echo "<div class='body table-responsive table_scroll'>";
        echo "<table class='table table-condensed table-bordered table-striped'>";
        echo "<thead>";
        echo "<tr class='back-color'><th>Page Type</th><th>Count</th></tr>";
        echo "</thead><tbody>";
        if ($displayLog != null) {
            while ($result_data = mysqli_fetch_array($displayLog)) {
                $page_type = $result_data['page_type'];
                $removeExtension = pathinfo($page_type)['filename'];
                $newPageType = str_replace('index', 'profile', $removeExtension);
                echo "<tr>
                                        <td>$newPageType</td>
                                        <td>";
                echo $result_data['count'];
                echo "</td>
                                    </tr>";


            }
        } else {
            echo "
                                <tr>
                                    <td colspan='10' class='text-center'>No data found!</td>
                                </tr>";

        }
        echo "</tbody></table></div></div></div></div></div>";

    }

}




/*if(isset(){

}*/

?>