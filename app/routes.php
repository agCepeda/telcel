<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");

    return $response;
});

$app->group('/auth', function() {
    $this->post('/login', function ($request, $response, $args) {
        sleep(1);

        $usernames = ['j.r.saenz', 'j.maussan', 'c.trejo'];

        $username = $request->getParam('username', null);
        $password = $request->getParam('password', null);

        if (in_array($username, $usernames)) {
            if ($password == '12345') {
                switch ($username) {
                    case "j.r.saenz":
                        $fakeResponse = require __DIR__ . '/fake/login_role_ejecutivo.php';
                        break;
                    case "j.maussan":
                        $fakeResponse = require __DIR__ . '/fake/login_role_distribuidor.php';
                        break;
                    case "c.trejo":
                        $fakeResponse = require __DIR__ . '/fake/login_role_promotor.php';
                        break;
                }
                
                return $response->withStatus(200)
                    ->withHeader('Content-Type', 'application/json')
                    ->write(json_encode($fakeResponse));
            } else {
                $fakeResponse = require __DIR__.'/fake/login_error_2.php';
            }
        } else {
            $fakeResponse = require __DIR__.'/fake/login_error_1.php';
        }
        
        return $response->withStatus(401)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode($fakeResponse));
    });
});

$app->get('/retail-chain/{retailChain}/store', function ($request, $response, $args) {
    $retailChain = $args['retailChain'];

    $fakeResponse = [];
    if ($retailChain == "1") {
        $fakeResponse = require __DIR__ . '/fake/store_1.php';
    }
    if ($retailChain == "3") {
        $fakeResponse = require __DIR__ . '/fake/store_3.php';
    }
    if ($retailChain == "2") {
        $fakeResponse = require __DIR__ . '/fake/store_2.php';
    }

    return $response->withStatus(200)
        ->withHeader('Content-Type', 'application/json')
        ->write(json_encode($fakeResponse));
});


$app->get('/store/{storeId}', function ($request, $response, $args) {
    $storeId = $args['storeId'];

    sleep(2);

    return $response->withStatus(200)
        ->withHeader('Content-Type', 'application/json')
        ->write("");
});