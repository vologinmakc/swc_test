<?php

namespace App\Models;

use App\Services\Dto\EventDto\EventDto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;

/**
 * Class Event
 *
 * @package App\Models
 *
 * @property int                                             $id
 * @property string                                          $title
 * @property string                                          $text
 * @property \Illuminate\Support\Carbon                      $creation_date
 * @property int                                             $creator_id
 * @property User                                            $creator
 * @property \Illuminate\Database\Eloquent\Collection|User[] $participants
 */
class Event extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'text',
        'creation_date',
        'creator_id'
    ];

    public static function create(EventDto $dto)
    {
        $self = new self;
        $self->title = $dto->title;
        $self->text = $dto->text;
        $self->creation_date = now();
        $self->creator_id = Auth::id();

        $self->save();

        return $self;
    }

    /**
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * @return BelongsToMany
     */
    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_user');
    }
}
