<?php
require_once("Control.class.php");

require_once("phpmailer/PHPMailerAutoload.php");

class Mailer {

    public function Mailer() {
        $this->control = Control::getControl();
    }

    public function sendBasicMail($from, $to, $subject, $body) {
        $headers = "From: " . $from . "\r\n";
        mail($to, $subject, $body, $headers);
    }

    public function createGenericMail() {
        $mail = new PHPMailer();
        $mail->isSMTP();

        return $mail;
    }

    public function createGmail($gmailUsername, $gmailPassword, $fromEmailAddress, $fromName, $toEmail, $subject, $body, $autoSend=FALSE) {
        $mail = $this->createGenericMail();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = $gmailUsername;                 // SMTP username
        $mail->Password = $gmailPassword;                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to

        $mail->From = $fromEmailAddress;
        $mail->FromName = $fromName;
//$mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
        $mail->addAddress($toEmail);               // Name is optional
        $mail->addReplyTo($fromEmailAddress, $fromName);
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = $subject;
        $mail->Body    = $body;
//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        if($autoSend) {
            $this->sendMail($mail);
        }
        return $mail;
    }

    public function sendMail($mail) {
        if(!$mail->send()) {
           // echo 'Message could not be sent.';
           // echo 'Mailer Error: ' . $mail->ErrorInfo;
            return FALSE;
        } else {
            return TRUE;
            //echo 'Message has been sent';
        }
    }


    /*
    $mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
    //Set the hostname of the mail server
$mail->Host = 'smtp.gmail.com';

//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
$mail->Port = 587;

//Set the encryption system to use - ssl (deprecated) or tls
$mail->SMTPSecure = 'tls';

//Whether to use SMTP authen

    $mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp1.example.com;smtp2.example.com';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'user@example.com';                 // SMTP username
$mail->Password = 'secret';                           // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;                                    // TCP port to connect to

$mail->From = 'from@example.com';
$mail->FromName = 'Mailer';
$mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
$mail->addAddress('ellen@example.com');               // Name is optional
$mail->addReplyTo('info@example.com', 'Information');
$mail->addCC('cc@example.com');
$mail->addBCC('bcc@example.com');

$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'Here is the subject';
$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}

*/

    /*
     * $to = "somebody@example.com";
$subject = "My subject";
$txt = "Hello world!";
$headers = "From: webmaster@example.com" . "\r\n" .
"CC: somebodyelse@example.com";

mail($to,$subject,$txt,$headers);
     */

    public $control;
}