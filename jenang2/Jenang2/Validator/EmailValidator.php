<?php

namespace Jenang2\Validator;

use Jenang2\Validator\RegexValidator;


class EmailValidator extends RegexValidator {
    // From http://stackoverflow.com/questions/13447539/php-preg-match-with-email-validation-issue
    protected $pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/";
}
