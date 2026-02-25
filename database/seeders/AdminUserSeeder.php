<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek apakah admin sudah ada
        $adminExists = DB::table('users')->where('email', 'admin@belajar.id')->exists();

        if (!$adminExists) {
            DB::table('users')->insert([
                'name' => 'Administrator WAKANDE',
                'email' => 'admin@belajar.id',
                'password' => Hash::make('admin111'),
                'school' => 'none',
                'grade' => 'Admin',
                'phone' => '081234567890',
                'role' => 'admin',
                'is_active' => true,
                'profile_photo' => null,
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            $this->command->info('✅ Admin user berhasil dibuat!');
            $this->command->info('📧 Email: admin@belajar.id');
            $this->command->info('🔑 Password: admin123');
        } else {
            $this->command->info('⚠️ Admin user sudah ada, tidak membuat duplikat.');
        }
    }
}
