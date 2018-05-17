<?php

require __DIR__ . '/vendor/autoload.php';

$config = [
    'settings' => [
        'displayErrorDetails' => true,

        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/app/logs/app.log',
        ],
    ],
];
$app = new \Slim\App($config);


require_once __DIR__ . '/app/routes.php';

$app->run();
