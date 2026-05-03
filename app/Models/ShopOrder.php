<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShopOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'item_id',
        'type',
        'buyer_name',
        'quantity',
        'price',
        'total_amount',
        'tax_rate',
        'tax_amount',
    ];

    protected $casts = [
        'tax_rate' => 'decimal:2',
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
