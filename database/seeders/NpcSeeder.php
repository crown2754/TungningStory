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
        $scholarAvatar = Avatar::where('name', '=', '01YangYing')->first();
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

        $JhengChengGoneAvatar = Avatar::where('name', '=', '02JhengChengGone')->first();
        Npc::create([
            'name' => '鄭成功',
            'title' => '延平王',
            'description' => '面容冷峻的國姓爺，身披赤金重甲，眉宇間透著復興大明的執念。',
            'greeting' => '驅除韃虜，復我大明！... 壯士既來，願隨我一同收復河山嗎？',
            'avatar_id' => $JhengChengGoneAvatar?->id, // 如果有找到頭像就綁定
            'location' => '', // 預設出沒在主畫面
            'is_active' => true,
        ]);

        $zhengZhilongAvatar = Avatar::where('name', '=', '03ZhengZhiLong')->first();
        Npc::create([
            'name' => '鄭芝龍',
            'title' => '海上梟雄',
            'description' => '明末清初叱吒風雲的海商集團首領。曾為海盜之王，掌控東南沿海貿易與龐大艦隊，富可敵國，為人精明、審時度勢。',
            'greeting' => '這海面上的每一陣風，吹來的都是黃金的味道... 年輕人，你也想在這片怒海中撈點好處嗎？',
            'avatar_id' => $zhengZhilongAvatar?->id,
            'location' => '',
            'is_active' => true,
        ]);

        $YanSiQiAvatar = Avatar::where('name', '=', '04YanSiQi')->first();
        Npc::create([
            'name' => '顏思齊',
            'title' => '開台王',
            'description' => '性格豪爽、膽識過人的海上領袖，人稱「開台王」。率眾渡海來到這片蠻荒之地拓墾建立據點，是早期冒險者們的大哥。',
            'greeting' => '哈哈哈！這片海岸可是不認人的，海風和強敵一樣猛烈。新來的，想在這裡紮根，就得拿出你全部的本事！來烤個火喝杯酒吧！',
            'avatar_id' => $YanSiQiAvatar?->id,
            'location' => '',
            'is_active' => true,
        ]);

        $ahQingAvatar = Avatar::where('name', '05AhQing')->first();
        Npc::create([
            'name' => '水仔阿慶',
            'title' => '大井頭看守者',
            'description' => '在大井頭邊賣水與涼茶的年輕人，笑容底下藏著精明。掌握著重要的淡水補給，每天與各路水手打交道，是碼頭上消息最靈通的情報中心。',
            'greeting' => '嘿！客官，要補給淡水還是來杯降火氣的武夷茶？聽剛靠岸的兄弟說，南邊海上有風暴，勸你得多備兩桶水喔。',
            'avatar_id' => $ahQingAvatar?->id,
            'location' => '',
            'is_active' => true,
        ]);

        $oldManGuoAvatar = Avatar::where('name', '06OldManGuo')->first();
        Npc::create([
            'name' => '郭老爹',
            'title' => '赤崁老農',
            'description' => '1652年郭懷一抗荷起義的倖存親族。平時在赤崁城外默默耕種，性格沉默寡言。對紅毛番懷有深沉的恐懼與敵意，若是遇見鄭家軍，會偷偷給予糧食支援。',
            'greeting' => '...（壓低聲音）... 噓，小聲點，紅毛番的巡邏隊剛過去。如果是為了國姓爺的大軍籌糧，這袋甘藷算你半價... 拿去吧，別聲張。',
            'avatar_id' => $oldManGuoAvatar?->id,
            'location' => '',
            'is_active' => true,
        ]);

        $kamachatAvatar = Avatar::where('name', 'Kamachat')->first();
        Npc::create([
            'name' => '卡瑪恰', // 取自大肚王名諱變體，帶有部族勇士的榮耀感
            'title' => '白晝之鹿', // 象徵他在森林中狩獵的高超技藝
            'description' => '大肚王國的精銳獵手，身上有著部族的榮耀紋面。負責與漢人、紅毛番進行鹿皮貿易，但始終保持著警戒。他的箭術在森林中無人能敵。',
            'greeting' => '停下，外鄉人。這裡是大肚王的獵場。如果你是來搶地盤的，我的箭不長眼；但如果你是來買上好的鹿皮，我們或許可以談談。',
            'avatar_id' => $kamachatAvatar?->id, // 綁定對應的頭像變數
            'location' => 'middag_forest_border', // 建議位置：大肚王國森林邊界 / 或者是 鹿場交易所
            'is_active' => true,
        ]);
        
    }
}
