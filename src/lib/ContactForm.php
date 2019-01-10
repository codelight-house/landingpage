<?php

namespace Codelight\Landingpage;

class ContactForm {
    /** @var \Maknz\Slack\Client */
    private $slackClient;
    public function __construct(\Maknz\Slack\Client $client) {
        $this->slackClient = $client;
    }

    public function send($fields): string {
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
        $message .= "Subject: *{$subject}*\n";
        $message .= "From: *{$name}*\n";
        $message .= "Email: *{$email}*\n";
        $message .= "Message:\n```{$contact_message}```";

        // Set From: header
        $from =  $name . " <" . $email . ">";

        // Email Headers
        $headers = "From: " . $from . "\r\n";
        $headers .= "Reply-To: ". $email . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=utf-8\r\n";


        if (!$error) {
            return ($this->sendToSlack($message)) ? "OK" : "Something went wrong. Please try again.";
        } # end if - no validation error

        else {

            $response = (isset($error['name'])) ? $error['name'] . "<br /> \n" : null;
            $response .= (isset($error['email'])) ? $error['email'] . "<br /> \n" : null;
            $response .= (isset($error['message'])) ? $error['message'] . "<br />" : null;

            return $response;

        } # end if - there was a validation error
    }

    private function sendToSlack($message) {
        $this->slackClient->attach([
            'fallback' => 'New contact form data',
            'text' => $message,
            'mrkdwn_in' => ['text']
        ])->send('New contact form data');
        return true;
    }

}