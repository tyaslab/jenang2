<?php

namespace Jenang2\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Jenang2\IoC\IoC;

class BaseController {
    // protected $c;
    private $templates;

    protected $request;
    protected $response;
    protected $user;
    protected $args = [];

    protected $twig;

    protected $allowed_methods = ['GET', 'POST'];
    protected $status_code = Response::HTTP_OK;

    public function __construct(Request $request, $args=[]) {
        $this->request = $request;
        $this->response = new Response();
        $this->args = $args;
        $this->session = IoC::resolve('session');

        $loader = new \Twig_Loader_Filesystem(ROOT_DIR . '/src/App/View');
        $twig_cache_dir = false;

        if (getenv('DEVELOPMENT_MODE') == 'production') {
            $twig_cache_dir = ROOT_DIR . '/cache';
        }

        $this->twig = new \Twig_Environment($loader, array(
            'cache' => $twig_cache_dir
        ));
    }

    public function render($template_path, $data=[]) {
        $this->response = $this->response->setContent($this->twig->render($template_path . '.html', $data));
        $this->response = $this->response->setStatusCode($this->status_code);

        return $this->response;
    }

    protected function getContextData($args=[]) {
        $data = [
            'controller' => $this,
            'request' => $this->request,
            'args' => $this->args,
            'method' => $this->request->getMethod(),
            'session' => $this->session
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
            throw new \Jenang2\Exception\NotImplementedException("Class method $method_function not implemented");

        return call_user_func_array([$that, strtolower($method)], []);
    }
}
