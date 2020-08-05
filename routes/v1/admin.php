<?php

$router->group(['prefix' => '/categories'], function() use ($router) {
    $router->get('/', ['as' => 'categories.index', 'uses' => 'CategoryController@index']);
    $router->post('/', ['as' => 'categories.store', 'uses' => 'CategoryController@store']);
    $router->get('/{id}', ['as' => 'categories.show', 'uses' => 'CategoryController@show']);
    $router->put('/{id}', ['as' => 'categories.update', 'uses' => 'CategoryController@update']);
    $router->get('/{id}/edit', ['as' => 'categories.edit', 'uses' => 'CategoryController@edit']);
    $router->delete('/{id}', ['as' => 'categories.destroy', 'uses' => 'CategoryController@destroy']);
});