<?php

namespace App\Services\Dto\User;

use App\Services\Dto\BaseDto;

class UserDto extends BaseDto
{
    public string  $login;
    public string  $first_name;
    public string  $last_name;
    public ?string $birth_date = null;
    public string  $password;
}
