<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use Filterable, HasFactory, SoftDeletes;

    protected $fillable = [
        'external_id',
        'title',
        'status',
        'price',
        'external_shipment_id',
        'delivered_at',
        'user_id',
    ];
}
