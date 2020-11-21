<?php

$router->group(['prefix' => '/schema-lists'], function() use ($router) {
    $router->get('/', ['as' => 'schemas.index', 'uses' => 'SchemaController@index']);
});