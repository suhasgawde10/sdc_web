<?php
$get_notify = $manage->total_Notification_count();
$notification_count = $get_notify['notification_count'];
 $pending_lead_count = $get_notify['lead_count'];

$total_count = $notification_count + $pending_lead_count;

if($total_count > 0){
echo  '<title class="title_count">('.$total_count.') Notification</title>';
}
?>

