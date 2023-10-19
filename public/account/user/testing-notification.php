<?php
// save in constant file
$api_key = "AAAAHMLbFk8:APA91bEwlgWphUe6g-NNerDYloGq5mAf0_dB71QzuDqGl9pazSjkzN9gMReIcR0y4Y9CnF_-JDfYQWaAYpHF8DKEsC8R-yITdfM5eVVcEteHf7g4yA98QjVTrf5g4chOGtK6oRwUS30I";

//get dynamically from user
$notification_token = "fWLHDXVINS0:APA91bG7pMIaHeS7sdJrlkrwS7P5WfHyPq3d9Is7K0kU2Yjpr5AEAT2RHJqKCKnLTljaStq2toPTZQyY8kizKyaXRrtiPX1oyAaQ8oLK8fnqAfvWoje3NiD61FPWBhWOmYP3zFVKSHAs";

$title = "this is title from api";

$message = "this is message from api";

//calling method
sendPushNotification($api_key,$notification_token,$title,$message);


function sendPushNotification($api_key,$notification_token,$title,$message){
    $url = 'https://kubictechnology.com/sendNotification.php';
    $myvars = 'api_key=' . $api_key . '&notification_token=' . $notification_token. '&title=' . $title. '&message=' . $message;

    $ch = curl_init( $url );
    curl_setopt( $ch, CURLOPT_POST, 1);
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $myvars);
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt( $ch, CURLOPT_HEADER, 0);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

    $response = curl_exec( $ch );
}