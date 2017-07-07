<?php

use Symfony\Component\HttpFoundation\Response;


$urls = [
    '/' => 'App\Controller\HomeController',
    '/about' => function() {
        return new Response('This is the about page');
    },
    '/hello/{name}' => 'App\Controller\HelloController',
    '/admin' => function() {
        return new Response('This is the admin page');
    }
];
