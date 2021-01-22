<?php

$router->group(['prefix' => '/schema-lists'], function() use ($router) {
    $router->get('/', ['as' => 'schemas.index', 'uses' => 'SchemaController@index']);
});

$router->group(['prefix' => '/schemas'], function() use ($router) {
    $router->get('/{id}', ['as' => 'schemas.show', 'uses' => 'SchemaController@show']);
});

$router->group(['prefix' => '/files'], function() use ($router) {
    $router->get('/', ['as' => 'files.index', 'uses' => 'FileController@index']);
    $router->post('/', ['as' => 'files.store', 'uses' => 'FileController@store']);
    $router->get('/{id}', ['as' => 'files.download', 'uses' => 'FileController@download']);
    $router->delete('/{id}', ['as' => 'files.destroy', 'uses' => 'FileController@destroy']);
});
