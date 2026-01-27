<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShopProduct extends Model
{
    protected $fillable = ['shop_id', 'item_id', 'price', 'quantity'];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}