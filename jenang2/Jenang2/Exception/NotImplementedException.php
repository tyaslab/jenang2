<?php

namespace Jenang2\Exception;

use Jenang2\Exception\HttpException;


class NotImplementedException extends HttpException {
    protected $status_code = 501;
}
