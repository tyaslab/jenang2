<?php

namespace Jenang2\Form;

use Illuminate\Support\Arr;

abstract class BaseForm {
    protected $fields = [];

    protected $data = [];
    protected $files = [];

    public $instance;
    public $context = [];

    public $cleaned_data = [];
    public $errors = [];

    public function __construct($data=[], $args=[]) {
        $this->data = $data;

        $default_args = [
            'instance' => NULL,
            'context' => [],
            'files' => [],
            'deleted_field' => 'deleted'
        ];

        $args = array_merge($default_args, $args);

        $this->instance = $args['instance'];
        $this->context = $args['context'];
        $this->files = $args['files'];
        $this->deleted_field = $args['deleted_field'];

        $this->setFields();
    }

    public function addError($field, $error_message) {
        if (Arr::has($this->errors, $field)) {
            array_push($this->errors[$field], $error_message);
        } else {
            $this->errors[$field] = [$error_message];
        }
    }

    public function setContext($key, $context) {
        $this->context[$key] = $context;
    }

    public function setInstance($instance) {
        $this->instance = $instance;
    }
    
    public function getValue($field) {
        if (Arr::has($this->data, $field)) {
            return $this->data[$field];
        } elseif (Arr::has($this->files, $field)) {
            return $this->files[$field];
        }

        return NULL;
    }

    public function getErrors($field) {
        return Arr::get($this->errors, $field, []);
    }

    public function isValid() {
        $this->errors = [];

        foreach($this->fields as $field => $validators) {
            if (
                !$this->instance ||
                (
                    $this->instance && (
                        Arr::has($this->data, $field) || Arr::has($this->files, $field)
                    )
                )
            ) { # TODO: DRY
                $has_error = false;
                foreach ($validators as $validator) {
                    try {
                        $validator->isValid($this->getValue($field), $this->instance);
                    } catch (\Jenang2\Exception\ValidationException $ve) {
                        $this->addError($field, $ve->getMessage());
                        if (!$has_error) $has_error = true;
                    }
                }
                if (!$has_error) $this->cleaned_data[$field] = $this->getValue($field);
            }
        }
        
        return !$this->errors;
    }

    public function isDelete() {
        return boolval($this->getValue($this->deleted_field));
    }

    protected function setFields() {
        throw new \Jenang2\Exception\FormException("setFields method not implemented");
    }

    public function save() {
        throw new \Jenang2\Exception\FormException("save method not implemented");
    }

    public function delete() {
        throw new \Jenang2\Exception\FormException("delete method not implemented");
    }
}
