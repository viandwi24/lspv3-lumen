<?php

namespace App\Http\Controllers\V1\Accession;

use App\Helpers\DataTable;
use App\Http\Controllers\Controller;
use App\Models\Schema;
use Illuminate\Http\Request;

class SchemaController extends Controller
{
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
}
