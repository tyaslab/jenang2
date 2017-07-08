<?php

namespace Jenang2\Controller;

use Symfony\Component\HttpFoundation\Response;
use Jenang2\Controller\TemplateController;


class UnauthorizedController extends TemplateController {
    protected $template_name = 'error/unauthorized';
    protected $status_code = Response::HTTP_UNAUTHORIZED;
}
