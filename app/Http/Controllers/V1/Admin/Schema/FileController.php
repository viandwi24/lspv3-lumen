<?php

namespace App\Http\Controllers\V1\Admin\Schema;

use App\Helpers\DataTable;
use App\Http\Controllers\Controller;
use App\Models\Schema;
use App\Models\SchemaFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
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
        $eloquent = $schema->files();

        // 
        $response = (new DataTable)
            ->of($eloquent)
            // ->addColumn('format', function (SchemaFile $file) {
            //     return implode(', ', $file->format);
            // })
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
            'name' => 'required|string|min:3|max:100',
            'format' => 'required|array',
            'format.*' => 'required|string'
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
        $schema = Schema::findOrFail($schema_id);

        // 
        $created = null;
        DB::transaction(function () use ($request, $schema, &$created) {
            $created = $schema->files()->create(
                $request->only('name', 'format')
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $schema_id, $id)
    {
        // 
        $schema = Schema::findOrFail($schema_id);
        $file = SchemaFile::findOrFail($id);

        // make validator
        $rules = [];
        $mergedData = [];
        if ($request->has('name') && $file->name != $request->name)
        {
            $rules['name'] = 'required|string|min:3|max:100';
            $mergedData['name'] = $request->name;
        }
        if ($request->has('format') && $file->format != $request->format)
        {
            $rules['format'] = 'required|array';
            $rules['format.*'] = 'required|string';
            $mergedData['format'] = $request->format;
        }

        // validate fails
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) return apiResponse(
            $request->all(),
            "Validation Fails.",
            false,
            'validation.fails',
            $validator->errors(),
            422
        );

        // 
        $updated = null;
        DB::transaction(function () use ($mergedData, $file, &$updated) {
            $updated = $file->update($mergedData);
        });

        // 
        return apiResponse(
            $file,
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
    public function destroy($schema_id, $id)
    {
        //
        $schema = Schema::find($schema_id);
        if (!$schema) return abort(404);

        //
        $ids = explode(',', $id);
        $schema_files = $schema->files()->whereIn('id', $ids)->get();
        $destroy = $schema_files->each(function ($schema_file, $key) {
            $schema_file->delete();
        });

        // 
        return apiResponse(
            $schema_files->pluck('id'),
            'delete data success.',
            true
        );
    }
}
