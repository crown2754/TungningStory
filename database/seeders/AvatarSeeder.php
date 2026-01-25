<?php

namespace Database\Seeders;

use App\Models\Avatar;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class AvatarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. 設定圖片來源資料夾 (public/images/avatar)
        $directory = public_path('images/avatar');

        // 檢查資料夾是否存在，不存在就建立 (避免報錯)
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
            $this->command->info("已建立資料夾: {$directory}，請將圖片放入此處後再次執行 Seeder。");
            return;
        }

        // 2. 掃描資料夾內的所有檔案
        $files = File::files($directory);

        if (empty($files)) {
            $this->command->warn("資料夾 {$directory} 是空的，沒有匯入任何頭像。");
            return;
        }

        $count = 0;
        foreach ($files as $file) {
            $filename = $file->getFilename();
            // 產生網頁可讀取的路徑
            $webPath = "/images/avatar/{$filename}";

            // 使用檔名 (不含副檔名) 作為頭像名稱
            // 例如: "male_han_01.png" -> 名稱: "male_han_01"
            $name = pathinfo($filename, PATHINFO_FILENAME);

            // 3. 寫入資料庫 (使用 firstOrCreate 避免重複)
            Avatar::firstOrCreate(
                ['path' => $webPath], // 如果路徑已存在就不重複新增
                [
                    'name' => $name,
                    'is_active' => true,
                ]
            );
            $count++;
        }

        $this->command->info("成功匯入 {$count} 張頭像！");
    }
}
