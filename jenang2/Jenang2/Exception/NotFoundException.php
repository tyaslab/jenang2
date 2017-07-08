<?php

namespace Jenang2\Exception;

use Jenang2\Exception\HttpException;


class NotFoundException extends HttpException {
    protected $status_code = 404;
}
