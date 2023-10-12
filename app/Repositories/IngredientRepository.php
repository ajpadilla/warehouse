<?php

namespace App\Repositories;

use App\Ingredient;
use Illuminate\Support\Collection;

class IngredientRepository extends AbstractRepository
{
    /**
     * @param Ingredient $model
     */
    function __construct(Ingredient $model)
    {
        $this->model = $model;
    }

    /**
     * @param Collection $joins
     * @param $table
     * @param $first
     * @param $second
     * @param string $join_type
     */
    private function addJoin(Collection &$joins, $table, $first, $second, $join_type = 'inner')
    {
        if (!$joins->has($table)) {
            $joins->put($table, json_encode(compact('first', 'second', 'join_type')));
        }
    }

    /**
     * @param array $filters
     * @param bool $count
     * @return mixed
     */
    public function search(array $filters = [], bool $count = false)
    {
        $query = $this->model
            ->distinct()
            ->select('ingredients.*');

        $joins = collect();


        $joins->each(function ($item, $key) use (&$query) {
            $item = json_decode($item);
            $query->join($key, $item->first, '=', $item->second, $item->join_type);
        });

        if (isset($filters['name'])) {
            $query->ofName($filters['name']);
        }

        if ($count) {
            return $query->count('ingredients.id');
        }

        return $query->orderBy('ingredients.id');
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getByName($name)
    {
        return $this->search(['name' => $name])->first();
    }

    /**
     * @param array $ingredientsList
     * @param $name
     * @return mixed|null
     */

    public function getIngredientByName(array $ingredientsList, $name) {
        foreach ($ingredientsList as $key => $ingredient) {
            if ($ingredient['name'] == $name) {
                return $ingredient['quantity'];
            }
        }
        return null;
    }
}
