<?php
/**
 * This example shows settings to use when sending via Google's Gmail servers.
 */
//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
date_default_timezone_set('Asia/Bangkok');
require 'PHPMailer/PHPMailerAutoload.php';

if(isset($_POST['DATA']))
{
    $data = $_POST['DATA'];
    $DATA = json_decode(str_replace ('\"','"', $data), true);
    $email = $DATA['email'];
    $user = $DATA['UserName'];
    $pass = $DATA['Password'];
}else{

}

// build message body
$body = '
<html>
<body>
<br>
___________________________________________________________________<br>
UserName: '.$user.'<br>
Password: '.$pass.'<br>
<br>
Thanks,<br>
____________________________________________________________________<br>
</body>
</html>
';

$mail = new PHPMailer;
$mail->CharSet = "UTF-8";
$mail->isSMTP();
$mail->SMTPDebug = 2;
$mail->Debugoutput = 'html';
$mail->Host = 'smtp.live.com';
$mail->Port = 587;
$mail->SMTPSecure = 'tls';
$mail->SMTPAuth = true;
$mail->Username = "poseintelligence@hotmail.com";
$mail->Password = "P6o6s2e8";
$mail->setFrom('poseintelligence@hotmail.com', 'First Last');
$mail->addAddress($email, 'Kanakron Dechnithichanon');
$mail->Subject = 'Send password...';
$mail->msgHTML($body);

$mail->AltBody = 'This is a plain-text message body';
//$mail->addAttachment('images/phpmailer_mini.png');
if (!$mail->send()) {
    $return['msg'] = "Mailer Error: " . $mail->ErrorInfo;
    echo json_encode($return);
    die;
} else {
    $return['msg'] = "Message sent!";
    echo json_encode($return);
    die;
}

?>