<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder {
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void {
        User::updateOrCreate(
            ['email' => 'admin@conveyance.local'],
            [
                'name'        => 'Admin',
                'password'    => Hash::make( '1234567' ),
                'is_admin'    => true,
                'approved_at' => now(),
            ]
        );
    }
}
