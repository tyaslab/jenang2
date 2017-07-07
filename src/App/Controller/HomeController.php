<?php

namespace App\Controller;

use Jenang2\Controller\TemplateController;

class HomeController extends TemplateController {
    protected $template_name = 'home';

    public function get() {
        return $this->renderToResponse(['world' => 'dunya']);
    }
}
