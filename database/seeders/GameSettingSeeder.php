<?php

namespace Database\Seeders;

use App\Models\GameSetting;
use Illuminate\Database\Seeder;

class GameSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'initial_gold',
                'value' => '100000',
                'name' => '玩家初始資產',
                'description' => '新註冊玩家預設獲得的通寶 (文)',
            ],
            [
                'key' => 'initial_stamina',
                'value' => '100',
                'name' => '玩家初始體力',
                'description' => '新註冊玩家目前的體力值',
            ],
            [
                'key' => 'initial_max_stamina',
                'value' => '100',
                'name' => '玩家初始體力上限',
                'description' => '新註冊玩家的體力最大值',
            ],
            [
                'key' => 'stamina_recovery_amount',
                'value' => '15',
                'name' => '每次體力恢復量',
                'description' => '排程執行時(預設每15分)，每次為玩家恢復的點數',
            ],
            [
                'key' => 'backend_logo_url',
                'value' => '/images/logo-tungning-bg.png', // 預設路徑 (請確認您 public 資料夾有預設圖)
                'name' => '後台標誌 (Logo)',
                'description' => '顯示於管理後台左上角的圖示',
            ],
            [
                'key' => 'shop_creation_fee',
                'value' => '10000',
                'name' => '商號盤讓費',
                'description' => '玩家開設第一間商號所需支付的通寶',
            ],
        ];

        foreach ($settings as $setting) {
            GameSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
