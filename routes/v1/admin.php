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
    
    // menu schema
    $router->group(['prefix' => '/{schema_id}', 'namespace' => 'Schema'], function() use ($router) {
        // asesi
        $router->group(['prefix' => '/accessions'], function() use ($router) {
            $router->get('/', ['as' => 'accessions.index', 'uses' => 'AccessionController@index']);
            $router->post('/', ['as' => 'accessions.store', 'uses' => 'AccessionController@store']);
            $router->delete('/{id}', ['as' => 'accessions.destroy', 'uses' => 'AccessionController@destroy']);
        });
        // asesor
        $router->group(['prefix' => '/assessors'], function() use ($router) {
            $router->get('/', ['as' => 'assessors.index', 'uses' => 'AssessorController@index']);
            $router->post('/', ['as' => 'assessors.store', 'uses' => 'AssessorController@store']);
            $router->delete('/{id}', ['as' => 'assessors.destroy', 'uses' => 'AssessorController@destroy']);
        });
        // place
        $router->group(['prefix' => '/places'], function() use ($router) {
            $router->get('/', ['as' => 'places.index', 'uses' => 'PlaceController@index']);
            $router->post('/', ['as' => 'places.store', 'uses' => 'PlaceController@store']);
            $router->delete('/{id}', ['as' => 'places.destroy', 'uses' => 'PlaceController@destroy']);
        });
        // schedule
        $router->group(['prefix' => '/schedules'], function() use ($router) {
            $router->get('/', ['as' => 'schedules.index', 'uses' => 'ScheduleController@index']);
            $router->post('/', ['as' => 'schedules.store', 'uses' => 'ScheduleController@store']);
            $router->delete('/{id}', ['as' => 'schedules.destroy', 'uses' => 'ScheduleController@destroy']);
        });
        // file
        $router->group(['prefix' => '/files'], function() use ($router) {
            $router->get('/', ['as' => 'files.index', 'uses' => 'FileController@index']);
            $router->post('/', ['as' => 'files.store', 'uses' => 'FileController@store']);
            $router->put('/{id}', ['as' => 'files.update', 'uses' => 'FileController@update']);
            $router->delete('/{id}', ['as' => 'files.destroy', 'uses' => 'FileController@destroy']);
        });
    });
    
    // competency unit
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

$router->group(['prefix' => '/accessions'], function() use ($router) {
    $router->get('/', ['as' => 'accession.index', 'uses' => 'AccessionController@index']);
    $router->post('/', ['as' => 'accession.store', 'uses' => 'AccessionController@store']);
    $router->get('/{id}', ['as' => 'accession.show', 'uses' => 'AccessionController@show']);
    $router->put('/{id}', ['as' => 'accession.update', 'uses' => 'AccessionController@update']);
    $router->get('/{id}/edit', ['as' => 'accession.edit', 'uses' => 'AccessionController@edit']);
    $router->delete('/{id}', ['as' => 'accession.destroy', 'uses' => 'AccessionController@destroy']);
});

$router->group(['prefix' => '/assessors'], function() use ($router) {
    $router->get('/', ['as' => 'assessor.index', 'uses' => 'AssessorController@index']);
    $router->post('/', ['as' => 'assessor.store', 'uses' => 'AssessorController@store']);
    $router->get('/{id}', ['as' => 'assessor.show', 'uses' => 'AssessorController@show']);
    $router->put('/{id}', ['as' => 'assessor.update', 'uses' => 'AssessorController@update']);
    $router->get('/{id}/edit', ['as' => 'assessor.edit', 'uses' => 'AssessorController@edit']);
    $router->delete('/{id}', ['as' => 'assessor.destroy', 'uses' => 'AssessorController@destroy']);
});