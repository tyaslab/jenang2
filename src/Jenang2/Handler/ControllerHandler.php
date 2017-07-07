<?php

namespace Jenang2\Handler;

use Whoops\Handler\PrettyPageHandler;


class ControllerHandler extends PrettyPageHandler {
    public $exceptionController = array();

    public function generateResponse() {
        $exception = $this->getException();
        if (isset($exception->status_code)) {
            $status_code = $exception->status_code;

            $this->exceptionController[$status_code]->get()->send();
        }

        return parent::generateResponse();
    }
}
