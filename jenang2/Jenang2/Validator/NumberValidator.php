<?php

namespace Jenang2\Validator;

use Jenang2\Validator\RegexValidator;


class NumberValidator extends RegexValidator {
    protected $pattern = "/^[0-9]+$/";
}