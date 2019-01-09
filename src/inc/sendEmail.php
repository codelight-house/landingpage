<?php

// Replace this with your own email address
$siteOwnersEmail = $_ENV['CONTACT_EMAIL'] ?? 'user@website.com';

function smtp_mail($to, $from, $message, $user, $pass, $host, $port)
{
	if ($h = fsockopen($host, $port))
	{
		$data = array(
			0,
			"EHLO $host",
			'AUTH LOGIN',
			base64_encode($user),
			base64_encode($pass),
			"MAIL FROM: <$from>",
			"RCPT TO: <$to>",
			'DATA',
			$message
		);
		foreach($data as $c)
		{
			$c && fwrite($h, "$c\r\n");
			while(substr(fgets($h, 256), 3, 1) != ' '){}
		}
		fwrite($h, "QUIT\r\n");
		return fclose($h);
	}
}

function testEmail($to, $from, $message, $subject) {
    ini_set('default_socket_timeout', 3);
    $user = $_ENV['SMTP_USER'];
    $pass = $_ENV['SMTP_PASS'];
    $host = $_ENV['SMTP_HOST'];
    $port = $_ENV['SMTP_PORT'];
    $template = "Subject: $subject\r\n"
        ."To: <$to>\r\n"
        ."From: $from\r\n"
        ."MIME-Version: 1.0\r\n"
        ."Content-Type: text/html; charset=utf-8\r\n"
        ."Content-Transfer-Encoding: base64\r\n\r\n"
        .base64_encode($message) . "\r\n.";
    if(smtp_mail($to, $from, $template, $user, $pass, $host, $port))
    {
        echo "Mail sent\n\n";
    }
    else
    {
        echo "Some error occured\n\n";
    }
}

if($_POST) {

    $name = trim(stripslashes($_POST['contactName']));
    $email = trim(stripslashes($_POST['contactEmail']));
    $subject = trim(stripslashes($_POST['contactSubject']));
    $contact_message = trim(stripslashes($_POST['contactMessage']));

    // Check Name
    if (strlen($name) < 2) {
        $error['name'] = "Please enter your name.";
    }
    // Check Email
    if (!preg_match('/^[a-z0-9&\'\.\-_\+]+@[a-z0-9\-]+\.([a-z0-9\-]+\.)*+[a-z]{2}/is', $email)) {
        $error['email'] = "Please enter a valid email address.";
    }
    // Check Message
    if (strlen($contact_message) < 15) {
        $error['message'] = "Please enter your message. It should have at least 15 characters.";
    }
    // Subject
    if ($subject == '') { $subject = "Contact Form Submission"; }


    // Set Message
    $message .= "Email from: " . $name . "<br />";
    $message .= "Email address: " . $email . "<br />";
    $message .= "Message: <br />";
    $message .= $contact_message;
    $message .= "<br /> ----- <br /> This email was sent from your site's contact form. <br />";

    // Set From: header
    $from =  $name . " <" . $email . ">";

    // Email Headers
    $headers = "From: " . $from . "\r\n";
    $headers .= "Reply-To: ". $email . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=utf-8\r\n";


    if (!$error) {

        ini_set("sendmail_from", $siteOwnersEmail); // for windows server
        $mail = mail($siteOwnersEmail, $subject, $message, $headers);

        if ($mail) { echo "OK"; }
        else { echo "Something went wrong. Please try again."; }

    } # end if - no validation error

    else {

        $response = (isset($error['name'])) ? $error['name'] . "<br /> \n" : null;
        $response .= (isset($error['email'])) ? $error['email'] . "<br /> \n" : null;
        $response .= (isset($error['message'])) ? $error['message'] . "<br />" : null;

        echo $response;

    } # end if - there was a validation error

}

