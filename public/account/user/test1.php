
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

</head>
<body>

<div class="col-md-12">
    <?php
    $from = 'sk3136537@gmail.com';
    $to = 'fahim@kubictechnology.in';
    $fromName = 'Fahim';

    $subject = "Send HTML Email in PHP by CodexWorld";

    $htmlContent = '
    <html>
    <head>
        <title>Welcome to Sharedigitalcard 2</title>
    </head>
    <body>
        <h1>Thanks you for joining with us!</h1>
        <table cellspacing="0" style="border: 2px dashed #FB4314; width: 100%;">
            <tr>
                <th>Name:</th><td>Sharedigitalcard</td>
            </tr>
            <tr style="background-color: #e0e0e0;">
                <th>Email:</th><td>contact@Sharedigitalcard.com</td>
            </tr>
            <tr>
                <th>Website:</th><td><a href="http://www.Sharedigitalcard.com">www.Sharedigitalcard.com</a></td>
            </tr>
        </table>
    </body>
    </html>';

    // Set content-type header for sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    // Additional headers
    $headers .= 'From: '.$fromName.'<'.$from.'>' . "\r\n";
   /* $headers .= 'Cc: welcome@example.com' . "\r\n";
    $headers .= 'Bcc: welcome2@example.com' . "\r\n";*/

    // Send email
    if(mail($to, $subject, $htmlContent)){
        echo 'Email has sent successfully.';
    }else{
        echo 'Email sending failed.';
    }
    ?>

</div>
</body>
</html>