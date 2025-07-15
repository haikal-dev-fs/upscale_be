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
    return $router->app->version();
});

$router->post('/auth/register',  'AuthController@register');
$router->post('/auth/login',     'AuthController@login');

$router->group(['middleware' => 'auth.jwt'], function () use ($router) {
    $router->get('/auth/me',      'AuthController@me');
    $router->get('/tasks',        'TaskController@index');      // ?status=pending|done
    $router->post('/tasks',       'TaskController@store');
    $router->patch('/tasks/{id}', 'TaskController@update');
    $router->delete('/tasks/{id}','TaskController@destroy');

    $router->get('/quote',        'QuoteController@daily');
});
