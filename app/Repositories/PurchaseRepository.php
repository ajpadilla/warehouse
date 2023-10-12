<?php

namespace App\Repositories;

use App\Purchase;
use Illuminate\Support\Collection;
class PurchaseRepository extends AbstractRepository
{
    /**
     * @param Purchase $model
     */
    function __construct(Purchase $model)
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
            ->select('purchases.*');

        $joins = collect();


        $joins->each(function ($item, $key) use (&$query) {
            $item = json_decode($item);
            $query->join($key, $item->first, '=', $item->second, $item->join_type);
        });


        if ($count) {
            return $query->count('purchases.id');
        }

        return $query->orderBy('purchases.id');
    }
}
