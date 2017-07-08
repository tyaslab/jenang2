<?php

require '../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Dotenv\Dotenv;

use Illuminate\Database\Capsule\Manager as Capsule;

use Jenang2\IoC\IoC;
use Jenang2\Event\MiddlewareBeforeResponseEvent;
use Jenang2\Event\MiddlewareAfterResponseEvent;

define('ROOT_DIR', dirname(__DIR__));

// session
$session = new Session();

if (!$session->isStarted()) {
    $session->start();
}

// load .env file
$dotenv = new Dotenv();
$dotenv->load(ROOT_DIR . '/.env');

// register session to ioc
IoC::register('session', function() use ($session) {
    return $session;
});

$request = Request::createFromGlobals();

// Mail Server
// Create the Transport
$transport = (new Swift_SmtpTransport('smtp.example.org', 25))
  ->setUsername('your username')
  ->setPassword('your password')
;

// Create the Mailer using your created Transport
$mailer = new Swift_Mailer($transport);

// Create a message
$message = (new Swift_Message('Wonderful Subject'))
  ->setFrom(['john@doe.com' => 'John Doe'])
  ->setTo(['receiver@domain.org', 'other@domain.org' => 'A name'])
  ->setBody('Here is the message itself');

// Send the message
// $result = $mailer->send($message);

// defining Whoops
$whoops = new Whoops\Run();

$development_mode = getenv('DEVELOPMENT_MODE');

if ($development_mode == 'debug') {
    // TODO: do you have better approach? :)
    $handler = new \Jenang2\Handler\DebugHandler();
} elseif ($development_mode == 'production') {
    $handler = new \Jenang2\Handler\ProductionHandler();
    $exceptionControllers = array(
        Response::HTTP_NOT_FOUND => new \Jenang2\Controller\NotFoundController($request),
        Response::HTTP_INTERNAL_SERVER_ERROR => new \Jenang2\Controller\ServerErrorController($request)
    );
    $handler->exceptionController = $exceptionControllers;
}

$whoops->pushHandler($handler);

// Set Whoops as the default error and exception handler used by PHP:
$whoops->register();

// DATABASE
$db_name = getenv('DB_NAME');

if ($db_name) {
    $db = [
        'driver'    => getenv('DB_DRIVER'),
        'host'      => $db_name,
        'database'  => getenv('DB_NAME'),  // your database name
        'username'  => getenv('DB_USERNAME'),  // your database username
        'password'  => getenv('DB_PASSWORD'),  // your database password
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => ''
    ];

    // INFO: uncomment to enable database
    $capsule = new Capsule;
    $capsule->addConnection($db, 'default');

    // Make this Capsule instance available globally via static methods... (optional)
    $capsule->setAsGlobal();

    // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
    $capsule->bootEloquent();
}

// use Core\RequestEvent;

$app = new \Jenang2\Jenang2();
require '../bootstrap/urls.php';
$app->map($urls);

require '../bootstrap/middlewares.php';
$app->on('beforeResponse', function(MiddlewareBeforeResponseEvent $event) use ($middlewares) {
    $request = $event->getRequest();

    foreach ($middlewares as $middleware) {
        $middleware_object = new $middleware();
        if (method_exists($middleware_object, 'beforeResponse')) {
            $middleware_object->beforeResponse($request);
        }
    }
});

$app->on('afterResponse', function(MiddlewareAfterResponseEvent $event) use ($middlewares) {
    $request = $event->getRequest();
    $response = $event->getResponse();

    foreach ($middlewares as $middleware) {
        $middleware_object = new $middleware();
        if (method_exists($middleware_object, 'afterResponse')) {
            $middleware_object->afterResponse($request, $response);
        }
    }
});

$response = $app->handle($request);
$response->send();
