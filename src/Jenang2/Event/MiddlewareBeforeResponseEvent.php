<?php

namespace Jenang2\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\Event;


class MiddlewareBeforeResponseEvent extends Event {
    protected $request;

    public function setRequest(Request $request) {
        $this->request = $request;
    }

    public function getRequest() {
        return $this->request;
    }
}
