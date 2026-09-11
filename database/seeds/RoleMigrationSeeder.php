<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\User;

class RoleMigrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Create Roles
        $roles = [
            1 => 'sadmin',
            2 => 'dosen',
            3 => 'mhs',
            4 => 'no_mhs',
            5 => 'dosen_luar',
            6 => 'kaprodi',
            7 => 'wadir1',
            8 => 'bauk',
            9 => 'admin_prodi',
            10 => 'wadir3',
            11 => 'prausta',
            12 => 'gugus_mutu',
        ];

        foreach ($roles as $id => $name) {
            Role::firstOrCreate(['name' => $name]);
        }

        // 2. Assign Roles to Users
        $users = User::all();
        $count = 0;

        foreach ($users as $user) {
            if (isset($roles[$user->role])) {
                $roleName = $roles[$user->role];

                // Assign role if not already assigned
                if (!$user->hasRole($roleName)) {
                    $user->assignRole($roleName);
                    $count++;
                }
            }
        }

        $this->command->info("Successfully migrated roles for {$count} users.");
    }
}
