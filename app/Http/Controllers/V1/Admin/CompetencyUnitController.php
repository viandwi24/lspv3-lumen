<?php

namespace App\Http\Controllers\V1\Admin;

use App\Helpers\DataTable;
use App\Http\Controllers\Controller;
use App\Models\CompetencyUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\VarDumper\VarDumper;

class CompetencyUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function full($schema_id)
    {
        // 
        $eloquent = CompetencyUnit::with('work_elements', 'work_elements.job_criterias')
            ->where('schema_id', $schema_id);
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($schema_id)
    {
        // 
        $eloquent = CompetencyUnit::where('schema_id', $schema_id);
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $schema_id)
    {
        // make validator
        $validator = Validator::make($request->all(), ([
            'code' => 'required|string|min:2|max:30',
            'title' => 'required|string|min:3|max:255',
            'standard_type' => 'required|string|min:3|max:255'
        ]));

        // validate fails
        if ($validator->fails()) return apiResponse(
            $request->all(),
            "Validation Fails.",
            false,
            'validation.fails',
            $validator->errors(),
            422
        );

        // 
        $created = null;
        DB::transaction(function () use ($request, $schema_id, &$created) {
            $created = CompetencyUnit::create(
                array_merge(
                    $request->only('code', 'title', 'standard_type'),
                    [ 'schema_id' => $schema_id ]   
                )
            );
        });

        // 
        return apiResponse(
            $created,
            'create data success.',
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
        $competency_unit = CompetencyUnit::findOrFail($id);
        return apiResponse(
            $competency_unit,
            'get data success.',
            true
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // make validator
        $validator = Validator::make($request->all(), ([
            'code' => 'required|string|min:2|max:30',
            'title' => 'required|string|min:3|max:255',
            'standard_type' => 'required|string|min:3|max:255'
        ]));

        // validate fails
        if ($validator->fails()) return apiResponse(
            $request->all(),
            "Validation Fails.",
            false,
            'validation.fails',
            $validator->errors(),
            422
        );

        // 
        $competency_unit = CompetencyUnit::findOrFail($id);

        // 
        $update = null;
        DB::transaction(function () use ($request, $competency_unit, &$update) {
            $update = $competency_unit->update($request->only('code', 'title', 'standard_type'));
        });

        // 
        return apiResponse(
            $competency_unit,
            'update data success.',
            true
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ids = explode(',', $id);
        $competency_units = CompetencyUnit::findOrFail($ids);
        $destroy = $competency_units->each(function ($competency_unit, $key) {
            $competency_unit->delete();
        });

        // 
        return apiResponse(
            $competency_units->pluck('id'),
            'delete data success.',
            true
        );
    }
}