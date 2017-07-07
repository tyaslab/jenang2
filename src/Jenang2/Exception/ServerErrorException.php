<?php

namespace Jenang2\Exception;


class ServerErrorException extends \Exception {
    public $status_code = 500;
}
