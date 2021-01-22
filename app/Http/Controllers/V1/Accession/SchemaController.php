<?php

namespace App\Http\Controllers\V1\Accession;

use App\Helpers\DataTable;
use App\Http\Controllers\Controller;
use App\Models\Schema;
use Illuminate\Http\Request;

class SchemaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 
        $eloquent = Schema::query();
        $response = (new DataTable)
            ->of($eloquent)
            ->make();
        return $response;

        $data = apiDataTablesResponse(
            $eloquent
        );
        return apiResponse(
            $data,
            'get data success.',
            true
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $schema = Schema::with('categories', 'files', 'competency_units', 'competency_units.work_elements', 'competency_units.work_elements.job_criterias')->findOrFail($id);
        return apiResponse(
            $schema,
            'get data success.',
            true
        );
    }
}
