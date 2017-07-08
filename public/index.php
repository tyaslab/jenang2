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

// $app->on('request', function(RequestEvent $event) {
//     if ('/admin' == $event->getRequest()->getPathInfo()) {
//         echo 'Access Denied!';
//         exit;
//     }
// });

$response = $app->handle($request);
$response->send();
