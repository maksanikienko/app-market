<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        // ==================== Создаём роли ====================
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole  = Role::firstOrCreate(['name' => 'user']);

        // ==================== Создаём администратора ====================
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@admin.com'], // уникальное поле для поиска
            [
                'name'              => 'Administrator',
                'password'          => Hash::make('admin'), // поменяй на сильный пароль!
                'email_verified_at' => now(),
                'is_admin'          => 1, // если у тебя ещё осталось это поле
            ]
        );

        // Назначаем роль admin
        if (!$adminUser->hasRole('admin')) {
            $adminUser->assignRole($adminRole);
        }

        // ==================== Обработка legacy пользователей ====================
        User::where('is_admin', 1)->each(function (User $user) use ($adminRole) {
            if (!$user->hasRole('admin')) {
                $user->assignRole($adminRole);
            }
        });

        $this->command->info('Roles and Admin user created successfully!');
        $this->command->info('Admin login: admin@admin.com');
        $this->command->info('Password: password');
    }
}