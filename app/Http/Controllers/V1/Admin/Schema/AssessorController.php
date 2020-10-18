<?php

namespace App\Http\Controllers\V1\Admin\Schema;

use App\Helpers\DataTable;
use App\Http\Controllers\Controller;
use App\Models\Schema;
use App\Models\SchemaAssessor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AssessorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($schema_id)
    {
        // 
        $schema = Schema::find($schema_id);
        if (!$schema) return abort(404);

        // 
        $eloquent = $schema->assessors();

        // 
        if (isset($_GET['add'])) $eloquent = User::where('role', 'Assessor')->whereNotIn('id', $eloquent->get()->pluck('id'));

        // 
        $response = (new DataTable)
            ->of($eloquent)
            ->make();

        return apiResponse(
            $response,
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
        $validator = Validator::make($request->all() ,[
            'assessor_id' => 'required|integer'
        ]);

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
        $user = User::findOrFail($request->assessor_id);
        if ($user->role != 'Assessor') return apiResponse(
            $request->all(),
            "Validation Fails.",
            false,
            'validation.fails',
            ['assessor' => ["cannt added user because, not assessor."]],
            422
        );
        
        // 
        $schema = Schema::findOrFail($schema_id);

        // 
        $created = null;
        DB::transaction(function () use ($schema, $request, &$created) {
            $created = SchemaAssessor::create([
                'schema_id' => $schema->id,
                'user_id' => $request->assessor_id
            ]);
        });

        // 
        return apiResponse(
            $created,
            'create data success.',
            true
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($schema_id, $id)
    {
        $ids = explode(',', $id);
        $schema_assesors = SchemaAssessor::where('schema_id', $schema_id)->whereIn('user_id', $ids)->get();
        $destroy = $schema_assesors->each(function ($schema_assesor, $key) {
            $schema_assesor->delete();
        });

        // 
        return apiResponse(
            $schema_assesors->pluck('id'),
            'delete data success.',
            true
        );
    }
}
