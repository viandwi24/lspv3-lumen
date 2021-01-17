<?php

namespace App\Http\Controllers\V1\Accession;

use App\Helpers\DataTable;
use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{
    protected $upload_path = 'users/files';
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 
        $user = Auth::user();
        $eloquent = $user->files();
        $response = (new DataTable)
            ->of($eloquent)
            ->make();
        return $response;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // vars
        $user = Auth::user();

        // path
        $upload_path = $this->upload_path;

        // validator
        $validator = Validator::make($request->all(), ['file' => 'required|file']);

        // validate fails
        if ($validator->fails()) return apiResponse(
            $request->all(),
            "Validation Fails.",
            false,
            'validation.fails',
            $validator->errors(),
            422
        );

        // file
        $file = $request->file('file');
        $file_name = $file->getClientOriginalName();
        $file_mime = $file->getClientMimeType();
        $file_size = $file->getSize();
        $file_detail = [
            'name' => $file_name,
            'type' => $file_mime,
            'size' => $file_size,
            'category' => 'user:file'
        ];

        // db transcation
        try {
            DB::beginTransaction();
    
            // store file
            // $store = $file->move($upload_path);
            $store = Storage::putFile($upload_path, $file);
            $file_stored = str_replace($upload_path . '/', '', $store);
            $file_detail['path'] = $file_stored;
            
            //
            $store_file = $user->files()->create($file_detail);
            DB::commit();

            // return
            return apiResponse(
                [$file_detail, $store_file],
                'Store file success.',
                true,
                null, [],
                201
            );
        } catch (\Throwable $th) {
            if (isset($file_detail['path'])) Storage::delete($upload_path . DIRECTORY_SEPARATOR . $store);
            DB::rollBack();

            // return
            return apiResponse(
                $file_detail,
                'Store file failed.',
                false,
                'file.store.fail',
                [
                    'upload' => $th->getMessage()
                ],
                400
            );
        }
    }

    /**
     * Download specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function download($id)
    {
        //
        $file = File::findOrFail($id);
        $upload_path = $this->upload_path;
        $path = $upload_path . DIRECTORY_SEPARATOR . $file->path;
        return Storage::download($path, $file->name);
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
        $files = File::findOrFail($ids);
        
        DB::transaction(function () use ($files) {
            $destroy = $files->each(function ($file, $key) {
                $file->delete();
            });
        });

        // 
        return apiResponse(
            $files->pluck('id'),
            'delete data success.',
            true
        );
    }
}