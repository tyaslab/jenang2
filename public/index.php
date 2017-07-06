<?php

require '../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Dotenv\Dotenv;

use Illuminate\Database\Capsule\Manager as Capsule;

define('ROOT_DIR', dirname(__DIR__));

$dotenv = new Dotenv();
$dotenv->load(ROOT_DIR . '/.env');



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

// use Core\RequestEvent;

$request = Request::createFromGlobals();

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
