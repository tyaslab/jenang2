<?php

namespace Jenang2\Controller;

use Jenang2\Controller\BaseController;


class TemplateController extends BaseController {
    protected $template_name = null;

    protected function getTemplateName() {
        return $this->template_name;
    }

    public function get() {
        $context = $this->getContextData();
        return $this->renderToResponse($context);
    }

    protected function renderToResponse($context) {
        $base_context = $this->getContextData();
        $context = array_merge($base_context, $context);
        $template_name = $this->getTemplateName();

        return $this->render($template_name, $context);
    }
}
