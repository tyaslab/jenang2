<?php

namespace Jenang2\Validator;

use Jenang2\Validator\BaseValidator;

use Symfony\Component\HttpFoundation\File\UploadedFile;


class RequiredValidator extends BaseValidator {
    protected $message = "Field %s is required";

    protected function isReallyNull($value) {
        // Really null both for value or file
        // Yes! Now supports file!
        if ($value == null) return true;

        // now supports FILE!
        if ($value instanceof UploadedFile && (!$value->getClientSize() || !$value->getClientOriginalName())) return true;

        return false;
    }

    public function isValid($value, $instance=NULL) {
        if ($this->isReallyNull($value)) $this->raiseValidationError();

        return true;
    }
}
