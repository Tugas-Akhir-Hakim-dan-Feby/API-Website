<?php

namespace App\Http\Traits;

trait FillableFixer
{
    protected function onlyFillables($attributes, $fillables)
    {
        $attributeFillables = [];

        foreach ($fillables as $fillable) {
            if (array_key_exists($fillable, $attributes)) {
                $attributeFillables[$fillable] = $attributes[$fillable];
            }
        }

        return $attributeFillables;
    }
}
