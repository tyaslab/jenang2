<?php

namespace Jenang2\Validator;

use Illuminate\Support\Arr;


abstract class BaseValidator {
    protected $field_name;
    protected $message = "Invalid input for field %s";
    protected $args = [];

    public function __construct($field_name, $args=[]) {
        $defaut_args = [
            'message' => null
        ];

        $args = array_merge($defaut_args, $args);

        $this->field_name = $field_name;

        if (isset($args['message'])) {
            $this->message = $args['message'];
            unset($args['message']);
        }

        $this->args = $args;
    }

    public function isValid($value, $instance=NULL) {
        throw new \Exception("Method isValid() not implemented");
    }

    protected function raiseValidationError() {
        throw new \Jenang2\ExceptionValidationException(sprintf($this->message, $this->field_name));
    }
}
