<?php

namespace App\Http\Controllers\V1\Admin;

use App\Helpers\DataTable;
use App\Http\Controllers\Controller;
use App\Models\WorkElement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class WorkElementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($competency_unit_id)
    {
        // 
        $eloquent = WorkElement::where('competency_unit_id', $competency_unit_id);
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
    public function store(Request $request, $competency_unit_id)
    {
        // make validator
        $validator = Validator::make($request->all(), ([
            'title' => 'required|string|min:3|max:255'
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
        DB::transaction(function () use ($request, $competency_unit_id, &$created) {
            $created = WorkElement::create(
                array_merge(
                    $request->only('title'),
                    ['competency_unit_id' => $competency_unit_id]
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
        $work_element = WorkElement::findOrFail($id);
        return apiResponse(
            $work_element,
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
            'title' => 'required|string|min:3|max:255'
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
        $workElement = WorkElement::findOrFail($id);

        // 
        $update = null;
        DB::transaction(function () use ($request, $workElement, &$update) {
            $update = $workElement->update($request->only('title'));
        });

        // 
        return apiResponse(
            $workElement,
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
        $workElements = WorkElement::findOrFail($ids);
        $destroy = $workElements->each(function ($workElement, $key) {
            $workElement->delete();
        });

        // 
        return apiResponse(
            $workElements->pluck('id'),
            'delete data success.',
            true
        );
    }
}