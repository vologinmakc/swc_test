<?php

namespace App\Models;

use App\Services\Dto\User\UserDto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class User
 *
 * @package App\Models
 *
 * @property int                                                   $id
 * @property string                                                $login
 * @property string                                                $password
 * @property string                                                $first_name
 * @property string                                                $last_name
 * @property \Illuminate\Support\Carbon                            $registration_date
 * @property \Illuminate\Support\Carbon|null                       $birth_date
 * @property string|null                                           $remember_token
 * @property \Illuminate\Support\Carbon                            $created_at
 * @property \Illuminate\Support\Carbon                            $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Event[] $createdEvents
 * @property-read \Illuminate\Database\Eloquent\Collection|Event[] $participatingEvents
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'login',
        'password',
        'first_name',
        'last_name',
        'registration_date',
        'birth_date',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // так как по умолчание используется email переопределим под новые требования
    public function findForPassport($login)
    {
        return $this->where('login', $login)->first();
    }

    public static function create(UserDto $dto)
    {
        $self = new self;
        $self->login = $dto->login;
        $self->first_name = $dto->first_name;
        $self->last_name = $dto->last_name;
        $self->birth_date = $dto->birth_date;
        $self->password = Hash::make($dto->password);

        $self->save();

        return $self;
    }

    /**
     * @return HasMany
     */
    public function createdEvents(): HasMany
    {
        return $this->hasMany(Event::class, 'creator_id');
    }

    /**
     *
     * События в который участвует пользователь
     * @return BelongsToMany
     */
    public function participatingEvents(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_user');
    }
}
