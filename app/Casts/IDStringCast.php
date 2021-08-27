<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class IDStringCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        preg_match_all('/\_(\d+)\_/', $value, $matches);

        if(isset($matches[1]))
        {
            return $matches[1];
        }

        return [];
    }

    public function set($model, string $key, $value, array $attributes)
    {
        $string = '';

        $value = $value ?: [];
        foreach($value as $id)
        {
            $string .= '_' . $id . '_';
        }

        return $string;
    }
}
