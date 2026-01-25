<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\GameSetting; // 引入設定模型

class RestoreStamina extends Command
{

    // 1. 指令名稱 (在終端機呼叫的名字)
    protected $signature = 'game:restore-stamina';

    // 2. 指令說明
    protected $description = '全服體力恢復排程';

    public function handle()
    {
        $this->info('[' . now() . '] 開始執行全服體力恢復...');

        // 從資料庫讀取「每次恢復多少點」，若沒設定預設為 1
        // (這樣雖然頻率固定 15 分鐘，但您可以透過後台調整恢復量來控制速度)
        $amount = (int) GameSetting::get('stamina_recovery_amount', 1);

        // 批量更新：只更新體力還沒滿的玩家
        // 邏輯：體力 = 最小值(體力 + 恢復量, 體力上限)
        $affected = User::whereColumn('stamina', '<', 'max_stamina')
            ->update([
                'stamina' => DB::raw("LEAST(stamina + {$amount}, max_stamina)")
            ]);

        $this->info("已完成！共幫 {$affected} 位玩家恢復了 {$amount} 點體力。");
    }
}
