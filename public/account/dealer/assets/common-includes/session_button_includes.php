<?php

if (isset($_POST['session_disable'])) {
    unset($_SESSION['create_user_status']);
    /*echo '<META HTTP-EQUIV="Refresh" Content="0; URL=' . $_SERVER['PHP_SELF'] . '">';*/
    $url = $_SERVER['PHP_SELF'];
    header('location:'.$url);
}?>


<div class="container-fluid">
    <div class="col-md-12">
        <div style="float: right;text-align: right">
            <form method="post" action="">
                <button class="btn btn-danger" name="session_disable" type="submit">complete user creation</button>
            </form>
        </div>
    </div>
</div>

<?php

ob_end_flush();
?>