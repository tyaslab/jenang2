<?php

namespace Jenang2\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Jenang2\IoC\IoC;
use Jenang2\Controller\UnauthorizedController;


class CSRFTokenMiddleware {
    public function beforeResponse(Request $request) {
        $session = IoC::resolve('session');
        $csrf_token_key = getenv('CSRF_TOKEN_KEY');
        if ($request->getMethod() == 'GET') {
            $session->set($csrf_token_key, md5(uniqid(rand(), TRUE)));
        } else {
            $csrf_token = $request->get($csrf_token_key);
            $session_csrf_token = $session->get($csrf_token_key);
            if ($csrf_token == NULL || $session_csrf_token != $csrf_token) {
                throw new \Jenang2\Exception\UnauthorizedException('CSRF Token failed!');
            }
        }
    }

    public function afterResponse(Request $request, Response $response) {

    }
}
