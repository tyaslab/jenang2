<?php

namespace Jenang2\Exception;


class NotFoundException extends \Exception {
    public $status_code = 404;
}
