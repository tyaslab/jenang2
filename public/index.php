<?php

require "../vendor/autoload.php";

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Core\RequestEvent;

$request = Request::createFromGlobals();

$app = new \Core\Jenang2();

$app->map('/', function() {
    return new Response('This is the home page');
});

$app->map('/about', function() {
    return new Response('This is the about page');
});

$app->map('/admin', function() {
    return new Response('This is the admin page');
});

$app->map('/hello/{name}', function($name) {
    return new Response('Hello ' . $name);
});

$app->on('request', function(RequestEvent $event) {
    if ('/admin' == $event->getRequest()->getPathInfo()) {
        echo 'Access Denied!';
        exit;
    }
});

$response = $app->handle($request);
$response->send();
