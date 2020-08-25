<?php

namespace App\Http\Controllers\V1\Admin;

use App\Helpers\DataTable;
use App\Http\Controllers\Controller;
use App\Models\Schema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // make validator
        $validator = Validator::make($request->all(), ([
            'title' => 'required|string|min:3|max:255',
            'code' => 'required|string|min:3|max:255',
            'description' => 'required|string|min:3',
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
        DB::transaction(function () use ($request, &$created) {
            $created = Schema::create($request->only('title', 'code', 'description'));
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
        $schema = Schema::findOrFail($id);
        return apiResponse(
            $schema,
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
            'name' => 'required|string|min:2|max:30',
            'address' => 'required|string|min:3|max:255',
            'phone' => 'required|string|min:12|max:13'
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
        $place = Place::findOrFail($id);

        // 
        $update = null;
        DB::transaction(function () use ($request, $place, &$update) {
            $update = $place->update($request->only('name', 'address', 'phone'));
        });

        // 
        return apiResponse(
            $place,
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
        $schemas = Schema::findOrFail($ids);
        $destroy = $schemas->each(function ($schema, $key) {
            $schema->delete();
        });

        // 
        return apiResponse(
            $schemas->pluck('id'),
            'delete data success.',
            true
        );
    }
}