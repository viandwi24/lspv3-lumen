<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class DataTable {
    protected $source;
    protected $columnFilters = [];
    protected $columns = [];

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
    
    public function columnFilter($name, $callback)
    {
        $this->columnFilters[$name] = $callback;
        return $this;
    }
    
    public function addColumn($name, $callback)
    {
        $this->columns[$name] = $callback;
        return $this;
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
        if (request()->has('sort') && request()->sort !== '')
        {
            $sorts = request()->get('sort', '[]');
            if (count($sorts) > 0)
            {
                foreach($sorts as $sort)
                {
                    // return ['data' => [], 'sort' => json_decode($sort)];
                    try {
                        $sort = json_decode($sort);
                        $eloquent->orderBy(
                            $sort->field,
                            $sort->type
                        );
                    } catch (\Throwable $th) {
                    }
                }
            }
        }

        // columnFilter
        if (count($this->columnFilters) > 0)
        {
            $tableColumnFilters = (array) json_decode(request()->get('columnFilters', '{}'));
            foreach ($tableColumnFilters as $key => $val)
            {
                // return [
                //     'data' => [],
                //     'test' => ['key' => $key, 'val' => $val]
                // ];
                if (isset($this->columnFilters[$key]))
                {
                    $callback = $this->columnFilters[$key];
                    $result = $callback($eloquent, $val);
                    if ($result instanceof \Illuminate\Database\Eloquent\Builder) $eloquent = $result;
                }
            }
        }

        // count
        $totalRecords = $eloquent->count();

        // 
        if (request()->has('columns'))
        {
            $perPage = (request()->get('length', null) != null)
                ? request()->get('length', 10)
                : request()->get('perPage', 10);
            $page = request()->get('page', 1);
            $totalPage = ceil($totalRecords/$perPage);
            $offset = ($page == 1)
                ? 0
                : (($page-1) * $perPage);

            // data
            $data = $this->injectData($eloquent->limit($perPage)->offset($offset)->get());
    
            // 
            $response = $this->makeResponse([
                'data' => $data,
                'totalRecords' => $totalRecords,
                'meta' => [
                    'page' => $page,
                    'totalPage' => $totalPage,
                    'offset' => $offset,
                    'searchableFields' => $searchableFields
                ]
            ]);
        } else {
            // data
            $data = $this->injectData($eloquent->get());

            // 
            $response =$this->makeResponse([
                'data' => $data,
                'totalRecords' => $totalRecords
            ]);
        }


        // disale query log
        DB::disableQueryLog();

        // 
        return $response;
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

    protected function injectData($data)
    {
        $result = [];
        foreach($data as $item)
        {
            $eq = $item->toArray();
            if (count($this->columns) > 0)
            {
                foreach($this->columns as $key => $column)
                {
                    $eq[$key] = (is_callable($column))
                        ? $column($item)
                        : ((isNull($column)) ? null : $column);
                }
            }
            $result[] = $eq;
        }
        return $result;
    }
}