<?php

namespace App;
use App\Order;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_product')
                    ->withPivot('cantidad', 'precio')
                    ->withTimestamps();
    }
}
