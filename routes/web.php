<?php

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
    return $router->app->version();
});

$router->group(['prefix' => 'v1'], function ($api) {

    $api->post('login', ['uses' => 'AuthController@authenticate']);

    $api->group(['middleware' => 'auth-jwt'], function ($api) {

        // User routes
        $api->get('users', ['uses' => 'UserController@index']);
        $api->get('users/{id}', ['uses' => 'UserController@show']);
        $api->post('users', ['uses' => 'UserController@store']);
        $api->put('users/{id}', ['uses' => 'UserController@update']);
        $api->delete('users/{id}', ['uses' => 'UserController@destroy']);

    });

});
