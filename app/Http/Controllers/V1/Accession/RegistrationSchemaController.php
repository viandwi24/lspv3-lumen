<?php

namespace App\Http\Controllers\V1\Accession;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Schema;
use App\Models\SchemaRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File as FacadesFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RegistrationSchemaController extends Controller
{
    protected $upload_path = 'users/files';
    protected $schema_path = 'schema/registration';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // vars
        $user = Auth::user();

        // path
        $rules = [
            'schema_id' => 'required|numeric',
            'user.name' => 'required|string',
            'user.name' => 'required|string',
            'user.placeOfBirth' => 'required|string',
            'user.dateOfBirth' => 'required|string|date',
            'user.gender' => 'required|string|in:Male,Female',
            'user.nationality' => 'required|string',
            'user.phone' => 'required|string',
            'user.email' => 'required|string|email',
            'user.last_education' => 'required|string',
            'job.status' => 'required|boolean',
            'certification.purpose' => 'required|string|in:Certification,Recertification,Other'
        ];
        if ($request->has('job.status') && ($request->input('job.status') == 'true' || $request->input('job.status') == true))
        {
            $rules = array_merge($rules, [
                'job.company' => 'required|string',
                'job.position' => 'required|string',
                'job.address' => 'required|string',
                'job.phone' => 'required|string',
                'job.email' => 'required|string|email',
            ]);
        }

        // validator
        $validator = Validator::make($request->all(), $rules);

        // validate fails
        if ($validator->fails()) return apiResponse(
            $request->all(),
            "Validation Fails.",
            false,
            'validation.fails',
            $validator->errors(),
            422
        );

        // vars
        $schema = Schema::findOrFail($request->schema_id);

        // custom validator
        $errors = [];
        $search = null;
        $schema_files = $schema->files;
        $user_files = (new Collection($request->has('files') ? $request->input('files') : []));
        foreach ($user_files as $key => $value) {
            if (is_null($value) || is_null(@$value['file'])) $user_files->splice($key, 1);
        }
        $user_files_accepted = [];
        foreach ($schema_files as $key => $schema_file) 
        {
            if (!in_array($schema_file->id, $user_files->pluck('schema_file')->toArray()))
            {
                $errors['file'][] = "File {$schema_file->name} harus dilampirkan juga!";
            } else {
                $user_files_arr = $user_files->toArray();
                $search = array_search($schema_file->id, array_column($user_files_arr, 'schema_file'));
                $file = File::findOrFail(@$user_files_arr[$search]['file']);
                return Storage::extension("{$this->upload_path}/{$file->path}");
                $file_ext = '.' . pathinfo(storage_path("app/{$this->upload_path}/{$file->path}"), PATHINFO_EXTENSION);
                if (in_array($file_ext, $schema_file->format))
                {
                    array_push($user_files_accepted, (object) ['file' => $file, 'schema_file' => $schema_files]);
                } else {
                    $errors['file'][] = "File {$schema_file->name} tidak sesuai dengan format yang diharuskan! (". implode(", ", $schema_file->format) .")";
                }
            }
        }
        $other_user_files = (new Collection($request->has('otherFile') ? $request->input('otherFile') : []));
        $user_other_files_accepted = [];
        foreach ($other_user_files as $key => $value) {
            if (is_null($value['name']) || empty($value['name']) || $value['name'] === '') 
            {
                $errors['otherFile'][] = "Ada Berkas pendukung yang tidak anda isi nama nya!";
            } else if (is_null($value['file']) || empty($value['file']) || $value['file'] === '') 
            {
                $errors['otherFile'][] = "Ada Berkas pendukung yang tidak anda isi file nya!";
            } else {
                $file = File::findOrFail(@$value['file']);
                $user_other_files_accepted[] = $file;
            }
        }

        // validate fails
        if (count($errors) > 0) return apiResponse(
            $request->all(),
            "Validation Fails.",
            false,
            'validation.fails',
            $errors,
            422
        );

        // 
        $store = null;
        DB::transaction(function () use ($request, $user, $user_files_accepted, &$store) {
            $store = SchemaRegistration::create([
                'schema_id' => $request->schema_id,
                'user_id' => $user->id,
                'data' => $request->all()
            ]);
            $path = $this->schema_path . DIRECTORY_SEPARATOR . $store->id;
            if (!Storage::exists($path)) Storage::makeDirectory($path, 0755, true);
            foreach ($user_files_accepted as $key => $value) {
                $copy = Storage::copy($this->upload_path . DIRECTORY_SEPARATOR . $value->file->path, $path . DIRECTORY_SEPARATOR . $value->file->path);
                $store->files()->create([
                    'name' => $value->file->name,
                    'type' => $value->file->type,
                    'size' => $value->file->size,
                    'path' => $value->file->path,
                ]);
            }
        });

        // 
        return apiResponse(
            [
                'file_user' => $user_files->pluck('schema_file'),
                'file_harus' => $schema_files->pluck('id'),
                'file_accepted' => [
                    $user_files_accepted,
                    $user_other_files_accepted
                ],
                'input' => $request->all(),
                'rules' => $rules,
                'store' => $store
            ],
            "create data success",
            true,
            null, null,
            201
        );
    }
}
