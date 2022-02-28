<?php

namespace App\Models;

use App\Casts\Price;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class CartItem extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'carts_items';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['id', 'cart_id'];

    /**
     * The attributes that should be cast for arrays.
     *
     * @var string[]
     */
    protected $casts = [
        'price' => Price::class
    ];

    /**
     * No timestamps for the model.
     *
     * @var array
     */
    public $timestamps = false;
}
