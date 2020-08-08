<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class DataTable {
    protected $source;

    protected function reset()
    {
        $this->source = null;
    }

    public function of($source)
    {
        $this->source = $source;
        $clone = clone $this;
        $this->reset();
        return $clone;
    }

    public function make()
    {
        // 
        $eloquent = null;
        if ($this->source instanceof \Illuminate\Database\Eloquent\Builder) $eloquent = $this->source;

        // 
        if ($eloquent == null) return $this->makeResponse([
            'message' => 'Source not instance of "Illuminate\Database\Eloquent\Builder"'
        ]);

        // enable query
        DB::enableQueryLog();

        // 
        $columns = request()->get('columns', []);
        $search = request()->get('search', null);

        // search
        $searchableFields = [];
        foreach($columns as $column)
        {
            $field = (gettype($column) == 'string')
                ? json_decode($column)
                : $column;
            if (isset($field->searchable) && $field->searchable == true) $searchableFields[] = $field->field;
        }
        if ($search != null)
        {
            $eloquent->where(function ($q) use ($searchableFields, $search, &$eloquent) {
                foreach($searchableFields as $field)
                {
                    $eloquent->orWhere($field, 'LIKE', "%{$search}%");
                }
            });
        }

        // sort
        $sort = json_decode(request()->sort);
        $eloquent->orderBy(
            $sort->field,
            $sort->type
        );

        // count
        $totalRecords = $eloquent->count();

        // 
        $perPage = request()->get('perPage', 10);
        $page = request()->get('page', 1);
        $totalPage = ceil($totalRecords/$perPage);
        $offset = ($page == 1)
            ? 0
            : (($page-1) * $perPage);

        // data
        $data = $eloquent->limit($perPage)->offset($offset)->get();

        // disale query log
        DB::disableQueryLog();

        // 
        return $this->makeResponse([
            'data' => $data,
            'totalRecords' => $totalRecords,
            'meta' => [
                'page' => $page,
                'totalPage' => $totalPage,
                'offset' => $offset,
                'searchableFields' => $searchableFields
            ]
        ]);
    }

    protected function makeResponse($data = [])
    {
        $vars = [
            'queries' => DB::getQueryLog(),
            'input' => request()->all()
        ];
        if (!isset($data['data'])) $vars['data'] = [];
        return array_merge(
            $data,
            $vars
        );
    }
}