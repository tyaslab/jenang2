<?php

namespace Jenang2\Validator;

use Jenang2\Validator\BaseValidator;


class RegexValidator extends BaseValidator {
    protected $pattern;

    public function isValid($value, $instance=NULL) {
        if ($value == null) return true;
        
        $matched = false;
        
        if ($value != null) {
            $matched = preg_match($this->pattern, $value);
        }

        if (!$matched) $this->raiseValidationError();

        return true;
    }
}
