<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class YayasanRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::firstOrCreate(['name' => 'yayasan']);
        $this->command->info("Role 'yayasan' created successfully.");
    }
}
