<?php

namespace Jenang2\Validator;

use Jenang2\Validator\RegexValidator;


class DecimalValidator extends RegexValidator {
    protected $pattern = "/^[0-9]+(\.[0-9]+)?$/";
}
