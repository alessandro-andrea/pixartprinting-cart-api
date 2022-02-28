<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return env('APP_NAME') . ' ' . env('APP_VERSION');
});

$router->group(['prefix' => 'v1'], function () use ($router) {
    $router->get('cart/{ecommerce_id}/{customer_id}', ['uses' => 'CartController@showOne']);
    $router->post('cart', ['uses' => 'CartController@create']);
    $router->put('cart/{ecommerce_id}/{customer_id}', ['uses' => 'CartController@update']);
    $router->patch('cart/{ecommerce_id}/{customer_id}', ['uses' => 'CartController@checkout']);
    $router->delete('cart/{ecommerce_id}/{customer_id}', ['uses' => 'CartController@delete']);
});

$router->addRoute(['GET','POST', 'PUT', 'PATCH', 'DELETE','OPTIONS'], '', function() {
    return env('APP_NAME') . ' ' . env('APP_VERSION');
});
