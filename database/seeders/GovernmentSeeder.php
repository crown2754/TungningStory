<?php

namespace Database\Seeders;

use App\Models\Government;
use Illuminate\Database\Seeder;

class GovernmentSeeder extends Seeder
{
    public function run(): void
    {
        Government::updateOrCreate(
            ['id' => 1],
            [
                'tax_rate'         => 5.00,
                'public_order'     => 70,
                'land_development' => 30,
                'treasury'         => 10000000,
                'population'       => 50000,
                'military_count'   => 5000,
                'military_food'    => 20000,
            ]
        );
    }
}
