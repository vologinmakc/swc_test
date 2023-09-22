<?php

namespace App\Services\Dto\EventDto;

use App\Services\Dto\BaseDto;

class EventDto extends BaseDto
{
    public ?int    $id;
    public string  $title;
    public string  $text;
}
