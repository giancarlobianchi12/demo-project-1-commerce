<?php

namespace App\ModelFilters;

use App\ModelFilters\Concerns\FilterByWhere;
use App\ModelFilters\Concerns\FilterByWhereDateBetween;
use EloquentFilter\ModelFilter;

class OrderFilter extends ModelFilter
{
    use FilterByWhere, FilterByWhereDateBetween;

    public function setup()
    {
        $this->setupFilterByWhere()
            ->setupFilterByWhereDateBetween();
    }

    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    public function getWhereColumnFilters(): array
    {
        return [
            'external_id',
            'status',
            'driver_user_id',
            'client_user_id',
            'zone_id',
        ];
    }

    public function getWhereDateBetweenColumnFilters(): array
    {
        return [
            'created_at',
        ];
    }
}
