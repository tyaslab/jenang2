<?php

namespace Jenang2\Exception;

use Jenang2\Exception\HttpException;


class ServerErrorException extends HttpException {
    protected $status_code = 500;
}
