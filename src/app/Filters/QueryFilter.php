<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QueryFilter
{
    protected $request;
    protected $builder;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Если в запросе есть указание фильтра будем искать нужный класс фильтра
     * @param Builder $builder
     * @return Builder
     */
    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        $filters = $this->request->get('filter', []);

        foreach ($filters as $filter => $value) {
            $filterClass = $this->resolveFilter($filter);

            if ($filterClass && class_exists($filterClass)) {
                (new $filterClass)->apply($this->builder, $value);
            }
        }

        return $this->builder;
    }

    protected function resolveFilter($name): string
    {
        $name = Str::camel($name);
        return 'App\\Models\\Filters\\' . class_basename($this->builder->getModel()) . '\\' . ucfirst($name);
    }
}

