<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        // Wipe old records cleanly before regenerating asset inventory
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('rooms')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $rooms = [];

        // 1. GENERATE KOLEJ KASA (MALE) -> 3 Floors (0 to 2), 47 Rooms Per Floor
        for ($floor = 0; $floor <= 2; $floor++) {
            for ($num = 1; $num <= 47; $num++) {
                $paddedNum = str_pad($num, 2, '0', STR_PAD_LEFT); // 1 -> "01"
                $roomID = "K" . $floor . $paddedNum;             // K001, K124, etc.
                $wing = ($num <= 23) ? 'Wing A' : 'Wing B';

                $rooms[] = [
                    'roomID' => $roomID,
                    'buildingName' => 'Kolej Kasa',
                    'wing' => $wing,
                    'minCapacity' => 4,
                    'maxCapacity' => 4,
                    'currentOccupancy' => 0,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        // 2. GENERATE KOLEJ SUTERA (FEMALE) -> 4 Floors (0 to 3), 47 Rooms Per Floor
        for ($floor = 0; $floor <= 3; $floor++) {
            for ($num = 1; $num <= 47; $num++) {
                $paddedNum = str_pad($num, 2, '0', STR_PAD_LEFT);
                $roomID = "S" . $floor . $paddedNum;             // S347, etc.
                $wing = ($num <= 23) ? 'Wing A' : 'Wing B';

                $rooms[] = [
                    'roomID' => $roomID,
                    'buildingName' => 'Kolej Sutera',
                    'wing' => $wing,
                    'minCapacity' => 4,
                    'maxCapacity' => 4,
                    'currentOccupancy' => 0,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        // Insert into database in small rapid chunks
        foreach (array_chunk($rooms, 100) as $chunk) {
            DB::table('rooms')->insert($chunk);
        }
    }
}