<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'unit',
        'image_path',
        'base_price',
        'min_price',
        'max_price',
        'stock',
        'target_stock',
        'volatility',
        'is_tradable',
    ];

    // 輔助方法：取得當前動態價格
    public function getCurrentPriceAttribute()
    {
        // 經濟公式：P = P_base * (1 + V * ((S_target - S_current) / S_target))

        $target = $this->target_stock > 0 ? $this->target_stock : 1;
        $ratio = ($target - $this->stock) / $target;

        $price = $this->base_price * (1 + ($ratio * $this->volatility));

        // 確保價格不超出上下限
        return (int) max($this->min_price, min($this->max_price, $price));
    }
}
