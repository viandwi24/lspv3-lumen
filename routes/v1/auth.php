<?php

$router->post('/login', ['as' => 'auth.login', 'uses' => 'AuthController@login']);
$router->post('/logout', ['as' => 'auth.logout', 'uses' => 'AuthController@logout']);
$router->post('/register', ['as' => 'auth.register', 'uses' => 'AuthController@register']);;
$router->post('/forgot-password', ['as' => 'auth.forgot-password', 'uses' => 'AuthController@forgot_password']);
$router->get('/user', ['as' => 'auth.user', 'uses' => 'AuthController@user', 'middleware' => 'auth']);

// update profile
$router->put('/user', ['as' => 'auth.update', 'uses' => 'AuthController@profile_update']);
$router->put('/change-password', ['as' => 'auth.change_password', 'uses' => 'AuthController@profile_change_password']);
