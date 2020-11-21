<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Version;

class HomeController extends Controller
{
    public function index()
    {
        $app = app('router')->app->version();
        preg_match("/\Lumen.*?\)/", $app, $lumen_version);
        preg_match("/\Laravel.*?\)/", $app, $component_version);

        return [
            'name' => env('APP_NAME', 'LSP APP'),
            'institute' => 'SMKN 1 MOJOKERTO',
            'version' => (new Version)->format(),
            'api_version' => env('API_VERSION', 1),
            'lumen' => str_replace(['(', ')', 'Lumen', ' '], '', $lumen_version[0]),
            'laravel_components' => str_replace(['(', ')', 'Laravel Components', ' '], '', $component_version[0])
        ];
    }
}
