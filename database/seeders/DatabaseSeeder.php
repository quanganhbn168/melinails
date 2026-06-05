<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@melinails.cz'],
            [
                'name' => 'Meli Admin',
                'password' => 'admin123',
                'phone' => '+420777768681',
                'address' => 'Uherské Hradiště, Czechia',
            ]
        );

        // Gán role super_admin (dùng cho Filament Shield)
        $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $user->assignRole($role);

        $this->call(MelinailsSeeder::class);
    }
}
