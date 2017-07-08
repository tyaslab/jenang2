<?php

namespace Jenang\Validator;

use Jenang2\Validator\BaseValidator;
use Illuminate\Database\Capsule\Manager as Capsule;


class UniqueValidator extends BaseValidator {
    protected $message = "Please choose another %s";

    # Arg 1: db_field_name = "db_table.db_field"
    # Arg 2: include_null = FALSE

    // TODO: how about file ???

    public function __construct($field_name, $args=[]) {
        parent::__construct($field_name, $args);
        if (!isset($args['db_field_name'])) throw new \Exception("db_field_name shuld be determined!");
    }

    public function isValid($value, $instance=NULL) {
        if (isset($this->args['include_null'])) {
            $include_null = $this->args['include_null'];
        } else {
            $include_null = FALSE;
        }

        $db_field_name = explode('.', $this->args['db_field_name']);

        if (!$include_null && $value == null) return true;

        // not different compared to origin
        if ($instance && $instance->{$db_field_name[1]} == $value)
            return true;

        $exist = Capsule::table($db_field_name[0])->where($db_field_name[1], $value)->count();

        if ($exist > 0)
            $this->raiseValidationError();

        return true;
    }
}
