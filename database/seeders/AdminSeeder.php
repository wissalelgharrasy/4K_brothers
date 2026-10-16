<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@4kbrothers.ma'],
            [
                'name' => 'Administrateur 4K Brothers',
                'password' => Hash::make('admin1234'),
                'email_verified_at' => now(),
            ]
        );
    }
}
