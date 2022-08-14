<?php

use PHPMailer\PHPMailer\PHPMailer;

require_once "PHPMailer/Exception.php";
require_once "PHPMailer/PHPMailer.php";
require_once "PHPMailer/SMTP.php";

$mail = new PHPMailer;

//$mail->SMTPDebug = 3;                               // Enable verbose debug output
$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'zineaonline@gmail.com';                 // SMTP username
$mail->Password = 'iyyexqowgayadqrd';                           // SMTP password
$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 465;                             // TCP port to connect to
//$mail->Host = 'smtp1.example.com;smtp2.example.com';// Specify main and backup SMTP servers

//Properties
//$mail->addAddress('ellen@example.com');               // Name is optional
//$mail->addReplyTo('info@example.com', 'Information');
//$mail->addCC('cc@example.com');
//$mail->addBCC('bcc@example.com');
//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
// SendEmail("test", "anoopkrishna157@gmail.com", "Test");
// SendEmail("test", "anoop@inkers.in", "Test");

function SendEmail($subject, $to, $htmlMessage)
{
     global $mail;
     $filteresultr = new \stdClass();
     $result = new \stdClass();
     $result->status = false;
     $result->errorMsg = "Some errror occured";

     $mail->From = 'noreply@zinea.in';
     $mail->FromName = 'KL-CSC-VLE-SOCIETY';
     $mail->addAddress($to);     // Add a recipient
     $mail->WordWrap = 50;                              // Set word wrap to 50 characters
     $mail->isHTML(true);                               // Set email format to HTML

     $mail->Subject = $subject;
     $mail->Body = $htmlMessage;
     $mail->AltBody = 'Some error occured';
     $result->mailDetails = $mail;

     if (!$mail->send()) {
          $result->errorMsg = 'Failed to send email to ' . $to;
     } else {
          $result->successMsg =  'Email has sent successfully.';
          $result->status = true;
          $result->errorMsg = null;
     }

     return $result;
}


function SendEmail_Old($subject, $to, $htmlContent)
{
     $result = new \stdClass();
     $result->status = false;
     $result->errorMsg = "Some errror occured";

     $from = 'noreply@zinea.in';
     $fromName = 'Zinea';

     // Set content-type header for sending HTML email 
     $headers = "MIME-Version: 1.0" . "\r\n";
     $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

     // Additional headers 
     $headers .= 'From: ' . $fromName . '<' . $from . '>' . "\r\n";
     //$headers .= 'Cc: welcome@example.com' . "\r\n";
     //$headers .= 'Bcc: welcome2@example.com' . "\r\n";

     // Send email 
     if (mail($to, $subject, $htmlContent, $headers)) {
          $result->successMsg =  'Email has sent successfully.';
          $result->status = true;
          $result->errorMsg = null;
     } else {
          $result->errorMsg = 'Failed to send email to ' . $to;
     }

     return $result;
}

function AccountApproved($password)
{
     $htmlContent = ' 
    <html> 
    <head> 
    </head> 
    <body> 
        <p>Your account has been approved. You can login using temporary password.</p>
        <h1>' . $password . '</h1> 
        <p>Thanks</p>
    </body> 
    </html>';
     return $htmlContent;
}

function TemporaryPassword($password)
{
     $htmlContent = ' 
    <html> 
    <head> 
    </head> 
    <body> 
        <p>Temporary password has been generated for your account.</p>
        <h1>' . $password . '</h1> 
        <p>Thanks</p>
    </body> 
    </html>';
     return $htmlContent;
}

