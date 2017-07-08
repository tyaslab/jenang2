<?php

namespace Jenang2\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Jenang2\IoC\IoC;
use Jenang2\Controller\UnauthorizedController;


class CSRFTokenMiddleware {
    public function beforeResponse(Request $request) {
        $session = IoC::resolve('session');
        if ($request->getMethod() == 'GET') {
            $session->set('CSRF_TOKEN', md5(uniqid(rand(), TRUE)));
        } else {
            $csrf_token = $request->get('CSRF_TOKEN');
            $session_csrf_token = $session->get('CSRF_TOKEN');
            if ($csrf_token == NULL || $session_csrf_token != $csrf_token) {
                $controller = new UnauthorizedController($request);
                $controller->get()->send();
                exit;
            }
        }
    }

    public function afterResponse(Request $request, Response $response) {

    }
}
