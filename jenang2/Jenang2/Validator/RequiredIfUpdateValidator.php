<?php

namespace Jenang2\Validator;

use Jenang2\Validator\RequiredValidator;


class RequiredIfUpdateValidator extends RequiredValidator {
    protected $message = "Field %s is required";

    public function isValid($value, $instance=NULL) {
        if (!$instance) return true;
        if ($this->isReallyNull($value)) $this->raiseValidationError();

        return true;
    }
}
