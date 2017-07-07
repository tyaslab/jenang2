<?php

namespace Jenang2\Handler;

use Whoops\Handler\PlainTextHandler;
use Jenang2\Exception\HttpException;

use Symfony\Component\HttpFoundation\Response;

class ProductionHandler extends PlainTextHandler {
    public function generateResponse() {
        $exception = $this->getException();

        $result = '';
        if ($exception instanceof HttpException) {
            $result = $this->exceptionController[$exception->getStatusCode()]->get()->getContent();
        }

        return $result;
    }

    public function handle() {
        $exception = $this->getException();

        if ($exception instanceof HttpException) {
            $response = new Response();
            $response->setStatusCode($exception->getStatusCode());
            $response->sendHeaders();
        }

        return parent::handle();
    }
}
