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
    
    // 
    $router->group(['prefix' => '/{schema_id}/competency-units'], function() use ($router) {
        $router->get('/full', ['as' => 'competency_units.index', 'uses' => 'CompetencyUnitController@full']);
        $router->get('/', ['as' => 'competency_units.index', 'uses' => 'CompetencyUnitController@index']);
        $router->post('/', ['as' => 'competency_units.store', 'uses' => 'CompetencyUnitController@store']);
        $router->get('/{id}', ['as' => 'competency_units.show', 'uses' => 'CompetencyUnitController@show']);
        $router->put('/{id}', ['as' => 'competency_units.update', 'uses' => 'CompetencyUnitController@update']);
        $router->get('/{id}/edit', ['as' => 'competency_units.edit', 'uses' => 'CompetencyUnitController@edit']);
        $router->delete('/{id}', ['as' => 'competency_units.destroy', 'uses' => 'CompetencyUnitController@destroy']);

        // 
        $router->group(['prefix' => '/{competency_unit_id}/work-elements'], function() use ($router) {
            $router->get('/', ['as' => 'work_elements.index', 'uses' => 'WorkElementController@index']);
            $router->post('/', ['as' => 'work_elements.store', 'uses' => 'WorkElementController@store']);
            $router->get('/{id}', ['as' => 'work_elements.show', 'uses' => 'WorkElementController@show']);
            $router->put('/{id}', ['as' => 'work_elements.update', 'uses' => 'WorkElementController@update']);
            $router->get('/{id}/edit', ['as' => 'work_elements.edit', 'uses' => 'WorkElementController@edit']);
            $router->delete('/{id}', ['as' => 'work_elements.destroy', 'uses' => 'WorkElementController@destroy']);

            // 
            $router->group(['prefix' => '/{work_element_id}/job-criterias'], function() use ($router) {
                $router->get('/', ['as' => 'job_criterias.index', 'uses' => 'JobCriteriaController@index']);
                $router->post('/', ['as' => 'job_criterias.store', 'uses' => 'JobCriteriaController@store']);
                $router->get('/{id}', ['as' => 'job_criterias.show', 'uses' => 'JobCriteriaController@show']);
                $router->put('/{id}', ['as' => 'job_criterias.update', 'uses' => 'JobCriteriaController@update']);
                $router->get('/{id}/edit', ['as' => 'job_criterias.edit', 'uses' => 'JobCriteriaController@edit']);
                $router->delete('/{id}', ['as' => 'job_criterias.destroy', 'uses' => 'JobCriteriaController@destroy']);
            });
        });
    });
});