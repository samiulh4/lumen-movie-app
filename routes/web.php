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

header('Access-Control-Allow-Headers: *');


$router->get('/', function () use ($router) {
    return '<h1 style="text-align: center;color: red;">Lumen Movie App Running...</h1>';
});

$router->post('api/sign-up', 'AuthenticationController@sign_up');
$router->post('api/sign-in', 'AuthenticationController@sign_in');
$router->get('api/token-refresh', 'AuthenticationController@token_refresh');
$router->get('api/movie/get-web-movies', 'MovieController@getWebMovies');
$router->get('api/movie/get-movie-view-data-by-Id/{id}', 'MovieController@getMovieViewDataById');
$router->get('api/data/get-countries', 'DataController@getCountries');
$router->get('api/data/get-languages', 'DataController@getLanguages');

$router->group(['middleware' => 'jwt.auth'], function ($router) {
    $router->get('api/sign-in-user', 'AuthenticationController@sign_in_user');
    $router->get('api/sign-out', 'AuthenticationController@sign_out');
    $router->get('api/user/user-data-by-Id/{id}', 'UserController@getUserDataById');
    $router->post('api/user/update/{id}', 'UserController@update');
    $router->post('api/movie/store', 'MovieController@store');
    $router->post('api/movie/update/{id}', 'MovieController@update');
});

