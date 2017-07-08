<?php

namespace Jenang2\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\EventDispatcher\Event;


class MiddlewareAfterResponseEvent extends Event {
    protected $request;
    protected $response;

    public function setRequest(Request $request) {
        $this->request = $request;
    }

    public function getRequest() {
        return $this->request;
    }

    public function setResponse(Response $response) {
        $this->response = $response;
    }

    public function getResponse() {
        return $this->response;
    }
}
