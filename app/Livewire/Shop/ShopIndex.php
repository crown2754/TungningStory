<?php

namespace App\Livewire\Shop;

use App\Models\Shop;
use App\Models\GameSetting;
use App\Models\AuditLog;
use App\Models\Npc;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShopIndex extends Component
{
    public $shopName;

    // [新增] 用來控制 NPC 說什麼話 (預設為空，代表說預設台詞)
    public $npcSpeech = '';

    public function createShop()
    {
        // 1. 重置對話
        $this->npcSpeech = '';

        // 2. 自訂驗證邏輯 (為了讓錯誤訊息也由 NPC 說出來)
        try {
            $this->validate([
                'shopName' => [
                    'required',
                    'string',
                    'max:10',
                    'unique:shops,name',
                    function ($attribute, $value, $fail) {
                        $blacklist = ['GM', 'Admin', 'System', 'Official', '官方', '管理員', '客服', '東寧府', '測試', '系統', '運營', '總督'];
                        foreach ($blacklist as $word) {
                            if (stripos($value, $word) !== false) {
                                $fail("這個名諱「{$word}」乃官府專用，還請避諱。");
                                return;
                            }
                        }
                    },
                ],
            ], [
                // [修正] 定義中文錯誤訊息，避免楊英烙英文
                'shopName.required' => '招牌上空無一字，這可不行。',
                'shopName.max' => '這名號太長了，匾額刻不下。',
                'shopName.unique' => '這名號已有人用了。',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // [關鍵] 驗證失敗時，讓楊英說出錯誤原因
            // 取出第一個錯誤訊息
            $firstError = current($e->errors())[0];
            $this->npcSpeech = "且慢！{$firstError} 請您三思再重新題字。";

            // 重新拋出例外讓前端顯示紅框 (選擇性，或者直接 return)
            throw $e;
        }

        $user = Auth::user();
        $cost = (int) GameSetting::get('shop_creation_fee', 5000);

        // 3. 檢查錢夠不夠
        if ($user->gold < $cost) {
            $shortage = number_format($cost - $user->gold);
            // [關鍵] 沒錢的時候楊英說的話
            $this->npcSpeech = "這位大人... 您的盤纏似乎還差了 {$shortage} 文。庫房規矩森嚴，恕在下無法通融。";
            return;
        }

        // 4. 執行交易
        DB::transaction(function () use ($user, $cost) {
            $user = \App\Models\User::where('id', $user->id)->lockForUpdate()->first();

            // 二次防禦
            if ($user->gold < $cost) {
                $this->npcSpeech = "這位大人... 您的銀兩似乎不夠啊。";
                return;
            };

            $user->decrement('gold', $cost);

            Shop::create([
                'user_id' => $user->id,
                'name' => $this->shopName,
                'description' => '新張大吉，誠信經營。',
                'is_open' => false,
                'opened_at' => now(),
            ]);

            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'CREATE_SHOP',
                'description' => "花費 {$cost} 文盤下了商號：{$this->shopName}",
                'changes' => ['cost' => $cost, 'shop_name' => $this->shopName],
            ]);
        });

        // 5. 成功後的祝賀詞
        $this->npcSpeech = "恭喜大人！手續已辦妥，這間「{$this->shopName}」現在是您的了。願您生意興隆，富甲一方！";
    }

    public function render()
    {
        $user = Auth::user();
        $shop = $user->shop;
        $cost = GameSetting::get('shop_creation_fee', 5000);
        $npc = Npc::where('name', '楊英')->first();

        return view('livewire.shop.shop-index', [
            'hasShop' => $shop !== null,
            'shop' => $shop,
            'cost' => $cost,
            'npc' => $npc,
        ])->layout('layouts.app');
    }
}
