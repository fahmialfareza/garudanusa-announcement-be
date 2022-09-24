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
$router->group(['prefix' => '/api/v1'], function () use ($router) {
    $router->group(['prefix' => '/auth'], function () use ($router) {
        $router->post('/register', 'AuthController@register');
        $router->post('/login', 'AuthController@login');

        $router->group(['middleware' => 'auth'], function () use ($router) {
            $router->get('/users', 'AuthController@getAllUsers');
            $router->get('/me', 'AuthController@me');
            $router->delete('/delete/{id}', 'AuthController@deleteUser');
        });
    });

    $router->group(['prefix' => '/announcement'], function () use ($router) {
        $router->get('/phone/{phone}', 'AnnouncementController@resultByPhoneNumber');

        $router->group(['middleware' => 'auth'], function () use ($router) {
            $router->get('/', 'AnnouncementController@getAllAnnouncements');
            $router->post('/import', 'AnnouncementController@importFromExcel');
            $router->delete('/delete', 'AnnouncementController@deleteAllAnnouncement');
        });
    });

    $router->group(['prefix' => '/countdown'], function () use ($router) {
        $router->get('/', 'CountdownController@getCountdown');

        $router->group(['middleware' => 'auth'], function () use ($router) {
            $router->post('/', 'CountdownController@setCountdown');
        });
    });
});