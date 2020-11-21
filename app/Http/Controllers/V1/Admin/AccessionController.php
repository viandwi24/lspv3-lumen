<?php

namespace App\Http\Controllers\V1\Admin;

use App\Helpers\DataTable;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AccessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 
        $eloquent = User::where('role', 'Accession');
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
            'name' => 'required|string|min:3|max:30',
            'username' => 'required|string|min:3|max:30|unique:users',
            'email' => 'required|string|min:3|max:30|email|unique:users',
            'password' => 'required|string|min:5|max:30'
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
            $created = User::create(
                array_merge(
                    [
                        'role' => 'Accession',
                        'status' => 'Active',
                        'password' => Hash::make($request->password)
                    ],
                    $request->only('name', 'username', 'email')
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
        // 
        $accession = User::findOrFail($id);
        if ($accession->role != 'Accession') return abort(404);

        // dynamic
        $rules = ['name' => 'required|string|min:3|max:30'];
        $mergedData = [];


        // 
        if ($request->has('password') && $request->password !== '' && !empty($request->password))
        {
            $rules['password'] = 'required|string|min:5|max:30';
            $mergedData['password'] = Hash::make($request->password);
        }
        if ($accession->email != $request->email)
        {
            $rules['email'] = 'required|string|min:3|max:30|email|unique:users';
            $mergedData['email'] = $request->email;
        }
        if ($accession->username != $request->username)
        {
            $rules['username'] = 'required|string|min:3|max:30|unique:users';
            $mergedData['username'] = $request->username;
        }

        // make validator
        $validator = Validator::make($request->all(), ($rules));

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
        $update = null;
        DB::transaction(function () use ($request, $accession, $mergedData, &$update) {
            $update = $accession->update(
                array_merge(
                    $mergedData,
                    $request->only('name')
                )
            );
        });

        // 
        return apiResponse(
            $accession,
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
        $accessions = User::findOrFail($ids);
        $destroy = $accessions->each(function ($accession, $key) {
            $accession->delete();
        });

        // 
        return apiResponse(
            $accessions->pluck('id'),
            'delete data success.',
            true
        );
    }
}
