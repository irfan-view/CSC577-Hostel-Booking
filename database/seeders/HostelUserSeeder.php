<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HostelUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create the master User record
        DB::table('hostel_users')->insert([
            'userID' => '2024669856',
            'userName' => 'ahmad_irfan',
            'passwordHash' => Hash::make('password123'), // Securely encrypts 'password123'
            'accountStatus' => 'Active',
            'strikeCount' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Create the linked Student profile record
        DB::table('students')->insert([
            'userID' => '2024669856',
            'programCode' => 'CS241',
            'email' => '2024669856@student.uitm.edu.my',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}