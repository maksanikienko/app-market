<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            ['name' => 'Магазин "Elat"', 'type' => 'store',     'address' => 'Bulevardul Decebal 99, Chișinău', 'is_active' => true],
            ['name' => 'Основной склад',            'type' => 'warehouse', 'address' => null,                             'is_active' => true],
        ];

        foreach ($locations as $data) {
            Location::firstOrCreate(['name' => $data['name']], $data);
        }
    }
}
