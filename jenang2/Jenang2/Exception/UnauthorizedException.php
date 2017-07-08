<?php

namespace Jenang2\Exception;

use Jenang2\Exception\HttpException;


class UnauthorizedException extends HttpException {
    protected $status_code = 401;
}
