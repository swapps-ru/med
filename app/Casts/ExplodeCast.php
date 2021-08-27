<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class ExplodeCast implements CastsAttributes
{
    const SEPARATOR = ';';

    public function get($model, string $key, $value, array $attributes, ...$args)
    {
        $result = explode(self::SEPARATOR, $value);

        return !empty($result[0]) ? $result : [];
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return (!empty($value) && !empty($value[0])) ? implode(self::SEPARATOR, $value) : '';
    }
}
