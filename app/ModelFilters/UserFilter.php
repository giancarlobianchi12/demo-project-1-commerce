<?php

namespace App\ModelFilters;

use App\ModelFilters\Concerns\FilterByWhere;
use EloquentFilter\ModelFilter;

class UserFilter extends ModelFilter
{
    use FilterByWhere;

    public $relations = [];

    public function getWhereColumnFilters(): array
    {
        return [
            'type',
            'email',
        ];
    }
}
