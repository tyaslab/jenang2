<?php

namespace Jenang2\Controller;

use Symfony\Component\HttpFoundation\Response;
use Jenang2\Controller\TemplateController;


class NotFoundController extends TemplateController {
    protected $template_name = 'error/not_found';
    protected $status_code = Response::HTTP_NOT_FOUND;
}
