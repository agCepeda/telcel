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

$app->get('/sale', function ($request, $response, $args) {
    $salesJson = file_get_contents(__DIR__ . '/fake/sales.json');
    
    return $response->withStatus(200)
        ->withHeader('Content-Type', 'application/json')
        ->write($salesJson);
});

$app->post('/sale', function ($request, $response, $args) {
    $salesJson = file_get_contents(__DIR__ . '/fake/sales.json');

    $params = $request->getParams();

    $salesArray = json_decode($salesJson);

    array_push($salesArray, $params);

    $salesJson = file_put_contents(__DIR__ . '/fake/sales.json', json_encode($salesArray));
    
    return $response->withStatus(200)
        ->write(json_encode($params));
});

$app->get('/promotion', function ($request, $response, $args) {
    $promotionsJson = file_get_contents(__DIR__ . '/fake/promotions.json');
    
    return $response->withStatus(200)
        ->withHeader('Content-Type', 'application/json')
        ->write($promotionsJson);
});

$app->post('/promotion', function ($request, $response, $args) {
    $promotionsJson = file_get_contents(__DIR__ . '/fake/promotions.json');

    $params = $request->getParams();

    $promotionsArray = json_decode($promotionsJson);

    array_push($promotionsArray, $params);

    file_put_contents(__DIR__ . '/fake/promotions.json', json_encode($promotionsArray));
    
    return $response->withStatus(200)
        ->write(json_encode($params));
});
