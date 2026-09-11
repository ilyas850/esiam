<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;

class YayasanUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create the Yayasan user
        $user = User::firstOrCreate(
            ['username' => 'yayasan'],
            [
                'name' => 'Yayasan',
                'password' => Hash::make('yayasan123'),
                'role' => 0, // No integer role, uses Spatie role
                'id_user' => 0,
            ]
        );

        // Assign the yayasan role
        if (!$user->hasRole('yayasan')) {
            $user->assignRole('yayasan');
        }

        $this->command->info("Yayasan user created:");
        $this->command->info("  Username: yayasan");
        $this->command->info("  Password: yayasan123");
    }
}
