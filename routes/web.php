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
        $router->get('/{id}', 'AnnouncementController@getAnnouncement');

        $router->group(['middleware' => 'auth'], function () use ($router) {
            $router->get('/', 'AnnouncementController@getAllAnnouncements');
            $router->post('/import', 'AnnouncementController@importFromExcel');
            $router->put('/update/{id}', 'AnnouncementController@updateAnnouncement');
            $router->delete('/delete', 'AnnouncementController@deleteAllAnnouncement');
        });
    });

    $router->group(['prefix' => '/event'], function () use ($router) {
        $router->get('/', 'EventController@getEvent');

        $router->group(['middleware' => 'auth'], function () use ($router) {
            $router->post('/', 'EventController@setEvent');
        });
    });

    $router->group(['prefix' => 'status'], function () use ($router) {
        $router->group(['middleware' => 'auth'], function () use ($router) {
            $router->get('/', 'StatusController@getAllStatuses');
            $router->get('/{id}', 'StatusController@getStatus');
            $router->post("/", "StatusController@createStatus");
            $router->put("/{id}", "StatusController@updateStatus");
            $router->delete("/{id}", "StatusController@destroyStatus");
        });
    });
});