<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'name' => '武夷茶葉',
                'description' => '來自福建武夷山的岩茶，茶香濃郁，是士大夫與洋商的最愛。',
                'type' => 'luxury',
                'base_price' => 300, // 高單價
                'stock' => 500,      // 初始庫存
                'target_stock' => 500,
                'volatility' => 0.8, // 價格波動劇烈 (奢侈品)
            ],
            [
                'name' => '稻米',
                'description' => '東寧平原開墾所產的糧食，顆粒飽滿，是百姓與軍隊的根本。',
                'type' => 'food',
                'base_price' => 50,
                'stock' => 2000,
                'target_stock' => 2000,
                'volatility' => 1.5, // 糧食非常敏感，缺貨會暴漲 (災難模擬)
            ],
            [
                'name' => '陶瓷水甕',
                'description' => '燒製厚實的陶甕，能長時間保存淡水不變質，航海必備。',
                'type' => 'equipment',
                'base_price' => 120,
                'stock' => 800,
                'target_stock' => 800,
                'volatility' => 0.3, // 工藝品價格相對穩定
            ],
            [
                'name' => '木桶水槽',
                'description' => '用堅硬杉木箍成的巨大水槽，用於船上儲存洗刷用水或救火。',
                'type' => 'equipment',
                'base_price' => 200,
                'stock' => 400,
                'target_stock' => 400,
                'volatility' => 0.4,
            ],
            [
                'name' => '臘肉',
                'description' => '以鹽與香料醃製後風乾的肉條，便於攜帶且能久放。',
                'type' => 'food',
                'base_price' => 80,
                'stock' => 1000,
                'target_stock' => 1000,
                'volatility' => 0.6,
            ],
            [
                'name' => '白酒',
                'description' => '烈性高粱酒，不僅能驅寒，戰時還能用來消毒傷口。',
                'type' => 'luxury',
                'base_price' => 150,
                'stock' => 600,
                'target_stock' => 600,
                'volatility' => 0.5,
            ],
        ];

        foreach ($items as $item) {
            Item::updateOrCreate(['name' => $item['name']], $item);
        }
    }
}
