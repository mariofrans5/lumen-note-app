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

/* $router->get('/', function () use ($router) {
    echo "test";
}); */

// API route group
$router->group(['prefix' => 'api'], function () use ($router) {

    $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login');
    
    $router->group(['middleware' => 'auth_jwt'], function () use ($router) {
        $router->group(['prefix' => 'note'], function () use ($router) {
            $router->get('/','NoteController@index');
            $router->get('/{id}','NoteController@show');
            $router->post('/','NoteController@store');
            $router->put('/{id}','NoteController@update');
            $router->delete('/{id}','NoteController@destroy');
        });
     
    });
});
