<?php

namespace Jenang2\Controller;

use Jenang2\Controller\TemplateController;


class DetailController extends TemplateController {
    protected $model = NULL;  // must be a class
    protected $key_arg = 'id';  // usually id
    protected $field = 'id';  // usually id

    protected $object = NULL;

    protected function getModel() {
        return $this->model;
    }

    protected function getObject() {
        if ($this->object) return $this->object;

        $model = $this->getModel();
        $object = $model::where($this->field, $this->args[$this->key_arg])->first();

        if (!$object) {
            throw new \Jamakati2\Exception\NotFoundException('Object not found');
        }

        $this->object = $object;

        return $object;
    }

    protected function getContextData($args=[]) {
        $context = parent::getContextData($args);
        $context['object'] = $this->getObject();

        return $context;
    }
}
