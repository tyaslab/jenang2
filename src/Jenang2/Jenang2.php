<?php

namespace Jenang2;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

// use Symfony\Component\EventDispatcher\EventDispatcher;

// use Jenang2\Event\RequestEvent;

use Jenang2\Controller\BaseController;


class Jenang2 implements HttpKernelInterface {
    protected $routes;

    public function __construct() {
        $this->routes = new RouteCollection();
        // $this->dispatcher = new EventDispatcher();
    }

    public function handle(Request $request, $type=HttpKernelInterface::MASTER_REQUEST, $catch=true) {
        // $event = new RequestEvent();
        // $event->setRequest($request);

        // $this->dispatcher->dispatch('request', $event);

        $context = new RequestContext();
        $context->fromRequest($request);

        $matcher = new UrlMatcher($this->routes, $context);

        try {
            $path_info = $request->getPathInfo();

            $base_url = getenv('BASE_URL');

            // remove trailing slash
            if ($base_url[count($base_url) - 1] == '/') {
                $base_url = substr($base_url, 0, count($base_url) - 2);
            }

            if ($path_info == $base_url) {
                $path_info = '/';
            } elseif ($base_url && substr($path_info, 0, count($base_url)) == $base_url) {
                $path_info = substr($path_info, count($base_url));
            }

            // when path info returns none, it should be /
            if ($path_info == '') $path_info = '/';

            $attributes = $matcher->match($path_info);
            $controller = $attributes['controller'];
            unset($attributes['controller']);
            unset($attributes['_route']);  // I don't need this btw :)

            // TODO: do it better!
            if (gettype($controller) == 'string') {
                // if a Controller
                $response = $controller::dispatch($request, $attributes);
            } else {
                // if a Closure
                $response = call_user_func_array($controller, $attributes);
            }

        } catch (ResourceNotFoundException $e) {
            // TODO: error handler
            throw new \Jenang2\Exception\NotFoundException('Page \'' . $request->getPathInfo() . '\' not found');
        }

        return $response;
    }

    public function map() {
        $args = func_get_args();

        if (func_num_args() == 2) {
            $args = [$args];
        } else {
            $args = $args[0];
        }

        foreach ($args as $url => $controller) {
            $this->routes->add($url, new Route(
                $url,
                array('controller' => $controller)
            ));
        }

    }

    // public function on($event, $callback) {
    //     $this->dispatcher->addListener($event, $callback);
    // }

    // public function fire($event) {
    //     return $this->dispatcher->dispatch($event);
    // }
}
