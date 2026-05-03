<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Government extends Model
{
    protected $fillable = [
        'tax_rate',
        'public_order',
        'land_development',
        'treasury',
        'population',
        'military_count',
        'military_food',
    ];

    protected $casts = [
        'tax_rate' => 'decimal:2',
    ];

    /**
     * 取得唯一的政府資料（Singleton 模式）
     * 若資料庫尚未初始化，自動以預設值建立
     */
    public static function current(): self
    {
        return self::firstOrCreate(
            ['id' => 1],
            [
                'tax_rate' => 5.00,
                'public_order' => 70,
                'land_development' => 30,
                'treasury' => 10000000,
                'population' => 50000,
                'military_count' => 5000,
                'military_food' => 20000,
            ]
        );
    }

    /**
     * 根據當前稅率計算交易稅額（四捨五入到整數文）
     */
    public function calculateTaxAmount(int $amount): int
    {
        if ($amount <= 0) {
            return 0;
        }

        return (int) round($amount * ((float) $this->tax_rate / 100));
    }

    /**
     * 治安狀態的語意標籤
     */
    public function getPublicOrderLabelAttribute(): string
    {
        return match(true) {
            $this->public_order >= 80 => '太平',
            $this->public_order >= 60 => '安定',
            $this->public_order >= 40 => '動盪',
            $this->public_order >= 20 => '混亂',
            default                   => '大亂',
        };
    }

    /**
     * 開荒狀態的語意標籤
     */
    public function getLandDevelopmentLabelAttribute(): string
    {
        return match(true) {
            $this->land_development >= 80 => '沃野千里',
            $this->land_development >= 60 => '欣欣向榮',
            $this->land_development >= 40 => '初見成效',
            $this->land_development >= 20 => '篳路藍縷',
            default                        => '蠻荒未闢',
        };
    }
}
