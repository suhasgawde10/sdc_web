<div class="row">
<?php
if ($getAllService != "") {
    $count = 0;
    while ($rowservice = mysqli_fetch_array($getAllService)) {

        $read_more = $rowservice['url'];
        $whatsapp_data = $rowservice['whatsapp_btn'];
        $call_us = $rowservice['call_btn'];
        ?>

            <div class="col-md-3 col-sm-6">
                <div class="product-grid">
                    <div class="product-image">
                        <a href="<?php echo $read_more; ?>">
                            <img class="pic-1" src="white-lable/panel/uploads/other-service-img/<?php echo $rowservice['img_name']; ?>">
                        </a>
                    </div>
                    <div class="product-content">
                        <h3 class="title">
                            <a href="<?php echo $read_more; ?>"><?php echo $rowservice['title']; ?></a>
                        </h3>

                        <div class="description">
                            <?php echo $rowservice['description']; ?>
                        </div>
                    </div>
                    <ul class="menu-btn">
                        <?php
                        if($call_us != 0){
                            ?>
                            <li class="d-lg-none d-xl-none d-md-block">
                                <a href="tel:91<?php echo $cotactnum; ?>" class="btn btn-info btn-sm">
                                    <i class="fa fa-phone-square" aria-hidden="true"></i>
                                    Call us</a>
                            </li>
                        <?php
                        }
                        ?>
                        <?php
                        if($whatsapp_data != 0){
                            ?>
                            <li>
                                <a target="_blank" href="https://api.whatsapp.com/send?phone=91<?php echo $whatsapp; ?>" class="btn btn-success btn-sm" style="background-color: #28a745;border-color: #28a745;">
                                    <i class="fa fa-whatsapp" aria-hidden="true"></i>
                                    Whatsapp</a>
                            </li>
                        <?php
                        }
                        ?>
                        <?php
                        if($read_more != ""){
                            ?>
                            <li>
                                <a target="_blank" href="<?php echo $read_more; ?>" class="btn btn-primary btn-sm" style="background: #007bff;border-color: #007bff;"><i class="fa fa-paper-plane" aria-hidden="true"></i> Read More</a>
                            </li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>

    <?php
        $count++;
        if (basename($_SERVER['PHP_SELF']) != "services.php" && $count == 4){
            break;
        }
    }
}
?>
</div>