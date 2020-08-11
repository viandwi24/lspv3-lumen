<?php

$router->group(['prefix' => '/categories'], function() use ($router) {
    $router->get('/', ['as' => 'categories.index', 'uses' => 'CategoryController@index']);
    $router->post('/', ['as' => 'categories.store', 'uses' => 'CategoryController@store']);
    $router->get('/{id}', ['as' => 'categories.show', 'uses' => 'CategoryController@show']);
    $router->put('/{id}', ['as' => 'categories.update', 'uses' => 'CategoryController@update']);
    $router->get('/{id}/edit', ['as' => 'categories.edit', 'uses' => 'CategoryController@edit']);
    $router->delete('/{id}', ['as' => 'categories.destroy', 'uses' => 'CategoryController@destroy']);
});

$router->group(['prefix' => '/places'], function() use ($router) {
    $router->get('/', ['as' => 'places.index', 'uses' => 'PlaceController@index']);
    $router->post('/', ['as' => 'places.store', 'uses' => 'PlaceController@store']);
    $router->get('/{id}', ['as' => 'places.show', 'uses' => 'PlaceController@show']);
    $router->put('/{id}', ['as' => 'places.update', 'uses' => 'PlaceController@update']);
    $router->get('/{id}/edit', ['as' => 'places.edit', 'uses' => 'PlaceController@edit']);
    $router->delete('/{id}', ['as' => 'places.destroy', 'uses' => 'PlaceController@destroy']);
});