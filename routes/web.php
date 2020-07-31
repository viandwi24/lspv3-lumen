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

use App\Helpers\Version;

$router->group(['prefix' => 'v1'], function () use ($router) {
    $router->get('/', function () use ($router) {
        $app = app('router')->app->version();
        preg_match("/\Lumen.*?\)/", $app, $lumen_version);
        preg_match("/\Laravel.*?\)/", $app, $component_version);

        return [
            'name' => env('APP_NAME', 'LSP APP'),
            'institute' => 'SMKN 1 MOJOKERTO',
            'version' => (new Version)->format(),
            'api_version' => env('API_VERSION', 1),
            'lumen' => str_replace(['(', ')', 'Lumen', ' '], '', $lumen_version[0]),
            'laravel_components' => str_replace(['(', ')', 'Laravel Components', ' '], '', $component_version[0])
        ];
    });

    $router->group(['prefix' => 'auth'], function() use ($router) {
        $router->get('/', ['as' => 'auth.profile', 'uses' => 'AuthController@profile']);
        $router->post('/login', ['as' => 'auth.login', 'uses' => 'AuthController@login']);
        $router->post('/logout', ['as' => 'auth.logout', 'uses' => 'AuthController@logout']);
        $router->post('/register', ['as' => 'auth.register', 'uses' => 'AuthController@register']);;
        $router->post('/forgot-password', ['as' => 'auth.forgot-password', 'uses' => 'AuthController@forgot_password']);
        $router->get('/user', ['as' => 'auth.user', 'uses' => 'AuthController@user', 'middleware' => 'auth']);
    });
});
