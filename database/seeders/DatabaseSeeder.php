<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            GameSettingSeeder::class,
        ]);

        // 建立預設運營管理員 (OM)
        User::factory()->create([
            'name' => '運營管理員',
            'email' => 'service@gkgary.com',
            'password' => Hash::make('game@1234'), // 建議登入後立即更改
            'role' => User::ROLE_OM, // 使用模型中定義的常量
            'gold' => 100000,
            'stamina' => 100,
            'max_stamina' => 100,
            'job' => '平民',
        ]);

        // 也可以建立一個測試用的一般玩家
        User::factory()->create([
            'name' => '開拓者一號',
            'email' => 'gm@gkgary.com',
            'password' => Hash::make('game@1234'), // 建議登入後立即更改
            'role' => User::ROLE_GM,
            'gold' => 100000,
            'stamina' => 100,
            'max_stamina' => 100,
            'job' => '平民',
        ]);

        // 也可以建立一個測試用的一般玩家
        User::factory()->create([
            'name' => '開拓者一號',
            'email' => 'player@gkgary.com',
            'password' => Hash::make('game@1234'), // 建議登入後立即更改
            'role' => User::ROLE_PLAYER,
            'gold' => 100000,
            'stamina' => 100,
            'max_stamina' => 100,
            'job' => '平民',
        ]);
    }
}
