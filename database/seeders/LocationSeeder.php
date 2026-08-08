<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $locations = [
            ['name' => 'Wuse', 'state' => 'FCT', 'is_active' => true, 'sort_order' => 1],
            ['name' => 'Gwarinpa', 'state' => 'FCT', 'is_active' => true, 'sort_order' => 2],
            ['name' => 'Lugbe', 'state' => 'FCT', 'is_active' => true, 'sort_order' => 3],
            ['name' => 'Kubwa', 'state' => 'FCT', 'is_active' => true, 'sort_order' => 4],
            ['name' => 'Lokogoma', 'state' => 'FCT', 'is_active' => true, 'sort_order' => 5],
            ['name' => 'Maitama', 'state' => 'FCT', 'is_active' => true, 'sort_order' => 6],
            ['name' => 'Garki', 'state' => 'FCT', 'is_active' => true, 'sort_order' => 7],
            ['name' => 'Asokoro', 'state' => 'FCT', 'is_active' => true, 'sort_order' => 8],
            ['name' => 'Jabi', 'state' => 'FCT', 'is_active' => true, 'sort_order' => 9],
            ['name' => 'Utako', 'state' => 'FCT', 'is_active' => true, 'sort_order' => 10],
            ['name' => 'Karu', 'state' => 'FCT', 'is_active' => true, 'sort_order' => 11],
            ['name' => 'Nyanya', 'state' => 'FCT', 'is_active' => true, 'sort_order' => 12],
            ['name' => 'Gwagwalada', 'state' => 'FCT', 'is_active' => true, 'sort_order' => 13],
            ['name' => 'Kuje', 'state' => 'FCT', 'is_active' => true, 'sort_order' => 14],
            ['name' => 'Bwari', 'state' => 'FCT', 'is_active' => true, 'sort_order' => 15],
            ['name' => 'Durumi', 'state' => 'FCT', 'is_active' => true, 'sort_order' => 16],
            ['name' => 'Wuye', 'state' => 'FCT', 'is_active' => true, 'sort_order' => 17],
            ['name' => 'Life Camp', 'state' => 'FCT', 'is_active' => true, 'sort_order' => 18],
            ['name' => 'Katampe', 'state' => 'FCT', 'is_active' => true, 'sort_order' => 19],
            ['name' => 'Apo', 'state' => 'FCT', 'is_active' => true, 'sort_order' => 20],
        ];

        foreach ($locations as $loc) {
            DB::table('locations')->updateOrInsert(
                ['name' => $loc['name'], 'state' => $loc['state']],
                array_merge($loc, ['created_at' => $now, 'updated_at' => $now])
            );
        }
    }
}
