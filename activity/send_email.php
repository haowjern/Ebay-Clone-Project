<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    include 'mail/src/Exception.php';
    include 'mail/src/PHPMailer.php';
    include 'mail/src/SMTP.php';

function send_to_email($email_address, $subject, $body, $altbody, $emailee_name) {

    $mail = new PHPMailer(true);
    try {

        //Server settings
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->SMTPDebug = 3;                                 // Enable verbose debug output
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->SMTPSecure = 'ssl';       //'tls' or 'ssl'     // Enable TLS encryption, `ssl` also accepted
        $mail->Host = 'smtp.gmail.com';                      // Specify main and backup SMTP servers
        $mail->Port = 465;               //587 or 465         // TCP port to connect to
        $mail->Username = 'group10ebaydatabaseproject@gmail.com';                   // SMTP username
        $mail->Password = '321SUperDarrenkO';                           // SMTP password
 
        //Recipients
        $mail->setFrom('group10ebaydatabaseproject@gmail.com', 'Group10EbayProject');
        $mail->addAddress($email_address, $emailee_name);     // Add a recipient
        //$mail->addAddress('ellen@example.com');               // Name is optional
        //$mail->addReplyTo('info@example.com', 'Information');
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');
        
        //Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $subject; //'Here is the subject';
        $mail->Body    = $body;    //'This is the HTML message body <b>in bold!</b>';
        $mail->AltBody = $altbody; //'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }

    }
?>