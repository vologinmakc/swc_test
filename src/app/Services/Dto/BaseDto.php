<?php

namespace App\Services\Dto;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use ReflectionClass;

/**
 * Класс автоматического заполнения полей дочерних объектов Dto через конструктор
 */
class BaseDto
{
    public function __construct(array $dataForTransfer)
    {
        // Данный метод дает возможность задать значения по умолчание перед автоматическим заполнением
        $this->setDefaults();

        $propertyNames = collect((new ReflectionClass($this))->getProperties())
            ->pluck('name')
            ->filter(fn($property) => !isset($this->{$property}))
            ->toArray();

        // Приводим все ключи массива к нижнему регистру и затем к snake_case
        $dataForTransfer = collect($dataForTransfer)->mapWithKeys(function ($value, $key) {
            return [Str::snake(strtolower($key)) => $value];
        });

        foreach ($propertyNames as $propertyName) {
            // Приведение ключей входного массива к различным форматам написания
            $snakeCasePropertyName = Str::snake($propertyName);
            $camelCasePropertyName = Str::camel($propertyName);
            $pascalCasePropertyName = ucfirst($camelCasePropertyName);

            // Поиск соответствующих значений в входном массиве
            $this->{$propertyName} = $dataForTransfer[$snakeCasePropertyName]
                ?? $dataForTransfer[$camelCasePropertyName]
                ?? $dataForTransfer[$pascalCasePropertyName]
                ?? null;
        }

        // Если есть метод rules в классе-наследнике, производим валидацию
        if (method_exists($this, 'rules')) {
            $validator = Validator::make($this->getAttribute(), $this->rules());

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
        }
    }


    public function getAttribute(): array
    {
        return collect(get_object_vars($this))
            ->filter(fn($value) => null !== $value)
            ->mapWithKeys(fn($value, $key) => [Str::snake($key) => $value])
            ->toArray();
    }

    // Данный метод дает возможность задать значения по умолчание перед автоматическим заполнением
    protected function setDefaults()
    {

    }
}
