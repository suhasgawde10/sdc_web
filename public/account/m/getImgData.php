<?php
require_once "../controller/ManageDesktopCard.php";
$manage = new ManageDesktopCard();
require_once "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
if(isset($_POST['page'])){
    // Include pagination library file
    include_once 'assets/common-includes/Pagination.php';
    $user_id = $_POST['user_id'];

    // Set some useful configuration
    $baseURL = 'getImgData.php';
    $offset = !empty($_POST['page'])?$_POST['page']:0;
    $limit = 21;

    $get_count = $manage->mdm_displayGalleryCount($user_id);

    // Initialize pagination class
    $pagConfig = array(
        'user_id' => $user_id,
        'baseURL' => $baseURL,
        'totalRows' => $get_count,
        'perPage' => $limit,
        'currentPage' => $offset,
        'contentDiv' => 'postContent'
    );
    $pagination =  new Pagination($pagConfig);

    // Fetch records based on the offset and limit
 //   $query = $db->query("SELECT * FROM posts ORDER BY id DESC LIMIT $offset,$limit");
    $get_image_result = $manage->mu_displayGalleryDetailsByLimit($user_id,$offset,$limit);
    $get_modal_result = $manage->mu_displayGalleryDetailsByLimit($user_id,$offset,$limit);
    $get_column_result = $manage->mu_displayGalleryDetailsByLimit($user_id,$offset,$limit);

?>

    <ul>
        <?php
        $count = 1;
        while ($result_image_data = mysqli_fetch_array($get_image_result)) {
            ?>
            <li>
                <div class="gallery-div">
                    <img src="<?php echo FULL_WEBSITE_URL."user/uploads/" . $result_image_data['email'] . "/images/" . $result_image_data['img_name']; ?>"
                         onclick="openModal();currentSlide(<?php echo $count; ?>)"
                         class="hover-shadow cursor img-cust-gall">
                </div>
            </li>
            <?php
            $count++;
        }
        ?>
    </ul>
    <div id="myModalImage" class="modal">
        <span class="close cursor cust-close-img-gall" onclick="closeModal()">&times;</span>

        <div class="modal-content model-img">
            <?php
            if ($get_modal_result != null) {
                $total = mysqli_num_rows($get_modal_result);
                $count = 1;
                while ($result_modal_data = mysqli_fetch_array($get_modal_result)) {
                    ?>

                    <div class="mySlides">
                        <div class="numbertext"><?php echo $count; ?> / <?php echo $total; ?></div>
                        <img
                                src="<?php echo FULL_WEBSITE_URL."user/uploads/" . $result_modal_data['email'] . "/images/" . $result_modal_data['img_name']; ?>"
                                style="width:100%">
                    </div>
                    <?php
                    $count++;
                }
            }
            ?>

            <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
            <a class="next" onclick="plusSlides(1)">&#10095;</a>

            <div class="caption-container">
                <p id="caption"></p>
            </div>

            <?php
            if ($get_column_result != null) {
                $count = 1;
                while ($result_column_data = mysqli_fetch_array($get_column_result)) {
                    ?>
                    <div class="column img-slider">
                        <img class="demo cursor" src="<?php echo FULL_WEBSITE_URL."user/uploads/" . $result_column_data['email'] . "/images/" . $result_column_data['img_name']; ?>"
                             style="width:100%"
                             onclick="currentSlide(<?php echo $count; ?>)" alt="<?php echo $result_column_data['image_name']; ?>">
                    </div>
                    <?php
                    $count++;
                }
            }?>


        </div>
    </div>
                                                    <!-- Display pagination links -->
                                                    <?php echo $pagination->createLinks();
}
?>