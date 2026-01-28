<?php

namespace Database\Seeders;

use App\Models\Npc;
use App\Models\Avatar;
use Illuminate\Database\Seeder;

class NpcSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 嘗試找一張看起來有書卷氣的頭像給楊英 (例如漢人男性)
        // 如果找不到特定的，就隨便抓一張，或設為 null
        $scholarAvatar = Avatar::where('name', '=', '01YangYing.jpg')
            ->first();

        Npc::create([
            'name' => '楊英',
            'title' => '戶部主事',
            'description' => '明鄭時期的重要文官，著有《從征實錄》，負責記錄錢糧兵馬與重要戰事。為人嚴謹細心，掌管東寧府的戶籍與物資。',
            'greeting' => '這本帳冊還得細細核對... 喔？是新來的開拓者嗎？有什麼需要幫忙的？',
            'avatar_id' => $scholarAvatar?->id, // 如果有找到頭像就綁定
            'location' => 'dashboard', // 預設出沒在主畫面
            'is_active' => true,
        ]);

        // 您可以在這裡繼續加其他 NPC
        // Npc::create([...]);

        // 嘗試找一張看起來有書卷氣的頭像給楊英 (例如漢人男性)
        // 如果找不到特定的，就隨便抓一張，或設為 null
        $scholarAvatar = Avatar::where('name', '=', '02JhengChengGone.jpg')
            ->first();

        Npc::create([
            'name' => '鄭成功',
            'title' => '延平王', // 或是用「招討大將軍」
            'description' => '面容冷峻的國姓爺，身披赤金重甲，眉宇間透著復興大明的執念。',
            'greeting' => '驅除韃虜，復我大明！... 壯士既來，願隨我一同收復河山嗎？',
            'avatar_id' => $scholarAvatar?->id, // 如果有找到頭像就綁定
            'location' => '', // 預設出沒在主畫面
            'is_active' => true,
        ]);
    }
}
