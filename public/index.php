<?php

require '../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Dotenv\Dotenv;

use Illuminate\Database\Capsule\Manager as Capsule;


define('ROOT_DIR', dirname(__DIR__));

// load .env file
$dotenv = new Dotenv();
$dotenv->load(ROOT_DIR . '/.env');

$request = Request::createFromGlobals();

// defining Whoops
$whoops = new Whoops\Run();

$development_mode = getenv('DEVELOPMENT_MODE');

if ($development_mode == 'debug') {
    $whoops->pushHandler(new Whoops\Handler\PrettyPageHandler());
} elseif ($development_mode == 'production') {
    $handler = new \Jenang2\Handler\ControllerHandler();
    $handler->exceptionController[Response::HTTP_NOT_FOUND] = new \Jenang2\Controller\NotFoundController($request);
    $handler->exceptionController[Response::HTTP_INTERNAL_SERVER_ERROR] = new \Jenang2\Controller\ServerErrorController($request);

    $whoops->pushHandler($handler);
}

// Set Whoops as the default error and exception handler used by PHP:
$whoops->register();

// DATABASE
$db_name = getenv('DB_NAME');

if ($db_name) {
    $db = [
        'driver'    => 'mysql',
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

throw new \Exception();

// use Core\RequestEvent;

$app = new \Jenang2\Jenang2();
require '../bootstrap/urls.php';
$app->map($urls);

// $app->on('request', function(RequestEvent $event) {
//     if ('/admin' == $event->getRequest()->getPathInfo()) {
//         echo 'Access Denied!';
//         exit;
//     }
// });

$response = $app->handle($request);
$response->send();
