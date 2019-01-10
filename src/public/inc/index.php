<?php
require __DIR__.'/../../vendor/autoload.php';

use Codelight\Landingpage\ContactForm;

$dotenv = Dotenv\Dotenv::create(__DIR__.'/../../');
$dotenv->load();

$container = new \Slim\Container();
$container[ContactForm::class] = function($container) {
    return new ContactForm($container[\Maknz\Slack\Client::class]);
};
$container[\Maknz\Slack\Client::class] = function($container) {
    return new \Maknz\Slack\Client($_ENV['SLACK_URL']);
};

$app = new \Slim\App($container);

$app->post('/send-email', function ($request, $response, $args) {
    $body = $request->getParsedBody();
    $contactForm = $this->get(ContactForm::class);
    $message = $contactForm->send($body);
    return $response->write($message);
});

$app->run();