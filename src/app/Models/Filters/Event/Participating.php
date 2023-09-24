<?php

namespace App\Models\Filters\Event;

use App\Interfaces\Filter\FilterInterface;
use Illuminate\Database\Eloquent\Builder;

class Participating implements FilterInterface
{

    public function apply(Builder $builder, $value)
    {
        $currentUserId = auth()->id();

        return $builder->whereHas('participants', function ($q) use ($currentUserId) {
            $q->where('user_id', $currentUserId);
        });
    }
}
