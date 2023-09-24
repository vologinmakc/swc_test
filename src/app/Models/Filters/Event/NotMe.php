<?php

namespace App\Models\Filters\Event;

use App\Interfaces\Filter\FilterInterface;
use Illuminate\Database\Eloquent\Builder;

class NotMe implements FilterInterface
{

    public function apply(Builder $builder, $value)
    {
        $currentUserId = auth()->id();

        return $builder->whereDoesntHave('participants', function ($q) use ($currentUserId) {
            $q->where('user_id', $currentUserId);
        });
    }
}
