<?php

require __DIR__.'/../../vendor/autoload.php';

// Create and configure Slim app
$config = ['settings' => [
    'addContentLengthHeader' => false,
]];
$app = new \Slim\App($config);

// Define app routes
$app->post('/send-email', function ($request, $response, $args) {
    $body = $request->getParsedBody();
    $contactForm = new \Codelight\Landingpage\ContactForm();
    $message = $contactForm->send($body);
    return $response->write($message);
});

// Run app
$app->run();