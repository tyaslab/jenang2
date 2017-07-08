<?php

namespace Jenang2\Form;


// FORM SET HAS NO INSTANCE IN FORM CONSTRUCTOR
class FormSet {
    public $forms;
    public $errors = [];

    public function __construct($forms=[]) {
        $this->forms = $forms;
    }

    public function addForm($form) {
        array_push($this->forms, $form);
    }

    // this context will be injected to all form members
    public function setContext($key, $context) {
        foreach ($this->forms as $form) {
            $form->setContext($key, $context);
        }
    }

    public function addFormList($formClass, $data_list, $args=[]) {
        foreach ($data_list as $data_item) {
            $this->addForm(new $formClass($data_item, $args));
        }
    }

    public function isValid() {
        $this->errors = [];
        $has_error = FALSE;
        foreach ($this->forms as $form) {
            if ($form->isDelete()) continue;
            if ($form->isValid()) {
                $errors = [];
            } else {
                $errors = $form->errors;
                if (!$has_error) $has_error = TRUE;
            }

            array_push($this->errors, $errors);
        }

        return !$has_error;
    }

    public function save() {
        // default implementation
        foreach ($this->forms as $form) {
            if (!$form->isDelete()) {
                $form->save();
            }
        }
    }

    public function delete() {
        // default implementation
        foreach ($this->forms as $form) {
            if ($form->isDelete()) {
                $form->delete();
            }
        }

        $this->forms = array_filter($this->forms, function($item) {
            return !$item->isDelete();
        });
    }
}
