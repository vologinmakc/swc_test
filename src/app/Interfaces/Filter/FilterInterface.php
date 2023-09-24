<?php

namespace App\Interfaces\Filter;

use Illuminate\Database\Eloquent\Builder;

interface FilterInterface
{
    public function apply(Builder $builder, $value);
}
