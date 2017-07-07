<?php

namespace Jenang2\Handler;

use Symfony\Component\HttpFoundation\Response;

use Whoops\Handler\Handler;
use Whoops\Handler\PrettyPageHandler;

use Jenang2\Exception\HttpException;
use Jenang2\Handler\ProductionHandler;

class DebugHandler extends PrettyPageHandler {
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
