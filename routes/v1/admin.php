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

$router->group(['prefix' => '/schedules'], function() use ($router) {
    $router->get('/', ['as' => 'schedules.index', 'uses' => 'ScheduleController@index']);
    $router->post('/', ['as' => 'schedules.store', 'uses' => 'ScheduleController@store']);
    $router->get('/{id}', ['as' => 'schedules.show', 'uses' => 'ScheduleController@show']);
    $router->put('/{id}', ['as' => 'schedules.update', 'uses' => 'ScheduleController@update']);
    $router->get('/{id}/edit', ['as' => 'schedules.edit', 'uses' => 'ScheduleController@edit']);
    $router->delete('/{id}', ['as' => 'schedules.destroy', 'uses' => 'ScheduleController@destroy']);
});

$router->group(['prefix' => '/schemas'], function() use ($router) {
    $router->get('/', ['as' => 'schemas.index', 'uses' => 'SchemaController@index']);
    $router->post('/', ['as' => 'schemas.store', 'uses' => 'SchemaController@store']);
    $router->get('/{id}', ['as' => 'schemas.show', 'uses' => 'SchemaController@show']);
    $router->put('/{id}', ['as' => 'schemas.update', 'uses' => 'SchemaController@update']);
    $router->get('/{id}/edit', ['as' => 'schemas.edit', 'uses' => 'SchemaController@edit']);
    $router->delete('/{id}', ['as' => 'schemas.destroy', 'uses' => 'SchemaController@destroy']);
});