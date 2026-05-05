<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'user']);

        // Assign admin role to users who have is_admin = 1 (legacy field)
        User::where('is_admin', 1)->each(function (User $user) use ($adminRole) {
            if (!$user->hasRole('admin')) {
                $user->assignRole($adminRole);
            }
        });

        $this->command->info('Roles created. Admin role assigned to ' . User::where('is_admin', 1)->count() . ' user(s).');
    }
}
