<?php

require("class.phpmailer.php");

class sendMailSystem
{
    function sendMail($toName, $toEmail, $fromName, $fromEmail, $subject, $message)
    {
        $mail = new PHPMailer;
        //$mail->SMTPDebug = 3;                               // Enable verbose debug output

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = MAIL_HOST;
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = MAIL_USERNAME;
        $mail->Password = MAIL_PASSWORD;
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = MAIL_PORT;                                    // TCP port to connect to
        //$mail->SMTPDebug = 2;

        $mail->setFrom($fromEmail, $fromName);
        $mail->addAddress($toEmail, $toName);                   // Add a recipient
        /*$mail->addAddress('ellen@example.com');               // Name is optional*/
        $mail->addReplyTo($fromEmail, $fromName);
        /*$mail->addCC('fahim@kubictechnology.com');*/
       /* $mail->addBCC('komal@kubictechnology.com,yasar@kubictechnology.com');*/

        /*
        $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        */
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        if (!$mail->send()){
            return false;
            /*
                        echo 'Message could not be sent.';
                        echo 'Mailer Error: ' . $mail->ErrorInfo;
            */

        } else {
            return true;
            //echo 'Message has been sent';
        }
    }

    function sendMailWithAttachment($toName, $toEmail, $fromName, $fromEmail, $subject, $message, $attachment)
    {
        $mail = new PHPMailer;
        //$mail->SMTPDebug = 3;                               // Enable verbose debug output

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = MAIL_HOST;
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = MAIL_USERNAME;
        $mail->Password = MAIL_PASSWORD;
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = MAIL_PORT;                                    // TCP port to connect to
        //$mail->SMTPDebug = 2;

        $mail->setFrom($fromEmail, $fromName);
        $mail->addAddress($toEmail, $toName);     // Add a recipient
        /*$mail->addAddress('ellen@example.com');               // Name is optional*/
        $mail->addReplyTo($fromEmail, $fromName);
        /*$mail->addCC('cc@example.com');
        $mail->addBCC('bcc@example.com');
        */

        $mail->addAttachment($attachment);         // Add attachments
        /*$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        */
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        if (!$mail->send()){
            return false;
            /*
                        echo 'Message could not be sent.';
                        echo 'Mailer Error: ' . $mail->ErrorInfo;
            */

        } else {
            return true;
            //echo 'Message has been sent';
        }
    }




}

?>
