<?php

namespace Jenang2\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParser;
use Symfony\Component\Templating\Loader\FilesystemLoader;


class BaseController {
    // protected $c;
    private $templates;

    protected $request;
    protected $response;
    protected $user;
    protected $session;
    protected $args = [];

    protected $allowed_methods = ['GET', 'POST'];

    public function __construct(Request $request, $args=[]) {
        $this->request = $request;
        $this->response = new Response();
        $this->args = $args;
    }

    public function render($template_path, $data=[], $layout=NULL) {
        $loader = new FilesystemLoader(ROOT_DIR . '/src/App/View/%name%.php');
        $templating = new PhpEngine(new TemplateNameParser(), $loader);
        $content = $templating->render($template_path, $data);
        if (!$layout) {
            echo $content;
        } else {
            $data['pageContent'] = $content;
            echo $templating->render($layout, $data);
        }
    }

    protected function getContextData($args=[]) {
        $data = [
            'controller' => $this,
            'request' => $this->request,
            'args' => $this->args,
            'method' => $this->request->getMethod()
        ];

        $data = array_merge($data, $args);

        return $data;
    }

    public static function dispatch($request, $args=[]) {
        $class = get_called_class();
        $that = new $class($request, $args);
        $allowed_methods = array_filter($that->allowed_methods, "strtoupper");
        $method = $request->getMethod();
        $method_function = strtolower($method);

        if (!in_array($method, $allowed_methods)) {
            return $that->response->setStatusCode(Response::HTTP_NOT_IMPLEMENTED);
        }

        if (!method_exists($that, $method_function))
            throw new \Exception("Class method $method_function not implemented");

        return call_user_func_array([$that, strtolower($method)], []);
    }
}
