<?php

$baseName = basename($_SERVER['PHP_SELF']);

$animate_check = false;
$animate_for_service = false;
$animate_for_gallery = false;
$animate_for_client = false;
$animate_for_our_team = false;

$visited_for_bank = false;
$visited_for_service = false;
$visited_for_gallery = false;
$visited_for_client = false;
$visited_for_our_team = false;




$animate_plus = "animated infinite pulse";
$active_visited = "active visited";

if($baseName=='basic-user-info.php'){
    $active_visited = "";
    $animate_check = true;
}elseif($baseName=='service.php'){
    $visited_for_bank = true;
    $animate_for_service = true;
}elseif($baseName=='gallery.php'){
    $visited_for_bank = true;
    $animate_for_gallery = true;
    $visited_for_service = true;
}elseif($baseName=='video_gallery.php'){
    $visited_for_bank = true;
    $animate_for_gallery = true;
    $visited_for_service = true;
}elseif($baseName=='testimonial.php'){

    $visited_for_service = true;
    $visited_for_bank = true;
    $visited_for_gallery = true;
    $animate_for_client = true;


}elseif($baseName=='clients_review.php'){
    $visited_for_service = true;
    $visited_for_bank = true;
    $visited_for_gallery = true;
    $animate_for_client = true;

}elseif($baseName=='our-team.php'){

    $animate_for_our_team = true;
    $visited_for_client = true;
    $visited_for_service = true;
    $visited_for_bank = true;
    $visited_for_gallery = true;

}elseif($baseName=='payment.php'){
    $visited_for_client = true;
    $visited_for_service = true;
    $visited_for_bank = true;
    $visited_for_gallery = true;
    $visited_for_our_team = true;
}


?>

<div>
    <main>
        <div class="page-content" id="applyPage">
            <ul class="breadcrumbs">
                <li class="tab-link breadcrumb-item <?php echo "active visited"; ?> ">
                    <a href="basic-user-info.php">
                        <span class="number"><i class="fas fa-user"></i></span>
                        <span class="label"><?php echo $_SESSION['menu']['s_profile']; ?></span>
                    </a>
                </li>

                <li class="tab-link breadcrumb-item <?php if($animate_check){ echo $animate_plus." "; } if($visited_for_bank){ echo " ".$active_visited; } ?>" id="crumb5">
                    <a href="service.php">
                        <span class="number"><i class="far fa-list-alt"></i></span>
                        <span class="label"><?php echo  $_SESSION['menu']['s_services']; ?> / <?php echo  $_SESSION['menu']['s_products']; ?></span>
                    </a>
                </li>
                <li class="tab-link breadcrumb-item <?php if($animate_for_service){ echo $animate_plus." "; } if($visited_for_service){ echo " ".$active_visited;}  ?>">
                    <a href="gallery.php">
                        <span class="number"><i class="fas fa-images"></i></span>
                        <span class="label"><?php echo  $_SESSION['menu']['s_gallery']; ?></span>
                    </a>
                </li>
                <li class="tab-link breadcrumb-item <?php if($animate_for_gallery){ echo $animate_plus." "; } if($visited_for_gallery){ echo " ".$active_visited; }  ?>">
                    <a href="testimonial.php">
                        <span class="number"><i class="fas fa-poll"></i></span>
                        <span class="label"><?php echo  $_SESSION['menu']['s_clients']; ?></span>
                    </a>
                </li>
                <li class="tab-link breadcrumb-item <?php if($animate_for_client){ echo $animate_plus." "; } if($visited_for_client){ echo " ".$active_visited; } ?>">
                    <a href="our-team.php">
                        <span class="number"><i class="fas fa-users"></i></span>
                        <span class="label"><?php echo $_SESSION['menu']['s_team']; ?></span>
                        <!--<span><a href="manage-section.php"><i class="fas edit_menu_bar fa-pencil-alt"></i></a></span>-->

                    </a>
                </li>

                    <li class="tab-link breadcrumb-item <?php if ($animate_for_our_team) {
                        echo $animate_plus . " ";
                    }
                    if ($visited_for_our_team) {
                        echo " " . $active_visited;
                    } ?>">
                        <a href="payment.php">
                            <span class="number"><i class="fas fa-money-bill-alt"></i></span>
                            <span
                                class="label"><?php echo $_SESSION['menu']['s_bank']; ?></span>
                            <a href="manage-section.php"><span id="edit_menu_bar" class="number"><i
                                        class="fas fa-pencil-alt"></i></span></a>
                        </a>
                    </li>
            </ul>
        </div>
        <div class="progress-bar">
            <div class="progress" style="width:1%; background-position:1%;"><?php echo $_SESSION['total_percent'] ?>% Profile Completed</div>
        </div>
    </main>
</div>