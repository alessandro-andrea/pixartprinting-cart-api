<?php

namespace App\Models;

use App\Casts\Price;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'carts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['ecommerce_id', 'customer_id', 'status'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['id', 'date_checkout', 'updated_at'];

    /**
     * The attributes that should be cast for arrays.
     *
     * @var string[]
     */
    protected $casts = [
        'price' => Price::class,
        'created_at' => 'date:Y-m-d H:i:s'
    ];

    /**
     * Get the item list for the cart.
     */
    public function itemList()
    {
        return $this->hasMany(CartItem::class)->orderBy('id');
    }
}
