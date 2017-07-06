<?php

namespace App\Controller;

use Jenang2\Controller\BaseController;

class HomeController extends BaseController {
    public function get() {
        return $this->response->setContent('Hola');
    }
}
