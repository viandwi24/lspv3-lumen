<?php

use Illuminate\Http\Resources\Json\JsonResource;
use Yajra\DataTables\CollectionDataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Facades\DataTables;

if (!function_exists('apiResource'))
{
    /**
     * Make api response
     *
     * @param [type] $data
     * @param string $message
     * @param boolean $status
     * @param string $error_code
     * @param array $errors
     * @param integer $responseCode
     * @param array $customMeta
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory|\Illuminate\Http\JsonResponse
     */
    function apiResponse($data, string $message, bool $status, string $error_code = null, $errors = null, int $responseCode = null, array $customMeta = [])
    {
        // a data must extends JsonResource
        if (($data instanceof JsonResource)) $httpresource = $data;
        else $httpresource = new JsonResource($data);
    
        // clean data
        if ($data instanceof JsonResource) $data = $data->toArray(app('request'));
    
        // build meta
        $meta = [ 'status' => $status, 'message' => $message ];
        if ($error_code != null) { $meta['error_code'] = $error_code; $meta['errors'] = ($errors == null ? [] : $errors); }
        if (count($customMeta) > 0) $meta = array_merge($meta, $customMeta);
    
        // return
        return ($error_code == null) 
            ? (
                ($responseCode == null)
                ? $httpresource->additional($meta)->response()
                : $httpresource->additional($meta)->response()->setStatusCode($responseCode)
            )
            : response()->json(array_merge(['data' => $data], $meta), ($responseCode == null ? 200 : $responseCode) )
                ->header('Content-Type', 'application/json')
                ->header('Accept', 'application/json');
    }
}

if (!function_exists('apiDataTablesResponse'))
{
    function apiDataTablesResponse($data, $extCallback = null)
    {
        $datatables = DataTables::of($data);
        if ($extCallback != null && is_callable($extCallback))
        {
            $result = $extCallback($datatables);
            if (
                ($result instanceof EloquentDataTable) or
                ($result instanceof CollectionDataTable)
            ) {
                $datatables = $result;
            }
        }
        
        // 
        $builder = $datatables->make();
        $content = $builder->getOriginalContent();

        // 
        return $content;
    } 
}

if (! function_exists('public_path')) {
    /**
     * Get the path to the public folder.
     *
     * @param  string  $path
     * @return string
     */
    function public_path($path = '')
    {
        return app()->make('path.public').($path ? DIRECTORY_SEPARATOR.ltrim($path, DIRECTORY_SEPARATOR) : $path);
    }
}

if (! function_exists('storage_path')) {
    /**
     * Get the path to the storage folder.
     *
     * @param  string  $path
     * @return string
     */
    function storage_path($path = '')
    {
        return app('path.storage').($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}
