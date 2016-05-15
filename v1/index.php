<?php

error_reporting(-1);
ini_set('display_errors', 'On');

//require_once '../include/db_handler.php';
require '.././libs/Slim/Slim.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

/* Test script to check slim is running properly */
$app->get('/', function() use($app) {
    $app->response->setStatus(200);
    echo "Welcome to JiffyJob API";
});

$app->post('/notification/send', function() use ($app) {
    require '../notification/SendNotificationExternal.php';

    // reading post params
    $content = $app->request->post('content');

    if (!empty($content)) {
        $sendExt = new SendNotificationExternal();
        $sendExt->send($content);                
    }
});

$app->run();
?>