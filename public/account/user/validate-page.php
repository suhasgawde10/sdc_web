
<?php

$date = date("Y-m-d");

$userSpecificResult = $manage->displayUserSubscriptionDetails();
if(isset($_SESSION['type']) && $_SESSION['type'] !="Admin"){

    if($userSpecificResult!=null){

        $expiry_date = $userSpecificResult['expiry_date'];
        $plan_name = $userSpecificResult['year'];
        /*if($plan_name != 'Life Time'){
            if($expiry_date==""){
                header('location:basic-user-info.php');
            }elseif($expiry_date < $date){
                header('location:plan-selection.php');
            }
        }*/
        $referral_by = $userSpecificResult['referer_code'];
        $sell_ref = $userSpecificResult['sell_ref'];
        $user_country = $userSpecificResult['country'];
        if($sell_ref == ""){
            $sell_ref = "dealer_link";
        }
    }else{
        header('location:basic-user-info.php');
    }
}






?>