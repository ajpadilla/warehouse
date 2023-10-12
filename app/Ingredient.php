<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'quantity'
    ];


    /**
     * @param $query
     * @param $value
     * @return mixed
     */
    public function scopeOfName($query, $value)
    {
        if (is_array($value) && !empty($value)) {
            return $query->whereIn('ingredients.name', $value);
        }
        return !$value ? $query : $query->where('ingredients.name', $value);
    }
}
