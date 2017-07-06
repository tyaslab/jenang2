<?php

namespace App\Controller;

use Jenang2\Controller\BaseController;

class HelloController extends BaseController {
    public function get() {
        return $this->response->setContent('Hola ' . $this->args['name']);
    }
}
