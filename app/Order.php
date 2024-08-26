<?php

namespace App;
use App\Product;
use App\User;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $fillable = [
        'user_id',
        'domicilio_entrega',
        'domicilio_facturacion',
        'total',
        'estado',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_product')
                    ->withPivot('cantidad', 'precio')
                    ->withTimestamps();
    }
}
