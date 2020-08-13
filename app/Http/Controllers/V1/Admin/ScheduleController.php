<?php

namespace App\Http\Controllers\V1\Admin;

use App\Helpers\DataTable;
use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 
        $eloquent = Schedule::query();
        $response = (new DataTable)
            ->of($eloquent)
            ->addColumn('date', function (Schedule $schedule) {
                return $schedule->date->format('d-m-Y');
            })
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
            'name' => 'required|string|min:2|max:30',
            'date' => 'required|string|date',
            'announcement' => 'nullable|string',
            'agenda' => 'required|array',
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
            $created = Schedule::create($request->only('name', 'date', 'announcement', 'agenda'));
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
        //
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
            'date' => 'required|string|date',
            'announcement' => 'nullable|string',
            'agenda' => 'required|array',
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
        $place = Schedule::findOrFail($id);

        // 
        $update = null;
        DB::transaction(function () use ($request, $place, &$update) {
            $update = $place->update($request->only('name', 'date', 'announcement', 'agenda'));
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
        $places = Place::findOrFail($ids);
        $destroy = $places->each(function ($place, $key) {
            $place->delete();
        });

        // 
        return apiResponse(
            $places->pluck('id'),
            'delete data success.',
            true
        );
    }
}