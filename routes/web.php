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

use App\Models\Schema;

$router->get('/tes', function () {
    $skema = Schema::with(
        'competency_units',
        'competency_units.work_elements',
        'competency_units.work_elements.job_criterias'
    )->whereId(1)->first();

    return $skema;
});
