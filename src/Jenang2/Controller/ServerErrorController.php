<?php

namespace Jenang2\Controller;

use Symfony\Component\HttpFoundation\Response;
use Jenang2\Controller\TemplateController;


class ServerErrorController extends TemplateController {
    protected $template_name = 'error/server_error';

    public function get() {
        return parent::get()->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
