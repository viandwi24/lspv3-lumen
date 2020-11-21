<?php

namespace App\Http\Controllers\V1\Admin\Schema;

use App\Helpers\DataTable;
use App\Http\Controllers\Controller;
use App\Models\Place;
use App\Models\Schedule;
use App\Models\Schema;
use App\Models\SchemaSchedule;
use App\Models\User;
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
    public function index($schema_id)
    {
        // 
        $schema = Schema::find($schema_id);
        if (!$schema) return abort(404);

        // 
        $eloquent = $schema->schedules();

        // 
        if (isset($_GET['add'])) $eloquent = Schedule::whereNotIn('id', $eloquent->get()->pluck('id'));

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
            'schedule_id' => 'required|integer'
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
        $schedule = Schedule::findOrFail($request->schedule_id);
        
        // 
        $schema = Schema::findOrFail($schema_id);

        // 
        $created = null;
        DB::transaction(function () use ($schema, $request, &$created) {
            $created = SchemaSchedule::create([
                'schema_id' => $schema->id,
                'schedule_id' => $request->schedule_id
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
        $schema_schedules = SchemaSchedule::where('schema_id', $schema_id)->whereIn('schedule_id', $ids)->get();
        $destroy = $schema_schedules->each(function ($schema_schedule, $key) {
            $schema_schedule->delete();
        });

        // 
        return apiResponse(
            $schema_schedules->pluck('id'),
            'delete data success.',
            true
        );
    }
}

