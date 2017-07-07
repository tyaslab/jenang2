<?php

namespace Jenang2\Exception;


class HttpException extends \Exception {
    protected $status_code = 500;

    public function getStatusCode() {
        return $this->status_code;
    }
}
